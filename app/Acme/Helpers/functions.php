<?php
function getCrossingSchedule($array1, $array2)
{
    $result = [];
    foreach($array1 as $v1)
    {
        foreach($array2 as $v2)
        {
            if($v1['start']==$v2['start'])
            {
                $result[] = $v1; 
            }
        }
    }
    
    return $result;
}

function sortSchedule($a, $b)
{
    return strtotime($a['start'])-strtotime($b['start']);
}

function diffSchedules($v1, $v2)
{
    if ($v1['start']==$v2['start'] && $v1['end']==$v2['end'])
    {
        echo $v1['start'];
        return 1;
    }

    return -1;
}

function in_arrayi($needle, $haystack) {
    return in_array(strtolower($needle), array_map('strtolower', $haystack));
}

function calculateScheduleThreeWeek()
{
    
}

function getWeekRange()
{
    
}