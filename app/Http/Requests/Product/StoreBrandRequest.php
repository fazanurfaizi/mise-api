<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\FormRequest;

class StoreBrandRequest extends FormRequest
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
            'name' => 'string|required|unique:brands,name',
            'website' => 'string|nullable|unique:brands,website',
            'description' => 'string|nullable',
            'is_enabled' => 'nullable|boolean'
        ];
    }
}
