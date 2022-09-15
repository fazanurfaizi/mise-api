<?php

namespace App\Http\Controllers\Admin\Product;

use App\Actions\Product\CreateProduct;
use Exception;
use App\Models\Product\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product\ProductAttribute;
use App\Models\Product\ProductAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = QueryBuilder::for(Product::class)
            ->allowedFields(['id', 'name', 'slug', 'description'])
            ->allowedFilters(['name', 'slug'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'name')
            ->whereHas('categories', function($query) use ($request) {
                $query->where('product_categories.id', $request->get('category_id'));
            })
            ->jsonPaginate();

        return response()->json([
            'data' => new ProductCollection($products)
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
        $products = QueryBuilder::for(Product::class)
            ->allowedFields(['id', 'name', 'slug', 'description'])
            ->allowedFilters(['name', 'slug'])
            ->defaultSort('-created_at')
            ->allowedSorts('id', 'name')
            ->onlyTrashed()
            ->jsonPaginate();

        return response()->json([
            'data' => new ProductCollection($products)
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Product\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();

            CreateProduct::run($request);

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
     * @param  \App\Models\Product\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load(['brand', 'categories', 'units', 'media', 'variants']);

        return response()->json([
            'data' => new ProductResource($product)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $product->update([
                'name' => $request->post('name'),
                'brand_id' => $request->post('brand_id'),
                'description' => $request->post('description'),
                'condition' => $request->post('condition'),
                'min_purchase' => $request->post('min_purchase'),
                'featured' => $request->post('featured'),
            ]);

            $product->categories()->sync($request->post('categories'));

            if($request->hasFile('images')) {
                collect($request->images)->each(
                    fn ($file) => $product
                        ->addMedia($file)
                        ->withResponsiveImages()
                        ->toMediaCollection('products')
                );
            }

            if($request->has('units')) {
                foreach ($request->post('units') as $unit) {
                    $product->units()->attach($unit['unit'], [
                        'value' => $unit['value']
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => __('Updated successfully')
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
     * @param  \App\Models\Product\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            $product->delete();
            $product->skus()->delete();

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

            $product = Product::withTrashed()->findOrFail($id);
            $product->attributes()->detach();
            $product->categories()->detach();
            $product->units()->detach();
            $product->skus()->forceDelete();
            $product->media()->delete();
            $product->forceDelete();

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

            $products = Product::whereIn('id', $request->post('ids'))->get();
            $products->each(fn($product) => $product->skus()->delete());
            $products->each(fn($product) => $product->delete());

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

            $products = Product::query()
                ->withTrashed()
                ->whereIn('id', $request->post('ids'))
                ->get();

            $products->each(fn($product) => $product->attributes()->detach());
            $products->each(fn($product) => $product->categories()->detach());
            $products->each(fn($product) => $product->units()->detach());
            $products->each(fn($product) => $product->skus()->forceDelete());
            $products->each(fn($product) => $product->media()->delete());
            $products->each(fn($product) => $product->forceDelete());

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

            $product = Product::withTrashed()->findOrFail($id)->firstOrFail();
            $product->restore();
            $product->skus()->restore();

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

            $products = Product::query()
                ->withTrashed()
                ->whereIn('id', $request->post('ids'))
                ->get();

            $products->each(fn($product) => $product->skus()->restore());
            $products->each(fn($product) => $product->restore());

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
