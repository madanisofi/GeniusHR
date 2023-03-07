<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeCompensation extends Model
{

    protected $table = "overtime_compensations";
    protected $fillable = [
        'name',
        'attendance_option',
        'created_by',
    ];
}
