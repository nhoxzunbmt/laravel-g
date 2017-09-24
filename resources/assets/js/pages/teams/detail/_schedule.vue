<template>
    <div>
        <div class="row" v-if="team!==null">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    			<div class="panel panel-default card-view">
    				<div class="panel-wrapper collapse in">
    					<div class="panel-body">
                            <h4 class="mb-10">Calendar</h4>
                            <div class="row">
                            	
            					<div class="col-md-12 col-sm-12 col-xs-12">
                                
                                    <full-calendar ref="calendar" :events="events" :header="calHeader" :config="calConfig" :editable="false"></full-calendar>
                                    
                                </div>
                            </div>
                        </div>
    				</div>	
    			</div>	
    		</div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    metaInfo: {
        title: 'Schedule'
    },
    data() {
        return {
            success: false,
            error: false,
            response: null,
            team: null,
            events:[],
            calConfig:{
                slotDuration:'00:60:00',
                slotLabelInterval:'00:60:00',
                slotEventOverlap:false,
                allDaySlot: false,
                height: 600,
                columnFormat: 'ddd'
            },
            calHeader:{
                left:   '',
                center: '',// 'title',
                right:  ''//'today prev,next'
            }
        }
    },
    mounted() 
    {
        Event.listen('teamEditLoad', event => {
            this.team = event.team;
            this.events = this.team.schedule!==null ? this.team.schedule : [];
        });
        this.team = this.$parent.team;      
    }
}
</script>

<style>
    @import '~fullcalendar/dist/fullcalendar.css';
    .fc-time-grid .fc-slats td{
        height: 40px;
    }
    .fc th.fc-day-header{
        height: 40px;
        vertical-align: middle;
    }
    .fc-unthemed td.fc-today {
        background: transparent;
        border-left: 2px solid #469408;
        border-right: 2px solid #469408; 
    }
    .fc button{
        padding: 5px 10px;
    }
    .fc-event .fc-bg{
        background: #177ec1;
        opacity: 1;
    }
    .fc-time-grid-event .fc-time{
        text-align: center;
        vertical-align: middle;
        line-height: 40px;
        cursor: pointer;
    }
    .fc-toolbar h2{
        font-size: 22px;
        line-height: 34px;
    }
    .fc-toolbar.fc-header-toolbar{
        display: none;
    }
</style>