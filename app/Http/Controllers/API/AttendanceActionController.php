<?php

namespace App\Http\Controllers\API;

use App\Models\Utility;
use App\Models\AttendanceEmployee;
use App\Models\User;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\NotificationEmployee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Events\SendMessage;
use App\Helpers\Fcm;

class AttendanceActionController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required',
            'user_id'   => 'required'
        ]);

        $attendance = AttendanceEmployee::find($request->attendance_id);

        if (empty($attendance)) {
            return response()->json([
                'type' => 'error',
                'message' => 'attendance not found',
            ]);
        }

        $user = User::where('id', $request->user_id)->first();
        $settings = Utility::settings($user->created_by);
        $creator = $user->created_by;

        $approval = 0;
        $approve = [];
        $attendanceStatus = 'Present';
        if (count(json_decode($attendance->approve)) > 0) {
            $approve = json_decode($attendance->approve);
            $check_role = array_search($user->role_id, array_column($approve, 'author'));

            foreach ($approve as $key => $value) {
                if ($value->status == 'Approve') $approval += 1;
            }

            if ($check_role !== false) {
                $approve[$check_role] = ['author' => $user->role_id, 'type' => $user->type, 'user' => $user->name, 'status' => $request->status];

                if ($request->status != 'Approve') $approval--;
                else $approval += 1;
            } else {
                array_push($approve, ['author' => $user->role_id, 'type' => $user->type, 'user' => $user->name, 'status' => $request->status]);

                if ($request->status == 'Approve') $approval += 1;
            }
        } else {

            $approve[] = ['author' => $user->role_id, 'type' => $user->type, 'user' => $user->name, 'status' => $request->status];
            if ($request->status == 'Approve') $approval += 1;
        }

        $employee = Employee::find($attendance->employee_id);

        $approve_list = json_encode($approve);

        if ($attendance->status != 'Absence') {
            #role for head of
            if ($attendance->employee->user->type != 'employee') {

                if ($request->status != 'Approve') $attendanceStatus = 'Reject';
                $attendance->status        = $attendanceStatus;

                AttendanceEmployee::where('parent_id', $request->attendance_id)->update(['status' => $attendanceStatus]);

                if ($settings['qr_presence'] == 'on' and $attendanceStatus == 'Present') {
                    $userEmployee = User::find($employee->user_id);
                    $profile = asset(url('uploads/avatar/'));
                    if (!empty($userEmployee->avatar)) {
                        $picture = $profile . '/' . $userEmployee->avatar;
                    } else {
                        $picture = $profile . '/user.png';
                    }

                    $array = [
                        'name' => $employee->name,
                        'start' => $attendance->clock_in,
                        'end' => '00:00',
                        'emp_id' => $employee->employee_id,
                        'position' => $employee->position->name,
                        'created_by' => $user->created_by,
                        'profile' => $picture,
                        'status' => $attendance->status,
                        'hours' => date('H', strtotime($attendance->late)),
                        'minutes' => date('i', strtotime($attendance->late)),
                        'title' => 'In',
                        'status_qr' => 'Valid',
                        'type' => 'presence',
                        'permission' => (!empty($attendance->permission) ? $attendance->permission->title : '')
                    ];

                    event(new SendMessage($array));
                }
            } else {
                if ($approval >= $settings['attendance_approval']) {

                    foreach (json_decode($approve_list) as $key => $value) {
                        if ($value->status != 'Approve') $attendanceStatus = 'Reject';
                    }

                    $attendance->status        = $attendanceStatus;

                    AttendanceEmployee::where('parent_id', $request->attendance_id)->update(['status' => $attendanceStatus]);

                    if ($settings['qr_presence'] == 'on' and $attendanceStatus == 'Present') {

                        $userEmployee = User::find($employee->user_id);
                        $profile = asset(url('uploads/avatar/'));
                        if (!empty($userEmployee->avatar)) {
                            $picture = $profile . '/' . $userEmployee->avatar;
                        } else {
                            $picture = $profile . '/user.png';
                        }

                        $array = [
                            'name' => $employee->name,
                            'start' => $attendance->clock_in,
                            'end' => '00:00',
                            'emp_id' => $employee->employee_id,
                            'position' => $employee->position->name,
                            'created_by' => $user->created_by,
                            'profile' => $picture,
                            'status' => $attendance->status,
                            'hours' => (strtotime($attendance->late) > 0 ? date('H', strtotime($attendance->late)) : 0),
                            'minutes' => (strtotime($attendance->late) > 0 ? date('i', strtotime($attendance->late)) : 0),
                            'title' => 'In',
                            'status_qr' => 'Valid',
                            'type' => 'presence',
                            'permission' => (!empty($attendance->permission) ? $attendance->permission->title : '')
                        ];

                        event(new SendMessage($array));
                    }
                } else {
                    if (count(json_decode($approve_list)) >= $settings['attendance_approval'] and $approval < $settings['attendance_approval']) $attendance->status = 'Reject';
                    else $attendance->status = 'Pending';

                    $attendanceStatus = '';
                }
            }
        }

        $array2 = [
            'name' => $employee->name,
            'created_by' => $employee->created_by,
            'title' => __('Presence Approval'),
            'status_qr' => 'Valid',
            'type' => 'notif',
            'to' => $employee->department_id #ditujukan ke kepala bagian dan hrd & super admin (level > 0)
        ];

        event(new SendMessage($array2));

        if ($request->status == 'Approve') $status_approve = 'Menyetujui';
        else $status_approve = 'Menolak';

        if (count(json_decode($approve_list)) >= $settings['attendance_approval']) {

            $firebaseToken = [$employee->user->fcm_token];
            $userNotif = [$employee->user->id];

            if ($employee->user->fcm_token != null) {

                $data = [
                    "registration_ids" => $firebaseToken,
                    "notification" => [
                        "title" => 'Persetujuan Presensi',
                        "body" => 'Pengajuan Presensi Izin, ' . ($attendanceStatus == 'Present' ? 'Disetujui' : 'Ditolak'),
                    ],
                    "data" => [
                        "type" => "Izin",
                        "id" => $attendance->id
                    ]
                ];

                Fcm::sendMessage($data);

                // save notif into database
                $notification               = new Notification();
                $notification->title        = 'Persetujuan Presensi';
                $notification->type         = 'Izin';
                $notification->messages     = 'Pengajuan Pesensi Izin, ' . ($attendanceStatus == 'Present' ? 'Disetujui' : 'Ditolak');
                $notification->users        = implode(",", $userNotif);
                $notification->created_by   = $creator;
                $notification->save();

                foreach ($userNotif as $user) {
                    $notificationEmployee                  = new NotificationEmployee();
                    $notificationEmployee->notification_id = $notification->id;
                    $notificationEmployee->user_id         = $user;
                    $notificationEmployee->created_by      = $creator;

                    $notificationEmployee->save();
                }
            }
        } else {
            // send notif fcm to hrd and head of
            $getUser = Employee::selectRaw('users.id, users.name, users.fcm_token, users.type, users.role_id, employees.department_id')
                ->join('users', 'employees.user_id', '=', 'users.id')
                ->where('employees.created_by', $user->created_by)
                ->where('users.type', '!=', 'employee')
                ->where('users.fcm_token', '!=', null)
                ->where('users.id', '!=', $request->user_id)
                ->get();

            $firebaseToken = [];
            $userNotif = [];
            foreach ($getUser as $x) {
                if ($x->department_id == $employee->department_id or $x->type == 'hr') {
                    array_push($firebaseToken, $x->fcm_token);
                    array_push($userNotif, $x->id);
                }
            }

            $data = [
                "registration_ids" => $firebaseToken,
                "notification" => [
                    "title" => 'Persetujuan Presensi',
                    "body" => $user->name . ' ' . $status_approve . ' Presensi Izin ' . $employee->name,
                ],
                "data" => [
                    "type" => "Izin",
                    "id" => $attendance->id
                ]
            ];

            Fcm::sendMessage($data);

            // save notif into database
            $notification               = new Notification();
            $notification->title        = 'Persetujuan Presensi';
            $notification->type         = 'Izin';
            $notification->messages     = $user->name . ' ' . $status_approve . ' Presensi Izin ' . $employee->name;
            $notification->users        = implode(",", $userNotif);
            $notification->created_by   = $creator;
            $notification->save();

            foreach ($userNotif as $user) {
                $notificationEmployee                  = new NotificationEmployee();
                $notificationEmployee->notification_id = $notification->id;
                $notificationEmployee->user_id         = $user;
                $notificationEmployee->created_by      = $creator;

                $notificationEmployee->save();
            }
        }

        $attendance->approve           = json_encode($approve);

        $attendance->save();

        return response()->json([
            'type' => 'success',
            'message' => 'Approval Success',
        ]);
    }
}
