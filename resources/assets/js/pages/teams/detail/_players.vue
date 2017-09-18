<template>
    <div>                             
        <div class="row" v-if="team!==null">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    			<div class="panel panel-default card-view">
                    <div class="panel-heading">
            			<div class="pull-left">
            				<h3 class="panel-title txt-dark">
                                Players in team
                            </h3>
            			</div>
            			<div class="clearfix"></div>
            		</div>
    				<div class="panel-wrapper collapse in">
    					<div class="panel-body">
                            <div class="table-wrap" v-if="team.users!==null">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>avatar</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Captain</th>
                                            </tr>
                                        </thead>
    								    <tbody>
                                            <tr v-for="player in team.users">
                                                <td>
                                                    <router-link  :to="{ name: 'player', params: { id: player.id }}">
                                                        <img :src="getImageLink(player.avatar)" class="img-responsive team-image" />
                                                    </router-link>
                                                </td>
                                                <td>
                                                    <router-link  :to="{ name: 'player', params: { id: player.id }}">
                                                        {{ player.name}}
                                                    </router-link>
                                                </td>
                                                <td class="text-center">{{ player.email}}</td>
                                                <td class="text-center"><i class="fa fa-check text-danger" v-if="player.id==team.capt_id"></i></td>
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
        
        <div class="row" v-if="invitations!==null">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    			<div class="panel panel-default card-view">
                    <div class="panel-heading">
            			<div class="pull-left">
            				<h3 class="panel-title txt-dark">
                                Waiting for an answer
                            </h3>
            			</div>
            			<div class="clearfix"></div>
            		</div>
    				<div class="panel-wrapper collapse in">
    					<div class="panel-body">
                            <div class="table-wrap">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>avatar</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Captain</th>
                                            </tr>
                                        </thead>
    								    <tbody>
                                            <tr v-for="invitation in invitations">
                                                <td>
                                                    <router-link  :to="{ name: 'player', params: { id: invitation.user.id }}">
                                                        <img :src="getImageLink(invitation.user.avatar)" class="img-responsive team-image" />
                                                    </router-link>
                                                </td>
                                                <td>
                                                    <router-link  :to="{ name: 'player', params: { id: invitation.user.id }}">
                                                        {{ invitation.user.name}}
                                                    </router-link>
                                                </td>
                                                <td class="text-center">{{ invitation.user.email}}</td>
                                                <td class="text-center"><i class="fa fa-check text-danger" v-if="invitation.user.id==team.capt_id"></i></td>
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
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    data() {
        return {
            success: false,
            error: false,
            team: null,
            response: null,
            invitations: null
        }
    },
    computed: {
        ...mapGetters({
            user: 'authUser',
            authenticated: 'authCheck',
        })
    },
    mounted() {
        Event.listen('teamEditLoad', event => {
            this.team = event.team;
        });
        
        this.team = this.$parent.team;
        
        var self = this;
        setTimeout(function(){
            self.getInvitations(self.team.id);
        }, 1000);
    },
    methods: 
    {
        getInvitations(team_id)
        {
            var query = this.ArrayToUrl({
                'team_id' : team_id,
                'status' : 0,
                "_with" : 'user,team'
            });
            
            axios.get('/api/team_user?'+query).then((response) => {
                this.$set(this, 'invitations', response.data);
            });               
        }
    },
}
</script>