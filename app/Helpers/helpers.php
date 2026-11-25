<?php

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


if (! function_exists('sys_settings')) {
    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    function sys_settings($key, $default = null)
    {
        $settings = Cache::remember('sys_settings', 1800, function () {
            return Setting::all()->pluck('value', 'key');
        });

        $value = $settings->get($key) ?? $default;

        return is_json($value) ? json_decode($value, true) : $value;
    }
}
if (! function_exists('is_json')) {
    /**
     * check json value
     * @param $string, $decoded
     */
    function is_json($string, $decoded = false)
    {
        if (is_array($string)) {
            return false;
        }
        json_decode($string);
        $check = (json_last_error() == JSON_ERROR_NONE);

        if ($decoded && $check) {
            return json_decode($string);
        }

        return $check;
    }
}

if (! function_exists('clear_response_cache')) {
    function clear_response_cache(): void
    {
        if (app()->bound('responsecache')) {
            app('responsecache')->clear();
        }
    }
}

if (! function_exists('merchant_contact_email')) {
    function merchant_contact_email(): string
    {
        return (string) sys_settings('email', 'info@customboxeszone.com');
    }
}

if (! function_exists('merchant_contact_phone')) {
    function merchant_contact_phone(): string
    {
        return (string) sys_settings('phone', '(800) 203-6657');
    }
}

if (! function_exists('business_addresses')) {
    /**
     * Business addresses from Dashboard > Settings (Address 1-3), in order,
     * leaving out the ones that are empty.
     */
    function business_addresses(): array
    {
        $addresses = array_map(fn ($key) => trim((string) sys_settings($key)), ['address', 'address_2', 'address_3']);

        return array_values(array_filter($addresses, 'strlen'));
    }
}

if (! function_exists('merchant_policy_content')) {
    function merchant_policy_content(string $key, string $fallback = ''): string
    {
        $content = (string) sys_settings($key, $fallback);
        $contact = merchant_contact_email() . ' or ' . merchant_contact_phone();

        return str_replace(
            ['[Insert Email/Phone]', '[Insert Email]', '[Insert Phone]'],
            [$contact, merchant_contact_email(), merchant_contact_phone()],
            $content
        );
    }
}

if (! function_exists('store_artwork_upload')) {
    /**
     * Store a customer artwork upload on S3.
     *
     * The "mimes" validation rule only sniffs file content, so a JPEG/PHP
     * polyglot named "x.pht" passes it. Never reuse the client's extension
     * unless it is on this allow-list, and never reuse the client's filename.
     *
     * @return string|null Path on S3 (e.g. "artwork/abc.jpg"), or null if rejected.
     */
    function store_artwork_upload(UploadedFile $file, string $directory = 'artwork'): ?string
    {
        $allowed = ['jpeg', 'jpg', 'jfif', 'png', 'gif', 'webp', 'pdf', 'ai', 'psd', 'cdr', 'svg'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, $allowed, true)) {
            return null;
        }

        $options = ['disk' => 's3'];

        // An SVG opened straight from the bucket must not run scripts.
        if ($extension === 'svg') {
            $options['ContentDisposition'] = 'attachment';
        }

        $path = $file->storeAs($directory, Str::random(40) . '.' . $extension, $options);

        // Livewire's storeAs() returns the path even when the S3 write failed,
        // which left quotes pointing at artwork that was never saved.
        if (! $path || ! Storage::disk('s3')->exists($path)) {
            Log::error('Artwork upload to S3 failed', ['path' => $path, 'original_name' => $file->getClientOriginalName()]);

            return null;
        }

        return $path;
    }
}

if (! function_exists('upload_url')) {
    /**
     * Public URL for an uploaded file stored by relative path ("artwork/abc.jpg",
     * "client_stories/xyz.webp", "editor-images/...").
     *
     * New uploads live on S3. Folders that `php artisan uploads:migrate-to-s3`
     * has confirmed complete (the "uploads_on_s3" setting) always link to S3.
     * For any other folder a file still present in storage/app/public is
     * served from there, since it may not have been copied yet.
     */
    function upload_url(?string $path): string
    {
        if (! $path) {
            return '';
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $path = ltrim($path, '/');

        $folder = strtok($path, '/');

        if (in_array($folder, (array) sys_settings('uploads_on_s3', []), true) || ! Storage::disk('public')->exists($path)) {
            return Storage::disk('s3')->url($path);
        }

        return Storage::disk('public')->url($path);
    }
}
