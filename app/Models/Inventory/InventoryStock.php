<?php

namespace App\Models\Inventory;

use App\Models\Product\ProductSku;
use App\Traits\Models\HasInventoryStocks;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryStock extends Model
{
    use HasFactory,
        HasInventoryStocks,
        SoftDeletes;

    /**
     * Fields that can be mass assigned
     *
     * @var array
     */
    protected $fillable = [
        'warehouse_id',
        'product_sku_id',
        'quantity',
        'aisle',
        'row',
        'bin'
    ];

    /**
     * Guarded fields from mass assign
     *
     * @var array
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    /**
     * Local Scope to find item by sku on the inventory
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $sku
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindItemBySku(Builder $query, string $sku): Builder
    {
        return $query->whereHas('product.code', function ($q) use ($sku) {
            $q->where('code', 'LIKE', '%'. $sku .'%');
        });
    }

    /**
     * Local scope to find an item based on product name
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $sku
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindItem(Builder $query, string $product): Builder
    {
        return $query->whereHas('product.product', function ($q) use ($product) {
            return $q->where('name', 'LIKE', '%'. $product .'%');
        });
    }

    /**
     * Get the warehouse that owns the InventoryStock
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the product that owns the InventoryStock
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductSku::class, 'product_sku_id');
    }

    /**
     * Get all of the movements for the InventoryStock
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movements(): HasMany
    {
        return $this->hasMany(InventoryStockMovement::class, 'stock_id', 'id');
    }
}
