<?php

namespace App\Adapters;

use App\Http\Resources\Product\VariantResource;

class ProductVariantAdapter extends BaseAdapter
{
    /**
     * Single resource transformer
     *
     * @param mixed $model
     */
    public function __construct($model) {
        parent::__construct(new VariantResource($model));
    }

    /**
     * Static functin for the collection
     *
     * @param \Illuminate\Database\Elooquent\Model $model
     * @return array
     */
    public static function collection($collection): array
    {
        $resource = new self($collection);
        $resource->setResource(VariantResource::collection($collection));

        return $resource->transform();
    }
}
