<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoomTypeController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Room Type')) {
            $roomtypes = RoomType::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('roomtype.index', compact('roomtypes'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Room Type')) {
            return view('roomtype.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Room Type')) {

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

            $roomtype             = new RoomType();
            $roomtype->name       = $request->name;
            $roomtype->created_by = Auth::user()->creatorId();
            $roomtype->save();

            return redirect()->route('roomtype.index')->with('success', __('Room Type successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('roomtype.index');
    }

    public function edit(RoomType $roomtype)
    {
        if (Auth::user()->can('Edit Shift')) {
            if ($roomtype->created_by == Auth::user()->creatorId()) {

                return view('roomtype.edit', compact('roomtype'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, RoomType $roomtype)
    {
        if (Auth::user()->can('Edit Room Type')) {
            if ($roomtype->created_by == Auth::user()->creatorId()) {
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

                $roomtype->name = $request->name;
                $roomtype->save();

                return redirect()->route('roomtype.index')->with('success', __('Room Type successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(RoomType $roomtype)
    {
        if (Auth::user()->can('Delete Room Type')) {
            if ($roomtype->created_by == Auth::user()->creatorId()) {
                $roomtype->delete();

                return redirect()->route('roomtype.index')->with('success', __('Room Type successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
