<?php

namespace App\Http\Controllers\API;

use App\Models\CompanyPolicy;
use App\Models\Employee;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyPolicyController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $emp    = Employee::where('user_id', '=', $request->user_id)->first();

        if (!empty($emp)) {
            $user = User::find($request->user_id);
            $creator = $user->created_by;

            $company_policy = CompanyPolicy::where('created_by', '=', $creator)->get();

            if (empty($company_policy)) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'empty',
                    'data' => []
                ]);
            } else {
                $data = [];

                $storage = asset(url('uploads/companyPolicy/'));
                foreach ($company_policy as $item) {
                    $data[] = [
                        'id' => $item->id,
                        'branch' => $item->branch,
                        'title' => $item->title,
                        'description' => $item->description,
                        'attachment' => ($item->attachment != null ? $storage . '/' . $item->attachment : ''),
                    ];
                }
                return response()->json([
                    'type' => 'success',
                    'message' => 'available',
                    'data' => $data
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
