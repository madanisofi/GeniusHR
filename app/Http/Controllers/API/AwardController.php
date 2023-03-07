<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Award;

class AwardController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $emp    = Employee::where('user_id', '=', $request->user_id)->first();

        if (!empty($emp)) {
            $awards = Award::selectRAW('awards.id, employee_id, award_type, name as award_type_name, date, gift, description')
                ->join('award_types', 'awards.award_type', '=', 'award_types.id')
                ->where('employee_id', '=', $emp->id)
                ->orderBy('awards.id', 'DESC')->get()->toArray();

            if (empty($awards)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {
                return response()->json([
                    'type' => 'success',
                    'message' => 'available',
                    'data' => $awards
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
