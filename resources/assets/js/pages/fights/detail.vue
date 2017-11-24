<template>
    <div>
    
        <div class="row mt-20" v-if="fight!==null">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    			<div class="panel panel-default card-view">
                    <div class="panel-heading">
            			<div class="pull-left">
            				<h3 class="panel-title txt-dark">
                                {{fight.title}}
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
                                                <th>Teams</th>
                                                <th>Datetime</th>
                                                <th>Bet</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                    				    <tbody>
                                            <tr>
                                                <td class="text-center">
                                                    
                                                    <div class="w-45p-inline text-right">
                                                        <router-link  :to="{ name: 'team.detail', params: { slug: fight.invitations[0].team.slug }}" class="vm-title">
                                                            <span class="mr-20">{{ fight.invitations[0].team.title}}</span>
                                                            <img :src="getImageLink(fight.invitations[0].team.image, 'avatar_team')" class="img-responsive team-image" /> 
                                                        </router-link>
                                                    </div>
                                                    
                                                    <span class="ml-10 mr-10">VS</span>
                                                    
                                                    <div class="w-45p-inline text-left">
                                                        <router-link  :to="{ name: 'team.detail', params: { slug: fight.invitations[1].team.slug }}" class="vm-title">
                                                            <img :src="getImageLink(fight.invitations[1].team.image, 'avatar_team')" class="img-responsive team-image" /> 
                                                            <span>{{ fight.invitations[1].team.title}}</span>
                                                        </router-link>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{fight.start_at}}</td>
                                                <td class="text-center">{{fight.bet}}</td>
                                                <td class="text-center">
                                                    <span v-if="fight.status==0" class="text-muted">
                                                        waiting for <br />invites answers
                                                    </span>
                                                    <span v-if="fight.status==2" class="text-danger">
                                                        canceled, <br />{{fight.cancel_text}}
                                                    </span>
                                                    <span v-if="fight.status==1" class="text-success">
                                                        active
                                                    </span>
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
    
    export default {
        metaInfo () {
            return {
                title: this.title,
            }
        },
        computed: {
            ...mapGetters({
                user: 'authUser',
                authenticated: 'authCheck',
            }),
        },
        mounted() {
            this.getFight();
            this.getStreams();
        },
        data : function() {
            return {
                title: 'Detail fight',
                fight: null,
                teams: [],
                streams: []
            }
        },
        methods : {
            getFight: function()
            {
                var query = this.ArrayToUrl({
                    '_with' : 'game,invitations.team'
                });
                
                axios.get('/api/fights/'+this.$route.params.id+"?"+query).then((response) => {
                    this.$set(this, 'fight', response.data);
                    
                    this.title = this.fight.title;
                    this.$meta().refresh();
                });
            },
            getStreams: function()
            {
                var query = this.ArrayToUrl({
                    'team_id' : this.user.team_id
                });
                
                axios.get('/api/streams/?'+query).then((response) => {
                    this.$set(this, 'streams', response.data);
                });
            }
        }
    }
</script>