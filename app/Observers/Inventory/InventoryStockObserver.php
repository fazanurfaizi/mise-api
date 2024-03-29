<?php

namespace App\Observers\Inventory;

use App\Models\Inventory\InventoryStock;

class InventoryStockObserver
{
    /**
     * Handle the InventoryStock "creating" event.
     *
     * @param  \App\Models\Inventory\InventoryStock  $inventoryStock
     * @return void
     */
    public function creating(InventoryStock $InventoryStock)
    {
        /*
        * Check if a reason has been set, if not
        * let's retrieve the default first entry reason
        */
        if (!$InventoryStock->reason) {
            $InventoryStock->reason = 'First Item Record; Stock Increase';
        }
    }

    /**
     * Handle the InventoryStock "created" event.
     *
     * @param  \App\Models\Inventory\InventoryStock  $inventoryStock
     * @return void
     */
    public function created(InventoryStock $inventoryStock)
    {
        $inventoryStock->postCreate();
    }

    /**
     * Handle the InventoryStock "updating" event.
     *
     * @param  \App\Models\Inventory\InventoryStock  $inventoryStock
     * @return void
     */
    public function updating(InventoryStock $inventoryStock)
    {
        /*
        * Check if a reason has been set, if not let's retrieve the default change reason
        */
        if (!$inventoryStock->reason) {
            $inventoryStock->reason = 'Stock Adjustment';
        }
    }

    /**
     * Handle the InventoryStock "updated" event.
     *
     * @param  \App\Models\Inventory\InventoryStock  $inventoryStock
     * @return void
     */
    public function updated(InventoryStock $inventoryStock)
    {
        $inventoryStock->postUpdate();
    }

    /**
     * Handle the InventoryStock "deleted" event.
     *
     * @param  \App\Models\Inventory\InventoryStock  $inventoryStock
     * @return void
     */
    public function deleted(InventoryStock $inventoryStock)
    {
        //
    }

    /**
     * Handle the InventoryStock "restored" event.
     *
     * @param  \App\Models\Inventory\InventoryStock  $inventoryStock
     * @return void
     */
    public function restored(InventoryStock $inventoryStock)
    {
        //
    }

    /**
     * Handle the InventoryStock "force deleted" event.
     *
     * @param  \App\Models\Inventory\InventoryStock  $inventoryStock
     * @return void
     */
    public function forceDeleted(InventoryStock $inventoryStock)
    {
        //
    }
}
