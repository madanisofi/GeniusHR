<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceEmployee;
use App\Models\Employee;
use App\Models\Utility;
use App\Models\Shift;
use App\Models\User;
use App\Events\SendMessage;
use App\Helpers\Fcm;
use App\Models\PermissionType;
use App\Models\Notification;
use App\Models\NotificationEmployee;
use DateTime;

class AttendanceReasonController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'images' => 'required|image|mimes:jpeg,png,jpg,svg',
            'reason' => 'required',
            'permission_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $checkPermission = PermissionType::find($request->permission_id);
        if ($checkPermission->many_submission == 'yes') {
            $request->validate([
                'start_date' => 'required',
                'end_date' => 'required'
            ]);
        }

        $user = User::find($request->user_id);

        if (!empty($user)) {
            $creator = $user->created_by;
            $emp = Employee::where('user_id', '=', $request->user_id)->first();

            $settings = Utility::settings($creator);

            $employeeId      = !empty($emp) ? $emp->id : 0;
            $todayAttendance = AttendanceEmployee::where('employee_id', '=', $employeeId)->whereIn('status', array('Present', 'Pending'))->where('clock_out', '00:00:00')->whereRAW('created_at >= (NOW() - INTERVAL 24 HOUR)')->orderBy('id', 'desc')->first();
            if (empty($todayAttendance)) {
                if ($settings['shift'] == 'on' && empty($request->start_date)) {
                    $getShift = Shift::find($request->shift_id);

                    $startTime = $getShift->start_time;
                    $endTime = $getShift->end_time;
                } else {
                    $startTime = $settings['company_start_time'];
                    $endTime = $settings['company_end_time'];
                }

                if (isset($request->start_date)) {

                    $permission_counts = PermissionType::select(\DB::raw('COALESCE(COUNT(attendance_employees.id),0) AS total_permission, permission_types.title, permission_types.days,permission_types.id, permission_types.many_submission'))
                        ->leftjoin(
                            'attendance_employees',
                            function ($join) use ($emp) {
                                $join->on('attendance_employees.permissiontype_id', '=', 'permission_types.id');
                                $join->where('attendance_employees.employee_id', '=', $emp->id);
                                $join->whereYear('attendance_employees.date', date('Y'));
                                $join->whereMonth('attendance_employees.date', date('m'));
                            }
                        )->where('permission_types.id', '=', $request->permission_id)
                        ->groupBy('permission_types.id')->first();

                    $remaining_permit = $permission_counts->days - $permission_counts->total_permission;

                    $startDate = new \DateTime($request->start_date);
                    $endDate = new \DateTime((isset($request->end_date) ? $request->end_date : $request->start_date));
                    $total_days = !empty($startDate->diff($endDate)) ? $endDate->diff($startDate)->days + 1 : 0;

                    if ($total_days > $remaining_permit) {
                        return response()->json([
                            'type' => 'error',
                            'message' => 'Kuota Perizinan Tidak Mencukupi.'
                        ]);
                    }
                }

                $date = date("Y-m-d");
                $time = date("H:i:s");

                // late
                $totalLateSeconds = time() - strtotime($date . $startTime);
                $hours            = floor($totalLateSeconds / 3600);
                $mins             = floor($totalLateSeconds / 60 % 60);
                $secs             = floor($totalLateSeconds % 60);
                $late             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                // selfie
                $fileNameToStore = '';
                if ($request->hasFile('images')) {
                    $filenameWithExt = $request->file('images')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('images')->getClientOriginalExtension();
                    // hash file before store
                    $filename = md5($filename . time());
                    $fileNameToStore = $filename . '_reason_' . time() . '.' . $extension;
                    $dir             = storage_path('uploads/attendance/');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $path = $request->file('images')->storeAs('uploads/attendance/', $fileNameToStore);
                }

                // salary cuts
                if ($checkPermission->clock_out == 'no') {
                    $salary_cuts = 0;
                    $attendance_out = $endTime;
                } else {
                    $late_accumulate = $settings['late_accumulation'];
                    $max_late_fee = $settings['maximum_late_fee_in_one_day'];
                    $late_fee_in_minutes = $settings['late_fee_in_minutes'];

                    $counting_minutes = floor($totalLateSeconds / 60);
                    if ($counting_minutes > $late_accumulate) {
                        $salary_cuts = $max_late_fee;
                    } else {
                        if ($counting_minutes <= 0) $salary_cuts = 0;
                        else $salary_cuts = $counting_minutes * $late_fee_in_minutes;
                    }
                    $attendance_out = '00:00:00';
                }

                if (isset($request->start_date)) {
                    $start_date = new DateTime($request->start_date);
                    $end_date = new DateTime($request->end_date);
                    $diff = $end_date->diff($start_date);

                    $employeeAttendance                = new AttendanceEmployee();
                    $employeeAttendance->employee_id   = $employeeId;
                    $employeeAttendance->date          = $request->start_date;
                    $employeeAttendance->end_date      = $request->end_date;
                    $employeeAttendance->status        = 'Pending';
                    $employeeAttendance->approve       = json_encode([]);
                    $employeeAttendance->clock_in      = $time;
                    $employeeAttendance->clock_out     = $attendance_out;
                    $employeeAttendance->late          = $late;
                    $employeeAttendance->early_leaving = '00:00:00';
                    $employeeAttendance->overtime      = '00:00:00';
                    $employeeAttendance->total_rest    = '00:00:00';
                    $employeeAttendance->created_by    = $request->user_id;
                    $employeeAttendance->images        = $fileNameToStore;
                    $employeeAttendance->reason        = $request->reason;
                    $employeeAttendance->shift_id      = (isset($request->shift_id) ? $request->shift_id : null);
                    $employeeAttendance->permissiontype_id        = $request->permission_id;
                    $employeeAttendance->latitude      = (isset($request->latitude) ? $request->latitude : null);
                    $employeeAttendance->longitude     = (isset($request->longitude) ? $request->longitude : null);
                    $employeeAttendance->save();

                    $multi_attendance = [];
                    for ($x = 1; $x <= $diff->d; $x++) {
                        array_push($multi_attendance, array(
                            'employee_id'    => $employeeId,
                            'date'          => date('Y-m-d', strtotime('+' . $x . ' days', strtotime($request->start_date))),
                            'status'        => 'Pending',
                            'parent_id'     => $employeeAttendance->id,
                            'approve'       => json_encode([]),
                            'clock_in'      => $time,
                            'clock_out'     => $attendance_out,
                            'late'          => $late,
                            'early_leaving' => '00:00:00',
                            'overtime'      => '00:00:00',
                            'total_rest'    => '00:00:00',
                            'created_by'    => $request->user_id,
                            'images'        => $fileNameToStore,
                            'reason'        => $request->reason,
                            'shift_id'      => (isset($request->shift_id) ? $request->shift_id : null),
                            'permissiontype_id'        => $request->permission_id,
                            'latitude'      => (isset($request->latitude) ? $request->latitude : null),
                            'longitude'     => (isset($request->longitude) ? $request->longitude : null),
                            'created_at'    => date("Y-m-d H:i:s"),
                            'updated_at'    => date("Y-m-d H:i:s")
                        ));
                    }

                    AttendanceEmployee::insert($multi_attendance);
                } else {
                    $employeeAttendance                = new AttendanceEmployee();
                    $employeeAttendance->employee_id   = $employeeId;
                    $employeeAttendance->date          = $date;
                    $employeeAttendance->status        = 'Pending';
                    $employeeAttendance->approve       = json_encode([]);
                    $employeeAttendance->clock_in      = $time;
                    $employeeAttendance->clock_out     = $attendance_out;
                    $employeeAttendance->late          = $late;
                    $employeeAttendance->early_leaving = '00:00:00';
                    $employeeAttendance->overtime      = '00:00:00';
                    $employeeAttendance->total_rest    = '00:00:00';
                    $employeeAttendance->created_by    = $request->user_id;
                    $employeeAttendance->images        = $fileNameToStore;
                    $employeeAttendance->reason        = $request->reason;
                    $employeeAttendance->shift_id      = (isset($request->shift_id) ? $request->shift_id : null);
                    $employeeAttendance->permissiontype_id        = $request->permission_id;
                    $employeeAttendance->latitude      = (isset($request->latitude) ? $request->latitude : null);
                    $employeeAttendance->longitude     = (isset($request->longitude) ? $request->longitude : null);

                    $employeeAttendance->save();
                }

                if ($settings['late_fee_calculation'] == 'on') {
                    setLateCharge([
                        'attendance_id' => $employeeAttendance->id,
                        'salary_cuts'   => $salary_cuts,
                        'working_hours' => '00:00:00',
                        'working_late'  => '00:00:00'
                    ]);
                }

                // send notif to web
                $array = [
                    'name' => $user->name,
                    'emp_id' => $emp->employee_id,
                    'created_by' => $creator,
                    'title' => __('Attendance Permission'),
                    'type' => 'notif',
                    'to' => $emp->department_id #ditujukan ke kepala bagian dan hrd & super admin (level > 0)
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
                    if ($x->department_id == $emp->department_id or $x->type == 'hr') {
                        array_push($firebaseToken, $x->fcm_token);
                        array_push($userNotif, $x->id);
                    }
                }

                $data = [
                    "registration_ids" => $firebaseToken,
                    "notification" => [
                        "title" => 'Presensi Izin ' . PermissionType::find($request->permission_id)->title,
                        "body" => $emp->name . ', Membutuhkan Persetujuan Anda',
                    ],
                    "data" => [
                        "type" => "Izin",
                        "id" => $employeeAttendance->id
                    ]
                ];

                Fcm::sendMessage($data);

                // save notif into database
                $notification               = new Notification();
                $notification->title        = 'Permintaan Izin ' . PermissionType::find($request->permission_id)->title;
                $notification->type         = 'Izin';
                $notification->messages     = $emp->name . ', Membutuhkan Persetujuan Anda';
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

                return response()->json([
                    'type' => 'success',
                    'message' => 'Employee Successfully Clock In.'
                ]);
            } else {

                return response()->json([
                    'type' => 'error',
                    'message' => 'Employee are not allow multiple time clock in & clock for every day.'
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
