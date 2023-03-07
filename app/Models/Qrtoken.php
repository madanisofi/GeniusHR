<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qrtoken extends Model
{
    protected $fillable = [
        'token',
        'created_by',
    ];
}
