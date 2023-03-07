<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Position')) {
            $positions = Position::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('position.index', compact('positions'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Position')) {
            return view('position.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Position')) {

            $validator = Validator::make(
                $request->all(),
                [

                    'name' => 'required|max:50',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $position             = new Position();
            $position->name       = $request->name;
            $position->created_by = Auth::user()->creatorId();
            $position->save();

            return redirect()->route('position.index')->with('success', __('Position successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('roomtype.index');
    }

    public function edit(Position $position)
    {
        if (Auth::user()->can('Edit Shift')) {
            if ($position->created_by == Auth::user()->creatorId()) {

                return view('position.edit', compact('position'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Position $position)
    {
        if (Auth::user()->can('Edit Position')) {
            if ($position->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [

                        'name' => 'required|max:50',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }

                $position->name = $request->name;
                $position->save();

                return redirect()->route('position.index')->with('success', __('Position successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Position $position)
    {
        if (Auth::user()->can('Delete Position')) {
            if ($position->created_by == Auth::user()->creatorId()) {
                $position->delete();

                return redirect()->route('position.index')->with('success', __('Position successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
