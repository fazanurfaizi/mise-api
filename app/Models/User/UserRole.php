<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\User\UserRole
 *
 * @property int $role_id
 * @property string $model_type
 * @property int $model_id
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereRoleId($value)
 * @mixin \Eloquent
 */
class UserRole extends Pivot
{
    protected $table = 'user_has_roles';
}
