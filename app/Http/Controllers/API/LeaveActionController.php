<?php

namespace App\Http\Controllers\API;

use App\Helpers\Fcm;
use App\Models\Leave;
use App\Models\Utility;
use App\Models\User;
use App\Models\Notification;
use App\Models\NotificationEmployee;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;

class LeaveActionController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'leave_id' => 'required',
            'user_id'   => 'required'
        ]);

        $leave = Leave::find($request->leave_id);
        $user = User::find($request->user_id);

        if (empty($leave)) {
            return response()->json([
                'type' => 'error',
                'message' => 'leave not found',
            ]);
        }

        $settings = Utility::settings($user->created_by);
        $tiered_leave = $settings['tiered_leave'];

        if ($request->status == 'Approval') {
            $startDate               = new DateTime($leave->start_date);
            $endDate                 = new DateTime($leave->end_date);
            $total_leave_days        = $startDate->diff($endDate)->days;
            $leave->total_leave_days = $total_leave_days;
        }

        $approve = [];
        $status_approve = '';
        $status_from_hr = '';
        if ($request->status == 'Approve') $status_approve = 'Menyetujui';
        else $status_approve = 'Menolak';

        if ($user->role->level == null or $tiered_leave != 'on') {
            $leave->status           = $request->status;
            $status_from_hr = $request->status;
            if (count(json_decode($leave->acc)) > 0) {
                $approve = json_decode($leave->acc);
                $check_role = array_search($user->role_id, array_column($approve, 'author'));

                // return $check_role;
                if ($check_role !== false) {
                    $approve[$check_role] = ['author' => $user->role_id, 'type' => $user->type, 'status' => $request->status];
                } else {
                    array_push($approve, ['author' => $user->role_id, 'type' => $user->type, 'status' => $request->status]);
                }
            } else {

                $approve[] = ['author' => $user->role_id, 'type' => $user->type, 'status' => $request->status];
            }

            $leave->acc           = json_encode($approve);
            Leave::where('parent', $request->leave_id)->update(['status' => $request->status]);
        } else {
            if (count(json_decode($leave->acc)) > 0) {
                $approve = json_decode($leave->acc);
                $check_role = array_search($user->role_id, array_column($approve, 'author'));

                // return $check_role;
                if ($check_role !== false) {
                    $approve[$check_role] = ['author' => $user->role_id, 'type' => $user->type, 'status' => $request->status];
                } else {
                    array_push($approve, ['author' => $user->role_id, 'type' => $user->type, 'status' => $request->status]);
                }
            } else {

                $approve[] = ['author' => $user->role_id, 'type' => $user->type, 'status' => $request->status];
            }

            $leave->acc           = json_encode($approve);
            Leave::where('parent', $request->leave_id)->update(['acc' => $request->status]);
        }
        $leave->save();

        if ($status_from_hr != '') {
            #notif send to employee (approve or reject)
            $firebaseToken = [$leave->employees->user->fcm_token];
            $userNotif = [$leave->employees->user->id];

            if ($leave->employees->user->fcm_token != null) {

                $data = [
                    "registration_ids" => $firebaseToken,
                    "notification" => [
                        "title" => 'Persetujuan Cuti',
                        "body" => 'Pengajuan Cuti, ' .  ($status_from_hr == 'Approve' ? 'Disetujui' : 'Ditolak'),
                    ],
                    "data" => [
                        "type" => "Leave",
                        "id" => $leave->id
                    ]
                ];

                Fcm::sendMessage($data);

                // save notif into database
                $notification               = new Notification();
                $notification->title        = 'Persetujuan Cuti';
                $notification->type         = 'Leave';
                $notification->messages     = 'Pengajuan Cuti, ' . ($status_from_hr == 'Approve' ? 'Disetujui' : 'Ditolak');
                $notification->users        = implode(",", $userNotif);
                $notification->created_by   = $user->created_by;
                $notification->save();

                foreach ($userNotif as $userId) {
                    $notificationEmployee                  = new NotificationEmployee();
                    $notificationEmployee->notification_id = $notification->id;
                    $notificationEmployee->user_id         = $userId;
                    $notificationEmployee->created_by      = $user->created_by;

                    $notificationEmployee->save();
                }
            }
        } else {
            #notif send to hr / head of / other
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
                if ($x->department_id == $leave->employees->department_id or $x->type == 'hr') {
                    array_push($firebaseToken, $x->fcm_token);
                    array_push($userNotif, $x->id);
                }
            }

            $data = [
                "registration_ids" => $firebaseToken,
                "notification" => [
                    "title" => 'Persetujuan Cuti',
                    "body" => $user->name . ' ' . $status_approve . ' Pengajuan Cuti ' . $leave->employees->name,
                ],
                "data" => [
                    "type" => "Leave",
                    "id" => $leave->id
                ]
            ];

            Fcm::sendMessage($data);

            // save notif into database
            $notification               = new Notification();
            $notification->title        = 'Persetujuan Cuti';
            $notification->type         = 'Leave';
            $notification->messages     = $user->name . ' ' . $status_approve . ' Pengajuan Cuti ' . $leave->employees->name;
            $notification->users        = implode(",", $userNotif);
            $notification->created_by   = $user->created_by;
            $notification->save();

            foreach ($userNotif as $userId) {
                $notificationEmployee                  = new NotificationEmployee();
                $notificationEmployee->notification_id = $notification->id;
                $notificationEmployee->user_id         = $userId;
                $notificationEmployee->created_by      = $user->created_by;

                $notificationEmployee->save();
            }
        }

        return response()->json([
            'type' => 'success',
            'message' => 'Approval Success',
        ]);
    }
}
