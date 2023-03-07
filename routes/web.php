<?php

use App\Helpers\Fcm;
use App\Http\Controllers\AccountListController;
use App\Http\Controllers\AdditionalInformationController;
use App\Http\Controllers\AllowanceController;
use App\Http\Controllers\AllowanceOptionController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AttendanceEmployeeController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\CountingController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\DeductionOptionController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeTypeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InterviewScheduleController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobStageController;
use App\Http\Controllers\LandingPageSectionController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanOptionController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\OtherPaymentController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\PayshiftController;
use App\Http\Controllers\PaySlipController;
use App\Http\Controllers\PayslipTypeController;
use App\Http\Controllers\PermissionTypeController;
use App\Http\Controllers\PlanRequestController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PositionGroupController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\QrtokenController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\SaturationDeductionController;
use App\Http\Controllers\SetSalaryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\TerminationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TimeSheetController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\TransferBalanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoomMeetingController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth', 'git']], function () {
    Route::resource('settings', SettingsController::class);
    Route::post('email-settings', [SettingsController::class, 'saveEmailSettings'])->name('email.settings');
    Route::post('company-settings', [SettingsController::class, 'saveCompanySettings'])->name('company.settings');
    Route::post('payment-settings', [SettingsController::class, 'savePaymentSettings'])->name('payment.settings');
    Route::post('system-settings', [SettingsController::class, 'saveSystemSettings'])->name('system.settings');
    Route::post('company-setting', [SettingsController::class, 'companyIndex'])->name('company.setting');
    Route::get('company-email-setting/{name}', [EmailTemplateController::class, 'updateStatus'])->name('company.email.setting');
    Route::post('business-setting', [SettingsController::class, 'saveBusinessSettings'])->name('business.setting');
    Route::post('zoom-settings', [SettingsController::class, 'zoomSetting'])->name('zoom.settings');
    Route::get('test-mail', [SettingsController::class, 'testMail'])->name('test.mail');
    Route::post('test-mail', [SettingsController::class, 'testSendMail'])->name('test.send.mail');
    Route::get('create/ip', [SettingsController::class, 'createIp'])->name('create.ip');
    Route::post('create/ip', [SettingsController::class, 'storeIp'])->name('store.ip');
    Route::get('edit/ip/{id}', [SettingsController::class, 'editIp'])->name('edit.ip');
    Route::post('edit/ip/{id}', [SettingsController::class, 'updateIp'])->name('update.ip');
    Route::delete('destroy/ip/{id}', [SettingsController::class, 'destroyIp'])->name('destroy.ip');
    Route::get('orders', [StripePaymentController::class, 'index'])->name('order.index');
    Route::get('stripe/{code}', [StripePaymentController::class, 'stripe'])->name('stripe');
    Route::get('stripe_request/{code}', [StripePaymentController::class, 'stripe_request'])->name('stripe_request');
    Route::post('stripe', [StripePaymentController::class, 'stripePost'])->name('stripe.post');
    Route::get('email_template_lang/{id}/{lang?}', [EmailTemplateController::class, 'manageEmailLang'])->name('manage.email.language');
    Route::post('email_template_store/{pid}', [EmailTemplateController::class, 'storeEmailLang'])->name('store.email.language');
    Route::post('email_template_status/{id}', [EmailTemplateController::class, 'updateStatus'])->name('status.email.language');
    Route::resource('email_template', EmailTemplateController::class);
    Route::get('test', ['as' => 'test.email', 'uses' => [SettingsController::class, 'testEmail']]);
    Route::post('test/send', ['as' => 'test.email.send', 'uses' => [SettingsController::class, 'testEmailSend']]);
    Route::resource('user', UserController::class);
    Route::get('employee/datatable', [EmployeeController::class, 'datatable'])->name('employee.datatable');
    Route::post('employee/json', [EmployeeController::class, 'json'])->name('employee.json');
    Route::post('branch/employee/json', [EmployeeController::class, 'employeeJson'])->name('branch.employee.json');
    Route::get('employee-profile', [EmployeeController::class, 'profile'])->name('employee.profile');
    Route::get('show-employee-profile/{id}', [EmployeeController::class, 'profileShow'])->name('show.employee.profile');
    Route::get('lastlogin', [EmployeeController::class, 'lastLogin'])->name('lastlogin');
    Route::resource('employee', EmployeeController::class);
    Route::resource('department', DepartmentController::class);
    Route::resource('designation', DesignationController::class);
    Route::resource('document', DocumentController::class);
    Route::resource('branch', BranchController::class);
    Route::resource('awardtype', AwardTypeController::class);
    Route::resource('award', AwardController::class);
    Route::resource('termination', TerminationController::class);
    Route::resource('terminationtype', TerminationTypeController::class);
    Route::post('announcement/getdepartment', [AnnouncementController::class, 'getdepartment'])->name('announcement.getdepartment');
    Route::post('announcement/getemployee', [AnnouncementController::class, 'getemployee'])->name('announcement.getemployee');
    Route::resource('announcement', AnnouncementController::class);
    Route::get('holiday/calender', [HolidayController::class, 'calendar'])->name('holiday.calender');
    Route::resource('holiday', HolidayController::class);
    Route::get('employee/salary/{eid}', [SetSalaryController::class, 'employeeBasicSalary'])->name('employee.basic.salary');
    Route::get('allowances/create/{eid}', [AllowanceController::class, 'allowanceCreate'])->name('allowances.create');
    Route::get('commissions/create/{eid}', [CommissionController::class, 'commissionCreate'])->name('commissions.create');
    Route::get('loans/create/{eid}', [LoanController::class, 'loanCreate'])->name('loans.create');
    Route::get('saturationdeductions/create/{eid}', [SaturationDeductionController::class, 'saturationdeductionCreate'])->name('saturationdeductions.create');
    Route::get('otherpayments/create/{eid}', [OtherPaymentController::class, 'otherpaymentCreate'])->name('otherpayments.create');
    Route::get('overtimes/create/{eid}', [OvertimeController::class, 'overtimeCreate'])->name('overtimes.create');
    Route::resource('paysliptype', PayslipTypeController::class);
    Route::resource('allowance', AllowanceController::class);
    Route::resource('commission', CommissionController::class);
    Route::resource('allowanceoption', AllowanceOptionController::class);
    Route::resource('loanoption', LoanOptionController::class);
    Route::resource('deductionoption', DeductionOptionController::class);
    Route::resource('loan', LoanController::class);
    Route::resource('saturationdeduction', SaturationDeductionController::class);
    Route::resource('otherpayment', OtherPaymentController::class);
    Route::resource('overtime', OvertimeController::class);
    Route::post('event/getdepartment', [EventController::class, 'getdepartment'])->name('event.getdepartment');
    Route::post('event/getemployee', [EventController::class, 'getemployee'])->name('event.getemployee');
    Route::resource('event', EventController::class);
    Route::post('meeting/getdepartment', [MeetingController::class, 'getdepartment'])->name('meeting.getdepartment');
    Route::post('meeting/getemployee', [MeetingController::class, 'getemployee'])->name('meeting.getemployee');
    Route::resource('meeting', MeetingController::class);
    Route::post('employee/update/sallary/{id}', [SetSalaryController::class, 'employeeUpdateSalary'])->name('employee.salary.update');
    Route::get('salary/employeeSalary', [SetSalaryController::class, 'employeeSalary'])->name('employeesalary');
    Route::resource('setsalary', SetSalaryController::class);
    Route::get('payslip/paysalary/{id}/{date}', [PaySlipController::class, 'paysalary'])->name('payslip.paysalary');
    Route::get('payslip/bulk_pay_create/{date}', [PaySlipController::class, 'bulk_pay_create'])->name('payslip.bulk_pay_create');
    Route::post('payslip/bulkpayment/{date}', [PaySlipController::class, 'bulkpayment'])->name('payslip.bulkpayment');
    Route::post('payslip/search_json', [PaySlipController::class, 'search_json'])->name('payslip.search_json');
    Route::get('payslip/employeepayslip', [PaySlipController::class, 'employeepayslip'])->name('payslip.employeepayslip');
    Route::get('payslip/showemployee/{id}', [PaySlipController::class, 'showemployee'])->name('payslip.showemployee');
    Route::get('payslip/editemployee/{id}', [PaySlipController::class, 'editemployee'])->name('payslip.editemployee');
    Route::post('payslip/editemployee/{id}', [PaySlipController::class, 'updateEmployee'])->name('payslip.updateemployee');
    Route::get('payslip/pdf/{id}/{m}', [PaySlipController::class, 'pdf'])->name('payslip.pdf');
    Route::get('payslip/payslipPdf/{id}', [PaySlipController::class, 'payslipPdf'])->name('payslip.payslipPdf');
    Route::get('payslip/send/{id}/{m}', [PaySlipController::class, 'send'])->name('payslip.send');
    Route::get('payslip/delete/{id}', [PaySlipController::class, 'destroy'])->name('payslip.delete');
    Route::resource('payslip', PaySlipController::class);
    Route::resource('resignation', ResignationController::class);
    Route::resource('travel', TravelController::class);
    Route::resource('promotion', PromotionController::class);
    Route::resource('transfer', TransferController::class);
    Route::resource('complaint', ComplaintController::class);
    Route::resource('warning', WarningController::class);
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::post('edit-profile', [UserController::class, 'editprofile'])->name('update.account');
    Route::resource('accountlist', AccountListController::class);
    Route::get('accountbalance', [AccountListController::class, 'account_balance'])->name('accountbalance');
    Route::get('ticket/{id}/reply', [TicketController::class, 'reply'])->name('ticket.reply');
    Route::post('ticket/changereply', [TicketController::class, 'changereply'])->name('ticket.changereply');
    Route::resource('ticket', TicketController::class);
    Route::get('attendanceemployee/bulkattendance', [AttendanceEmployeeController::class, 'bulkAttendance'])->name('attendanceemployee.bulkattendance');
    Route::post('attendanceemployee/bulkattendance', [AttendanceEmployeeController::class, 'bulkAttendanceData'])->name('attendanceemployee.bulkattendance.post');
    Route::post('attendanceemployee/attendance', [AttendanceEmployeeController::class, 'attendance'])->name('attendanceemployee.attendance');
    Route::resource('attendanceemployee', AttendanceEmployeeController::class);
    Route::resource('timesheet', TimeSheetController::class);
    Route::resource('expensetype', ExpenseTypeController::class);
    Route::resource('incometype', IncomeTypeController::class);
    Route::resource('paymenttype', PaymentTypeController::class);
    Route::resource('leavetype', LeaveTypeController::class);
    Route::resource('payees', PayeesController::class);
    Route::resource('payer', PayerController::class);
    Route::resource('deposit', DepositController::class);
    Route::resource('expense', ExpenseController::class);
    Route::resource('transferbalance', TransferBalanceController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::get('user/{id}/plan', [UserController::class, 'upgradePlan'])->name('plan.upgrade');
    Route::get('user/{id}/plan/{pid}', [UserController::class, 'activePlan'])->name('plan.active');
    Route::resource('plans', PlanController::class);
    Route::resource('coupons', CouponController::class);
    Route::resource('account-assets', AssetController::class);
    Route::resource('document-upload', DucumentUploadController::class);
    Route::resource('indicator', IndicatorController::class);
    Route::resource('appraisal', AppraisalController::class);
    Route::resource('goaltype', GoalTypeController::class);
    Route::resource('goaltracking', GoalTrackingController::class);
    Route::resource('company-policy', CompanyPolicyController::class);
    Route::resource('trainingtype', TrainingTypeController::class);
    Route::resource('trainer', TrainerController::class);
    Route::post('training/status', [TrainingController::class, 'updateStatus'])->name('training.status');
    Route::resource('training', TrainingController::class);
    Route::get('report/income-expense', [ReportController::class, 'incomeVsExpense'])->name('report.income-expense');
    Route::get('report/leave', [ReportController::class, 'leave'])->name('report.leave');
    Route::get('employee/{id}/leave/{status}/{type}/{month}/{year}', [ReportController::class, 'employeeLeave'])->name('report.employee.leave');
    Route::get('report/account-statement', [ReportController::class, 'accountStatement'])->name('report.account.statement');
    Route::get('report/payroll', [ReportController::class, 'payroll'])->name('report.payroll');
    Route::get('report/monthly/attendance', [ReportController::class, 'monthlyAttendance'])->name('report.monthly.attendance');
    Route::get('report/attendance/{month}/{branch}/{department}', [ReportController::class, 'exportCsv'])->name('report.attendance');
    Route::get('report/timesheet', [ReportController::class, 'timesheet'])->name('report.timesheet');
    Route::resource('job-category', JobCategoryController::class);
    Route::resource('job-stage', JobStageController::class);
    Route::post('job-stage/order', [JobStageController::class, 'order'])->name('job.stage.order');
    Route::resource('job', JobController::class);
    Route::get('candidates-job-applications', [JobApplicationController::class, 'candidate'])->name('job.application.candidate');
    Route::resource('job-application', JobApplicationController::class);
    Route::post('job-application/order', [JobApplicationController::class, 'order'])->name('job.application.order');
    Route::post('job-application/{id}/rating', [JobApplicationController::class, 'rating'])->name('job.application.rating');
    Route::delete('job-application/{id}/archive', [JobApplicationController::class, 'archive'])->name('job.application.archive');
    Route::post('job-application/{id}/skill/store', [JobApplicationController::class, 'addSkill'])->name('job.application.skill.store');
    Route::post('job-application/{id}/note/store', [JobApplicationController::class, 'addNote'])->name('job.application.note.store');
    Route::delete('job-application/{id}/note/destroy', [JobApplicationController::class, 'destroyNote'])->name('job.application.note.destroy');
    Route::post('job-application/getByJob', [JobApplicationController::class, 'getByJob'])->name('get.job.application');
    Route::get('job-onboard', [JobApplicationController::class, 'jobOnBoard'])->name('job.on.board');
    Route::get('job-onboard/create/{id}', [JobApplicationController::class, 'jobBoardCreate'])->name('job.on.board.create');
    Route::post('job-onboard/store/{id}', [JobApplicationController::class, 'jobBoardStore'])->name('job.on.board.store');
    Route::get('job-onboard/edit/{id}', [JobApplicationController::class, 'jobBoardEdit'])->name('job.on.board.edit');
    Route::post('job-onboard/update/{id}', [JobApplicationController::class, 'jobBoardUpdate'])->name('job.on.board.update');
    Route::delete('job-onboard/delete/{id}', [JobApplicationController::class, 'jobBoardDelete'])->name('job.on.board.delete');
    Route::get('job-onboard/convert/{id}', [JobApplicationController::class, 'jobBoardConvert']);
    Route::post('job-onboard/convert/{id}', [JobApplicationController::class, 'jobBoardConvertData'])->name('job.on.board.convert');
    Route::post('job-application/stage/change', [JobApplicationController::class, 'stageChange'])->name('job.application.stage.change');
    Route::resource('custom-question', CustomQuestionController::class);
    Route::resource('interview-schedule', InterviewScheduleController::class);
    Route::get('interview-schedule/create/{id?}', [InterviewScheduleController::class, 'create']);
    Route::resource('competencies', CompetenciesController::class);
    Route::resource('performanceType', PerformanceTypeController::class);
    Route::get('change-language/{lang}', [LanguageController::class, 'changeLanquage'])->name('change.language');
    Route::get('manage-language/{lang}', [LanguageController::class, 'manageLanguage'])->name('manage.language');
    Route::post('store-language-data/{lang}', [LanguageController::class, 'storeLanguageData'])->name('store.language.data');
    Route::get('create-language', [LanguageController::class, 'createLanguage'])->name('create.language');
    Route::post('store-language', [LanguageController::class, 'storeLanguage'])->name('store.language');
    Route::delete('lang/{id}', [LanguageController::class, 'destroyLang'])->name('lang.destroy');
    Route::resource('plan_request', PlanRequestController::class);
    Route::get('plan_request', [PlanRequestController::class, 'index'])->name('plan_request.index');
    Route::get('request_frequency/{id}', [PlanRequestController::class, 'requestView'])->name('request.view');
    Route::get('request_send/{id}', [PlanRequestController::class, 'userRequest'])->name('send.request');
    Route::get('request_response/{id}/{response}', [PlanRequestController::class, 'acceptRequest'])->name('response.request');
    Route::get('request_cancel/{id}', [PlanRequestController::class, 'cancelRequest'])->name('request.cancel');
    Route::any('zoommeeting/calendar', [ZoomMeetingController::class, 'calender'])->name('zoom_meeting.calender');
    Route::resource('zoom-meeting', ZoomMeetingController::class);
    Route::post('/recaptcha-settings', ['as' => 'recaptcha.settings.store', 'uses' => [SettingsController::class, 'recaptchaSettingStore']]);
    Route::resource('shift', ShiftController::class);
    Route::resource('roomtype', RoomTypeController::class);
    Route::resource('employeetype', EmployeeTypeController::class);
    Route::resource('position', PositionController::class);
    Route::resource('group', GroupController::class);
    Route::resource('education', EducationController::class);
    Route::resource('additional', AdditionalInformationController::class);
    Route::resource('permissiontype', PermissionTypeController::class);
    Route::resource('compensation', OvertimeCompensationController::class);
    Route::resource('counting', CountingController::class);
    Route::resource('positiongroup', PositionGroupController::class);
    Route::get('employee/consumption/{id}', [SetSalaryController::class, 'employeeConsumptionFee'])->name('employee.consumption.fee');
    Route::post('employee/update/consumption/{id}', [SetSalaryController::class, 'employeeUpdateConsumption'])->name('employee.consumption.update');
    Route::resource('payshift', 'PayshiftController');
    Route::get('payshifts/create/{eid}', [PayshiftController::class, 'payshiftCreate'])->name('payshifts.create');
    Route::resource('leave', LeaveController::class);
    Route::get('leave/{id}/action', [LeaveController::class, 'action'])->name('leave.action');
    Route::post('leave/changeaction', [LeaveController::class, 'changeaction'])->name('leave.changeaction');
    Route::post('leave/jsoncount', [LeaveController::class, 'jsoncount'])->name('leave.jsoncount');
    Route::post('leave/leavevalidate', [LeaveController::class, 'leavevalidate'])->name('leave.leavevalidate');
    Route::get('report/employee/room', 'ReportController@employeeRoom')->name('report.employee.room');
    Route::get('report/employee/position', 'ReportController@employeePosition')->name('report.employee.position');
    Route::get('attendanceemployee/showpicture/{id}', 'AttendanceEmployeeController@showpicture')->name('attendanceemployee.showpicture');
    Route::get('attendanceemployee/showpictureout/{id}', 'AttendanceEmployeeController@showpictureout')->name('attendanceemployee.showpictureout');
    Route::resource('schedule', 'ScheduleController');
    Route::post('schedule/getdepartment', 'ScheduleController@getdepartment')->name('schedule.getdepartment');
    Route::post('schedule/getemployee', 'ScheduleController@getemployee')->name('schedule.getemployee');
    Route::resource('scheduleEmployee', 'ScheduleEmployeeController');
    Route::get('/schedule/show/{id}', 'ScheduleController@show');
    Route::get('schedule/create_schedule/new/', 'ScheduleController@create_schedule')->name('schedule.create_schedule.new');
});

