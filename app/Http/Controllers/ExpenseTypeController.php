<?php

namespace App\Http\Controllers;

use App\Models\ExpenseType;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseTypeController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Expense Type')) {
            $auto_expense_payroll = Utility::getValByName('auto_expense_payroll');
            if ($auto_expense_payroll == 'on') {
                $expensetypes = ExpenseType::whereIn('created_by', array(1, Auth::user()->creatorId()))->get();
            } else {
                $expensetypes = ExpenseType::where('created_by', '=', Auth::user()->creatorId())->get();
            }

            return view('expensetype.index', compact('expensetypes', 'auto_expense_payroll'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Expense Type')) {
            return view('expensetype.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Expense Type')) {

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

            $expensetype             = new ExpenseType();
            $expensetype->name       = $request->name;
            $expensetype->created_by = Auth::user()->creatorId();
            $expensetype->save();

            return redirect()->route('expensetype.index')->with('success', __('ExpenseType  successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('expensetype.index');
    }

    public function edit(ExpenseType $expensetype)
    {
        if (Auth::user()->can('Edit Expense Type')) {
            if ($expensetype->created_by == Auth::user()->creatorId()) {

                return view('expensetype.edit', compact('expensetype'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, ExpenseType $expensetype)
    {
        if (Auth::user()->can('Edit Expense Type')) {
            if ($expensetype->created_by == Auth::user()->creatorId()) {
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

                $expensetype->name = $request->name;
                $expensetype->save();

                return redirect()->route('expensetype.index')->with('success', __('ExpenseType successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(ExpenseType $expensetype)
    {
        if (Auth::user()->can('Delete Expense Type')) {
            if ($expensetype->created_by == Auth::user()->creatorId()) {
                $expensetype->delete();

                return redirect()->route('expensetype.index')->with('success', __('ExpenseType successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
