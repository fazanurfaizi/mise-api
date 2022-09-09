<?php

namespace App\Models\Access;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserRole extends Pivot
{
    protected $table = 'user_has_roles';
}
