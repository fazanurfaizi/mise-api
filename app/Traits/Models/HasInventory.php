<?php

namespace App\Traits\Models;

use App\Models\Inventory\InventoryStock;
use App\Models\Product\ProductSku;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

trait HasInventory
{
    /**
     * Get all of the warehouses for the HasInventory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function warehouses(): HasManyThrough
    {
        return $this->hasManyThrough(InventoryStock::class, ProductSku::class)
            ->with('warehouse');
    }

    /**
	 * Get the list of warehouse in which product
	 * has stocks
	 *
	 * @return \Illuminate\Support\Collection
	 */
    public function getWarehouses(): Collection
    {
        return collect($this->warehouses)
            ->map(function($item) {
                return $item->warehouse;
            })
            ->unique('id')
            ->values();
    }
}
