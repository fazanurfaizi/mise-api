<?php

namespace App\Models\Inventory;

use App\Traits\Models\HasItemMovements;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Inventory\InventoryStockMovement
 *
 * @property int $id
 * @property int $stock_id
 * @property int $before
 * @property int $after
 * @property string|null $cost
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Inventory\InventoryStock $stock
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement query()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InventoryStockMovement extends Model
{
    use HasFactory, HasItemMovements;

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
