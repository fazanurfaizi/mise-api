<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\FormRequest;

class StoreProductSkuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'skus' => 'required|array',
            'skus.*.code' => 'required|string',
            'skus.*.price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'skus.*.cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'skus.*.variant' => 'array',
            'skus.*.variant.*.option' => 'string',
            'skus.*.variant.*.value' => 'string'
        ];
    }
}
