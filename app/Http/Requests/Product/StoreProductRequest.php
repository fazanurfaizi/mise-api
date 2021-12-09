<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products',
            'description' => 'nullable|string',
            'items' => 'required|array',
            'items.*.discount_id' => 'nullable|integer|exists:discounts,id',
            'items.*.variant_id' => 'nullable|integer|exists:variants,id',
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:0',
            'items.*.price' => 'required|integer|min:0',
            'items.*.sku' => 'nullable|string',
        ];
    }
}
