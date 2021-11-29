<?php

namespace App\Http\Middleware;

use Closure;
use App\Contracts\Auth\TwoFactorAuthenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class ConfirmTwoFactorCode
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
        if($this->userHasNotEnabledTwoFactorAuth() || $this->codeWasValidated($request)) {
            return $next($request);
        }

        return response()->json([
            'message' => __('auth.2fa.required')
        ]);
    }

    /**
     * Check if the user is using Two-Factor Authentication.
     *
     * @return bool
     */
    protected function userHasNotEnabledTwoFactorAuth(): bool
    {
        $user = auth()->user();

        return !($user instanceof TwoFactorAuthenticatable && $user->hasTwoFactorEnabled());
    }

     /**
     * Determine if the confirmation timeout has expired.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function codeWasValidated(Request $request): bool
    {
        $confirmedAt = now()->timestamp - Cache::get("2fa.{$request->ip()}-{$request->user()->id}", 0);

        return $confirmedAt < config('auth2fa.confirm.timeout', 10800);
    }
}
