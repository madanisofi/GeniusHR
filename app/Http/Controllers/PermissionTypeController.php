<?php

namespace App\Http\Controllers;

use App\Models\PermissionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PermissionTypeController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Permission Type')) {
            $permissiontypes = PermissionType::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('permissiontype.index', compact('permissiontypes'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Permission Type')) {
            return view('permissiontype.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {

        if (Auth::user()->can('Create Permission Type')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'title' => 'required|max:20',
                    'days' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            if (!isset($request->get_consumption_fee)) {
                $request->get_consumption_fee = 'no';
            }
            if (!isset($request->clock_out)) {
                $request->clock_out = 'no';
            }
            if (!isset($request->many_submission)) {
                $request->many_submission = 'no';
            }

            $permissiontype             = new permissionType();
            $permissiontype->title       = $request->title;
            $permissiontype->days       = $request->days;
            $permissiontype->many_submission       = $request->many_submission;
            $permissiontype->clock_out       = $request->clock_out;
            $permissiontype->get_consumption_fee       = $request->get_consumption_fee;
            $permissiontype->created_by = Auth::user()->creatorId();
            $permissiontype->save();

            return redirect()->route('permissiontype.index')->with('success', __('Permission Type successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('permissiontype.index');
    }

    public function edit(PermissionType $permissiontype)
    {
        if (Auth::user()->can('Edit Permission Type')) {
            if ($permissiontype->created_by == Auth::user()->creatorId()) {

                return view('permissiontype.edit', compact('permissiontype'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, PermissionType $permissiontype)
    {
        if (Auth::user()->can('Edit Permission Type')) {
            if ($permissiontype->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'title' => 'required',
                        'days' => 'required'
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }

                if (!isset($request->get_consumption_fee)) {
                    $request->get_consumption_fee = 'no';
                }
                if (!isset($request->clock_out)) {
                    $request->clock_out = 'no';
                }
                if (!isset($request->many_submission)) {
                    $request->many_submission = 'no';
                }

                $permissiontype->title = $request->title;
                $permissiontype->days = $request->days;
                $permissiontype->many_submission = $request->many_submission;
                $permissiontype->clock_out = $request->clock_out;
                $permissiontype->get_consumption_fee       = $request->get_consumption_fee;
                $permissiontype->save();

                return redirect()->route('permissiontype.index')->with('success', __('Permission Type successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(PermissionType $permissiontype)
    {
        if (Auth::user()->can('Delete Permission Type')) {
            if ($permissiontype->created_by == Auth::user()->creatorId()) {
                $permissiontype->delete();

                return redirect()->route('permissiontype.index')->with('success', __('Permission Type successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
