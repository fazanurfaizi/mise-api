<?php

namespace App\Http\Controllers\Auth;

use JWTAuth;
use App\Exceptions\LockedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User\User;
use App\Models\Auth\UserVerification;
use App\Notifications\VerifyEmailNotification;
use App\Services\Auth\TokenManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $remember = $request->get('remember', false);
        $username = filter_var($request->get('username'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $request->merge([
            $username => $request->get('username')
        ]);

        $credentials = $request->only($username, 'password');

        if($token = Auth::attempt($credentials)) {
            $user = Auth::user();

            try {

                $this->checkIfUserHasVerifiedEmail($user, $request);

            } catch (LockedException $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ], Response::HTTP_LOCKED);
            }

            return TokenManager::fromUser($user)->createToken($remember)->response();
        } else {
            return response()->json([
                'message' => 'Incorrect email or password'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $payload = JWTAuth::parseToken()->getPayload();

        return response()->json($payload);
    }

    public function logout(Request $request)
    {
        try {
            TokenManager::fromAuth()->deleteToken();

            return response()->json([
                'message' => 'Logout successfully'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    private function checkIfUserHasVerifiedEmail(User $user, Request $request)
    {
        if(!$user->hasVerifiedEmail()) {
            UserVerification::create([
                'email' => $user->email,
                'token' => (string) Str::uuid()
            ]);

            Notification::send($user, new VerifyEmailNotification($user->userVerification->token));

            // logout

            $message = __(
                'We sent a confirmation email to :email. Please follow the instructions to complete your registration.',
                ['email' => $user->email]
            );

            throw new LockedException($message);
        }
    }
}
