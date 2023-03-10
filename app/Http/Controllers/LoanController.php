<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Loan;
use App\Models\LoanOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    public function loanCreate($id)
    {
        $employee = Employee::find($id);
        $loan_options      = LoanOption::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
        return view('loan.create', compact('employee', 'loan_options'));
    }

    public function store(Request $request)
    {

        if (Auth::user()->can('Create Loan')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'loan_option' => 'required',
                    'title' => 'required',
                    'amount' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'reason' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $loan              = new Loan();
            $loan->employee_id = $request->employee_id;
            $loan->loan_option = $request->loan_option;
            $loan->title       = $request->title;
            $loan->amount      = $request->amount;
            $loan->start_date  = $request->start_date;
            $loan->end_date    = $request->end_date;
            $loan->reason      = $request->reason;
            $loan->created_by  = Auth::user()->creatorId();
            $loan->save();

            return back()->with('success', __('Loan  successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('commision.index');
    }

    public function edit($loan)
    {
        $loan = Loan::find($loan);
        if (Auth::user()->can('Edit Loan')) {
            if ($loan->created_by == Auth::user()->creatorId()) {
                $loan_options = LoanOption::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');

                return view('loan.edit', compact('loan', 'loan_options'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Loan $loan)
    {
        if (Auth::user()->can('Edit Loan')) {
            if ($loan->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [

                        'loan_option' => 'required',
                        'title' => 'required',
                        'amount' => 'required',
                        'start_date' => 'required',
                        'end_date' => 'required',
                        'reason' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }
                $loan->loan_option = $request->loan_option;
                $loan->title       = $request->title;
                $loan->amount      = $request->amount;
                $loan->start_date  = $request->start_date;
                $loan->end_date    = $request->end_date;
                $loan->reason      = $request->reason;
                $loan->save();

                return back()->with('success', __('Loan successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Loan $loan)
    {
        if (Auth::user()->can('Delete Loan')) {
            if ($loan->created_by == Auth::user()->creatorId()) {
                $loan->delete();

                return back()->with('success', __('Loan successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
