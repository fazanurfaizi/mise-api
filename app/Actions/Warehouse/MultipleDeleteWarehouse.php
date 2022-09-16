<?php

namespace App\Actions\Warehouse;

use App\Models\Inventory\Warehouse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class MultipleDeleteWarehouse
{
    use AsAction;

    /**
     * Multiple Delete Warehouse Action
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function handle(Request $request)
    {
        Warehouse::query()
            ->whereIn('id', $request->post('ids'))
            ->delete();
    }
}
