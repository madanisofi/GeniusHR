<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalInformation extends Model
{
    protected $table = 'additional_informations';
    protected $fillable = [
        'name',
        'type',
        'can_insert',
        'send_notification',
        'reminder',
        'is_required',
        'created_by',
    ];
}
