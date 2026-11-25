<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Moves the customer/editor upload folders from storage/app/public to S3.
 *
 * Files keep the same path ("artwork/abc.jpg" -> S3 key "artwork/abc.jpg"), so
 * the relative paths stored in quotes, cart items and client stories need no
 * change. Editor images are embedded as full URLs in HTML, so those URLs are
 * rewritten to the S3 URL once the file is confirmed on S3.
 *
 * Client-story images that also have a media-library record get a copy at
 * that record's own S3 key (products/client_stories/{id}/{file}) when it is
 * missing there, because the site falls back to that key once the local file
 * is gone.
 *
 * Folders that finish without failures are recorded in the "uploads_on_s3"
 * setting; from then on upload_url() links to S3 for them even while local
 * copies are still on the server.
 *
 * Safe to run repeatedly: files already on S3 with the same size are skipped.
 * Local files are only removed with --delete-local, and only when the S3 copy
 * has the same size.
 */
class MigrateUploadsToS3 extends Command
{
    protected $signature = 'uploads:migrate-to-s3
                            {--dry-run : Report what would happen without uploading, rewriting or deleting anything}
                            {--dir=* : Folder(s) under storage/app/public to migrate (default: artwork, client_stories, editor-images, quill-images)}
                            {--limit=0 : Stop after uploading this many files (0 = no limit), to keep a run short}
                            {--delete-local : Delete local files whose S3 copy is confirmed (same size)}
                            {--skip-urls : Do not rewrite editor image URLs in page content}';

    protected $description = 'Copy upload folders (artwork, client stories, editor images) from local storage to S3';

    public const DEFAULT_DIRS = ['artwork', 'client_stories', 'editor-images', 'quill-images'];

    /** Setting holding the folders confirmed complete on S3 (read by upload_url()). */
    public const ON_S3_SETTING = 'uploads_on_s3';

