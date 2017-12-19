<template>
    <div>
    
        <div class="row heading-bg">
        	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h5 class="txt-dark">Matches</h5>
        	</div>
        </div>
        
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
        		<div class="panel panel-default card-view  pa-0">
        			<div class="panel-wrapper collapse in">
        				<div class="panel-body  pa-0">
        					<div class="tab-struct custom-tab-1 mt-5 mb-10 weight-600">
    							<ul class="nav nav-tabs">
                                    <li v-for="tab in tabs" class="nav-item">
                                        <router-link :to="{ name: tab.route }" class="nav-link" active-class="active">
                                            {{ tab.name }}
                                        </router-link>
                                    </li>
    							</ul>
    						</div>
                            
                            <div class="tab-content pa-15">
                                <div class="tab-pane fade active in">
                                    <transition name="fade" mode="out-in">
                                        <router-view></router-view>
                                    </transition>
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
    import swal from 'sweetalert2'
    
    export default {
        metaInfo: {
            title: 'Matches',
            titleTemplate: null
        },
        computed: {
            ...mapGetters({
                user: 'authUser',
                authenticated: 'authCheck',
            })
        },
        mounted() {
        },
        data : function() {
            return {
                events: [],
                team: [],
                invitations: [],
                tabs: [
                    {
                        name: 'Calendar',
                        route: 'fights.calendar'
                    },
                    {
                        name: 'Confirmed matches',
                        route: 'fights.confirmed'
                    },
                    {
                        name: 'Invites received',
                        route: 'fights.invitations.received'
                    },
                    {
                        name: 'Invites sent',
                        route: 'fights.invitations.sent'
                    }
                ],
            }
        },
        methods : {
            /*getInvitationFights(team_id)
            {
                var query = this.ArrayToUrl({
                    'team_id' : team_id,
                    'status' : 0,
                    "_with" : 'fight,team,fight.createdByTeam'
                });
                
                axios.get('/api/fight_team?'+query).then((response) => {
                    var invitations = response.data;
                    var _self = this;
                    
                    invitations.forEach(function(invitation)
                    {
                        invitation.fight.start_at = _self.dateConvertUTC(invitation.fight.start_at, -1);
                    });
                    
                    this.$set(this, 'invitations', invitations);
                }); 
            }*/
        }
    }
</script>