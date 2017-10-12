<?php
function sortSchedule($a, $b)
{
    return strtotime($a['start'])-strtotime($b['start']);
}

function diffSchedules($v1, $v2)
{
    if ($v1['start']==$v2['start'] && $v1['end']==$v2['end'])
    {
        return 0;
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