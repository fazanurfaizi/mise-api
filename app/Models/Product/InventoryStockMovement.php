<?php

namespace App\Models\Product;

use App\Traits\Models\HasItemMovements;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryStockMovement extends Model
{
    use HasFactory;

    /**
     * Fields that can be mass assigned
     *
     * @var arary
     */
    protected $fillable = [
        'stock_id',
        'before',
        'after',
        'cost',
        'reason'
    ];

    /**
     * Guarded fields that can't be mass assign
     *
     * @var array
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];
}
