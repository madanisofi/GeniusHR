<?php

namespace App\Http\Controllers;

use App\Models\TrainingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TrainingTypeController extends Controller
{

    public function index()
    {
        if (Auth::user()->can('Manage Training Type')) {
            $trainingtypes = TrainingType::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('trainingtype.index', compact('trainingtypes'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if (Auth::user()->can('Create Training Type')) {
            return view('trainingtype.create');
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if (Auth::user()->can('Create Training Type')) {

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

            $trainingtype             = new TrainingType();
            $trainingtype->name       = $request->name;
            $trainingtype->created_by = Auth::user()->creatorId();
            $trainingtype->save();

            return redirect()->route('trainingtype.index')->with('success', __('TrainingType  successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }


    public function edit($id)
    {

        if (Auth::user()->can('Edit Training Type')) {
            $trainingType = TrainingType::find($id);
            if ($trainingType->created_by == Auth::user()->creatorId()) {

                return view('trainingtype.edit', compact('trainingType'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, $id)
    {
        if (Auth::user()->can('Edit Training Type')) {
            $trainingType = TrainingType::find($id);
            if ($trainingType->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',

                    ]
                );

                $trainingType->name = $request->name;
                $trainingType->save();

                return redirect()->route('trainingtype.index')->with('success', __('TrainingType successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy($id)
    {
        if (Auth::user()->can('Delete Training Type')) {

            $trainingType = TrainingType::find($id);
            if ($trainingType->created_by == Auth::user()->creatorId()) {
                $trainingType->delete();

                return redirect()->route('trainingtype.index')->with('success', __('TrainingType successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
