<template>
<div class="row">
	<div class="col-lg-12 col-md-12 col-xs-12 mb-10">
        <div class="table-wrap">
			<div class="table-responsive" data-toggle="table">
				<!--<table class="table mb-0">
					<thead>
                        <tr>
                            <th>#</th>
                            <th v-for="day in daysOfWeek">{{day}}</th>
                        </tr>
					</thead>
					<tbody>
                        <tr v-for="i in hours">
                            <td>{{i}}:00</td>
                            <td v-for="(day, dayNumber) in daysOfWeek" :data-date="dayNumber+','+i+':00:00'">{{i}}:00</td>
                        </tr>
					</tbody>
				</table>-->
                
                <div class="alert alert-warning alert-dismissable" v-if="blockHours.length==0">
					<i class="zmdi zmdi-info-outline pr-15 pull-left"></i>
                    <p class="pull-left" v-if="editable!='false'">You need fill {{blockSize}}-hours blocks!</p>
                    <p class="pull-left" v-else>The team doesn't have crossed blocks! The team will not be able to participate in the battles!</p>
					<div class="clearfix"></div>
				</div>
                <div class="alert alert-success alert-dismissable" v-else>
					<i class="zmdi zmdi-check pr-15 pull-left"></i>
                    <p class="pull-left" v-if="editable!='false'">Great! You have {{blockHours.length}} blocks!</p>
                    <p class="pull-left" v-else>Player has {{blockHours.length}} blocks!</p>
					<div class="clearfix"></div>
				</div>
                
                <table class="table table-bordered mb-0 schedule-list" v-bind:class="{ 'table-event-editable': editable!='false'}">
					<thead>
                        <tr>
                            <th>D/H:00</th>
                            <th v-for="i in hours">{{i}}</th>
                        </tr>
					</thead>
					<tbody>
                        <tr v-for="(day, dayNumber) in daysOfWeek">
                            <th>{{day}}</th>
                            <td v-for="i in hours" :data-date="dayNumber+','+i+':00:00'" class="text-center" :title="day+' '+i+':00:00'" @click="selectSchedule(dayNumber+','+i)"  v-bind:class="{ 'active-event': checkIn(blockHours, dayNumber+','+i)}">
                                <i v-if="checkIn(schedule, dayNumber+','+i)" class="fa fa-check text-success"></i>
                            </td>
                        </tr>
					</tbody>
				</table>
                
			</div>
		</div>      
     </div>
</div>
</template>

<script>

    function compare(a,b) 
    {
        if (a.d < b.d)
        {
            return -1
        }else if (a.d > b.d){
            return 1;
        }else{
            if(a.h<b.h)
            {
                return -1
            }else{
                return 1;
            }
        }
        
        return 0;
    }

    export default {
        name: 'calendar-shedule',
        props: ['schedule', 'blockSize', 'editable'],
        computed:{
            hours: function () 
            {
                var ahours = Array.apply(null, {length: 24}).map(Number.call, Number);//range 0-23
                
                ahours = ahours.map(function(hour) 
                {
                    if(parseInt(hour)<10)
                        hour = '0'+hour;
                    
                    return hour;
                });
                
                return ahours;
            },
            blockHours: function()
            {
                var blockSize = this.blockSize;
                if(blockSize==1)
                    return this.schedule;
                
                var bHours = [];
                this.schedule.forEach(function(event)
                {
                    var arr = event.split(',');
                    bHours.push({
                        'd' : parseInt(arr[0]),
                        'h' : parseInt(arr[1])
                    });
                });
                
                bHours.sort(compare);
                
                var last_event = false;
                var blockSchedules = [];
                var blocks = [];
                var i = 0;
                
                //Add 3 first hours to the end to check crossing going to next week
                var startHours = Array.apply(null, {length: blockSize}).map(Number.call, Number);   //need for next week start
                bHours.forEach(function(event)
                {
                    if(event.d==0 && startHours.indexOf(event.h)>-1)
                    {
                        bHours.push(event);
                    }
                });
                
                //console.log(bHours);
                
                bHours.forEach(function(event)
                {
                    if(!last_event)
                    {
                        last_event = event;
                        blocks.push(event);
                        i++;
                    }else{
                        
                        if( ((event.d-last_event.d==1 || event.d==0 && last_event.d==6) && last_event.h==23 && event.h==0) || (event.d==last_event.d && (event.h-last_event.h==1)) )
                        {
                            last_event = event;
                            blocks.push(event);
                            i++;   
                        }else{
                            var countCrosses = i - blockSize+1;

                            if(countCrosses>0)
                            {
                                var crossingBlocks = blocks.slice(0, countCrosses);
 
                                crossingBlocks.forEach(function(event2){
                                    blockSchedules.push(event2);
                                });
                            }

                            last_event = event;
                            blocks = [];
                            blocks.push(event);
                            i = 1;                        
                        }
                        
                    }                    
                });
                
                var countCrosses = i - blockSize+1;            
                if(countCrosses>0)
                {
                    var crossingBlocks = blocks.slice(0, countCrosses);
                    crossingBlocks.forEach(function(event2){
                        blockSchedules.push(event2);
                    });
                }
                
                blockSchedules = blockSchedules.map(function(event) 
                {
                    var hour = event.h;
                    if(parseInt(hour)<10)
                        hour = '0'+hour;
                    
                    return event.d+","+hour;
                });
                
                blockSchedules = Array.from(new Set(blockSchedules));//array_unique
                                
                return blockSchedules;
            }
        },
        data() {
            return {
                daysOfWeek: {
                    0: 'Sun',
                    1: 'Mon',
                    2: 'Tue',
                    3: 'Wed',
                    4: 'Thu',
                    5: 'Fri',
                    6: 'Sat'
                }/*
                schedule: ['0,03', '2,08'],
                blockSize: 3*/
            }
        },
        methods:{
            selectSchedule(value)
            {
                if(this.editable=='false')
                    return false;
                
                var ind = this.schedule.indexOf(value);
                
                if(ind>-1)
                {
                    this.schedule.splice(ind, 1);
                }else{
                    this.schedule.push(value);
                }
                
                console.log(this.blockHours);
            },
            checkIn: function (values, value) 
            {
                return values.indexOf(value) > -1 ? true : false;
            }
        }
    }
</script>

<style>
    .schedule-list.table-event-editable tbody tr td:hover{
        cursor: pointer;
        border: 2px solid #469408 !important;
    }

    .schedule-list thead tr th
    {
        border-bottom: 1px solid #fff !important;
    }
    .schedule-list tbody tr th, .schedule-list thead tr th:first-child{
        color: #fff;
        border-right: 1px solid #fff !important;
    }
    .schedule-list tbody tr td.active-event{
        background-color: #3a87ad;
    }
    .schedule-list tbody tr td.active-event i{
        color: #fff !important;
    }
</style>