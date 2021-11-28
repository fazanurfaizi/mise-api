<?php

namespace App\Services\Auth;

use stdClass;
use Carbon\Carbon;
use App\Models\User\User;
use App\Models\Auth\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenManager
{
    public $user;
    public $token;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function createToken(bool $remember = false): self
    {
        $this->token = JWTAuth::fromUser($this->user);

        return $this;
    }

    public function refreshToken(): self
    {
        $this->token = JWTAuth::refresh(true, true);

        return $this;
    }

    public function deleteToken(): void
    {
        Auth::logout(true);
    }

    public function response(): object
    {
        $obj = new stdClass();
        $obj->access_token = $this->token;
        $obj->token_type = 'Bearer';
        $obj->user = $this->user;

        return response()->json([
            'message' => 'success',
            'data' => $obj
        ], 200);
    }

    public static function fromUser(User $user): self
    {
        return new TokenManager($user);
    }

    public static function fromAuth(): self
    {
        $user = Auth::guard(config('auth.defaults.guard'))->user();

        return new TokenManager($user);
    }
}
