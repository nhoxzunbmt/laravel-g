<template>
<div>
    <div class="row" v-if="player!==null && player.teams!==null">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    			<div class="panel panel-default card-view">
    				<div class="panel-wrapper collapse in">
    					<div class="panel-body">
        
                            <div class="table-wrap">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Logo</th>
                                                <th>Name</th>
                                                <th>Total fights</th>
                                                <th>Count wins</th>
                                                <th>Victory rate</th>
                                                <th>Earned</th>
                                                <th>Amount paid to investors</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
    								    <tbody>
                                            <tr v-for="team in player.teams">
                                                <td>
                                                    <router-link  :to="{ name: 'team.detail', params: { slug: team.slug }}">
                                                        <img :src="getImageLink(team.image)" class="img-responsive team-image" :alt="team.title" />
                                                    </router-link>
                                                </td>
                                                <td>
                                                    <router-link  :to="{ name: 'team.detail', params: { slug: team.slug }}">
                                                        {{ team.title}}
                                                    </router-link>
                                                </td>
                                                <td class="text-center">{{team.count_fights}}</td>
                                                <td class="text-center">{{team.count_wins}}</td>
                                                <td class="text-center" v-if="team.count_fights>0">{{ Number((team.count_wins/team.count_fights).toFixed(2))}}</td>
                                                <td class="text-center" v-else>0</td>
                                                <td class="text-center">{{team.total_sum}}</td>
                                                <td class="text-center">{{team.payed_dividents}}</td>
    
                                                <td class="text-center">
                                                    <span v-if="team.status==0">pending</span>
                                                    <span v-if="team.status==1">accepted</span>
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
            this.player = event.team;
        });
    },
    data() {
        return {
            success: false,
            error: false,
            player: null,
            response: null
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
    
    },
    methods: {       
        
    },
}
</script>