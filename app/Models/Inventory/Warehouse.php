<?php

namespace App\Models\Inventory;

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
        'name',
        'description',
        'email',
        'address',
        'city',
        'zipcode',
        'phone_number',
        'is_default'
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
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function($warehouse) {
            if($warehouse->is_default) {
                static::query()->update(['is_default' => false]);
            }
        });

        static::updating(function($warehouse) {
            if($warehouse->is_default) {
                static::query()->update(['is_default' => false]);
            }
        });
    }

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
