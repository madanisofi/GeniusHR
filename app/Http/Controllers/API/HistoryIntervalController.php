<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceEmployee;
use App\Models\Employee;

class HistoryIntervalController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $emp = Employee::where('user_id', '=', $request->user_id)->first();

        if (!empty($emp)) {

            $attendanceEmployee = AttendanceEmployee::selectRAW('id, clock_in, clock_out, date, status')
                ->where('employee_id', $emp->id)
                ->whereRAW('created_at >= (NOW() - INTERVAL 24 HOUR)')
                ->where('clock_out', '00:00:00')
                ->where('parent_id', null)
                ->orderBy('id', 'DESC')
                ->get();

            if (empty($attendanceEmployee)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => $attendanceEmployee
                ]);
            }

            $my_history = [];

            foreach ($attendanceEmployee as $x => $val) {
                $my_history[] = [
                    'id' => $val->id,
                    'clock_in' => $val->clock_in,
                    'clock_out' => $val->clock_out,
                    'date' => $val->date,
                    'status' => $val->status,
                    'attendance_out' => (!empty($val->permission) ? $val->permission->clock_out : ''),
                ];
            }

            return response()->json([
                'type' => 'success',
                'message' => 'available',
                'data' => [
                    'my_history' => $my_history,
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
