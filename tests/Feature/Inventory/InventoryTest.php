<?php

namespace Tests\Feature\Inventory;

use App\Adapters\ProductVariantAdapter;
use App\Models\Inventory\Warehouse;
use App\Models\Product\ProductSku;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CreateProducts;

class InventoryTest extends TestCase
{
    use RefreshDatabase,
        CreateProducts;

    /**
     * @test
     */
    public function itShouldCreateWarehouse()
    {
        $warehouse = Warehouse::factory()->create();

        $this->assertDatabaseHas('warehouses', [
            'id' => $warehouse->id
        ]);
    }

    /**
     * @test
     */
    public function itShouldInsertProductInWarehouse()
    {
        $warehouse = Warehouse::factory()->create();

        $product = $this->createTestProduct();

        $product->skus->each(function(ProductSku $sku) use ($warehouse) {
            $warehouse->items()->create([
                'product_sku_id' => $sku->id,
                'quantity' => rand(5, 20),
                'asile' => 'ai-' . rand(20, 30),
                'row' => 'rw-' . rand(1, 9)
            ]);
        });

        $warehouse->load('items');

        $this->assertArrayHasKey('items', $warehouse, 'Warehose should have a stocks');
    }

    /**
     * @test
     */
    public function itShouldListTheWarehouseStocks()
    {
        $warehouse = Warehouse::factory()->create();

        $product = $this->createTestProduct();

        $product->skus->each(function(ProductSku $sku) use ($warehouse) {
            $warehouse->items()->create([
                'product_sku_id' => $sku->id,
                'quantity' => rand(5, 20),
                'asile' => 'ai-' . rand(20, 30),
                'row' => 'rw-' . rand(1, 9)
            ]);
        });

        $warehouse->load('items');
        $items = collect($warehouse->items)
            ->map(function($item) {
                return (new ProductVariantAdapter($item->product))->transform();
            })
            ->toArray();

        // Each product should have a parent_product
        collect($items)->each(function($item) {
            $this->assertArrayHasKey('parent_product_id', $item, 'It should have a parent_product_id');
        });
    }
}
