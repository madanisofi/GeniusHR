<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'fcm_token' => 'required'
        ]);

        try {
            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'invalid_credentials'
                ]);
            }
        } catch (JWTException $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'could_not_create_token'
            ]);
        }

        if (JWTAuth::user()->is_active != 1) {
            return response()->json([
                'type' => 'error',
                'message' => 'account is disable'
            ]);
        }

        // update FCM_token
        $user = User::find(JWTAuth::User()->id);
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'type' => 'success',
            'message' => 'Logged In',
            'token' => $token,
            'user_id' => JWTAuth::User()->id
        ]);
    }
}
