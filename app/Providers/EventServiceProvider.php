<?php

namespace App\Providers;

use App\Events\TwoFactor\TwoFactorRecoveryCodesGenerated;
use App\Listeners\UserRegisteredListener;
use App\Listeners\TwoFactorRecoveryCodesGeneratedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            UserRegisteredListener::class,
        ],

        TwoFactorRecoveryCodesGenerated::class => [
            TwoFactorRecoveryCodesGeneratedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
