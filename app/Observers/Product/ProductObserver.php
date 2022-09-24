<?php

namespace App\Observers\Product;

use App\Models\Product\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        $product->skus()->delete();
    }

    /**
     * Handle the Product "deleting" event.
     *
     * @param  \App\Models\Product\Product  $product
     * @return void
     */
    public function deleting(Product $product)
    {
        if ($product->isForceDeleting()) {
            $product->attributes()->detach();
            $product->categories()->detach();
            $product->units()->detach();
            $product->skus()->forceDelete();
            $product->media()->delete();
        }
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Product\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        $product->skus()->restore();
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Product\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
