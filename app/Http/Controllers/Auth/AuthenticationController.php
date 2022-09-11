<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\LockedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User\User;
use App\Models\Auth\UserVerification;
use App\Notifications\VerifyEmailNotification;
use App\Services\Auth\TokenManager;
use App\Services\Auth\AuthenticateUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class AuthenticationController extends Controller
{

    public function __construct() {
        $this->middleware(['verified'])->except('login');
    }

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

        DB::beginTransaction();

        if(Auth::attempt($credentials)) {
            try {
                $user = Auth::user();
                $this->checkIfUserHasVerifiedEmail($user, $request);

                DB::commit();
                return TokenManager::fromUser($user)->createToken($request, $remember)->response();
            } catch (LockedException $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ], Response::HTTP_LOCKED);
            }
        } else {
            DB::rollback();

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
        $this->middleware(['verified']);

        $user = AuthenticateUser::getUser();

        return response()->json($user);
    }

    public function refresh()
    {
        try {
            $this->middleware(['verified']);

            return TokenManager::fromAuth()->refreshToken(true, true)->response();
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->middleware(['verified']);

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

            UserVerification::where('email', $user->email)->delete();

            UserVerification::create([
                'email' => $user->email,
                'token' => (string) Str::uuid()
            ]);

            Notification::send($user, new VerifyEmailNotification($user->userVerification->token));

            // logout
            $this->logout($request);

            $message = __(
                'We sent a confirmation email to :email. Please follow the instructions to complete your registration.',
                ['email' => $user->email]
            );

            throw new LockedException($message);
        }
    }
}
