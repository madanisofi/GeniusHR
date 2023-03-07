<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $emp = Employee::where('user_id', '=', $request->user_id)->first();

        if (!empty($emp)) {
            $detailEmployee = Employee::selectRAW('employees.id, user_id, employees.name, dob, gender, phone, address, email, employees.employee_id, b.name as branch_name, d.name as department_name, ds.name as designation_name, company_doj')
                ->join('branches as b', 'employees.branch_id', '=', 'b.id')
                ->join('departments as d', 'employees.department_id', '=', 'd.id')
                ->join('designations as ds', 'employees.designation_id', '=', 'ds.id')
                ->where('employees.id', $emp->id)
                ->get();

            return response()->json(compact('detailEmployee'));
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }
}
