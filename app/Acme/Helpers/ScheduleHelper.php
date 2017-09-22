<?php

namespace App\Acme\Helpers;

use Illuminate\Support\Str;
use Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleHelper{
    
    public static function modifyForTwoWeeks($schedule)
    {
        usort($schedule, 'sortSchedule');
        
        $startWeek = Carbon::now()->startOfWeek()->timestamp;
        $endWeek = Carbon::now()->endOfWeek()->timestamp;
        
        $scheduleOnTwoWeeks = [];
        foreach($schedule as $event)
        {
            if(strtotime($event['start'])>=$startWeek && strtotime($event['end'])<=$endWeek)
            {
                $scheduleOnTwoWeeks[] = $event;
                $scheduleOnTwoWeeks[] = [
                    'start' => str_replace(" ", "T", Carbon::parse($event['start'])->addWeek()),
                    'end' => str_replace(" ", "T", Carbon::parse($event['end'])->addWeek())
                ];
            }
        }
        
        usort($scheduleOnTwoWeeks, 'sortSchedule');
        
        return $scheduleOnTwoWeeks;
    }
    
}