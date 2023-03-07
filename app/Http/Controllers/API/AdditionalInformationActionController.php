<?php

namespace App\Http\Controllers\API;

use App\Models\Employee;
use App\Models\EmployeeAdditionalInformation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdditionalInformationActionController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $employee = Employee::where('user_id', '=', $request->user_id)->first();

        if (!empty($employee)) {
            $creator = $employee->created_by;

            if ($request->additional && $request->additional != '') {
                foreach ($request->additional as $key => $additional) {
                    $employee_additional_check = EmployeeAdditionalInformation::where('employee_id', $employee->employee_id)->where('additional_id', $key)->first();
                    if (!empty($employee_additional_check)) {
                        $employee_additional_check->additional_value = $request['additional'][$key];
                        $employee_additional_check->save();
                    } else {
                        if ($request['additional'][$key] != '') {

                            $employee_additional = EmployeeAdditionalInformation::create(
                                [
                                    'employee_id' => $employee['id'],
                                    'additional_id' => $key,
                                    'additional_value' => $request['additional'][$key],
                                    'created_by' => Auth::user()->creatorId(),
                                ]
                            );
                            $employee_additional->save();
                        }
                    }
                }

                return response()->json([
                    'type' => 'success',
                    'message' => 'Upload Success.'
                ]);
            } else {
                return response()->json([
                    'type' => 'success',
                    'message' => 'No Changes.'
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
