<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function validation()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getCode());
        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getCode());
        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getCode());
        }

        return response()->json([
            'type' => 'success',
            'data' => $user
        ]);
    }

    public function getUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $userProfile = User::selectRAW('id, name, email, type, avatar')->where('id', $request->user_id)->first();

        $profile = asset(url('uploads/avatar/'));

        $position = $userProfile->employee;

        if (!empty($position->position)) {
            $position = $position->position->name;
        } else {
            $position = $userProfile->type;
        }

        if (!empty($userProfile)) {
            return response()->json([
                'type' => 'success',
                'message' => 'available',
                'data' => array(
                    'id' => $userProfile->id,
                    'name' => $userProfile->name,
                    'email' => $userProfile->email,
                    'type' => $userProfile->type,
                    'type_full' => ucfirst($position),
                    'avatar' => (!empty($userProfile->avatar) ? $profile . '/' . $userProfile->avatar : $profile . '/avatar.png')
                )
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'user not found.',
                'data' => []
            ]);
        }
    }

    public function editProfile(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $userDetail = User::find($request->user_id);

        if (!empty($userDetail)) {

            $user = User::findOrFail($userDetail->id);

            $validation_email = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                    'profile' => 'image|mimes:jpeg,png,jpg,svg|max:3072',
                ]
            );

            if ($validation_email->fails()) {
                $messages = $validation_email->getMessageBag();

                return response()->json([
                    'type' => 'error',
                    'message' =>  $messages->first()
                ]);
            }

            if ($request->hasFile('profile')) {
                $filenameWithExt = $request->file('profile')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('profile')->getClientOriginalExtension();
                // hash file before store
                $filename = md5($filename . time());
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir             = storage_path('uploads/avatar/');
                $image_path      = $dir . $userDetail['avatar'];

                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $path = $request->file('profile')->storeAs('uploads/avatar/', $fileNameToStore);
            }

            if (!empty($request->profile)) {
                $user['avatar'] = $fileNameToStore;
            }
            $user['name']  = $request['name'];
            $user['email'] = $request['email'];
            $user->save();

            if ($userDetail->type == 'employee') {
                $employee        = Employee::where('user_id', $userDetail->id)->first();
                $employee->name = $request['name'];
                $employee->email = $request['email'];
                $employee->save();
            }

            return response()->json([
                'type' => 'success',
                'message' => 'Profile successfully updated.'
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'user not found.'
            ]);
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $detailUser = User::find($request->user_id);

        if (!empty($detailUser)) {
            $validation = Validator::make(
                $request->all(),
                [
                    'current_password' => 'required',
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
                ]
            );

            if ($validation->fails()) {
                $messages = $validation->getMessageBag();

                return response()->json([
                    'type' => 'error',
                    'message' =>  $messages->first()
                ]);
            }

            $objUser          = User::find($request->user_id);
            $request_data     = $request->All();
            $current_password = $objUser->password;
            if (Hash::check($request_data['current_password'], $current_password)) {
                $user_id            = $objUser->id;
                $obj_user           = User::find($user_id);
                $obj_user->password = Hash::make($request_data['new_password']);;
                $obj_user->save();

                return response()->json([
                    'type' => 'success',
                    'message' => 'Password successfully updated.'
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Please enter correct current password.'
                ]);
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'user not found.'
            ]);
        }
    }

    public function checkActive(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {
            return response()->json([
                'type' => 'success',
                'message' => 'Status employee is ' . ($user->is_active == 1 ? 'active' : 'not active'),
                'status' => $user->is_active
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }

    public function userPin(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'pin' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {

            $user->pin = Hash::make($request->pin);
            $user->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Setup PIN successfully.'
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }

    public function checkPin(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'pin' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {

            $current_pin = $user->pin;

            if ($current_pin == null) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'PIN not setup.'
                ]);
            }

            if (Hash::check($request->pin, $current_pin)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'PIN match.'
                ]);
            } else {
                return response()->json([
                    'type' => 'failed',
                    'message' => 'PIN does not match.'
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
