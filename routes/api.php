<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdditionalInformationActionController;
use App\Http\Controllers\API\AdditionalInformationController;
use App\Http\Controllers\API\AttendanceActionController;
use App\Http\Controllers\API\AttendanceDetailController;
use App\Http\Controllers\API\AttendanceHistoryController;
use App\Http\Controllers\API\AttendanceLoginController;
use App\Http\Controllers\API\AttendanceLogoutController;
use App\Http\Controllers\API\AttendanceReasonController;
use App\Http\Controllers\API\AttendanceReasonOutController;
use App\Http\Controllers\API\AwardController;
use App\Http\Controllers\API\CompanyPolicyController;
use App\Http\Controllers\API\DetailOvertimeController;
use App\Http\Controllers\API\DevController;
use App\Http\Controllers\API\DocumentActionController;
use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\EventsController;
use App\Http\Controllers\API\HeadOfController;
use App\Http\Controllers\API\HistoryIntervalController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\LeaderboardController;
use App\Http\Controllers\API\LeaveActionController;
use App\Http\Controllers\API\LeaveController;
use App\Http\Controllers\API\LeaveCreateController;
use App\Http\Controllers\API\LeaveDetailController;
use App\Http\Controllers\API\LeaveListController;
use App\Http\Controllers\API\LeaveTypeController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\LogoutController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\NotificationDetailController;
use App\Http\Controllers\API\OvertimeActionController;
use App\Http\Controllers\API\OvertimeController;
use App\Http\Controllers\API\OvertimeCreateController;
use App\Http\Controllers\API\OvertimeHistoryController;
use App\Http\Controllers\API\OvertimeInController;
use App\Http\Controllers\API\OvertimeOutController;
use App\Http\Controllers\API\PayslipController;
use App\Http\Controllers\API\PermissionTypeController;
use App\Http\Controllers\API\PushNotifController;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\ShiftController;
use App\Http\Controllers\API\UserController;

Route::middleware('auth:sanctum')->get('user', function (Request $request) {
    return $request->user();
});

Route::post('login', LoginController::class);
Route::group(['middleware' => 'jwt.verify'], function () {
    Route::get('user', [UserController::class, 'validation']);
    Route::get('user_active', [UserController::class, 'checkActive']);
    Route::get('profile', [UserController::class, 'getUser']);
    Route::post('profile_update', [UserController::class, 'editProfile']);
    Route::post('password_update', [UserController::class, 'updatePassword']);
    Route::post('logout', LogoutController::class);
    Route::post('attendance_login', AttendanceLoginController::class);
    Route::post('attendance_logout', [AttendanceLogoutController::class, 'logout']);
    Route::post('attendance_reason_out', [AttendanceReasonOutController::class, 'reasonOut']);
    Route::post('attendance_reason', AttendanceReasonController::class);
    Route::get('events', EventsController::class);
    Route::get('attendance_history', AttendanceHistoryController::class);
    Route::get('notification', NotificationController::class);
    Route::get('notification_detail', NotificationDetailController::class);
    Route::get('leaves', LeaveController::class);
    Route::get('leave_detail', LeaveDetailController::class);
    Route::get('leave_type', LeaveTypeController::class);
    Route::post('leave_create', LeaveCreateController::class);
    Route::get('leave_list', LeaveListController::class);
    Route::get('award', AwardController::class);
    Route::get('employee', EmployeeController::class);
    Route::get('payslip', [PayslipController::class, 'employeeSalary']);
    Route::get('commission', [PayslipController::class, 'commissionSalary']);
    Route::get('allowance', [PayslipController::class, 'allowanceSalary']);
    Route::get('overtime', [PayslipController::class, 'overtimeSalary']);
    Route::get('loan', [PayslipController::class, 'loanSalary']);
    Route::get('saturation', [PayslipController::class, 'saturationSalary']);
    Route::get('other', [PayslipController::class, 'otherSalary']);
    Route::get('salary', [PayslipController::class, 'salary']);
    Route::get('headof', HeadOfController::class);
    Route::get('schedule', ScheduleController::class);
    Route::get('shift', ShiftController::class);
    Route::get('setting', SettingController::class);
    Route::get('companypolicy', CompanyPolicyController::class);
    Route::post('attendance_action', AttendanceActionController::class);
    Route::get('attendance_detail', AttendanceDetailController::class);
    Route::post('leave_action', LeaveActionController::class);
    Route::get('document', DocumentController::class);
    Route::post('document_action', DocumentActionController::class);
    Route::get('additional_information', AdditionalInformationController::class);
    Route::post('additional_information_action', AdditionalInformationActionController::class);
    Route::get('permission_type', PermissionTypeController::class);
    Route::get('attendance_history_interval', HistoryIntervalController::class);
    Route::post('pushnotif_izin', [PushNotifController::class, 'permission']);
    Route::post('pushnotif_leave', [PushNotifController::class, 'leave']);
    Route::post('pushnotif_payslip', [PushNotifController::class, 'payslip']);
    Route::post('pushnotif_notif', [PushNotifController::class, 'notif']);
    Route::get('dev_get_user', [DevController::class, 'getEmployee']);
    Route::get('delete_attendance', [DevController::class, 'delAttendance']);
    Route::get('insert_attendance', [DevController::class, 'insAttendance']);
    Route::get('home', [HomeController::class, 'home']);
    Route::get('leaderboard', LeaderboardController::class);
    Route::get('overtime_add', OvertimeController::class);
    Route::post('overtime_create', OvertimeCreateController::class);
    ROute::get('overtime_history', OvertimeHistoryController::class);
    Route::post('overtime_in', OvertimeInController::class);
    Route::post('overtime_out', OvertimeOutController::class);
    Route::post('overtime_action', OvertimeActionController::class);
    Route::get('overtime_detail', DetailOvertimeController::class);
});
