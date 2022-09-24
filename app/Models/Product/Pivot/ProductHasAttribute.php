<?php

namespace App\Models\Product\Pivot;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\Product\ProductHasAttribute
 *
 * @property int $id
 * @property int $product_id
 * @property int $attribute_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product\ProductAttribute $attribute
 * @property-read \App\Models\Product\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasAttribute whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasAttribute whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasAttribute whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductHasAttribute extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $table = "product_has_attribute";

    /**
     * Get the product that owns the ProductHasAttribute
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get the attribute that owns the ProductHasAttribute
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id', 'id');
    }
}
