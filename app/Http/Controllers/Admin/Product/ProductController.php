<?php

namespace App\Http\Controllers\Admin\Product;

use Exception;
use App\Actions\Products\Product\CreateProduct;
use App\Actions\Products\Product\GetProducts;
use App\Actions\Products\Product\UpdateProduct;
use App\Models\Product\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $this->authorize('product-viewAny');

        return response()->json([
            'data' => new ProductCollection(
                GetProducts::run($request)
            )
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
        $this->authorize('product-viewBin');

        return response()->json([
            'data' => new ProductCollection(
                GetProducts::run($request, onlyTrashed: true)
            )
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
            $this->authorize('product-store');

            CreateProduct::run($request);

            return response()->json([
                'message' => __('Created successfully')
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {

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
        $this->authorize('product-view');

        $product->load(['brand', 'categories', 'units', 'media', 'variants']);

        return response()->json([
            'data' => new ProductResource($product)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Product\UpdateProductRequest $request
     * @param  \App\Models\Product\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $this->authorize('product-update');

            UpdateProduct::run($request, $product);

            return response()->json([
                'message' => __('Updated successfully')
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
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
            $this->authorize('product-delete');

            $product->delete();

            return response()->json([
                'message' => __('Deleted successfully')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
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
            $this->authorize('product-delete');

            Product::query()
                ->withTrashed()
                ->findOrFail($id)
                ->forceDelete();

            return response()->json([
                'message' => __('Deleted successfully')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
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
            $this->authorize('product-delete');

            Product::query()
                ->whereIn('id', $request->post('ids'))
                ->get()
                ->each
                ->delete();

            return response()->json([
                'message' => __('Deleted successfully')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
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
            $this->authorize('product-delete');

            Product::query()
                ->withTrashed()
                ->whereIn('id', $request->post('ids'))
                ->get()
                ->each
                ->forceDelete();

            return response()->json([
                'message' => __('Deleted successfully')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
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
            $this->authorize('product-restore');

            Product::query()
                ->withTrashed()
                ->findOrFail($id)
                ->restore();

            return response()->json([
                'message' => __('Restored successfully')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
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
            $this->authorize('product-restore');

            Product::query()
                ->withTrashed()
                ->whereIn('id', $request->post('ids'))
                ->get()
                ->each
                ->restore();

            return response()->json([
                'message' => __('Restored successfully')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
