<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\FormRequest;

class UpdateProductUnitRequest extends FormRequest
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
        $id = $this->route()->parameter('product_unit')['id'];

        return [
            'name' => 'required|string|unique:product_units,name,' . $id,
            'symbol' => 'nullable|string',
            'quantity' => 'nullable|string'
        ];
    }
}
