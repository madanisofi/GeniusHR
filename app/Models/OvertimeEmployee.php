<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeEmployee extends Model
{
    // use HasFactory;

    protected $fillable = [
        'overtime_id',
        'employees_id',
        'created_by',
    ];

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employees_id');
    }

    public function overtime()
    {
        return $this->hasOne('App\Models\OvertimeAttendance', 'id', 'overtime_id');
    }
}
