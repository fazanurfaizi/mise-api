<?php

namespace App\Http\Requests;

use App\Exceptions\FormValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

class FormRequest extends BaseFormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new FormValidationException($validator);
    }
}
