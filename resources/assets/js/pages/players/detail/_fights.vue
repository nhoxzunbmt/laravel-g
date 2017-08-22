<template>
<div>
    <div class="row" v-if="player!=null && player.fights!==null">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                    <h3 class="panel-title txt-dark">
                        Individual fights
                    </h3></div> 
                    <div class="clearfix"></div>
                </div>
				<div class="panel-wrapper collapse in">
					<div class="panel-body">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Start at</th>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Game</th>
                                            <th>Quantity</th>
                                            <th>Winner</th>
                                            <th>Canceled</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
								    <tbody>
                                        <tr v-for="fight in player.fights">
                                            <td>
                                                {{fight.start_at}}
                                            </td>
                                            <td>
                                                <router-link  :to="{ name: 'fight', params: { id: fight.id }}">
                                                    {{ fight.title}}
                                                </router-link>
                                            </td>
                                            <td class="text-center">{{ fight.type}}</td>
                                            <td class="text-center" v-if="fight.game!==null">{{ fight.game.title}}</td>
                                            <td class="text-center" v-else></td>
                                            <td class="text-center" v-if="fight.type=='personal'">{{fight.users.length}}/{{fight.count_parts}}</td>
                                            <td class="text-center" v-else>{{fight.teams.length}}/{{fight.count_parts}}</td>
                                            <td class="text-center">{{fight.winner}}</td>
                                            <td class="text-center" v-if="fight.canceled==1">{{fight.cancel_text | truncate(40, '...') }}</td>
                                            <td class="text-center" v-else></td>
                                            <td class="text-center">
                                                <span v-if="fight.active==0">not active</span>
                                                <span v-if="fight.active==1">active</span>
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
    
    <div class="row" v-if="player!=null && team_figths!==null">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                    <h3 class="panel-title txt-dark">
                        Team fights
                    </h3></div> 
                    <div class="clearfix"></div>
                </div>
				<div class="panel-wrapper collapse in">
					<div class="panel-body">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Start at</th>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Game</th>
                                            <th>Quantity</th>
                                            <th>Winner</th>
                                            <th>Canceled</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
								    <tbody>
                                        <tr v-for="fight in team_figths">
                                            <td>
                                                {{fight.start_at}}
                                            </td>
                                            <td>
                                                <router-link  :to="{ name: 'fight', params: { id: fight.id }}">
                                                    {{ fight.title}}
                                                </router-link>
                                            </td>
                                            <td class="text-center">{{ fight.type}}</td>
                                            <td class="text-center" v-if="fight.game!==null">{{ fight.game.title}}</td>
                                            <td class="text-center" v-else></td>
                                            <td class="text-center">{{fight.teams.length}}/{{fight.count_parts}}</td>
                                            <td class="text-center">{{fight.winner}}</td>
                                            <td class="text-center" v-if="fight.canceled==1">{{fight.cancel_text | truncate(40, '...') }}</td>
                                            <td class="text-center" v-else></td>
                                            <td class="text-center">
                                                <span v-if="fight.active==0">not active</span>
                                                <span v-if="fight.active==1">active</span>
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
</template>


<script>
import { mapGetters } from 'vuex'
import axios from 'axios'
import swal from 'sweetalert2'

export default {
    computed: {
        ...mapGetters({
            user: 'authUser',
            authenticated: 'authCheck',
        })     
    },
    ready() {
        Event.listen('playerDetailLoad', event => {
            this.player = event.player;
        });        
    },
    data() {
        return {
            success: false,
            error: false,
            player: null,
            response: null,
            team_figths: null
        }
    },
    mounted() {
        
        if(this.player===null)
        {
            Event.listen('playerDetailLoad', event => {
                this.player = event.player;
            });
            
            this.player = this.$parent.player;
        }
        
        this.getUserTeamsFights();
    },
    methods: {       
        getUserTeamsFights()
        {
            var query = this.ArrayToUrl({
                '_with' : 'game,teams'
            });
            
            axios.get('/api/user/'+this.$route.params.id+"/teams/all/fights?"+query).then((response) => {
                this.$set(this, 'team_figths', response.data);
            });
        },
    },
}
</script>