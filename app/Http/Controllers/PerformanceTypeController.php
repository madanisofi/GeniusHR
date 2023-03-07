<?php

namespace App\Http\Controllers;

use App\Models\Performance_Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PerformanceTypeController extends Controller
{
    public function index()
    {
        $performance_types = Performance_Type::where('created_by', '=', Auth::user()->id)->get();
        return view('performance_type.index', compact('performance_types'));
    }


    public function create()
    {
        return view('performance_type.create');
    }


    public function store(Request $request)
    {
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

        $performance_type = new Performance_Type();
        $performance_type->name = $request->name;
        $performance_type->created_by = Auth::user()->id;
        $performance_type->save();

        return back()->with('success', 'Performance Type created successfully');
    }

    public function edit($id)
    {
        $performance_type  = Performance_Type::find($id);
        return view('performance_type.edit', compact('performance_type'));
    }


    public function update(Request $request, $id)
    {
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

        $performance_type = Performance_Type::findOrFail($id);
        $performance_type->name = $request->name;
        $performance_type->save();

        return back()->with('success', 'Performance Type updated successfully');
    }

    public function destroy($id)
    {
        $performance_type = performance_type::findOrFail($id);
        $performance_type->delete();

        return back()->with('success', 'Performance Type deleted successfully');
    }
}
