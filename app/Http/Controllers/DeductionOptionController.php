<?php

namespace App\Http\Controllers;

use App\Models\DeductionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DeductionOptionController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Deduction Option')) {
            $deductionoptions = DeductionOption::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('deductionoption.index', compact('deductionoptions'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Deduction Option')) {
            return view('deductionoption.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Deduction Option')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $deductionoption             = new DeductionOption();
            $deductionoption->name       = $request->name;
            $deductionoption->created_by = Auth::user()->creatorId();
            $deductionoption->save();

            return redirect()->route('deductionoption.index')->with('success', __('DeductionOption  successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('deductionoption.index');
    }

    public function edit($deductionoption)
    {
        $deductionoption = DeductionOption::find($deductionoption);
        if (Auth::user()->can('Edit Deduction Option')) {
            if ($deductionoption->created_by == Auth::user()->creatorId()) {

                return view('deductionoption.edit', compact('deductionoption'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, DeductionOption $deductionoption)
    {
        if (Auth::user()->can('Edit Deduction Option')) {
            if ($deductionoption->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',

                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }
                $deductionoption->name = $request->name;
                $deductionoption->save();

                return redirect()->route('deductionoption.index')->with('success', __('DeductionOption successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(DeductionOption $deductionoption)
    {
        if (Auth::user()->can('Delete Deduction Option')) {
            if ($deductionoption->created_by == Auth::user()->creatorId()) {
                $deductionoption->delete();

                return redirect()->route('deductionoption.index')->with('success', __('DeductionOption successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
