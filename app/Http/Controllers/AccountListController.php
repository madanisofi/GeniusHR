<?php

namespace App\Http\Controllers;

use App\Models\AccountList;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AccountListController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Account List')) {
            $auto_expense_payroll = Utility::getValByName('auto_expense_payroll');
            $accountlists = AccountList::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('accountlist.index', compact('accountlists', 'auto_expense_payroll'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Account List')) {
            $auto_expense_payroll = Utility::getValByName('auto_expense_payroll');
            return view('accountlist.create', compact('auto_expense_payroll'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Account List')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'account_name' => 'required',
                    'initial_balance' => 'required',
                    'account_number' => 'required',
                    'branch_code' => 'required',
                    'bank_branch' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }


            $accountlist                  = new AccountList();
            $accountlist->account_name    = $request->account_name;
            $accountlist->initial_balance = $request->initial_balance;
            $accountlist->account_number  = $request->account_number;
            $accountlist->branch_code     = $request->branch_code;
            $accountlist->bank_branch     = $request->bank_branch;
            $accountlist->auto_payroll    = $request->auto_payroll;
            $accountlist->created_by      = Auth::user()->creatorId();
            $accountlist->save();

            return redirect()->route('accountlist.index')->with('success', __('Account successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('accountlist.index');
    }

    public function edit(AccountList $accountlist)
    {
        if (Auth::user()->can('Edit Account List')) {
            if ($accountlist->created_by == Auth::user()->creatorId()) {
                $auto_expense_payroll = Utility::getValByName('auto_expense_payroll');
                return view('accountlist.edit', compact('accountlist', 'auto_expense_payroll'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, AccountList $accountlist)
    {
        if (Auth::user()->can('Edit Account List')) {
            if ($accountlist->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'account_name' => 'required',
                        'initial_balance' => 'required',
                        'account_number' => 'required',
                        'branch_code' => 'required',
                        'bank_branch' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }

                $accountlist->account_name    = $request->account_name;
                $accountlist->initial_balance = $request->initial_balance;
                $accountlist->account_number  = $request->account_number;
                $accountlist->branch_code     = $request->branch_code;
                $accountlist->bank_branch     = $request->bank_branch;
                $accountlist->auto_payroll    = $request->auto_payroll;
                $accountlist->save();

                return redirect()->route('accountlist.index')->with('success', __('Account successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(AccountList $accountlist)
    {
        if (Auth::user()->can('Delete Account List')) {
            if ($accountlist->created_by == Auth::user()->creatorId()) {
                $accountlist->delete();

                return redirect()->route('accountlist.index')->with('success', __('Account successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function account_balance()
    {
        $accountLists = AccountList::where('created_by', Auth::user()->creatorId())->get();

        return view('accountlist.account_balance', compact('accountLists'));
    }
}
