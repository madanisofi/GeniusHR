<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LateCharge extends Model
{
    protected $fillable = [
        'attendance_id',
        'salary_cuts',
        'working_hours',
        'working_late',
    ];

    public function attendance()
    {
        return $this->hasOne('App\Models\AttendanceEmployee', 'id', 'attendance_id');
    }
}
