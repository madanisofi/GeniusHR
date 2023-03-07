<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobCategoryController extends Controller
{

    public function index()
    {
        if (Auth::user()->can('Manage Job Category')) {
            $categories = JobCategory::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('jobCategory.index', compact('categories'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('jobCategory.create');
    }


    public function store(Request $request)
    {
        if (Auth::user()->can('Create Job Category')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $jobCategory             = new JobCategory();
            $jobCategory->title      = $request->title;
            $jobCategory->created_by = Auth::user()->creatorId();
            $jobCategory->save();

            return back()->with('success', __('Job category  successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }


    public function edit(JobCategory $jobCategory)
    {
        return view('jobCategory.edit', compact('jobCategory'));
    }


    public function update(Request $request, JobCategory $jobCategory)
    {
        if (Auth::user()->can('Edit Job Category')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $jobCategory->title = $request->title;
            $jobCategory->save();

            return back()->with('success', __('Job category  successfully updated.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(JobCategory $jobCategory)
    {
        if (Auth::user()->can('Delete Job Category')) {
            if ($jobCategory->created_by == Auth::user()->creatorId()) {
                $jobCategory->delete();

                return back()->with('success', __('Job category successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
