<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveTypeController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Leave Type')) {
            $leavetypes = LeaveType::where('created_by', '=', Auth::user()->creatorId())->get();

            $data = [];

            $month = [
                '00' => 'All',
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

            foreach ($leavetypes as $key => $value) {
                $parent = [];
                if ($value->parent != '') {
                    foreach (json_decode($value->parent) as $x) {
                        $parent[] = LeaveType::find($x)->title;
                    }
                }

                $data[] = [
                    'id' => $value->id,
                    'title' => $value->title,
                    'days' => $value->days,
                    'select_all' => $value->select_all,
                    'parent' => $parent,
                    'reduction' => $value->reduction,
                    'start_date' => $month[(string)$value->start_date],
                    'end_date' => $month[(string)$value->end_date],
                ];
            }

            return view('leavetype.index', compact('leavetypes', 'data'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {

        if (Auth::user()->can('Create Leave Type')) {
            $leavetype = LeaveType::where('created_by', '=', Auth::user()->creatorId())->get();

            $month = [
                '00' => 'All',
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
            return view('leavetype.create', compact('leavetype', 'month'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {

        if (Auth::user()->can('Create Leave Type')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'days' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $leavetype             = new LeaveType();
            $leavetype->title      = $request->title;
            if (isset($request->parent))  $leavetype->parent = json_encode($request->parent);
            if (isset($request->start_date))  $leavetype->start_date = $request->start_date;
            if (isset($request->end_date))  $leavetype->end_date = $request->end_date;
            if (isset($request->reduction))  $leavetype->reduction = $request->reduction;
            if (!isset($request->select_all)) {
                $leavetype->select_all       = 'off';
            } else {
                $leavetype->select_all  = $request->select_all;
            }
            $leavetype->days       = $request->days;
            $leavetype->days       = $request->days;
            $leavetype->created_by = Auth::user()->creatorId();
            $leavetype->save();

            return redirect()->route('leavetype.index')->with('success', __('LeaveType  successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('leavetype.index');
    }

    public function edit(LeaveType $leavetype)
    {
        if (Auth::user()->can('Edit Leave Type')) {
            if ($leavetype->created_by == Auth::user()->creatorId()) {

                $leavetypes = LeaveType::where('created_by', '=', Auth::user()->creatorId())->get();
                $month = [
                    '00' => 'All',
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

                $parent = [];
                if ($leavetype->parent != null) {
                    $parent = json_decode($leavetype->parent);
                }

                return view('leavetype.edit', compact('leavetype', 'leavetypes', 'month', 'parent'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, LeaveType $leavetype)
    {
        if (Auth::user()->can('Edit Leave Type')) {
            if ($leavetype->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'title' => 'required',
                        'days' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }

                $leavetype->title = $request->title;
                $leavetype->days  = $request->days;
                if (isset($request->parent)) {
                    $leavetype->parent = json_encode($request->parent);
                } else {
                    $leavetype->parent = null;
                }
                if (!isset($request->select_all)) {
                    $leavetype->select_all       = 'off';
                } else {
                    $leavetype->select_all  = $request->select_all;
                }
                if (isset($request->start_date))  $leavetype->start_date = $request->start_date;
                if (isset($request->end_date))  $leavetype->end_date = $request->end_date;
                if (isset($request->reduction))  $leavetype->reduction = $request->reduction;
                $leavetype->save();

                return redirect()->route('leavetype.index')->with('success', __('LeaveType successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(LeaveType $leavetype)
    {
        if (Auth::user()->can('Delete Leave Type')) {
            if ($leavetype->created_by == Auth::user()->creatorId()) {
                $leavetype->delete();

                return redirect()->route('leavetype.index')->with('success', __('LeaveType successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
