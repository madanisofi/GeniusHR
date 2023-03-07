<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommissionController extends Controller
{
    public function commissionCreate($id)
    {
        $employee = Employee::find($id);

        return view('commission.create', compact('employee'));
    }

    public function store(Request $request)
    {

        if (Auth::user()->can('Create Commission')) {
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

            $commission              = new Commission();
            $commission->employee_id = $request->employee_id;
            $commission->title       = $request->title;
            $commission->amount      = $request->amount;
            $commission->created_by  = Auth::user()->creatorId();
            $commission->save();

            return back()->with('success', __('Commission  successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('commision.index');
    }

    public function edit($commission)
    {
        $commission = Commission::find($commission);
        if (Auth::user()->can('Edit Commission')) {
            if ($commission->created_by == Auth::user()->creatorId()) {

                return view('commission.edit', compact('commission'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Commission $commission)
    {
        if (Auth::user()->can('Edit Commission')) {
            if ($commission->created_by == Auth::user()->creatorId()) {
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

                $commission->title  = $request->title;
                $commission->amount = $request->amount;
                $commission->save();

                return back()->with('success', __('Commission successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Commission $commission)
    {

        if (Auth::user()->can('Delete Commission')) {
            if ($commission->created_by == Auth::user()->creatorId()) {

                $commission->delete();

                return back()->with('success', __('Commission successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
