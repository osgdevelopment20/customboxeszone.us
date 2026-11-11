<?php

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
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
     * Store a customer artwork upload on the public disk.
     *
     * The "mimes" validation rule only sniffs file content, so a JPEG/PHP
     * polyglot named "x.pht" passes it. Never reuse the client's extension
     * unless it is on this allow-list, and never reuse the client's filename.
     *
     * @return string|null Path on the public disk, or null if rejected.
     */
    function store_artwork_upload(UploadedFile $file, string $directory = 'artwork'): ?string
    {
        $allowed = ['jpeg', 'jpg', 'jfif', 'png', 'gif', 'webp', 'pdf', 'ai', 'psd', 'cdr', 'svg'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, $allowed, true)) {
            return null;
        }

        return $file->storeAs($directory, Str::random(40) . '.' . $extension, 'public') ?: null;
    }
}
