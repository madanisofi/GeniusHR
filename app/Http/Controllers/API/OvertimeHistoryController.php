<?php

namespace App\Http\Controllers\API;

use App\Models\OvertimeAttendance;
use App\Models\Employee;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OvertimeHistoryController extends Controller
{
    public function __invoke(Request $request)
    {
        /**
         * validate input
         */
        $request->validate([
            'user_id'           => 'required'
        ]);

        /**
         * check user
         */
        $user = User::find($request->user_id);
        if (!empty($user)) {
            $emp = Employee::where('user_id', '=', $request->user_id)->first();

            /**
             * get History
             */
            if ($user->type == 'employee') {
                $historyOvertime = OvertimeAttendance::where('applicant', $emp->id)->orderBy('overtime_date', 'desc')->get();
            } else {
                if (empty($user->role->level)) {
                    $employeeList = Employee::where('created_by', $user->created_by)->get()->pluck('id');
                } else {
                    $employeeList = Employee::where('department_id', $emp->department_id)->where('created_by', $user->created_by)->get()->pluck('id');
                }
                $historyOvertime = OvertimeAttendance::whereIN('applicant', $employeeList)->orderBy('overtime_date', 'desc')->get();
            }

            /**
             * build array
             */
            $assets = asset(url('uploads/overtime/'));
            $dataOvertime = [];
            foreach ($historyOvertime as $key => $val) {
                $dataOvertime[] = [
                    'id'                => $val->id,
                    'applicant'         => $val->applicant,
                    'applicant_name'    => $val->employee->name,
                    'status'            => $val->status,
                    'start_time'        => $val->start_time != null ? date('H:i:s', strtotime($val->start_time)) : '00:00:00',
                    'end_time'          => $val->end_time != null ? date('H:i:s', strtotime($val->end_time)) : '00:00:00',
                    'date'              => $val->date,
                    'overtime_date'     => $val->overtime_date,
                    'notes'             => $val->notes,
                    'aggrement'         => json_decode($val->aggrement, true),
                    'approved_date'     => $val->approved_date == null ? '' : $val->approved_date,
                    'compensation'      => isset($val->compensation) ? $val->compensation->name : '',
                    'employee'          => Employee::selectRAW('name')->whereIN('id', $val->allemployee()->pluck('employees_id'))->get()->toArray(),
                    'picture_in'        => $val->picture_in != null ? $assets . '/' . $val->picture_in : '',
                    'picture_out'       => $val->picture_out != null ? $assets . '/' . $val->picture_out : '',
                ];
            }

            /**
             * split array to my overtime & approved
             */
            $active = [];
            $history = [];
            $approved = [];
            foreach ($dataOvertime as $val) {
                if ($val['applicant'] == $emp->id) {
                    if ($val['start_time'] != '00:00:00' and $val['end_time'] != '00:00:00') {
                        $history[] = $val;
                    } else {
                        $active[] = $val;
                    }
                } else {
                    $searchKey = searchArrayKeyVal('author', $user->role_id, $val['aggrement']);
                    if (strtotime(date('Y-m-d')) <= strtotime($val['overtime_date']) && $searchKey === false) {
                        $approved[] = $val;
                    }
                }
            }

            /**
             * output
             */
            return response()->json([
                'type' => 'success',
                'message' => 'available',
                'data' => [
                    'role' => $user->role->name,
                    'active' => $active,
                    'history' => $history,
                    'need_approve' => $approved,
                ]
            ]);
        } else {
            /**
             * error
             */
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }
}
