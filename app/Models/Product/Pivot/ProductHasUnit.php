<?php

namespace App\Models\Product\Pivot;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\Product\ProductHasUnit
 *
 * @property int $id
 * @property int $product_id
 * @property int $unit_id
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product\Product $product
 * @property-read \App\Models\Product\ProductUnit $unit
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasUnit whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasUnit whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasUnit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductHasUnit whereValue($value)
 * @mixin \Eloquent
 */
class ProductHasUnit extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $table = "product_has_unit";

    protected $fillable = [
        'value'
    ];

    /**
     * Get the product that owns the ProductHasUnit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get the unit that owns the ProductHasUnit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class, 'unit_id', 'id');
    }
}
