<?php

namespace App\Events\TwoFactor;

use App\Contracts\Auth\TwoFactorAuthenticatable;
use Illuminate\Queue\SerializesModels;

class TwoFactorDisabled
{
    use SerializesModels;

    /**
     * The user using Two-Factor Authentication.
     *
     * @var \App\Contracts\Auth\TwoFactorAuthenticatable
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TwoFactorAuthenticatable $user)
    {
        $this->user = $user;
    }
}
