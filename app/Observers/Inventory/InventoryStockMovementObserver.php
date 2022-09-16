<?php

namespace App\Observers\Inventory;

use App\Models\Inventory\InventoryStockMovement;
use Illuminate\Support\Facades\Log;

class InventoryStockMovementObserver
{
    /**
     * Handle the InventoryStockMovement "creating" event.
     *
     * @param  \App\Models\Inventory\InventoryStockMovement  $inventoryStockMovement
     * @return void
     */
    public function creating(InventoryStockMovement $inventoryStockMovement)
    {
        /*
        * Check if a reason has been set, if not
        * let's retrieve the default first entry reason
        */
        if (!$inventoryStockMovement->reason) {
            $inventoryStockMovement->reason = 'First Item Record; Stock Increase';
        }
    }

    /**
     * Handle the InventoryStockMovement "created" event.
     *
     * @param  \App\Models\Inventory\InventoryStockMovement  $inventoryStockMovement
     * @return void
     */
    public function created(InventoryStockMovement $inventoryStockMovement)
    {
        $inventoryStockMovement->postCreate();
    }

    /**
     * Handle the InventoryStockMovement "updating" event.
     *
     * @param  \App\Models\Inventory\InventoryStockMovement  $inventoryStockMovement
     * @return void
     */
    public function updating(InventoryStockMovement $inventoryStockMovement)
    {
        /*
        * Retrieve the original quantity before it was updated,
        * so we can create generate an update with it
        */
        $inventoryStockMovement->beforeQuantity = $inventoryStockMovement->getOriginal('quantity');

        /*
        * Check if a reason has been set, if not let's retrieve the default change reason
        */
        if (!$inventoryStockMovement->reason) {
            $inventoryStockMovement->reason = 'Stock Adjustment';
        }
    }

    /**
     * Handle the InventoryStockMovement "updated" event.
     *
     * @param  \App\Models\Inventory\InventoryStockMovement  $inventoryStockMovement
     * @return void
     */
    public function updated(InventoryStockMovement $inventoryStockMovement)
    {
        $inventoryStockMovement->postUpdate();
    }

    /**
     * Handle the InventoryStockMovement "deleted" event.
     *
     * @param  \App\Models\Inventory\InventoryStockMovement  $inventoryStockMovement
     * @return void
     */
    public function deleted(InventoryStockMovement $inventoryStockMovement)
    {
        //
    }

    /**
     * Handle the InventoryStockMovement "restored" event.
     *
     * @param  \App\Models\Inventory\InventoryStockMovement  $inventoryStockMovement
     * @return void
     */
    public function restored(InventoryStockMovement $inventoryStockMovement)
    {
        //
    }

    /**
     * Handle the InventoryStockMovement "force deleted" event.
     *
     * @param  \App\Models\Inventory\InventoryStockMovement  $inventoryStockMovement
     * @return void
     */
    public function forceDeleted(InventoryStockMovement $inventoryStockMovement)
    {
        //
    }
}
