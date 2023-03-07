<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PayShift;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PayshiftController extends Controller
{
    public function payshiftCreate($id)
    {
        $employee = Employee::find($id);
        $shift      = Shift::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
        return view('payshift.create', compact('employee', 'shift'));
    }

    public function store(Request $request)
    {

        if (Auth::user()->can('Create Guard Bonus')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'shift_id' => 'required',
                    'amount' => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            // validate
            $validate_shift = PayShift::where('employee_id', $request->employee_id)->where('shift_id', $request->shift_id)->where('created_by', Auth::user()->creatorId())->first();
            if ($validate_shift) {
                return back()->with('error', __('Guard Bonus Available.'));
            }

            $payshift              = new PayShift();
            $payshift->employee_id = $request->employee_id;
            $payshift->shift_id     = $request->shift_id;
            $payshift->amount      = $request->amount;
            $payshift->created_by  = Auth::user()->creatorId();
            $payshift->save();

            return back()->with('success', __('Guard Bonus successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('commision.index');
    }

    public function edit($payshift)
    {
        $payshift = PayShift::find($payshift);
        if (Auth::user()->can('Edit Guard Bonus')) {
            if ($payshift->created_by == Auth::user()->creatorId()) {
                $shift      = Shift::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');

                return view('payshift.edit', compact('shift', 'payshift'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, PayShift $payshift)
    {
        if (Auth::user()->can('Edit Guard Bonus')) {
            if ($payshift->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'amount' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }
                $payshift->amount      = $request->amount;
                $payshift->save();

                return back()->with('success', __('Guard Bonus successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(PayShift $payshift)
    {
        if (Auth::user()->can('Delete Guard Bonus')) {
            if ($payshift->created_by == Auth::user()->creatorId()) {
                $payshift->delete();

                return back()->with('success', __('Pay Shif successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
