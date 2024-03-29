<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\User\User;
use App\Models\Auth\UserVerification;
use App\Events\EmailWasVerifiedEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailVerificationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $token): JsonResponse
    {
        try {
            DB::beginTransaction();

            if (!$request->hasValidSignature()) {
                return response()->json([
                    'message' => __('Your verification link is expired!')
                ], Response::HTTP_UNAUTHORIZED);
            }

            $verification = UserVerification::where('token', $token)->first();

            $user = User::where('email', $verification->email)->first();

            if(!$user->hasVerifiedEmail() && $user->markEmailAsVerified()) {
                event(new EmailWasVerifiedEvent($user));

                $verification->delete();

                DB::commit();

                return response()->json([
                    'message' => __('Email successfully verified')
                ], Response::HTTP_OK);
            }

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => config('app.env') === 'local' ? $e->getMessage() : __('Invalid token for email verification')
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
