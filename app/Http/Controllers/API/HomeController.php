<?php

namespace App\Http\Controllers\API;

use App\Models\Employee;
use App\Models\AttendanceEmployee;
use App\Models\AdditionalInformation;
use App\Models\EmployeeAdditionalInformation;
use App\Models\User;
use App\Models\Utility;
use App\Models\Event;
use App\Models\Schedule;
use App\Models\Shift;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * main API
     */
    public function home(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $emp = Employee::where('user_id', '=', $request->user_id)->first();

        if (!empty($emp)) {
            $creator = $emp->created_by;
            return response()->json([
                'type' => 'success',
                'message' => 'available',
                'settings' => $this->settings($creator),
                'user' => $this->users($request->user_id),
                'interval_attendance' => $this->interval_attendance($emp),
                'events' => $this->event($emp, $creator),
                'shift' => $this->shift($emp, $creator),
                'information' => array_merge($this->getNotifAdditional($emp, $creator), $this->getBirthday($creator))
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }

    /**
     * get API Settings
     */
    public function settings($creator)
    {
        $settings = Utility::settings($creator);
        return $settings;
    }

    /**
     * get API Interval History
     */
    public function interval_attendance($emp)
    {
        $attendanceEmployee = AttendanceEmployee::selectRAW('id, clock_in, clock_out, date, status')
            ->where('employee_id', $emp->id)
            ->whereRAW('created_at >= (NOW() - INTERVAL 24 HOUR)')
            ->where('clock_out', '00:00:00')
            ->whereIn('status', array('Present', 'Pending'))
            ->where('parent_id', null)
            ->orderBy('id', 'DESC')
            ->get();

        if (empty($attendanceEmployee)) {
            return response()->json([
                'type' => 'success',
                'message' => 'empty',
                'data' => $attendanceEmployee
            ]);
        }

        $my_history = [];

        foreach ($attendanceEmployee as $x => $val) {
            $my_history[] = [
                'id' => $val->id,
                'clock_in' => $val->clock_in,
                'clock_out' => $val->clock_out,
                'date' => $val->date,
                'status' => $val->status,
                'attendance_out' => (!empty($val->permission) ? $val->permission->clock_out : ''),
            ];
        }

        return $my_history;
    }

    /**
     * get API event
     */
    public function event($emp, $creator)
    {
        $events    = Event::leftjoin('event_employees', 'events.id', '=', 'event_employees.event_id')->where('event_employees.employee_id', '=', $emp->id)->orWhere(
            function ($q) use ($creator) {
                $q->where('events.department_id', '["0"]')->where('events.employee_id', '["0"]')->where('events.created_by', $creator);
            }
        )->get()->toArray();

        if (empty($events)) {
            return [];
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

        return $arrEvents;
    }

    /**
     * get API user profile
     */
    public function users($user_id)
    {
        $userProfile = User::selectRAW('id, name, email, type, avatar, pin')->where('id', $user_id)->first();

        $profile = asset(url('uploads/avatar/'));

        $position = $userProfile->employee;

        if (!empty($position->position)) {
            $position = $position->position->name;
        } else {
            $position = $userProfile->type;
        }

        $data = [];

        if (!empty($userProfile)) {
            $data = [
                'type' => 'success',
                'message' => 'available',
                'data' => array(
                    'id' => $userProfile->id,
                    'name' => $userProfile->name,
                    'email' => $userProfile->email,
                    'type' => $userProfile->type,
                    'type_full' => ucfirst($position),
                    'avatar' => (!empty($userProfile->avatar) ? $profile . '/' . $userProfile->avatar : $profile . '/avatar.png'),
                    'pin' => $userProfile->pin != null ? 1 : 0
                )
            ];
        }

        return $data;
    }

    /**
     * get API Shift
     */
    public function shift($employee, $creator)
    {
        $settings = $this->settings($creator);

        $access_in_before = gmdate('H:i:s', ($settings['can_access_attendance_in_before'] * 60));
        $access_in_after = gmdate('H:i:s', ($settings['can_access_attendance_in_after'] * 60));

        $this_month = date('Y-m');

        $schedule = Schedule::selectRaw('schedules.id as id, schedules.shift_id, schedules.room_id, day_on_month, month, schedules.repeat, employees.name as employee, day, schedules.created_by as created_by')
            ->join('employees', 'schedules.employee_id', '=', 'employees.id')
            ->where('schedules.employee_id', $employee->id)
            ->where('schedules.created_by', '=', $creator)
            ->whereIn('schedules.month', array('', $this_month))
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

                            if ($settings['can_access_attendance_in_before'] != 0) {
                                if ($settings['can_access_attendance_in_after'] != 0) {
                                    if (strtotime(accumulateTime([date('H:i:s'), $access_in_before])) >= strtotime(date('H:i:s', strtotime($getShift->start_time))) and strtotime(date('H:i:s')) < strtotime(accumulateTime([date('H:i:s', strtotime($getShift->start_time)), $access_in_after]))) {
                                        if ($getShift) {
                                            $data[] = [
                                                'id' => $val,
                                                'name' => $getShift->name,
                                                'start_time' => $getShift->start_time,
                                                'end_time' => $getShift->end_time,
                                                'date' => $value->date
                                            ];
                                        }
                                    }
                                } else {
                                    if (strtotime(accumulateTime([date('H:i:s'), $access_in_before])) >= strtotime(date('H:i:s', strtotime($getShift->start_time))) and strtotime(date('H:i:s')) <= strtotime(date('H:i:s', strtotime($getShift->end_time)))) {
                                        if ($getShift) {
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
                            } else {
                                if ($getShift) {
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
                }
            }

            if ($schedule->day != null and $schedule->repeat == 'on') {
                if ($settings['can_access_attendance_in_before'] != 0) {
                    foreach (json_decode($schedule->day) as $key => $value) {
                        if ($settings['can_access_attendance_in_after'] != 0) {
                            if (strtotime(accumulateTime([date('H:i:s'), $access_in_before])) >= strtotime(date('H:i:s', strtotime($value->start_time))) and strtotime(date('H:i:s')) < strtotime(accumulateTime([date('H:i:s', strtotime($value->start_time)), $access_in_after]))) {
                                $data[] = [
                                    'id' => $value->shift,
                                    'name' => $value->shift_name,
                                    'start_time' => $value->start_time,
                                    'end_time' => $value->end_time
                                ];
                            }
                        } else {
                            if (strtotime(accumulateTime([date('H:i:s'), $access_in_before])) >= strtotime(date('H:i:s', strtotime($value->start_time))) and strtotime(date('H:i:s')) <= strtotime(date('H:i:s', strtotime($value->end_time)))) {
                                $data[] = [
                                    'id' => $value->shift,
                                    'name' => $value->shift_name,
                                    'start_time' => $value->start_time,
                                    'end_time' => $value->end_time
                                ];
                            }
                        }
                    }
                } else {
                    foreach (json_decode($schedule->day) as $key => $value) {
                        $data[] = [
                            'id' => $value->shift,
                            'name' => $value->shift_name,
                            'start_time' => $value->start_time,
                            'end_time' => $value->end_time
                        ];
                    }
                }
            }

            return $data;
        }
    }

    /**
     * get API Notif Additional
     */
    public function getNotifAdditional($employee, $creator)
    {
        $list_additional = AdditionalInformation::where('created_by', $creator)->get();
        $employee_additional = $employee->additional()->pluck('additional_value', 'additional_id');

        $data = [];
        if (!empty($employee)) {
            foreach ($list_additional as $val) {
                if ($val->send_notification == 1 and $val->reminder != 0 and $val->type == 'date') {

                    if (!empty($employee_additional[$val->id])) {
                        $date = date('Y-m-d', strtotime('-' . $val->reminder . ' days', strtotime($employee_additional[$val->id])));
                        $data[] = [
                            'title' => $val->name,
                            'value' => $date,
                            'background' => '',
                            'detail' => ''
                        ];
                    }
                }
            }

            return $data;
        } else {
            return $data;
        }
    }

    /**
     * get birthday
     */
    public function getBirthday($creator)
    {
        $employee = Employee::where('created_by', $creator)->get();
        $todayBirthday = [];
        foreach ($employee as $val) {
            if (date('m-d', strtotime($val->dob)) == date('m-d')) {
                $todayBirthday[] = [
                    'title' => 'Selamat Ulang Tahun',
                    'value' => $val->name,
                    'background' => '',
                    'detail' => ''
                ];
            }
        }

        return $todayBirthday;
    }
}
