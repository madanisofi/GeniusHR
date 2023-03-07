<?php

namespace App\Http\Controllers;

use App\Models\AttendanceEmployee;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\IpRestrict;
use App\Models\User;
use App\Models\Utility;
use App\Models\Shift;
use App\Models\PermissionType;
use App\Models\Notification;
use App\Models\NotificationEmployee;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\SendMessage;
use DateTime;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Fcm;

class AttendanceEmployeeController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->can('Manage Attendance')) {
            $branch = Branch::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branch->prepend('All', '');

            $department = Department::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $department->prepend('All', '');

            $shift = Utility::getValByName('shift');
            $late_fee_calculation = Utility::getValByName('late_fee_calculation');

            $user = Auth::user();
            $user_employee = Employee::where('user_id', $user->id)->first();

            $settings = Utility::settings();

            if ($user->type == 'employee') {

                $emp = !empty($user->employee) ? $user->employee->id : 0;

                $attendanceEmployee = AttendanceEmployee::where('employee_id', $emp);

                if ($request->type == 'monthly' && !empty($request->month)) {
                    $month = date('m', strtotime($request->month));
                    $year  = date('Y', strtotime($request->month));

                    $payroll_date   = $settings['payroll_date'];
                    $payroll_time   = $settings['payroll_time'];
                    $month_start_date = $settings['month_start_date'];
                    if ($payroll_time == 'first') {
                        $start_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $month_start_date));
                        $end_date = date('Y-m-d', strtotime('+1 month', strtotime($year . '-' . $month . '-' . $payroll_date)));
                        $end_date = date('Y-m-d', strtotime('-1 days', strtotime($end_date)));
                    } else {
                        $start_date = date('Y-m-d', strtotime('-1 month', strtotime($year . '-' . $month . '-' . $month_start_date)));
                        $end_date = date('Y-m-d', strtotime('-1 days', strtotime($year . '-' . $month . '-' . $payroll_date)));
                    }

