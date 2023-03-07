<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\OtherPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OtherPaymentController extends Controller
{
    public function otherpaymentCreate($id)
    {
        $employee = Employee::find($id);

        return view('otherpayment.create', compact('employee'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Other Payment')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'title' => 'required',
                    'amount' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $otherpayment              = new OtherPayment();
            $otherpayment->employee_id = $request->employee_id;
            $otherpayment->title       = $request->title;
            $otherpayment->amount      = $request->amount;
            $otherpayment->created_by  = Auth::user()->creatorId();
            $otherpayment->save();

            return back()->with('success', __('OtherPayment  successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('commision.index');
    }

    public function edit($otherpayment)
    {
        $otherpayment = OtherPayment::find($otherpayment);
        if (Auth::user()->can('Edit Other Payment')) {
            if ($otherpayment->created_by == Auth::user()->creatorId()) {
                return view('otherpayment.edit', compact('otherpayment'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, OtherPayment $otherpayment)
    {
        if (Auth::user()->can('Edit Other Payment')) {
            if ($otherpayment->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [

                        'title' => 'required',
                        'amount' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }

                $otherpayment->title  = $request->title;
                $otherpayment->amount = $request->amount;
                $otherpayment->save();

                return back()->with('success', __('OtherPayment successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(OtherPayment $otherpayment)
    {
        if (Auth::user()->can('Delete Other Payment')) {
            if ($otherpayment->created_by == Auth::user()->creatorId()) {
                $otherpayment->delete();

                return back()->with('success', __('OtherPayment successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
