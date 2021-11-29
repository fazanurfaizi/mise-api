<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException as BaseException;

class FormValidationException extends BaseException
{

    public $validator;

    public $status = Response::HTTP_UNPROCESSABLE_ENTITY;

    /**
     * Create a new exception instance.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param string                                     $errorBag
     * @return void
     */
    public function __construct($validator, $response = null, $errorBag = 'default')
    {
        parent::__construct($validator);

        $this->response = $response;
        $this->errorBag = $errorBag;
        $this->validator = $validator;
    }

    public function render(): JsonResponse
    {
        return new JsonResponse([
            'data' => [
                'message' => 'The given data was invalid.',
                'errors' => $this->validator->errors()
            ],
            'meta' => [
                'timestamp' => intdiv((int) now()->format('Uu'), 1000)
            ]
        ], $this->status);
    }
}
