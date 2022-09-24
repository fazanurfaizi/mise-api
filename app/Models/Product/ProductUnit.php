<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Product\ProductUnit
 *
 * @property int $id
 * @property string $name
 * @property string|null $symbol
 * @property string|null $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\Product[] $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\Product\ProductUnitFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductUnit newQuery()
 * @method static \Illuminate\Database\Query\Builder|ProductUnit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductUnit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductUnit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductUnit whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductUnit whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductUnit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ProductUnit withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProductUnit withoutTrashed()
 * @mixin \Eloquent
 */
class ProductUnit extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $fillable = [
        'name',
        'symbol',
        'quantity'
    ];

    /**
     * Get all of the products for the ProductUnit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_has_unit',
            'unit_id',
            'product_id',
        )->using(ProductHasCategory::class)
        ->withPivot('value')
        ->withTimestamps()
        ->whereNull('product_has_unit.deleted_at');
    }
}
