<?php

namespace App\Actions\Warehouse;

use App\Models\Inventory\Warehouse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateWarehouse
{
    use AsAction;

    /**
     * Create warehouse action
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Inventory\Warehouse
     */
    public function handle(Request $request): Warehouse
    {
        return Warehouse::create([
            'name' => $request->post('name'),
            'description' => $request->post('description'),
            'email' => $request->post('email'),
            'address' => $request->post('address'),
            'city' => $request->post('city'),
            'zipcode' => $request->post('zipcode'),
            'phone_number' => $request->post('phone_number'),
            'is_default' => $request->post('is_default'),
        ]);
    }
}
