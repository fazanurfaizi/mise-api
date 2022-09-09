<?php

namespace App\Services\Auth;

use stdClass;
use Carbon\Carbon;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class TokenManager
{
    public $user;
    public $token;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function createToken(Request $request, bool $remember = false): self
    {
        if ($remember) {
            $expired = Carbon::now()->addMinutes(config('auth.expired_token.remember'))->timestamp;
        } else {
            $expired = Carbon::now()->addMinutes(config('auth.expired_token.default'))->timestamp;
        }

        $this->token = JWTAuth::customClaims(['exp' => $expired])
            ->fromUser($this->user);

        return $this;
    }

    // Pass true as the first param to force the token to be blacklisted "forever".
    // The second parameter will reset the claims for the new token
    public function refreshToken(bool $blacklisted = false, bool $reset = false): self
    {
        $this->token = JWTAuth::refresh($blacklisted, $reset);

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
