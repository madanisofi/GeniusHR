<?php

namespace App\Http\Controllers;

use App\Models\JobStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobStageController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Job Stage')) {
            $stages = JobStage::where('created_by', '=', Auth::user()->creatorId())->orderBy('order', 'asc')->get();

            return view('jobStage.index', compact('stages'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('jobStage.create');
    }


    public function store(Request $request)
    {
        if (Auth::user()->can('Create Job Stage')) {

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

            $jobStage             = new JobStage();
            $jobStage->title      = $request->title;
            $jobStage->created_by = Auth::user()->creatorId();
            $jobStage->save();

            return back()->with('success', __('Job stage  successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }


    public function edit(JobStage $jobStage)
    {
        return view('jobStage.edit', compact('jobStage'));
    }


    public function update(Request $request, JobStage $jobStage)
    {
        if (Auth::user()->can('Edit Job Stage')) {

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


            $jobStage->title      = $request->title;
            $jobStage->created_by = Auth::user()->creatorId();
            $jobStage->save();

            return back()->with('success', __('Job stage  successfully updated.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(JobStage $jobStage)
    {
        if (Auth::user()->can('Delete Job Stage')) {
            if ($jobStage->created_by == Auth::user()->creatorId()) {
                $jobStage->delete();

                return back()->with('success', __('Job stage successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function order(Request $request)
    {
        $post = $request->all();
        foreach ($post['order'] as $key => $item) {
            $stage        = JobStage::where('id', '=', $item)->first();
            $stage->order = $key;
            $stage->save();
        }
    }
}
