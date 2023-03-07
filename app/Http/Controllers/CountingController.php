<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Group;
use App\Models\Counting;
use App\Models\EmployeeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CountingController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Counting')) {
            $countings    = Counting::selectRaw('countings.id as id, g.name as group_name, type, start_year, max_year, countings.created_by as created_by')
                ->join('groups as g', 'countings.group_id', '=', 'g.id')
                ->where('countings.created_by', '=', Auth::user()->creatorId())->orderBy('countings.created_at', 'desc')->get();

            $data = [];
            $salary = [];
            foreach ($countings as $key => $value) {
                $salary = [];
                foreach (json_decode($value->type) as $x) {
                    $salary[] = [
                        'id' => EmployeeType::find($x->id)->name,
                        'salary' => $x->salary
                    ];
                }
                $data[] = [
                    'id' => $value->id,
                    'group_name' => $value->group_name,
                    'start_year' => $value->start_year,
                    'max_year' => $value->max_year,
                    'salary' => $salary
                ];
            }

            return view('counting.index', compact('countings', 'data'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Counting')) {

            $group = Group::where('created_by', '=', Auth::user()->creatorId())->get();
            $position = Position::where('created_by', '=', Auth::user()->creatorId())->get();
            $employeetype = EmployeeType::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('counting.create', compact('position', 'group', 'employeetype'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Counting')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'max_year' => 'required',
                    'group_id' => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $checkGroup = Counting::where('group_id', $request->group_id)->first();

            if ($checkGroup) {
                return back()->with('error', __('Group Available.'));
            }

            $type = [];

            $emptype = EmployeeType::where('created_by', Auth::user()->creatorId())->get();
            foreach ($emptype as $key => $value) {
                $type[] = [
                    'id' => $value->id,
                    'salary' => $request['salary' . $value->id]
                ];
            }

            $counting                 = new Counting();
            $counting->group_id       = $request->group_id;
            $counting->start_year       = $request->start_year;
            $counting->max_year       = $request->max_year;
            $counting->type           = json_encode($type);
            $counting->created_by     = Auth::user()->creatorId();

            $counting->save();

            return redirect()->route('counting.index')->with('success', __('Counting successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Counting $counting)
    {
        return redirect()->route('position_group.index');
    }

    public function edit($counting)
    {
        if (Auth::user()->can('Edit Counting')) {
            $counting    = Counting::where('id', $counting)->where('created_by', '=', Auth::user()->creatorId())->orderBy('created_at', 'desc')->first();

            $data = [];


            $day = [];

            if ($counting->created_by == Auth::user()->creatorId()) {
                $data = [];
                $salary = [];
                foreach (json_decode($counting->type) as $x) {
                    $salary[] = [
                        'id' => $x->id,
                        'salary' => $x->salary
                    ];
                }
                $data = [
                    'id' => $counting->id,
                    'group_id' => Group::find($counting->group_id)->name,
                    'max_year' => $counting->max_year,
                    'start_year' => $counting->start_year,
                    'salary' => $salary
                ];

                $group = Group::where('created_by', '=', Auth::user()->creatorId())->get();
                $position = Position::where('created_by', '=', Auth::user()->creatorId())->get();
                $employeetype = EmployeeType::where('created_by', '=', Auth::user()->creatorId())->get();

                return view('counting.edit', compact('position', 'employeetype', 'counting', 'data', 'group'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Counting $counting)
    {
        if (Auth::user()->can('Edit Counting')) {
            if ($counting->created_by == Auth::user()->creatorId()) {

                $type = [];

                $emptype = EmployeeType::where('created_by', Auth::user()->creatorId())->get();
                foreach ($emptype as $key => $value) {
                    $type[] = [
                        'id' => $value->id,
                        'salary' => $request['salary' . $value->id]
                    ];
                }

                $counting->start_year       = $request->start_year;
                $counting->max_year       = $request->max_year;
                $counting->type           = json_encode($type);
                $counting->save();

                return redirect()->route('counting.index')->with('success', __('Counting successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Counting $counting)
    {
        if (Auth::user()->can('Delete Counting')) {
            if ($counting->created_by == Auth::user()->creatorId()) {
                $counting->delete();

                return redirect()->route('counting.index')->with('success', __('Counting successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
