<?php

namespace App\Http\Controllers\API;

use App\Models\OvertimeEmployee;
use App\Models\User;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use App\Models\OvertimeAttendance;
use Illuminate\Http\Request;

class OvertimeCreateController extends Controller
{
    public function __invoke(Request $request)
    {
        /**
         * validate input
         */
        $request->validate([
            'user_id'           => 'required',
            'overtime_date'     => 'required',
            'compensation'      => 'required',
            'notes'             => 'required',
            'employee'          => 'required',
            'latitude'          => 'required',
            'longitude'         => 'required'
        ]);

        /**
         * check user
         */
        $user = User::find($request->user_id);

        if (!empty($user)) {
            $emp = Employee::where('user_id', '=', $request->user_id)->first();

            /**
             * insert into overtime table
             */
            $overtime                   = new OvertimeAttendance();
            $overtime->applicant        = $emp->id;
            $overtime->status           = 'Pending';
            $overtime->date             = date('Y-m-d');
            $overtime->overtime_date    = $request->overtime_date;
            $overtime->aggrement        = json_encode([]);
            $overtime->notes            = $request->notes;
            $overtime->compensation_id  = $request->compensation;
            $overtime->latitude         = $request->latitude;
            $overtime->longitude        = $request->longitude;
            $overtime->created_by       = $user->created_by;
            $overtime->save();

            /**
             * insert into overtime employee
             * detail employee
             */
            $overtime_employee = [];
            foreach ($request->employee as $key => $value) {
                array_push($overtime_employee, [
                    'overtime_id'   => $overtime->id,
                    'employees_id'  => $value,
                    'created_by'    => $user->created_by,
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);
            }
            OvertimeEmployee::insert($overtime_employee);

            /**
             * send notif
             */
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
                if ($x->department_id == $emp->department_id or $x->type == 'hr') {
                    array_push($firebaseToken, $x->fcm_token);
                    array_push($userNotif, $x->id);
                }
            }

            return response()->json([
                'type' => 'success',
                'message' => 'Overtime Submission Successful'
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }
}
