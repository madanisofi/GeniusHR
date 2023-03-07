<?php

namespace App\Http\Controllers\API;

use App\Models\PermissionType;
use App\Models\User;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use App\Models\Utility;
use Illuminate\Http\Request;

class PermissionTypeController extends Controller
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

            $settings = Utility::settings($creator);

            $payroll_date   = $settings['payroll_date'];
            $payroll_time   = $settings['payroll_time'];
            $month_start_date = $settings['month_start_date'];
            $month = date('m');
            $year  = date('Y');

            if ($payroll_time == 'first') {
                $start_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $month_start_date));
                $end_date = date('Y-m-d', strtotime('+1 month', strtotime($year . '-' . $month . '-' . $payroll_date)));
                $end_date = date('Y-m-d', strtotime('-1 days', strtotime($end_date)));
            } else {
                if (date('d') >= $payroll_date) {
                    $start_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $month_start_date));
                    $end_date = date('Y-m-d', strtotime('+1 month', strtotime($year . '-' . $month . '-' . $payroll_date)));
                    $end_date = date('Y-m-d', strtotime('-1 days', strtotime($end_date)));
                } else {
                    $start_date = date('Y-m-d', strtotime('-1 month', strtotime($year . '-' . $month . '-' . $month_start_date)));
                    $end_date = date('Y-m-d', strtotime('-1 days', strtotime($year . '-' . $month . '-' . $payroll_date)));
                }
            }

            $permission_counts = PermissionType::select(\DB::raw('COALESCE(COUNT(attendance_employees.id),0) AS total_permission, permission_types.title, permission_types.days,permission_types.id, permission_types.many_submission'))
                ->leftjoin(
                    'attendance_employees',
                    function ($join) use ($employee, $start_date, $end_date) {
                        $join->on('attendance_employees.permissiontype_id', '=', 'permission_types.id');
                        $join->where('attendance_employees.employee_id', '=', $employee->id);
                        $join->where('attendance_employees.date', '>=', $start_date);
                        $join->where('attendance_employees.date', '<=', $end_date);
                        $join->where('attendance_employees.status', '=', 'Present');
                    }
                )->where('permission_types.created_by', '=', $creator)
                ->groupBy('permission_types.id')->get();

            if (empty($permission_counts)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {
                return response()->json([
                    'type' => 'success',
                    'message' => 'available',
                    'data' => $permission_counts
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
