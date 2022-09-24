<?php

namespace App\Http\Controllers\Admin\Product;

use Exception;
use App\Actions\Products\ProductSku\AttachProductSku;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductSkuRequest;
use App\Http\Resources\Product\ProductVariantResource;
use App\Models\Product\Product;
use App\Models\Product\ProductSku;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductSkuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Product\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        return response()->json([
            'data' => ProductVariantResource::collection($product->getVariations())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Product\StoreProductSkuRequest  $request
     * @param  \App\Models\Product\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductSkuRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();

            AttachProductSku::run($request, $product);

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
     * @param  \App\Models\Product\ProductSku  $sku
     * @return \Illuminate\Http\Response
     */
    public function show(ProductSku $sku)
    {
        return response()->json([
            'data' => new ProductVariantResource($sku),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product\ProductSku $sku
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductSku $sku)
    {
        $sku->delete();

        return response()->json([
            'message' => __('Delete Successfully')
        ]);
    }
}