// Route::get('password/resets/{lang?}', [LoginController::class, 'showLinkRequestForm'])->name('change.langPass');
Route::get('home/getlanguvage', [HomeController::class, 'getlanguvage'])->name('home.getlanguvage');
Route::get('termination/{id}/description', [TerminationController::class, 'description'])->name('termination.description');
Route::get('event/data/{id}', [EventController::class, 'showData'])->name('eventsshow');
Route::get('import/event/file', [EventController::class, 'importFile'])->name('event.file.import');
Route::post('import/event', [EventController::class, 'import'])->name('event.import');
Route::get('export/event', [EventController::class, 'export'])->name('event.export');
Route::post('change-password', [UserController::class, 'updatePassword'])->name('update.password');

Route::get('apply-coupon', ['as' => 'apply.coupon', 'uses' => [CouponController::class, 'applyCoupon']]);

//=================================  Recuritment =========================================//

Route::get('career/{id}/{lang}', [JobController::class, 'career'])->name('career');
Route::get('job/requirement/{code}/{lang}', [JobController::class, 'jobRequirement'])->name('job.requirement');
Route::get('job/apply/{code}/{lang}', [JobController::class, 'jobApply'])->name('job.apply');
Route::post('job/apply/data/{code}', [JobController::class, 'jobApplyData'])->name('job.apply.data');

