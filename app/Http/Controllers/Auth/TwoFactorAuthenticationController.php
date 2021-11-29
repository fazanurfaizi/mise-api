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

                return response()->json([
                    'message' => __('Activate 2-Step Verification - Google Authenticator.')
                ], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            Log::error(ExceptionFormat::log($e));
        }

        return response()->json([
            'message' => __('Invalid 2FA verification code. Please try again')
        ], Response::HTTP_LOCKED);
    }
}
