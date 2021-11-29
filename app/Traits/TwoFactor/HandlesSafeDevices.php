<?php

namespace App\Traits\TwoFactor;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

trait HandlesSafeDevices
{
    /**
     * Returns the timestamp of the safe device.
     *
     * @param null|string $token
     *
     * @return null|\Illuminate\Support\Carbon
     */
    public function getSafeDeviceTimestamp(string $token = null): ?Carbon
    {
        if($token && $device = $this->safe_devices?->firstWhere('2fa_remember', $token)) {
            return Carbon::createFromTimestamp($device['added_at']);
        }

        return null;
    }

    /**
     * Generates a device token to bypass Two-Factor Authentication.
     *
     * @return string
     */
    public static function generateDefaultTwoFactorRemember(): string
    {
        return Str::random(100);
    }
}
