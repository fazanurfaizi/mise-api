<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
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
			'parent_product_id' => $this->product_id,
			'sku' => $this->code,
			'name' => $this->product->name,
			'description' => $this->product->description,
			'price' => number_format($this->price, 2, '.', ''),
			'cost' => number_format($this->cost, 2, '.', ''),
			'attributes' => collect($this->variants)->map(function ($item) {
				return [
					'name' => $item->attribute->name,
					'option' => $item->option->value
				];
			})->values()->toArray()
        ];
    }
}
