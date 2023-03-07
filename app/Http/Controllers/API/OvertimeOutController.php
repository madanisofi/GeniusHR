<?php

namespace App\Http\Controllers\API;

use App\Models\OvertimeAttendance;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OvertimeOutController extends Controller
{
    public function __invoke(Request $request)
    {
        /**
         * validate input
         */
        $request->validate([
            'user_id'           => 'required',
            'overtime_id'       => 'required',
            'images'             => 'required'
        ]);

        /**
         * check user
         */
        $user = User::find($request->user_id);
        if (!empty($user)) {

            /**
             * validation time, date, and id
             */
            $validation = OvertimeAttendance::where('id', $request->overtime_id)->first();
            if (empty($validation)) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Overtime schedule not available.',
                ]);
            }

            if ($validation->end_time != null or $validation->overtime_date != date('Y-m-d')) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Overtime schedule not available.',
                ]);
            }

            /**
             * count duration
             */
            $duration = overtimeDuration($validation->start_time, date('Y-m-d H:i:s'));

            /**
             * save image
             */
            $fileNameToStore = '';
            if ($request->hasFile('images')) {
                $filenameWithExt = $request->file('images')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('images')->getClientOriginalExtension();
                // hash file before store
                $filename = md5($filename . time());
                $fileNameToStore = $filename . '_logout_' . time() . '.' . $extension;
                $dir             = storage_path('uploads/overtime/');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $path = $request->file('images')->storeAs('uploads/overtime/', $fileNameToStore);
            }

            /**
             * start overtime
             */
            $overtime                   = OvertimeAttendance::find($request->overtime_id);
            $overtime->end_time         = date('Y-m-d H:i:s');
            $overtime->duration         = $duration;
            $overtime->picture_out      = $fileNameToStore;
            $overtime->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Employee Successfully Overtime Out.',
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
