<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\EmployeeDocument;
use App\Mail\UserCreate;
use App\Models\Plan;
use App\Models\User;
use App\Models\Utility;
use App\Models\Position;
use App\Models\Educations;
use App\Models\AdditionalInformation;
use App\Models\EmployeeAdditionalInformation;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Imports\EmployeesImport;
use App\Exports\EmployeesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Employee')) {
            if (Auth::user()->type == 'employee') {
                $employees = Employee::where('user_id', '=', Auth::user()->id)->get();
            } else {
                $employees = Employee::where('created_by', Auth::user()->creatorId())->get();
            }

            $automatic_salary       = Utility::getValByName('automatic_basic_salary');

            return view('employee.index', compact('employees', 'automatic_salary'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Employee')) {

            // Check if branch, department, designation, position, employee type and education is empty
            $branch_check = Branch::where('created_by', Auth::user()->creatorId())->first();
            $department_check = Department::where('created_by', Auth::user()->creatorId())->first();
            $designation_check = Designation::where('created_by', Auth::user()->creatorId())->first();
            $position_check = Position::where('created_by', Auth::user()->creatorId())->first();
            $employee_type_check = EmployeeType::where('created_by', Auth::user()->creatorId())->first();
            $education_check = Educations::where('created_by', Auth::user()->creatorId())->first();

            if (empty($branch_check) || empty($department_check) || empty($designation_check) || empty($position_check) || empty($employee_type_check) || empty($education_check)) {
                return redirect()->route('employee.index')->with('error', __('Please add branch, department, designation, position, employee type and education first.'));
            } else {
                $company_settings = Utility::settings();
                $documents        = Document::where('created_by', Auth::user()->creatorId())->get();
                $additionalInformation        = AdditionalInformation::where('created_by', Auth::user()->creatorId())->get();
                $branches         = Branch::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
                $departments      = Department::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
                $designations     = Designation::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
                $employees        = User::where('created_by', Auth::user()->creatorId())->get();
                $employeesId      = Auth::user()->employeeIdFormat($this->employeeNumber());
                $position         = Position::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
                $employeetype     = EmployeeType::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
                $roles            = DB::table('roles')->where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
                $education        = Educations::where('created_by', Auth::user()->creatorId())->orderBy('name', 'ASC')->get()->pluck('name', 'id');

                return view('employee.create', compact('employees', 'employeesId', 'departments', 'designations', 'documents', 'branches', 'company_settings', 'position', 'employeetype', 'roles', 'education', 'additionalInformation'));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Employee')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'dob' => 'required',
                    'birthplace' => 'required',
                    'gender' => 'required',
                    'phone' => 'required',
                    'address' => 'required',
                    'email' => 'required|unique:users',
                    'password' => 'required',
                    'role_id' => 'required',
                    'employee_id' => 'required',
                    'department_id' => 'required',
                    'position_id' => 'required',
                    'document.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf,doc,zip|max:20480',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->withInput()->with('error', $messages->first());
            }

            $objUser        = User::find(Auth::user()->creatorId());
            $total_employee = $objUser->countEmployees();
            $plan           = Plan::find($objUser->plan);

            if ($total_employee < $plan->max_employees || $plan->max_employees == -1) {
                $role_r = Role::findById($request['role_id']);
                $user = User::create(
                    [
                        'name' => $request['name'],
                        'email' => $request['email'],
                        'password' => Hash::make($request['password']),
                        'type' => $role_r->name,
                        'role_id' => $request['role_id'],
                        'lang' => 'en',
                        'created_by' => Auth::user()->creatorId(),
                    ]
                );
                $user->save();
                $user->assignRole($role_r);
            } else {
                return back()->with('error', __('Your employee limit is over, Please upgrade plan.'));
            }


            if (!empty($request->document) && !is_null($request->document)) {
                $document_implode = implode(',', array_keys($request->document));
            } else {
                $document_implode = null;
            }

            if (!empty($request->additional) && $request->additional != null) {
                $additional_array = [];
                foreach ($request->additional as $key => $additional) {
                    if ($request['additional'][$key] != '') array_push($additional_array, $key);
                }
                if (count($additional_array) > 0) $additional_implode = implode(',', array_values($additional_array));
                else $additional_implode = null;
            } else {
                $additional_implode = null;
            }

            $employee = Employee::create(
                [
                    'user_id' => $user->id,
                    'name' => $request['name'],
                    'dob' => $request['dob'],
                    'birthplace' => $request['birthplace'],
                    'gender' => $request['gender'],
                    'phone' => $request['phone'],
                    'address' => $request['address'],
                    'email' => $request['email'],
                    'password' => Hash::make($request['password']),
                    'employee_id' => $request['employee_id'], #id incompany
                    'branch_id' => $request['branch_id'],
                    'department_id' => $request['department_id'],
                    'designation_id' => $request['designation_id'],
                    'position_id' => $request['position_id'],
                    'employeetype_id' => $request['employeetype_id'],
                    'role_id' => $request['role_id'],
                    'education_id' => $request['education_id'],
                    'company_doj' => $request['company_doj'],
                    'documents' => $document_implode,
                    'additionals' => $additional_implode,
                    'account_holder_name' => $request['account_holder_name'],
                    'account_number' => $request['account_number'],
                    'bank_name' => $request['bank_name'],
                    'bank_identifier_code' => $request['bank_identifier_code'],
                    'branch_location' => $request['branch_location'],
                    'tax_payer_id' => $request['tax_payer_id'],
                    'created_by' => Auth::user()->creatorId(),
                ]
            );


            if ($request->hasFile('document')) {
                foreach ($request->document as $key => $document) {

                    $filenameWithExt = $request->file('document')[$key]->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('document')[$key]->getClientOriginalExtension();

                    // hash file before store
                    $filename = md5($filename . time());

                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir             = storage_path('uploads/document/');
                    $image_path      = $dir . $filenameWithExt;

                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }

                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $path              = $request->file('document')[$key]->storeAs('uploads/document/', $fileNameToStore);
                    $employee_document = EmployeeDocument::create(
                        [
                            'employee_id' => $employee['id'],
                            'document_id' => $key,
                            'document_value' => $fileNameToStore,
                            'created_by' => Auth::user()->creatorId(),
                        ]
                    );
                    $employee_document->save();
                }
            }

            if ($request->additional && $request->additional != '') {
                foreach ($request->additional as $key => $additional) {
                    if ($request['additional'][$key] != '') {
                        $employee_additional = EmployeeAdditionalInformation::create(
                            [
                                'employee_id' => $employee['id'],
                                'additional_id' => $key,
                                'additional_value' => $request['additional'][$key],
                                'created_by' => Auth::user()->creatorId(),
                            ]
                        );
                        $employee_additional->save();
                    }
                }
            }

            $setings = Utility::settings();
            if ($setings['employee_create'] == 1) {
                $user->type     = 'Employee';
                $user->password = $request['password'];
                try {
                    Mail::to($user->email)->send(new UserCreate($user));
                } catch (\Exception $e) {
                    $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                }

                return redirect()->route('employee.index')->with('success', __('Employee successfully created.') . (isset($smtp_error) ? $smtp_error : ''));
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        if (Auth::user()->can('Edit Employee')) {
            $documents    = Document::where('created_by', Auth::user()->creatorId())->get();
            $additionalInformation        = AdditionalInformation::where('created_by', Auth::user()->creatorId())->get();
            $branches     = Branch::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments  = Department::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $designations = Designation::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employee     = Employee::find($id);
            $format       = Utility::getValByName('employee_prefix');
            $employeesId  = $employee->employee_id;
            $position     = Position::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employeetype         = EmployeeType::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $roles            = DB::table('roles')->where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $education        = Educations::where('created_by', Auth::user()->creatorId())->orderBy('name', 'ASC')->get()->pluck('name', 'id');

            return view('employee.edit', compact('employee', 'employeesId', 'branches', 'departments', 'designations', 'documents', 'position', 'employeetype', 'roles', 'education', 'additionalInformation', 'format'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {

        if (Auth::user()->can('Edit Employee')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'dob' => 'required',
                    'birthplace' => 'required',
                    'gender' => 'required',
                    'phone' => 'required|numeric',
                    'address' => 'required',
                    'document.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf,doc,zip|max:20480',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return back()->with('error', $messages->first());
            }

            $employee = Employee::findOrFail($id);

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
                            $employee_document->created_by     = Auth::user()->creatorId();
                            $employee_document->save();
                        }
                    }
                }
            }

            if ($request->additional && $request->additional != '') {
                foreach ($request->additional as $key => $additional) {
                    $employee_additional_check = EmployeeAdditionalInformation::where('employee_id', $employee->employee_id)->where('additional_id', $key)->first();
                    if (!empty($employee_additional_check)) {
                        $employee_additional_check->additional_value = $request['additional'][$key];
                        $employee_additional_check->save();
                    } else {
                        if ($request['additional'][$key] != '') {

                            $employee_additional = EmployeeAdditionalInformation::create(
                                [
                                    'employee_id' => $employee['id'],
                                    'additional_id' => $key,
                                    'additional_value' => $request['additional'][$key],
                                    'created_by' => Auth::user()->creatorId(),
                                ]
                            );
                            $employee_additional->save();
                        }
                    }
                }
            }


            $employee = Employee::findOrFail($id);

            if ($employee->role_id != $request->role_id or !empty($request->password) or isset($request->is_active)) {
                # code...
                $role_r = Role::findById($request->role_id);
                $user = User::find($employee->user_id);
                $user->type = $role_r->name;
                if (isset($request->is_active)) $user->is_active = $request->is_active;
                if ($employee->role_id != $request->role_id) $user->role_id = $request->role_id;

                if (!empty($request->password)) $user->password = Hash::make($request['password']);

                $user->save();
                $user->syncRoles($role_r);
            }

            if (!empty($request->document) && !is_null($request->document)) {
                $document_implode = implode(',', array_keys($request->document));
            } else {
                $document_implode = null;
            }

            if (!empty($request->additional) && $request->additional != null) {
                $additional_array = [];
                foreach ($request->additional as $key => $additional) {
                    if ($request['additional'][$key] != '') array_push($additional_array, $key);
                }
                if (count($additional_array) > 0) $additional_implode = implode(',', array_values($additional_array));
                else $additional_implode = null;
            } else {
                $additional_implode = null;
            }

            $employee->name = $request['name'];
            $employee->dob = $request['dob'];
            $employee->birthplace = $request['birthplace'];
            $employee->gender = $request['gender'];
            $employee->phone = $request['phone'];
            $employee->address = $request['address'];
            if (!empty($request->password)) $employee->password = Hash::make($request['password']);
            $employee->branch_id = $request['branch_id'];
            $employee->department_id = $request['department_id'];
            $employee->designation_id = $request['designation_id'];
            $employee->position_id = $request['position_id'];
            $employee->employeetype_id = $request['employeetype_id'];
            $employee->role_id = $request['role_id'];
            $employee->education_id = $request['education_id'];
            $employee->company_doj = $request['company_doj'];
            $employee->documents = $document_implode;
            $employee->additionals = $additional_implode;
            $employee->account_holder_name = $request['account_holder_name'];
            $employee->account_number = $request['account_number'];
            $employee->bank_name = $request['bank_name'];
            $employee->bank_identifier_code = $request['bank_identifier_code'];
            $employee->branch_location = $request['branch_location'];
            $employee->tax_payer_id = $request['tax_payer_id'];
            $employee->is_active = $request['is_active'];

            $employee->save();

            if ($request->salary) {
                return redirect()->route('setsalary.index')->with('success', 'Employee successfully updated.');
            }

            if (Auth::user()->type != 'employee') {
                return redirect()->route('employee.index')->with('success', 'Employee successfully updated.');
            } else {
                return redirect()->route('employee.show', Crypt::encrypt($employee->id))->with('success', 'Employee successfully updated.');
            }
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('Delete Employee')) {
            $employee      = Employee::findOrFail($id);
            $user          = User::where('id', '=', $employee->user_id)->first();
            $emp_documents = EmployeeDocument::where('employee_id', $employee->employee_id)->get();
            $employee->delete();
            $user->delete();
            $dir = storage_path('uploads/document/');
            foreach ($emp_documents as $emp_document) {
                $emp_document->delete();
                if (!empty($emp_document->document_value)) {
                    unlink($dir . $emp_document->document_value);
                }
            }

            return redirect()->route('employee.index')->with('success', 'Employee successfully deleted.');
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function show($id)
    {

        if (Auth::user()->can('Show Employee')) {
            $empId        = Crypt::decrypt($id);
            $documents    = Document::where('created_by', Auth::user()->creatorId())->get();
            $additionalInformation        = AdditionalInformation::where('created_by', Auth::user()->creatorId())->get();
            $branches     = Branch::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments  = Department::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $designations = Designation::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employee     = Employee::find($empId);
            $employeesId  = Auth::user()->employeeIdFormat($employee->employee_id);
            $format       = Utility::getValByName('employee_prefix');

            return view('employee.show', compact('employee', 'employeesId', 'branches', 'departments', 'designations', 'documents', 'additionalInformation', 'format'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }



    function employeeNumber()
    {
        $latest = Employee::where('created_by', '=', Auth::user()->creatorId())->latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->id + 1;
    }

    public function export()
    {
        $name = 'employee_' . date('Y-m-d i:h:s');
        $data = Excel::download(new EmployeesExport(), $name . '.xlsx');
        ob_end_clean();

        return $data;
    }

    public function importFile()
    {
        return view('employee.import');
    }

    public function import(Request $request)
    {
        $rules = [
            'file' => 'required|mimes:csv,txt',
        ];

        $validator = Validator::make($request->all(), $rules);
        $creator = Auth::user()->creatorId();

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return back()->with('error', $messages->first());
        }

        $employees = (new EmployeesImport())->toArray(request()->file('file'))[0];
        $totalCustomer = count($employees) - 1;
        $errorArray    = [];
        $dataEmployee  = [];

        for ($i = 1; $i <= count($employees) - 1; $i++) {

            $employee = $employees[$i];
            $userByEmail = User::where('email', $employee[6])->first();

            if (empty($userByEmail)) {
                $user = new User();
                $user->name = $employee[0];
                $user->email = $employee[6];
                $user->password = Hash::make($employee[7]);
                $user->type = 'employee';
                $user->role_id = $employee[20];
                $user->lang = 'en';
                $user->created_by = $creator;
                $user->save();
                $user->assignRole('Employee');

                $user_id = $user->id;
            } else {
                $user_id = $userByEmail->id;
            }

            $dataEmployee[] = [
                'name'                  => $employee[0],
                'user_id'               => $user_id,
                'birthplace'            => $employee[1],
                'dob'                   => $employee[2],
                'gender'                => $employee[3],
                'phone'                 => $employee[4],
                'address'               => $employee[5],
                'email'                 => $employee[6],
                'password'              => Hash::make($employee[7]),
                'employee_id'           => $employee[8],
                'branch_id'             => $employee[9],
                'department_id'         => $employee[10],
                'designation_id'        => $employee[11],
                'company_doj'           => $employee[12],
                'account_holder_name'   => $employee[13],
                'account_number'        => $employee[14],
                'bank_name'             => $employee[15],
                'bank_identifier_code'  => $employee[16],
                'branch_location'       => $employee[17],
                'tax_payer_id'          => $employee[18],
                'position_id'           => $employee[19],
                'role_id'               => $employee[20],
                'employeetype_id'       => $employee[21],
                'created_by'            => $creator
            ];
        }

        Employee::insert($dataEmployee);

        $errorRecord = [];

        if (empty($errorArray)) {
            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        } else {
            $data['status'] = 'error';
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalCustomer . ' ' . 'record');


            foreach ($errorArray as $errorData) {

                $errorRecord[] = implode(',', $errorData);
            }

            \Session::put('errorArray', $errorRecord);
        }

        return back()->with($data['status'], $data['msg']);
    }

    public function json(Request $request)
    {
        $designations = Designation::where('department_id', $request->department_id)->get()->pluck('name', 'id')->toArray();

        return response()->json($designations);
    }

    public function profile(Request $request)
    {
        if (Auth::user()->can('Manage Employee Profile')) {
            $employees = Employee::where('created_by', Auth::user()->creatorId());
            if (!empty($request->branch)) {
                $employees->where('branch_id', $request->branch);
            }
            if (!empty($request->department)) {
                $employees->where('department_id', $request->department);
            }
            if (!empty($request->designation)) {
                $employees->where('designation_id', $request->designation);
            }
            $employees = $employees->get();

            $brances = Branch::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $brances->prepend('All', '');

            $departments = Department::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments->prepend('All', '');

            $designations = Designation::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $designations->prepend('All', '');

            return view('employee.profile', compact('employees', 'departments', 'designations', 'brances'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }


    public function profileShow($id)
    {
        if (Auth::user()->can('Show Employee Profile')) {
            $empId        = Crypt::decrypt($id);
            $documents    = Document::where('created_by', Auth::user()->creatorId())->get();
            $additionalInformation        = AdditionalInformation::where('created_by', Auth::user()->creatorId())->get();
            $branches     = Branch::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments  = Department::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $designations = Designation::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employee     = Employee::find($empId);
            $employeesId  = Auth::user()->employeeIdFormat($employee->employee_id);
            $format       = Utility::getValByName('employee_prefix');

            return view('employee.show', compact('employee', 'employeesId', 'branches', 'departments', 'designations', 'documents', 'additionalInformation', 'format'));
        } else {
            return back()->with('error', __('Permission denied.'));
        }
    }

    public function lastLogin()
    {
        $users = User::where('created_by', \Auth::user()->creatorId())->get();

        return view('employee.lastLogin', compact('users'));
    }

    public function employeeJson(Request $request)
    {
        $employees = Employee::where('branch_id', $request->branch)->get()->pluck('name', 'id')->toArray();

        return response()->json($employees);
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()){
            if (Auth::user()->can('Manage Employee')) {
                if (Auth::user()->type == 'employee') {
                    $employees = Employee::with(['position', 'employeetype', 'department', 'designation', 'education', 'group'])->where('user_id', Auth::user()->id);
                } else {
                    $employees = Employee::with(['position', 'employeetype', 'department', 'designation', 'education', 'group'])->where('created_by', Auth::user()->creatorId());
                }

                $automatic_salary       = Utility::getValByName('automatic_basic_salary');

                return DataTables::of($employees)
                    ->addIndexColumn()
                    ->addColumn('employee_id', function ($employee){
                        if (auth()->user()->can('Show Employee')){
                            $btn = '<a class="btn btn-outline-primary" href="' . route('employee.show', Crypt::encrypt($employee->id)) . '">' . $employee->employee_id . '</a>';
                        } else {
                            $btn = '<a href="#" class="btn btn-outline-primary">' . $employee->employee_id . '</a>';
                        }
                        return $btn;
                    })
                    ->addColumn('dateFormat', function ($employee){
                        return auth()->user()->dateFormat($employee->company_doj);
                    })
                    ->addColumn('getType', function ($employee){
                        return !empty(auth()->user()->getType($employee->id)) ? auth()->user()->getType($employee->id)->type : '';
                    })
                    ->addColumn('lengthOfWork', function ($employee){
                        return auth()->user()->lengthOfWork($employee->company_doj);
                    })
                    ->addColumn('action', function($employee){
                        if (auth()->user()->canany(['Edit Employee', 'Delete Employee'])){
                            $action = '
                                <span>
                                    <div class="action-btn bg-info ms-2">
                                        <a href="' . route('employee.edit', Crypt::encrypt($employee->id)) . '"
                                            class="mx-3 btn btn-sm  align-items-center"
                                            data-bs-toggle="tooltip" title=""
                                            data-bs-original-title="' . __('Edit') . '">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    <div class="action-btn bg-danger ms-2">
                                        <form id="delete-form-' . $employee->id . '" action="' . route('employee.destroy', $employee->id) . '" method="post">

                                        <a href="#"
                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                            data-bs-toggle="tooltip" title=""
                                            data-bs-original-title="Delete" aria-label="Delete"><i
                                                class="ti ti-trash text-white"></i></a>
                                        </form>
                                    </div>
                                </span>
                            ';
                        }

                        return $action;
                    })
                    ->rawColumns(['employee_id', 'action'])
                    ->make();
            }
        }
    }
}
