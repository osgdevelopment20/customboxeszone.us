<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Builds the list of URLs that can be pushed to the Google Indexing API,
 * either by reading the generated sitemaps or straight from the database.
 */
class IndexableUrlCollector
{
    /**
     * @param  string  $source  sitemap|database|both
     * @return string[]
     */
    public function collect(string $source = 'both', ?Carbon $since = null): array
    {
        $urls = match ($source) {
            'sitemap'  => $this->fromSitemaps(),
            'database' => $this->fromDatabase($since),
            default    => array_merge($this->fromDatabase($since), $this->fromSitemaps()),
        };

        return array_values(array_unique(array_filter($urls)));
    }

    /* -----------------------------------------------------------------
     |  Sitemaps
     | -----------------------------------------------------------------
     */

    /**
     * @return string[]
     */
    public function fromSitemaps(): array
    {
        $urls = [];

        foreach ((array) config('google-indexing.sources.sitemap.files', []) as $file) {
            if (is_file($file)) {
                $urls = array_merge($urls, $this->parseSitemap((string) file_get_contents($file)));
            }
        }

        foreach ((array) config('google-indexing.sources.sitemap.urls', []) as $url) {
            $urls = array_merge($urls, $this->fromSitemapUrl($url));
        }

        return $urls;
    }

    /**
     * Fetch a remote sitemap (sitemap index files are followed one level deep).
     *
     * @return string[]
     */
    public function fromSitemapUrl(string $url, int $depth = 0): array
    {
        try {
            $response = Http::timeout(30)->get($url);

            if ($response->failed()) {
                Log::warning('Google Indexing: could not download sitemap ' . $url . ' (HTTP ' . $response->status() . ')');

                return [];
            }

            return $this->parseSitemap($response->body(), $depth);
        } catch (\Throwable $e) {
            Log::warning('Google Indexing: sitemap ' . $url . ' failed - ' . $e->getMessage());

            return [];
        }
    }

    /**
     * @return string[]
     */
    public function parseSitemap(string $xml, int $depth = 0): array
    {
        $previous = libxml_use_internal_errors(true);

        try {
            $parsed = simplexml_load_string($xml);
        } catch (\Throwable $e) {
            $parsed = false;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        if ($parsed === false) {
            return [];
        }

        $urls = [];
        $isIndex = strtolower($parsed->getName()) === 'sitemapindex';

        foreach ($parsed->children() as $node) {
            $loc = trim((string) $node->loc);

            if ($loc === '') {
                continue;
            }

            if ($isIndex) {
                if ($depth < 2) {
                    $urls = array_merge($urls, $this->fromSitemapUrl($loc, $depth + 1));
                }

                continue;
            }

            $urls[] = $loc;
        }

        return $urls;
    }

    /* -----------------------------------------------------------------
     |  Database
     | -----------------------------------------------------------------
     */

    /**
     * Static routes plus every published product / category / blog.
     *
     * @param  Carbon|null  $since  only include records updated after this date
     * @return string[]
     */
    public function fromDatabase(?Carbon $since = null): array
    {
        $urls = [];

        if ($since === null) {
            foreach ((array) config('google-indexing.sources.database.routes', []) as $name) {
                try {
                    $urls[] = route($name);
                } catch (\Throwable $e) {
                    Log::debug('Google Indexing: route "' . $name . '" could not be resolved.');
                }
            }
        }

        foreach ((array) config('google-indexing.sources.database.models', []) as $model => $config) {
            $urls = array_merge($urls, $this->fromModel($model, $config, $since));
        }

        return $urls;
    }

    /**
     * @return string[]
     */
    protected function fromModel(string $model, array $config, ?Carbon $since = null): array
    {
        $urls = [];

        try {
            $query = $model::query();

            if (method_exists($model, 'scopeActive')) {
                $query->active();
            }

            if ($since !== null) {
                $query->where('updated_at', '>=', $since);
            }

            $slugColumn = $config['slug'] ?? 'slug';
            $routeName = $config['route'];

            $query->select(['id', $slugColumn, 'updated_at'])
                ->orderByDesc('updated_at')
                ->chunk(500, function ($records) use (&$urls, $routeName, $slugColumn) {
                    foreach ($records as $record) {
                        $slug = $record->{$slugColumn};

                        if (! empty($slug)) {
                            $urls[] = route($routeName, $slug);
                        }
                    }
                });
        } catch (\Throwable $e) {
            Log::warning('Google Indexing: could not collect URLs from ' . class_basename($model) . ' - ' . $e->getMessage());
        }

        return $urls;
    }

    /* -----------------------------------------------------------------
     |  Manual input
     | -----------------------------------------------------------------
     */

    /**
     * Turn a pasted textarea (one URL per line) into a clean list.
     *
     * @return string[]
     */
    public function fromText(?string $text): array
    {
        if (empty($text)) {
            return [];
        }

        $urls = preg_split('/[\r\n,\s]+/', $text) ?: [];

        return array_values(array_unique(array_filter(array_map('trim', $urls), fn ($url) => Str::startsWith($url, ['http://', 'https://']))));
    }
}
