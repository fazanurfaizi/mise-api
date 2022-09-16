<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Actions\Warehouse\CreateWarehouse;
use App\Actions\Warehouse\DeleteWarehouse;
use App\Actions\Warehouse\ForceDeleteWarehouse;
use App\Actions\Warehouse\MultipleDeleteWarehouse;
use App\Actions\Warehouse\MultipleForceDeleteWarehouse;
use App\Actions\Warehouse\MultipleRestoreWarehouse;
use App\Actions\Warehouse\RestoreWarehouse;
use App\Actions\Warehouse\UpdateWarehouse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreWarehouseRequest;
use App\Http\Requests\Inventory\UpdateWarehouseRequest;
use App\Http\Resources\Inventory\WarehouseCollection;
use App\Http\Resources\Inventory\WarehouseResource;
use App\Models\Inventory\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouses = QueryBuilder::for(Warehouse::class)
            ->allowedFields(['id', 'name', 'description', 'email', 'address', 'city', 'zipcode', 'phone_number'])
            ->allowedFilters(['name', 'city'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'name', 'created_at')
            ->jsonPaginate();

        return response()->json([
            'data' => new WarehouseCollection($warehouses)
        ]);
    }

    /**
     * Display a listing of the resource in the bin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function browseBin(Request $request)
    {
        $warehouses = QueryBuilder::for(Warehouse::class)
            ->allowedFields(['id', 'name', 'description', 'email', 'address', 'city', 'zipcode', 'phone_number'])
            ->allowedFilters(['name', 'city'])
            ->defaultSort('-deleted_at')
            ->allowedSorts('id', 'name', 'deleted_at')
            ->onlyTrashed()
            ->jsonPaginate();

        return response()->json([
            'data' => new WarehouseCollection($warehouses)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Inventory\StoreWarehouseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWarehouseRequest $request)
    {
        CreateWarehouse::run($request);

        return response()->json([
            'message' => __('Create successfully')
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inventory\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function show(Warehouse $warehouse)
    {
        return response()->json([
            'data' => new WarehouseResource($warehouse)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Inventory\UpdateWarehouseRequest  $request
     * @param  \App\Models\Inventory\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        UpdateWarehouse::run($request, $warehouse);

        return response()->json([
            'message' => __('Update successfully')
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventory\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warehouse $warehouse)
    {
        DeleteWarehouse::run($warehouse);

        return response()->json([
            'message' => __('Delete successfully')
        ], Response::HTTP_OK);
    }

    /**
     * Force remove the specified resource from storage.
     *
     * @param   mixed $id
     * @return \Illuminate\Http\Response
     */
    public function forceDestroy($id)
    {
        ForceDeleteWarehouse::run($id);

        return response()->json([
            'message' => __('Force Delete successfully')
        ], Response::HTTP_OK);
    }

    /**
     * Mulitple remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multipleDestroy(Request $request)
    {
        MultipleDeleteWarehouse::run($request);

        return response()->json([
            'message' => __('Multiple Delete successfully')
        ], Response::HTTP_OK);
    }

    /**
     * Mulitple force remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multipleForceDestroy(Request $request)
    {
        MultipleForceDeleteWarehouse::run($request);

        return response()->json([
            'message' => __('Multiple Force Delete successfully')
        ], Response::HTTP_OK);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param   mixed $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        RestoreWarehouse::run($id);

        return response()->json([
            'message' => __('Restore successfully')
        ], Response::HTTP_OK);
    }

    /**
     * Mulitple restore the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multipleRestore(Request $request)
    {
        MultipleRestoreWarehouse::run($request);

        return response()->json([
            'message' => __('Multiple Restore successfully')
        ], Response::HTTP_OK);
    }
}
