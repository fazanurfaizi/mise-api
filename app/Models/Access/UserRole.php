<?php

namespace App\Models\Access;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserRole extends Pivot
{
    protected $table = 'model_has_roles';
}
