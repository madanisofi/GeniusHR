<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use App\Models\AllowanceOption;
use App\Models\Commission;
use App\Models\DeductionOption;
use App\Models\Employee;
use App\Models\Loan;
use App\Models\LoanOption;
use App\Models\OtherPayment;
use App\Models\Overtime;
use App\Models\PayslipType;
use App\Models\SaturationDeduction;
use App\Models\Utility;
use App\Models\PayShift;
use App\Models\Counting;
use App\Models\PositionGroup;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;

class SetSalaryController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Set Salary')) {
            $employees = Employee::where(
                [
                    'created_by' => Auth::user()->creatorId(),
                ]
            )->get();

            $settings = Utility::settings();

            return view('setsalary.index', compact('employees', 'settings'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        $counting = new Counting();

        $month = date('m');
        $year  = date('Y');

        $employees = Employee::where('created_by', Auth::user()->creatorId())->where('company_doj', '<=', date($year . '-' . $month . '-t'))->get();
        $settings = Utility::settings();
        if ($settings['automatic_basic_salary'] == 'on') {
            // validate group increase
            foreach ($employees as $x) {
                $position = PositionGroup::where('position_id', 'LIKE', '%' . $x->position_id . '%')->where('created_by', Auth::user()->creatorId())->first();

                if (isset($position)) {

                    // get group
                    $emp_group = json_decode($position->group_id);
                    // max group
                    $max_emp_group = count($emp_group);
                    // get emp type
                    $emp_type = $x->employeetype_id;
                    // get masa kerja
                    $diff = \Carbon\Carbon::parse($x->company_doj)->diff(now()); #$diff->y
                    // get kenaikan golongan
                    $ci = $counting->getClassIncrease();

                    // cek golongan
                    $validate_group = floor($diff->y / $ci);
                    if ($validate_group >= $max_emp_group) {
                        $group = $max_emp_group - 1; #array start from 0
                    } else {
                        $group = $validate_group;
                    }
                    $group_id = $emp_group[$group];

                    // get kenaikan masa kerja
                    $yos = $counting->getYearOfService();
                    // rumus kenaikan masa kerja
                    $formula_y = $counting->getFormulaY();
                    // get salary
                    $salary = $counting->getSalaryByGroup($group_id, $emp_type);
                    $start_year_of_group = $salary['start_year'];

                    $validate_max_year_of_group = $diff->y;

                    $year_service = floor((($validate_max_year_of_group - $start_year_of_group) / $yos));
                    if ($year_service <= 0) {
                        $final_salary = $salary['salary'];
                    } else {
                        $final_salary = $counting->getFinalSalary($salary['salary'], $formula_y, $year_service);
                    }

                    // save golongan in emp
                    if ($final_salary != $x->salary) {
                        Employee::find($x->id)->update(array(
                            'group_now' => $group_id,
                            'salary' => round($final_salary)
                        ));
                    }
                }
            }
        }

        return redirect()->route('setsalary.index')->with('success', 'Generate Success.');
    }

    public function edit($id)
    {
        if (Auth::user()->can('Edit Set Salary')) {

            $payslip_type      = PayslipType::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $allowance_options = AllowanceOption::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $loan_options      = LoanOption::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $deduction_options = DeductionOption::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            if (Auth::user()->type == 'employee') {
                $currentEmployee      = Employee::where('user_id', '=', Auth::user()->id)->first();
                $allowances           = Allowance::where('employee_id', $currentEmployee->id)->get();
                $commissions          = Commission::whereYear('updated_at', '=', date('Y'))->whereMonth('updated_at', '=', date('m'))->where('employee_id', $currentEmployee->id)->get();
                $loans                = Loan::where('employee_id', $currentEmployee->id)->get();
                $saturationdeductions = SaturationDeduction::where('employee_id', $currentEmployee->id)->get();
                $otherpayments        = OtherPayment::whereYear('updated_at', '=', date('Y'))->whereMonth('updated_at', '=', date('m'))->where('employee_id', $currentEmployee->id)->get();
                $overtimes            = Overtime::whereYear('updated_at', '=', date('Y'))->whereMonth('updated_at', '=', date('m'))->where('employee_id', $currentEmployee->id)->get();
                $employee             = Employee::where('user_id', '=', Auth::user()->id)->first();

                return view('setsalary.employee_salary', compact('employee', 'payslip_type', 'allowance_options', 'commissions', 'loan_options', 'overtimes', 'otherpayments', 'saturationdeductions', 'loans', 'deduction_options', 'allowances'));
            } else {
                $allowances           = Allowance::where('employee_id', $id)->get();
                $commissions          = Commission::whereYear('updated_at', '=', date('Y'))->whereMonth('updated_at', '=', date('m'))->where('employee_id', $id)->get();
                $loans                = Loan::where('employee_id', $id)->get();
                $saturationdeductions = SaturationDeduction::where('employee_id', $id)->get();
                $otherpayments        = OtherPayment::whereYear('updated_at', '=', date('Y'))->whereMonth('updated_at', '=', date('m'))->where('employee_id', $id)->get();
                $overtimes            = Overtime::whereYear('updated_at', '=', date('Y'))->whereMonth('updated_at', '=', date('m'))->where('employee_id', $id)->get();
                $employee             = Employee::find($id);

                return view('setsalary.edit', compact('employee', 'payslip_type', 'allowance_options', 'commissions', 'loan_options', 'overtimes', 'otherpayments', 'saturationdeductions', 'loans', 'deduction_options', 'allowances'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show($id)
    {


        $payslip_type      = PayslipType::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
        $allowance_options = AllowanceOption::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
        $loan_options      = LoanOption::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
        $deduction_options = DeductionOption::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
        $settings = Utility::settings();

        $payroll_date   = $settings['payroll_date'];
        $payroll_time   = $settings['payroll_time'];
        $month_start_date = $settings['month_start_date'];
        $month = date('m');
        $year  = date('Y');

        if ($payroll_time == 'first') {
            $start_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $month_start_date));
            $end_date = date('Y-m-d', strtotime('+1 month', strtotime($year . '-' . $month . '-' . $payroll_date)));
            $end_date = date('Y-m-d', strtotime('-1 days', strtotime($end_date)));
        } else {
            if (date('d') >= $payroll_date) {
                $start_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $month_start_date));
                $end_date = date('Y-m-d', strtotime('+1 month', strtotime($year . '-' . $month . '-' . $payroll_date)));
                $end_date = date('Y-m-d', strtotime('-1 days', strtotime($end_date)));
            } else {
                $start_date = date('Y-m-d', strtotime('-1 month', strtotime($year . '-' . $month . '-' . $month_start_date)));
                $end_date = date('Y-m-d', strtotime('-1 days', strtotime($year . '-' . $month . '-' . $payroll_date)));
            }
        }

        if (Auth::user()->type == 'employee') {
            $currentEmployee      = Employee::where('user_id', '=', Auth::user()->id)->first();
            $allowances           = Allowance::where('employee_id', $currentEmployee->id)->get();
            $commissions          = Commission::whereDate('updated_at', '>=', $start_date)->whereDate('updated_at', '<=', $end_date)->where('employee_id', $currentEmployee->id)->get();
            $loans                = Loan::where('employee_id', $currentEmployee->id)->get();
            $saturationdeductions = SaturationDeduction::where('employee_id', $currentEmployee->id)->get();
            $otherpayments        = OtherPayment::whereDate('updated_at', '>=', $start_date)->whereDate('updated_at', '<=', $end_date)->where('employee_id', $currentEmployee->id)->get();
            $overtimes            = Overtime::whereDate('updated_at', '>=', $start_date)->whereDate('updated_at', '<=', $end_date)->where('employee_id', $currentEmployee->id)->get();
            $payshift             = PayShift::where('employee_id', $currentEmployee->id)->get();
            $employee             = Employee::where('user_id', '=', Auth::user()->id)->first();

            return view('setsalary.employee_salary', compact('employee', 'payslip_type', 'allowance_options', 'commissions', 'loan_options', 'overtimes', 'otherpayments', 'saturationdeductions', 'loans', 'deduction_options', 'allowances', 'settings', 'payshift'));
        } else {

            // Check if payslip type is empty
            $payslip_type_checker = PayslipType::where('created_by', Auth::user()->creatorId())->get();

            if ($payslip_type_checker->isEmpty()) {
                return redirect()->route('payslip_type.index')->with('error', __('Please create payslip type first.'));
            } else {
                $allowances           = Allowance::where('employee_id', $id)->get();
                $commissions          = Commission::whereDate('updated_at', '>=', $start_date)->whereDate('updated_at', '<=', $end_date)->where('employee_id', $id)->get();
                $loans                = Loan::where('employee_id', $id)->get();
                $saturationdeductions = SaturationDeduction::where('employee_id', $id)->get();
                $otherpayments        = OtherPayment::whereDate('updated_at', '>=', $start_date)->whereDate('updated_at', '<=', $end_date)->where('employee_id', $id)->get();
                $overtimes            = Overtime::whereDate('updated_at', '>=', $start_date)->whereDate('updated_at', '<=', $end_date)->where('employee_id', $id)->get();
                $payshift             = PayShift::where('employee_id', $id)->get();
                $employee             = Employee::find($id);

                return view('setsalary.employee_salary', compact('employee', 'payslip_type', 'allowance_options', 'commissions', 'loan_options', 'overtimes', 'otherpayments', 'saturationdeductions', 'loans', 'deduction_options', 'allowances', 'settings', 'payshift'));
            }
        }
    }


    public function employeeUpdateSalary(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'salary_type' => 'required',
                'salary' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return back()->with('error', $messages->first());
        }
        $employee = Employee::findOrFail($id);
        $input    = $request->all();
        $employee->fill($input)->save();

        return back()->with('success', 'Employee Salary Updated.');
    }

    public function employeeSalary()
    {
        if (Auth::user()->type == "employee") {
            $employees = Employee::where('user_id', Auth::user()->id)->get();

            return view('setsalary.index', compact('employees'));
        }
    }

    public function employeeBasicSalary($id)
    {

        $payslip_type = PayslipType::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
        $employee     = Employee::find($id);

        return view('setsalary.basic_salary', compact('employee', 'payslip_type'));
    }

    public function employeeConsumptionFee($id)
    {

        $payslip_type = PayslipType::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
        $employee     = Employee::find($id);

        return view('setsalary.consumption_fee', compact('employee', 'payslip_type'));
    }

    public function employeeUpdateConsumption(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'consumption_fee' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return back()->with('error', $messages->first());
        }
        $employee = Employee::findOrFail($id);
        $input    = $request->all();
        $employee->fill($input)->save();

        return back()->with('success', 'Employee Consumption Fee Updated.');
    }
}