    /** Real uploads only: anything else (e.g. probe .txt/.html files) is reported and left alone. */
    public const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'jfif', 'png', 'gif', 'webp', 'avif', 'svg', 'pdf', 'ai', 'psd', 'cdr', 'eps'];

    /** Content types accepted for files without an extension. */
    public const ALLOWED_MIME_PREFIXES = ['image/', 'application/pdf', 'application/postscript', 'application/illustrator', 'application/vnd.adobe.photoshop'];

    /** Folders whose files are embedded as URLs in HTML content. */
    public const URL_DIRS = ['editor-images', 'quill-images'];

    /** HTML/text columns that can contain editor image URLs (missing ones are ignored). */
    public const CONTENT_COLUMNS = [
        'blogs' => ['blog_ldis'],
        'products' => ['product_description', 'product_detail', 'faqs'],
        'categories' => ['description', 'detail', 'faqs'],
        'product_variations' => ['specifications'],
        'settings' => ['value'],
    ];

    protected array $stats = [];

    /** @var array<string,int> S3 key => size, for every object under the migrated prefixes. */
    protected array $s3Index = [];

    /** @var array<string,true> S3 prefixes already listed into $s3Index. */
    protected array $indexedPrefixes = [];

    /** @var \Illuminate\Support\Collection|null client_stories media rows on S3, grouped by file name. */
    protected $storyMedia = null;

    public function handle(): int
    {
        $lock = Cache::lock('uploads-migrate-to-s3', 6 * 3600);

        if (! $lock->get()) {
            $this->error('Another run is still in progress. Try again when it has finished.');

            return self::FAILURE;
        }

        try {
            return $this->migrate();
        } finally {
            $lock->release();
        }
    }

    protected function migrate(): int
    {
        // Laravel reuses the command object when it is called more than once in a process.
        $this->s3Index = [];
        $this->indexedPrefixes = [];
        $this->storyMedia = null;
        $this->stats = [
            'uploaded' => 0, 'already_on_s3' => 0, 'library_copies' => 0, 'skipped_type' => 0, 'skipped_empty' => 0,
            'failed' => 0, 'deleted_local' => 0, 'kept_local' => 0, 'rows_rewritten' => 0, 'urls_rewritten' => 0,
        ];

        $dryRun = (bool) $this->option('dry-run');
        $dirs = array_values(array_filter((array) $this->option('dir'))) ?: self::DEFAULT_DIRS;
        $limit = max(0, (int) $this->option('limit'));
        $local = Storage::disk('public');
        $s3 = Storage::disk('s3');

        $this->info(($dryRun ? '[DRY RUN] ' : '') . 'Migrating ' . implode(', ', $dirs) . ' to S3 bucket "' . config('filesystems.disks.s3.bucket') . '"');

        $completed = [];

        foreach ($dirs as $dir) {
            $this->indexS3($s3, $dir);
            $failedBefore = $this->stats['failed'];

            if (! $local->exists($dir)) {
                $this->line("  {$dir}: no local folder, nothing to upload");
                $completed[] = $dir;
                continue;
            }

            $files = array_values(array_filter($local->allFiles($dir), fn ($f) => ! str_starts_with(basename($f), '.')));
            $this->line("  {$dir}: " . count($files) . ' local file(s)');

            foreach ($files as $i => $path) {
                if ($limit && $this->stats['uploaded'] >= $limit) {
                    $this->warn("  Upload limit of {$limit} reached; run again to continue.");
                    break 2;
                }

                $this->migrateFile($local, $s3, $path, $dryRun);

                if (($i + 1) % 100 === 0) {
                    $this->line('    ...' . ($i + 1) . ' / ' . count($files));
                }
            }

            // Reached only when the whole folder was processed (an upload limit breaks out above).
            if ($this->stats['failed'] === $failedBefore) {
                $completed[] = $dir;
            }
        }

        if (! $dryRun && $completed) {
            $this->markOnS3($completed);
        }

        if (! $this->option('skip-urls')) {
            $this->rewriteContentUrls($s3, $dryRun);
        }

        $this->newLine();
        $this->table(['Result', 'Count'], collect($this->stats)->map(fn ($v, $k) => [str_replace('_', ' ', $k), $v])->values()->all());

        Log::info('uploads:migrate-to-s3 ' . ($dryRun ? '(dry run) ' : '') . json_encode($this->stats));

        if (! $dryRun && ! $this->option('delete-local') && $this->stats['kept_local'] > 0) {
            $this->line('Local copies were kept. When the site looks right, run again with --delete-local to remove them.');
        }

        return $this->stats['failed'] > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Record folders whose every file is confirmed on S3, so links switch to S3.
     */
    protected function markOnS3(array $dirs): void
    {
        $setting = Setting::firstOrNew(['key' => self::ON_S3_SETTING]);
        $current = (array) json_decode((string) $setting->value, true);
        $setting->value = json_encode(array_values(array_unique(array_merge($current, $dirs))));
        $setting->save();

        Cache::forget('sys_settings');
        clear_response_cache();

        $this->info('Links now use S3 for: ' . implode(', ', $dirs));
    }

    /**
     * One listing per folder instead of one request per file.
     */
    protected function indexS3($s3, string $dir): void
    {
        if (isset($this->indexedPrefixes[$dir])) {
            return;
        }

        $this->indexedPrefixes[$dir] = true;

        foreach ($s3->getDriver()->listContents($dir, true) as $item) {
            if ($item->isFile()) {
                $this->s3Index[$item->path()] = (int) $item->fileSize();
            }
        }
    }

    protected function migrateFile($local, $s3, string $path, bool $dryRun): void
    {
        $size = $local->size($path);

        if ($size === 0) {
            $this->stats['skipped_empty']++;
            $this->line("    skipped (empty file): {$path}");

            return;
        }

        $contentType = $this->allowedContentType($local, $path);

        if ($contentType === null) {
            $this->stats['skipped_type']++;
            $this->line("    skipped (not an image/document): {$path}");

            return;
        }

        if (($this->s3Index[$path] ?? null) === $size) {
            $this->stats['already_on_s3']++;
        } elseif ($dryRun) {
            $this->stats['uploaded']++;
            $this->s3Index[$path] = $size; // so the URL-rewrite preview includes it
        } else {
            try {
                $stream = $local->readStream($path);
                $options = ['ContentType' => $contentType];

                // An SVG opened straight from the bucket must not run scripts; <img> use is unaffected.
                if ($contentType === 'image/svg+xml') {
                    $options['ContentDisposition'] = 'attachment';
                }

                $s3->put($path, $stream, $options);

                if (is_resource($stream)) {
                    fclose($stream);
                }

                $uploadedSize = $s3->size($path);

                if ($uploadedSize !== $size) {
                    throw new \RuntimeException("size mismatch after upload ({$uploadedSize} vs {$size} bytes)");
                }

                $this->s3Index[$path] = $uploadedSize;
                $this->stats['uploaded']++;
            } catch (\Throwable $e) {
                $this->stats['failed']++;
                $this->error("    failed: {$path} - {$e->getMessage()}");

                return;
            }
        }

        $libraryOk = $this->ensureMediaLibraryCopies($s3, $path, $dryRun);

        if (! $this->option('delete-local')) {
            $this->stats['kept_local']++;

            return;
        }

        // Only delete when S3 holds a copy of the same size (and any library copies).
        if (! $dryRun && $libraryOk && ($this->s3Index[$path] ?? null) === $size) {
            $local->delete($path);
            $this->stats['deleted_local']++;
        } elseif ($dryRun) {
            $this->stats['deleted_local']++;
        } else {
            $this->stats['kept_local']++;
        }
    }

    /**
     * Copy a client_stories file to the S3 key of every media-library row that
     * uses it, when that key is missing. Returns false if a copy failed.
     */
    protected function ensureMediaLibraryCopies($s3, string $path, bool $dryRun): bool
    {
        if (! str_starts_with($path, 'client_stories/') || ! Schema::hasTable('media')) {
            return true;
        }

        $this->storyMedia ??= Media::where('collection_name', 'client_stories')
            ->where('disk', 's3')
            ->get()
            ->groupBy('file_name');

        $generator = app(config('media-library.path_generator'));
        $prefix = trim((string) config('media-library.prefix', ''), '/');
        $ok = true;

        foreach ($this->storyMedia->get(basename($path), collect()) as $media) {
            $key = ltrim(($prefix !== '' ? "{$prefix}/" : '') . $generator->getPath($media) . $media->file_name, '/');

            // List the library folder once (e.g. "products/client_stories") rather than per file.
            $this->indexS3($s3, implode('/', array_slice(explode('/', $key), 0, -2)));

            if (isset($this->s3Index[$key])) {
                continue;
            }

            $this->stats['library_copies']++;

            if ($dryRun) {
                continue;
            }

            try {
                $s3->copy($path, $key);
                $this->s3Index[$key] = $s3->size($key);
            } catch (\Throwable $e) {
                $ok = false;
                $this->stats['failed']++;
                $this->error("    failed library copy: {$key} - {$e->getMessage()}");
            }
        }

        return $ok;
    }

    /**
     * The content type to upload with, or null when the file is not a real upload.
     */
    protected function allowedContentType($local, string $path): ?string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $detected = (string) $local->mimeType($path);

        if ($extension !== '') {
            if (! in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
                return null;
            }

            if ($extension === 'svg') {
                return 'image/svg+xml';
            }

            return $detected ?: 'application/octet-stream';
        }

        // No extension (e.g. an old artwork upload saved as "3735826"): judge by content.
        foreach (self::ALLOWED_MIME_PREFIXES as $prefix) {
            if (str_starts_with($detected, $prefix) && $detected !== 'image/svg+xml') {
                return $detected;
            }
        }

        return null;
    }

    /**
     * Point embedded editor image URLs (".../storage/editor-images/x.png") at S3,
     * but only for files that are confirmed on S3.
     */
    protected function rewriteContentUrls($s3, bool $dryRun): void
    {
        $dirs = implode('|', array_map('preg_quote', self::URL_DIRS));
        // Matches absolute or root-relative links, plain or JSON-escaped ("\/").
        $pattern = '~(?:https?:\\\\?/\\\\?/[^\s"\'<>()\\\\]+)?\\\\?/storage\\\\?/(' . $dirs . ')\\\\?/([A-Za-z0-9._-]+)~';

        foreach (self::CONTENT_COLUMNS as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    continue;
                }

                // Coarse filter only; the pattern below decides what is an image URL.
                $rows = DB::table($table)->select('id', $column)->where(function ($q) use ($column) {
                    foreach (self::URL_DIRS as $dir) {
                        $q->orWhere($column, 'like', "%{$dir}%");
                    }
                })->get();

                foreach ($rows as $row) {
                    $count = 0;
                    $new = preg_replace_callback($pattern, function ($m) use ($s3, &$count) {
                        $key = "{$m[1]}/{$m[2]}";

                        if (! isset($this->s3Index[$key])) {
                            return $m[0];
                        }

                        $count++;
                        $url = $s3->url($key);

                        // Keep JSON-escaped slashes in JSON columns.
                        return str_contains($m[0], '\\/') ? str_replace('/', '\\/', $url) : $url;
                    }, (string) $row->{$column});

                    if ($count === 0 || $new === $row->{$column}) {
                        continue;
                    }

                    $this->stats['rows_rewritten']++;
                    $this->stats['urls_rewritten'] += $count;
                    $this->line("    " . ($dryRun ? 'would rewrite' : 'rewrote') . " {$count} image URL(s) in {$table}.{$column} #{$row->id}");

                    if (! $dryRun) {
                        DB::table($table)->where('id', $row->id)->update([$column => $new]);
                    }
                }
            }
        }

        // Cached settings and cached pages still hold the old URLs.
        if (! $dryRun && $this->stats['rows_rewritten'] > 0) {
            Cache::forget('sys_settings');
            clear_response_cache();
        }
    }
}
