<?php

namespace App\Http\Controllers\API;

use App\Models\AttendanceEmployee;
use App\Models\User;
use App\Models\Employee;
use App\Models\Utility;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $emp = Employee::where('user_id', '=', $request->user_id)->first();

        if (!empty($emp)) {
            $user = User::where('id', $request->user_id)->first();
            $creator = $user->created_by;
            $settings = Utility::settings($emp->created_by);
            $getUser = Employee::where('created_by', '=', $creator)->where('is_active', 1)->get()->pluck('id');
            $getUserName = Employee::where('created_by', '=', $creator)->where('is_active', 1)->get()->pluck('name', 'id');
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
            $date2 = strtotime($end_date);
            $funcdate = $date2 - $date1;
            $difference = $funcdate / 60 / 60 / 24;

            $param_diff = [
                'd' => $difference
            ];
            $diff = (object)$param_diff;

            $rest_mode = $settings['rest_mode'];

            if ($rest_mode == 'on') {
                $my_attendance = AttendanceEmployee::selectRaw('if(parent_id IS NULL, max(id), parent_id) as id, employee_id, date, end_date, status, parent_id, late, working_hours, working_late, salary_cuts, (select ae.shift_id from attendance_employees as ae where ae.id = max(attendance_employees.id)) as shift_id')->whereIn('employee_id', $getUser);

                $my_attendance->whereBetween(
                    'date',
                    [
                        $start_date,
                        $end_date,
                    ]
                );
                $my_attendance = $my_attendance->latest()->groupBy('date')->orderBy('id', 'ASC')->get();
            } else {
                $my_attendance = AttendanceEmployee::selectRaw('if(parent_id IS NULL, id, parent_id) as id, employee_id, date, end_date, status, parent_id, late, working_hours, working_late, salary_cuts, shift_id')->whereIn('employee_id', $getUser);

                $my_attendance->whereBetween(
                    'date',
                    [
                        $start_date,
                        $end_date,
                    ]
                );
                $my_attendance = $my_attendance->orderBy('id', 'ASC')->get();
            }

            $history = [];
            foreach ($getUser as $val) {
                foreach ($my_attendance as $key => $value) {
                    if ($value->employee_id == $val) {
                        $history[$val][] = [
                            'id'                => $value->id,
                            'employee_id'       => $value->employee_id,
                            'employee'          => $value->employee->name,
                            'std_date'          => $value->date,
                            'working_hours'     => isset($value->latecharge) ? $value->latecharge->working_hours : ($value->working_hours != null ? $value->working_hours : '00:00:00'),
                            'salary_cuts_int'   => isset($value->latecharge) ? $value->latecharge->salary_cuts : $value->salary_cuts,
                        ];
                    }
                }
            }

            $data_hours = [];
            $working_days = json_decode($settings['working_days']);

            if (count($history) > 0) {
                foreach ($getUser as $value) {
                    // get leave in month
                    $arrWorkingHours = [];

                    for ($i = 0; $i <= $diff->d; $i++) {
                        $lastDate = date('Y-m-d', strtotime('-' . ($i) . ' days', strtotime($end_date)));
                        if (isset($history[$value])) {
                            $key = array_search($lastDate, array_column($history[$value], 'std_date'));

                            if (in_array(date('N', strtotime($lastDate)), $working_days)) {
                                if (in_array($lastDate, $history[$value][$key])) {
                                    $addworkinghours = $history[$value][$key]['working_hours'];
                                    if ($history[$value][$key]['working_hours'] != '') array_push($arrWorkingHours, $addworkinghours);
                                }
                            }
                        }
                    }

                    $sumTime = accumulateTime($arrWorkingHours);
                    $seconds = 0;
                    list(
                        $g, $i, $s
                    ) = explode(
                        ':',
                        $sumTime
                    );
                    $seconds += $g * 3600;
                    $seconds += $i * 60;
                    $seconds += $s;
                    $hrs                = floor($seconds / 3600);
                    $mins               = floor($seconds / 60 % 60);
                    $secs               = floor($seconds % 60);
                    $accumulate         = sprintf('%02d:%02d:%02d', $hrs, $mins, $secs);
                    $data_hours[] = [
                        'name' => $getUserName[$value],
                        'value' => $accumulate,
                        'sec'   => $seconds
                    ];
                }
            }

            $keys = array_column($data_hours, 'sec');
            array_multisort($keys, SORT_DESC, $data_hours);

            return response()->json([
                'type' => 'success',
                'message' => 'available',
                'data' => $data_hours
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }
}
