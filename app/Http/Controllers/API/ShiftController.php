<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Shift;
use App\Models\Schedule;
use App\Models\Employee;
use App\Models\Utility;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {
            $creator = $user->created_by;

            $settings = Utility::settings($creator);

            $access_in_before = gmdate('H:i:s', ($settings['can_access_attendance_in_before'] * 60));
            $access_in_after = gmdate('H:i:s', ($settings['can_access_attendance_in_after'] * 60));

            $employee = Employee::where('user_id', '=', $request->user_id)->first();
            $schedule = Schedule::selectRaw('schedules.id as id, schedules.shift_id, schedules.room_id, day_on_month, month, schedules.repeat, employees.name as employee, day, schedules.created_by as created_by')
                ->join('employees', 'schedules.employee_id', '=', 'employees.id')
                ->where('schedules.employee_id', $employee->id)
                ->where('schedules.created_by', '=', $creator)
                ->orderBy('schedules.updated_at', 'DESC')->first();

            if (empty($schedule)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {

                $data = [];

                if ($schedule->day == null) {
                    $getShift = Shift::find($schedule->shift_id)->first();
                    $data[] = [
                        'id'    => (string)$schedule->shift_id,
                        'name' => $getShift->name,
                        'start_time' => $getShift->start_time,
                        'end_time' => $getShift->end_time
                    ];
                }

                if ($schedule->day != null and $schedule->repeat != 'on') {
                    foreach (json_decode($schedule->day) as $key => $value) {
                        if (date('Y-m-d', strtotime($value->date)) == date('Y-m-d')) {
                            foreach ($value->shift as $x => $val) {
                                $getShift = Shift::where('id', $val)->first();

                                if ($settings['can_access_attendance_in_before'] != 0) {
                                    if ($settings['can_access_attendance_in_after'] != 0) {
                                        if (strtotime(accumulateTime([date('H:i:s'), $access_in_before])) >= strtotime(date('H:i:s', strtotime($getShift->start_time))) and strtotime(date('H:i:s')) < strtotime(accumulateTime([date('H:i:s', strtotime($getShift->start_time)), $access_in_after]))) {
                                            $data[] = [
                                                'id' => $val,
                                                'name' => $getShift->name,
                                                'start_time' => $getShift->start_time,
                                                'end_time' => $getShift->end_time,
                                                'date' => $value->date
                                            ];
                                        }
                                    } else {
                                        if (strtotime(accumulateTime([date('H:i:s'), $access_in_before])) >= strtotime(date('H:i:s', strtotime($getShift->start_time))) and strtotime(date('H:i:s')) <= strtotime(date('H:i:s', strtotime($getShift->end_time)))) {
                                            $data[] = [
                                                'id' => $val,
                                                'name' => $getShift->name,
                                                'start_time' => $getShift->start_time,
                                                'end_time' => $getShift->end_time,
                                                'date' => $value->date
                                            ];
                                        }
                                    }
                                } else {
                                    $data[] = [
                                        'id' => $val,
                                        'name' => $getShift->name,
                                        'start_time' => $getShift->start_time,
                                        'end_time' => $getShift->end_time,
                                        'date' => $value->date
                                    ];
                                }
                            }
                        }
                    }
                }

                if ($schedule->day != null and $schedule->repeat == 'on') {
                    if ($settings['can_access_attendance_in_before'] != 0) {
                        foreach (json_decode($schedule->day) as $key => $value) {
                            if ($settings['can_access_attendance_in_after'] != 0) {
                                if (strtotime(accumulateTime([date('H:i:s'), $access_in_before])) >= strtotime(date('H:i:s', strtotime($value->start_time))) and strtotime(date('H:i:s')) < strtotime(accumulateTime([date('H:i:s', strtotime($value->start_time)), $access_in_after]))) {
                                    $data[] = [
                                        'id' => $value->shift,
                                        'name' => $value->shift_name,
                                        'start_time' => $value->start_time,
                                        'end_time' => $value->end_time
                                    ];
                                }
                            } else {
                                if (strtotime(accumulateTime([date('H:i:s'), $access_in_before])) >= strtotime(date('H:i:s', strtotime($value->start_time))) and strtotime(date('H:i:s')) <= strtotime(date('H:i:s', strtotime($value->end_time)))) {
                                    $data[] = [
                                        'id' => $value->shift,
                                        'name' => $value->shift_name,
                                        'start_time' => $value->start_time,
                                        'end_time' => $value->end_time
                                    ];
                                }
                            }
                        }
                    } else {
                        foreach (json_decode($schedule->day) as $key => $value) {
                            $data[] = [
                                'id' => $value->shift,
                                'name' => $value->shift_name,
                                'start_time' => $value->start_time,
                                'end_time' => $value->end_time
                            ];
                        }
                    }
                }

                if (count($data) > 0) {
                    return response()->json([
                        'type' => 'success',
                        'message' => 'available',
                        'data' => $data
                    ]);
                } else {
                    return response()->json([
                        'type' => 'success',
                        'message' => 'empty',
                        'data' => []
                    ]);
                }
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }
}
