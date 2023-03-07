<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\PositionGroup;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PositionGroupController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Position Group')) {
            $positionGroup    = PositionGroup::where('position_groups.created_by', '=', Auth::user()->creatorId())->orderBy('created_at', 'desc')->get();

            $data = [];
            foreach ($positionGroup as $key => $value) {
                # code...
                $pos = [];
                $gr = [];
                foreach (json_decode($value->position_id) as $x) {
                    $position_name = Position::find($x);
                    if ($position_name) {
                        $pos[] = $position_name->name;
                    } else {
                        $pos[] = '';
                    }
                }

                foreach (json_decode($value->group_id) as $x) {
                    $gr[] = Group::find($x)->name;
                }

                $data[] = [
                    'id' => $value->id,
                    'position' => $pos,
                    'group' => $gr
                ];
            }


            return view('position_group.index', compact('data'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Position Group')) {

            $creator = Auth::user()->creatorId();
            $group = Group::where('created_by', '=', $creator)->get();
            $positionGroup = PositionGroup::where('created_by', '=', $creator)->get();

            $positionInGroup = [];

            foreach ($positionGroup as $val) {
                foreach (json_decode($val->position_id) as $val2) {
                    $positionInGroup[] = $val2;
                }
            }

            $position = Position::where('created_by', '=', $creator)->whereNotIn('id', $positionInGroup)->get();

            return view('position_group.create', compact('position', 'group'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Position Group')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'position_id' => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $position_group                 = new PositionGroup();
            $position_group->position_id    = json_encode($request->position_id);
            $position_group->group_id       = json_encode($request->group_id);
            $position_group->created_by     = Auth::user()->creatorId();

            $position_group->save();

            return redirect()->route('positiongroup.index')->with('success', __('Position Group successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('position_group.index');
    }

    public function edit($positionGroup)
    {
        if (Auth::user()->can('Edit Position Group')) {

            $creator = Auth::user()->creatorId();
            $positionGroup    = PositionGroup::where('id', $positionGroup)->where('position_groups.created_by', '=', $creator)->orderBy('created_at', 'desc')->first();

            $data = [];


            $day = [];

            if ($positionGroup->created_by == $creator) {
                $pos = [];
                $gr = [];
                foreach (json_decode($positionGroup->position_id) as $x) {
                    $pos[] = $x;
                }

                foreach (json_decode($positionGroup->group_id) as $x) {
                    $gr[] = $x;
                }

                $position_select = $pos;
                $group_select = $gr;


                $positionGroupList = PositionGroup::where('created_by', '=', $creator)->where('id', '!=', $positionGroup->id)->get();

                $positionInGroup = [];

                foreach ($positionGroupList as $val) {
                    foreach (json_decode($val->position_id) as $val2) {
                        $positionInGroup[] = $val2;
                    }
                }

                $position = Position::where('created_by', '=', $creator)->whereNotIn('id', $positionInGroup)->get();

                $group = Group::where('created_by', '=', Auth::user()->creatorId())->get();

                return view('position_group.edit', compact('position', 'group', 'positionGroup', 'position_select', 'group_select'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, PositionGroup $positiongroup)
    {
        if (Auth::user()->can('Edit Position Group')) {
            if ($positiongroup->created_by == Auth::user()->creatorId()) {

                $positiongroup->position_id    = json_encode($request->position_id);
                $positiongroup->group_id       = json_encode($request->group_id);
                $positiongroup->save();

                return redirect()->route('positiongroup.index')->with('success', __('Position Group successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(PositionGroup $positiongroup)
    {
        if (Auth::user()->can('Delete Position Group')) {
            if ($positiongroup->created_by == Auth::user()->creatorId()) {
                $positiongroup->delete();

                return redirect()->route('positiongroup.index')->with('success', __('Position Group successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
