<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Group')) {
            $groups = Group::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('group.index', compact('groups'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Group')) {
            return view('group.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Group')) {

            $validator = Validator::make(
                $request->all(),
                [

                    'name' => 'required|max:20',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $group             = new Group();
            $group->name       = $request->name;
            $group->created_by = Auth::user()->creatorId();
            $group->save();

            return redirect()->route('group.index')->with('success', __('Group successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('group.index');
    }

    public function edit(Group $group)
    {
        if (Auth::user()->can('Edit Shift')) {
            if ($group->created_by == Auth::user()->creatorId()) {

                return view('group.edit', compact('group'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Group $group)
    {
        if (Auth::user()->can('Edit Group')) {
            if ($group->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [

                        'name' => 'required|max:20',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }

                $group->name = $request->name;
                $group->save();

                return redirect()->route('group.index')->with('success', __('Group successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Group $group)
    {
        if (Auth::user()->can('Delete Group')) {
            if ($group->created_by == Auth::user()->creatorId()) {
                $group->delete();

                return redirect()->route('group.index')->with('success', __('Group successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
