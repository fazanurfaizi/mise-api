<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $credentials = $request->only('email');

        $response = $this->broker()->sendResetLink($credentials);

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    protected function broker()
    {
        return Password::broker();
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response): JsonResponse
    {
        $message = __('Email send, please check your email to continue');

        return response()->json([
            'message' => $message
        ], Response::HTTP_OK);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response): JsonResponse
    {
        $message = __('We could not complete your request, are your sure that this email is correct?');

        return response()->json([
            'message' => $message
        ], Response::HTTP_BAD_REQUEST);
    }
}