                    $attendanceEmployee->whereBetween(
                        'date',
                        [
                            $start_date,
                            $end_date,
                        ]
                    );
                } elseif ($request->type == 'daily' && !empty($request->date)) {
                    $attendanceEmployee->where('date', $request->date);
                } else {

                    $date = date('Y-m-d');

                    $attendanceEmployee->where('date', $date);
                }
                $attendanceEmployee = $attendanceEmployee->get();
            } else {

                if (empty($user->role->level)) {
                    #hr or super
                    $employee = Employee::select('id')->where('created_by', $user->creatorId());
                } else {
                    #head unit or other
                    $employee = Employee::select('id')
                        ->where('department_id', $user_employee->department_id)
                        ->where('created_by', $user->creatorId());
                }

                if (!empty($request->branch)) {
                    $employee->where('branch_id', $request->branch);
                }

                if (!empty($request->department)) {
                    $employee->where('department_id', $request->department);
                }

                $employee = $employee->get()->pluck('id');

                $attendanceEmployee = AttendanceEmployee::whereIn('employee_id', $employee);

                if ($request->type == 'monthly' && !empty($request->month)) {
                    $month = date('m', strtotime($request->month));
                    $year  = date('Y', strtotime($request->month));

                    $payroll_date   = $settings['payroll_date'];
                    $payroll_time   = $settings['payroll_time'];
                    $month_start_date = $settings['month_start_date'];
                    if ($payroll_time == 'first') {
                        $start_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $month_start_date));
                        $end_date = date('Y-m-d', strtotime('+1 month', strtotime($year . '-' . $month . '-' . $payroll_date)));
                        $end_date = date('Y-m-d', strtotime('-1 days', strtotime($end_date)));
                    } else {
                        $start_date = date('Y-m-d', strtotime('-1 month', strtotime($year . '-' . $month . '-' . $month_start_date)));
                        $end_date = date('Y-m-d', strtotime('-1 days', strtotime($year . '-' . $month . '-' . $payroll_date)));
                    }

                    $attendanceEmployee->whereBetween(
                        'date',
                        [
                            $start_date,
                            $end_date,
                        ]
                    );
                } elseif ($request->type == 'daily' && !empty($request->date)) {
                    $attendanceEmployee->where('date', $request->date);
                } else {

                    $date = date('Y-m-d');

                    $attendanceEmployee->where('date', $date);
                }


                $attendanceEmployee = $attendanceEmployee->get();
            }

            $qr_presence    = $settings['qr_presence'];

            return view('attendance.index', compact('attendanceEmployee', 'branch', 'department', 'shift', 'user', 'qr_presence', 'late_fee_calculation'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function showpicture($attendanceEmployee)
    {
        $data = AttendanceEmployee::find($attendanceEmployee);

        $public = asset(url('uploads/attendance'));
        if ($data->images != null) {
            $picture = $public . '/' . $data->images;
        } else {
            if ($data->images_reason != null) {
                $picture = $public . '/' . $data->images_reason;
            } else {
                $picture = $public . '/' . 'no_image.png';
            }
        }

        return view('attendance.show', compact('picture'));
    }

    public function showpictureout($attendanceEmployee)
    {
        $data = AttendanceEmployee::find($attendanceEmployee);

        $public = asset(url('uploads/attendance'));
        if ($data->images_out != null) {
            $picture = $public . '/' . $data->images_out;
        } else {
            $picture = $public . '/' . 'no_image.png';
        }

        return view('attendance.show', compact('picture'));
    }

    public function create()
    {
        if (Auth::user()->can('Create Attendance')) {
            $employees = Employee::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $shift  = Shift::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $shift_setting    = Utility::getValByName('shift');

            return view('attendance.create', compact('employees', 'shift', 'shift_setting'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Attendance')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'date' => 'required',
                    'clock_in' => 'required',
                    'clock_out' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $employees = Employee::where('id', $request->employee_id)->first();

            $mod_user = new User();

            $settings = Utility::settings();
            $qr_presence    = $settings['qr_presence'];

            if ($settings['shift'] == 'on') {
                $getShift = Shift::find($request->shift_id);

                $startTime = $getShift->start_time;
                $endTime = $getShift->end_time;
            } else {
                $startTime = $settings['company_start_time'];
                $endTime = $settings['company_end_time'];
            }

            $attendance = AttendanceEmployee::where('employee_id', '=', $employees->id)->where('date', '=', $request->date)->where('clock_out', '=', '00:00:00')->get()->toArray();
            if ($attendance) {
                return redirect()->route('attendanceemployee.index')->with('error', __('Employee Attendance Already Created.'));
            } else {
                $date = date("Y-m-d");

                $user = User::find($employees->user_id);

                $totalLateSeconds = strtotime($request->clock_in) - strtotime($date . $startTime);

                $hours = floor($totalLateSeconds / 3600);
                $mins  = floor($totalLateSeconds / 60 % 60);
                $secs  = floor($totalLateSeconds % 60);
                $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                if (strtotime($request->clock_out) > strtotime($date . $endTime)) {
                    //Overtime
                    $totalOvertimeSeconds = strtotime($request->clock_out) - strtotime($date . $endTime);
                    $hours                = floor($totalOvertimeSeconds / 3600);
                    $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                    $secs                 = floor($totalOvertimeSeconds % 60);
                    $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                } else {
                    $overtime = '00:00:00';
                }

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

                $employeeAttendance                = new AttendanceEmployee();
                $employeeAttendance->employee_id   = $employees->id;
                $employeeAttendance->date          = $request->date;
                $employeeAttendance->status        = 'Present';
                $employeeAttendance->approve       = json_encode([]);
                $employeeAttendance->clock_in      = $request->clock_in . ':00';
                $employeeAttendance->clock_out     = $request->clock_out . ':00';
                $employeeAttendance->late          = $late;
                $employeeAttendance->early_leaving = '00:00:00';
                $employeeAttendance->overtime      = '00:00:00';
                $employeeAttendance->total_rest    = '00:00:00';
                $employeeAttendance->shift_id      = (isset($request->shift_id) ? $request->shift_id : null);
                $employeeAttendance->created_by    = Auth::user()->creatorId();
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
                if ($qr_presence == 'on') {

                    /**
                     * validasi attendance late / onlime
                     */
                    if ($request->clock_in . ':00' > date('H:i:s', strtotime($startTime))) {
                        $status_attendance = 'Late';
                    } else {
                        $status_attendance = 'Ontime';
                    }

                    /**
                     * get avatar for presensi QR
                     */
                    $profile = asset(url('uploads/avatar/'));
                    if (!empty($user->avatar)) {
                        $picture = $profile . '/' . $user->avatar;
                    } else {
                        $picture = $profile . '/user.png';
                    }

                    $array = [
                        'name' => $employees->name,
                        'start' => $request->clock_in . ':00',
                        'end' => '00:00:00',
                        'emp_id' => $employees->employee_id,
                        'position' => $mod_user->getPosition($employees->position_id)->name,
                        'created_by' => $employees->created_by,
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

                return redirect()->route('attendanceemployee.index')->with('success', __('Employee attendance successfully created.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('Edit Attendance')) {
            $attendanceEmployee = AttendanceEmployee::where('id', $id)->first();
            $employees          = Employee::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('attendance.edit', compact('attendanceEmployee', 'employees'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        $settings = Utility::settings();
        $todayAttendance = AttendanceEmployee::where('status', '=', 'Present')->where('id', '=', $id)->where('clock_out', '00:00:00')->whereRAW('created_at >= (NOW() - INTERVAL 24 HOUR)')->orderBy('id', 'DESC')->first();
        if (!empty($todayAttendance) && $todayAttendance->clock_out == '00:00:00') {
            if ($settings['shift'] == 'on') {
                $getShift = Shift::find($todayAttendance->shift_id);

                $startTime = $getShift->start_time;

                $schedule = Schedule::where('employee_id', $todayAttendance->employee_id)->orderBy('id', 'desc')->first();
                if ($schedule->day != null and $schedule->repeat == 'on') {
                    $getCountAttendanceToday = AttendanceEmployee::where('employee_id', $todayAttendance->employee_id)->where('date', date('Y-m-d'))->count();
                    if ($getCountAttendanceToday < count(json_decode($schedule->day))) {
                        $endTime = $settings['company_end_time'];
                    } else {
                        $endTime = $getShift->end_time;
                    }
                } else {
                    $endTime = $getShift->end_time;
                }
            } else {
                $startTime = Utility::getValByName('company_start_time');
                $endTime   = Utility::getValByName('company_end_time');
            }

            $date = $todayAttendance->date;
            $tolerance = $settings['presence_tolerance'];
            $time = checkClockOut($tolerance, date('H:i:s', strtotime($request->clock_out)), $endTime);

            $totalLateSeconds = strtotime($request->clock_in) - strtotime($date . $startTime);

            $hours = floor($totalLateSeconds / 3600);
            $mins  = floor($totalLateSeconds / 60 % 60);
            $secs  = floor($totalLateSeconds % 60);
            $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

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

            //early Leaving
            $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($request->clock_out);
            $hours                    = floor($totalEarlyLeavingSeconds / 3600);
            $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
            $secs                     = floor($totalEarlyLeavingSeconds % 60);
            $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

            if (strtotime($request->clock_out) < strtotime($date . $endTime)) {
                //Overtime
                $totalOvertimeSeconds = strtotime($request->clock_out) - strtotime($date . $endTime);
                $hours                = floor($totalOvertimeSeconds / 3600);
                $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                $secs                 = floor($totalOvertimeSeconds % 60);
                $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                // $status_attendance = 'Early';
            } else {
                $overtime = '00:00:00';
                // $status_attendance = 'Ontime';
            }

            $attendanceEmployee                = AttendanceEmployee::find($todayAttendance->id);
            if (date('H:i:s', strtotime($request->clock_in)) != $attendanceEmployee->clock_in) {
                $attendanceEmployee->clock_in  = $request->clock_in;
                $attendanceEmployee->late      = $late;
            }

            if (date('H:i:s', strtotime($request->clock_out)) != '00:00:00') {
                $attendanceEmployee->clock_out     = $request->clock_out;
                $attendanceEmployee->early_leaving = $earlyLeaving;
                $attendanceEmployee->overtime      = $overtime;
            }
            $attendanceEmployee->save();

            if (date('H:i:s', strtotime($request->clock_out)) != '00:00:00') {
                if ($settings['rest_mode'] == 'on') {
                    $allAttendance = AttendanceEmployee::where('employee_id', $todayAttendance->employee_id)->where('date', date('Y-m-d'))->get()->toarray();
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
                    $working            = strtotime($date . ($todayAttendance->clock_out != '00:00:00' ? $todayAttendance->clock_out : $time)) - strtotime($todayAttendance->date . $attendanceEmployee->clock_in);
                    $hrs                = floor($working / 3600);
                    $mins               = floor($working / 60 % 60);
                    $secs               = floor($working % 60);
                    $accumulate_hours   = sprintf('%02d:%02d:%02d', $hrs, $mins, $secs);
                }

                $countLate = countingSalaryCutsV2(
                    $accumulate_hours,
                    $settings['working_hours'],
                    $settings['late_fee_in_minutes'],
                    $settings['maximum_late_fee_in_one_day'],
                    $settings['late_accumulation']
                );

                if ($settings['late_fee_calculation'] == 'on') {
                    updateLateCharge([
                        'attendance_id'     => $todayAttendance->id,
                        'salary_cuts'       => $countLate['salary_cuts'],
                        'working_hours'     => $countLate['working_hours'],
                        'working_late'      => $countLate['hours'] . ':' . $countLate['mins'] . ':' . $countLate['sec']
                    ]);
                }
            } else {
                if ($settings['late_fee_calculation'] == 'on') {
                    updateLateCharge([
                        'attendance_id'     => $todayAttendance->id,
                        'salary_cuts'       => $salary_cuts,
                        'working_hours'     => '00:00:00',
                        'working_late'      => '00:00:00'
                    ]);
                }
            }

            return redirect()->route('attendanceemployee.index')->with('success', __('Employee attendance successfully updated.'));
        } else {
            return back()->with('error', __('Presence Not Found Or Not Approved'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('Delete Attendance')) {
            $attendance = AttendanceEmployee::where('id', $id)->first();

            $attendance->delete();

            return redirect()->route('attendanceemployee.index')->with('success', __('Attendance successfully deleted.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function attendance(Request $request)
    {
        $settings = Utility::settings();
        $creator = Auth::user()->creatorId();

        if ($settings['ip_restrict'] == 'on') {
            $userIp = request()->ip();
            $ip     = IpRestrict::where('created_by', $creator)->whereIn('ip', [$userIp])->first();
            if (empty($ip)) {
                return back()->with('error', __('this ip is not allowed to clock in & clock out.'));
            }
        }

        $user = Auth::user();
        $employeeId      = !empty($user->employee) ? $user->employee->id : 0;
        $emp = Employee::where('user_id', $user->id)->first();
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

            // check qr presenc on / off
            if ($settings['qr_presence'] == 'on' or $settings['selfie_presence'] == 'on') {
                $status     = 'Pending';
                $approve    = null;
            } else {
                $status     = 'Present';
                $approve    = null;
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

            $checkPermission = PermissionType::find($request->permission_id);

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

            if ($checkPermission->many_submission == 'yes') {
                $start_date = new DateTime($request->start_date);
                $end_date = new DateTime($request->end_date);

                $diff = $end_date->diff($start_date);

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
                    return back()->with('error', __('Insufficient Licensing Quota.'));
                }

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
                $employeeAttendance->created_by    = $user->id;
                $employeeAttendance->reason        = $request->reason;
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
                        'created_by'    => $user->id,
                        'reason'        => $request->reason,
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
                $employeeAttendance->status        = $status;
                $employeeAttendance->approve       = json_encode([]);
                $employeeAttendance->clock_in      = $time;
                $employeeAttendance->clock_out     = $attendance_out;
                $employeeAttendance->late          = $late;
                $employeeAttendance->early_leaving = '00:00:00';
                $employeeAttendance->overtime      = '00:00:00';
                $employeeAttendance->total_rest    = '00:00:00';
                $employeeAttendance->created_by    = $user->id;
                $employeeAttendance->reason        = $request->reason;
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

            if ($settings['qr_presence'] == 'on' or $settings['selfie_presence'] == 'on') {
                // send notif to web
                $array = [
                    'name' => $user->name,
                    'created_by' => $user->created_by,
                    'title' => __('Incoming Attendance'),
                    'status_qr' => 'Valid',
                    'type' => 'notif',
                    'to' => $user->employee->department_id #ditujukan ke kepala bagian dan hrd & super admin (level > 0)
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
                    ->where('users.id', '!=', $user->id)
                    ->get();

                $firebaseToken = [];
                $userNotif = [];
                foreach ($getUser as $x) {
                    if ($x->department_id == $user->employee->department_id or $x->type == 'hr') {
                        array_push($firebaseToken, $x->fcm_token);
                        array_push($userNotif, $x->id);
                    }
                }

                $firebaseToken = User::whereIn('id', array(55))->pluck('fcm_token')->all();
                $permissionTitle = PermissionType::find($request->permission_id)->title;
                $data = [
                    "registration_ids" => $firebaseToken,
                    "notification" => [
                        "title" => 'Presensi Izin ' . $permissionTitle,
                        "body" => $user->name . ', Membutuhkan Persetujuan Anda',
                    ],
                    "data" => [
                        "type" => "Izin",
                        "id" => $employeeAttendance->id
                    ]
                ];

                Fcm::sendMessage($data);

                // save notif into database
                $notification               = new Notification();
                $notification->title        = 'Presensi Izin ' . $permissionTitle;
                $notification->type         = 'Izin';
                $notification->messages     = $user->name . ', Membutuhkan Persetujuan Anda';
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

            return redirect()->route('home')->with('success', __('Employee Successfully Clock In.'));
        } else {
            return back()->with('error', __('Employee are not allow multiple time clock in & clock for every day.'));
        }
    }

    public function bulkAttendance(Request $request)
    {
        if (Auth::user()->can('Create Attendance')) {

            $branch = Branch::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branch->prepend('Select Branch', '');

            $department = Department::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $department->prepend('Select Department', '');

            $employees = [];
            if (!empty($request->branch) && !empty($request->department)) {
                $employees = Employee::where('created_by', Auth::user()->creatorId())->where('branch_id', $request->branch)->where('department_id', $request->department)->get();
            }


            return view('attendance.bulk', compact('employees', 'branch', 'department'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function bulkAttendanceData(Request $request)
    {

        if (Auth::user()->can('Create Attendance')) {
            if (!empty($request->branch) && !empty($request->department)) {
                $startTime = Utility::getValByName('company_start_time');
                $endTime   = Utility::getValByName('company_end_time');

                $employees = $request->employee_id;
                $atte      = [];
                foreach ($employees as $employee) {
                    $present = 'present-' . $employee;
                    $in      = 'in-' . $employee;
                    $out     = 'out-' . $employee;
                    $atte[]  = $present;
                    if ($request->$present == 'on') {

                        $in  = date("H:i:s", strtotime($request->$in));
                        $out = date("H:i:s", strtotime($request->$out));

                        $totalLateSeconds = strtotime($in) - strtotime($startTime);

                        $hours = floor($totalLateSeconds / 3600);
                        $mins  = floor($totalLateSeconds / 60 % 60);
                        $secs  = floor($totalLateSeconds % 60);
                        $late  = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                        //early Leaving
                        $totalEarlyLeavingSeconds = strtotime($endTime) - strtotime($out);
                        $hours                    = floor($totalEarlyLeavingSeconds / 3600);
                        $mins                     = floor($totalEarlyLeavingSeconds / 60 % 60);
                        $secs                     = floor($totalEarlyLeavingSeconds % 60);
                        $earlyLeaving             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);


                        if (strtotime($out) > strtotime($endTime)) {
                            //Overtime
                            $totalOvertimeSeconds = strtotime($out) - strtotime($endTime);
                            $hours                = floor($totalOvertimeSeconds / 3600);
                            $mins                 = floor($totalOvertimeSeconds / 60 % 60);
                            $secs                 = floor($totalOvertimeSeconds % 60);
                            $overtime             = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        } else {
                            $overtime = '00:00:00';
                        }


                        $attendance = AttendanceEmployee::where('employee_id', '=', $employee)->where('date', '=', $request->date)->first();

                        if (!empty($attendance)) {
                            $employeeAttendance = $attendance;
                        } else {
                            $employeeAttendance              = new AttendanceEmployee();
                            $employeeAttendance->employee_id = $employee;
                            $employeeAttendance->created_by  = Auth::user()->creatorId();
                        }


                        $employeeAttendance->date          = $request->date;
                        $employeeAttendance->status        = 'Present';
                        $employeeAttendance->clock_in      = $in;
                        $employeeAttendance->clock_out     = $out;
                        $employeeAttendance->late          = $late;
                        $employeeAttendance->early_leaving = ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00';
                        $employeeAttendance->overtime      = $overtime;
                        $employeeAttendance->total_rest    = '00:00:00';
                        $employeeAttendance->save();
                    } else {
                        $attendance = AttendanceEmployee::where('employee_id', '=', $employee)->where('date', '=', $request->date)->first();

                        if (!empty($attendance)) {
                            $employeeAttendance = $attendance;
                        } else {
                            $employeeAttendance              = new AttendanceEmployee();
                            $employeeAttendance->employee_id = $employee;
                            $employeeAttendance->created_by  = Auth::user()->creatorId();
                        }

                        $employeeAttendance->status        = 'Leave';
                        $employeeAttendance->date          = $request->date;
                        $employeeAttendance->clock_in      = '00:00:00';
                        $employeeAttendance->clock_out     = '00:00:00';
                        $employeeAttendance->late          = '00:00:00';
                        $employeeAttendance->early_leaving = '00:00:00';
                        $employeeAttendance->overtime      = '00:00:00';
                        $employeeAttendance->total_rest    = '00:00:00';
                        $employeeAttendance->save();
                    }
                }

                return back()->with('success', __('Employee attendance successfully created.'));
            } else {
                return back()->with('error', __('Branch & department field required.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function action($id)
    {
        $attendance     = AttendanceEmployee::find($id);
        $employee       = Employee::find($attendance->employee_id);
        $qr_presence    = Utility::getValByName('qr_presence');
        $public         = asset(url('uploads/attendance'));

        return view('attendance.action', compact('employee', 'attendance', 'qr_presence', 'public'));
    }

    public function changeaction(Request $request)
    {

        $attendance = AttendanceEmployee::find($request->attendance_id);
        $user = Auth::user();
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

            // return $check_role;
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

        $settings = Utility::settings();

        if ($attendance->status != 'Absence') {

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
                if ($approval >= Utility::getValByName('attendance_approval')) {
                    foreach (json_decode($approve_list) as $key => $value) {
                        if ($value->status != 'Approve') $attendanceStatus = 'Reject';
                    }

                    $attendance->status        = $attendanceStatus;

                    AttendanceEmployee::where('parent_id', $request->attendance_id)->update(['status' => $attendanceStatus]);

                    // send notif to show qr view
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
                            'created_by' => $employee->created_by,
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
            // send notif fcm to employee

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

        $attendance->approve = json_encode($approve);
        $attendance->save();

        return redirect()->route('attendanceemployee.index')->with('success', __('Attendance status successfully updated.'));
    }
}
