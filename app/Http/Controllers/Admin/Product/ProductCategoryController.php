<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Product\ProductCategory;
use App\Http\Requests\Product\StoreProductCategoryRequest;
use App\Http\Requests\Product\UpdateProductCategoryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $productCategories = QueryBuilder::for(ProductCategory::class)
            ->allowedFields(['id', 'parent_id', 'name', 'slug', 'description', 'sku', 'image'])
            ->allowedFilters(['name', 'sku'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'parent_id', 'name', 'sku')
            ->with('children')
            ->where('parent_id', null)
            ->jsonPaginate();

        return response()->json([
            'data' => $productCategories
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
        $productCategories = QueryBuilder::for(ProductCategory::class)
            ->allowedFields(['id', 'parent_id', 'name', 'slug', 'description', 'sku', 'image'])
            ->allowedFilters(['name', 'sku'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'parent_id', 'name', 'sku')
            ->onlyTrashed()
            ->with('children')
            ->where('parent_id', null)
            ->jsonPaginate();

        return response()->json([
            'data' => $productCategories
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreProductCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductCategoryRequest $request)
    {
        DB::beginTransaction();

        try {

            $productCategory = ProductCategory::create([
                'parent_id' => $request->get('parent_id'),
                'name' => $request->get('name'),
                'slug' => $request->get('slug'),
                'description' => $request->get('description'),
                'sku' => $request->get('sku'),
            ]);

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
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productCategory = ProductCategory::with('children')->where('id', $id)->first();

        return response()->json([
            'data' => $productCategory
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProductCategoryRequest  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductCategoryRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $productCategory = ProductCategory::findOrFail($id);

            $productCategory->update([
                'parent_id' => $request->get('parent_id'),
                'name' => $request->get('name'),
                'slug' => $request->get('slug'),
                'description' => $request->get('description'),
                'sku' => $request->get('sku'),
            ]);

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
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $productCategory = ProductCategory::findOrFail($id);
            $productCategory->delete();

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
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDestroy($id)
    {
        DB::beginTransaction();

        try {
            $productCategory = ProductCategory::withTrashed()->findOrFail($id);
            $productCategory->forceDelete();

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
            $ids = explode(',', $request->ids);

            foreach ($ids as $id) {
                $productCategory = ProductCategory::findOrFail($id);
                $productCategory->delete();
            }

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
            $ids = explode(',', $request->ids);

            foreach ($ids as $id) {
                $productCategory = ProductCategory::withTrashed()->findOrFail($id);
                $productCategory->forceDelete();
            }

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
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $productCategory = ProductCategory::withTrashed()->findOrFail($id);
            $productCategory->restore();

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
            $ids = explode(',', $request->ids);

            foreach ($ids as $id) {
                $productCategory = ProductCategory::withTrashed()->findOrFail($id);
                $productCategory->restore();
            }

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
