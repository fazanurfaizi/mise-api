<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User\User;
use App\Models\Access\Role;
use App\Models\Access\UserRole;
use App\Models\Auth\UserVerification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;

class RegisterController extends Controller
{

    public function register(RegisterRequest $request) {

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->get('name'),
                'username' => $request->get('username'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            $role = $this->getCustomerRole();

            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role->id
            ]);

            UserVerification::create([
                'email' => $user->email,
                'token' => (string) Str::uuid()
            ]);

            event(new Registered($user));

            DB::commit();

            $message = __(
                'We sent a confirmation email to :email. Please follow the instructions to complete your registration.',
                ['email' => $user->email]
            );

            return response()->json([
                'message' => $message
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    protected function getCustomerRole()
    {
        $role = Role::where('name', 'customer')->first();

        if (is_null($role)) {
            $role = Role::create([
                'name' => 'customer'
            ]);
        }

        return $role;
    }

}
