<?php

namespace App\Adapters;

use App\Http\Resources\Product\ProductResource;

class ProductAdapter extends BaseAdapter
{
    /**
     * Single resource transformer
     *
     * @param mixed $model
     */
    public function __construct($model) {
        parent::__construct(new ProductResource($model));
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
        $resource->setResource(ProductResource::collection($collection));

        return $resource->transform();
    }
}
