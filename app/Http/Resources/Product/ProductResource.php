<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'slug' => $this->slug,
            'sku' => $this->hasSku() ? $this->skus()->first()->code : null,
            'description' => $this->description,
            'price' => $this->hasSku() ? number_format($this->skus()->first()->price, 2, '.', '') : 0.00,
            'cost' => $this->hasSku() ? number_format($this->skus()->first()->price, 2, '.', '') : 0.00,
            'categories' => $this->whenLoaded('categories', CategoryResource::collection($this->categories)),
            'attributes' => $this->whenLoaded('attributes', AttributeResource::collection($this->attributes)->toArray(app('request'))),
            'variations' => $this->whenLoaded(
                'variations',
                $this->when($this->hasAttributes() && $this->hasSku(),
                    VariantResource::collection($this->skus)->toArray(app('request'))
                )
            )
        ];
    }
}
