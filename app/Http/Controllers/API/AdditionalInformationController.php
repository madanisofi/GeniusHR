<?php

namespace App\Http\Controllers\API;

use App\Models\AdditionalInformation;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdditionalInformationController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $employee = Employee::where('user_id', '=', $request->user_id)->first();

        if (!empty($employee)) {
            $creator = $employee->created_by;
            $list_additional = AdditionalInformation::where('created_by', $creator)->get();
            $employee_additional = $employee->additional()->pluck('additional_value', 'additional_id');

            if (!empty($list_additional)) {
                $data = [];

                foreach ($list_additional as $key => $value) {
                    $data[] = [
                        'id' => $value->id,
                        'name' => $value->name,
                        'required' => $value->is_required,
                        'can_insert' => $value->can_insert,
                        'value' => (!empty($employee_additional[$value->id]) ? $employee_additional[$value->id] : ''),
                        'type' => $value->type
                    ];
                }

                return response()->json([
                    'type' => 'success',
                    'message' => 'data available.',
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'type' => 'success',
                    'message' => 'data available.',
                    'data' => []
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
