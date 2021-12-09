<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory,
        SoftDeletes;

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description'
    ];

    /**
     * Guarded fields
     *
     * @var array
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    /**
     * Get all of the items for the Warehouse
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(InventoryStock::class);
    }
}
