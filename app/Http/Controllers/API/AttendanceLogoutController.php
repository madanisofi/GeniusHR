<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceEmployee;
use App\Models\Employee;
use App\Models\User;
use App\Models\Utility;
use App\Models\Shift;
use App\Models\Qrtoken;
use App\Models\Schedule;
use Illuminate\Support\Facades\Storage;
use App\Events\SendMessage;

class AttendanceLogoutController extends Controller
{
    public function logout(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {

            $creator = $user->created_by;
            $mod_user = new User();

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
                        'title' => 'Out',
                        'status_qr' => 'Invalid',
                        'type' => 'presence'
                    ];

                    event(new SendMessage($array));

                    return response()->json([
                        'type' => 'error',
                        'message' => 'Token Invalid',
                    ]);
                }
            } else {
                /**
                 * uploads images selfie
                 */

                $request->validate([
                    'images' => 'required|image|mimes:jpeg,png,jpg,svg',
                ]);

                // selfie
                $fileNameToStore = '';
                if ($request->hasFile('images')) {
                    $filenameWithExt = $request->file('images')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('images')->getClientOriginalExtension();
                    // hash file before store
                    $filename = md5($filename . time());
                    $fileNameToStore = $filename . '_logout_' . time() . '.' . $extension;
                    $dir             = storage_path('uploads/attendance/');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $path = $request->file('images')->storeAs('uploads/attendance/', $fileNameToStore);
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

            $settings = Utility::settings($creator);

            $employeeId      = !empty($emp) ? $emp->id : 0;
            $todayAttendance = AttendanceEmployee::where('status', '=', 'Present')->where('employee_id', '=', $employeeId)->where('clock_out', '00:00:00')->whereRAW('created_at >= (NOW() - INTERVAL 24 HOUR)')->orderBy('id', 'DESC')->first();
            if (!empty($todayAttendance)) {
                if ($settings['shift'] == 'on') {
                    $getShift = Shift::find($todayAttendance->shift_id);

                    $startTime = $getShift->start_time;
                    // $endTime = $getShift->end_time;

                    $schedule = Schedule::where('employee_id', $employeeId)->orderBy('id', 'desc')->first();
                    if ($schedule->day != null and $schedule->repeat == 'on') {
                        $getCountAttendanceToday = AttendanceEmployee::where('employee_id', $employeeId)->where('date', date('Y-m-d'))->count();
                        if ($getCountAttendanceToday < count(json_decode($schedule->day))) {
                            $endTime = $settings['company_end_time'];
                        } else {
                            $endTime = $getShift->end_time;
                        }
                    } else {
                        $endTime = $getShift->end_time;
                    }
                } else {
                    $endTime = $settings['company_end_time'];
                }

                if (date('Y-m-d') != $todayAttendance->date) {
                    if ($todayAttendance->shift != null and date('H:i:s', strtotime($todayAttendance->shift->start_time)) > date('H:i:s', strtotime($todayAttendance->shift->end_time))) {
                        $date = date('Y-m-d');
                        $time = date('H:i:s');
                    } else {
                        $date = $todayAttendance->date;
                        $tolerance = 0; #tolerance only in same day
                        $convert_tolerane       = gmdate('H:i:s', ($tolerance * 60));
                        $convert_working_time   = date('H:i:s', strtotime($endTime));
                        $times                  = [$convert_tolerane, $convert_working_time];

                        $time = accumulateTime($times);
                    }
                } else {
                    $date = date("Y-m-d");
                    $tolerance = $settings['presence_tolerance'];
                    $time = checkClockOut($tolerance, date('H:i:s'), $endTime);
                }

                //early Leaving
                $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($date . $time);
                $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                $secs                     = floor($totalEarlyLeavingSeconds % 60);
                $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                if (strtotime($date . $time) < strtotime($date . $endTime)) {
                    //Overtime
                    $totalOvertimeSeconds = strtotime($date . $time) - strtotime($date . $endTime);
                    $hours                = floor($totalOvertimeSeconds / 3600);
                    $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                    $secs                 = floor($totalOvertimeSeconds % 60);
                    $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                } else {
                    $overtime = '00:00:00';
                }

                $attendanceEmployee                = AttendanceEmployee::find($todayAttendance->id);
                $attendanceEmployee->clock_out     = $time;
                $attendanceEmployee->early_leaving = $earlyLeaving;
                $attendanceEmployee->overtime      = $overtime;
                $attendanceEmployee->images_out    = (!isset($request->token) ? $fileNameToStore : null);
                $attendanceEmployee->save();

                if ($settings['rest_mode'] == 'on') {
                    $allAttendance = AttendanceEmployee::where('employee_id', $employeeId)->where('date', date('Y-m-d'))->get()->toarray();
                    $times = [];

                    if (count($allAttendance) > 1) {
                        foreach ($allAttendance as $key => $value) {
                            $working            = strtotime($value['date'] . ($value['clock_out'] != '00:00:00' ? $value['clock_out'] : $time)) - strtotime($value['date'] . $value['clock_in']);
                            $hrs                = floor($working / 3600);
                            $mins               = floor($working / 60 % 60);
                            $secs               = floor($working % 60);
                            $accumulate_hours   = sprintf('%02d:%02d:%02d', $hrs, $mins, $secs);
                            $times[]            = $accumulate_hours;
                        }
                        // return $times;
                        $accumulate_hours = accumulateTime($times);
                    } else {

                        $working            = strtotime($allAttendance[0]['date'] . ($allAttendance[0]['clock_out'] != '00:00:00' ? $allAttendance[0]['clock_out'] : $time)) - strtotime($allAttendance[0]['date'] . $allAttendance[0]['clock_in']);
                        $hrs                = floor($working / 3600);
                        $mins               = floor($working / 60 % 60);
                        $secs               = floor($working % 60);
                        $accumulate_hours   = sprintf('%02d:%02d:%02d', $hrs, $mins, $secs);

                        $rest_time          = gmdate('H:i:s', ($settings['rest_time'] * 60));

                        $diff               = strtotime($accumulate_hours) - strtotime($rest_time);
                        $hrs                = floor($diff / 3600);
                        $mins               = floor($diff / 60 % 60);
                        $secs               = floor($diff % 60);
                        $accumulate_hours   = sprintf('%02d:%02d:%02d', $hrs, $mins, $secs);
                    }
                } else {
                    $working            = strtotime($date . ($todayAttendance->clock_out != '00:00:00' ? $todayAttendance->clock_out : $time)) - strtotime($todayAttendance->date . $todayAttendance->clock_in);
                    $hrs                = floor($working / 3600);
                    $mins               = floor($working / 60 % 60);
                    $secs               = floor($working % 60);
                    $accumulate_hours   = sprintf('%02d:%02d:%02d', $hrs, $mins, $secs);
                }

                // return $accumulate_hours;

                $countLate = countingSalaryCutsV2(
                    $accumulate_hours,
                    $settings['working_hours'],
                    $settings['late_fee_in_minutes'],
                    $settings['maximum_late_fee_in_one_day'],
                    $settings['late_accumulation']
                );

                // return $countLate;

                if ($settings['late_fee_calculation'] == 'on') {
                    updateLateCharge([
                        'attendance_id'     => $todayAttendance->id,
                        'salary_cuts'       => $countLate['salary_cuts'],
                        'working_hours'     => $countLate['working_hours'],
                        'working_late'      => $countLate['hours'] . ':' . $countLate['mins'] . ':' . $countLate['sec']
                    ]);
                }

                if ($countLate['status'] == 'Late') {
                    $status_attendance  = 'Late';
                    $counting_hours     = $countLate['hours'];
                    $counting_minutes   = $countLate['mins'];
                } else {
                    $status_attendance = 'Ontime';
                    $counting_hours     = $countLate['hours'];
                    $counting_minutes   = $countLate['mins'];
                }

                /**
                 * send to redis server
                 */
                if (isset($request->token) and $settings['qr_presence'] == 'on') {
                    $array = [
                        'name' => $user->name,
                        'start' => $todayAttendance->clock_in,
                        'end' => $time,
                        'emp_id' => $emp->employee_id,
                        'position' => $mod_user->getPosition($emp->position_id)->name,
                        'created_by' => $creator,
                        'profile' => $picture,
                        'status' => $status_attendance,
                        'hours' => $counting_hours,
                        'minutes' => $counting_minutes,
                        'title' => 'Out',
                        'status_qr' => 'Valid',
                        'type' => 'presence',
                        'permission' => ''
                    ];

                    event(new SendMessage($array));
                }

                if ($request->hasFile('images')) {
                    $array = [
                        'name' => $user->name,
                        'created_by' => $creator,
                        'title' => __('Out Attendance'),
                        'status_qr' => 'Valid',
                        'type' => 'notif',
                        'to' => $emp->department_id #ditujukan ke kepala bagian dan hrd & super admin (level > 0)
                    ];

                    event(new SendMessage($array));
                }

                return response()->json([
                    'type' => 'success',
                    'message' => 'Employee successfully clock Out.'
                ]);
            } else {
                $array = [
                    'name' => $user->name,
                    'start' => '00:00',
                    'end' => '00:00',
                    'emp_id' => '',
                    'position' => '',
                    'created_by' => $creator,
                    'profile' => '',
                    'status' => 'Multiple',
                    'hours' => 0,
                    'minutes' => '',
                    'title' => 'Out',
                    'status_qr' => 'Valid',
                    'type' => 'presence',
                    'permission' => ''
                ];

                event(new SendMessage($array));

                return response()->json([
                    'type' => 'error',
                    'message' => 'Presence Not Found Or Not Approved'
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
