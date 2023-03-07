<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\Employee;
use App\Models\RoomType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id);
        $employee = Employee::where('user_id', '=', $request->user_id)->first();
        if (!empty($user)) {

            $creator = $user->created_by;

            $this_month = date('Y-m');

            $schedule = Schedule::selectRaw('schedules.id as id, schedules.shift_id, schedules.room_id, day_on_month, month, schedules.repeat, employees.name as employee, day, schedules.created_by as created_by')
                ->join('employees', 'schedules.employee_id', '=', 'employees.id')
                ->where('schedules.employee_id', $employee->id)
                ->where('schedules.created_by', '=', $creator)
                ->whereIn('schedules.month', array('', $this_month))
                ->orderBy('schedules.updated_at', 'DESC')->first();

            if (empty($schedule)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => (object)[]
                ]);
            } else {

                $schedule_list = [];
                if ($schedule->day != null and $schedule->repeat != 'on') {
                    foreach (json_decode($schedule->day) as $x => $val) {
                        $schedule_list[] = [
                            'date' => date('d-M-Y', strtotime($val->date)),
                            'room' => $val->room,
                            'room_name' => $val->room_name,
                            'shift' => $val->shift,
                            'shift' => $val->shift,
                            'shift_name' => $val->shift_name,
                            'start_time' => $val->start_time,
                            'end_time' => $val->end_time,
                        ];
                    }
                }

                if ($schedule->day != null and $schedule->repeat == 'on') {
                    foreach (json_decode($schedule->day) as $value) {
                        $schedule_list[] = [
                            'date' => 'Hari Kerja',
                            'room' => ($schedule->room_id != null ? $schedule->room_id : ''),
                            'room_name' => ($schedule->room_id != null ? $schedule->room->name : ''),
                            'shift' => [$value->shift],
                            'shift_name' => [$value->shift_name],
                            'start_time' => [$value->start_time],
                            'end_time' => [$value->end_time],
                        ];
                    }
                }


                $data = [
                    'id' => $schedule->id,
                    'month' => $schedule->month,
                    'repeat' => ($schedule->repeat != null ? $schedule->repeat : ''),
                    'shift' => ($schedule->shift_id != null ? Shift::find($schedule->shift_id)->name : ''),
                    'start_time' => ($schedule->shift_id != null ? Shift::find($schedule->shift_id)->start_time : ''),
                    'end_time' => ($schedule->shift_id != null ? Shift::find($schedule->shift_id)->end_time : ''),
                    'room' => ($schedule->room_id != null ? RoomType::find($schedule->room_id)->name : ''),
                    'schedule' => ($schedule->day != null ? $schedule_list : [])
                ];

                return response()->json([
                    'type' => 'success',
                    'message' => 'available',
                    'data' => $data
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
