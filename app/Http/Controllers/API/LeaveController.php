<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\User;
use App\Models\Utility;

class LeaveController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $employee = Employee::where('user_id', '=', $request->user_id)->first();

        if (!empty($employee)) {
            $user = User::where('id', $request->user_id)->first();
            $settings = Utility::settings($user->created_by);
            $tiered_leave = $settings['tiered_leave'];

            $module_user = new User();

            $year = (isset($request->year) ? $request->year : date('Y'));

            if ($user->type == 'employee') {
                /**
                 * Employee
                 */
                $leaves   = Leave::where('parent', null)->where('employee_id', '=', $employee->id)->whereYear('created_at', $year)->orderBy('id', 'DESC')->get();
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
                                ->where('created_by', '=', $user->creatorId())->whereYear('created_at', $year)->whereExists(function ($query) {
                                    $query->select('id')
                                        ->from('employees')
                                        ->whereRaw('id = leaves.employee_id');
                                });

                            $leaves = Leave::where('parent', null)
                                ->where('created_by', '=', $user->creatorId())->whereYear('created_at', $year)->whereExists(function ($query) {
                                    $query->select('id')
                                        ->from('employees')
                                        ->whereRaw('id = leaves.employee_id');
                                })
                                ->union($leaves1)->orderBy('id', 'DESC')->get();
                        } else {
                            /**
                             * hrd
                             */
                            $leaves1 = Leave::where('parent', null)
                                ->where('addressed_to', '!=', 0)
                                ->where('created_by', '=', $user->creatorId())->whereYear('created_at', $year)->whereExists(function ($query) {
                                    $query->select('id')
                                        ->from('employees')
                                        ->whereRaw('id = leaves.employee_id');
                                });

                            $leaves = Leave::where('parent', null)
                                ->where('employee_id', $employee->id)
                                ->where('created_by', '=', $user->creatorId())->whereYear('created_at', $year)->whereExists(function ($query) {
                                    $query->select('id')
                                        ->from('employees')
                                        ->whereRaw('id = leaves.employee_id');
                                })
                                ->union($leaves1)->orderBy('id', 'DESC')->get();
                        }
                    } else {
                        /**
                         * head of unit or other
                         */
                        $leaves1 = Leave::where('parent', null)
                            ->where('addressed_to', $employee->department_id)
                            ->where('created_by', '=', $user->creatorId())->whereYear('created_at', $year)->whereExists(function ($query) {
                                $query->select('id')
                                    ->from('employees')
                                    ->whereRaw('id = leaves.employee_id');
                            });

                        $leaves = Leave::where('parent', null)
                            ->where('employee_id', $employee->id)
                            ->where('created_by', '=', $user->creatorId())->whereYear('created_at', $year)->whereExists(function ($query) {
                                $query->select('id')
                                    ->from('employees')
                                    ->whereRaw('id = leaves.employee_id');
                            })
                            ->union($leaves1)->orderBy('id', 'DESC')->get();
                    }
                } else {
                    $leaves = Leave::where('created_by', '=', $user->creatorId())->whereYear('created_at', $year)->whereExists(function ($query) {
                        $query->select('id')
                            ->from('employees')
                            ->whereRaw('id = leaves.employee_id');
                    })->orderBy('id', 'DESC')->get();
                }
            }

            if (empty($leaves)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {

                $my_leave = [];
                $need_approve = [];
                $leave_list = [];
                foreach ($leaves as $key => $value) {

                    $leave_list[] = [
                        'id' => $value->id,
                        'employee_id' => $value->employee_id,
                        'employee' => $value->employees->name,
                        'leave_type_id' => $value->leave_type_id,
                        'leave_type' => $value->leaveType->title,
                        'applied_on' => $value->applied_on,
                        'start_date' => $value->start_date,
                        'end_date' => $value->end_date,
                        'total_leave_days' => $value->total_leave_days,
                        'leave_reason' => $value->leave_reason,
                        'remark' => $value->remark,
                        'status' => $value->status,
                        'addresed_to' => $value->addresed_to,
                        'acc' => json_decode($value->acc),
                        'created_by' => $value->created_by,
                    ];

                    if ($value->employee_id == $employee->id) {
                        $my_leave[] = [
                            'id' => $value->id,
                            'employee_id' => $value->employee_id,
                            'employee' => $value->employees->name,
                            'leave_type_id' => $value->leave_type_id,
                            'leave_type' => $value->leaveType->title,
                            'applied_on' => $value->applied_on,
                            'start_date' => $value->start_date,
                            'end_date' => $value->end_date,
                            'total_leave_days' => $value->total_leave_days,
                            'leave_reason' => $value->leave_reason,
                            'remark' => $value->remark,
                            'status' => $value->status,
                            'addresed_to' => $value->addresed_to,
                            'acc' => json_decode($value->acc),
                            'created_by' => $value->created_by,
                        ];
                    } else {
                        if (!preg_match("/" . $user->type . "/i", $value->acc)) {
                            $need_approve[] = [
                                'id' => $value->id,
                                'employee_id' => $value->employee_id,
                                'employee' => $value->employees->name,
                                'leave_type_id' => $value->leave_type_id,
                                'leave_type' => $value->leaveType->title,
                                'applied_on' => $value->applied_on,
                                'start_date' => $value->start_date,
                                'end_date' => $value->end_date,
                                'total_leave_days' => $value->total_leave_days,
                                'leave_reason' => $value->leave_reason,
                                'remark' => $value->remark,
                                'status' => $value->status,
                                'addresed_to' => $value->addresed_to,
                                'acc' => json_decode($value->acc),
                                'created_by' => $value->created_by,
                            ];
                        }
                    }
                }

                return response()->json([
                    'type' => 'success',
                    'message' => 'available',
                    'data' => [
                        'role'  => $employee->role->name,
                        'my_leave' => $my_leave,
                        'need_approve' => $need_approve,
                        'leave_list' => ($user->type == 'employee' ? [] : $leave_list)
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
}
