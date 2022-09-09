<?php

namespace App\Models\Access;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RolePermission extends Pivot
{
    protected $table = 'role_has_permissions';
}
