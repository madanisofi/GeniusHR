<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Employee;

class EventsController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $emp = Employee::where('user_id', '=', $request->user_id)->first();

        if (!empty($emp)) {
            $creator = $emp->created_by;
            $events    = Event::leftjoin('event_employees', 'events.id', '=', 'event_employees.event_id')->where('event_employees.employee_id', '=', $emp->id)->orWhere(
                function ($q) use ($creator) {
                    $q->where('events.department_id', '["0"]')->where('events.employee_id', '["0"]')->where('events.created_by', $creator);
                }
            )->get()->toArray();

            if (empty($events)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            }

            $arrEvents = [];
            foreach ($events as $event) {

                $arr['id']              = $event['id'];
                $arr['title']           = $event['title'];
                $arr['start']           = $event['start_date'];
                $arr['end']             = $event['end_date'];
                $arr['backgroundColor'] = $event['color'];
                $arr['borderColor']     = "#fff";
                $arr['textColor']       = "white";
                $arrEvents[]            = $arr;
            }

            return response()->json([
                'type' => 'success',
                'message' => 'available',
                'data' => $arrEvents
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }
}
