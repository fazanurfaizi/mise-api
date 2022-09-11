<?php

namespace App\Http\Controllers\Admin\Product;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreBrandRequest;
use App\Http\Requests\Product\UpdateBrandRequest;
use App\Http\Resources\Product\BrandCollection;
use App\Http\Resources\Product\BrandResource;
use App\Models\Product\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $brands = QueryBuilder::for(Brand::class)
            ->allowedFields(['id', 'name', 'website', 'is_enabled'])
            ->allowedFilters(['name', 'is_enabled'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'name')
            ->jsonPaginate();

        return response()->json([
            'data' => new BrandCollection($brands)
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
        $brands = QueryBuilder::for(Brand::class)
            ->allowedFields(['id', 'name', 'website', 'is_enabled'])
            ->allowedFilters(['name', 'is_enabled'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'name')
            ->onlyTrashed()
            ->jsonPaginate();

        return response()->json([
            'data' => new BrandCollection($brands)
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Product\StoreBrandRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBrandRequest $request)
    {
        try {
            DB::beginTransaction();

            Brand::create([
                'name' => $request->post('name'),
                'website' => $request->post('website'),
                'description' => $request->post('description'),
                'is_enabled' => $request->post('is_enabled'),
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
     * @param  \App\Models\Product\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return response()->json([
            'data' => new BrandResource($brand)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Product\UpdateBrandRequest  $request
     * @param  \App\Models\Product\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        try {
            DB::beginTransaction();

            $brand->update([
                'name' => $request->post('name', $brand->name),
                'website' => $request->post('website', $brand->website),
                'description' => $request->post('description', $brand->description),
                'is_enabled' => $request->post('is_enabled', $brand->is_enabled),
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
     * @param  \App\Models\Product\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        DB::beginTransaction();

        try {
            $brand->delete();

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
        try {
            DB::beginTransaction();

            Brand::withTrashed()->findOrFail($id)->forceDelete();

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

            Brand::whereIn('id', $request->post('ids'))->delete();

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

            Brand::withTrashed()
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
     * @param  mixed $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        try {
            DB::beginTransaction();

            Brand::withTrashed()->findOrFail($id)->restore();

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

            Brand::withTrashed()
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
