<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Media\MediaResource;
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
            'condition' => $this->condition,
            'min_purchase' => $this->min_purchase,
            'featured' => $this->featured,
            'price' => $this->hasSku() ? number_format($this->skus()->first()->price, 2, '.', '') : 0.00,
            'cost' => $this->hasSku() ? number_format($this->skus()->first()->price, 2, '.', '') : 0.00,
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'units' => ProductUnitResource::collection($this->whenLoaded('units')),
            'images' => MediaResource::collection($this->whenLoaded('media', $this->getMedia('products'))),
            // 'attributes' => $this->whenLoaded('attributes', ProductAttributeResource::collection($this->attributes)->toArray(app('request'))),
            'variants' => $this->whenLoaded(
                'variants',
                $this->when($this->hasAttributes() && $this->hasSku(),
                    ProductVariantResource::collection($this->skus)->toArray(app('request'))
                )
            )
        ];
    }
}
