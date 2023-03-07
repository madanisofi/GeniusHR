<?php

namespace App\Http\Controllers;

use App\Models\AccountList;
use App\Models\Announcement;
use App\Models\AttendanceEmployee;
use App\Models\Employee;
use App\Models\Event;
use App\Models\Job;
use App\Models\LandingPageSection;
use App\Models\Meeting;
use App\Models\Order;
use App\Models\Payees;
use App\Models\Payer;
use App\Models\Plan;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Shift;
use App\Models\Utility;
use App\Models\Schedule;
use App\Models\PermissionType;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Set language
            if (Auth::user()->lang != '') {
                App::setLocale(Auth::user()->lang);
            }
            if ($user->type == 'employee') {
                $emp = Employee::where('user_id', '=', $user->id)->first();
                $creator = $emp->created_by;

                $announcements = Announcement::orderBy('announcements.id', 'desc')->take(5)->leftjoin('announcement_employees', 'announcements.id', '=', 'announcement_employees.announcement_id')->where('announcement_employees.employee_id', '=', $emp->id)->orWhere(
                    function ($q) use ($creator) {
                        $q->where('announcements.department_id', '["0"]')->where('announcements.employee_id', '["0"]')->where('announcements.created_by', $creator);
                    }
                )->get();

                $employees = Employee::get();
                $meetings  = Meeting::orderBy('meetings.id', 'desc')->take(5)->leftjoin('meeting_employees', 'meetings.id', '=', 'meeting_employees.meeting_id')->where('meeting_employees.employee_id', '=', $emp->id)->orWhere(
                    function ($q) use ($creator) {
                        $q->where('meetings.department_id', '["0"]')->where('meetings.employee_id', '["0"]')->where('meetings.created_by', $creator);
                    }
                )->get();
                $events    = Event::leftjoin('event_employees', 'events.id', '=', 'event_employees.event_id')->where('event_employees.employee_id', '=', $emp->id)->orWhere(
                    function ($q) use ($creator) {
                        $q->where('events.department_id', '["0"]')->where('events.employee_id', '["0"]')->where('events.created_by', $creator);
                    }
                )->get();

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

                $date               = date("Y-m-d");
                $time               = date("H:i:s");
                $employeeAttendance = AttendanceEmployee::orderBy('id', 'desc')->where('employee_id', '=', !empty(Auth::user()->employee) ? Auth::user()->employee->id : 0)->where('date', '=', $date)->first();

                $officeTime['startTime'] = Utility::getValByName('company_start_time');
                $officeTime['endTime']   = Utility::getValByName('company_end_time');

                $modeshift = Utility::getValByName('shift');
                $modeQr = Utility::getValByName('qr_presence');
                $modeSelfie = Utility::getValByName('selfie_presence');


                $shift = $this->getShift($emp->id);
                $permission = $this->getPermission(($emp->id));

                return view('dashboard.dashboard', compact('arrEvents', 'announcements', 'employees', 'meetings', 'employeeAttendance', 'officeTime', 'modeshift', 'shift', 'modeQr', 'modeSelfie', 'permission'));
            } else if ($user->type == 'super admin') {
                $user                       = Auth::user();
                $user['total_user']         = $user->countCompany();
                $user['total_paid_user']    = $user->countPaidCompany();
                $user['total_orders']       = Order::total_orders();
                $user['total_orders_price'] = Order::total_orders_price();
                $user['total_plan']         = Plan::total_plan();
                $user['most_purchese_plan'] = (!empty(Plan::most_purchese_plan()) ? Plan::most_purchese_plan()->name : '');

                $chartData = $this->getOrderChart(['duration' => 'week']);

                return view('dashboard.super_admin', compact('user', 'chartData'));
            } else {
                $events    = Event::where('created_by', '=', Auth::user()->creatorId())->get();
                $arrEvents = [];

                foreach ($events as $event) {
                    $arr['id']    = $event['id'];
                    $arr['title'] = $event['title'];
                    $arr['start'] = $event['start_date'];
                    $arr['end']   = $event['end_date'];

                    $arr['backgroundColor'] = $event['color'];
                    $arr['borderColor']     = "#fff";
                    $arr['textColor']       = "white";
                    $arr['url']             = route('event.edit', $event['id']);

                    $arrEvents[] = $arr;
                }


                $announcements = Announcement::orderBy('announcements.id', 'desc')->take(5)->where('created_by', '=', Auth::user()->creatorId())->get();


                $emp           = User::where('type', '=', 'employee')->where('created_by', '=', Auth::user()->creatorId())->get();
                $countEmployee = count($emp);

                $user      = User::where('type', '!=', 'employee')->where('created_by', '=', Auth::user()->creatorId())->get();
                $countUser = count($user);

                $countTicket      = Ticket::where('created_by', '=', Auth::user()->creatorId())->count();
                $countOpenTicket  = Ticket::where('status', '=', 'open')->where('created_by', '=', Auth::user()->creatorId())->count();
                $countCloseTicket = Ticket::where('status', '=', 'close')->where('created_by', '=', Auth::user()->creatorId())->count();

                $currentDate = date('Y-m-d');

                $employees     = User::where('type', '=', 'employee')->where('created_by', '=', Auth::user()->creatorId())->get();
                $countEmployee = count($employees);
                $notClockIn    = AttendanceEmployee::where('date', '=', $currentDate)->get()->pluck('employee_id');

                $notClockIns = Employee::where('created_by', '=', Auth::user()->creatorId())->whereNotIn('id', $notClockIn)->get();

                $accountBalance = AccountList::where('created_by', '=', Auth::user()->creatorId())->sum('initial_balance');
                $activeJob   = Job::where('status', 'active')->where('created_by', '=', Auth::user()->creatorId())->count();
                $inActiveJOb = Job::where('status', 'in_active')->where('created_by', '=', Auth::user()->creatorId())->count();

                $totalPayee = Payees::where('created_by', '=', Auth::user()->creatorId())->count();
                $totalPayer = Payer::where('created_by', '=', Auth::user()->creatorId())->count();

                $meetings = Meeting::where('created_by', '=', Auth::user()->creatorId())->limit(5)->get();

                return view('dashboard.dashboard', compact('arrEvents', 'activeJob', 'inActiveJOb', 'announcements', 'employees', 'meetings', 'countEmployee', 'countUser', 'countTicket', 'countOpenTicket', 'countCloseTicket', 'notClockIns', 'countEmployee', 'accountBalance', 'totalPayee', 'totalPayer'));
            }
        } else {
            $settings = Utility::settings();
            if ($settings['display_landing_page'] == 'on') {
                $plans = Plan::get();
                $get_section = LandingPageSection::orderBy('section_order', 'ASC')->get();

                return view('layouts.landing', compact('plans', 'get_section'));
            } else {
                return redirect('login');
            }
        }
    }

    public function getOrderChart($arrParam)
    {
        $arrDuration = [];
        if ($arrParam['duration']) {
            if ($arrParam['duration'] == 'week') {
                $previous_week = strtotime("-2 week +1 day");
                for ($i = 0; $i < 14; $i++) {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);
                    $previous_week                              = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }

        $arrTask          = [];
        $arrTask['label'] = [];
        $arrTask['data']  = [];
        foreach ($arrDuration as $date => $label) {

            $data               = Order::select(\DB::raw('count(*) as total'))->whereDate('created_at', '=', $date)->first();
            $arrTask['label'][] = $label;
            $arrTask['data'][]  = $data->total;
        }

        return $arrTask;
    }

    public function getShift($emp_id)
    {
        $schedule = Schedule::selectRaw('schedules.id as id, schedules.shift_id, schedules.room_id, day_on_month, month, schedules.repeat, employees.name as employee, day, schedules.created_by as created_by')
            ->join('employees', 'schedules.employee_id', '=', 'employees.id')
            ->where('schedules.employee_id', $emp_id)
            ->orderBy('schedules.updated_at', 'DESC')->first();

        if (empty($schedule)) {
            return [];
        } else {

            $data = [];

            if ($schedule->day == null) {
                $getShift = Shift::find($schedule->shift_id)->first();
                $data[] = [
                    'id'    => (string)$schedule->shift_id,
                    'name' => $getShift->name,
                    'start_time' => $getShift->start_time,
                    'end_time' => $getShift->end_time
                ];
            }

            if ($schedule->day != null and $schedule->repeat != 'on') {
                foreach (json_decode($schedule->day) as $key => $value) {
                    if (date('Y-m-d', strtotime($value->date)) == date('Y-m-d')) {
                        foreach ($value->shift as $x => $val) {
                            $getShift = Shift::where('id', $val)->first();
                            $data[] = [
                                'id' => $val,
                                'name' => $getShift->name,
                                'start_time' => $getShift->start_time,
                                'end_time' => $getShift->end_time,
                                'date' => $value->date
                            ];
                        }
                    }
                }
            }

            if ($schedule->day != null and $schedule->repeat == 'on') {
                foreach (json_decode($schedule->day) as $key => $value) {
                    $data[] = [
                        'id' => $value->shift,
                        'name' => $value->shift_name,
                        'start_time' => $value->start_time,
                        'end_time' => $value->end_time
                    ];
                }
            }

            return $data;
        }
    }

    public function getPermission($emp_id)
    {
        $employee = Employee::find($emp_id);
        $permission_counts = PermissionType::select(\DB::raw('COALESCE(COUNT(attendance_employees.id),0) AS total_permission, permission_types.title, permission_types.days,permission_types.id, permission_types.many_submission'))
            ->leftjoin(
                'attendance_employees',
                function ($join) use ($employee) {
                    $join->on('attendance_employees.permissiontype_id', '=', 'permission_types.id');
                    $join->where('attendance_employees.employee_id', '=', $employee->id);
                    $join->whereYear('attendance_employees.date', date('Y'));
                    $join->whereMonth('attendance_employees.date', date('m'));
                }
            )->where('permission_types.created_by', '=', $employee->created_by)
            ->groupBy('permission_types.id')->get();
        if (empty($permission_counts)) {
            return [];
        } else {
            return $permission_counts;
        }
    }
}
