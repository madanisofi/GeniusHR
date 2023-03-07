<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\Utility;
use App\Models\Notification;
use App\Models\NotificationEmployee;
use App\Events\SendMessage;
use App\Helpers\Fcm;
use Illuminate\Support\Facades\Auth;

class LeaveCreateController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required',
            'start_date' => 'required',
            'leave_reason' => 'required',
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {
            // get leave selected
            $leave_select = LeaveType::find($request->leave_type_id);
            $creator = $user->created_by;

            $settings = Utility::settings($user->created_by);
            $tiered_leave = $settings['tiered_leave'];

            if ($leave_select->select_all == 'on') {
                $request_start = $request->start_date;
                $request_end = date('Y-m-d', strtotime('+' . ((int)$leave_select->days - 1) . ' days', strtotime($request->start_date)));
                // leave day
                $total_leave_days = $leave_select->days;
            } else {
                $request_start = $request->start_date;
                $request_end = $request->end_date;

                // leave day
                $startDate = new \DateTime($request->start_date);
                $endDate = new \DateTime($request->end_date);
                $total_leave_days = !empty($startDate->diff($endDate)) ? $endDate->diff($startDate)->days + 1 : 0;
            }

            $emp_days_off = Leave::where('leave_type_id', $request->leave_type_id)->where('employee_id', $request->employee_id)->whereYear('applied_on', date('Y'))->sum('total_leave_days');
            $total_days_off = LeaveType::find($request->leave_type_id)->days;
            $remaining_days_off = (int)$total_days_off - ((int)$emp_days_off + (int)$total_leave_days);

            if ($remaining_days_off < 0) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Leave Has Exceeded The Limit.'
                ]);
            }

            $employee = Employee::where('user_id', '=', $request->user_id)->first();
            $leave = new Leave();

            $status_acc = json_encode([]);

            if (!empty($employee)) {
                $leave->employee_id = $employee->id;
                $leave->leave_type_id    = $request->leave_type_id;
                $leave->applied_on       = date('Y-m-d');
                $leave->start_date       = $request_start;
                $leave->end_date         = $request_end;
                $leave->total_leave_days = $total_leave_days;
                $leave->leave_reason     = $request->leave_reason;
                $leave->remark           = (isset($request->remark) ? $request->remark : '');
                $leave->status           = 'Pending';
                if (Employee::getType($employee->id)->type != 'employee' or $tiered_leave != 'on') {
                    $leave->addressed_to = 0;
                } else {
                    $leave->addressed_to = $employee->department_id;
                }

                $leave->acc              = $status_acc;
                $leave->created_by       = $employee->created_by;
                $leave->save();

                // chech parenting
                $getParent = LeaveType::where('id', $request->leave_type_id)->first();
                $reduction = $getParent->reduction;
                $remain_rdcn = $reduction;
                $now = date('m');
                if ($getParent->parent != null) {
                    $searchLeave = LeaveType::whereIn('id', json_decode($getParent->parent))->get();
                    $count_searchLeave = count($searchLeave);
                    $mean = 12 / $count_searchLeave; #12 = jumlah bulan

                    foreach ($searchLeave as $x) {
                        $start = $x->start_date;
                        $end = $x->end_date;

                        if ($start != '00' && $end != '00') {

                            if ($now <= $mean) {
                                // under 7
                                if (($now >= (int)$start && $now <= (int)$end) or $remain_rdcn > 0) {
                                    $remain = Leave::where('leave_type_id', $x->id)
                                        ->where('employee_id', $request->employee_id)
                                        ->where('status', '=', 'Approve')
                                        ->where('acc', '=', 'Approve')
                                        ->whereYear('applied_on', date('Y'))
                                        ->sum('total_leave_days');
                                    $remain_off = (int)$reduction - ((int)$x->days - (int)$remain);

                                    if ($remain_off > 0) {
                                        // insert again
                                        $remain_rdcn = $remain_off;
                                        $accumulate_total_leave_days = (int)$x->days - (int)$remain;
                                        $reduction = $remain_off;

                                        // new insert
                                        Leave::create([
                                            'employee_id' => $leave->employee_id,
                                            'leave_type_id' => $x->id,
                                            'applied_on' => date('Y-m-d'),
                                            'total_leave_days' => $accumulate_total_leave_days,
                                            'leave_reason' => $leave_select->title,
                                            'status' => 'Pending',
                                            'remark' => 'Accumulate',
                                            'acc' => $status_acc,
                                            'parent' => $leave->id,
                                            'created_by' => Auth::user()->creatorId()
                                        ]);
                                    } else {
                                        // insert one
                                        $remain_rdcn = 0;
                                        $accumulate_total_leave_days = $reduction;
                                        $reduction = 0;

                                        // new insert
                                        Leave::create([
                                            'employee_id' => $leave->employee_id,
                                            'leave_type_id' => $x->id,
                                            'applied_on' => date('Y-m-d'),
                                            'total_leave_days' => $accumulate_total_leave_days,
                                            'leave_reason' => $leave_select->title,
                                            'status' => 'Pending',
                                            'remark' => 'Accumulate',
                                            'acc' => $status_acc,
                                            'parent' => $leave->id,
                                            'created_by' => Auth::user()->creatorId()
                                        ]);
                                    }
                                }
                            } else {
                                if ($now >= (int)$start && $now <= (int)$end) {
                                    $remain = Leave::where('leave_type_id', $x->id)
                                        ->where('employee_id', $request->employee_id)
                                        ->where('status', '=', 'Approve')
                                        ->where('acc', '=', 'Approve')
                                        ->whereYear('applied_on', date('Y'))
                                        ->sum('total_leave_days');
                                    $remain_off = (int)$reduction - ((int)$x->days - (int)$remain);

                                    $remain_rdcn = 0;
                                    $accumulate_total_leave_days = $reduction;
                                    $reduction = 0;

                                    Leave::create([
                                        'employee_id' => $leave->employee_id,
                                        'leave_type_id' => $x->id,
                                        'applied_on' => date('Y-m-d'),
                                        'total_leave_days' => $accumulate_total_leave_days,
                                        'leave_reason' => $leave_select->title,
                                        'status' => 'Pending',
                                        'remark' => 'Accumulate',
                                        'acc' => $status_acc,
                                        'parent' => $leave->id,
                                        'created_by' => Auth::user()->creatorId()
                                    ]);
                                }
                            }
                        } else {
                            // new insert
                            Leave::create([
                                'employee_id' => $leave->employee_id,
                                'leave_type_id' => $x->id,
                                'applied_on' => date('Y-m-d'),
                                'total_leave_days' => $reduction,
                                'leave_reason' => $leave_select->title,
                                'status' => 'Pending',
                                'remark' => 'Accumulate',
                                'acc' => $status_acc,
                                'parent' => $leave->id,
                                'created_by' => Auth::user()->creatorId()
                            ]);
                        }
                    }
                }

                $array = [
                    'name' => $user->name,
                    'emp_id' => $employee->employee_id,
                    'created_by' => $employee->created_by,
                    'title' => 'Pengajuan Cuti',
                    'type' => 'notif',
                    'to' => $employee->department_id #ditujukan ke kepala bagian dan hrd & super admin (level > 0)
                ];

                event(new SendMessage($array));

                // send notif fcm to hrd and head of
                $getUser = Employee::selectRaw('users.id, users.name, users.fcm_token, users.type, users.role_id, employees.department_id')
                    ->join('users', 'employees.user_id', '=', 'users.id')
                    ->where('employees.created_by', $creator)
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
                        "title" => 'Pengajuan Cuti',
                        "body" => $employee->name . ', Membutuhkan Persetujuan Anda',
                    ],
                    "data" => [
                        "type" => "Leave",
                        "id" => $leave->id
                    ]
                ];

                Fcm::sendMessage($data);

                // save notif into database
                $notification               = new Notification();
                $notification->title        = 'Pengajuan Cuti';
                $notification->type         = 'Leave';
                $notification->messages     = $employee->name . ', Membutuhkan Persetujuan Anda';
                $notification->users        = implode(",", $userNotif);
                $notification->created_by   = $creator;
                $notification->save();

                foreach ($userNotif as $userId) {
                    $notificationEmployee                  = new NotificationEmployee();
                    $notificationEmployee->notification_id = $notification->id;
                    $notificationEmployee->user_id         = $userId;
                    $notificationEmployee->created_by      = $creator;

                    $notificationEmployee->save();
                }

                return response()->json([
                    'type' => 'success',
                    'message' => 'Leave successfully created.'
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'employee not found.'
                ]);
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'user not found.'
            ]);
        }
    }
}
