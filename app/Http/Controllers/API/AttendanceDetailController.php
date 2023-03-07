<?php

namespace App\Http\Controllers\API;

use App\Models\AttendanceEmployee;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttendanceDetailController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'attendance_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        $attendance = AttendanceEmployee::find($request->attendance_id);

        if (empty($attendance)) {
            return response()->json([
                'type' => 'error',
                'message' => 'attendance not found',
            ]);
        }

        $picture = asset(url('uploads/attendance/'));

        $data = [
            'id' => $attendance->id,
            'employee_id' => $attendance->employee_id,
            'employee' => $attendance->employee->name,
            'date' => date('d-M-Y', strtotime($attendance->date)),
            'end_date' => ($attendance->end_date != null ? date('d-M-Y', strtotime($attendance->end_date)) : ''),
            'status' => $attendance->status,
            'approve' => json_decode($attendance->approve),
            'notes' => ($attendance->notes != null ? $attendance->notes : ''),
            'clock_in' => $attendance->clock_in,
            'clock_out' => $attendance->clock_out,
            'late' => $attendance->late,
            'shift' => ($attendance->shift != null ? $attendance->shift->name : ''),
            'working_hours' => ($attendance->working_hours != null ? $attendance->working_hours : ''),
            'working_late' => ($attendance->working_late != null ? $attendance->working_late : ''),
            'salary_cuts' => $attendance->salary_cuts,
            'images' => ($attendance->images != null ? asset(url('uploads/attendance/' . $attendance->images))  : ''),
            'images_out' => ($attendance->images_out != null ? asset(url('uploads/attendance/' . $attendance->images_out))  : ''),
            'permission' => (!empty($attendance->permission) ? $attendance->permission->title : ''),
            'attendance_out' => (!empty($attendance->permission) ? $attendance->permission->clock_out : ''),
            'reason' => ($attendance->reason != null ? $attendance->reason : '')
        ];

        return response()->json([
            'type' => 'success',
            'message' => 'attendance found',
            'data' => $data
        ]);
    }
}
