<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Department')) {
            $departments = Department::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('department.index', compact('departments'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Department')) {
            $branch = Branch::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('department.create', compact('branch'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Department')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'branch_id' => 'required',
                    'name' => 'required|max:70',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $department             = new Department();
            $department->branch_id  = $request->branch_id;
            $department->name       = $request->name;
            $department->created_by = Auth::user()->creatorId();
            $department->save();

            return redirect()->route('department.index')->with('success', __('Department  successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('department.index');
    }

    public function edit(Department $department)
    {
        if (Auth::user()->can('Edit Department')) {
            if ($department->created_by == Auth::user()->creatorId()) {
                $branch = Branch::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');

                return view('department.edit', compact('department', 'branch'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Department $department)
    {
        if (Auth::user()->can('Edit Department')) {
            if ($department->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'branch_id' => 'required',
                        'name' => 'required|max:70',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }

                $department->branch_id = $request->branch_id;
                $department->name      = $request->name;
                $department->save();

                return redirect()->route('department.index')->with('success', __('Department successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Department $department)
    {
        if (Auth::user()->can('Delete Department')) {
            if ($department->created_by == Auth::user()->creatorId()) {
                $department->delete();

                return redirect()->route('department.index')->with('success', __('Department successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
