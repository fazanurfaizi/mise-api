<?php

namespace App\Traits\Models;

use Exception;
use App\Exceptions\StockAlreadyExistsException;
use App\Exceptions\StockNotFoundException;
use App\Models\Inventory\InventoryStock;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasItemStocks
{
    use InteractWithLocation;

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
     * @param $location
     * @param string           $reason
     * @param int|float|string $cost
     * @param null             $aisle
     * @param null             $row
     * @param null             $bin
     *
     * @throws \App\Exceptions\StockAlreadyExistsException
     * @throws \App\Exceptions\StockNotFoundException
     * @throws \App\Exceptions\InvalidLocationException
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createStockOnLocation($quantity, $location, $reason = '', $cost = 0, $aisle = null, $row = null, $bin = null)
    {
        $location = $this->getLocation($location);

        try {
            /*
             * We want to make sure stock doesn't exist on the specified location already
             */
            if($this->getStockFromLocation($location)) {
                throw new StockAlreadyExistsException(__('Stock Already Exists'));
            }

            /*
             * A stock record wasn't found on this location, we'll create one
             */
            return $this->stocks()->create([
                'warehouse_id' => $location->getKey(),
                'quantity' => $quantity,
                'aisle' => $aisle,
                'row' => $row,
                'bin' => $bin
            ]);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Instantiates a new stock on the specified
     * location on the current item.
     *
     * @param $location
     *
     * @throws \App\Exceptions\StockAlreadyExistsException
     * @throws \App\Exceptions\InvalidLocationException
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function newStockOnLocation($location)
    {
        $location = $this->getLocation($location);

        try {
            if($this->getStockFromLocation($location)) {
                throw new StockAlreadyExistsException(__('Stock Already Exists'));
            }

            $stock = $this->stocks()->getRelated();
            $stock->product_sku_id = $this->getKey();
            $stock->warehouse_id = $location->getKey();

            return $stock;
        } catch (Exception $exception) {
            throw $exception;
        }
    }


    /**
     * Takes the specified amount ($quantity) of stock from specified stock location.
     *
     * @param int|float|string $quantity
     * @param $location
     * @param string           $reason
     *
     * @throws StockNotFoundException
     *
     * @return array
     */
    public function takeFromLocation($quantity, $location, $reason = '')
    {
        if(is_array($location)) {
            return $this->takeFromManyLocations($quantity, $location, $reason);
        } else {
            $stock = $this->ensureStockIsExists($location);

            if($stock->take($quantity, $reason)) {
                return $this;
            }
        }

        return false;
    }

    /**
     * Takes the specified amount ($quantity) of stock from the specified stock locations.
     *
     * @param int|float|string $quantity
     * @param array            $locations
     * @param string           $reason
     *
     * @throws \App\Exceptions\StockNotFoundException
     *
     * @return array
     */
    public function takeFromManyLocations($quantity, $locations = [], $reason = '')
    {
        $stocks = [];

        foreach ($locations as $location) {
            $stock = $this->ensureStockIsExists($location);

            $stocks[] = $stock->take($quantity, $reason);
        }

        return $stocks;
    }

    /**
     * Alias for the `take` function.
     *
     * @param int|float|string $quantity
     * @param $location
     * @param string           $reason
     *
     * @return array
     */
    public function removeFromLocation($quantity, $location, $reason = '')
    {
        return $this->takeFromLocation($quantity, $location, $reason);
    }

    /**
     * Alias for the `takeFromMany` function.
     *
     * @param int|float|string $quantity
     * @param array            $locations
     * @param string           $reason
     *
     * @return array
     */
    public function removeFromManyLocations($quantity, $locations = [], $reason = '')
    {
        return $this->takeFromManyLocations($quantity, $locations, $reason);
    }

    /**
     * Puts the specified amount ($quantity) of stock into the specified stock location(s).
     *
     * @param int|float|string $quantity
     * @param $location
     * @param string           $reason
     * @param int|float|string $cost
     *
     * @throws \App\Exceptions\StockNotFoundException
     *
     * @return array
     */
    public function putToLocation($quantity, $location, $reason = '', $cost = 0)
    {
        if(is_array($location)) {
            return $this->putToManyLocations($quantity, $location);
        } else {
            $stock = $this->ensureStockIsExists($location);

            if($stock->put($quantity, $reason, $cost)) {
                return $this;
            }
        }

        return false;
    }

    /**
     * Puts the specified amount ($quantity) of stock into the specified stock locations.
     *
     * @param int|float|string $quantity
     * @param array            $locations
     * @param string           $reason
     * @param int|float|string $cost
     *
     * @throws \App\Exceptions\StockNotFoundException
     *
     * @return array
     */
    public function putToManyLocations($quantity, $locations = [], $reason = '', $cost = 0)
    {
        $stocks = [];

        foreach ($locations as $location) {
            $stock = $this->ensureStockIsExists($location);

            $stocks[] = $stock->put($quantity, $reason, $cost);
        }
    }

    /**
     * Alias for the `put` function.
     *
     * @param int|float|string $quantity
     * @param $location
     * @param string           $reason
     * @param int|float|string $cost
     *
     * @return array
     */
    public function addToLocation($quantity, $location, $reason = '', $cost = 0)
    {
        return $this->putToLocation($quantity, $location, $reason, $cost);
    }

    /**
     * Alias for the `putToMany` function.
     *
     * @param int|float|string $quantity
     * @param array $locations
     * @param string $reason
     * @param int|float|string $cost
     *
     * @return array
     */
    public function addToManyLocations($quantity, $locations = [], $reason = '', $cost = 0)
    {
        return $this->putToManyLocations($quantity, $locations, $reason, $cost);
    }

    /**
     * Moves a stock from one location to another
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
            ->moveTo($this->getLocation($to));
    }

    /**
     * Retrieves an item stock from a given location.
     *
     * @param $location
     * @throws \App\Exceptions\InvalidLocationException
     * @throws \App\Exceptions\StockNotFoundException
     * @return mixed
     */
    public function getStockFromLocation($location)
    {
        $location = $this->getLocation($location);

        $stock = $this->stocks()
            ->where('product_sku_id', $this->getKey())
            ->where('warehouse_id', $location->getKey())
            ->first();

        return $stock;
    }

    /**
     * Check if item stock is already exists or not.
     *
     * @param $location
     * @throws \App\Exceptions\InvalidLocationException
     * @throws \App\Exceptions\StockNotFoundException
     * @return mixed
     */
    private function ensureStockIsExists($location)
    {
        return $this->getStockFromLocation($location) ?? throw new StockNotFoundException(__('Stock Not Found'));
    }

}
