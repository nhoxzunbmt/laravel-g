<template>
    <div>
    
        <div class="row heading-bg">
        	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h5 class="txt-dark">Fights</h5>
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
        
        
        
        <!--<div class="row" v-if="user!==null && invitations.length>0">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    			<div class="panel panel-default card-view">
    				<div class="panel-wrapper collapse in">
    					<div class="panel-body">
                            <h4 class="mb-10">Invitations to battles</h4>
                            <div class="row">
            					<div class="col-md-12 col-sm-12 col-xs-12">
                                    
                                    <div class="table-wrap">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Team</th>
                                                        <th>Datetime</th>
                                                        <th>Bet</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
            								    <tbody>
                                                    <tr v-for="invitation in invitations">
                                                        
                                                        <td>
                                                            <router-link  :to="{ name: 'team.detail', params: { slug: invitation.fight.created_by_team.slug }}" class="vm-title">
                                                                <img :src="getImageLink(invitation.fight.created_by_team.image, 'avatar_team')" class="img-responsive team-image" /> 
                                                                <span>{{ invitation.fight.created_by_team.title}}</span>
                                                            </router-link>
                                                        </td>
                                                        <td class="text-center">{{invitation.fight.start_at}}</td>
                                                        <td class="text-center">{{ invitation.fight.bet}}</td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0)" @click="answerToInviteFight(invitation.id, 1)" title="confirm" v-if="user.id==invitation.team.capt_id"><i class="fa fa-check text-success"></i></a>&nbsp;
                                                            <a href="javascript:void(0)" @click="answerToInviteFight(invitation.id, 2)" title="reject" v-if="user.id==invitation.team.capt_id"><i class="fa fa-times text-danger"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
    				</div>	
    			</div>	
    		</div>
        </div>-->
        
    </div>    
</template>

<script> 
    import { mapGetters } from 'vuex'
    import axios from 'axios'
    import swal from 'sweetalert2'
    
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
                //this.getCalendarFights(this.user.team_id);
                //this.getTeam(this.user.team_id);
                this.getInvitationFights(this.user.team_id);
            }
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
                        name: 'Confirmed battles',
                        route: 'fights.confirmed'
                    },
                    {
                        name: 'Received invitations',
                        route: 'fights.invitations.received'
                    },
                    {
                        name: 'Sent invitations',
                        route: 'fights.invitations.sent'
                    }
                ],
            }
        },
        methods : {
            getInvitationFights(team_id)
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
            },
            answerToInviteFight(fight_team_id, status)
            {
                axios.put('/api/fight_team/'+fight_team_id, {
                    status: status
                }).then((response) => {
    
                    swal({
                        type: 'success',
                        html: response.data.message
                    });
                    
                });
            }
        }
    }
</script>