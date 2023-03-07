<?php

namespace App\Http\Controllers;

use App\Models\AdditionalInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdditionalInformationController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Additional Information')) {
            $additionals = AdditionalInformation::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('additional.index', compact('additionals'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Additional Information')) {
            return view('additional.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Additional Information')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:100',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $additional              = new AdditionalInformation();
            $additional->name        = $request->name;
            $additional->type        = $request->type;
            $additional->can_insert         = $request->can_insert;
            $additional->send_notification  = $request->send_notification;
            $additional->reminder           = $request->reminder;
            $additional->is_required = $request->is_required;
            $additional->created_by  = Auth::user()->creatorId();
            $additional->save();

            return redirect()->route('additional.index')->with('success', __('Additional Information successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('additional.index');
    }

    public function edit(AdditionalInformation $additional)
    {
        if (Auth::user()->can('Edit Additional Information')) {
            if ($additional->created_by == Auth::user()->creatorId()) {

                return view('additional.edit', compact('additional'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, AdditionalInformation $additional)
    {

        if (Auth::user()->can('Edit Additional Information')) {
            if ($additional->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:100',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return back()->with('error', $messages->first());
                }


                $additional->name        = $request->name;
                $additional->type        = $request->type;
                $additional->can_insert         = $request->can_insert;
                $additional->send_notification  = $request->send_notification;
                $additional->reminder           = $request->reminder;
                $additional->is_required = $request->is_required;
                $additional->save();

                return redirect()->route('additional.index')->with('success', __('Additional Information successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(AdditionalInformation $additional)
    {
        if (Auth::user()->can('Delete Additional Information')) {
            if ($additional->created_by == Auth::user()->creatorId()) {
                $additional->delete();

                return redirect()->route('additional.index')->with('success', __('Additional Information successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
