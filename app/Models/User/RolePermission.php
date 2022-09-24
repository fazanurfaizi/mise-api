<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RolePermission extends Pivot
{
    protected $table = 'role_has_permissions';
}
