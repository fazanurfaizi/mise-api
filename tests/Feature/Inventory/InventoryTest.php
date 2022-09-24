<?php

namespace Tests\Feature\Inventory;

use App\Exceptions\InvalidMovementException;
use App\Exceptions\InvalidQuantityException;
use App\Exceptions\InvalidWarehouseException;
use App\Exceptions\NotEnoughStockException;
use App\Exceptions\StockAlreadyExistsException;
use App\Exceptions\StockNotFoundException;
use App\Models\Inventory\InventoryStock;
use App\Models\Inventory\Warehouse;
use App\Models\Product\ProductSku;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreateInventoryStock;

class InventoryTest extends TestCase
{
    use RefreshDatabase;
    use CreateInventoryStock;

    /**
     * @test
     */
    public function itShouldCreateStockOnWarehouse()
    {
        $size = rand(1, 20);
        $item = ProductSku::factory()->create();
        $warehouse = Warehouse::factory()->create();
        $item->createStockOnWarehouse($size, $warehouse);
        $stock = $item->getStockFromWarehouse($warehouse);

        $this->assertEquals($size, $stock->quantity);
    }

    /**
     * @test
     */
    public function itShouldCallNewStockOnWarehouse()
    {
        $item = ProductSku::factory()->create();
        $warehouse = Warehouse::factory()->create();
        $stock = $item->newStockOnWarehouse($warehouse);

        $this->assertEquals($item->id, $stock->product_sku_id);
        $this->assertEquals($warehouse->id, $stock->warehouse_id);
    }

    /**
     * @test
     */
    public function itShouldPutStockOnWarehouse()
    {
        $stock = $this->createStock();
        $before = $stock->quantity;
        $quantity = rand(1, 10);

        $stock->put($quantity, 'Added quantity', 15);

        $this->assertEquals(($before + $quantity), $stock->quantity);
    }

    /**
     * @test
     */
    public function itShouldTakeStock()
    {
        $stock = $this->createStock();
        $before = $stock->quantity;
        $quantity = rand(1, 10);

        $stock->take($quantity, 'Remove some quantity', 15);

        $this->assertEquals(($before - $quantity), $stock->quantity);
        $this->assertEquals('Remove some quantity', $stock->reason);
        $this->assertEquals(15, $stock->cost);
    }

    /**
     * @test
     */
    public function itShouldMoveStock()
    {
        $stock = $this->createStock();

        $newWarehouse = Warehouse::factory()->create();

        $stock->moveTo($newWarehouse);

        $this->assertEquals($newWarehouse->id, $stock->warehouse_id);
    }

    /**
     * @test
     */
    public function itShouldCheckIfStockIsValid()
    {
        $stock = $this->createStock();

        $this->assertTrue($stock->isValidQuantity(500));
        $this->assertTrue($stock->isValidQuantity(5.000));
        $this->assertTrue($stock->isValidQuantity('500'));
        $this->assertTrue($stock->isValidQuantity('500.00'));
        $this->assertTrue($stock->isValidQuantity('500.0'));
        $this->assertTrue($stock->isValidQuantity('1.500'));
        $this->assertTrue($stock->isValidQuantity('15000000'));
        $this->assertTrue($stock->isValidQuantity('15_000_000'));
    }

    /**
     * @test
     */
    public function itShouldThrowStockNotValid()
    {
        $stock = $this->createStock();

        $this->expectException(InvalidQuantityException::class);

        $stock->isValidQuantity('40a');
        $stock->isValidQuantity('5,000');
        $stock->isValidQuantity('5.000,00');
    }

    /**
     * @test
     */
    public function itShouldThrowInvalidMovement()
    {
        $stock = $this->createStock();

        $this->expectException(InvalidMovementException::class);

        $stock->getMovement('testing');
    }

    /**
     * @test
     */
    public function itShouldUpdateStockQuantity()
    {
        $stock = $this->createStock();

        $quantity = rand(1, 10);

        $stock->updateQuantity($quantity);

        $this->assertEquals($quantity, $stock->quantity);
    }

    /**
     * @test
     */
    public function itShouldThrowErrorInUpdateStockQuantity()
    {
        $stock = $this->createStock();

        $this->expectException(InvalidQuantityException::class);

        $stock->updateQuantity(-10);
    }

    /**
     * @test
     */
    public function itShouldThrowNotEnoughStock()
    {
        $stock = $this->createStock();

        $this->expectException(NotEnoughStockException::class);

        $stock->take(100);
    }

    /**
     * @test
     */
    public function itShouldThrowStockAlready()
    {
        $this->createStock();

        $item = ProductSku::first();
        $warehouse = Warehouse::first();

        $this->expectException(StockAlreadyExistsException::class);

        $item->createStockOnWarehouse(10, $warehouse);
    }

    /**
     * @test
     */
    public function itShouldTakeStockFromManyWarehouses()
    {
        $stockBefore = $this->createStock();

        $item = ProductSku::first();

        $warehouses = Warehouse::first();

        $quantity = rand(1, 10);

        $item->takeFromManyWarehouses($quantity, [$warehouses]);

        $stock = InventoryStock::first();

        $this->assertEquals($stockBefore->quantity - $quantity, $stock->quantity);
    }

    /**
     * @test
     */
    public function itShouldAddStockAddToManyWarehouses()
    {
        $stockBefore = $this->createStock();

        $item = ProductSku::first();

        $warehouses = Warehouse::first();

        $quantity = rand(1, 10);

        $item->addToManyWarehouses($quantity, [$warehouses]);

        $stock = InventoryStock::first();

        $this->assertEquals($stockBefore->quantity + $quantity, $stock->quantity);
    }

    /**
     * @test
     */
    public function itShouldMoveInventoryStock()
    {
        $this->createStock();

        $warehouseBefore = Warehouse::first();
        $warehouseTarget = Warehouse::factory()->create();

        $item = ProductSku::first();

        $item->moveStock($warehouseBefore, $warehouseTarget);

        $stock = InventoryStock::first();

        $this->assertEquals($warehouseTarget->id, $stock->warehouse_id);
    }

    /**
     * @test
     */
    public function itShouldGetTotalStockFromInventory()
    {
        $stock = $this->createStock();

        $item = ProductSku::first();

        $this->assertEquals($stock->quantity, $item->getTotalStock());
    }

    /**
     * @test
     */
    public function itShouldThrowInvalidWarehouseException()
    {
        $stock = $this->createStock();

        $item = ProductSku::first();

        $this->expectException(InvalidWarehouseException::class);

        $item->getStockFromWarehouse('testing');
    }
}
