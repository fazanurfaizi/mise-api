<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecoveryCodesGeneratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $recoveryCodes;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($recoveryCodes)
    {
        $this->recoveryCodes = $recoveryCodes;
        $this->onQueue('notifications');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->markdown('emails.default', [
                'email' => $notifiable->email,
                'recoveryCodes' => $this->recoveryCodes
            ])
            ->success()
            ->subject(__(':app_name - Two Factor Authentication Generated', ['app_name' => config('app.name')]))
            ->greeting(__('Activate 2-Step Verification - Google Authenticator'))
            ->line('<b>' . __('5 Security Tips') . '</b>')
            ->line('<small>' . __('DO NOT give your password to anyone!') . '<br>' .
                __(
                    'DO NOT call any phone number for someone clainming to be :app_name support!',
                    ['app_name' => config('app.name')]
                ) . '<br>' .
                __(
                    'DO NOT send any money to anyone clainming to be a member of :app_name!',
                    ['app_name' => config('app.name')]
                ) . '<br>' .
                __('Enable Two Factor Authentication!') . '<br>' .
                __('Make sure you are visiting :app_url', ['app_url' => config('app.url')]) . '</small>');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
