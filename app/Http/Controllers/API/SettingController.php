<?php

namespace App\Http\Controllers\API;

use App\Models\Utility;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {

            $creator = $user->created_by;
            $settings = Utility::settings($creator);

            if (empty($settings)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {
                return response()->json([
                    'type' => 'success',
                    'message' => 'available',
                    'data' => $settings
                ]);
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }
}
