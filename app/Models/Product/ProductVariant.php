<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Product\ProductVariant
 *
 * @property int $id
 * @property int $product_id
 * @property int $product_sku_id
 * @property int $attribute_id
 * @property int $attribute_value_id
 * @property-read \App\Models\Product\ProductAttribute $attribute
 * @property-read \App\Models\Product\ProductAttributeValue $option
 * @property-read \App\Models\Product\Product $product
 * @property-read \App\Models\Product\ProductSku $productSku
 * @method static \Database\Factories\Product\ProductVariantFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereAttributeValueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereProductSkuId($value)
 * @mixin \Eloquent
 */
class ProductVariant extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * Fields that can be mass assigned
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'product_sku_id',
        'attribute_id',
        'attribute_value_id'
    ];

    /**
     * Protected fields during mass assigned
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Get the product that owns the ProductVariant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the productSku that owns the ProductVariant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productSku(): BelongsTo
    {
        return $this->belongsTo(ProductSku::class, 'product_sku_id');
    }

    /**
     * Get the attribute that owns the ProductVariant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id');
    }

    /**
     * Get the option that owns the ProductVariant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(ProductAttributeValue::class, 'attribute_value_id');
    }
}
