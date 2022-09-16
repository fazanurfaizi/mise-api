<?php

namespace App\Actions\Warehouse;

use App\Models\Inventory\Warehouse;
use Lorisleiva\Actions\Concerns\AsAction;

class RestoreWarehouse
{
    use AsAction;

    /**
     * Restore Warehouse Action
     *
     * @param int $id
     * @return void
     */
    public function handle(int $id)
    {
        Warehouse::query()
            ->withTrashed()
            ->findOrFail($id)
            ->restore();
    }
}
