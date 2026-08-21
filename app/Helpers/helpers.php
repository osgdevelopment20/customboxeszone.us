<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;


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