//================================= Custom Landing Page ====================================//

Route::group(['middleware' => 'auth'], function () {
    Route::post('landingPage/removeSection/{id}', [LandingPageSectionController::class, 'removeSection']);
    Route::post('landingPage/setOrder', [LandingPageSectionController::class, 'setOrder']);
    Route::post('landingPage/copySection', [LandingPageSectionController::class, 'copySection']);
    Route::post('landingPage/setConetent', [LandingPageSectionController::class, 'setConetent']);
    Route::get('landingpage', [LandingPageSectionController::class, 'index'])->name('custom_landing_page.index');
});

Route::get('LandingPage/show/{id}', [LandingPageSectionController::class, 'show']);
Route::get('get_landing_page_section/{name}', function ($name) {
    $plans = DB::table('plans')->get();
    return view('custom_landing_page.' . $name, compact('plans'));
});

//employee Import & Export
Route::get('import/employee/file', [EmployeeController::class, 'importFile'])->name('employee.file.import');
Route::post('import/employee', [EmployeeController::class, 'import'])->name('employee.import');
Route::get('export/employee', [EmployeeController::class, 'export'])->name('employee.export');

// Timesheet Import & Export
Route::get('import/timesheet/file', [TimeSheetController::class, 'importFile'])->name('timesheet.file.import');
Route::post('import/timesheet', [TimeSheetController::class, 'import'])->name('timesheet.import');
Route::get('export/timesheet', [TimeSheetController::class, 'export'])->name('timesheet.export');

