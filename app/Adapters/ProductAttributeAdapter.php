<?php

namespace App\Adapters;

use App\Http\Resources\Product\AttributeResource;

class ProductAttributeAdapter extends BaseAdapter
{
    /**
     * Single resource transformer
     *
     * @param mixed $model
     */
    public function __construct($mode) {
        parent::__construct(new AttributeResource($model));
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
        $resource->setResource(AttributeResource::collection($collection));

        return $resource->transform();
    }
}
