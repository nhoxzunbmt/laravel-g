<template>
    <div>
    
        <div class="row heading-bg">
        	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h5 class="txt-dark">Teams</h5>
        	</div>
        </div>
        
        <!--<div class="row">
        	<div class="col-md-12">
        		<div class="panel panel-default card-view">
        			<div class="panel-wrapper collapse in">
        				<div class="panel-body">
                            <div class="form-wrap">
                                <form autocomplete="off" id="filter-form">
                                    <div class="row">
        								<div class="col-md-3">
                                            <select v-model="status" name="status" class='form-control' data-style="form-control btn-default btn-outline" id="status_list">
                                                <option v-for="status in statuses" v-bind:value="status.id">
                                                    {{ status.title }}
                                                </option>
                                            </select>	
        								</div>
                                        <div class="col-md-3">
                                            <select v-model="game_id" name="game_id" class='form-control' data-style="form-control btn-default btn-outline" id="game_list">
                                                <option v-for="game in games" v-bind:value="game.id">
                                                    {{ game.title }}
                                                </option>
                                            </select>	
        								</div>
                                    </div>
                                </form>
        					</div>
        				</div>
        			</div>
        		</div>
        	</div>
        </div>-->
    
        <div class="row" v-if="teams!==null">
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
                                                <th>Players</th>
                                                <th>Game</th>
                                                <th>Total fights</th>
                                                <th>Count wins</th>
                                                <th>Victory rate</th>
                                                <th>Earned</th>
                                                <th>Amount paid to investors</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
    								    <tbody>
                                            <tr v-for="team in teams">
                                                <!--<td>{{ moment(team.created_at, "YYYY-MM-DD h:mm:ss").fromNow()}}</td>-->
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
                                                <td class="text-center"><router-link  :to="{ name: 'team.detail.players', params: { slug: team.slug }}">{{ team.users_accepted.length}}</router-link> / {{ team.quantity}}</td>
                                                <td class="text-center" v-if="team.game!==null">{{ team.game.title}}</td>
                                                <td class="text-center" v-else></td>
                                                
                                                <td class="text-center">{{team.count_fights}}</td>
                                                <td class="text-center">{{team.count_wins}}</td>
                                                <td class="text-center" v-if="team.count_fights>0">{{ Number((team.count_wins/team.count_fights).toFixed(2))}}</td>
                                                <td class="text-center" v-else>0</td>
                                                <td class="text-center">{{team.total_sum}}</td>
                                                <td class="text-center">{{team.payed_dividents}}</td>
    
                                                <td class="text-center">
                                                    <span v-if="team.status==0">pending</span>
                                                    <span v-if="team.status==1">accepted</span>
                                                    <button v-if="team.quantity>team.users_accepted.length && authenticated && user.id!==team.capt_id" @click="joinTeam(team.id)" class="btn btn-primary btn-icon left-icon btn-xs ml-10"><i aria-hidden="true" class="fa fa-check"></i>&nbsp; join the team</button>
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
        
        <div class="row">
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <nav>
        	        <ul class="pagination">
        	            <li v-if="pagination.current_page > 1">
                            <router-link :to="getLink(pagination.current_page - 1)" aria-label="Previous">
                                <span aria-hidden="true">«</span>
                            </router-link>                            
        	            </li>
        	            <li v-for="page in pagesNumber" v-if="pagination.last_page > 1"
        	                v-bind:class="[ page == isActived ? 'active' : '']">
                            <router-link :to="getLink(page)">
                                {{ page }}
                            </router-link>
        	            </li>
        	            <li v-if="pagination.current_page < pagination.last_page">
                            <router-link :to="getLink(pagination.current_page + 1)" aria-label="Next">
                                <span aria-hidden="true">»</span>
                            </router-link>
        	            </li>
        	        </ul>
        	    </nav>
            </div>
        </div>
    </div>    
</template>

<script> 
    import { mapGetters } from 'vuex'
    import axios from 'axios'
    
    export default {
        metaInfo: {
            title: 'Teams',
            titleTemplate: null
        },
        computed: {
            ...mapGetters({
                user: 'authUser',
                authenticated: 'authCheck',
            }),
            isActived: function () {
                return this.pagination.current_page;
            },
            pagesNumber: function () {
                if (!this.pagination.to) {
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        mounted() {
            this.getVueItems();
            /*this.getGames();
        
            var self = this;
            Vue.nextTick(function(){ 
                
                $("#status_list").select2({
                    placeholder: "Select status",
                    allowClear: true
                }).on("select2:select", function(e) { 
                    self.status = $(e.currentTarget).find("option:selected").val();
                    self.search();
                }).on("select2:unselecting", function (e) {
                    self.status = '';
                    self.$router.push(self.$route.path+"?game_id="+self.game_id);
                });
                
                $("#game_list").select2({
                    placeholder: "Select game",
                    allowClear: true
                }).on("select2:select", function(e) { 
                    self.game_id = $(e.currentTarget).find("option:selected").val();
                    self.search();
                }).on("select2:unselecting", function (e) {
                    self.game_id = '';
                    self.$router.push(self.$route.path+"?status="+self.status);
                });

            });*/
        },
        data : function() {
            return {
                teams: [],
                games: [],
                queryString: '',
                pagination: {
                    total: 0, 
                    per_page: 2,
                    from: 1, 
                    to: 0,
                    current_page: 1,
                    next_page_url: null,
                    prev_page_url: null
                },
                offset: 12,
                statuses: [
                    {id:'', title: ''},
                    {id:0, title: 'pending'},
                    {id:1, title: 'accepted'}
                ],
                status: this.$route.query.status || '',
                game_id: this.$route.query.game_id || '',
            }
        },
        methods : {
            getVueItems: function(){
                
                var queryStartParams = {
                    'page' : 1,
                    '_limit' : 12,
                    '_with' : 'game,fights,usersAccepted',
                    "_sort" : '-id'
                };
                
                var query = this.UrlParamsMerge(queryStartParams);
                
                axios.get('/api/teams?'+query).then((response) => {
                    this.$set(this, 'teams', response.data.data);
                    
                    delete response.data.data;
                    this.pagination = response.data;
                });
            },
            /*getGames: function()
            {
                axios.get('/api/games?show_all=1').then((response) => {
                    this.$set(this, 'games', response.data);
                    this.$parent.games = this.games;
                });
            },*/
            getLink(page){
                let link = location.search;
                link = this.$route.path + this.updateUrlParameter(link, "page", page);
                return link;
            },
            search: function(event)
            {
                this.$router.push(this.$route.path+"?"+$("#filter-form").serialize());
            },
            joinTeam(team_id)
            {
                axios.put('/api/teams/'+team_id+'/users/'+this.user.id).then(response => {
                    console.log(response);
                });
            },
        },
        watch: {
            '$route.query'(newPage, oldPage) {
                this.getVueItems();
            }
        }
    }
</script>