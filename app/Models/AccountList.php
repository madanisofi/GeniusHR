<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountList extends Model
{
    protected $fillable = [
        'company_id',
        'account_name',
        'initial_balance',
        'account_number',
        'branch_code',
        'bank_branch',
        'auto_payroll',
        'created_by',
    ];

    public static function add_Balance($id, $amount)
    {
        $accountBalance = \App\Models\AccountList::where('id', '=', $id)->first();
        $accountBalance->initial_balance = $amount + $accountBalance->initial_balance;
        $accountBalance->save();
    }
    public static function remove_Balance($id, $amount)
    {
        $accountBalance = \App\Models\AccountList::where('id', '=', $id)->first();
        $accountBalance->initial_balance =  $accountBalance->initial_balance - $amount;
        $accountBalance->save();
    }

    public static function transfer_Balance($from_account, $to_account, $amount)
    {
        $fromAccount = \App\Models\AccountList::where('id', '=', $from_account)->first();
        $fromAccount->initial_balance = $fromAccount->initial_balance - $amount;
        $fromAccount->save();
        $toAccount = \App\Models\AccountList::where('id', '=', $to_account)->first();
        $toAccount->initial_balance = $toAccount->initial_balance + $amount;
        $toAccount->save();
    }

    public static function get_account_auto_payroll($amount)
    {
        $account_auto_payroll = AccountList::where('initial_balance', '>=', $amount)
            ->where('auto_payroll', '=', 'on')
            ->limit(1)->first();

        if ($account_auto_payroll) {
            return $account_auto_payroll;
        } else {
            return AccountList::where('auto_payroll', '=', 'on')->limit(1)->first();
        }
    }
}
