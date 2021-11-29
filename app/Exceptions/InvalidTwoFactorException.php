<?php

namespace App\Exceptions;

use App\Contracts\Auth\TwoFactorAuthenticatable;
use Illuminate\Validation\ValidationException;

class InvalidTwoFactorException extends ValidationException
{
    /**
     * Create a new exception instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @param  \Symfony\Component\HttpFoundation\Response|null  $response
     * @param  string  $errorBag
     * @return void
     */
    public function __construct($validator, $response = null, $errorBag = 'default')
    {
        parent::__construct($validator, $response, $errorBag);

        $this->withMessage(trans('validation.totp_code'));
    }

    /**
     * Sets a custom validation message.
     *
     * @param  string  $message
     *
     * @return $this
     */
    public function withMessage(string $message): static
    {
        $this->validator->errors()->add(config('auth2fa.input', '2fa_code'), $message);

        return $this;
    }
}
