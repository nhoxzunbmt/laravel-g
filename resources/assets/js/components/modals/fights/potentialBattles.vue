<template>
    <modal name="potential-battles" :adaptive="true" width="60%" :minWidth="minWidth" height="auto" :scrollable="true" :pivotY="pivotY">
        <div class="row heading-bg">
        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button type="button" class="close pull-right txt-dark" aria-label="Close" @click="close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="txt-dark">Schedule to {{date}}. Potential battles.</h5>
        	</div>
        </div>
        <div class="row" v-if="date!==null">
        	<div class="col-lg-12 col-md-12 col-xs-12">
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
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
    								    <tbody>
                                            <tr v-for="team in potentialBattles">
                                                <td>
                                                    <router-link  :to="{ name: 'team.detail', params: { slug: team.slug }}">
                                                        <img :src="getImageLink(team.image, 'avatar_team')" class="img-responsive team-image" :alt="team.title" />
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
                                                <td class="text-center">
                                                    <button v-if="authenticated && user.id==userTeam.capt_id" class="btn btn-primary btn-icon left-icon btn-xs ml-10">Invite to battle</button>
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
    </modal>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    name: 'potential-battles-modal',
    props: ['date', 'potentialBattles', 'userTeam'],
    computed: {
        ...mapGetters({
            user: 'authUser',
            authenticated: 'authCheck',
        })
    },
    data() {
        return {
            pivotY: 0.1,
            minWidth: 600,
            title: null,
            success: false,
            error: false,
            response: null
        }
    },
    methods: {
        close()
        {
            console.log(this.potentialBattles);
            this.$modal.hide('potential-battles');   
        }
    },
}
</script>