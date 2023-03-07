<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceEmployee extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'status',
        'clock_in',
        'clock_out',
        'late',
        'early_leaving',
        'overtime',
        'total_rest',
        'shift_id',
        'created_by',
        'images',
        'images_out',
        'images_reason',
        'reason',
        'approve',
        'notes',
        'latitude',
        'longitude',
        'working_hours',
        'salary_cuts',
        'permissiontype_id',
        'parent_id',
        'end_date',
    ];

    public function employees()
    {
        return $this->hasOne('App\Models\Employee', 'user_id', 'employee_id');
    }

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    }

    public function shift()
    {
        return $this->hasOne('App\Models\Shift', 'id', 'shift_id');
    }

    public function permission()
    {
        return $this->hasOne('App\Models\PermissionType', 'id', 'permissiontype_id');
    }

    public function latecharge()
    {
        return $this->hasOne('App\Models\LateCharge', 'attendance_id', 'id');
    }
}
