<?php

namespace App\Http\Controllers\API;

use App\Helpers\Fcm;
use App\Models\User;
use App\Models\AttendanceEmployee;
use App\Models\PaySlip;
use App\Models\Leave;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Notification;
use Illuminate\Http\Request;

class PushNotifController extends Controller
{
    public function notif(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $user = User::find($request->user_id);

        if (empty($user)) {
            return response()->json([
                'type' => 'error',
                'message' => 'user not found.'
            ]);
        }

        $emp = Employee::where('user_id', $request->user_id)->first();

        $firebaseToken = User::where('id', $request->user_id)->where('fcm_token', '!=', null)->pluck('fcm_token')->all();
        $notification    = Notification::orderBy('notifications.id', 'desc')->leftjoin('notification_employees', 'notifications.id', '=', 'notification_employees.notification_id')->where('notification_employees.user_id', '=', $request->user_id)->orWhere(
            function ($q) {
                $q->where('notifications.users', '["0"]');
            }
        )->get();

        $id = 0;
        if (!empty($notification)) {
            $data = [];
            foreach ($notification as $key => $value) {
                $data[] = [
                    'id'    => $value->notification_id,
                    'title' => $value->title,
                    'type' => $value->type,
                    'messages' => $value->messages,
                    'details' => ($value->details != null ? $value->details : ''),
                    'date' => date('Y-m-d', strtotime($value->created_at))
                ];

                $id = $value->notification_id;
            }
        }

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => 'Other Notification',
                "body" => 'Example Notification For Other',
            ],
            "data" => [
                "type" => "Notif",
                "id" => $id
            ]
        ];

        Fcm::sendMessage($data);

        return response()->json([
            'type' => 'success',
            'message' => 'send notif success',
            'data' => $data
        ]);
    }

    public function leave(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $user = User::find($request->user_id);

        if (empty($user)) {
            return response()->json([
                'type' => 'error',
                'message' => 'user not found.'
            ]);
        }

        $emp = Employee::where('user_id', $request->user_id)->first();

        $firebaseToken = User::where('id', $request->user_id)->where('fcm_token', '!=', null)->pluck('fcm_token')->all();
        $leave = Leave::where('employee_id', $emp->id)->orderBy('id', 'desc')->first();
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => 'Leave Notification',
                "body" => 'Example Notification For Leave',
            ],
            "data" => [
                "type" => "Leave",
                "id" => (!empty($leave) ? $leave->id : 0)
            ]
        ];

        Fcm::sendMessage($data);

        return response()->json([
            'type' => 'success',
            'message' => 'send notif success',
            'data' => $data
        ]);
    }

    public function payslip(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $user = User::find($request->user_id);

        if (empty($user)) {
            return response()->json([
                'type' => 'error',
                'message' => 'user not found.'
            ]);
        }

        $emp = Employee::where('user_id', $request->user_id)->first();

        $firebaseToken = User::where('id', $request->user_id)->where('fcm_token', '!=', null)->pluck('fcm_token')->all();
        $payslip = PaySlip::where('employee_id', $emp->id)->orderBy('id', 'desc')->first();
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => 'Payslip Notification',
                "body" => 'Example Notification For Payslip',
            ],
            "data" => [
                "type" => "Payslip",
                "id" => (!empty($payslip) ? $payslip->id : 0)
            ]
        ];

        Fcm::sendMessage($data);

        return response()->json([
            'type' => 'success',
            'message' => 'send notif success',
            'data' => $data
        ]);
    }

    public function permission(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $user = User::find($request->user_id);

        if (empty($user)) {
            return response()->json([
                'type' => 'error',
                'message' => 'user not found.'
            ]);
        }

        $emp = Employee::where('user_id', $request->user_id)->first();

        $firebaseToken = User::where('id', $request->user_id)->where('fcm_token', '!=', null)->pluck('fcm_token')->all();
        $attendance = AttendanceEmployee::where('employee_id', $emp->id)->orderBy('id', 'desc')->first();
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => 'Permission Notification',
                "body" => 'Example Notification For Attendance Reason',
            ],
            "data" => [
                "type" => "Izin",
                "id" => (!empty($attendance) ? $attendance->id : 0)
            ]
        ];

        Fcm::sendMessage($data);

        return response()->json([
            'type' => 'success',
            'message' => 'send notif success',
            'data' => $data
        ]);
    }
}
