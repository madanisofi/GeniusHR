<?php

namespace App\Http\Controllers\API;

use App\Models\Employee;
use App\Models\OvertimeCompensation;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {
            /**
             * get employee
             */
            $employees = Employee::selectRAW('id, name')->where('is_active', 1)->where('created_by', $user->created_by)->get();

            /**
             * get compensation
             */
            $compensation = OvertimeCompensation::selectRAW('id, name')->where('created_by', $user->created_by)->get();

            return response()->json([
                'type'          => 'success',
                'mesage'        => 'available',
                'employees'     => $employees,
                'compensation'  => $compensation
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }
}
