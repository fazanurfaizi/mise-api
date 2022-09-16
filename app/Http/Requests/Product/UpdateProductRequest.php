<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'description' => 'nullable|string',
            'condition' => 'required|in:new,second',
            'min_purchase' => 'required|integer',
            'featured' => 'required|boolean',
            'categories' => 'required|array|exists:product_categories,id',
            'images.*' => 'mimes:jpeg,jpg,png,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv|max:2048',
            'units' => 'required|array',
            'units.*.unit' => 'required|exists:product_units,id',
            'units.*.value' => 'required',
        ];
    }
}
