<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\InteractsWithMedia;

trait HasMediaTrait
{
    use InteractsWithMedia;

    /**
     * Get the Model that owns the Media
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function morphOneMedia(): MorphOne
    {
        return $this->morphOne(config('media-library.media_model'), 'model')->orderBy('order_column');
    }

    /**
     * Get all of the Media for the Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function morphManyMedia(): MorphMany
    {
        return $this->morphMany(config('media-library.media_model'), 'model')->orderBy('order_column');
    }
}
