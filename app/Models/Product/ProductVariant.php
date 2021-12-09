<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductVariant extends Pivot
{
    /**
     * A membership is a user assigned to a location
     *
     * @var string
     */
    protected $table = 'product_variant';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}
