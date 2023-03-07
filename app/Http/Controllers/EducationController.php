<?php

namespace App\Http\Controllers;

use App\Models\Educations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EducationController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Education')) {
            $educations = Educations::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('education.index', compact('educations'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Education')) {
            return view('education.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {

        if (Auth::user()->can('Create Education')) {

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
            $education             = new Educations();
            $education->name       = $request->name;
            $education->created_by = Auth::user()->creatorId();
            $education->save();

            return redirect()->route('education.index')->with('success', __('Education  successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('education.index');
    }

    public function edit(Educations $education)
    {
        if (Auth::user()->can('Edit Education')) {
            if ($education->created_by == Auth::user()->creatorId()) {

                return view('education.edit', compact('education'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Educations $education)
    {
        if (Auth::user()->can('Edit Education')) {
            if ($education->created_by == Auth::user()->creatorId()) {
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

                $education->name = $request->name;
                $education->save();

                return redirect()->route('education.index')->with('success', __('Education successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Educations $education)
    {
        if (Auth::user()->can('Delete Education')) {
            if ($education->created_by == Auth::user()->creatorId()) {
                $education->delete();

                return redirect()->route('education.index')->with('success', __('Education successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
