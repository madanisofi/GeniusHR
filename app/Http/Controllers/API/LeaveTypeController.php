<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\Employee;

class LeaveTypeController extends Controller
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
            $leaveType = LeaveType::where('created_by', '=', $creator)->get()->toArray();

            $leave_counts = LeaveType::select(\DB::raw('COALESCE(SUM(leaves.total_leave_days),0) AS total_leave, leave_types.title, leave_types.days,leave_types.id,select_all'))
                ->leftjoin(
                    'leaves',
                    function ($join) use ($employee) {
                        $join->on('leaves.leave_type_id', '=', 'leave_types.id');
                        $join->where('leaves.employee_id', '=', $employee->id);
                        $join->where('leaves.status', '=', 'Approve');
                        $join->whereYear('leaves.applied_on', date('Y'));
                    }
                )->where('leave_types.created_by', '=', $creator)
                ->groupBy('leave_types.id')->get();

            if (empty($leave_counts)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {
                return response()->json([
                    'type' => 'success',
                    'message' => 'available',
                    'data' => $leave_counts
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
