<?php

namespace App\Http\Controllers;

use App\Models\AllowanceOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AllowanceOptionController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Allowance Option')) {
            $allowanceoptions = AllowanceOption::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('allowanceoption.index', compact('allowanceoptions'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Allowance Option')) {
            return view('allowanceoption.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Allowance Option')) {

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

            $allowanceoption             = new AllowanceOption();
            $allowanceoption->name       = $request->name;
            $allowanceoption->created_by = Auth::user()->creatorId();
            $allowanceoption->save();

            return redirect()->route('allowanceoption.index')->with('success', __('AllowanceOption  successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('allowanceoption.index');
    }

    public function edit(AllowanceOption $allowanceoption)
    {
        if (Auth::user()->can('Edit Allowance Option')) {
            if ($allowanceoption->created_by == Auth::user()->creatorId()) {

                return view('allowanceoption.edit', compact('allowanceoption'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, AllowanceOption $allowanceoption)
    {
        if (Auth::user()->can('Edit Allowance Option')) {
            if ($allowanceoption->created_by == Auth::user()->creatorId()) {
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
                $allowanceoption->name = $request->name;
                $allowanceoption->save();

                return redirect()->route('allowanceoption.index')->with('success', __('AllowanceOption successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(AllowanceOption $allowanceoption)
    {
        if (Auth::user()->can('Delete Allowance Option')) {
            if ($allowanceoption->created_by == Auth::user()->creatorId()) {
                $allowanceoption->delete();

                return redirect()->route('allowanceoption.index')->with('success', __('AllowanceOption successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