// //leave export
Route::get('export/leave', [LeaveController::class, 'export'])->name('leave.export');

//deposite Export
Route::get('export/deposite', [DepositController::class, 'export'])->name('deposite.export');

//expense Export
Route::get('export/expense', [ExpenseController::class, 'export'])->name('expense.export');

//Transfer Balance Export
Route::get('export/transfer-balance', [TransferBalanceController::class, 'export'])->name('transfer_balance.export');

//Training Import & Export
Route::get('export/training', [TrainingController::class, 'export'])->name('training.export');

//Trainer Export
Route::get('export/trainer', [TrainerController::class, 'export'])->name('trainer.export');
Route::get('import/training/file', [TrainerController::class, 'importFile'])->name('trainer.file.import');
Route::post('import/training', [TrainerController::class, 'import'])->name('trainer.import');

//Holiday Export & Import
Route::get('export/holidays', [HolidayController::class, 'export'])->name('holidays.export');
Route::get('import/holidays/file', [HolidayController::class, 'importFile'])->name('holidays.file.import');
Route::post('import/holidays', [HolidayController::class, 'import'])->name('holidays.import');

//Asset Import & Export
Route::get('export/assets', [AssetController::class, 'export'])->name('assets.export');
Route::get('import/assets/file', [AssetController::class, 'importFile'])->name('assets.file.import');
Route::post('import/assets', [AssetController::class, 'import'])->name('assets.import');

