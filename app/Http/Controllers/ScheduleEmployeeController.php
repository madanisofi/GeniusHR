<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Shift;
use App\Models\RoomType;
use App\Models\ScheduleEmployee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ScheduleEmployeeController extends Controller
{

    public function create()
    {
        if (Auth::user()->can('Create Schedule')) {

            $employees = Employee::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employees->prepend('All', 0);
            $branch      = Branch::where('created_by', '=', Auth::user()->creatorId())->get();
            $departments = Department::where('created_by', '=', Auth::user()->creatorId())->get();
            $shift      = Shift::where('created_by', '=', Auth::user()->creatorId())->get();
            $roomtype = RoomType::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('schedule.change', compact('employees', 'branch', 'departments', 'shift', 'roomtype'));
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
                    'employee_id' => 'required',
                    'shift_id' => 'required',
                    'date'  => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $scheduleEmployee               = new ScheduleEmployee();
            $scheduleEmployee->shift_id   = $request->shift_id;
            $scheduleEmployee->employee_id   = $request->employee_id;
            $scheduleEmployee->date   = $request->date;
            $scheduleEmployee->created_by    = Auth::user()->creatorId();

            $scheduleEmployee->save();

            return redirect()->route('schedule.index')->with('success', __('Schedule successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
