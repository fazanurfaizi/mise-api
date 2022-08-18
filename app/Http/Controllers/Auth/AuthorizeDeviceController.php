<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\Auth\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Token;

class AuthorizeDeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $devices = PersonalAccessToken::where('user_id', $request->user()->id)->get();

        return response()->json([
            'data' => $devices
        ], Response::HTTP_OK);
    }

    /**
     * Logout a device based on device id.
     *
     * @return \Illuminate\Http\Response
     */
    public function logoutDevice(Request $request, $id)
    {
        try {
            $device = PersonalAccessToken::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->first();

            try {
                $decrypted = Crypt::decrypt($device->token);

                JWTAuth::manager()->invalidate(new Token($decrypted), $forceForever = false);

                $device->delete();

            } catch (DecryptException $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'message' => __('Device has already logged out!')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
