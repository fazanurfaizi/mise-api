<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Auth\UserVerification
 *
 * @property int $id
 * @property string $email
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification whereToken($value)
 * @mixin \Eloquent
 */
class UserVerification extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'email',
        'token',
    ];
}
