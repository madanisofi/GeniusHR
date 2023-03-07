<?php

namespace App\Http\Controllers;

use App\Exports\ExpenseExport;
use App\Models\AccountList;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Payees;
use App\Models\PaymentType;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Expense')) {
            $expenses = Expense::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('expense.index', compact('expenses'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Expense')) {
            $auto_expense_payroll = Utility::getValByName('auto_expense_payroll');

            $expenses = Expense::where('created_by', '=', Auth::user()->creatorId())->get();
            $accounts = AccountList::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('account_name', 'id');

            if ($auto_expense_payroll == 'on') {
                $expenseCategory = ExpenseType::whereIn('created_by', array(1, Auth::user()->creatorId()))->get()->pluck('name', 'id');
                $payees          = Payees::whereIn('created_by', array(1, Auth::user()->creatorId()))->get()->pluck('payee_name', 'id');
            } else {
                $expenseCategory = ExpenseType::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');
                $payees          = Payees::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('payee_name', 'id');
            }

            $paymentTypes    = PaymentType::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('expense.create', compact('expenses', 'accounts', 'expenseCategory', 'payees', 'paymentTypes'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Expense')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'account_id' => 'required',
                    'amount' => 'required',
                    'date' => 'required',
                    'expense_category_id' => 'required',
                    'payee_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $expense                      = new Expense();
            $expense->account_id          = $request->account_id;
            $expense->amount              = $request->amount;
            $expense->date                = $request->date;
            $expense->expense_category_id = $request->expense_category_id;
            $expense->payee_id            = $request->payee_id;
            $expense->payment_type_id     = $request->payment_type_id;
            $expense->referal_id          = $request->referal_id;
            $expense->description         = $request->description;
            $expense->created_by          = Auth::user()->creatorId();
            $expense->save();

            AccountList::remove_Balance($request->account_id, $request->amount);

            return redirect()->route('expense.index')->with('success', __('Expense  successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('expense.index');
    }

    public function edit(Expense $expense)
    {
        if (Auth::user()->can('Edit Expense')) {
            if ($expense->created_by == Auth::user()->creatorId()) {
                $auto_expense_payroll = Utility::getValByName('auto_expense_payroll');
                $expenses        = Expense::where('created_by', '=', Auth::user()->creatorId())->get();
                $accounts        = AccountList::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('account_name', 'id');
                if ($auto_expense_payroll == 'on') {
                    $expenseCategory = ExpenseType::whereIn('created_by', array(1, Auth::user()->creatorId()))->get()->pluck('name', 'id');
                    $payees          = Payees::whereIn('created_by', array(1, Auth::user()->creatorId()))->get()->pluck('payee_name', 'id');
                } else {
                    $expenseCategory = ExpenseType::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');
                    $payees          = Payees::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('payee_name', 'id');
                }
                $paymentTypes    = PaymentType::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');

                return view('expense.edit', compact('expense', 'accounts', 'expenseCategory', 'payees', 'paymentTypes'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Expense $expense)
    {
        if (Auth::user()->can('Edit Expense')) {
            if ($expense->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'account_id' => 'required',
                        'amount' => 'required',
                        'date' => 'required',
                        'expense_category_id' => 'required',
                        'payee_id' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }

                $expense->account_id          = $request->account_id;
                $expense->amount              = $request->amount;
                $expense->date                = $request->date;
                $expense->expense_category_id = $request->expense_category_id;
                $expense->payee_id            = $request->payee_id;
                $expense->payment_type_id     = $request->payment_type_id;
                $expense->referal_id          = $request->referal_id;
                $expense->description         = $request->description;
                $expense->save();

                return redirect()->route('expense.index')->with('success', __('Expense successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Expense $expense)
    {
        if (Auth::user()->can('Delete Expense')) {
            if ($expense->created_by == Auth::user()->creatorId()) {
                $expense->delete();

                return redirect()->route('expense.index')->with('success', __('Expense successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
    public function export()
    {
        $name = 'Expense_' . date('Y-m-d i:h:s');
        $data = Excel::download(new ExpenseExport(), $name . '.xlsx');
        ob_end_clean();

        return $data;
    }
}
