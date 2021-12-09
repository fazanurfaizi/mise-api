<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\FormRequest;
use Illuminate\Http\Request;

class UpdateProductCategoryRequest extends FormRequest
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
        $id = Request::route()->parameters()['id'];

        return [
            'parent_id' => 'nullable|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'slug' => "required|string|max:255",
            'description' => 'nullable|string',
            'sku' => "nullable|string|max:255",
            'image' => 'nullable'
        ];
    }
}
