<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationEmployee extends Model
{
    protected $fillable = [
        'notification_id',
        'user_id',
        'created_by',
    ];
}
