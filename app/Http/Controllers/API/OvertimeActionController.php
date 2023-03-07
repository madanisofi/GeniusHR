<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\OvertimeAttendance;
use App\Models\Employee;
use App\Models\Utility;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OvertimeActionController extends Controller
{
    public function __invoke(Request $request)
    {
        /**
         * validate input
         */
        $request->validate([
            'user_id'           => 'required',
            'overtime_id'       => 'required',
            'status'            => 'required'
        ]);

        /**
         * check user
         */
        $user = User::find($request->user_id);
        if (!empty($user)) {
            /**
             * data employee
             */
            $emp = Employee::where('user_id', $request->user_id)->first();

            /**
             *  validation
             */
            $overtime = OvertimeAttendance::find($request->overtime_id);
            if (empty($overtime)) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Overtime not found.',
                ]);
            }

            /**
             * load all settings
             */
            $settings = Utility::settings($user->created_by);
            $numberOfApprovals = $settings['attendance_approval'];

            /**
             * process count approved
             */
            $approval = 0;
            $approve = [];
            $overtimeStatus = 'Approve';
            if (count(json_decode($overtime->aggrement)) > 0) {
                $approve = json_decode($overtime->aggrement, true);
                $check_role = searchArrayKeyVal('author', $user->role_id, $approve);

                foreach ($approve as $key => $value) {
                    if ($value['status'] == 'Approve') $approval += 1;
                }

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

            $applicant = $overtime->employee;
            $approve_list = json_encode($approve);

            /**
             * overtime for non employee (HR, Head Of, Other)
             */
            if ($applicant->user->type != 'employee') {
                $overtimeStatus = $request->status != 'Approve' ? 'Reject' : $overtimeStatus;

                // set status overtime
                $overtime->status = $overtimeStatus;
                $overtime->approved_date = date('Y-m-d');
            } else {
                if ($approval >= $numberOfApprovals) {
                    foreach (json_decode($approve_list) as $key => $value) {
                        if ($value->status != 'Approve') $overtimeStatus = 'Reject';
                    }

                    // set status overtime
                    $overtime->status = $overtimeStatus;
                    $overtime->approved_date = date('Y-m-d');
                } else {
                    if (count(json_decode($approve_list)) >= $numberOfApprovals and $approval < $numberOfApprovals) {
                        $overtime->status = 'Reject';
                        $overtime->approved_date = date('Y-m-d');
                    } else {
                        $overtime->status = 'Pending';
                    }
                }
            }

            /**
             * send to firebase notification
             * insert into table notification
             */

            $overtime->aggrement = json_encode($approve);
            $overtime->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Approval Success',
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
