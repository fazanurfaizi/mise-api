<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        Log::info(Request::route()->parameters());
        $id = Request::route()->parameter('product_attribute')['id'];
        Log::info($id);

        return [
            'attribute' => 'string|required|unique:product_attributes,name,' . $id,
            'values' => 'nullable|array'
        ];
    }
}
