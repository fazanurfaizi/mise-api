<?php

namespace App\Http\Controllers\Admin\Product;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductUnitRequest;
use App\Http\Requests\Product\UpdateProductUnitRequest;
use App\Http\Resources\Product\ProductUnitCollection;
use App\Models\Product\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class ProductUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $productUnits = QueryBuilder::for(ProductUnit::class)
            ->allowedFields(['id', 'name', 'symbol', 'quantity'])
            ->allowedFilters(['id', 'name'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'name')
            ->jsonPaginate();

        return response()->json([
            'data' => new ProductUnitCollection($productUnits)
        ], Response::HTTP_OK);
    }

    /**
     * Display a listing of the resource in the bin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function browseBin(Request $request)
    {
        $productUnits = QueryBuilder::for(ProductUnit::class)
            ->allowedFields(['id', 'name', 'symbol', 'quantity'])
            ->allowedFilters(['id', 'name'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'name')
            ->onlyTrashed()
            ->jsonPaginate();

        return response()->json([
            'data' => new ProductUnitCollection($productUnits)
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Product\StoreProductUnitRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductUnitRequest $request)
    {
        try {
            DB::beginTransaction();

            ProductUnit::create([
                'name' => $request->post('name'),
                'symbol' => $request->post('symbol'),
                'quantity' => $request->post('quantity')
            ]);

            DB::commit();

            return response()->json([
                'message' => __('Created successfully')
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product\ProductUnit  $productUnit
     * @return \Illuminate\Http\Response
     */
    public function show(ProductUnit $productUnit)
    {
        return response()->json([
            'data' => $productUnit
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Product\UpdateProductUnitRequest  $request
     * @param  \App\Models\Product\ProductUnit  $productUnit
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductUnitRequest $request, ProductUnit $productUnit)
    {
        try {
            DB::beginTransaction();

            $productUnit->update([
                'name' => $request->post('name'),
                'symbol' => $request->post('symbol'),
                'quantity' => $request->post('quantity')
            ]);

            DB::commit();

            return response()->json([
                'message' => __('Update successfully')
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product\ProductUnit  $productUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductUnit $productUnit)
    {
        try {
            DB::beginTransaction();

            $productUnit->delete();

            DB::commit();

            return response()->json([
                'message' => __('Deleted successfully')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Force remove the specified resource from storage.
     *
     * @param   mixed $id
     * @return \Illuminate\Http\Response
     */
    public function forceDestroy($id)
    {
        try {
            DB::beginTransaction();

            ProductUnit::withTrashed()->findOrFail($id)->forceDelete();

            DB::commit();

            return response()->json([
                'message' => __('Deleted successfully')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Mulitple remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multipleDestroy(Request $request)
    {
        try {
            DB::beginTransaction();

            ProductUnit::whereIn('id', $request->post('ids'))->delete();

            DB::commit();

            return response()->json([
                'message' => __('Deleted successfully')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Mulitple force remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multipleForceDestroy(Request $request)
    {
        try {
            DB::beginTransaction();

            ProductUnit::withTrashed()
                ->whereIn('id', $request->post('ids'))
                ->forceDelete();

            DB::commit();

            return response()->json([
                'message' => __('Deleted successfully')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param   mixed $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        try {
            DB::beginTransaction();

            ProductUnit::withTrashed()->findOrFail($id)->restore();

            DB::commit();

            return response()->json([
                'message' => __('Restored successfully')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Mulitple restore the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multipleRestore(Request $request)
    {
        try {
            DB::beginTransaction();

            ProductUnit::withTrashed()
                ->whereIn('id', $request->post('ids'))
                ->restore();

            DB::commit();

            return response()->json([
                'message' => __('Restored successfully')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
