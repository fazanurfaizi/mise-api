<?php

namespace App\Services\Auth;

use App\Models\Access\UserRole;
use App\Models\Access\Permission;
use App\Models\Access\RolePermission;
use Illuminate\Support\Facades\Auth;

class AuthenticateUser
{
    public static function getUser()
    {
        $user = Auth::guard(config('auth.defaults.guard'))->user();

        if(!is_null($user)) {
            $user->roles = self::getRoles($user->id);
            $user->permissions = self::getPermissions($user->id);
        }

        return $user;
    }

    private static function getRoles($user_id)
    {
        return UserRole::join('roles', 'roles.id', '=', 'user_has_roles.role_id')
            ->where('model_id', $user_id)
            ->select('roles.*')
            ->get();
    }

    private static function getPermissions($user_id)
    {
        return RolePermission::join('user_has_roles', 'role_has_permissions.role_id', '=', 'user_has_roles.role_id')
            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->where('user_has_roles.model_id', $user_id)
            ->select('permissions.id', 'permissions.name')
            ->get();
    }

    public static function isAllowedTo($permissions_string)
    {
        if (is_null($permissions_string)) {
            return true;
        }
        $permissions = explode(',', $permissions_string);
        $user = self::getUser();

        $user_permissions = isset($user) ? $user->permissions : [];
        $user_permissions = collect($user_permissions)->pluck('name')->toArray();

        $intersect = array_intersect($permissions, $user_permissions);

        if (count($intersect) > 0) {
            return true;
        }

        return false;
    }

    public static function ignore($permissions_string = '')
    {
        $permissions = explode(',', $permissions_string);
        $public_permissions = Permission::get();
        $public_permissions = collect($public_permissions)->pluck('name')->toArray();

        $intersect = array_intersect($permissions, $public_permissions);

        if (count($intersect) > 0) {
            return true;
        }

        return false;
    }
}
