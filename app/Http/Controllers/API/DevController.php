<?php

namespace App\Http\Controllers\API;

use App\Models\Employee;
use App\Models\AttendanceEmployee;
use App\Models\User;
use App\Models\Utility;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DevController extends Controller
{
    public function getEmployee(Request $request)
    {
        $request->validate([
            'company' => 'required'
        ]);

        $companyCheck = User::where('email', $request->company)->first();

        if (!$companyCheck) {
            return response()->json([
                'type' => 'error',
                'message' => 'company not found'
            ]);
        }

        $getUser = Employee::where('created_by', '=', $companyCheck->id)->get()->pluck('id');

        return response()->json([
            'type' => 'success',
            'message' => 'available',
            'data' => $getUser
        ]);
    }

    public function delAttendance(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'company' => 'required'
        ]);

        $companyCheck = User::where('email', $request->company)->first();

        if (!$companyCheck) {
            return response()->json([
                'type' => 'error',
                'message' => 'company not found'
            ]);
        }

        $getUser = Employee::where('created_by', '=', $companyCheck->id)->get()->pluck('id');

        // show attendance
        $show = AttendanceEmployee::whereIn('employee_id', $getUser);
        $show->whereBetween(
            'date',
            [
                $request->start_date,
                $request->end_date,
            ]
        );
        $show = $show->orderBy('id', 'DESC')->delete();

        return 'ok';
    }

    public function insAttendance(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'company' => 'required'
        ]);

        $companyCheck = User::where('email', $request->company)->first();

        if (!$companyCheck) {
            return response()->json([
                'type' => 'error',
                'message' => 'company not found'
            ]);
        }

        $x1 = date_create($request->start_date);
        $x2 = date_create($request->end_date);
        $diff  = date_diff($x1, $x2);

        $settings = Utility::settings($companyCheck->id);
        $working_days = json_decode($settings['working_days']);

        $getUser = Employee::where('created_by', '=', $companyCheck->id)->get()->pluck('id');

        foreach ($getUser as $value) {
            $data = [];
            for ($i = 0; $i <= $diff->d; $i++) {
                $date = date('Y-m-d', strtotime('+' . ($i) . ' days', strtotime($request->start_date)));

                if (in_array(date('N', strtotime($date)), $working_days)) {
                    $data[] = [
                        'employee_id'       => $value,
                        'date'              => $date,
                        'status'            => 'Present',
                        'approve'           => json_encode([]),
                        'clock_in'          => '08:00:00',
                        'clock_out'         => '17:00:00',
                        'late'              => '00:00:00',
                        'early_leaving'     => '00:00:00',
                        'overtime'          => '00:00:00',
                        'total_rest'        => '00:00:00',
                        'working_hours'     => '08:00:00',
                        'working_late'      => '00:00:00',
                        'created_by'        => $value,
                        'created_at'        => date("Y-m-d H:i:s"),
                        'updated_at'        => date("Y-m-d H:i:s")
                    ];
                }
            }
            AttendanceEmployee::insert($data);
        }

        return 'ok';
    }
}
