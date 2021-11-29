<?php

namespace App\Models\Auth;

use App\Contracts\Auth\TwoFactorTotp;
use App\Traits\TwoFactor\HandlesCodes;
use App\Traits\TwoFactor\HandlesRecoveryCodes;
use App\Traits\TwoFactor\HandlesSafeDevices;
use App\Traits\TwoFactor\SerializesSharedSecret;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ParagonIE\ConstantTime\Base32;

class TwoFactorAuthentication extends Model implements TwoFactorTotp
{
    use HasFactory,
        HandlesCodes,
        HandlesRecoveryCodes,
        HandlesSafeDevices,
        SerializesSharedSecret;

    /**
     * The attributes that shoul be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'shared_secret' => 'encrypted',
        'authenticatable_id' => 'int',
        'digits' => 'int',
        'seconds' => 'int',
        'window' => 'int',
        'recovery_codes' => 'encrypted:collection',
        'safe_devices' => 'collection',
        'enabled_at' => 'datetime',
        'recovery_codes_generated_at' => 'datetime'
    ];

    /**
     * The model that uses Two-Factor Authentication.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function authenticatable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('authenticatable');
    }

    /**
     * Sets the Algorithm to lowercase.
     *
     * @param $value
     *
     * @return void
     */
    protected function setAlgorithmAttribute($value): void
    {
        $this->attributes['algorithm'] = strtolower($value);
    }

    /**
     * Returns if the Two-Factor Authentication has been enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled_at !== null;
    }

    /**
     * Returns if the Two-Factor Authentication is not been enabled.
     *
     * @return bool
     */
    public function isDisabled(): bool
    {
        return !$this->isEnabled();
    }

    /**
     * Flushes all authentication data and cycles the shared secret.
     *
     * @return $this
     */
    public function flushAuth(): static
    {
        $this->recovery_codes_generated_at = null;
        $this->safe_devices = null;
        $this->enabled_at = null;

        $this->attributes = array_merge($this->attributes, config('auth2fa.totp'));

        $this->shared_secret = static::generateRandomSecret();
        $this->recovery_codes = null;

        return $this;
    }

    /**
     * Creates a new random secret.
     *
     * @return string
     */
    public static function generateRandomSecret(): string
    {
        return Base32::encodeUpper(
            random_bytes(config('auth2fa.secret_length'))
        );
    }
}
