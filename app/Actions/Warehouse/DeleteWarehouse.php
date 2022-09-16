<?php

namespace App\Actions\Warehouse;

use App\Models\Inventory\Warehouse;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteWarehouse
{
    use AsAction;

    /**
     * Delete Warehouse action
     *
     * @param \App\Models\Inventory\Warehouse $warehouse
     * @return void
     */
    public function handle(Warehouse $warehouse)
    {
        $warehouse->delete();
    }
}
