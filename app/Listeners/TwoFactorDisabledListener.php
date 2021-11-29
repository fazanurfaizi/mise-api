<?php

namespace App\Listeners;

use App\Notifications\TwoFactorAuthenticationWasDisabledNotification;
use App\Events\TwoFactor\TwoFactorDisabled;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Notification;

class TwoFactorDisabledListener
{
    use Queueable;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('notifications');
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TwoFactorDisabled $event)
    {
        Notification::send($event->user, new TwoFactorAuthenticationWasDisabledNotification());
    }
}
