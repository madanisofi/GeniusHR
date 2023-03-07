<?php

namespace App\Http\Controllers\API;

use App\Models\OvertimeAttendance;
use App\Models\User;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DetailOvertimeController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'overtime_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {
            /**
             * get overtime
             */
            $get_overtime = OvertimeAttendance::find($request->overtime_id);

            if (!isset($get_overtime)) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'data not found.'
                ]);
            }

            $assets = asset(url('uploads/overtime/'));
            $detailOvertime = [
                'id'                => $get_overtime->id,
                'applicant'         => $get_overtime->applicant,
                'applicant_name'    => $get_overtime->employee->name,
                'status'            => $get_overtime->status,
                'start_time'        => $get_overtime->start_time != null ? date('H:i:s', strtotime($get_overtime->start_time)) : '00:00:00',
                'end_time'          => $get_overtime->end_time != null ? date('H:i:s', strtotime($get_overtime->end_time)) : '00:00:00',
                'date'              => $get_overtime->date,
                'overtime_date'     => $get_overtime->overtime_date,
                'notes'             => $get_overtime->notes,
                'aggrement'         => json_decode($get_overtime->aggrement, true),
                'approved_date'     => $get_overtime->approved_date == null ? '' : $get_overtime->approved_date,
                'compensation'      => isset($get_overtime->compensation) ? $get_overtime->compensation->name : '',
                'employee'          => Employee::selectRAW('name')->whereIN('id', $get_overtime->allemployee()->pluck('employees_id'))->get()->toArray(),
                'picture_in'        => $get_overtime->picture_in != null ? $assets . '/' . $get_overtime->picture_in : '',
                'picture_out'       => $get_overtime->picture_out != null ? $assets . '/' . $get_overtime->picture_out : '',
            ];

            return response()->json([
                'type'          => 'success',
                'mesage'        => 'available',
                'data'          => $detailOvertime
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }
}
