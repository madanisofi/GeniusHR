<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationDetailController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'notification_id' => 'required'
        ]);

        $detailNotification = Notification::find($request->notification_id);

        if (empty($detailNotification)) {
            return response()->json([
                'type' => 'error',
                'message' => 'notification not found',
                'data' => []
            ]);
        }

        return response()->json([
            'type' => 'success',
            'message' => 'notification available',
            'data' => [
                'id'    => $detailNotification->id,
                'title' => $detailNotification->title,
                'type' => $detailNotification->type,
                'messages' => $detailNotification->messages,
                'details' => ($detailNotification->details != null ? $detailNotification->details : ''),
                'date' => date('Y-m-d', strtotime($detailNotification->created_at)),
                'full_date' => $detailNotification->created_at
            ]
        ]);
    }
}
