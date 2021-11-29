<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Support\ExceptionFormat;
use App\Http\Requests\Auth\DisableTwoFactorAuthenticationRequest;
use App\Http\Requests\Auth\EnableTwoFactorAuthenticationRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class TwoFactorAuthenticationController extends Controller
{
    /**
     * Generate a new 2fa entry for current loggedd user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function generate(Request $request): JsonResponse
    {
        $secret = $request->user()->createTwoFactorAuth();

        return response()->json([
            'message' => __('Secret Key generated. Follow the next steps'),
            'google2fa_qr' => $secret->toQr(),
            'google2fa_secret' => $secret->toString(),
            'google2fa_url' => $secret->toUri()
        ], Response::HTTP_OK);
    }

    /**
     * Enable the previously generated 2fa.
     *
     * @param EnableTwoFactorAuthenticationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable(EnableTwoFactorAuthenticationRequest $request)
    {
        try {
            if($request->user()->confirmTwoFactorAuth($request->get('code'))) {
                $request->user()->generateRecoveryCodes();

                Cache::put("2fa.{$request->ip()}-{$request->user()->id}", now()->timestamp, config('auth2fa.confirm.timeout', 10800));

                return response()->json([
                    'message' => __('auth.2fa.enabled')
                ], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            Log::error(ExceptionFormat::log($e));
        }

        return response()->json([
            'message' => __('auth.2fa.enable')
        ], Response::HTTP_LOCKED);
    }

    /**
     * Disable the 2fa of current logged user.
     *
     * @param DisableTwoFactorAuthenticationRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function disable(DisableTwoFactorAuthenticationRequest $request)
    {
        $user = $request->user();

        if(!Hash::check($request->get('password'), $user->password)) {
            return response()->json([
                'message' => __('auth.password')
            ], Response::HTTP_BAD_REQUEST);
        }

        $user->disableTwoFactorAuth();

        return response()->json([
            'message' => __('auth.2fa.disabled')
        ], Response::HTTP_OK);
    }
}
