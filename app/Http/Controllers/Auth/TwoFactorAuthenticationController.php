<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Http\Requests\Auth\DisableTwoFactorAuthenticationRequest;
use App\Http\Requests\Auth\EnableTwoFactorAuthenticationRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

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
            'google2fa_url' => $secret->toUri(),
            'recoverCodes' => $request->user()->generateRecoveryCodes()
        ], Response::HTTP_OK);
    }
}
