<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this->city,
            'zipcode' => $this->zipcode,
            'phone_number' => $this->phone_number,
            'is_default' => $this->is_default
        ];
    }
}
