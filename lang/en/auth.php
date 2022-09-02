<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    '2fa' => [
        'title'    => 'Two-Factor Authentication',
        'required' => 'Two-Factor Authentication is required.',
        'back'     => 'Go back',
        'continue' => 'To continue, open up your Authenticator app and issue your 2FA code.',
        'enable'   => 'You need to enable Two-Factor Authentication.',

        'fail_confirm' => 'The code to activate Two-Factor Authentication is invalid.',
        'enabled'      => 'Two-Factor Authentication has been enabled for your account.',
        'disabled'     => 'Two-Factor Authentication has been disabled for your account.',

        'safe_device' => 'We won\'t ask you for Two-Factor Authentication codes in this device for some time.',

        'confirm'   => 'Confirm code',
        'switch_on' => 'Go to enable Two-Factor Authentication.',

        'recovery_code' => [
            'used'      => 'You have used a Recovery Code. Remember to regenerate them if you have used almost all.',
            'depleted'  => 'You have used all your Recovery Codes. Please use alternate authentication methods to continue.',
            'generated' => 'You have generated a new set of Recovery Codes. Any previous set of codes have been invalidated.',
        ],
    ]

];
