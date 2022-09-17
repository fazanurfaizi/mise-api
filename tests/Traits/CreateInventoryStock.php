<?php

namespace Tests\Traits;

use App\Models\Inventory\InventoryStock;
use App\Models\Inventory\Warehouse;
use App\Models\Product\ProductSku;

trait CreateInventoryStock
{
    /**
     * Create inventory stock for testing
     *
     * @return \App\Models\Inventory\InventoryStock
     */
    public function createStock(): InventoryStock
    {
        $item = ProductSku::factory()->create();
        $warehouse = Warehouse::factory()->create();
        return $item->createStockOnWarehouse(10, $warehouse);
    }
}
