<?php

namespace App\Http\Controllers;

use App\Models\CustomQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomQuestionController extends Controller
{

    public function index()
    {
        if (Auth::user()->can('Manage Custom Question')) {
            $questions = CustomQuestion::where('created_by', Auth::user()->creatorId())->get();

            return view('customQuestion.index', compact('questions'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        $is_required = CustomQuestion::$is_required;

        return view('customQuestion.create', compact('is_required'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Custom Question')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'question' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $question              = new CustomQuestion();
            $question->question    = $request->question;
            $question->is_required = $request->is_required;
            $question->created_by  = Auth::user()->creatorId();
            $question->save();

            return back()->with('success', __('Question successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }


    public function edit(CustomQuestion $customQuestion)
    {
        $is_required = CustomQuestion::$is_required;
        return view('customQuestion.edit', compact('customQuestion', 'is_required'));
    }

    public function update(Request $request, CustomQuestion $customQuestion)
    {
        if (Auth::user()->can('Edit Custom Question')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'question' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $customQuestion->question    = $request->question;
            $customQuestion->is_required = $request->is_required;
            $customQuestion->save();

            return back()->with('success', __('Question successfully updated.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(CustomQuestion $customQuestion)
    {
        if (Auth::user()->can('Delete Custom Question')) {
            $customQuestion->delete();

            return back()->with('success', __('Question successfully deleted.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