//slack
Route::post('setting/slack', [SettingsController::class, 'slack'])->name('slack.setting');

//telegram
Route::post('setting/telegram', [SettingsController::class, 'telegram'])->name('telegram.setting');

//twilio
Route::post('setting/twilio', [SettingsController::class, 'twilio'])->name('twilio.setting');

// user reset password
Route::get('user-reset-password/{id}', [UserController::class, 'userPassword'])->name('user.reset');
Route::post('user-reset-password/{id}', [UserController::class, 'userPasswordReset'])->name('user.password.update');

Route::get('ipget', function () {
    // return request()->ip();
    if (isset($_SERVER)) {

        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
            return $_SERVER["HTTP_X_FORWARDED_FOR"];

        if (isset($_SERVER["HTTP_CLIENT_IP"]))
            return $_SERVER["HTTP_CLIENT_IP"];

        return $_SERVER["REMOTE_ADDR"];
    }

    if (getenv('HTTP_X_FORWARDED_FOR'))
        return getenv('HTTP_X_FORWARDED_FOR');

    if (getenv('HTTP_CLIENT_IP'))
        return getenv('HTTP_CLIENT_IP');

    return getenv('REMOTE_ADDR');
});

Route::get('testselect2', function () {
    return view('qrcode.test');
});

Route::get('notif', function () {
    $firebaseToken = User::where('fcm_token', '!=', null)->pluck('fcm_token')->all();
    $data = [
        "registration_ids" => $firebaseToken,
        "notification" => [
            "title" => 'Testing Notif',
            "body" => 'Type = Notif',
        ],
        "data" => [
            "type" => "Notif"
        ]
    ];

    Fcm::sendMessage($data);

    return 'success';
});

