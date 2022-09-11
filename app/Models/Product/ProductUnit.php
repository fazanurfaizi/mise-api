<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        ->withTimestamps()
        ->whereNull('product_has_unit.deleted_at');
    }
}
