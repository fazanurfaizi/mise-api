<?php

namespace App\Listeners;

use App\Notifications\RecoveryCodesGeneratedNotification;
use App\Events\TwoFactor\TwoFactorRecoveryCodesGenerated;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Notification;

class TwoFactorRecoveryCodesGeneratedListener
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
    public function handle(TwoFactorRecoveryCodesGenerated $event)
    {
        Notification::send($event->user, new RecoveryCodesGeneratedNotification($event->user->getRecoveryCodes()));
    }
}
