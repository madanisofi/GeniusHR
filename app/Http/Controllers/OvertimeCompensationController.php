<?php

namespace App\Http\Controllers;

use App\Models\OvertimeCompensation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OvertimeCompensationController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Overtime Compensation')) {
            $overtimeCompensation = OvertimeCompensation::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('compensation.index', compact('overtimeCompensation'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Overtime Compensation')) {
            return view('compensation.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Overtime Compensation')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $overtimeCompensation             = new OvertimeCompensation();
            $overtimeCompensation->name       = $request->name;
            $overtimeCompensation->attendance_option = $request->attendance_option;
            $overtimeCompensation->created_by = Auth::user()->creatorId();
            $overtimeCompensation->save();

            return redirect()->route('compensation.index')->with('success', __('Overtime Compensation  successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('compensation.index');
    }

    public function edit(OvertimeCompensation $compensation)
    {
        if (Auth::user()->can('Edit Overtime Compensation')) {
            if ($compensation->created_by == Auth::user()->creatorId()) {

                return view('compensation.edit', compact('compensation'));
            } else {
                return response()->json(['error' => $compensation->created_by], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, OvertimeCompensation $compensation)
    {
        if (Auth::user()->can('Edit Overtime Compensation')) {
            if ($compensation->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }

                $compensation->name = $request->name;
                $compensation->attendance_option = $request->attendance_option;
                $compensation->save();

                return redirect()->route('compensation.index')->with('success', __('Overtime Compensation successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(OvertimeCompensation $compensation)
    {
        if (Auth::user()->can('Delete Overtime Compensation')) {
            if ($compensation->created_by == Auth::user()->creatorId()) {
                $compensation->delete();

                return redirect()->route('compensation.index')->with('success', __('Overtime Compensation successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
