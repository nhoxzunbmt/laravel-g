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
        
        //$startWeek = Carbon::now()->startOfWeek()->timestamp;
        //$endWeek = Carbon::now()->endOfWeek()->timestamp;
        
        $startWeek = Carbon::parse('this sunday');
        $endWeek = Carbon::parse('this sunday')->addDays(7);
        
        $scheduleOnTwoWeeks = [];
        foreach($schedule as $event)
        {
            if(strtotime($event['start'])>=strtotime($startWeek) && strtotime($event['end'])<=strtotime($endWeek))
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
    
    /**
     * Get crossing schedules with sorting by time
     */
    public static function getCrossingSchedule($collection, $obj = false)
    {
        $arCrossingSchedules = false;
        
        foreach($collection as $object)
        {
            if(!$arCrossingSchedules)
            {
                $arCrossingSchedules = $object->schedule;
            }else{
                $arCrossingSchedules = array_uintersect($arCrossingSchedules, $object->schedule, "diffSchedules");
            }
        }
        
        if($obj)
        {
            $arCrossingSchedules = array_uintersect($arCrossingSchedules, $obj->schedule, "diffSchedules");
        }
        
        usort($arCrossingSchedules, 'sortSchedule');
        
        return $arCrossingSchedules;
    }
    
    /**
     * Get 3 hours crossing blocks of schedules during 7 days
     */
    public static function getCrossingBlocks($arSchedules)
    {
        //Get 7 days range
        //$startDatetime = Carbon::now()->timestamp;
        //$endDatetime = Carbon::now()->addWeek()->timestamp;
        $startDatetime = Carbon::parse('this sunday');
        $endDatetime = Carbon::parse('this sunday')->addDays(7);

        //Calculate blocks schedules
        $blockHours = 3;
        $countInBlock = 0;
        $arBlock = [];
        $blockSchedules = [];
        $lastSchedule = false;
        foreach($arSchedules as $arSchedule)
        {
            if(strtotime($arSchedule['start'])>=strtotime($startDatetime) && strtotime($arSchedule['end'])<=strtotime($endDatetime))
            {
                if(!$lastSchedule)
                {
                    $lastSchedule = $arSchedule;
                    $arBlock[] = $lastSchedule; 
                    $countInBlock++;
                }else{
                    
                    if($lastSchedule['end']==$arSchedule['start'])
                    {
                        $lastSchedule = $arSchedule;
                        $arBlock[] = $lastSchedule;
                        $countInBlock++;
                    }else{
                        
                        $countCrosses = $countInBlock-$blockHours+1; //or count($arBlock)-$blockHours;
                        if($countCrosses>0)
                        {
                            //Get from crossing block first hours
                            $hourCrossingBlocks = array_slice($arBlock, 0, $countCrosses);
                            $blockSchedules = array_merge($blockSchedules, $hourCrossingBlocks);
                        }
                        
                        $countInBlock = 1;
                        $lastSchedule = $arSchedule;
                        $arBlock = [];
                        $arBlock[] = $lastSchedule;
                    }
                }
            }
        }
        
        $countCrosses = $countInBlock-$blockHours+1; //or count($arBlock)-$blockHours;
        if($countCrosses>0)
        {
            //Get from crossing block first hours
            $hourCrossingBlocks = array_slice($arBlock, 0, $countCrosses);
            $blockSchedules = array_merge($blockSchedules, $hourCrossingBlocks);
        }
        
        //dd($blockSchedules);
        
        return $blockSchedules;
    }
}