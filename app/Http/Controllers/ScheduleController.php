<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementEmployee;
use App\Models\ScheduleEmployee;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\RoomType;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\FacadesAuth;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Schedule')) {

            $user = Auth::user(); // get access
            $level = Auth::user()->getLevel($user->type);

            if (Auth::user()->type == 'employee') {
                $current_employee = Employee::where('user_id', '=', $user->id)->first();

                $schedules    = Schedule::selectRaw('schedules.id as id, schedules.month as month, schedules.repeat, e.name as employee, schedules.employee_id as emp_id, day')
                    ->join('employees as e', 'schedules.employee_id', '=', 'e.id')
                    ->where('schedules.employee_id', '=', $current_employee->id)
                    ->where('schedules.created_by', '=', $user->creatorId())
                    ->orderBy('e.name', 'ASC')->get();

                $schedulesEmp = ScheduleEmployee::where('employee_id', '=', $current_employee->id)
                    ->where('created_by', '=', $user->creatorId())->get();
            } else {
                if ($user->type == 'company' or $level == null) {
                    $current_employee = Employee::where('user_id', '=', $user->id)->first();

                    $schedules1    = Schedule::selectRaw('schedules.id as id, schedules.month as month, schedules.repeat, e.name as employee, schedules.employee_id as emp_id, day')
                        ->join('employees as e', 'schedules.employee_id', '=', 'e.id')
                        ->where('schedules.created_by', '=', $user->creatorId())
                        ->where('schedules.month', '>=', date('Y-m'))
                        ->orderBy('e.name', 'ASC');

                    $schedules    = Schedule::selectRaw('schedules.id as id, schedules.month as month, schedules.repeat, e.name as employee, schedules.employee_id as emp_id, day')
                        ->join('employees as e', 'schedules.employee_id', '=', 'e.id')
                        ->where('schedules.created_by', '=', $user->creatorId())
                        ->where('schedules.repeat', '=', 'on')
                        ->orderBy('e.name', 'ASC')->union($schedules1)->get();

                    $schedulesEmp = ScheduleEmployee::where('created_by', '=', $user->creatorId())->get();
                } else {
                    $current_employee = Employee::where('user_id', '=', $user->id)->first();

                    $schedules1    = Schedule::selectRaw('schedules.id as id, schedules.month as month, schedules.repeat, e.name as employee, schedules.employee_id as emp_id, day, dp.name as department, d.name as designation')
                        ->join('employees as e', 'schedules.employee_id', '=', 'e.id')
                        ->join('departments as dp', 'e.department_id', '=', 'dp.id')
                        ->join('designations as d', 'e.designation_id', '=', 'd.id')
                        ->where('schedules.created_by', '=', $user->creatorId())
                        ->where('e.department_id', '=', $current_employee->department_id)
                        ->where('schedules.month', '>=', date('Y-m'))
                        ->orderBy('e.name', 'ASC');

                    $schedules    = Schedule::selectRaw('schedules.id as id, schedules.month as month, schedules.repeat, e.name as employee, schedules.employee_id as emp_id, day, dp.name as department, d.name as designation')
                        ->join('employees as e', 'schedules.employee_id', '=', 'e.id')
                        ->join('departments as dp', 'e.department_id', '=', 'dp.id')
                        ->join('designations as d', 'e.designation_id', '=', 'd.id')
                        ->where('schedules.created_by', '=', $user->creatorId())
                        ->where('e.department_id', '=', $current_employee->department_id)
                        ->where('schedules.month', '=', 'on')
                        ->orderBy('e.name', 'ASC')->union($schedules1)->get();

                    $schedulesEmp = ScheduleEmployee::where('created_by', '=', $user->creatorId())->get();
                }
            }

            $test = ["1", "2"];

            $data = [];

            // akses schedule for head of
            $settings = Utility::settings();
            $get_access = 'close';
            if ($settings['access_restrictions'] == 'on') {
                $day_of_access = (int)$settings['day_of_access'];
                $this_day = date('Y-m-d');
                $last_day_in_month = date('Y-m-t');
                $difference_day = date('Y-m-d', strtotime('-' . $day_of_access . ' days', strtotime($last_day_in_month)));

                if ($this_day >= $difference_day) {
                    $get_access = 'open';
                }
            }

            $month = [
                '01' => 'JAN',
                '02' => 'FEB',
                '03' => 'MAR',
                '04' => 'APR',
                '05' => 'MAY',
                '06' => 'JUN',
                '07' => 'JUL',
                '08' => 'AUG',
                '09' => 'SEP',
                '10' => 'OCT',
                '11' => 'NOV',
                '12' => 'DEC',
            ];

            $year = [
                '2022' => '2022',
                '2023' => '2023',
                '2024' => '2024',
                '2025' => '2025',
                '2026' => '2026',
                '2027' => '2027',
                '2028' => '2028',
                '2029' => '2029',
                '2030' => '2030',
            ];

            return view('schedule.index_new', compact('schedules', 'current_employee', 'test', 'data', 'get_access', 'level', 'month', 'year'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Schedule')) {

            $employees = Employee::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employees->prepend('All', 0);
            $branch      = Branch::where('created_by', '=', Auth::user()->creatorId())->get();
            $departments = Department::where('created_by', '=', Auth::user()->creatorId())->get();
            $shift      = Shift::where('created_by', '=', Auth::user()->creatorId())->get();
            $roomtype = RoomType::where('created_by', '=', Auth::user()->creatorId())->get();
            $day_on_this_month = date('t');
            $month = date('Y-m');

            return view('schedule.create_new2', compact('employees', 'branch', 'departments', 'shift', 'roomtype', 'day_on_this_month', 'month'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function create_schedule(Request $request)
    {
        if (Auth::user()->can('Create Schedule')) {

            $employees = Employee::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employees->prepend('All', 0);
            $branch      = Branch::where('created_by', '=', Auth::user()->creatorId())->get();
            $departments = Department::where('created_by', '=', Auth::user()->creatorId())->get();
            $shift      = Shift::where('created_by', '=', Auth::user()->creatorId())->get();
            $roomtype = RoomType::where('created_by', '=', Auth::user()->creatorId())->get();
            $day_on_this_month = date('t', strtotime($request->year . '-' . $request->month));
            $month = date('Y-m', strtotime($request->year . '-' . $request->month));

            return view('schedule.create_new2', compact('employees', 'branch', 'departments', 'shift', 'roomtype', 'day_on_this_month', 'month'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Schedule')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $checkSchedule = Schedule::where('employee_id', $request->employee_id)->where('month', '=', $request->month)->first();

            if ($checkSchedule) {
                return back()->with('error', __('Schedule Available.'));
            }

            $schedule                   = new Schedule();

            if ($request->employee_id != 0) {
                $schedule->employee_id      = $request->employee_id;
            } else {
                if ($request->department_id[0] != 0) {
                    $employees = Employee::whereIn('department_id', $request->department_id)->where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
                } else {
                    $employees = Employee::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
                }
            }

            if ($request->repeat == 'on') {
                #code
                $schedule->room_id      = $request->room;
                $schedule->month        = '';

                $shift_schedule = [];
                foreach ($request->shift as $val) {
                    $shift_data = Shift::find($val);
                    $shift_schedule[] = [
                        'shift' => $val,
                        'shift_name' => $shift_data->name,
                        'start_time' => $shift_data->start_time,
                        'end_time' => $shift_data->end_time
                    ];
                }

                $schedule->shift_id     = null;
                $schedule->day          = json_encode($shift_schedule);
            } else {
                #code
                $shift_schedule = [];

                for ($i = 1; $i <= $request->day_on_month; $i++) {
                    # code...
                    $shift = [];
                    $shift_name = [];
                    $start_time = [];
                    $end_time = [];
                    if (isset($_POST[$i . 'shift'])) {
                        foreach ($_POST[$i . 'shift'] as $key => $value) {
                            # code...

                            $id_shift = $_POST[$i . 'shift'][$key];
                            $shift[] = $_POST[$i . 'shift'][$key];

                            $shift_data = Shift::where('id', $id_shift)->first();

                            $shift_name[] = $shift_data->name;
                            $start_time[] = $shift_data->start_time;
                            $end_time[] = $shift_data->end_time;
                        }
                    }

                    $room = (isset($_POST[$i . 'room']) ? $_POST[$i . 'room'] : null);
                    $room_name = '';

                    if ($room != null) $room_name = RoomType::find($room)->name;

                    $shift_schedule[] = [
                        'date' => $_POST[$i . 'date'],
                        'room' => $room,
                        'room_name' => $room_name,
                        'shift' => $shift,
                        'shift_name' => $shift_name,
                        'start_time' => $start_time,
                        'end_time' => $end_time
                    ];
                }

                $schedule->shift_id     = null;
                $schedule->room_id      = null;
                $schedule->day          = json_encode($shift_schedule);
                $schedule->month        = $request->month;
                $schedule->day_on_month = $request->day_on_month;
            }

            $schedule->repeat = $request->repeat;
            $creator = Auth::user()->creatorId();
            $schedule->created_by    = $creator;

            if ($request->employee_id != 0) {
                $schedule->save();
            } else {
                #save multi
                $multi = [];
                foreach ($employees as $x => $val) {
                    $multi[] = [
                        'employee_id'   => $x,
                        'shift_id'      => (count($request->shift) > 1 || $request->repeat != 'on' ? null : $request->shift[0]),
                        'room_id'       => ($request->repeat == 'on' ? $request->room : null),
                        'month'         => ($request->repeat == 'on' ? '' : $request->month),
                        'day_on_month'  => ($request->repeat == 'on' ? null : $request->day_on_month),
                        'day'           => (count($request->shift) > 1 ? json_encode($shift_schedule) : null),
                        'repeat'        => $request->repeat,
                        'created_by'    => $creator,
                        'created_at'    => date("Y-m-d H:i:s"),
                        'updated_at'    => date("Y-m-d H:i:s")
                    ];
                }

                Schedule::insert($multi);
            }

            return redirect()->route('schedule.index')->with('success', __('Schedule successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show($id)
    {
        $schedule = Schedule::selectRaw('schedules.id as id, schedules.shift_id, schedules.room_id, day_on_month, month, schedules.repeat, employees.name as employee, day, schedules.created_by as created_by')->join('employees', 'schedules.employee_id', '=', 'employees.id')->where('schedules.id', $id)->first();
        $day = [];
        $room = [];

        if ($schedule->day != null and $schedule->repeat != 'on') {
            #code
            foreach (json_decode($schedule->day) as $x => $val) {

                $sh = [];
                $rm = [];
                if ($val->shift != null) {
                    foreach ($val->shift as $shift) {
                        $sh[] = $shift;
                    }
                }

                if ($val->room != '') {
                    $rm[] = $val->room;
                }

                $room[$val->date] = $rm;

                $day[$val->date] = $sh;
            }
        } else {
            $day = [];
            foreach (json_decode($schedule->day) as $x) {
                $day[] = $x->shift;
            }
        }

        $shift      = Shift::where('created_by', '=', Auth::user()->creatorId())->get();
        $roomtype = RoomType::where('created_by', '=', Auth::user()->creatorId())->get();

        $day_on_this_month = ($schedule->day_on_month != null ? $schedule->day_on_month : date('t'));
        $month = ($schedule->month != null ? $schedule->month : date('Y-m'));

        return view('schedule.show', compact('schedule', 'day_on_this_month', 'roomtype', 'shift', 'day', 'room', 'month'));
    }

    public function edit($schedule)
    {
        if (Auth::user()->can('Edit Schedule')) {
            $schedule = Schedule::selectRaw('schedules.id as id, schedules.shift_id, schedules.room_id, day_on_month, month, schedules.repeat, employees.name as employee, day, schedules.created_by as created_by')->join('employees', 'schedules.employee_id', '=', 'employees.id')->where('schedules.id', $schedule)->first();
            $day = [];
            $room = [];

            if ($schedule->created_by == Auth::user()->creatorId()) {
                if ($schedule->day != null and $schedule->repeat != 'on') {
                    #code
                    foreach (json_decode($schedule->day) as $x => $val) {

                        $sh = [];
                        $rm = [];
                        if ($val->shift != null) {
                            foreach ($val->shift as $shift) {
                                $sh[] = $shift;
                            }
                        }

                        if ($val->room != '') {
                            $rm[] = $val->room;
                        }

                        $room[$val->date] = $rm;

                        $day[$val->date] = $sh;
                    }
                } else {
                    $day = [];
                    foreach (json_decode($schedule->day) as $x) {
                        $day[] = $x->shift;
                    }
                }

                $shift      = Shift::where('created_by', '=', Auth::user()->creatorId())->get();
                $roomtype = RoomType::where('created_by', '=', Auth::user()->creatorId())->get();

                $day_on_this_month = ($schedule->day_on_month != null ? $schedule->day_on_month : date('t', strtotime('2022-12-1')));
                $month = ($schedule->month != null ? $schedule->month : date('Y-m'));

                return view('schedule.edit_new', compact('schedule', 'day_on_this_month', 'roomtype', 'shift', 'day', 'room', 'month'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Schedule $schedule)
    {
        if (Auth::user()->can('Edit Schedule')) {
            if ($schedule->created_by == Auth::user()->creatorId()) {

                $shift_schedule = [];

                if ($request->repeat == 'on') {
                    #code
                    $schedule->room_id      = $request->room;
                    $schedule->month        = '';

                    $schedule->day_on_month = null;
                    $schedule->repeat       = $request->repeat;

                    #
                    $shift_schedule = [];
                    foreach ($request->shift as $val) {
                        $shift_data = Shift::find($val);
                        $shift_schedule[] = [
                            'shift' => $val,
                            'shift_name' => $shift_data->name,
                            'start_time' => $shift_data->start_time,
                            'end_time' => $shift_data->end_time
                        ];
                    }

                    $schedule->shift_id     = null;
                    $schedule->day          = json_encode($shift_schedule);
                } else {
                    #code
                    $shift_schedule = [];

                    for ($i = 1; $i <= $request->day_on_month; $i++) {
                        # code...
                        $shift = [];
                        $shift_name = [];
                        $start_time = [];
                        $end_time = [];
                        if (isset($_POST[$i . 'shift'])) {
                            foreach ($_POST[$i . 'shift'] as $key => $value) {
                                # code...

                                $id_shift = $_POST[$i . 'shift'][$key];
                                $shift[] = $_POST[$i . 'shift'][$key];

                                $shift_data = Shift::where('id', $id_shift)->first();

                                $shift_name[] = $shift_data->name;
                                $start_time[] = $shift_data->start_time;
                                $end_time[] = $shift_data->end_time;
                            }
                        }

                        $room = (isset($_POST[$i . 'room']) ? $_POST[$i . 'room'] : null);
                        $room_name = '';

                        if ($room != null) $room_name = RoomType::find($room)->name;

                        $shift_schedule[] = [
                            'date' => $_POST[$i . 'date'],
                            'room' => $room,
                            'room_name' => $room_name,
                            'shift' => $shift,
                            'shift_name' => $shift_name,
                            'start_time' => $start_time,
                            'end_time' => $end_time
                        ];
                    }

                    $schedule->shift_id     = null;
                    $schedule->room_id      = null;
                    $schedule->day          = json_encode($shift_schedule);
                    $schedule->month        = $request->month;
                    $schedule->day_on_month = $request->day_on_month;
                    $schedule->repeat       = '';
                }
                $schedule->save();

                return redirect()->route('schedule.index')->with('success', __('Schedule successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Schedule $schedule)
    {
        if (Auth::user()->can('Delete Schedule')) {
            if ($schedule->created_by == Auth::user()->creatorId()) {
                $schedule->delete();

                return redirect()->route('schedule.index')->with('success', __('Schedule successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
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
