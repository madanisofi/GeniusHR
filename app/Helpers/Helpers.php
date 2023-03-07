<?php

use App\Models\LateCharge;

function test()
{
    return 'success';
}

function setLateCharge($data)
{
    $lateCharge                 = new LateCharge();
    $lateCharge->attendance_id  = $data['attendance_id'];
    $lateCharge->salary_cuts    = $data['salary_cuts'];
    $lateCharge->working_hours  = $data['working_hours'];
    $lateCharge->working_late   = $data['working_late'];
    $lateCharge->save();

    return $lateCharge->id;
}

function updateLateCharge($data)
{
    LateCharge::where('attendance_id', $data['attendance_id'])
        ->update([
            'salary_cuts'       => $data['salary_cuts'],
            'working_hours'     => $data['working_hours'],
            'working_late'      => $data['working_late'],
        ]);
}

function accumulateTime($times)
{
    $seconds = 0;
    foreach ($times as $time) {
        list(
            $g, $i, $s
        ) = explode(':', $time);
        $seconds += $g * 3600;
        $seconds += $i * 60;
        $seconds += $s;
    }

    $hours = floor($seconds / 3600);
    $seconds -= $hours * 3600;
    $minutes = floor($seconds / 60);
    $seconds -= $minutes * 60;

    $accumulate_time = ($hours < 10 ? "0" . $hours : $hours) . ":" . ($minutes < 10 ? "0" . $minutes : $minutes) . ":" . ($seconds < 10 ? "0" . $seconds : $seconds);

    return $accumulate_time;
}

function checkClockOut($tolerance, $clock_out, $company_end_time)
{
    $convert_tolerane       = gmdate('H:i:s', ($tolerance * 60));
    $convert_working_time   = date('H:i:s', strtotime($company_end_time));
    $times                  = [$convert_tolerane, $convert_working_time];
    $accumulate_time        = accumulateTime($times);

    if ($clock_out > $accumulate_time) return $accumulate_time;
    else return $clock_out;
}

function countingSalaryCuts($data)
{
    $date                   = $data['date']; #date("Y-m-d");
    $clock_in               = $data['clock_in']; #'08:30';
    $clock_out              = $data['clock_out']; #'16:01';
    $tolerance              = $data['tolerance']; #120;
    $working_hours          = $data['working_hours']; #8;
    $company_start_time     = $data['company_start_time']; #'08:00';
    $company_end_time       = $data['company_end_time']; #'17:00';
    $late_fee_in_minutes    = $data['late_fee_in_minutes']; #1000;
    $max_late_fee           = $data['max_late_fee']; #50000;
    $late_accumulate        = $data['late_accumulate']; #50;

    $salary_cuts            = 0;
    $convert_working_hours  = gmdate('H:i:s', ($working_hours * 3600));
    $convert_tolerane       = gmdate('H:i:s', ($tolerance * 60));
    $convert_working_time   = date('H:i:s', strtotime($company_end_time));
    $times                  = [$convert_tolerane, $convert_working_time];
    $accumulate_time = accumulateTime($times);

    // if clock out > tolerance , clock out == tolerance
    if ($clock_out > $accumulate_time) $totalLateSeconds = strtotime($date . $accumulate_time) - strtotime($date . $clock_in);
    else $totalLateSeconds = strtotime($date . $clock_out) - strtotime($date . $clock_in);
    $hrs            = floor($totalLateSeconds / 3600);
    $mins             = floor($totalLateSeconds / 60 % 60);
    $secs             = floor($totalLateSeconds % 60);
    $late             = sprintf('%02d:%02d:%02d', $hrs, $mins, $secs);

    if ($hrs < $working_hours) {
        $diff = strtotime($convert_working_hours) - strtotime($late);
        $counting_minutes = floor($diff / 60);

        if ($counting_minutes > $late_accumulate) {
            $salary_cuts = $max_late_fee;
        } else {
            $salary_cuts = $counting_minutes * $late_fee_in_minutes;
        }

        return ['status' => 'Late', 'hours' => floor($diff / 3600), 'mins' => floor($diff / 60 % 60), 'deficiency' => $counting_minutes, 'salary_cuts' => $salary_cuts, 'exceed_tolerance' => 'no', 'working_hours' => $late];
    } else {
        $salary_cuts = 0;
        return ['status' => 'Ontime', 'hours' => '00', 'mins' => '00', 'deficiency' => 0, 'salary_cuts' => $salary_cuts, 'exceed_tolerance' => 'no', 'working_hours' => $late];
    }
}

function countingSalaryCutsV2($accumulate_hours, $working_hours, $late_fee_in_minutes, $max_late_fee, $late_accumulate)
{
    $working_hours          = $working_hours;
    $late_fee_in_minutes    = $late_fee_in_minutes;
    $max_late_fee           = $max_late_fee;
    $late_accumulate        = $late_accumulate;
    $salary_cuts            = 0;
    $convert_working_hours  = gmdate('H:i:s', ($working_hours * 3600));

    $hrs                    = date('H', strtotime($accumulate_hours));
    $mins                   = date('i', strtotime($accumulate_hours));
    $secs                   = date('s', strtotime($accumulate_hours));
    $late                   = sprintf('%02d:%02d:%02d', $hrs, $mins, $secs);

    if ($hrs < $working_hours) {
        $diff = strtotime($convert_working_hours) - strtotime($late);
        $counting_minutes = floor($diff / 60);

        if ($counting_minutes > $late_accumulate) {
            $salary_cuts = $max_late_fee;
        } else {
            $salary_cuts = $counting_minutes * $late_fee_in_minutes;
        }

        return [
            'status'            => 'Late',
            'hours'             => floor($diff / 3600),
            'mins'              => floor($diff / 60 % 60),
            'sec'               => floor($diff % 60),
            'deficiency'        => $counting_minutes,
            'salary_cuts'       => $salary_cuts,
            'exceed_tolerance'  => 'no',
            'working_hours'     => $late
        ];
    } else {
        $salary_cuts = 0;
        return [
            'status'            => 'Ontime',
            'hours'             => '00',
            'mins'              => '00',
            'sec'               => '00',
            'deficiency'        => 0,
            'salary_cuts'       => $salary_cuts,
            'exceed_tolerance'  => 'no',
            'working_hours'     => $late
        ];
    }

    function searchArrayKeyVal($sKey, $id, $array)
    {
        foreach ($array as $key => $val) {
            if ($val[$sKey] == $id) {
                return $key;
            }
        }
        return false;
    }

    function overtimeDuration($start, $end)
    {
        $totalLateSeconds = strtotime($end) - strtotime($start);
        $hrs              = floor($totalLateSeconds / 3600);
        $mins             = floor($totalLateSeconds / 60 % 60);
        $secs             = floor($totalLateSeconds % 60);
        $duration         = sprintf('%02d:%02d:%02d', $hrs, $mins, $secs);

        return $duration;
    }
}
