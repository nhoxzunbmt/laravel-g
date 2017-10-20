<template>
    <div>
        <div class="row" v-if="user!==null">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    			<div class="panel panel-default card-view">
    				<div class="panel-wrapper collapse in">
    					<div class="panel-body">
                            <h4 class="mb-10">Calendar</h4>
                            <div class="row">
                            	
            					<div class="col-md-12 col-sm-12 col-xs-12">
                                
                                    <calendar-shedule :schedule="events" blockSize="3" editable="true"></calendar-shedule>
                                
                                    <!--<full-calendar ref="calendar" :events="events" :header="calHeader" :config="calConfig" :editable="false" @event-selected="eventSelected" @event-created="eventCreate"></full-calendar>-->
                                    
                                    <div class="form-actions mt-10">
                                        <button type="submit" class="btn btn-primary mr-10" @click="save">
                                            <span>Save</span>
                                        </button>			
    								</div>
                                    
                                    <div class="alert alert-success alert-dismissable mt-20" v-if="success">
                                        <p>Calendar data is updated.</p>
                                    </div>
                    
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
        title: 'Calendar'
    },
    computed: {
        ...mapGetters({
            user: 'authUser',
            authenticated: 'authCheck',
        })
    },
    data() {
        return {
            success: false,
            error: false,
            response: null,
            events:[]/*,
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
            }*/
        }
    },
    mounted() 
    {
        this.events = this.user.schedule!==null ? this.user.schedule : [];       
    },
    methods: {
        save(event) {
            event.preventDefault()
            //console.log(this.events);
            
            this.user.schedule = this.events;
            
            axios.post('/api/users', this.user).then(response => {
                this.error = false;
                this.success = true;
                
            }).catch(error => {
                this.response = error.response.data
                this.error = true
                this.success = false;                
            });
        }/*,
        eventSelected(event, jsEvent, view)
        {
            var event = event;
            var events = this.events;
            this.events = events.filter(function(el) { 
                return el.start != event.start.format() && el.end!=event.end.format(); 
            });
            
            this.success = false;
        },
        eventCreate(event)
        {
            this.success = false;
            
            if(this.events==null)
            {
                this.events  = [];
            }
            
            //console.log(event.start.format());
            
            this.events.push({
                start: event.start.format(),
                end: event.end.format()
            });
        }*/
    },
}
</script>
<!--
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
</style>-->