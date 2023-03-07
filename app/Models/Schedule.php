<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shift;

class Schedule extends Model
{
    protected $fillable = [
        'shift_id',
        'room_id',
        'employee_id',
        'date',
        'day',
        'repeat',
        'month',
        'day_on_month',
        'created_by',
    ];

    public function room()
    {
        return $this->hasOne('App\Models\RoomType', 'id', 'room_id');
    }
}
