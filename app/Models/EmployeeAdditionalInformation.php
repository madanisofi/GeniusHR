<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAdditionalInformation extends Model
{
    protected $table = 'employee_additional_informations';

    protected $fillable = [
        'employee_id', 'additional_id', 'additional_value', 'created_by'
    ];

    public function information()
    {
        return $this->hasOne('App\Models\AdditionalInformation', 'id', 'additional_id');
    }
}
