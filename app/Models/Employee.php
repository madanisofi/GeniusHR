<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'dob',
        'gender',
        'phone',
        'address',
        'email',
        'password',
        'employee_id',
        'employee_no',
        'branch_id',
        'department_id',
        'designation_id',
        'position_id',
        'employeetype_id',
        'room_id',
        'role_id',
        'group_now',
        'company_doj',
        'documents',
        'account_holder_name',
        'account_number',
        'bank_name',
        'bank_identifier_code',
        'branch_location',
        'tax_payer_id',
        'salary_type',
        'salary',
        'consumption_fee',
        'created_by',
        'education_id',
        'additionals',
        'birthplace',
    ];

    public function documents()
    {
        return $this->hasMany('App\Models\EmployeeDocument', 'employee_id', 'id')->get();
    }

    public function additional()
    {
        return $this->hasMany('App\Models\EmployeeAdditionalInformation', 'employee_id', 'id')->get();
    }

    public function salary_type()
    {
        return $this->hasOne('App\Models\PayslipType', 'id', 'salary_type')->pluck('name')->first();
    }

    public function get_net_salary($start_date = null, $end_date = null)
    {
        if ($start_date == null && $end_date == null) {
            $settings = Utility::settings();
            $payroll_date   = $settings['payroll_date'];
            $payroll_time   = $settings['payroll_time'];
            $month = date('m');
            $year  = date('Y');
            if ($payroll_time == 'first') {
                $start_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $payroll_date));
                $end_date = date('Y-m-d', strtotime('+1 month', strtotime($year . '-' . $month . '-' . $payroll_date)));
                // $end_date = date('Y-m-d', strtotime('-1 days', strtotime($end_date)));
            } else {
                $start_date = date('Y-m-d', strtotime('-1 month', strtotime($year . '-' . $month . '-' . $payroll_date)));
                // $end_date = date('Y-m-d', strtotime('-1 days', strtotime($year . '-' . $month . '-' . $payroll_date)));
                $end_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $payroll_date));
            }
        }

        //allowance
        $allowances      = Allowance::where('employee_id', '=', $this->id)->get();
        $total_allowance = 0;
        foreach ($allowances as $allowance) {
            $total_allowance = $allowance->amount + $total_allowance;
        }

        //commission
        $commissions      = Commission::whereDate('updated_at', '>=', $start_date)->whereDate('updated_at', '<=', $end_date)->where('employee_id', '=', $this->id)->get();
        $total_commission = 0;
        foreach ($commissions as $commission) {
            $total_commission = $commission->amount + $total_commission;
        }

        //Loan
        $loans      = Loan::where('employee_id', '=', $this->id)->get();
        $total_loan = 0;
        foreach ($loans as $loan) {
            $total_loan = $loan->amount + $total_loan;
        }

        //Saturation Deduction
        $saturation_deductions      = SaturationDeduction::where('employee_id', '=', $this->id)->get();
        $total_saturation_deduction = 0;
        foreach ($saturation_deductions as $saturation_deduction) {
            $total_saturation_deduction = $saturation_deduction->amount + $total_saturation_deduction;
        }

        //OtherPayment
        $other_payments      = OtherPayment::whereDate('updated_at', '>=', $start_date)->whereDate('updated_at', '<=', $end_date)->where('employee_id', '=', $this->id)->get();
        $total_other_payment = 0;
        foreach ($other_payments as $other_payment) {
            $total_other_payment = $other_payment->amount + $total_other_payment;
        }

        //Overtime
        $over_times      = Overtime::whereDate('updated_at', '>=', $start_date)->whereDate('updated_at', '<=', $end_date)->where('employee_id', '=', $this->id)->get();
        $total_over_time = 0;
        foreach ($over_times as $over_time) {
            $total_work      = $over_time->number_of_days * $over_time->hours;
            $amount          = $total_work * $over_time->rate;
            $total_over_time = $amount + $total_over_time;
        }

        // consumption
        $attendance = AttendanceEmployee::where('employee_id', $this->id)->where('status', 'Present')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->count();
        $consumption = Employee::find($this->id);
        $consumption_fee = $attendance * $consumption->consumption_fee;

        // payshift
        $payshift = DB::table('attendance_employees as ae')->selectRaw('ae.employee_id,
        COUNT(ae.shift_id) AS count_attendance,
        ae.shift_id,
        s.name,
        (SELECT amount FROM pay_shifts AS ps WHERE ps.shift_id = ae.shift_id AND ps.employee_id = ae.employee_id ) AS payment')
            ->join('shifts as s', 'ae.shift_id', '=', 's.id')
            ->where('status', '=', 'Present')
            ->where('ae.employee_id', '=', $this->id)
            ->where('ae.date', '>=', $start_date)
            ->where('ae.date', '<=', $end_date)
            ->groupBy('ae.employee_id', 'ae.shift_id')->get();

        $total_payshift = 0;
        foreach ($payshift as $payshift) {
            $total_work     = $payshift->count_attendance;
            $amount         = $total_work * $payshift->payment;
            $total_payshift = $amount + $total_payshift;
        }

        // consumption
        // $attendance = AttendanceEmployee::where('employee_id', $this->id)->where('status', 'Present')->whereYear('date', $year)->whereMonth('date', $month)->count();
        // $consumption = $attendance * $this->consumption_fee;

        //Net Salary Calculate
        $advance_salary = $total_allowance + $total_commission - $total_loan - $total_saturation_deduction - $total_other_payment + $total_over_time + $consumption_fee + $total_payshift;

        $employee       = Employee::where('id', '=', $this->id)->first();

        $net_salary     = (!empty($employee->salary) ? $employee->salary : 0) + $advance_salary;

        return $net_salary;
    }

    public static function allowance($id)
    {

        //allowance
        $allowances      = Allowance::selectRaw('*, FORMAT(amount, 0) amount_str')->where('employee_id', '=', $id)->get();
        $total_allowance = 0;
        foreach ($allowances as $allowance) {
            $total_allowance = $allowance->amount + $total_allowance;
        }

        $allowance_json = json_encode($allowances);

        return $allowance_json;
    }

    public static function commission($id, $start_date, $end_date)
    {
        //commission
        $commissions      = Commission::selectRaw('*, FORMAT(amount, 0) amount_str')
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->where('employee_id', '=', $id)->get();

        $total_commission = 0;
        foreach ($commissions as $commission) {
            $total_commission = $commission->amount + $total_commission;
        }
        $commission_json = json_encode($commissions);

        return $commission_json;
    }

    public static function loan($id)
    {
        //Loan
        $loans      = Loan::selectRaw('*, FORMAT(amount, 0) amount_str')->where('employee_id', '=', $id)->get();
        $total_loan = 0;
        foreach ($loans as $loan) {
            $total_loan = $loan->amount + $total_loan;
        }
        $loan_json = json_encode($loans);

        return $loan_json;
    }

    public static function saturation_deduction($id)
    {
        //Saturation Deduction
        $saturation_deductions      = SaturationDeduction::selectRaw('*, FORMAT(amount, 0) amount_str')->where('employee_id', '=', $id)->get();
        $total_saturation_deduction = 0;
        foreach ($saturation_deductions as $saturation_deduction) {
            $total_saturation_deduction = $saturation_deduction->amount + $total_saturation_deduction;
        }
        $saturation_deduction_json = json_encode($saturation_deductions);

        return $saturation_deduction_json;
    }

    public static function other_payment($id, $start_date, $end_date)
    {

        //OtherPayment
        $other_payments      = OtherPayment::selectRaw('*, FORMAT(amount, 0) amount_str')
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->where('employee_id', '=', $id)->get();

        $total_other_payment = 0;
        foreach ($other_payments as $other_payment) {
            $total_other_payment = $other_payment->amount + $total_other_payment;
        }
        $other_payment_json = json_encode($other_payments);

        return $other_payment_json;
    }

    public static function overtime($id, $start_date, $end_date)
    {
        //Overtime
        $over_times      = Overtime::selectRaw('*, FORMAT(rate, 0) rate_str')
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->where('employee_id', '=', $id)->get();

        $total_over_time = 0;
        foreach ($over_times as $over_time) {
            $total_work      = $over_time->number_of_days * $over_time->hours;
            $amount          = $total_work * $over_time->rate;
            $total_over_time = $amount + $total_over_time;
        }
        $over_time_json = json_encode($over_times);

        return $over_time_json;
    }

    public static function payshift($id, $start_date, $end_date)
    {

        // check payshift
        $shift = PayShift::where('employee_id', $id)->count();

        if ($shift > 0) {
            // payshift
            $payshift = DB::table('attendance_employees as ae')->selectRaw('ae.employee_id, COUNT(ae.shift_id) AS count_attendance, ae.shift_id, s.name,
            (SELECT amount FROM pay_shifts AS ps WHERE ps.shift_id = ae.shift_id AND ps.employee_id = ae.employee_id ) AS payment,
            (SELECT (amount*COUNT(ae.shift_id)) FROM pay_shifts AS ps WHERE ps.shift_id = ae.shift_id AND ps.employee_id = ae.employee_id ) AS payment_total,
            (SELECT FORMAT((amount*COUNT(ae.shift_id)),0) FROM pay_shifts AS ps WHERE ps.shift_id = ae.shift_id AND ps.employee_id = ae.employee_id ) as payment_str')
                ->join('shifts as s', 'ae.shift_id', '=', 's.id')
                ->where('status', '=', 'Present')
                ->where('ae.employee_id', '=', $id);

            $payshift->whereBetween(
                'ae.date',
                [
                    $start_date,
                    $end_date,
                ]
            );

            $payshift = $payshift->groupBy('ae.employee_id', 'ae.shift_id')->get();

            return json_encode($payshift);
        } else {
            return json_encode([]);
        }
    }

    public static function getCountPresentAttendance($id, $start_date, $end_date)
    {
        $countPresent = AttendanceEmployee::selectRaw('id, employee_id, date, permissiontype_id, created_by, status')
            ->where('employee_id', $id);

        $countPresent->whereBetween(
            'date',
            [
                $start_date,
                $end_date,
            ]
        );

        $countPresent = $countPresent->where('status', 'Present')
            ->groupBy('date')->get();

        $count = 0;

        if (!empty($countPresent)) {
            foreach ($countPresent as $key => $value) {
                if (empty($value->permission)) $count += 1;
                else {
                    if ($value->permission->get_consumption_fee == 'yes') $count += 1;
                }
            }
        }

        return $count;
    }

    public static function getconsumption($id)
    {
        $consumption_fee = Employee::find($id)->consumption_fee;

        return $consumption_fee;
    }

    public static function getattendance($id)
    {
        $payslip = Payslip::find($id);
        $explode = explode('-', $payslip->salary_month);
        $year = $explode[0];
        $month = $explode[1];

        $attendance = AttendanceEmployee::where('employee_id', $payslip->employee_id)->where('status', 'Present')->whereYear('date', $year)->whereMonth('date', $month)->count();

        return $attendance;
    }

    public static function employee_id()
    {
        $employee = Employee::latest()->first();

        return !empty($employee) ? $employee->id + 1 : 1;
    }

    public function branch()
    {
        return $this->hasOne('App\Models\Branch', 'id', 'branch_id')->withDefault();
    }

    public function phone()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'phone')->withDefault();
    }

    public function department()
    {
        return $this->hasOne('App\Models\Department', 'id', 'department_id')->withDefault();
    }

    public function designation()
    {
        return $this->hasOne('App\Models\Designation', 'id', 'designation_id')->withDefault();
    }

    public function education()
    {
        return $this->hasOne('App\Models\Educations', 'id', 'education_id')->withDefault();
    }

    public function salaryType()
    {
        return $this->hasOne('App\Models\PayslipType', 'id', 'salary_type')->withDefault();
    }

    public function group()
    {
        return $this->hasOne('App\Models\Group', 'id', 'group_now')->withDefault();
    }

    public function role()
    {
        return $this->hasOne('Spatie\Permission\Models\Role', 'id', 'role_id')->withDefault();
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->withDefault();
    }

    public function paySlip()
    {
        return $this->hasOne('App\Models\PaySlip', 'id', 'employee_id')->withDefault();
    }

    public function position()
    {
        return $this->hasOne('App\Models\Position', 'id', 'position_id')->withDefault();
    }

    public function employeetype()
    {
        return $this->hasOne('App\Models\EmployeeType', 'id', 'employeetype_id')->withDefault();
    }

    public function present_status($employee_id, $data)
    {
        return AttendanceEmployee::where('employee_id', $employee_id)->where('date', $data)->first();
    }
    public static function employee_name($name)
    {

        $employee = Employee::where('id', $name)->first();
        if (!empty($employee)) {
            return $employee->name;
        }
    }

    public static function getType($id)
    {
        $employee = Employee::selectRaw('u.id, u.type, u.role_id')->join('users as u', 'employees.user_id', '=', 'u.id')->where('employees.id', $id)->first();
        return $employee;
    }


    public static function login_user($name)
    {
        $user = User::where('id', $name)->first();
        return $user->name;
    }

    public static function employee_salary($salary)
    {
        $employee = Employee::where("salary", $salary)->first();
        if ($employee->salary == '0' || $employee->salary == '0.0') {
            return "-";
        } else {
            return $employee->salary;
        }
    }
}
