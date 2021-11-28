<?php

namespace App\Http\Controllers\Auth;

use DB;
use Exception;
use Carbon\Carbon;
use App\Models\User\User;
use App\Models\Auth\UserVerification;
use App\Events\EmailWasVerifiedEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class EmailVerificationController extends Controller
{
    public function verify($token): JsonResponse
    {
        try {
            DB::beginTransaction();

            $verification = UserVerification::where('token', $token)->first();

            if(Carbon::parse($verification->created_at)->addSeconds(60 * 60)->isPast()) {
                $verification->delete();

                DB::commit();

                return response()->json([
                    'message' => __('Token was expired')
                ], Response::HTTP_BAD_REQUEST);
            }

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
