<?php

namespace App\Traits\Models;

use App\Exceptions\InvalidLocationException;

trait InteractWithLocation
{
    /**
     * Returns a location depending on the specified argument. If an object is supplied, it is checked if it
     * is an instance of the model Location, if a numeric value is entered, it is retrieved by it's ID.
     *
     * @param mixed $location
     * @throws \App\Exceptions\InvalidLocationException
     * @return mixed
     */
    public function getLocation($location)
    {
        if($this->isModel($location)) {
            return $location;
        }

        throw new InvalidLocationException(__('Invalid Location'));
    }

    /**
     * Returns true/false if the specified model is a subclass
     * of the Eloquent Model.
     *
     * @param mixed $model
     *
     * @return bool
     */
    private function isModel($model)
    {
        return is_subclass_of($model, 'Illuminate\Database\Eloquent\Model');
    }
}
