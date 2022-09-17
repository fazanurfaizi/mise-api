<?php

namespace App\Traits\Models;

use App\Exceptions\StockAlreadyExistsException;
use App\Exceptions\StockNotFoundException;
use App\Models\Inventory\InventoryStock;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasItemStocks
{
    use InteractWithWarehouse;

    /**
     * Get all of the stocks for the ProductSku
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(InventoryStock::class);
    }

    /**
     * Returns the total sum of the current stock.
     *
     * @return int|float
     */
    public function getTotalStock(): int|float
    {
        return $this->stocks->sum('quantity');
    }

    /**
     * Check if item has stock in warehouse
     *
     * @return bool
     */
    public function isInStock(): bool
    {
        return $this->getTotalStock() > 0;
    }

    /**
     * Creates a stock record to the current inventory item.
     *
     * @param int|float|string $quantity
     * @param $warehouse
     * @param string           $reason
     * @param int|float|string $cost
     * @param null             $aisle
     * @param null             $row
     * @param null             $bin
     *
     * @throws \App\Exceptions\StockAlreadyExistsException
     * @throws \App\Exceptions\StockNotFoundException
     * @throws \App\Exceptions\InvalidWarehouseException
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createStockOnWarehouse($quantity, $warehouse, $reason = '', $cost = 0, $aisle = null, $row = null, $bin = null)
    {
        $warehouse = $this->getWarehouse($warehouse);

        /*
        * We want to make sure stock doesn't exist on the specified wareh$warehouse already
        */
        if($this->getStockFromWarehouse($warehouse)) {
            throw new StockAlreadyExistsException(__('Stock Already Exists'));
        }

        /*
        * A stock record wasn't found on this warehouse, we'll create one
        */
        return $this->stocks()->create([
            'warehouse_id' => $warehouse->getKey(),
            'quantity' => $quantity,
            'aisle' => $aisle,
            'row' => $row,
            'bin' => $bin
        ]);
    }

    /**
     * Instantiates a new stock on the specified
     * warehouse on the current item.
     *
     * @param $warehouse
     *
     * @throws \App\Exceptions\StockAlreadyExistsException
     * @throws \App\Exceptions\InvalidWarehouseException
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function newStockOnWarehouse($warehouse)
    {
        $warehouse = $this->getWarehouse($warehouse);

        if($this->getStockFromWarehouse($warehouse)) {
            throw new StockAlreadyExistsException(__('Stock Already Exists'), 422);
        }

        $stock = $this->stocks()->getRelated();
        $stock->product_sku_id = $this->getKey();
        $stock->warehouse_id = $warehouse->getKey();

        return $stock;
    }


    /**
     * Takes the specified amount ($quantity) of stock from specified stock warehouse.
     *
     * @param int|float|string $quantity
     * @param $warehouse
     * @param string           $reason
     *
     * @throws StockNotFoundException
     *
     * @return array
     */
    public function takeFromWarehouse($quantity, $warehouse, $reason = '')
    {
        if(is_array($warehouse)) {
            return $this->takeFromManyWarehouses($quantity, $warehouse, $reason);
        } else {
            $stock = $this->ensureStockIsExists($warehouse);

            if($stock->take($quantity, $reason)) {
                return $this;
            }
        }

        return false;
    }

    /**
     * Takes the specified amount ($quantity) of stock from the specified stock warehouse.
     *
     * @param int|float|string $quantity
     * @param array            $warehouse
     * @param string           $reason
     *
     * @throws \App\Exceptions\StockNotFoundException
     *
     * @return array
     */
    public function takeFromManyWarehouses($quantity, $warehouses = [], $reason = '')
    {
        $stocks = [];

        foreach ($warehouses as $warehouse) {
            $stock = $this->ensureStockIsExists($warehouse);

            $stocks[] = $stock->take($quantity, $reason);
        }

        return $stocks;
    }

    /**
     * Alias for the `take` function.
     *
     * @param int|float|string $quantity
     * @param $warehouse
     * @param string           $reason
     *
     * @return array
     */
    public function removeFromWarehouse($quantity, $warehouse, $reason = '')
    {
        return $this->takeFromWarehouse($quantity, $warehouse, $reason);
    }

    /**
     * Alias for the `takeFromMany` function.
     *
     * @param int|float|string $quantity
     * @param array            $warehouses
     * @param string           $reason
     *
     * @return array
     */
    public function removeFromManyWarehouses($quantity, $warehouses = [], $reason = '')
    {
        return $this->takeFromManyWarehouses($quantity, $warehouses, $reason);
    }

    /**
     * Puts the specified amount ($quantity) of stock into the specified stock warehouse(s).
     *
     * @param int|float|string $quantity
     * @param $warehouse
     * @param string           $reason
     * @param int|float|string $cost
     *
     * @throws \App\Exceptions\StockNotFoundException
     *
     * @return array
     */
    public function putToWarehouse($quantity, $warehouse, $reason = '', $cost = 0)
    {
        if(is_array($warehouse)) {
            return $this->putToManyWarehouses($quantity, $warehouse);
        } else {
            $stock = $this->ensureStockIsExists($warehouse);

            if($stock->put($quantity, $reason, $cost)) {
                return $this;
            }
        }

        return false;
    }

    /**
     * Puts the specified amount ($quantity) of stock into the specified stock warehouses.
     *
     * @param int|float|string $quantity
     * @param array            $warehouses
     * @param string           $reason
     * @param int|float|string $cost
     *
     * @throws \App\Exceptions\StockNotFoundException
     *
     * @return array
     */
    public function putToManyWarehouses($quantity, $warehouses = [], $reason = '', $cost = 0)
    {
        $stocks = [];

        foreach ($warehouses as $warehouse) {
            $stock = $this->ensureStockIsExists($warehouse);

            $stocks[] = $stock->put($quantity, $reason, $cost);
        }
    }

    /**
     * Alias for the `put` function.
     *
     * @param int|float|string $quantity
     * @param $warehouse
     * @param string           $reason
     * @param int|float|string $cost
     *
     * @return array
     */
    public function addToWarehouse($quantity, $warehouse, $reason = '', $cost = 0)
    {
        return $this->putToWarehouse($quantity, $warehouse, $reason, $cost);
    }

    /**
     * Alias for the `putToMany` function.
     *
     * @param int|float|string $quantity
     * @param array $warehouses
     * @param string $reason
     * @param int|float|string $cost
     *
     * @return array
     */
    public function addToManyWarehouses($quantity, $warehouses = [], $reason = '', $cost = 0)
    {
        return $this->putToManyWarehouses($quantity, $warehouses, $reason, $cost);
    }

    /**
     * Moves a stock from one warehouse to another
     *
     * @param $from
     * @param $to
     *
     * @throws \App\Exceptions\StockNotFoundException
     *
     * @return mixed
     */
    public function moveStock($from, $to)
    {
        return $this->ensureStockIsExists($from)
            ->moveTo($this->getWarehouse($to));
    }

    /**
     * Retrieves an item stock from a given warehouse.
     *
     * @param $warehouse
     * @throws \App\Exceptions\InvalidWarehouseException
     * @throws \App\Exceptions\StockNotFoundException
     * @return mixed
     */
    public function getStockFromWarehouse($warehouse)
    {
        $warehouse = $this->getWarehouse($warehouse);

        $stock = $this->stocks()
            ->where('product_sku_id', $this->getKey())
            ->where('warehouse_id', $warehouse->getKey())
            ->first();

        return $stock;
    }

    /**
     * Check if item stock is already exists or not.
     *
     * @param $warehouse
     * @throws \App\Exceptions\InvalidWarehouseException
     * @throws \App\Exceptions\StockNotFoundException
     * @return mixed
     */
    private function ensureStockIsExists($warehouse)
    {
        return $this->getStockFromWarehouse($warehouse) ?? throw new StockNotFoundException(__('Stock Not Found'));
    }

}
