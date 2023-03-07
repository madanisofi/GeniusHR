<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayShift extends Model
{
    protected $fillable = [
        'employee_id',
        'shift_id',
        'amount',
        'created_by',
    ];

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id')->first();
    }

    public function shift()
    {
        return $this->hasOne('App\Models\Shift', 'id', 'shift_id')->first();
    }
}
