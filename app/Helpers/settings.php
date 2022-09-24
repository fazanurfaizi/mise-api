<?php

use App\Models\System\Setting;
use Illuminate\Support\Facades\Cache;

if(!function_exists('mise_setting')) {
    /**
     * Return mise setting from the setting table.
     *
     * @return string|null
     */
    function mise_setting(string $key): ?string
    {
        $setting = Cache::remember("mise-setting-{$key}", 60 * 60 * 24, fn () => Setting::query()->where('key', $key)->first());

        return $setting?->value;
    }
}
