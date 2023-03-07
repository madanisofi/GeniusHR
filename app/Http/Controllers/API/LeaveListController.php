<?php

namespace App\Http\Controllers\API;

use App\Models\Leave;
use App\Models\Employee;
use App\Models\User;
use App\Models\Utility;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveListController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'year'  => 'required'
        ]);

        $employee = Employee::where('user_id', '=', $request->user_id)->first();

        if (!empty($employee)) {
            $user = User::where('id', $request->user_id)->first();

            if ($user->type == 'employee') {
                return response()->json([
                    'type' => 'error',
                    'message' => 'permission denied'
                ]);
            }

            $settings = Utility::settings($user->created_by);

            $tiered_leave = $settings['tiered_leave'];
            if ($tiered_leave == 'on') {
                if (empty($user->role->level)) {
                    if ($user->created_by == 1) {
                        /**
                         * Company / Super Admin in company
                         */
                        $leaves1 = Leave::where('parent', null)
                            ->whereIn('acc', array('Approve', 'Persetujuan'))
                            ->where('addressed_to', 0)
                            ->whereYear('applied_on', $request->year)
                            ->where('created_by', '=', $user->creatorId())->whereExists(function ($query) {
                                $query->select('id')
                                    ->from('employees')
                                    ->whereRaw('id = leaves.employee_id');
                            });

                        $leaves = Leave::where('parent', null)
                            ->whereYear('applied_on', $request->year)
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
                            ->whereYear('applied_on', $request->year)
                            ->where('created_by', '=', $user->creatorId())->whereExists(function ($query) {
                                $query->select('id')
                                    ->from('employees')
                                    ->whereRaw('id = leaves.employee_id');
                            });

                        $leaves = Leave::where('parent', null)
                            ->where('employee_id', $employee->id)
                            ->whereYear('applied_on', $request->year)
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
                        ->whereYear('applied_on', $request->year)
                        ->where('created_by', '=', $user->creatorId())->whereExists(function ($query) {
                            $query->select('id')
                                ->from('employees')
                                ->whereRaw('id = leaves.employee_id');
                        });

                    $leaves = Leave::where('parent', null)
                        ->where('employee_id', $employee->id)
                        ->whereYear('applied_on', $request->year)
                        ->where('created_by', '=', $user->creatorId())->whereExists(function ($query) {
                            $query->select('id')
                                ->from('employees')
                                ->whereRaw('id = leaves.employee_id');
                        })
                        ->union($leaves1)->get();
                }
            } else {
                $leaves = Leave::where('created_by', '=', $user->creatorId())->whereYear('applied_on', $request->year)->whereExists(function ($query) {
                    $query->select('id')
                        ->from('employees')
                        ->whereRaw('id = leaves.employee_id');
                })->get();
            }

            if (empty($leaves)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {

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
                }

                return response()->json([
                    'type' => 'success',
                    'message' => 'available',
                    'data' => $leave_list,
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
