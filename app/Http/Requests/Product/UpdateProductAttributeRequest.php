<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\FormRequest;

class UpdateProductAttributeRequest extends FormRequest
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
        $id = $this->route()->parameter('product_attribute')['id'];

        return [
            'attribute' => 'string|required|unique:product_attributes,name,' . $id,
            'values' => 'nullable|array'
        ];
    }
}
