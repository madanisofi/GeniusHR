<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;

class LeaveDetailController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'leave_id' => 'required'
        ]);

        $leave_detail = Leave::find($request->leave_id);

        if (empty($leave_detail)) {
            return response()->json([
                'type' => 'error',
                'message' => 'leave not found',
                'data' => []
            ]);
        } else {
            $data = [
                'id' => $leave_detail->id,
                'employee_id' => $leave_detail->employee_id,
                'employee' => $leave_detail->employees->name,
                'leave_type_id' => $leave_detail->leave_type_id,
                'leave_type' => $leave_detail->leaveType->title,
                'applied_on' => $leave_detail->applied_on,
                'start_date' => $leave_detail->start_date,
                'end_date' => $leave_detail->end_date,
                'total_leave_days' => $leave_detail->total_leave_days,
                'leave_reason' => $leave_detail->leave_reason,
                'remark' => $leave_detail->remark,
                'status' => $leave_detail->status,
                'addresed_to' => $leave_detail->addresed_to,
                'acc' => json_decode($leave_detail->acc),
                'created_by' => $leave_detail->created_by,
            ];

            return response()->json([
                'type' => 'success',
                'message' => 'leave available',
                'data' => $data
            ]);
        }
    }
}
