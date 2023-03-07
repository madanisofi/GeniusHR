<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleEmployee extends Model
{
    protected $fillable = [
        'shift_id',
        'employee_id',
        'date',
        'created_by',
    ];
}
