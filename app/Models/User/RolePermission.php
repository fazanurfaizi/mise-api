<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\User\RolePermission
 *
 * @property int $permission_id
 * @property int $role_id
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereRoleId($value)
 * @mixin \Eloquent
 */
class RolePermission extends Pivot
{
    protected $table = 'role_has_permissions';
}
