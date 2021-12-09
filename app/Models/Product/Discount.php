<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'discount_type',
        'discount_value',
        'time_start',
        'time_end',
        'date_start',
        'date_end',
        'active'
    ];

    /**
     * Get all of the productDetails for the Discount
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productDetails(): HasMany
    {
        return $this->hasMany(ProductDetail::class);
    }
}
