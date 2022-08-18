<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductSku extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Fields that can be mass assigned
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'code', 'price', 'cost'
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

    /**
     * Get all of the stocks for the ProductSku
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(InventoryStock::class);
    }
}
