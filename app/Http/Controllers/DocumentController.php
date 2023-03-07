<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function index()
    {

        if (Auth::user()->can('Manage Document Type')) {
            $documents = Document::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('document.index', compact('documents'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Document Type')) {
            return view('document.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Document Type')) {
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

            $document              = new Document();
            $document->name        = $request->name;
            $document->is_required = $request->is_required;
            $document->created_by  = Auth::user()->creatorId();
            $document->save();

            return redirect()->route('document.index')->with('success', __('Document type successfully created.'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('document.index');
    }

    public function edit(Document $document)
    {
        if (Auth::user()->can('Edit Document Type')) {
            if ($document->created_by == Auth::user()->creatorId()) {

                return view('document.edit', compact('document'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Document $document)
    {

        if (Auth::user()->can('Edit Document Type')) {
            if ($document->created_by == Auth::user()->creatorId()) {
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


                $document->name        = $request->name;
                $document->is_required = $request->is_required;
                $document->save();

                return redirect()->route('document.index')->with('success', __('Document type successfully updated.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Document $document)
    {
        if (Auth::user()->can('Delete Document Type')) {
            if ($document->created_by == Auth::user()->creatorId()) {
                $document->delete();

                return redirect()->route('document.index')->with('success', __('Document type successfully deleted.'));
            } else {
                return back()->with('error', __('Permission denied.'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }
}
