<?php

namespace App\Http\Controllers\Admin\Product;

use Exception;
use App\Models\Product\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
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
            'data' => $products
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
            'data' => $products
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load(['categories', 'variants']);

        return response()->json([
            'data' => $product
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
