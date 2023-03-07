<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\LoanOption;
use App\Models\User;
use App\Models\PaySlip;
use App\Models\DeductionOption;
use App\Models\AllowanceOption;

class PayslipController extends Controller
{
    public function salary(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'year' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {

            $emp = Employee::where('user_id', '=', $request->user_id)->first();

            // last salary
            $payslip = PaySlip::where('employee_id', $emp->id)->where('status', 1)->where('salary_month', 'like', '%' . $request->year . '%')->orderBy('created_at', 'DESC')->get();

            if (empty($payslip)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {
                $data = [];
                foreach ($payslip as $key => $value) {
                    $data[] = [
                        'id' => $value->id,
                        'employee_id' => $value->employee_id,
                        'nat_payble' => number_format($value->net_payble),
                        'salary_month' => $value->salary_month,
                        'status' => $value->status,
                        'basic_salary' => number_format($value->basic_salary),
                        'comsumption_fee' => number_format($value->consumption_fee),
                        'allowance_total' => number_format(array_sum(array_column(json_decode($value->allowance), 'amount'))),
                        'allowance' => json_decode($value->allowance),

                        'commission_total' => number_format(array_sum(array_column(json_decode($value->commission), 'amount'))),
                        'commission' => json_decode($value->commission),

                        'loan_total' => number_format(array_sum(array_column(json_decode($value->loan), 'amount'))),
                        'loan' => json_decode($value->loan),

                        'saturation_deduction_total' => number_format(array_sum(array_column(json_decode($value->saturation_deduction), 'amount'))),
                        'saturation_deduction' => json_decode($value->saturation_deduction),

                        'other_payment_total' => number_format(array_sum(array_column(json_decode($value->other_payment), 'amount'))),
                        'other_payment' => json_decode($value->other_payment),

                        'overtime_total' => number_format(array_sum(array_column(json_decode($value->overtime), 'rate'))),
                        'overtime' => json_decode($value->overtime),

                        'payshift_total' => number_format(array_sum(array_column(json_decode($value->payshift), 'payment_total'))),
                        'payshift' => json_decode($value->payshift),
                        'created_by' => $value->created_by
                    ];
                }
                return response()->json([
                    'type' => 'success',
                    'message' => 'available',
                    'data' => $data
                ]);
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }
    public function employeeSalary(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'payslip_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {
            $emp = Employee::where('user_id', '=', $request->user_id)->first();

            // last salary
            $payslip = PaySlip::where('employee_id', $emp->id)->where('id', $request->payslip_id)->first();

            if (empty($payslip)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {
                return response()->json([
                    'type' => 'success',
                    'message' => 'available',
                    'data' => [
                        'id' => $payslip->id,
                        'employee_id' => $payslip->employee_id,
                        'nat_payble' => number_format($payslip->net_payble),
                        'salary_month' => $payslip->salary_month,
                        'status' => $payslip->status,
                        'basic_salary' => number_format($payslip->basic_salary),
                        'comsumption_fee' => number_format($payslip->consumption_fee),
                        'allowance_total' => number_format(array_sum(array_column(json_decode($payslip->allowance), 'amount'))),
                        'allowance' => json_decode($payslip->allowance),

                        'commission_total' => number_format(array_sum(array_column(json_decode($payslip->commission), 'amount'))),
                        'commission' => json_decode($payslip->commission),

                        'loan_total' => number_format(array_sum(array_column(json_decode($payslip->loan), 'amount'))),
                        'loan' => json_decode($payslip->loan),

                        'saturation_deduction_total' => number_format(array_sum(array_column(json_decode($payslip->saturation_deduction), 'amount'))),
                        'saturation_deduction' => json_decode($payslip->saturation_deduction),

                        'other_payment_total' => number_format(array_sum(array_column(json_decode($payslip->other_payment), 'amount'))),
                        'other_payment' => json_decode($payslip->other_payment),

                        'overtime_total' => number_format(array_sum(array_column(json_decode($payslip->overtime), 'rate'))),
                        'overtime' => json_decode($payslip->overtime),

                        'payshift_total' => number_format(array_sum(array_column(json_decode($payslip->payshift), 'payment_total'))),
                        'payshift' => json_decode($payslip->payshift),
                        'created_by' => $payslip->created_by
                    ]
                ]);
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }

    public function commissionSalary(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'payslip_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {

            $emp = Employee::where('user_id', '=', $request->user_id)->first();

            // last salary
            $payslip = PaySlip::where('employee_id', $emp->id)->where('id', $request->payslip_id)->first();

            if (empty($payslip)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {
                $commission = json_decode($payslip->commission);

                if ($commission != null) {

                    foreach (json_decode($payslip->commission) as $key => $value) {
                        $tmp = [];
                        $tmp['id'] = $value->id;
                        $tmp['employee_id'] = $value->employee_id;
                        $tmp['title'] = $value->title;
                        $tmp['amount'] = $value->amount;
                        $tmp['created_by'] = $value->created_by;
                        $data[] = $tmp;
                    }

                    return response()->json([
                        'type' => 'success',
                        'message' => 'available',
                        'data' => $data
                    ]);
                } else {
                    return response()->json([
                        'type' => 'success',
                        'message' => 'empty',
                        'data' => []
                    ]);
                }
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }

    public function allowanceSalary(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'payslip_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {

            $emp = Employee::where('user_id', '=', $request->user_id)->first();

            // last salary
            $payslip = PaySlip::where('employee_id', $emp->id)->where('id', $request->payslip_id)->first();

            if (empty($payslip)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {

                $allowance = json_decode($payslip->allowance);

                if ($allowance != null) {
                    foreach (json_decode($payslip->allowance) as $key => $value) {
                        $tmp = [];
                        $tmp['id'] = $value->id;
                        $tmp['employee_id'] = $value->employee_id;
                        $tmp['title'] = $value->title;
                        $tmp['allowance_option'] = AllowanceOption::find($value->allowance_option)->name;
                        $tmp['amount'] = $value->amount;
                        $tmp['created_by'] = $value->created_by;
                        $data[] = $tmp;
                    }

                    return response()->json([
                        'type' => 'success',
                        'message' => 'available',
                        'data' => $data
                    ]);
                } else {
                    return response()->json([
                        'type' => 'success',
                        'message' => 'empty',
                        'data' => []
                    ]);
                }
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }

    public function overtimeSalary(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'payslip_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {

            $emp = Employee::where('user_id', '=', $request->user_id)->first();

            // last salary
            $payslip = PaySlip::where('employee_id', $emp->id)->where('id', $request->payslip_id)->first();

            if (empty($payslip)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {

                $overtime = json_decode($payslip->overtime);

                if ($overtime != null) {
                    foreach (json_decode($payslip->overtime) as $key => $value) {
                        $tmp = [];
                        $tmp['id'] = $value->id;
                        $tmp['employee_id'] = $value->employee_id;
                        $tmp['title'] = $value->title;
                        $tmp['number_of_days'] = $value->number_of_days;
                        $tmp['hours'] = $value->hours;
                        $tmp['rate'] = $value->rate;
                        $tmp['created_by'] = $value->created_by;
                        $data[] = $tmp;
                    }

                    return response()->json([
                        'type' => 'success',
                        'message' => 'available',
                        'data' => $data
                    ]);
                } else {
                    return response()->json([
                        'type' => 'success',
                        'message' => 'empty',
                        'data' => []
                    ]);
                }
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }

    public function loanSalary(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'payslip_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {

            $emp = Employee::where('user_id', '=', $request->user_id)->first();

            // last salary
            $payslip = PaySlip::where('employee_id', $emp->id)->where('id', $request->payslip_id)->first();

            if (empty($payslip)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {

                $loan = json_decode($payslip->loan);

                if ($loan != null) {
                    foreach (json_decode($payslip->loan) as $key => $value) {
                        $tmp = [];
                        $tmp['id'] = $value->id;
                        $tmp['employee_id'] = $value->employee_id;
                        $tmp['loan_option'] = LoanOption::find($value->loan_option)->name;
                        $tmp['title'] = $value->title;
                        $tmp['amount'] = $value->amount;
                        $tmp['reason'] = $value->reason;
                        $tmp['start_date'] = $value->start_date;
                        $tmp['end_date'] = $value->end_date;
                        $tmp['created_by'] = $value->created_by;
                        $data[] = $tmp;
                    }

                    return response()->json([
                        'type' => 'success',
                        'message' => 'available',
                        'data' => $data
                    ]);
                } else {
                    return response()->json([
                        'type' => 'success',
                        'message' => 'empty',
                        'data' => []
                    ]);
                }
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }

    public function saturationSalary(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'payslip_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {

            $emp = Employee::where('user_id', '=', $request->user_id)->first();

            // last salary
            $payslip = PaySlip::where('employee_id', $emp->id)->where('id', $request->payslip_id)->first();

            if (empty($payslip)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {

                $saturation_deduction = json_decode($payslip->saturation_deduction);

                if ($saturation_deduction != null) {
                    foreach (json_decode($payslip->saturation_deduction) as $key => $value) {
                        $tmp = [];
                        $tmp['id'] = $value->id;
                        $tmp['employee_id'] = $value->employee_id;
                        $tmp['deduction_option'] = DeductionOption::find($value->deduction_option)->name;
                        $tmp['title'] = $value->title;
                        $tmp['amount'] = $value->amount;
                        $tmp['created_by'] = $value->created_by;
                        $data[] = $tmp;
                    }

                    return response()->json([
                        'type' => 'success',
                        'message' => 'available',
                        'data' => $data
                    ]);
                } else {
                    return response()->json([
                        'type' => 'success',
                        'message' => 'empty',
                        'data' => []
                    ]);
                }
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }

    public function otherSalary(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'payslip_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!empty($user)) {

            $emp = Employee::where('user_id', '=', $request->user_id)->first();

            // last salary
            $payslip = PaySlip::where('employee_id', $emp->id)->where('id', $request->payslip_id)->first();

            if (empty($payslip)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {
                $other_payment = json_decode($payslip->other_payment);

                if ($other_payment != null) {

                    foreach (json_decode($payslip->other_payment) as $key => $value) {
                        $tmp = [];
                        $tmp['id'] = $value->id;
                        $tmp['employee_id'] = $value->employee_id;
                        $tmp['title'] = $value->title;
                        $tmp['amount'] = $value->amount;
                        $tmp['created_by'] = $value->created_by;
                        $data[] = $tmp;
                    }

                    return response()->json([
                        'type' => 'success',
                        'message' => 'available',
                        'data' => $data
                    ]);
                } else {
                    return response()->json([
                        'type' => 'success',
                        'message' => 'empty',
                        'data' => []
                    ]);
                }
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }
}
