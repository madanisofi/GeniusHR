<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeTypeController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Employee Type')) {
            $employeetypes = EmployeeType::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('employeetype.index', compact('employeetypes'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Employee Type')) {
            return view('employeetype.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Employee Type')) {

            $validator = Validator::make(
                $request->all(),
                [

                    'name' => 'required|max:20',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $employeetype             = new EmployeeType();
            $employeetype->name       = $request->name;
            $employeetype->created_by = Auth::user()->creatorId();
            $employeetype->save();

            return redirect()->route('employeetype.index')->with('success', __('Employee Type successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show(EmployeeType $employeetype)
    {
        return redirect()->route('roomtype.index');
    }

    public function edit(EmployeeType $employeetype)
    {
        if (Auth::user()->can('Edit Shift')) {
            if ($employeetype->created_by == Auth::user()->creatorId()) {

                return view('employeetype.edit', compact('employeetype'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, EmployeeType $employeetype)
    {
        if (Auth::user()->can('Edit Employee Type')) {
            if ($employeetype->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [

                        'name' => 'required|max:20',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }

                $employeetype->name = $request->name;
                $employeetype->save();

                return redirect()->route('employeetype.index')->with('success', __('Employee Type successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(EmployeeType $employeetype)
    {
        if (Auth::user()->can('Delete Employee Type')) {
            if ($employeetype->created_by == Auth::user()->creatorId()) {
                $employeetype->delete();

                return redirect()->route('employeetype.index')->with('success', __('Employee Type successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
