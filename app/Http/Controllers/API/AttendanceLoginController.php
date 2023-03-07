<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceEmployee;
use App\Models\Employee;
use App\Models\Utility;
use App\Models\Shift;
use App\Models\User;
use App\Models\Qrtoken;
use App\Models\Notification;
use App\Models\NotificationEmployee;
use Illuminate\Support\Facades\Storage;
use App\Events\SendMessage;
use App\Helpers\Fcm;

class AttendanceLoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        $user = User::find($request->user_id);

        if (!empty($user)) {

            $creator = $user->created_by;
            $mod_user = new User();

            $settings = Utility::settings($creator);

            /**
             * check shift on / off
             */
            if ($settings['shift'] == 'on') {
                $request->validate([
                    'shift_id' => 'required',
                ]);
            }

            if (isset($request->token)) {
                /**
                 * check token
                 */
                $token = Qrtoken::where('created_by', $creator)->limit(1)->first();

                if ($token->token != $request->token) {

                    $array = [
                        'name' => $user->name,
                        'start' => '00:00',
                        'end' => '00:00',
                        'emp_id' => '',
                        'position' => '',
                        'created_by' => $creator,
                        'profile' => '',
                        'status' => '',
                        'hours' => 0,
                        'minutes' => '',
                        'title' => 'In',
                        'status_qr' => 'Invalid',
                        'type' => 'presence'
                    ];

                    event(new SendMessage($array));

                    return response()->json([
                        'type' => 'error',
                        'message' => 'QR Code Invalid',
                    ]);
                }

                /**
                 * set status & approve
                 */
                $status     = 'Present';
                $approve    = json_encode([]);
            } else {
                /**
                 * uploads images selfie
                 */

                $request->validate([
                    'images' => 'required|image|mimes:jpeg,png,jpg,svg',
                    'latitude' => 'required',
                    'longitude' => 'required',
                ]);

                // selfie
                $fileNameToStore = '';
                if ($request->hasFile('images')) {
                    $filenameWithExt = $request->file('images')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('images')->getClientOriginalExtension();
                    // hash file before store
                    $filename = md5($filename . time());
                    $fileNameToStore = $filename . '_login_' . time() . '.' . $extension;
                    $dir             = storage_path('uploads/attendance/');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $path = $request->file('images')->storeAs('uploads/attendance/', $fileNameToStore);
                }

                /**
                 * check presence by qr on / off
                 */
                if ($settings['qr_presence'] == 'on') {
                    // set status & approve
                    $status     = 'Pending';
                    $approve    = json_encode([]);
                } else { #selfie mode
                    $status     = 'Present';
                    $approve    = json_encode([]);
                }
            }

            $emp = Employee::where('user_id', '=', $request->user_id)->first();

            /**
             * get avatar for presensi QR
             */
            $profile = asset(url('uploads/avatar/'));
            if (!empty($user->avatar)) {
                $picture = $profile . '/' . $user->avatar;
            } else {
                $picture = $profile . '/user.png';
            }

            $employeeId      = !empty($emp) ? $emp->id : 0;
            $todayAttendance = AttendanceEmployee::where('employee_id', '=', $employeeId)->where('clock_out', '00:00:00')->whereRAW('created_at >= (NOW() - INTERVAL 24 HOUR)')->orderBy('id', 'desc')->first();
            if (empty($todayAttendance)) {
                if ($settings['shift'] == 'on') {
                    $getShift = Shift::find($request->shift_id);

                    $startTime = $getShift->start_time;
                    $endTime = $getShift->end_time;
                } else {
                    $startTime = $settings['company_start_time'];
                    $endTime = $settings['company_end_time'];
                }

                $attendance = AttendanceEmployee::orderBy('id', 'desc')->where('employee_id', '=', $employeeId)->where('clock_out', '=', '00:00:00')->first();

                if ($attendance != null) {
                    $attendance            = AttendanceEmployee::find($attendance->id);
                    $attendance->clock_out = $endTime;
                    $attendance->save();
                }

                $date = date("Y-m-d");
                $time = date("H:i:s");

                //late
                $totalLateSeconds = time() - strtotime($date . $startTime);
                $hours            = floor($totalLateSeconds / 3600);
                $mins             = floor($totalLateSeconds / 60 % 60);
                $secs             = floor($totalLateSeconds % 60);
                $late             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                // salary cuts
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

                /**
                 * validasi attendance late / onlime
                 */
                if ($time > date('H:i:s', strtotime($startTime))) {
                    $status_attendance = 'Late';
                } else {
                    $status_attendance = 'Ontime';
                }

                $employeeAttendance                = new AttendanceEmployee();
                $employeeAttendance->employee_id   = $employeeId;
                $employeeAttendance->date          = $date;
                $employeeAttendance->status        = $status;
                $employeeAttendance->approve       = $approve;
                $employeeAttendance->clock_in      = $time;
                $employeeAttendance->clock_out     = '00:00:00';
                $employeeAttendance->late          = $late;
                $employeeAttendance->early_leaving = '00:00:00';
                $employeeAttendance->overtime      = '00:00:00';
                $employeeAttendance->total_rest    = '00:00:00';
                $employeeAttendance->shift_id      = $request->shift_id;
                $employeeAttendance->created_by    = $request->user_id;
                $employeeAttendance->images        = (!isset($request->token) ? $fileNameToStore : null);
                $employeeAttendance->latitude      = (isset($request->latitude) ? $request->latitude : null);
                $employeeAttendance->longitude     = (isset($request->longitude) ? $request->longitude : null);

                $employeeAttendance->save();

                if ($settings['late_fee_calculation'] == 'on') {
                    setLateCharge([
                        'attendance_id' => $employeeAttendance->id,
                        'salary_cuts'   => $salary_cuts,
                        'working_hours' => '00:00:00',
                        'working_late'  => '00:00:00'
                    ]);
                }

                /**
                 * send to redis server
                 */
                if (isset($request->token) and $settings['qr_presence'] == 'on') {
                    $array = [
                        'name' => $user->name,
                        'start' => $time,
                        'end' => '00:00:00',
                        'emp_id' => $emp->employee_id,
                        'position' => $mod_user->getPosition($emp->position_id)->name,
                        'created_by' => $creator,
                        'profile' => $picture,
                        'status' => $status_attendance,
                        'hours' => sprintf('%02d', $hours),
                        'minutes' => sprintf('%02d', $mins),
                        'title' => 'In',
                        'status_qr' => 'Valid',
                        'type' => 'presence',
                        'permission' => ''
                    ];

                    event(new SendMessage($array));
                }

                if ($request->hasFile('images') and $settings['qr_presence'] == 'on') {
                    // send notif to web
                    $array = [
                        'name' => $user->name,
                        'created_by' => $creator,
                        'title' => __('Incoming Attendance'),
                        'status_qr' => 'Valid',
                        'type' => 'notif',
                        'to' => $emp->department_id #ditujukan ke kepala bagian dan hrd & super admin (level > 0)
                    ];

                    event(new SendMessage($array));

                    // send notif fcm
                    $getUser = Employee::selectRaw('users.id, users.name, users.fcm_token, users.type, users.role_id, employees.department_id')
                        ->join(
                            'users',
                            'employees.user_id',
                            '=',
                            'users.id'
                        )
                        ->where('employees.created_by', $creator)
                        ->where('users.type', '!=', 'employee')
                        ->where('users.fcm_token', '!=', null)
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
                            "title" => 'Presensi Izin',
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
                    $notification->title        = 'Presensi Izin';
                    $notification->type         = 'Izin';
                    $notification->messages     = $emp->name;
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

                return response()->json([
                    'type' => 'success',
                    'message' => 'Employee Successfully Clock In.',
                    'time' => $time,
                    'late' => $late,
                ]);
            } else {
                $array = [
                    'name' => $user->name,
                    'start' => '00:00:00',
                    'end' => '00:00:00',
                    'emp_id' => '',
                    'position' => '',
                    'created_by' => $creator,
                    'profile' => '',
                    'status' => 'Multiple',
                    'hours' => 0,
                    'minutes' => '',
                    'title' => 'In',
                    'status_qr' => 'Valid',
                    'type' => 'presence',
                    'permission' => ''
                ];

                event(new SendMessage($array));

                return response()->json([
                    'type' => 'error',
                    'message' => 'Employee are not allow multiple time clock in & clock out for every day.'
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
