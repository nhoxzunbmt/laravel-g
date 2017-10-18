<template>
<div class="row">
	<div class="col-lg-12 col-md-12 col-xs-12 mb-50">
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
                
                <table class="table table-bordered mb-0 schedule-list">
					<thead>
                        <tr>
                            <th>D/H:00</th>
                            <th v-for="i in hours">{{i}}</th>
                        </tr>
					</thead>
					<tbody>
                        <tr v-for="(day, dayNumber) in daysOfWeek">
                            <th>{{day}}</th>
                            <td v-for="i in hours" :data-date="dayNumber+','+i+':00:00'" class="text-center" :title="day+' '+i+':00:00'" @click="selectSchedule(dayNumber+','+i)">
                                <i v-if="checkIn(dayNumber+','+i)" class="fa fa-check text-success"></i>
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
    export default {
        name: 'calendar-shedule',
        //props: ['schedule'],
        computed:{
            hours: function () 
            {
                var ahours = Array.apply(null, {length: 24}).map(Number.call, Number);
                
                ahours = ahours.map(function(hour) 
                {
                    if(parseInt(hour)<10)
                        hour = '0'+hour;
                    
                    return hour;
                });
                
                return ahours;
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
                },
                schedule: ['0,03', '2,08']
            }
        },
        methods:{
            selectSchedule(value)
            {
                var ind = this.schedule.indexOf(value);
                
                if(ind>-1)
                {
                    this.schedule.splice(ind, 1);
                }else{
                    this.schedule.push(value);
                }
            },
            checkIn: function (value) 
            {
                return this.schedule.indexOf(value) > -1 ? true : false;
            }
        }
    }
</script>

<style>
    .schedule-list tbody tr td:hover{
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
</style>