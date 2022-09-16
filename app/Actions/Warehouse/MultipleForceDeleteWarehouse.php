<?php

namespace App\Actions\Warehouse;

use App\Models\Inventory\Warehouse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class MultipleForceDeleteWarehouse
{
    use AsAction;

    /**
     * Multiple Force Delete Warehouse Action
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function handle(Request $request)
    {
        Warehouse::query()
            ->withoutTrashed()
            ->whereIn('id', $request->post('ids'))
            ->forceDelete();
    }
}
