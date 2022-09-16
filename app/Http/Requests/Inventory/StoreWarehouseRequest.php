<?php

namespace App\Http\Requests\Inventory;

use App\Http\Requests\FormRequest;
use App\Rules\PhoneRule;

class StoreWarehouseRequest extends FormRequest
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
            'name' => 'required|string|unique:warehouses,name',
            'description' => 'nullable|string',
            'email' => 'required|string|unique:warehouses,email',
            'address' => 'required|string',
            'city' => 'required|string',
            'zipcode' => 'required|numeric',
            'phone_number' => ['required', new PhoneRule()],
            'is_default' => 'nullable|boolean'
        ];
    }
}
