<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'group_id',
        'created_by',
    ];
}
