<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $mod_user = new User();

        $employees              = Employee::where('created_by', \Auth::user()->creatorId())->get();

        $data = [];
        foreach ($employees as $val) {
            $data[] = [
                'employee_id' => $mod_user->employeeIdFormat($val->employee_id),
                'name' => $val->name,
                'birthplace' => $val->birthplace,
                'dob' => date('d-M-Y', strtotime($val->dob)),
                'phone' => $val->phone,
                'gender' => $val->gender,
                'address' => $val->address,
                'position' => isset($val->position) ? $val->position->name : '',
                'employee_type' => isset($val->employeetype) ? $val->employeetype->name : '',
                'email' => $val->email,
                'role' => isset($val->role) ? $val->role->name : '',
                'department' => isset($val->department) ? $val->department->name : '',
                'designation' => isset($val->designation) ? $val->designation->name : '',
                'education' => isset($val->education) ? $val->education->name : '',
                'doj' => date('d-M-Y', strtotime($val->company_doj)),
                'low' => $mod_user->lengthOfWork($val->company_doj)
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            __('Employee ID'),
            __('Name'),
            __('Birthplace'),
            __('Date of Birth'),
            __('Phone'),
            __('Gender'),
            __('Address'),
            __('Position'),
            __('Employee Type'),
            __('Email'),
            __('Role'),
            __('Department'),
            __('Designation'),
            __('Education'),
            __('Date Of Joining'),
            __('Length of work'),
        ];
    }
}
