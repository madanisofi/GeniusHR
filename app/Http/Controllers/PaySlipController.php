<?php

namespace App\Http\Controllers;

use App\Helpers\Fcm;
use App\Models\Allowance;
use App\Models\Commission;
use App\Models\Employee;
use App\Models\Loan;
use App\Models\Leave;
use App\Mail\PayslipSend;
use App\Models\OtherPayment;
use App\Models\Overtime;
use App\Models\PaySlip;
use App\Models\SaturationDeduction;
use App\Models\Utility;
use App\Models\AttendanceEmployee;
use App\Models\PayShift;
use App\Models\AccountList;
use App\Models\Expense;
use App\Models\Notification;
use App\Models\NotificationEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PaySlipController extends Controller
{

    public function index()
    {
        if (Auth::user()->can('Manage Pay Slip') || Auth::user()->type == 'employee') {
            $employees = Employee::where(
                [
                    'created_by' => Auth::user()->creatorId(),
                ]
            )->first();

            $month = [
                '01' => 'JAN',
                '02' => 'FEB',
                '03' => 'MAR',
                '04' => 'APR',
                '05' => 'MAY',
                '06' => 'JUN',
                '07' => 'JUL',
                '08' => 'AUG',
                '09' => 'SEP',
                '10' => 'OCT',
                '11' => 'NOV',
                '12' => 'DEC',
            ];

            $year = [
                '2022' => '2022',
                '2023' => '2023',
                '2024' => '2024',
                '2025' => '2025',
                '2026' => '2026',
                '2027' => '2027',
                '2028' => '2028',
                '2029' => '2029',
                '2030' => '2030',
            ];

            $shift = Utility::getValByName('shift');

            return view('payslip.index', compact('employees', 'month', 'year', 'shift'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'month' => 'required',
                'year' => 'required',

            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return back()->with('error', $messages->first());
        }

        $month = $request->month;
        $year  = $request->year;

        $settings = Utility::settings();

        $payroll_date   = $settings['payroll_date'];
        $payroll_time   = $settings['payroll_time'];
        $month_start_date = $settings['month_start_date'];
        if ($payroll_time == 'first') {
            $start_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $month_start_date));
            $end_date = date('Y-m-d', strtotime('+1 month', strtotime($year . '-' . $month . '-' . $payroll_date)));
            $end_date = date('Y-m-d', strtotime('-1 days', strtotime($end_date)));
        } else {
            $start_date = date('Y-m-d', strtotime('-1 month', strtotime($year . '-' . $month . '-' . $month_start_date)));
            $end_date = date('Y-m-d', strtotime('-1 days', strtotime($year . '-' . $month . '-' . $payroll_date)));
        }

        $mod_emp = new Employee();

        $formate_month_year = $year . '-' . $month;
        $validatePaysilp    = PaySlip::where('salary_month', '=', $formate_month_year)->where('created_by', Auth::user()->creatorId())->pluck('employee_id');
        $payslip_employee   = Employee::where('created_by', Auth::user()->creatorId())->where('company_doj', '<=', date($year . '-' . $month . '-t'))->count();

        if ($payslip_employee > count($validatePaysilp)) {
            $employees = Employee::where('created_by', Auth::user()->creatorId())->where('company_doj', '<=', date($year . '-' . $month . '-t'))->whereNotIn('employee_id', $validatePaysilp)->get();

            $employeesSalary = Employee::where('created_by', Auth::user()->creatorId())->where('salary', '<=', 0)->first();

            if (!empty($employeesSalary)) {
                return redirect()->route('payslip.index')->with('error', __('Please set employee salary.'));
            }

            if ($settings['late_fee_calculation'] == 'on') {
                $this->setFinesAndRewards($month, $year);
            }

            $mod_emp = new Employee();

            foreach ($employees as $employee) {

                $diff = \Carbon\Carbon::parse($employee->company_doj)->diff(now()); #$diff->y

                $payslipEmployee                       = new PaySlip();
                $payslipEmployee->employee_id          = $employee->id;
                $payslipEmployee->net_payble           = $employee->get_net_salary($start_date, $end_date);
                $payslipEmployee->salary_month         = $formate_month_year;
                $payslipEmployee->status               = 0;
                $payslipEmployee->basic_salary         = !empty($employee->salary) ? $employee->salary : 0;
                $payslipEmployee->consumption_fee      = $employee->consumption_fee * $mod_emp->getCountPresentAttendance($employee->id, $start_date, $end_date);
                $payslipEmployee->allowance            = $mod_emp->allowance($employee->id);
                $payslipEmployee->commission           = $mod_emp->commission($employee->id, $start_date, $end_date);
                $payslipEmployee->loan                 = $mod_emp->loan($employee->id);
                $payslipEmployee->saturation_deduction = $mod_emp->saturation_deduction($employee->id);
                $payslipEmployee->other_payment        = $mod_emp->other_payment($employee->id, $start_date, $end_date);
                $payslipEmployee->overtime             = $mod_emp->overtime($employee->id, $start_date, $end_date);
                $payslipEmployee->payshift             = $mod_emp->payshift($employee->id, $start_date, $end_date);
                $payslipEmployee->group_id             = $employee->group_now;
                $payslipEmployee->year_service         = $diff->y;
                $payslipEmployee->created_by           = Auth::user()->creatorId();

                $payslipEmployee->save();

                // slack
                $setting = Utility::settings(Auth::user()->creatorId());
                $month = date('M Y', strtotime($payslipEmployee->salary_month . ' ' . $payslipEmployee->time));
                if (isset($setting['monthly_payslip_notification']) && $setting['monthly_payslip_notification'] == 1) {
                    $msg = ("payslip generated of") . ' ' . $month . '.';
                    Utility::send_slack_msg($msg);
                }

                // telegram
                $setting = Utility::settings(Auth::user()->creatorId());
                $month = date('M Y', strtotime($payslipEmployee->salary_month . ' ' . $payslipEmployee->time));
                if (isset($setting['telegram_monthly_payslip_notification']) && $setting['telegram_monthly_payslip_notification'] == 1) {
                    $msg = ("payslip generated of") . ' ' . $month . '.';
                    Utility::send_telegram_msg($msg);
                }

                // twilio
                $setting  = Utility::settings(Auth::user()->creatorId());
                $emp = Employee::where('id', $payslipEmployee->employee_id = Auth::user()->id)->first();
                if (isset($setting['twilio_payslip_notification']) && $setting['twilio_payslip_notification'] == 1) {
                    $employeess = Employee::where($request->employee_id)->get();
                    foreach ($employeess as $key => $employee) {
                        $msg = ("payslip generated of") . ' ' . $month . '.';

                        Utility::send_twilio_msg($emp->phone, $msg);
                    }
                }
            }

            return redirect()->route('payslip.index')->with('success', __('Payslip successfully created.'));
        } else {
            return redirect()->route('payslip.index')->with('error', __('Payslip Already created.'));
        }
    }

    public function destroy($id)
    {
        $payslip = PaySlip::find($id);
        $payslip->delete();

        return true;
    }

    public function showemployee($paySlip)
    {
        $payslip = PaySlip::find($paySlip);

        $attendance = Employee::getattendance($payslip->id);
        $consumption_emp = Employee::getconsumption($payslip->employee_id);

        return view('payslip.show', compact('payslip', 'attendance', 'consumption_emp'));
    }


    public function search_json(Request $request)
    {

        $formate_month_year = $request->datePicker;
        $validatePaysilp    = PaySlip::where('salary_month', '=', $formate_month_year)->where('created_by', Auth::user()->creatorId())->get()->toarray();

        $shift = Utility::getValByName('shift');

        if (empty($validatePaysilp)) {
            return;
        } else {
            $paylip_employee = PaySlip::select(
                [
                    'employees.id',
                    'employees.employee_id',
                    'employees.name',
                    'payslip_types.name as payroll_type',
                    'pay_slips.basic_salary',
                    'pay_slips.net_payble',
                    'pay_slips.id as pay_slip_id',
                    'pay_slips.status',
                    'employees.user_id',
                    'groups.name as group_name',
                    'pay_slips.year_service'
                ]
            )->leftjoin(
                'employees',
                function ($join) use ($formate_month_year) {
                    $join->on('employees.id', '=', 'pay_slips.employee_id');
                    $join->on('pay_slips.salary_month', '=', \DB::raw("'" . $formate_month_year . "'"));
                    $join->leftjoin('payslip_types', 'payslip_types.id', '=', 'employees.salary_type');
                }
            )->leftjoin(
                'groups',
                function ($join) {
                    $join->on('pay_slips.group_id', '=', 'groups.id');
                }
            )->where('employees.created_by', Auth::user()->creatorId())->get();


            foreach ($paylip_employee as $employee) {

                if (Auth::user()->type == 'employee') {
                    if (Auth::user()->id == $employee->user_id) {
                        $tmp   = [];
                        $tmp[] = $employee->id;
                        $tmp[] = $employee->name;
                        if ($shift == 'on') {
                            $tmp[] = $employee->group_name . ' - ' . $employee->year_service . ' ' . ($employee->year_service != null ? __('Year') : '');
                        } else {
                            $tmp[] = $employee->payroll_type;
                        }
                        $tmp[] = $employee->pay_slip_id;
                        $tmp[] = !empty($employee->basic_salary) ? Auth::user()->priceFormat($employee->basic_salary) : '-';
                        $tmp[] = !empty($employee->net_payble) ? Auth::user()->priceFormat($employee->net_payble) : '-';
                        if ($employee->status == 1) {
                            $tmp[] = 'paid';
                        } else {
                            $tmp[] = 'unpaid';
                        }
                        $tmp[]  = !empty($employee->pay_slip_id) ? $employee->pay_slip_id : 0;
                        $data[] = $tmp;
                    }
                } else {

                    $tmp   = [];
                    $tmp[] = $employee->id;
                    $tmp[] = Auth::user()->employeeIdFormat($employee->employee_id);
                    $tmp[] = $employee->name;
                    if ($shift == 'on') {
                        $tmp[] = $employee->group_name . ' - ' . $employee->year_service . ' ' . ($employee->year_service != null ? __('Year') : '');
                    } else {
                        $tmp[] = $employee->payroll_type;
                    }
                    $tmp[] = !empty($employee->basic_salary) ? Auth::user()->priceFormat($employee->basic_salary) : '-';
                    $tmp[] = !empty($employee->net_payble) ? Auth::user()->priceFormat($employee->net_payble) : '-';
                    if ($employee->status == 1) {
                        $tmp[] = 'Paid';
                    } else {
                        $tmp[] = 'UnPaid';
                    }
                    $tmp[]  = !empty($employee->pay_slip_id) ? $employee->pay_slip_id : 0;
                    $data[] = $tmp;
                }
            }

            return $data;
        }
    }

    public function paysalary($id, $date)
    {
        $employeePayslip = PaySlip::where('employee_id', '=', $id)->where('created_by', Auth::user()->creatorId())->where('salary_month', '=', $date)->first();
        if (!empty($employeePayslip)) {
            $employeePayslip->status = 1;
            $employeePayslip->save();

            $auto_expense_payroll = Utility::getValByName('auto_expense_payroll');
            if ($auto_expense_payroll == 'on') {
                $accountlist = AccountList::get_account_auto_payroll($employeePayslip->net_payble);

                $expense                      = new Expense();
                $expense->account_id          = $accountlist->id;
                $expense->amount              = $employeePayslip->net_payble;
                $expense->date                = date('Y-m-d');
                $expense->expense_category_id = 1; #1 = payroll, created by superadmin
                $expense->payee_id            = 1; #1 = employee, created by superadmin
                $expense->payment_type_id     = null;
                $expense->referal_id          = 0000000;
                $expense->description         = 'auto payroll employee';
                $expense->created_by          = Auth::user()->creatorId();
                $expense->save();

                AccountList::remove_Balance($accountlist->id, $employeePayslip->net_payble);
            }

            // send notif mobile
            $employee = Employee::find($id);
            $firebaseToken = [$employee->user->fcm_token];
            $userNotif = [$employee->user_id];

            if ($employee->user->fcm_token != null) {

                $data = [
                    "registration_ids" => $firebaseToken,
                    "notification" => [
                        "title" => 'Penggajian',
                        "body" => 'Penggajian Bulan ' .  date('F Y', strtotime($employeePayslip->salary_month . '-01')) . ' Telah Dibayarkan',
                    ],
                    "data" => [
                        "type" => "Payslip",
                        "id" => $employeePayslip->id
                    ]
                ];

                Fcm::sendMessage($data);

                // save notif into database
                $notification               = new Notification();
                $notification->title        = 'Penggajian';
                $notification->type         = 'Payslip';
                $notification->messages     = 'Penggajian Bulan ' . date('F Y', strtotime($employeePayslip->salary_month . '-01')) . ' Telah Dibayarkan';
                $notification->users        = implode(",", $userNotif);
                $notification->created_by   = $employee->created_by;
                $notification->save();

                foreach ($userNotif as $userId) {
                    $notificationEmployee                  = new NotificationEmployee();
                    $notificationEmployee->notification_id = $notification->id;
                    $notificationEmployee->user_id         = $userId;
                    $notificationEmployee->created_by      = $employee->created_by;

                    $notificationEmployee->save();
                }
            }

            return redirect()->route('payslip.index')->with('success', __('Payslip Payment successfully.'));
        } else {
            return redirect()->route('payslip.index')->with('error', __('Payslip Payment failed.'));
        }
    }

    public function bulk_pay_create($date)
    {
        $Employees       = PaySlip::where('salary_month', $date)->where('created_by', Auth::user()->creatorId())->get()->toArray();
        $unpaidEmployees = PaySlip::where('salary_month', $date)->where('created_by', Auth::user()->creatorId())->where('status', '=', 0)->get()->toArray();

        return view('payslip.bulkcreate', compact('Employees', 'unpaidEmployees', 'date'));
    }

    public function bulkpayment(Request $request, $date)
    {
        $creator = Auth::user()->creatorId();
        $unpaidEmployees = PaySlip::where('salary_month', $date)->where('created_by', $creator)->where('status', '=', 0)->get();

        $total_payroll = 0;
        foreach ($unpaidEmployees as $employee) {
            $employee->status = 1;
            $employee->save();

            $total_payroll += $employee->net_payble;
        }

        $auto_expense_payroll = Utility::getValByName('auto_expense_payroll');
        if ($auto_expense_payroll == 'on') {
            $accountlist = AccountList::get_account_auto_payroll($total_payroll);

            $expense                      = new Expense();
            $expense->account_id          = $accountlist->id;
            $expense->amount              = $total_payroll;
            $expense->date                = date('Y-m-d');
            $expense->expense_category_id = 1; #1 = payroll, created by superadmin
            $expense->payee_id            = 1; #1 = employee, created by superadmin
            $expense->payment_type_id     = null;
            $expense->referal_id          = 0000000;
            $expense->description         = 'auto payroll employee';
            $expense->created_by          = Auth::user()->creatorId();
            $expense->save();

            AccountList::remove_Balance($accountlist->id, $total_payroll);
        }

        // send notif mobile
        $getUser = Employee::selectRaw('users.id, users.name, users.fcm_token, users.type, users.role_id, employees.department_id')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->where('employees.created_by', $creator)
            ->where('users.fcm_token', '!=', null)
            ->get();

        $firebaseToken = [];
        $userNotif = [];
        foreach ($getUser as $x) {
            array_push($firebaseToken, $x->fcm_token);
            array_push($userNotif, $x->id);
        }

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => 'Penggajian',
                "body" => 'Penggajian Bulan ' .  date('F Y', strtotime($date . '-01')) . ' Telah Dibayarkan',
            ],
            "data" => [
                "type" => "Payslip",
                "id" => 0
            ]
        ];

        Fcm::sendMessage($data);

        // save notif into database
        $notification               = new Notification();
        $notification->title        = 'Penggajian';
        $notification->type         = 'Payslip';
        $notification->messages     = 'Penggajian Bulan ' . date('F Y', strtotime($date . '-01')) . ' Telah Dibayarkan';
        $notification->users        = implode(",", $userNotif);
        $notification->created_by   = $creator;
        $notification->save();

        foreach ($userNotif as $userId) {
            $notificationEmployee                  = new NotificationEmployee();
            $notificationEmployee->notification_id = $notification->id;
            $notificationEmployee->user_id         = $userId;
            $notificationEmployee->created_by      = $creator;

            $notificationEmployee->save();
        }

        return redirect()->route('payslip.index')->with('success', __('Payslip Bulk Payment successfully.'));
    }

    public function employeepayslip()
    {
        $employees = Employee::where(
            [
                'user_id' => Auth::user()->id,
            ]
        )->first();

        $payslip = PaySlip::where('employee_id', '=', $employees->id)->get();

        return view('payslip.employeepayslip', compact('payslip'));
    }

    public function pdf($id, $month)
    {
        $payslip  = PaySlip::where('employee_id', $id)->where('salary_month', $month)->where('created_by', Auth::user()->creatorId())->first();
        $employee = Employee::find($payslip->employee_id);

        $attendance = Employee::getattendance($payslip->id);
        $consumption_emp = Employee::getconsumption($payslip->employee_id);

        $payslipDetail = Utility::employeePayslipDetail($id, $month);
        $validatePayshift = PayShift::where('employee_id', $payslip->employee_id)->count();

        return view('payslip.pdf', compact('payslip', 'employee', 'payslipDetail', 'attendance', 'consumption_emp', 'validatePayshift'));
    }

    public function send($id, $month)
    {
        $payslip  = PaySlip::where('employee_id', $id)->where('salary_month', $month)->where('created_by', Auth::user()->creatorId())->first();
        $employee = Employee::find($payslip->employee_id);

        $payslip->name  = $employee->name;
        $payslip->email = $employee->email;

        $payslipId    = Crypt::encrypt($payslip->id);
        $payslip->url = route('payslip.payslipPdf', $payslipId);

        $setings = Utility::settings();
        if ($setings['payroll_create'] == 1) {
            try {
                Mail::to($payslip->email)->send(new PayslipSend($payslip));
            } catch (\Exception $e) {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }

            return back()->with('success', __('Payslip successfully sent.') . (isset($smtp_error) ? $smtp_error : ''));
        }

        return back()->with('success', __('Payslip successfully sent.'));
    }

    public function payslipPdf($id)
    {
        $payslipId = Crypt::decrypt($id);

        $payslip  = PaySlip::where('id', $payslipId)->where('created_by', Auth::user()->creatorId())->first();
        $employee = Employee::find($payslip->employee_id);

        $attendance = Employee::getattendance($payslip->id);
        $consumption_emp = Employee::getconsumption($payslip->employee_id);

        $payslipDetail = Utility::employeePayslipDetail($payslip->employee_id);
        $validatePayshift = PayShift::where('employee_id', $payslip->employee_id)->count();

        return view('payslip.payslipPdf', compact('payslip', 'employee', 'payslipDetail', 'attendance', 'consumption_emp', 'validatePayshift'));
    }

    public function editEmployee($paySlip)
    {
        $payslip = PaySlip::find($paySlip);

        return view('payslip.salaryEdit', compact('payslip'));
    }

    public function updateEmployee(Request $request, $id)
    {
        $settings = Utility::settings();
        $payroll_date   = $settings['payroll_date'];
        $payroll_time   = $settings['payroll_time'];
        $month_start_date = $settings['month_start_date'];

        if (isset($request->allowance) && !empty($request->allowance)) {
            $allowances   = $request->allowance;
            $allowanceIds = $request->allowance_id;
            foreach ($allowances as $k => $allownace) {
                $allowanceData         = Allowance::find($allowanceIds[$k]);
                $allowanceData->amount = $allownace;
                $allowanceData->save();
            }
        }


        if (isset($request->commission) && !empty($request->commission)) {
            $commissions   = $request->commission;
            $commissionIds = $request->commission_id;
            foreach ($commissions as $k => $commission) {
                $commissionData         = Commission::find($commissionIds[$k]);
                $commissionData->amount = $commission;
                $commissionData->save();
            }
        }

        if (isset($request->loan) && !empty($request->loan)) {
            $loans   = $request->loan;
            $loanIds = $request->loan_id;
            foreach ($loans as $k => $loan) {
                $loanData         = Loan::find($loanIds[$k]);
                $loanData->amount = $loan;
                $loanData->save();
            }
        }


        if (isset($request->saturation_deductions) && !empty($request->saturation_deductions)) {
            $saturation_deductionss   = $request->saturation_deductions;
            $saturation_deductionsIds = $request->saturation_deductions_id;
            foreach ($saturation_deductionss as $k => $saturation_deductions) {

                $saturation_deductionsData         = SaturationDeduction::find($saturation_deductionsIds[$k]);
                $saturation_deductionsData->amount = $saturation_deductions;
                $saturation_deductionsData->save();
            }
        }


        if (isset($request->other_payment) && !empty($request->other_payment)) {
            $other_payments   = $request->other_payment;
            $other_paymentIds = $request->other_payment_id;
            foreach ($other_payments as $k => $other_payment) {
                $other_paymentData         = OtherPayment::find($other_paymentIds[$k]);
                $other_paymentData->amount = $other_payment;
                $other_paymentData->save();
            }
        }


        if (isset($request->rate) && !empty($request->rate)) {
            $rates   = $request->rate;
            $rateIds = $request->rate_id;
            $hourses = $request->hours;

            foreach ($rates as $k => $rate) {
                $overtime        = Overtime::find($rateIds[$k]);
                $overtime->rate  = $rate;
                $overtime->hours = $hourses[$k];
                $overtime->save();
            }
        }


        $payslipEmployee                       = PaySlip::find($request->payslip_id);

        $year = date('Y', strtotime($payslipEmployee->salary_month . '-01'));
        $month = date('m', strtotime($payslipEmployee->salary_month . '-01'));

        if ($payroll_time == 'first') {
            $start_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $month_start_date));
            $end_date = date('Y-m-d', strtotime('+1 month', strtotime($year . '-' . $month . '-' . $payroll_date)));
            $end_date = date('Y-m-d', strtotime('-1 days', strtotime($end_date)));
        } else {
            $start_date = date('Y-m-d', strtotime('-1 month', strtotime($year . '-' . $month . '-' . $month_start_date)));
            $end_date = date('Y-m-d', strtotime('-1 days', strtotime($year . '-' . $month . '-' . $payroll_date)));
        }

        $payslipEmployee->basic_salary         = Employee::find($payslipEmployee->employee_id)->salary;
        $payslipEmployee->allowance            = Employee::allowance($payslipEmployee->employee_id);
        $payslipEmployee->commission           = Employee::commission($payslipEmployee->employee_id, $start_date, $end_date);
        $payslipEmployee->loan                 = Employee::loan($payslipEmployee->employee_id);
        $payslipEmployee->saturation_deduction = Employee::saturation_deduction($payslipEmployee->employee_id);
        $payslipEmployee->other_payment        = Employee::other_payment($payslipEmployee->employee_id, $start_date, $end_date);
        $payslipEmployee->overtime             = Employee::overtime($payslipEmployee->employee_id, $start_date, $end_date);
        $payslipEmployee->net_payble           = Employee::find($payslipEmployee->employee_id)->get_net_salary();
        $payslipEmployee->save();

        return redirect()->route('payslip.index')->with('success', __('Employee payroll successfully updated.'));
    }

    public function setFinesAndRewards($month, $year)
    {
        $creator = Auth::user()->creatorId();
        $getUser = Employee::where('created_by', '=', $creator)->where('is_active', 1)->get()->pluck('id');

        $settings = Utility::settings();

        $payroll_date   = $settings['payroll_date'];
        $payroll_time   = $settings['payroll_time'];
        $month_start_date = $settings['month_start_date'];
        if ($payroll_time == 'first') {
            $start_date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $month_start_date));
            $end_date = date('Y-m-d', strtotime('+1 month', strtotime($year . '-' . $month . '-' . $payroll_date)));
            $end_date = date('Y-m-d', strtotime('-1 days', strtotime($end_date)));
        } else {
            $start_date = date('Y-m-d', strtotime('-1 month', strtotime($year . '-' . $month . '-' . $month_start_date)));
            $end_date = date('Y-m-d', strtotime('-1 days', strtotime($year . '-' . $month . '-' . $payroll_date)));
        }

        $date1 = strtotime($start_date);
        $date2 = strtotime($end_date);
        $funcdate = $date2 - $date1;
        $difference = $funcdate / 60 / 60 / 24;

        $param_diff = [
            'd' => $difference
        ];
        $diff = (object)$param_diff;

        $rest_mode = $settings['rest_mode'];

        if ($rest_mode == 'on') {
            $my_attendance = AttendanceEmployee::selectRaw('if(parent_id IS NULL, max(id), parent_id) as id, employee_id, date, end_date, status, parent_id, late, working_hours, working_late, salary_cuts, (select ae.shift_id from attendance_employees as ae where ae.id = max(attendance_employees.id)) as shift_id')->whereIn('employee_id', $getUser);

            $my_attendance->whereBetween(
                'date',
                [
                    $start_date,
                    $end_date,
                ]
            );
            $my_attendance = $my_attendance->latest()->groupBy('date')->orderBy('id', 'ASC')->get();
        } else {
            $my_attendance = AttendanceEmployee::selectRaw('if(parent_id IS NULL, id, parent_id) as id, employee_id, date, end_date, status, parent_id, late, working_hours, working_late, salary_cuts, shift_id')->whereIn('employee_id', $getUser);

            $my_attendance->whereBetween(
                'date',
                [
                    $start_date,
                    $end_date,
                ]
            );
            $my_attendance = $my_attendance->orderBy('id', 'ASC')->get();
        }

        $history = [];
        foreach ($getUser as $val) {
            foreach ($my_attendance as $key => $value) {
                if ($value->employee_id == $val) {
                    $history[$val][] = [
                        'id'                => $value->id,
                        'employee_id'       => $value->employee_id,
                        'employee'          => $value->employee->name,
                        'std_date'          => $value->date,
                        'working_hours'     => isset($value->latecharge) ? $value->latecharge->working_hours : ($value->working_hours != null ? $value->working_hours : '00:00:00'),
                        'salary_cuts_int'   => isset($value->latecharge) ? $value->latecharge->salary_cuts : $value->salary_cuts,
                    ];
                }
            }
        }

        $data_cuts = [];
        $data_rewards = [];
        $attendance_gift = $settings['attendance_gift'];
        $costAbsence = $settings['daily_no_show_fee'];
        $working_days = json_decode($settings['working_days']);
        $working_hours_per_days = $settings['working_hours'];
        $convert_working_hours  = gmdate('H:i:s', ($working_hours_per_days * 3600));


        if (count($history) > 0) {
            foreach ($getUser as $value) {
                // get leave in month
                $total_salary_cuts = 0;
                $rewardsTime = 0;
                $arrWorkingHours = [];
                $leaveList = Leave::where('employee_id', $value);

                $leaveList->whereBetween(
                    'start_date',
                    [
                        $start_date,
                        $end_date,
                    ]
                );
                $leaveList = $leaveList->where('status', 'Approve')->get();

                $arrLeave = [];
                foreach ($leaveList as $val) {
                    if ($val->start_date != $val->end_date) {
                        for ($i = 0; $i < $val->total_leave_days; $i++) {
                            $arrDate = date('Y-m-d', strtotime('+' . ($i) . ' days', strtotime($val->start_date)));
                            array_push($arrLeave, $arrDate);
                        }
                    } else {
                        array_push($arrLeave, $val->start_date);
                    }
                }

                for ($i = 0; $i <= $diff->d; $i++) {
                    $lastDate = date('Y-m-d', strtotime('-' . ($i) . ' days', strtotime($end_date)));
                    $key = array_search($lastDate, array_column($history[$value], 'std_date'));

                    if (in_array(date('N', strtotime($lastDate)), $working_days)) {
                        if (in_array($lastDate, $history[$value][$key])) {
                            // present
                            $total_salary_cuts += $history[$value][$key]['salary_cuts_int'];
                            if ($history[$value][$key]['working_hours'] >= $convert_working_hours) {
                                $addworkinghours = $convert_working_hours;
                            } else {
                                $addworkinghours = $history[$value][$key]['working_hours'];
                            }
                            if ($history[$value][$key]['working_hours'] != '') array_push($arrWorkingHours, $addworkinghours);
                        } else {
                            // absence
                            if (!in_array($lastDate, $arrLeave)) {
                                $total_salary_cuts += $costAbsence;
                            }
                        }
                    }
                }

                $data_cuts[$value] = $total_salary_cuts;

                $sumTime = accumulateTime($arrWorkingHours);
                $seconds = 0;
                list(
                    $g, $i, $s
                ) = explode(
                    ':',
                    $sumTime
                );
                $seconds += $g * 3600;
                $seconds += $i * 60;
                $seconds += $s;
                $hours = floor($seconds / 3600);
                $rewardsTime = $hours * $attendance_gift;

                $data_rewards[$value] = $rewardsTime;
            }
        }


        // insert other payment
        $other_payment = [];
        foreach ($data_cuts as $key => $value) {
            $other_payment[] = [
                'employee_id'   => $key,
                'title'         => 'Denda Keterlambatan',
                'amount'        => $value,
                'created_by'    => $creator,
                'created_at'    => $end_date . ' ' . date("H:i:s"),
                'updated_at'    => $end_date . ' ' . date("H:i:s")
            ];
        }
        OtherPayment::insert($other_payment);

        // commission
        $commission = [];
        foreach ($data_rewards as $key => $value) {
            $commission[] = [
                'employee_id'   => $key,
                'title'         => 'Bonus Kehadiran',
                'amount'        => $value,
                'created_by'    => $creator,
                'created_at'    => $end_date . ' ' . date("H:i:s"),
                'updated_at'    => $end_date . ' ' . date("H:i:s")
            ];
        }
        Commission::insert($commission);
    }
}
