<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\FormRequest;

class UpdateBrandRequest extends FormRequest
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
        $id = $this->route()->parameter('brand')['id'];

        return [
            'name' => 'string|nullable|unique:brands,name,' . $id,
            'website' => 'string|nullable|unique:brands,website,' . $id,
            'description' => 'string|nullable',
            'is_enabled' => 'nullable|boolean'
        ];
    }
}
