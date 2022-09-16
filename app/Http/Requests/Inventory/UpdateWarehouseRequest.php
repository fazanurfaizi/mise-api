<?php

namespace App\Http\Requests\Inventory;

use App\Http\Requests\FormRequest;
use App\Rules\PhoneRule;

class UpdateWarehouseRequest extends FormRequest
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
        $id = $this->route()->parameter('warehouse')['id'];

        return [
            'name' => 'required|string|unique:warehouses,name,' . $id,
            'description' => 'nullable|string',
            'email' => 'required|string|unique:warehouses,email,' . $id,
            'address' => 'required|string',
            'city' => 'required|string',
            'zipcode' => 'required|numeric',
            'phone_number' => ['required', new PhoneRule()],
            'is_default' => 'nullable|boolean'
        ];
    }
}
