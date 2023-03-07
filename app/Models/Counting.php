<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Counting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'type',
        'start_year',
        'max_year',
        'created_by',
    ];

    public function getYearOfService()
    {
        $settings = Utility::settings();

        return (int)$settings['formula_y_o_s'];
    }

    public function getClassIncrease()
    {
        $settings = Utility::settings();

        return (int)$settings['formula_c_i'];
    }

    public function getFormulaY()
    {
        $settings = Utility::settings();

        return (float)$settings['formula_y'] / 100;
    }

    public function getSalaryByGroup($group_id, $emp_type)
    {
        $counting    = Counting::selectRaw('id, type, start_year, max_year')->where('group_id', $group_id)->where('created_by', '=', Auth::user()->creatorId())->orderBy('created_at', 'desc')->get();

        $salary = 0;
        $start_year = 0;
        $max_year = 0;
        foreach ($counting as $value) {
            foreach (json_decode($value->type) as $xx) {
                if ($xx->id == $emp_type) {
                    $salary = $xx->salary;
                }
            }
            $start_year = $value->start_year;
            $max_year = $value->max_year;
        }

        return array('salary' => $salary, "start_year" => $start_year, "max_year" => $max_year);
    }

    public function getFinalSalary($first_salary, $formula, $year_service)
    {
        $final = 0;
        for ($i = 0; $i < $year_service; $i++) {
            $final = $first_salary + ($first_salary * $formula);
            $first_salary = $final;
        }
        return $final;
    }
}