Route::get('notif-izin', function () {
    $firebaseToken = User::where('fcm_token', '!=', null)->pluck('fcm_token')->all();
    $data = [
        "registration_ids" => $firebaseToken,
        "notification" => [
            "title" => 'Testing Notif',
            "body" => 'Type = Izin',
        ],
        "data" => [
            "type" => "Izin"
        ]
    ];

    Fcm::sendMessage($data);

    return 'success';
});

Route::get('notif-leave', function () {
    $firebaseToken = User::where('fcm_token', '!=', null)->pluck('fcm_token')->all();
    $data = [
        "registration_ids" => $firebaseToken,
        "notification" => [
            "title" => 'Testing Notif',
            "body" => 'Type = leave',
        ],
        "data" => [
            "type" => "Leave"
        ]
    ];

    Fcm::sendMessage($data);

    return 'success';
});

Route::get('notif-payslip', function () {
    $firebaseToken = User::where('fcm_token', '!=', null)->pluck('fcm_token')->all();
    $data = [
        "registration_ids" => $firebaseToken,
        "notification" => [
            "title" => 'Testing Notif',
            "body" => 'Type = payslip',
        ],
        "data" => [
            "type" => "Payslip"
        ]
    ];

    Fcm::sendMessage($data);

    return 'success';
});

Route::get('privacy_policy', function () {
    return view('privacy_policy');
});
Route::get('generate-presence/{code}', function ($code) {
    $getCompany = User::where('email', $code)->where('type', 'company')->first();

    if ($getCompany) {
        $key = Crypt::encryptString(strtotime($getCompany->created_at));
        $key2 = $getCompany->id;

        print(url('presensi?key=' . $key . '&key2=' . $key2));
    } else {
        return 'unauthorized';
    }
    // return $code;
});

Route::get('presensi', [PresenceController::class, 'index']);
Route::get('qrtoken', [QrtokenController::class, 'index']);
Route::get('today_presence', [PresenceController::class, 'getTodayPresence']);

Route::get('alat-debug/resetAndSetupPermissions', [DebugController::class, 'resetAndSetupPermissions']);
