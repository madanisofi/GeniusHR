<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceEmployee;
use App\Models\User;
use App\Models\Employee;
use App\Models\Utility;
use App\Models\Leave;

class AttendanceHistoryController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $emp = Employee::where('user_id', '=', $request->user_id)->first();

        if (!empty($emp)) {
            $user = User::where('id', $request->user_id)->first();

            $settings = Utility::settings($emp->created_by);

            $payroll_date   = $settings['payroll_date'];
            $payroll_time   = $settings['payroll_time'];
            $month_start_date = $settings['month_start_date'];
            $month = (isset($request->month) ? $request->month : date('m'));
            $year  = (isset($request->year) ? $request->year : date('Y'));
            if (isset($request->month)) {
                if ($payroll_time == 'first') {
                    $start_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $month_start_date));
                    $end_date = date('Y-m-d', strtotime('+1 month', strtotime($year . '-' . $month . '-' . $payroll_date)));
                    $end_date = date('Y-m-d', strtotime('-1 days', strtotime($end_date)));
                } else {
                    $start_date = date('Y-m-d', strtotime('-1 month', strtotime($year . '-' . $month . '-' . $month_start_date)));
                    $end_date = date('Y-m-d', strtotime('-1 days', strtotime($year . '-' . $month . '-' . $payroll_date)));
                }
            } else {
                if (date('d') >= $payroll_date) {
                    $start_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $month_start_date));
                    $end_date = date('Y-m-d', strtotime('+1 month', strtotime($year . '-' . $month . '-' . $payroll_date)));
                    $end_date = date('Y-m-d', strtotime('-1 days', strtotime($end_date)));
                } else {
                    $start_date = date('Y-m-d', strtotime('-1 month', strtotime($year . '-' . $month . '-' . $month_start_date)));
                    $end_date = date('Y-m-d', strtotime('-1 days', strtotime($year . '-' . $month . '-' . $payroll_date)));
                }
            }

            $date1 = strtotime($start_date);
            if (isset($request->month)) {
                if (date('m') == date('m', strtotime($start_date))) {
                    $date2 = strtotime(date('Y-m-d'));
                } else {
                    $date2 = strtotime($end_date);
                }
            } else {
                $date2 = strtotime(date('Y-m-d'));
            }
            $funcdate = $date2 - $date1;
            $difference = $funcdate / 60 / 60 / 24;

            $param_diff = [
                'd' => $difference
            ];
            $diff = (object)$param_diff;

            if ($user->type == 'employee') {

                $attendanceEmployee = AttendanceEmployee::where('employee_id', $emp->id);

                $attendanceEmployee->whereBetween(
                    'date',
                    [
                        $start_date,
                        $end_date,
                    ]
                );

                $attendanceEmployee = $attendanceEmployee->whereIn('status', array('Present', 'Pending'))->orderBy('id', 'ASC')->get();
            } else {

                if (empty($user->role->level)) {
                    #hr or super
                    $employee = Employee::select('id')->where('created_by', $user->creatorId());
                } else {
                    #head unit or other
                    $employee = Employee::select('id')
                        ->where('department_id', $emp->department_id)
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

                $attendanceEmployee->whereBetween(
                    'date',
                    [
                        $start_date,
                        $end_date,
                    ]
                );

                $attendanceEmployee = $attendanceEmployee->whereIn('status', array('Present', 'Pending'))->orderBy('date', 'DESC')->get();
            }

            // list all presence
            $list_attendance = [];
            $listAttendance = AttendanceEmployee::selectRAW('employees.name, attendance_employees.status, attendance_employees.permissiontype_id')
                ->join('employees', 'attendance_employees.employee_id', '=', 'employees.id')
                ->where('attendance_employees.date', date('Y-m-d'))
                ->where('employees.created_by', $emp->created_by)
                ->orderBy('attendance_employees.status', 'DESC')
                ->orderBy('employees.name', 'ASC')
                ->groupBy('attendance_employees.employee_id')->get();

            foreach ($listAttendance as $key => $val) {
                if ($val->status == 'Present' or $val->status == 'Pending') {
                    $list_attendance[] = [
                        'name' => $val->name,
                        'status' => $val->status,
                        'permission' => isset($val->permission) ? $val->permission->title : ''
                    ];
                }
            }

            $rest_mode = $settings['rest_mode'];

            if ($rest_mode == 'on') {
                $my_attendance = AttendanceEmployee::selectRaw('if(parent_id IS NULL, max(id), parent_id) as id, date, end_date, status, parent_id, late, working_hours, working_late, salary_cuts, (select ae.shift_id from attendance_employees as ae where ae.id = max(attendance_employees.id)) as shift_id')->where('employee_id', $emp->id)->whereIn('status', array('Present', 'Pending'));

                $my_attendance->whereBetween(
                    'date',
                    [
                        $start_date,
                        $end_date,
                    ]
                );
                $my_attendance = $my_attendance->latest()->groupBy('date')->orderBy('id', 'ASC')->get();
            } else {
                $my_attendance = AttendanceEmployee::selectRaw('if(parent_id IS NULL, id, parent_id) as id, date, end_date, status, parent_id, late, working_hours, working_late, salary_cuts, shift_id')->where('employee_id', $emp->id)->whereIn('status', array('Present', 'Pending'));

                $my_attendance->whereBetween(
                    'date',
                    [
                        $start_date,
                        $end_date,
                    ]
                );
                $my_attendance = $my_attendance->orderBy('id', 'ASC')->get();
            }

            // return $my_attendance;

            $listHistory = [];
            foreach ($my_attendance as $key => $value) {
                $listHistory[] = [
                    'id'        => $value->id,
                    'tanggal'   => $value->date,
                    'durasi'    => isset($value->latecharge) ? $value->latecharge->working_hours : ($value->working_hours != null ? $value->working_hours : '00:00:00'),
                    'terlambat' => isset($value->latecharge) ? $value->latecharge->working_late : ($value->working_late != null ? $value->working_late : '00:00:00'),
                    'denda'     => isset($value->latecharge) ? $value->latecharge->salary_cuts : $value->salary_cuts,
                    'denda_rp'  => number_format(isset($value->latecharge) ? $value->latecharge->salary_cuts : $value->salary_cuts),
                    'shift'     => isset($value->shift) ? $value->shift->name : '',
                    'status'    => $value->status
                ];
            }

            // return $listHistory;

            $my_history = [];
            $need_apprive = [];
            $total_salary_cuts = 0;
            foreach ($attendanceEmployee as $x => $val) {

                if ($val->employee_id == $emp->id) {

                    $my_history[] = [
                        'id' => $val->id,
                        'std_date' => $val->date,
                        'date' => date('d-M-Y', strtotime($val->date)),
                        'end_date' => ($val->end_date != null ? date('d-M-Y', strtotime($val->end_date)) : ''),
                        'status' => $val->status,
                        'clock_in' => $val->clock_in,
                        'clock_out' => $val->clock_out,
                        'shift' => ($val->shift != null ? $val->shift->name : ''),
                        'permission' => (!empty($val->permission) ? $val->permission->title : ''),
                        'reason' => ($val->reason != null ? $val->reason : ''),
                        'notes' => ($val->notes != null ? $val->notes : '')
                    ];
                } else {
                    if ($val->status != 'Present' && $val->parent_id == null) {
                        if ($val->date >= date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d')))) && !array_search($user->type, array_column(json_decode($val->approve), 'type'))) {
                            $need_apprive[] = [
                                'id' => $val->id,
                                'employee_id' => $val->employee_id,
                                'employee' => $val->employee->name,
                                'date' => date('d-M-Y', strtotime($val->date)),
                                'end_date' => ($val->end_date != null ? date('d-M-Y', strtotime($val->end_date)) : ''),
                                'status' => $val->status,
                                'approve' => json_decode($val->approve),
                                'clock_in' => $val->clock_in,
                                'clock_out' => $val->clock_out,
                            ];
                        }
                    }
                }
            }

            foreach ($listHistory as $key => $val) {
                $detail = [];
                foreach ($my_history as $key2 => $val2) {
                    if ($val['tanggal'] == $val2['std_date']) {
                        $detail[] = $val2;
                    }
                }
                $listHistory[$key]['detail'] = $detail;
            }

            // first command
            $newHistory = [];
            $arrWorkingHours = [];
            $rewardsTime = 0;
            $attendance_gift = $settings['attendance_gift'];
            $working_hours_per_days = $settings['working_hours'];
            $convert_working_hours  = gmdate('H:i:s', ($working_hours_per_days * 3600));

            if (count($listHistory) > 0) {

                $working_days = json_decode($settings['working_days']);
                $costAbsence = $settings['daily_no_show_fee'];

                // get leave in month
                $leaveList = Leave::where('employee_id', $emp->id);
                $leaveList->whereBetween(
                    'start_date',
                    [
                        $start_date,
                        $end_date,
                    ]
                );
                $leaveList = $leaveList->where('status', 'Approve')->get();

                $arrLeave = [];
                foreach ($leaveList as $val) {
                    if ($val->start_date != $val->end_date) {
                        for ($i = 0; $i < $val->total_leave_days; $i++) {
                            $arrDate = date('Y-m-d', strtotime('+' . ($i) . ' days', strtotime($val->start_date)));
                            array_push($arrLeave, $arrDate);
                        }
                    } else {
                        array_push($arrLeave, $val->start_date);
                    }
                }

                if (strtotime($end_date) < strtotime(date('Y-m-d'))) {
                    $timeParams = $end_date;
                } else {
                    $timeParams = date('Y-m-d');
                }

                for ($i = 0; $i <= $diff->d; $i++) {
                    $lastDate = date('Y-m-d', strtotime('-' . ($i) . ' days', strtotime($timeParams)));
                    $key = array_search($lastDate, array_column($listHistory, 'tanggal'));

                    if (in_array(date('N', strtotime($lastDate)), $working_days)) {
                        if (in_array($lastDate, $listHistory[$key])) {
                            // present

                            $newHistory[] = $listHistory[$key];

                            $total_salary_cuts += $listHistory[$key]['denda'];

                            if ($listHistory[$key]['durasi'] >= $convert_working_hours) {
                                $addworkinghours = $convert_working_hours;
                            } else {
                                $addworkinghours = $listHistory[$key]['durasi'];
                            }
                            if ($listHistory[$key]['durasi'] != '') array_push($arrWorkingHours, $addworkinghours);
                        } else {
                            // absence

                            if (!in_array($lastDate, $arrLeave)) {

                                $newHistory[] = [
                                    'id' => 0,
                                    'tanggal' => $lastDate,
                                    'durasi' => '-',
                                    'terlambat' => '-',
                                    'denda' => (int)$costAbsence,
                                    'denda_rp' => number_format($costAbsence),
                                    'status' => 'Absence',
                                    'detail' => [
                                        [
                                            'id' => 0,
                                            'std_date' => $lastDate,
                                            'date' => date('d-M-Y', strtotime($lastDate)),
                                            'end_date' => '',
                                            'status' => 'Absence',
                                            'clock_in' => '-',
                                            'clock_out' => '-',
                                            'shift' => '',
                                            'permission' => '',
                                            'reason' => '',
                                            'notes' => ''
                                        ]
                                    ]
                                ];

                                $total_salary_cuts += $costAbsence;
                            } else {
                                $newHistory[] = [
                                    'id' => 0,
                                    'tanggal' => $lastDate,
                                    'durasi' => '-',
                                    'terlambat' => '-',
                                    'denda' => 0,
                                    'denda_rp' => number_format(0),
                                    'status' => 'Leave',
                                    'detail' => [
                                        [
                                            'id' => 0,
                                            'std_date' => $lastDate,
                                            'date' => date('d-M-Y', strtotime($lastDate)),
                                            'end_date' => '',
                                            'status' => 'Leave',
                                            'clock_in' => '-',
                                            'clock_out' => '-',
                                            'shift' => '',
                                            'permission' => '',
                                            'reason' => '',
                                            'notes' => ''
                                        ]
                                    ]
                                ];
                            }
                        }
                    }
                }
            } else {

                if (strtotime($end_date) < strtotime(date('Y-m-d'))) {
                    $timeParams = $end_date;
                } else {
                    $timeParams = date('Y-m-d');
                }
                $costAbsence = $settings['daily_no_show_fee'];

                for ($i = 0; $i <= $diff->d; $i++) {
                    $lastDate = date('Y-m-d', strtotime('-' . ($i) . ' days', strtotime($timeParams)));
                    $key = array_search($lastDate, array_column($listHistory, 'tanggal'));

                    $newHistory[] = [
                        'id' => 0,
                        'tanggal' => $lastDate,
                        'durasi' => '-',
                        'terlambat' => '-',
                        'denda' => (int)$costAbsence,
                        'denda_rp' => number_format($costAbsence),
                        'status' => 'Absence',
                        'detail' => [
                            [
                                'id' => 0,
                                'std_date' => $lastDate,
                                'date' => date('d-M-Y', strtotime($lastDate)),
                                'end_date' => '',
                                'status' => 'Absence',
                                'clock_in' => '-',
                                'clock_out' => '-',
                                'shift' => '',
                                'permission' => '',
                                'reason' => '',
                                'notes' => ''
                            ]
                        ]
                    ];

                    $total_salary_cuts += $costAbsence;
                    // }
                }
            }

            // return $newHistory;

            $sumTime = accumulateTime($arrWorkingHours);
            $seconds = 0;
            list(
                $g, $i, $s
            ) = explode(':', $sumTime);
            $seconds += $g * 3600;
            $seconds += $i * 60;
            $seconds += $s;
            $hours = floor($seconds / 3600);
            $rewardsTime = $hours * $attendance_gift;

            return response()->json([
                'type' => 'success',
                'message' => 'available',
                'data' => [
                    'role' => $user->role->name,
                    'my_salary_cuts' => number_format($total_salary_cuts),
                    'attendance_rewards' => number_format($rewardsTime),
                    'my_history' => $newHistory,
                    'need_approve' => $need_apprive,
                    'attendance_list' => $list_attendance,
                ]
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }
}
