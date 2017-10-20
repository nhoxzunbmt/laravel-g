<template>
    <div>
        <div class="row" v-if="team!==null && team.schedule!=null">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    			<div class="panel panel-default card-view">
    				<div class="panel-wrapper collapse in">
    					<div class="panel-body">
                            <h4 class="mb-10">Calendar</h4>
                            <div class="row">
                            	
            					<div class="col-md-12 col-sm-12 col-xs-12">
                                    <calendar-shedule :schedule="team.schedule" blockSize="1" editable="false"></calendar-shedule>
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
            events:[]
        }
    },
    mounted() 
    {
        Event.listen('teamEditLoad', event => {
            this.team = event.team;
        });
        this.team = this.$parent.team;
        //this.events = this.team.schedule!==null ? this.team.schedule : [];      
    }
}
</script>