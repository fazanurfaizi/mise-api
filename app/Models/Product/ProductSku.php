<?php

namespace App\Models\Product;

use App\Traits\Models\HasItemStocks;
use App\Traits\Models\HasPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Product\ProductSku
 *
 * @property int $id
 * @property int $product_id
 * @property string $code
 * @property string $price
 * @property string $cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Product\Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Inventory\InventoryStock[] $stocks
 * @property-read int|null $stocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product\ProductVariant[] $variants
 * @property-read int|null $variants_count
 * @method static \Database\Factories\Product\ProductSkuFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSku newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSku newQuery()
 * @method static \Illuminate\Database\Query\Builder|ProductSku onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSku query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSku whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSku whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSku whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSku whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSku whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSku wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSku whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSku whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ProductSku withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProductSku withoutTrashed()
 * @mixin \Eloquent
 */
class ProductSku extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasItemStocks;
    use HasPrice;

    /**
     * Fields that can be mass assigned
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'code',
        'price',
        'cost'
    ];

    /**
     * Fields that are guarded during mass assign
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Get the product that owns the ProductSku
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all of the variants for the ProductSku
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'product_sku_id');
    }
}
