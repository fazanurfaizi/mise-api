<?php

namespace App\Models\Product;

use App\Models\Concerns\SelfReference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Variant extends Model
{
    use HasFactory,
        SoftDeletes,
        SelfReference;

    protected $fillable = [
        'parent_id',
        'name'
    ];

    /**
     * The products that belong to the Variant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, ProductVariant::class);
    }

    /**
     * Get all of the variants for the Variant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function variants(): HasManyThrough
    {
        return $this->hasManyThrough(Comment::class, Post::class);
    }
}
