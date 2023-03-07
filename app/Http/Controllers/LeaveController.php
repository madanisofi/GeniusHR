<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Mail\LeaveActionSend;
use App\Models\Utility;
use App\Models\User;
use App\Models\Notification;
use App\Models\NotificationEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Exports\LeaveExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Events\SendMessage;
use App\Helpers\Fcm;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Leave')) {
            // Check if Leave Type is empty
            $leave_type_checker = LeaveType::where('created_by', '=', Auth::user()->creatorId())->count();
            if ($leave_type_checker == 0) {
                return redirect()->route('leavetype.index')->with('error', __('Please create leave type first.'));
            } else {
                $user     = Auth::user();
                $employee = Employee::where('user_id', '=', $user->id)->first();
                $tiered_leave = Utility::getValByName('tiered_leave');
                if ($user->type == 'employee') {
                    /**
                     * Employee
                     */
                    $leaves   = Leave::where('parent', null)->where('employee_id', '=', $employee->id)->get();
                } else {
                    if ($tiered_leave == 'on') {
                        if (empty($user->role->level)) {
                            if ($user->created_by == 1) {
                                /**
                                 * Company / Super Admin in company
                                 */
                                $leaves1 = Leave::where('parent', null)
                                    ->whereIn('acc', array('Approve', 'Persetujuan'))
                                    ->where('addressed_to', 0)
                                    ->where('created_by', '=', $user->creatorId())->whereExists(function ($query) {
                                        $query->select('id')
                                            ->from('employees')
                                            ->whereRaw('id = leaves.employee_id');
                                    });

                                $leaves = Leave::where('parent', null)
                                    ->where('created_by', '=', $user->creatorId())->whereExists(function ($query) {
                                        $query->select('id')
                                            ->from('employees')
                                            ->whereRaw('id = leaves.employee_id');
                                    })
                                    ->union($leaves1)->get();
                            } else {
                                /**
                                 * hrd
                                 */
                                $leaves1 = Leave::where('parent', null)
                                    ->where('addressed_to', '!=', 0)
                                    ->where('created_by', '=', $user->creatorId())->whereExists(function ($query) {
                                        $query->select('id')
                                            ->from('employees')
                                            ->whereRaw('id = leaves.employee_id');
                                    });

                                $leaves = Leave::where('parent', null)
                                    ->where('employee_id', $employee->id)
                                    ->where('created_by', '=', $user->creatorId())->whereExists(function ($query) {
                                        $query->select('id')
                                            ->from('employees')
                                            ->whereRaw('id = leaves.employee_id');
                                    })
                                    ->union($leaves1)->get();
                            }
                        } else {
                            /**
                             * head of unit or other
                             */
                            $leaves1 = Leave::where('parent', null)
                                ->where('addressed_to', $employee->department_id)
                                ->where('created_by', '=', $user->creatorId())->whereExists(function ($query) {
                                    $query->select('id')
                                        ->from('employees')
                                        ->whereRaw('id = leaves.employee_id');
                                });

                            $leaves = Leave::where('parent', null)
                                ->where('employee_id', $employee->id)
                                ->where('created_by', '=', $user->creatorId())->whereExists(function ($query) {
                                    $query->select('id')
                                        ->from('employees')
                                        ->whereRaw('id = leaves.employee_id');
                                })
                                ->union($leaves1)->get();
                        }
                    } else {
                        $leaves = Leave::where('created_by', '=', $user->creatorId())->whereExists(function ($query) {
                            $query->select('id')
                                ->from('employees')
                                ->whereRaw('id = leaves.employee_id');
                        })->get();
                    }
                }

                $role = $user->type;

                return view('leave.index', compact('leaves', 'role', 'tiered_leave', 'user'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Leave')) {
            if (Auth::user()->type == 'employee') {
                $employees = Employee::where('user_id', '=', Auth::user()->id)->get()->pluck('name', 'id');
            } else {
                $employees = Employee::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');
            }
            $leavetypes      = LeaveType::where('created_by', '=', Auth::user()->creatorId())->get();
            $leavetypes_days = LeaveType::where('created_by', '=', Auth::user()->creatorId())->get();
            $tiered_leave = Utility::getValByName('tiered_leave');

            return view('leave.create', compact('employees', 'leavetypes', 'leavetypes_days', 'tiered_leave'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Leave')) {
            $tiered_leave = Utility::getValByName('tiered_leave');
            $validator = Validator::make(
                $request->all(),
                [
                    'leave_type_id' => 'required',
                    'start_date' => 'required',
                    // 'end_date' => 'required',
                    'leave_reason' => 'required',
                    // 'remark' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $leave    = new Leave();
            if (Auth::user()->type == "employee") {
                $employee = Employee::where('user_id', '=', Auth::user()->id)->first();
                $leave->employee_id = $employee->id;
            } else {
                $leave->employee_id = $request->employee_id;
                $employee = Employee::where('id', '=', $request->employee_id)->first();
            }

            $status_acc = json_encode([]);

            // get leave selected
            $leave_select = LeaveType::find($request->leave_type_id);

            if ($leave_select->select_all == 'on') {
                $request_start = $request->start_date;
                $request_end = date('Y-m-d', strtotime('+' . ((int)$leave_select->days - 1) . ' days', strtotime($request->start_date)));
                // leave day
                $total_leave_days = $leave_select->days;
            } else {
                $request_start = $request->start_date;
                $request_end = $request->end_date;

                // leave day
                $startDate = new \DateTime($request->start_date);
                $endDate = new \DateTime($request->end_date);
                $total_leave_days = !empty($startDate->diff($endDate)) ? $endDate->diff($startDate)->days + 1 : 0;
            }

            $emp_days_off = Leave::where('leave_type_id', $request->leave_type_id)->where('employee_id', $request->employee_id)->where('status', '=', 'Approve')->where('acc', '=', 'Approve')->whereYear('applied_on', date('Y'))->sum('total_leave_days');
            $total_days_off = LeaveType::find($request->leave_type_id)->days;
            $remaining_days_off = (int)$total_days_off - ((int)$emp_days_off + (int)$total_leave_days);

            if ($remaining_days_off < 0) {
                return redirect()->back()->with('error', __('Leave Has Exceeded The Limit.'));
            }

            $leave->leave_type_id    = $request->leave_type_id;
            $leave->applied_on       = date('Y-m-d');
            $leave->start_date       = $request_start;
            $leave->end_date         = $request_end;
            $leave->total_leave_days = $total_leave_days;
            $leave->leave_reason     = $request->leave_reason;
            $leave->remark           = $request->remark;
            $leave->status           = 'Pending';

            if (Employee::getType($employee->id)->type != 'employee' or $tiered_leave != 'on') {
                $leave->addressed_to = 0;
            } else {
                $leave->addressed_to = $employee->department_id;
            }

            $leave->acc              = $status_acc;
            $leave->created_by       = Auth::user()->creatorId();
            $leave->save();

            // chech parenting
            $getParent = LeaveType::where('id', $request->leave_type_id)->first();
            $reduction = $getParent->reduction;
            // $reduction = 0;
            $remain_rdcn = $reduction;
            $now = date('m');
            // $now = 7;
            if ($getParent->parent != null) {
                $searchLeave = LeaveType::whereIn('id', json_decode($getParent->parent))->get();
                $count_searchLeave = count($searchLeave);
                $mean = 12 / $count_searchLeave; #12 = jumlah bulan

                foreach ($searchLeave as $x) {
                    $start = $x->start_date;
                    $end = $x->end_date;

                    if ($start != '00' && $end != '00') {

                        if ($now <= $mean) {
                            // under 7
                            if (($now >= (int)$start && $now <= (int)$end) or $remain_rdcn > 0) {
                                $remain = Leave::where('leave_type_id', $x->id)
                                    ->where('employee_id', $request->employee_id)
                                    ->where('status', '=', 'Approve')
                                    ->where('acc', '=', 'Approve')
                                    ->whereYear('applied_on', date('Y'))
                                    ->sum('total_leave_days');
                                $remain_off = (int)$reduction - ((int)$x->days - (int)$remain);

                                if ($remain_off > 0) {
                                    // insert again
                                    $remain_rdcn = $remain_off;
                                    $accumulate_total_leave_days = (int)$x->days - (int)$remain;
                                    $reduction = $remain_off;
                                    // echo 'other one : ' . $accumulate_total_leave_days . ' - ' . $x->id . '<br>';

                                    // new insert
                                    Leave::create([
                                        'employee_id' => $leave->employee_id,
                                        'leave_type_id' => $x->id,
                                        'applied_on' => date('Y-m-d'),
                                        'total_leave_days' => $accumulate_total_leave_days,
                                        'leave_reason' => $leave_select->title,
                                        'status' => 'Pending',
                                        'remark' => 'Accumulate',
                                        'acc' => $status_acc,
                                        'parent' => $leave->id,
                                        'created_by' => Auth::user()->creatorId()
                                    ]);
                                } else {
                                    // insert one 
                                    $remain_rdcn = 0;
                                    $accumulate_total_leave_days = $reduction;
                                    $reduction = 0;

                                    // echo 'one : ' . $accumulate_total_leave_days . ' - ' . $x->id;

                                    // new insert
                                    Leave::create([
                                        'employee_id' => $leave->employee_id,
                                        'leave_type_id' => $x->id,
                                        'applied_on' => date('Y-m-d'),
                                        'total_leave_days' => $accumulate_total_leave_days,
                                        'leave_reason' => $leave_select->title,
                                        'status' => 'Pending',
                                        'remark' => 'Accumulate',
                                        'acc' => $status_acc,
                                        'parent' => $leave->id,
                                        'created_by' => Auth::user()->creatorId()
                                    ]);
                                }
                            }
                        } else {
                            if ($now >= (int)$start && $now <= (int)$end) {
                                $remain = Leave::where('leave_type_id', $x->id)
                                    ->where('employee_id', $request->employee_id)
                                    ->where('status', '=', 'Approve')
                                    ->where('acc', '=', 'Approve')
                                    ->whereYear('applied_on', date('Y'))
                                    ->sum('total_leave_days');
                                $remain_off = (int)$reduction - ((int)$x->days - (int)$remain);

                                $remain_rdcn = 0;
                                $accumulate_total_leave_days = $reduction;
                                $reduction = 0;

                                // echo 'one: ' . $accumulate_total_leave_days . ' - ' . $x->id;

                                Leave::create([
                                    'employee_id' => $leave->employee_id,
                                    'leave_type_id' => $x->id,
                                    'applied_on' => date('Y-m-d'),
                                    'total_leave_days' => $accumulate_total_leave_days,
                                    'leave_reason' => $leave_select->title,
                                    'status' => 'Pending',
                                    'remark' => 'Accumulate',
                                    'acc' => $status_acc,
                                    'parent' => $leave->id,
                                    'created_by' => Auth::user()->creatorId()
                                ]);
                            }
                        }
                    } else {
                        // new insert
                        Leave::create([
                            'employee_id' => $leave->employee_id,
                            'leave_type_id' => $x->id,
                            'applied_on' => date('Y-m-d'),
                            'total_leave_days' => $reduction,
                            'leave_reason' => $leave_select->title,
                            'status' => 'Pending',
                            'remark' => 'Accumulate',
                            'acc' => $status_acc,
                            'parent' => $leave->id,
                            'created_by' => Auth::user()->creatorId()
                        ]);
                    }
                }
            }

            $array = [
                'name' => $employee->name,
                'emp_id' => $employee->employee_id,
                'created_by' => $employee->created_by,
                'title' => 'Pengajuan Cuti',
                'type' => 'notif',
                'to' => $employee->department_id #ditujukan ke kepala bagian dan hrd & super admin (level > 0)
            ];
            event(new SendMessage($array));

            return redirect()->route('leave.index')->with('success', __('Leave  successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Leave $leave)
    {
        return redirect()->route('leave.index');
    }

    public function edit(Leave $leave)
    {
        if (Auth::user()->can('Edit Leave')) {
            if ($leave->created_by == Auth::user()->creatorId()) {
                $employees  = Employee::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');
                $leavetypes = LeaveType::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('title', 'id');
                $headof = User::selectRaw('users.id as id, users.name as name')->join('roles as r', 'users.type', '=', 'r.name')->where('level', '>', 0)->where('users.created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');

                $check_select_leave = LeaveType::find($leave->leave_type_id)->select_all;

                $tiered_leave = Utility::getValByName('tiered_leave');

                return view('leave.edit', compact('leave', 'employees', 'leavetypes', 'headof', 'check_select_leave', 'tiered_leave'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $leave)
    {

        $leave = Leave::find($leave);
        if (Auth::user()->can('Edit Leave')) {
            if ($leave->created_by == Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        // 'leave_type_id' => 'required',
                        'start_date' => 'required',
                        // 'end_date' => 'required',
                        'leave_reason' => 'required',
                        'remark' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                // get leave selected
                $leave_select = LeaveType::find($leave->leave_type_id);

                if ($leave_select->select_all == 'on') {
                    $request_start = $request->start_date;
                    $request_end = date('Y-m-d', strtotime('+' . ((int)$leave_select->days - 1) . ' days', strtotime($request->start_date)));
                    // leave day
                    $total_leave_days = $leave_select->days;
                } else {
                    $request_start = $request->start_date;
                    $request_end = $request->end_date;

                    // leave day
                    $startDate = new \DateTime($request->start_date);
                    $endDate = new \DateTime($request->end_date);
                    $total_leave_days = !empty($startDate->diff($endDate)) ? $endDate->diff($startDate)->days + 1 : 0;
                }

                $emp_days_off = Leave::where('leave_type_id', $request->leave_type_id)->where('employee_id', $request->employee_id)->where('status', '=', 'Approve')->where('acc', '=', 'Approve')->sum('total_leave_days');
                // $total_days_off = LeaveType::find($request->leave_type_id)->days;
                $total_days_off = LeaveType::find($leave->leave_type_id)->days;
                $remaining_days_off = (int)$total_days_off - ((int)$emp_days_off + (int)$total_leave_days - (int)$leave->total_leave_days);

                if ($remaining_days_off < 0) {
                    return redirect()->back()->with('error', __('Leave Has Exceeded The Limit.'));
                }

                if (Auth::user()->type != "employee") {
                    $leave->employee_id = $request->employee_id;
                }

                // $leave->leave_type_id    = $request->leave_type_id;
                $leave->start_date       = $request_start;
                $leave->end_date         = $request_end;
                $leave->total_leave_days = $total_leave_days;
                $leave->leave_reason     = $request->leave_reason;
                $leave->remark           = $request->remark;
                if (isset($request->addressed_to)) $leave->addressed_to     = $request->addressed_to;

                $leave->save();

                return redirect()->route('leave.index')->with('success', __('Leave successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Leave $leave)
    {
        if (Auth::user()->can('Delete Leave')) {
            if ($leave->created_by == Auth::user()->creatorId()) {
                Leave::where('parent', $leave->id)->delete();
                $leave->delete();

                return redirect()->route('leave.index')->with('success', __('Leave successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function export()
    {
        $name = 'leave_' . date('Y-m-d i:h:s');
        $data = Excel::download(new LeaveExport(), $name . '.xlsx');
        ob_end_clean();

        return $data;
    }



    public function action($id)
    {
        $leave     = Leave::find($id);
        $employee  = Employee::find($leave->employee_id);
        $leavetype = LeaveType::find($leave->leave_type_id);
        $tiered_leave = Utility::getValByName('tiered_leave');


        return view('leave.action', compact('employee', 'leavetype', 'leave', 'tiered_leave'));
    }

    public function changeaction(Request $request)
    {

        $leave = Leave::find($request->leave_id);
        $tiered_leave = Utility::getValByName('tiered_leave');

        // $leave->status = $request->status;
        if ($request->status == 'Approval') {
            $startDate               = new \DateTime($leave->start_date);
            $endDate                 = new \DateTime($leave->end_date);
            $total_leave_days        = $startDate->diff($endDate)->days;
            $leave->total_leave_days = $total_leave_days;
            // $leave->status           = 'Approve';
        }

        $approve = [];
        $status_approve = '';
        $status_from_hr = '';
        if ($request->status == 'Approve') $status_approve = 'Menyetujui';
        else $status_approve = 'Menolak';

        if (Auth::user()->getLevel(Auth::user()->type) == null or $tiered_leave != 'on') {
            $leave->status           = $request->status;
            $status_from_hr = $request->status;
            $user = Auth::user();
            if (count(json_decode($leave->acc)) > 0) {
                $approve = json_decode($leave->acc);
                $check_role = array_search($user->role_id, array_column($approve, 'author'));

                // return $check_role;
                if ($check_role !== false) {
                    // return 'ada';
                    $approve[$check_role] = ['author' => $user->role_id, 'type' => $user->type, 'status' => $request->status];
                } else {
                    array_push($approve, ['author' => $user->role_id, 'type' => $user->type, 'status' => $request->status]);
                }
            } else {

                $approve[] = ['author' => $user->role_id, 'type' => $user->type, 'status' => $request->status];
            }

            $leave->acc           = json_encode($approve);
            Leave::where('parent', $request->leave_id)->update(['status' => $request->status]);
        } else {
            $user = Auth::user();
            if (count(json_decode($leave->acc)) > 0) {
                $approve = json_decode($leave->acc);
                $check_role = array_search($user->role_id, array_column($approve, 'author'));

                // return $check_role;
                if ($check_role !== false) {
                    // return 'ada';
                    $approve[$check_role] = ['author' => $user->role_id, 'type' => $user->type, 'status' => $request->status];
                } else {
                    array_push($approve, ['author' => $user->role_id, 'type' => $user->type, 'status' => $request->status]);
                }
            } else {

                $approve[] = ['author' => $user->role_id, 'type' => $user->type, 'status' => $request->status];
            }

            $leave->acc           = json_encode($approve);
            Leave::where('parent', $request->leave_id)->update(['acc' => $request->status]);
        }

        if ($status_from_hr != '') {
            #notif send to employee (approve or reject)
            $firebaseToken = [$leave->employees->user->fcm_token];
            $userNotif = [$leave->employees->user->id];

            if ($leave->employees->user->fcm_token != null) {

                $data = [
                    "registration_ids" => $firebaseToken,
                    "notification" => [
                        "title" => 'Persetujuan Cuti',
                        "body" => 'Pengajuan Cuti, ' .  ($status_from_hr == 'Approve' ? 'Disetujui' : 'Ditolak'),
                    ],
                    "data" => [
                        "type" => "Leave",
                        "id" => $leave->id
                    ]
                ];

                Fcm::sendMessage($data);

                // save notif into database
                $notification               = new Notification();
                $notification->title        = 'Persetujuan Cuti';
                $notification->type         = 'Leave';
                $notification->messages     = 'Pengajuan Cuti, ' . ($status_from_hr == 'Approve' ? 'Disetujui' : 'Ditolak');
                $notification->users        = implode(",", $userNotif);
                $notification->created_by   = $user->created_by;
                $notification->save();

                foreach ($userNotif as $userId) {
                    $notificationEmployee                  = new NotificationEmployee();
                    $notificationEmployee->notification_id = $notification->id;
                    $notificationEmployee->user_id         = $userId;
                    $notificationEmployee->created_by      = $user->created_by;

                    $notificationEmployee->save();
                }
            }
        } else {
            #notif send to hr / head of / other 
            $getUser = Employee::selectRaw('users.id, users.name, users.fcm_token, users.type, users.role_id, employees.department_id')
                ->join('users', 'employees.user_id', '=', 'users.id')
                ->where('employees.created_by', $user->created_by)
                ->where('users.type', '!=', 'employee')
                ->where('users.fcm_token', '!=', null)
                ->where('users.id', '!=', $request->user_id)
                ->get();

            $firebaseToken = [];
            $userNotif = [];
            foreach ($getUser as $x) {
                if ($x->department_id == $leave->employees->department_id or $x->type == 'hr') {
                    array_push($firebaseToken, $x->fcm_token);
                    array_push($userNotif, $x->id);
                }
            }

            $data = [
                "registration_ids" => $firebaseToken,
                "notification" => [
                    "title" => 'Persetujuan Cuti',
                    "body" => $user->name . ' ' . $status_approve . ' Pengajuan Cuti ' . $leave->employees->name,
                ],
                "data" => [
                    "type" => "Leave",
                    "id" => $leave->id
                ]
            ];

            Fcm::sendMessage($data);

            // save notif into database
            $notification               = new Notification();
            $notification->title        = 'Persetujuan Cuti';
            $notification->type         = 'Leave';
            $notification->messages     = $user->name . ' ' . $status_approve . ' Pengajuan Cuti ' . $leave->employees->name;
            $notification->users        = implode(",", $userNotif);
            $notification->created_by   = $user->created_by;
            $notification->save();

            foreach ($userNotif as $userId) {
                $notificationEmployee                  = new NotificationEmployee();
                $notificationEmployee->notification_id = $notification->id;
                $notificationEmployee->user_id         = $userId;
                $notificationEmployee->created_by      = $user->created_by;

                $notificationEmployee->save();
            }
        }


        $leave->save();

        // twilio  
        $setting = Utility::settings(Auth::user()->creatorId());
        $emp = Employee::find($leave->employee_id);
        if (isset($setting['twilio_leave_approve_notification']) && $setting['twilio_leave_approve_notification'] == 1) {
            $msg = __("Your leave has been") . ' ' . $leave->status . '.';


            Utility::send_twilio_msg($emp->phone, $msg);
        }

        $setings = Utility::settings();
        if ($setings['leave_status'] == 1) {
            $employee     = Employee::where('id', $leave->employee_id)->where('created_by', '=', Auth::user()->creatorId())->first();
            $leave->name  = !empty($employee->name) ? $employee->name : '';
            $leave->email = !empty($employee->email) ? $employee->email : '';
            try {
                Mail::to($leave->email)->send(new LeaveActionSend($leave));
            } catch (\Exception $e) {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }

            return redirect()->route('leave.index')->with('success', __('Leave status successfully updated.') . (isset($smtp_error) ? $smtp_error : ''));
        }

        return redirect()->route('leave.index')->with('success', __('Leave status successfully updated.'));
    }

    public function jsoncount(Request $request)
    {
        $leave_counts = LeaveType::select(\DB::raw('COALESCE(SUM(leaves.total_leave_days),0) AS total_leave, leave_types.title, leave_types.days,leave_types.id'))
            ->leftjoin(
                'leaves',
                function ($join) use ($request) {
                    $join->on('leaves.leave_type_id', '=', 'leave_types.id');
                    $join->where('leaves.employee_id', '=', $request->employee_id);
                    $join->where('leaves.status', '=', 'Approve');
                    // $join->where('leaves.acc', '=', 'Approve');
                    $join->whereYear('leaves.applied_on', date('Y'));
                }
            )->where('leave_types.created_by', Auth::user()->creatorId())->groupBy('leave_types.id')->get();

        $checktype = Employee::getType($request->employee_id)->type;

        if ($checktype == 'employee') {
            $addresedto = User::selectRaw('users.id as id, users.name as name')->join('roles as r', 'users.type', '=', 'r.name')->where('level', '>', 0)->where('users.created_by', '=', Auth::user()->creatorId())->get();
        } else {
            $addresedto = User::selectRaw('users.id as id, users.name as name')->where('users.id', '=', Auth::user()->creatorId())->get();
        }

        return response()->json([
            'count' => $leave_counts,
            'addresedto' => $addresedto
        ]);
    }

    public function leavevalidate(Request $request)
    {
        return LeaveType::find($request->leave_type_id)->select_all;
    }
}
