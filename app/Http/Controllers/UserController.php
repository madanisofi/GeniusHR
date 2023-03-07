<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Mail\UserCreate;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\Utility;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage User')) {
            $user = Auth::user();
            if (Auth::user()->type == 'super admin') {
                $users = User::where('created_by', '=', $user->creatorId())->where('type', '=', 'company')->get();
            } else {
                $users = User::where('created_by', '=', $user->creatorId())->where('type', '!=', 'employee')->get();
            }

            return view('user.index', compact('users'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create User')) {
            $user  = Auth::user();
            $roles = Role::where('created_by', '=', $user->creatorId())->where('name', '!=', 'employee')->get()->pluck('name', 'id');

            return view('user.create', compact('roles'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create User')) {
            $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
            $validator        = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|unique:users',
                    'password' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            if (Auth::user()->type == 'super admin') {
                $user = User::create(
                    [
                        'name' => $request['name'],
                        'email' => $request['email'],
                        'password' => Hash::make($request['password']),
                        'type' => 'company',
                        'role_id' => DB::table('roles')->where('name', 'company')->first()->id,
                        'plan' => $plan = Plan::where('price', '<=', 0)->first()->id,
                        'lang' => !empty($default_language) ? $default_language->value : '',
                        'created_by' => Auth::user()->id,
                    ]
                );

                $user->assignRole('Company');
                Utility::jobStage($user->id);
                $role_r = Role::findById(2);

                $role             = new Role();
                $role->name       = 'employee';
                $role->level       = 0;
                $role->created_by  = $user->id;
                $role->save();

                $role2             = new Role();
                $role2->name       = 'hr';
                $role2->created_by  = $user->id;
                $role2->save();
            } else {
                $objUser    = Auth::user();
                $total_user = $objUser->countUsers();
                $plan       = Plan::find($objUser->plan);

                if ($total_user < $plan->max_users || $plan->max_users == -1) {
                    $role_r = Role::findById($request->role);
                    $user   = User::create(
                        [
                            'name' => $request['name'],
                            'email' => $request['email'],
                            'password' => Hash::make($request['password']),
                            'type' => $role_r->name,
                            'role_id' => $request->role,
                            'lang' => !empty($default_language) ? $default_language->value : '',
                            'created_by' => Auth::user()->id,
                        ]
                    );
                    $user->assignRole($role_r);
                } else {
                    return back()->with('error', __('Your user limit is over, Please upgrade plan.'));
                }
            }

            $setings = Utility::settings();
            if ($setings['create_user'] == 1) {

                $user->type     = $role_r->name;
                $user->password = $request['password'];
                try {
                    Mail::to($user->email)->send(new UserCreate($user));
                } catch (\Exception $e) {
                    $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                }

                return redirect()->route('user.index')->with('success', __('User successfully created.') . (isset($smtp_error) ? $smtp_error : ''));
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function show()
    {
        return view('profile.index');
    }

    public function edit($id)
    {
        if (Auth::user()->can('Edit User')) {
            $user  = User::find($id);
            $roles = Role::where('created_by', '=', $user->creatorId())->where('name', '!=', 'employee')->get()->pluck('name', 'id');

            return view('user.edit', compact('user', 'roles'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'unique:users,email,' . $id,
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return back()->with('error', $messages->first());
        }

        if (Auth::user()->type == 'super admin') {
            $user  = User::findOrFail($id);
            $input = $request->all();
            $user->fill($input)->save();
        } else {
            $user = User::findOrFail($id);

            $role          = Role::findById($request->role);
            $input         = $request->all();
            $input['type'] = $role->name;
            $user->fill($input)->save();

            $user->assignRole($role);
        }

        return redirect()->route('user.index')->with('success', 'User successfully updated.');
    }


    public function destroy($id)
    {
        if (Auth::user()->can('Delete User')) {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('user.index')->with('success', 'User successfully deleted.');
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function profile()
    {
        $userDetail = Auth::user();

        return view('user.profile')->with('userDetail', $userDetail);
    }

    public function editprofile(Request $request)
    {
        $userDetail = Auth::user();
        $user       = User::findOrFail($userDetail['id']);

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                'profile' => 'image|mimes:jpeg,png,jpg,svg|max:3072',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return back()->with('error', $messages->first());
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

        if (Auth::user()->type == 'employee') {
            $employee        = Employee::where('user_id', $user->id)->first();
            $employee->email = $request['email'];
            $employee->save();
        }

        return back()->with(
            'success',
            'Profile successfully updated.'
        );
    }

    public function userPassword($id)
    {
        $eId        = Crypt::decrypt($id);

        $user = User::find($eId);

        $employee = User::where('id', $eId)->first();

        return view('user.reset', compact('user', 'employee'));
    }

    public function userPasswordReset(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required|confirmed|same:password_confirmation',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return back()->with('error', $messages->first());
        }


        $user                 = User::where('id', $id)->first();
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return redirect()->route('user.index')->with(
            'success',
            'User Password successfully updated.'
        );
    }


    public function updatePassword(Request $request)
    {
        if (Auth::Check()) {
            $request->validate(
                [
                    'current_password' => 'required',
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
                ]
            );
            $objUser          = Auth::user();
            $request_data     = $request->All();
            $current_password = $objUser->password;
            if (Hash::check($request_data['current_password'], $current_password)) {
                $user_id            = Auth::User()->id;
                $obj_user           = User::find($user_id);
                $obj_user->password = Hash::make($request_data['new_password']);;
                $obj_user->save();

                return redirect()->route('profile', $objUser->id)->with('success', __('Password successfully updated.'));
            } else {
                return redirect()->route('profile', $objUser->id)->with('error', __('Please enter correct current password.'));
            }
        } else {
            return redirect()->route('profile', Auth::user()->id)->with('error', __('Something is wrong.'));
        }
    }


    public function upgradePlan($user_id)
    {
        $user = User::find($user_id);

        $plans = Plan::get();

        return view('user.plan', compact('user', 'plans'));
    }

    public function activePlan($user_id, $plan_id)
    {

        $user       = User::find($user_id);
        $assignPlan = $user->assignPlan($plan_id);
        $plan       = Plan::find($plan_id);
        if ($assignPlan['is_success'] == true && !empty($plan)) {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            Order::create(
                [
                    'order_id' => $orderID,
                    'name' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'price' => $plan->price,
                    'price_currency' => !empty(env('CURRENCY')) ? env('CURRENCY') : '$',
                    'txn_id' => '',
                    'payment_status' => 'succeeded',
                    'receipt' => null,
                    'user_id' => $user->id,
                ]
            );

            return back()->with('success', 'Plan successfully upgraded.');
        } else {
            return back()->with('error', 'Plan fail to upgrade.');
        }
    }

    public function notificationSeen($user_id)
    {
        Notification::where('user_id', '=', $user_id)->update(['is_read' => 1]);

        return response()->json(['is_success' => true], 200);
    }
}
