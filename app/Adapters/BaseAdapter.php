<?php

namespace App\Adapters;

use App\Contracts\Inventory\AdapterContract;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseAdapter implements AdapterContract
{
    /**
     * Resource property
     */
    protected $resource;

    abstract public static function collection($collection): array;

    /**
     * Single resource transformer
     *
     * @param \Illuminate\Http\Resources\Json\JsonResource $resource
     */
    protected function __construct(JsonResource $resource) {
        $this->resource = $resource;
    }

    /**
	 * Setter for resource if you want it to be a resource
	 *
	 * @param \Illuminate\Http\Resources\Json\JsonResource $resource
	 * @return void
	 */
	public function setResource($resource): void
	{
		$this->resource = $resource;
	}

    /**
     * Transform the resource into array
     *
     * @return array
     */
    public function transform(): array
    {
        return $this->resource->toArray(app('request'));
    }

}
