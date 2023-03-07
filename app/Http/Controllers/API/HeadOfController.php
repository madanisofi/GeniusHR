<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HeadOfController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {

            $creator = $user->created_by;

            if ($user->type == 'employee') {
                $headof = User::selectRaw('users.id as id, users.name as name')->join('roles as r', 'users.type', '=', 'r.name')->where('level', '>', 0)->where('users.created_by', '=', $creator)->get();
            } else {
                $headof = User::selectRaw('users.id as id, users.name as name')->where('users.id', '=', $creator)->get();
            }

            if (empty($headof)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {
                return response()->json([
                    'type' => 'success',
                    'message' => 'available',
                    'data' => $headof
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
