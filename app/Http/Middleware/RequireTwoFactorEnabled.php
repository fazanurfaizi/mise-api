<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Contracts\Auth\TwoFactorAuthenticatable;

class RequireTwoFactorEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($this->hasTwoFactorAuthDisabled()) {
            return response()->json([
                'message' => __('auth.2fa.enable')
            ], 403);
        }

        return $next($request);
    }

    /**
     * Check if the user has Two-Factor Authentication enabled.
     *
     * @return bool
     */
    protected function hasTwoFactorAuthDisabled(): bool
    {
        $user = auth()->user();

        return $user instanceof TwoFactorAuthenticatable && !$user->hasTwoFactorEnabled();
    }
}
