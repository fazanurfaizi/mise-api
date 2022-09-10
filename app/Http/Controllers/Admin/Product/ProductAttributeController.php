<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductAttributeRequest;
use App\Http\Requests\Product\UpdateProductAttributeRequest;
use App\Http\Resources\Product\AttributeResource;
use App\Http\Resources\Product\ProductAttributeCollection;
use App\Models\Product\ProductAttribute;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

class ProductAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        DB::enableQueryLog();
        $productAttributes = QueryBuilder::for(ProductAttribute::class)
            ->allowedFields(['id', 'name'])
            ->allowedFilters(['name'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'name')
            ->allowedIncludes(['values'])
            ->jsonPaginate();

        return response()->json([
            'data' => new ProductAttributeCollection($productAttributes)
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
        $productAttributes = QueryBuilder::for(ProductAttribute::class)
            ->allowedFields(['id', 'name'])
            ->allowedFilters(['name'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'name')
            ->allowedIncludes(['values'])
            ->onlyTrashed()
            ->jsonPaginate();

        return response()->json([
            'data' => new ProductAttributeCollection($productAttributes)
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Product\StoreProductAttributeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductAttributeRequest $request)
    {
        try {
            DB::beginTransaction();

            $productAttribute = ProductAttribute::create([
                'name' => $request->post('attribute')
            ]);

            if ($request->has('values')) {
                $productAttribute->addValue($request->post('values'));
            }

            DB::commit();

            return response()->json([
                'message' => __('Created successfully')
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product\ProductAttribute  $productAttribute
     * @return \Illuminate\Http\Response
     */
    public function show(ProductAttribute $productAttribute)
    {
        $productAttribute->load('values');

        return response()->json([
            'data' => new AttributeResource($productAttribute)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Product\UpdateProductAttributeRequest  $request
     * @param  \App\Models\Product\ProductAttribute  $productAttribute
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductAttributeRequest $request, ProductAttribute $productAttribute)
    {
        try {
            DB::beginTransaction();

            $productAttribute->update([
                'name' => $request->post('attribute')
            ]);

            if ($request->has('values')) {
                $productAttribute->values()->delete();
                $productAttribute->addValue($request->post('values'));
            }

            DB::commit();

            return response()->json([
                'message' => __('Updated successfully')
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product\ProductAttribute  $productAttribute
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductAttribute $productAttribute)
    {
        DB::beginTransaction();

        try {
            $productAttribute->values()->delete();
            $productAttribute->delete();

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
     * @param  mixed $id
     * @return \Illuminate\Http\Response
     */
    public function forceDestroy($id)
    {
        DB::beginTransaction();

        try {
            $productAttribute = ProductAttribute::withTrashed()->findOrFail($id);
            $productAttribute->values()->forceDelete();
            $productAttribute->forceDelete();

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
        DB::beginTransaction();

        try {
            $productAttributes = ProductAttribute::whereIn('id', $request->post('ids'));
            $productAttributes->each(fn($attribute) => $attribute->values()->delete());
            $productAttributes->delete();

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
        DB::beginTransaction();

        try {
            $productAttributes = ProductAttribute::withTrashed()->whereIn('id', $request->post('ids'));
            $productAttributes->each(fn($attribute) => $attribute->values()->withTrashed()->forceDelete());
            $productAttributes->forceDelete();

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
     * @param  mixed $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $productAttribute = ProductAttribute::withTrashed()->findOrFail($id);
            $productAttribute->values()->restore();
            $productAttribute->restore();

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
        DB::beginTransaction();

        try {
            $productAttributes = ProductAttribute::withTrashed()->whereIn('id', $request->post('ids'));
            $productAttributes->each(fn($attribute) => $attribute->values()->withTrashed()->restore());
            $productAttributes->restore();

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
