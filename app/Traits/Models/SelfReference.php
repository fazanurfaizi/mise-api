<?php

namespace App\Traits\Models;

trait SelfReference
{
    protected $parentColumn = 'parent_id';

    public function parent()
    {
        return $this->belongsTo(static::class);
    }

    public function children()
    {
        return $this->hasMany(static::class, $this->parentColumn);
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    public function root()
    {
        return $this->parent
            ? $this->parent->root()
            : $this;
    }

    /**
	 * Assert if the Category is Parent
	 *
	 * @return bool
	 */
	public function isParent(): bool
	{
		return is_null($this->parent_id);
	}

	/**
	 * Local scope for getting only the parents
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeParentOnly($query)
	{
		return $query->whereNull('parent_id');
	}
}
