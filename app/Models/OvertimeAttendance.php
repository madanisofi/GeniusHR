<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeAttendance extends Model
{
    // use HasFactory;

    protected $fillable = [
        'applicant',
        'start_time',
        'end_time',
        'duration',
        'status',
        'date',
        'approved_date',
        'overtime_date',
        'aggrement',
        'notes',
        'picture_in',
        'picture_out',
        'compensation_id',
        'latitude',
        'longitude',
        'created_by',
    ];

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'applicant');
    }

    public function compensation()
    {
        return $this->hasOne('App\Models\OvertimeCompensation', 'id', 'compensation_id');
    }

    public function allemployee()
    {
        return $this->hasMany('App\Models\OvertimeEmployee', 'overtime_id', 'id')->get();
    }
}
