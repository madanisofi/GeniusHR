<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = [
        'title',
        'days',
        'parent',
        'start_date',
        'end_date',
        'select_all',
        'reduction',
        'created_by',
    ];
}
