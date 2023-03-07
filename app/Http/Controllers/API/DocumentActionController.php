<?php

namespace App\Http\Controllers\API;

use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;

class DocumentActionController extends Controller
{
    public function __invoke(Request $request)
    {

        $request->validate([
            'user_id' => 'required'
        ]);

        $employee = Employee::where('user_id', '=', $request->user_id)->first();

        if (!empty($employee)) {

            if ($request->document) {

                foreach ($request->document as $key => $document) {
                    if (!empty($document)) {
                        $filenameWithExt = $request->file('document')[$key]->getClientOriginalName();
                        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension       = $request->file('document')[$key]->getClientOriginalExtension();
                        // hash file before store
                        $filename = md5($filename . time());
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                        $dir        = storage_path('uploads/document/');
                        $image_path = $dir . $filenameWithExt;

                        if (File::exists($image_path)) {
                            File::delete($image_path);
                        }
                        if (!file_exists($dir)) {
                            mkdir($dir, 0777, true);
                        }
                        $path = $request->file('document')[$key]->storeAs('uploads/document/', $fileNameToStore);


                        $employee_document = EmployeeDocument::where('employee_id', $employee->id)->where('document_id', $key)->first();

                        if (!empty($employee_document)) {
                            $employee_document->document_value = $fileNameToStore;
                            $employee_document->save();
                        } else {
                            $employee_document                 = new EmployeeDocument();
                            $employee_document->employee_id    = $employee->id;
                            $employee_document->document_id    = $key;
                            $employee_document->document_value = $fileNameToStore;
                            $employee_document->created_by     = $employee->created_by;
                            $employee_document->save();
                        }
                    }
                }

                return response()->json([
                    'type' => 'success',
                    'message' => 'Upload Success.'
                ]);
            } else {
                return response()->json([
                    'type' => 'success',
                    'message' => 'No Changes.'
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
