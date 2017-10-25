<template>
    <div>
    
        <div class="row heading-bg">
        	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h5 class="txt-dark">Fights</h5>
        	</div>
        </div>
        
        <div class="row" v-if="user!==null">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    			<div class="panel panel-default card-view">
    				<div class="panel-wrapper collapse in">
    					<div class="panel-body">
                            <h4 class="mb-10">Calendar</h4>
                            <div class="row">
                            	
            					<div class="col-md-12 col-sm-12 col-xs-12">
                                
                                    <calendar-fights :schedule="events"></calendar-fights>
 
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
            title: 'Fights',
            titleTemplate: null
        },
        computed: {
            ...mapGetters({
                user: 'authUser',
                authenticated: 'authCheck',
            })
        },
        mounted() {
            if(this.user.team_id!=null)
            {
                this.getCalendarFights(this.user.team_id);
            }
        },
        data : function() {
            return {
                events: []
            }
        },
        methods : {
            getCalendarFights: function(team_id)
            {
                axios.get('/api/teams/'+team_id + '/fights/calendar').then((response) => {
                    this.$set(this, 'events', response.data);
                });
            }
        }
    }
</script>