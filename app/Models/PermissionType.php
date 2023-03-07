<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionType extends Model
{
    protected $fillable = [
        'title',
        'days',
        'many_submission',
        'clock_out',
        'get_consumption_fee',
        'created_by',
    ];
}
