<?php

namespace App\Actions\Warehouse;

use App\Models\Inventory\Warehouse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class MultipleRestoreWarehouse
{
    use AsAction;

    /**
     * Multiple Restore Warehouse Action
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function handle(Request $request)
    {
        Warehouse::query()
            ->withTrashed()
            ->whereIn('id', $request->post('ids'))
            ->restore();
    }
}
