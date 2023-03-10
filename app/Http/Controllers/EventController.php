<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Event;
use App\Models\EventEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utility;
use App\Imports\EventImport;
use App\Exports\EventExport;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class EventController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Event')) {
            $employees = Employee::where('created_by', '=', Auth::user()->creatorId())->get();
            $events    = Event::where('created_by', '=', Auth::user()->creatorId())->get();

            $today_date = date('m');
            $current_month_event = Event::select('id', 'start_date', 'end_date', 'title', 'created_at', 'color')->whereMonth('start_date', '=', $today_date)->where('created_by', '=', Auth::user()->creatorId())->get();

            $arrEvents = [];
            foreach ($events as $event) {

                $arr['id']    = $event['id'];
                $arr['title'] = $event['title'];
                $arr['start'] = $event['start_date'];
                $arr['end']   = $event['end_date'];
                //                $arr['allDay']    = !0;
                //                $arr['className'] = 'bg-danger';
                $arr['className'] = $event['color'];

                // $arr['borderColor']     = "#fff";
                // $arr['textColor']       = "white";
                $arr['url']             = route('event.edit', $event['id']);

                $arrEvents[] = $arr;
            }
            // $arrEvents = str_replace('"[', '[', str_replace(']"', ']', json_encode($arrEvents)));
            $arrEvents =  json_encode($arrEvents);

            return view('event.index', compact('arrEvents', 'employees', 'current_month_event', 'events'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Event')) {
            $employees   = Employee::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branch      = Branch::where('created_by', '=', Auth::user()->creatorId())->get();
            $departments = Department::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('event.create', compact('employees', 'branch', 'departments'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {

        if (Auth::user()->can('Create Event')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'branch_id' => 'required',
                    'department_id' => 'required',
                    'employee_id' => 'required',
                    'title' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'color' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $event                = new Event();
            $event->branch_id     = $request->branch_id;
            $event->department_id = json_encode($request->department_id);
            $event->employee_id   = json_encode($request->employee_id);
            $event->title         = $request->title;
            $event->start_date    = $request->start_date;
            $event->end_date      = $request->end_date;
            $event->color         = $request->color;
            $event->description   = $request->description;
            $event->created_by    = Auth::user()->creatorId();
            $event->save();

            //  slack
            $setting = Utility::settings(Auth::user()->creatorId());
            $branch = Branch::find($request->branch_id);
            if (isset($setting['event_notification']) && $setting['event_notification'] == 1) {
                $msg = $request->title . ' ' . __("for branch") . ' ' . $branch->name . ' ' . ("from") . ' ' . $request->start_date . ' ' . __("to") . ' ' . $request->end_date . '.';
                Utility::send_slack_msg($msg);
            }

            //telegram
            $setting = Utility::settings(Auth::user()->creatorId());
            $branch = Branch::find($request->branch_id);
            if (isset($setting['telegram_ticket_notification']) && $setting['telegram_ticket_notification'] == 1) {
                $msg = $request->title . ' ' . __("for branch") . ' ' . $branch->name . ' ' . ("from") . ' ' . $request->start_date . ' ' . __("to") . ' ' . $request->end_date . '.';
                Utility::send_telegram_msg($msg);
            }


            //twilio
            $setting = Utility::settings(Auth::user()->creatorId());
            $branch = Branch::find($request->branch_id);
            $employee = Employee::where('branch_id', $request->branch_id)->first();

            if (isset($setting['twilio_event_notification']) && $setting['twilio_event_notification'] == 1) {
                $employeess = Employee::whereIn('branch_id', $request->employee_id)->get();
                foreach ($employeess as $key => $employee) {
                    $msg = $request->title . ' ' . __("for branch") . ' ' . $branch->name . ' ' . ("from") . ' ' . $request->start_date . ' ' . __("to") . ' ' . $request->end_date . '.';
                    Utility::send_twilio_msg($employee->phone, $msg);
                }
            }



            if (in_array('0', $request->employee_id)) {
                $departmentEmployee = Employee::whereIn('department_id', $request->department_id)->get()->pluck('id');
                $departmentEmployee = $departmentEmployee;
            } else {
                $departmentEmployee = $request->employee_id;
            }
            foreach ($departmentEmployee as $employee) {
                $eventEmployee              = new EventEmployee();
                $eventEmployee->event_id    = $event->id;
                $eventEmployee->employee_id = $employee;
                $eventEmployee->created_by  = Auth::user()->creatorId();
                $eventEmployee->save();
            }

            return redirect()->route('event.index')->with('success', __('Event  successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('event.index');
    }

    public function edit($event)
    {

        if (Auth::user()->can('Edit Event')) {
            $event = Event::find($event);
            if ($event->created_by == Auth::user()->creatorId()) {
                $employees = Employee::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');

                return view('event.edit', compact('event', 'employees'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Event $event)
    {
        if (Auth::user()->can('Edit Event')) {
            if ($event->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'title' => 'required',
                        'start_date' => 'required',
                        'end_date' => 'required',
                        'color' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }

                $event->title       = $request->title;
                $event->start_date  = $request->start_date;
                $event->end_date    = $request->end_date;
                $event->color       = $request->color;
                $event->description = $request->description;
                $event->save();

                return redirect()->route('event.index')->with('success', __('Event successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Event $event)
    {
        if (Auth::user()->can('Delete Event')) {
            if ($event->created_by == Auth::user()->creatorId()) {
                $event->delete();

                return redirect()->route('event.index')->with('success', __('Event successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function export()
    {
        $name = 'event' . date('Y-m-d i:h:s');
        $data = Excel::download(new EventExport(), $name . '.xlsx');
        ob_end_clean();

        return $data;
    }

    public function importFile()
    {
        return view('event.import');
    }

    public function import(Request $request)
    {
        $rules = [
            'file' => 'required|mimes:csv,txt',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return back()->with('error', $messages->first());
        }

        $events = (new EventImport())->toArray(request()->file('file'))[0];
        $totalEvents = count($events) - 1;
        $errorArray    = [];

        for ($i = 1; $i <= count($events) - 1; $i++) {

            $event = $events[$i];
            $eventsByTitle = Event::where('title', $event[2])->first();

            if (!empty($eventsByTitle)) {
                $eventData = $eventsByTitle;
            } else {
                $eventData = new Event();
            }

            $eventData->branch_id           = $event[0];
            $eventData->department_id       = $event[1];
            $eventData->employee_id         = '["0"]';
            $eventData->title               = $event[2];
            $eventData->start_date          = $event[3];
            $eventData->end_date            = $event[4];
            $eventData->color               = $event[5];
            $eventData->description         = $event[6];
            $eventData->created_by          = $event[7];

            if (empty($eventData)) {
                $errorArray[] = $eventData;
            } else {
                $eventData->save();
            }
        }

        $errorRecord = [];
        if (empty($errorArray)) {
            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        } else {
            $data['status'] = 'error';
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalEvents . ' ' . 'record');


            foreach ($errorArray as $errorData) {

                $errorRecord[] = implode(',', $errorData);
            }

            \Session::put('errorArray', $errorRecord);
        }

        return back()->with($data['status'], $data['msg']);
    }

    public function getdepartment(Request $request)
    {

        if ($request->branch_id == 0) {
            $departments = Department::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id')->toArray();
        } else {
            $departments = Department::where('created_by', '=', Auth::user()->creatorId())->where('branch_id', $request->branch_id)->get()->pluck('name', 'id')->toArray();
        }

        return response()->json($departments);
    }

    public function getemployee(Request $request)
    {
        if (in_array('0', $request->department_id)) {
            $employees = Employee::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id')->toArray();
        } else {
            $employees = Employee::where('created_by', '=', Auth::user()->creatorId())->whereIn('department_id', $request->department_id)->get()->pluck('name', 'id')->toArray();
        }

        return response()->json($employees);
    }
}
