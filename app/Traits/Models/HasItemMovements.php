<?php

namespace App\Traits\Models;

use App\Models\Product\InventoryStock;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasItemMovements
{
    /**
     * Get the stock that owns the HasItemMovements
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(InventoryStock::class, 'stock_id', 'id');
    }

    /**
     * Rolls back the current movement.
     *
     * @param bool $recursive
     *
     * @return mixed
     */
    public function rollback($recursive = false)
    {
        return $this->stock->rollback($this, $recursive);
    }
}
