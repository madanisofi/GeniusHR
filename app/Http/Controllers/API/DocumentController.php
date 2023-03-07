<?php

namespace App\Http\Controllers\API;

use App\Models\Document;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $employee = Employee::where('user_id', '=', $request->user_id)->first();

        if (!empty($employee)) {
            $creator = $employee->created_by;
            $list_document = Document::where('created_by', $creator)->get();
            $employee_document = $employee->documents()->pluck('document_value', 'document_id');

            if (!empty($list_document)) {
                $data = [];

                foreach ($list_document as $key => $value) {
                    $data[] = [
                        'id' => $value->id,
                        'name' => $value->name,
                        'required' => $value->is_required,
                        'value' => (!empty($employee_document[$value->id]) ? asset(url('uploads/document/' . $employee_document[$value->id])) : '')
                    ];
                }

                return response()->json([
                    'type' => 'success',
                    'message' => 'data available.',
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'type' => 'success',
                    'message' => 'data available.',
                    'data' => []
                ]);
            }
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'employee not found.'
            ]);
        }
    }
}
