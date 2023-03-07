<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Shift')) {
            $shift_get = Shift::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('shift.index', compact('shift_get'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Shift')) {
            return view('shift.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Shift')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:20',
                    'start_time' => 'required',
                    'end_time' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $shift             = new Shift();
            $shift->name       = $request->name;
            $shift->start_time = $request->start_time;
            $shift->end_time   = $request->end_time;
            $shift->created_by = Auth::user()->creatorId();
            $shift->save();

            return redirect()->route('shift.index')->with('success', __('Shift successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('shift.index');
    }

    public function edit(Shift $shift)
    {
        if (Auth::user()->can('Edit Shift')) {
            if ($shift->created_by == Auth::user()->creatorId()) {

                return view('shift.edit', compact('shift'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Shift $shift)
    {
        if (Auth::user()->can('Edit Shift')) {
            if ($shift->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:20',
                        'start_time' => 'required',
                        'end_time' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }

                $shift->name        = $request->name;
                $shift->start_time  = $request->start_time;
                $shift->end_time    = $request->end_time;
                $shift->save();

                return redirect()->route('shift.index')->with('success', __('Shift successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Shift $shift)
    {
        if (Auth::user()->can('Delete Shift')) {
            if ($shift->created_by == Auth::user()->creatorId()) {
                $shift->delete();

                return redirect()->route('shift.index')->with('success', __('Shift successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
