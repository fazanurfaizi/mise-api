<?php

namespace App\Traits\Models;

use App\Exceptions\InvalidWarehouseException;

trait InteractWithWarehouse
{
    /**
     * Returns a warehouse depending on the specified argument. If an object is supplied, it is checked if it
     * is an instance of the model warehouse, if a numeric value is entered, it is retrieved by it's ID.
     *
     * @param mixed $warehouse
     * @throws \App\Exceptions\InvalidWarehouseException
     * @return mixed
     */
    public function getWarehouse($warehouse)
    {
        if($this->isModel($warehouse)) {
            return $warehouse;
        }

        throw new InvalidWarehouseException(__('Invalid Warehouse'));
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
