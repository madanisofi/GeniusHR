<?php

namespace App\Http\Controllers;

use App\Models\Utility;
use App\Models\Employee;
use App\Models\User;
use App\Models\AttendanceEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class PresenceController extends Controller
{
    public function index(Request $request)
    {

        if (!isset($request->key) && !isset($request->key2)) {
            return view('qrcode.error');
        }
        $key = $request->key;
        $key2 = $request->key2;

        $decrypt = Crypt::decryptString($key);
        $decryptTime = date('Y-m-d H:i:s', $decrypt);

        $check = User::where('created_at', $decryptTime)->where('id', $key2)->first();

        if (!empty($check)) {
            $created_by = $check->id;
            $settings = Utility::settings($created_by);

            if ($settings['qr_presence'] == 'on') {
                $last_attendance = AttendanceEmployee::selectRaw('attendance_employees.id, employees.name, employees.employee_id, positions.name as position, users.avatar, clock_in, clock_out, late, early_leaving, working_late, permissiontype_id')
                    ->join('employees', 'attendance_employees.employee_id', '=', 'employees.id')
                    ->join('users', 'employees.user_id', '=', 'users.id')
                    ->join('positions', 'employees.position_id', '=', 'positions.id')
                    ->where('attendance_employees.date', date('Y-m-d'))
                    ->where('users.created_by', $created_by)
                    ->where('status', 'Present')
                    ->orderBy('attendance_employees.updated_at', 'DESC')->first();

                return view('qrcode.index', compact('created_by', 'settings', 'last_attendance'));
            } else {
                return view('qrcode.error');
            }
        } else {
            return view('qrcode.error');
        }
    }

    public function getIp()
    {
        if (isset($_SERVER)) {

            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
                return $_SERVER["HTTP_X_FORWARDED_FOR"];

            if (isset($_SERVER["HTTP_CLIENT_IP"]))
                return $_SERVER["HTTP_CLIENT_IP"];

            return $_SERVER["REMOTE_ADDR"];
        }

        if (getenv('HTTP_X_FORWARDED_FOR'))
            return getenv('HTTP_X_FORWARDED_FOR');

        if (getenv('HTTP_CLIENT_IP'))
            return getenv('HTTP_CLIENT_IP');

        return getenv('REMOTE_ADDR');
    }

    public function getTodayPresence(Request $request)
    {

        $created_by = $request->created_by;
        $todayPresence = AttendanceEmployee::selectRaw('employees.name, users.avatar, clock_in, permissiontype_id')
            ->join('employees', 'attendance_employees.employee_id', '=', 'employees.id')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->where('attendance_employees.date', date('Y-m-d'))
            ->where('users.created_by', $created_by)
            ->where('status', 'Present')
            ->orderBy('attendance_employees.updated_at', 'DESC')->get();

        $today      = [];
        $data       = [];
        $profile    = asset(url('uploads/avatar/'));
        if (!empty($todayPresence)) {
            foreach ($todayPresence as $key => $value) {
                # code...
                $avatar = (!empty($value->avatar) ? $profile . '/' . $value->avatar : $profile . '/user.png');
                $name = explode(' ', $value->name);
                $name = $name[0] . ' ' . (isset($name[1]) ? $name[1] : '');
                $today[] = [
                    'name' => $value->name,
                    'avatar' => $avatar
                ];

                $data[] = [

                    'item' => '<div class="article"><div class="item flex items-center justify-center m-1"><img src="' . $avatar . '" /></div><div class="m-1"><center><p class="font-sm md:font-lg xl:font-lg font-inter">' . $name . ' <br> ' . $value->clock_in . (!empty($value->permission) ? '<br><span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-yellow-500 rounded">' . $value->permission->title . '</span>' : '') .  ' </p></center></div></div>'
                ];
            }
        }

        return response()->json(['status' => 'ok', 'data' => $data, 'today' => $today]);
    }
}
