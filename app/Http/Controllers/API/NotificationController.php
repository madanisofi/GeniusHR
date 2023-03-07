<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $emp = Employee::where('user_id', '=', $request->user_id)->first();

        if (!empty($emp)) {
            $creator = $emp->created_by;
            try {
                $notification    = Notification::orderBy('notifications.id', 'desc')->leftjoin('notification_employees', 'notifications.id', '=', 'notification_employees.notification_id')->where('notification_employees.user_id', '=', $request->user_id)->orWhere(
                    function ($q) use ($creator) {
                        $q->where('notifications.users', '["0"]')->where('notifications.created_by', $creator);
                    }
                )->get();

                if (empty($notification)) {
                    return response()->json([
                        'type' => 'success',
                        'message' => 'empty',
                        'data' => []
                    ]);
                }

                $data = [];
                foreach ($notification as $key => $value) {
                    $data[] = [
                        'id'    => $value->notification_id,
                        'title' => $value->title,
                        'type' => $value->type,
                        'messages' => $value->messages,
                        'details' => ($value->details != null ? $value->details : ''),
                        'date' => date('Y-m-d', strtotime($value->created_at)),
                        'full_date' => $value->created_at
                    ];
                }

                return response()->json([
                    'type' => 'success',
                    'message' => 'available',
                    'data' => $data
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'type' => 'error',
                    'error' => $e->getMessage(),
                    'message' => 'server error'
                ], 500);
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }
}
