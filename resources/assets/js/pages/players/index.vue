<template>
    <div>
    
        <div class="row heading-bg">
        	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h5 class="txt-dark">Players</h5>
        	</div>
        </div>
    
        <div class="row" v-if="players!==null">
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
                                                <th>Country</th>
                                                <th>Teams</th>
                                                <th>Total fights</th>
                                                <th>Team wins</th>
                                                <th>Personal wins</th>
                                                <th>Victory rate</th>
                                            </tr>
                                        </thead>
    								    <tbody>
                                            <tr v-for="player in players">
                                                <td>
                                                    <router-link  :to="{ name: 'player', params: { id: player.id }}">
                                                        <img :src="getImageLink(player.avatar)" class="img-responsive team-image" :alt="player.name" />
                                                    </router-link>
                                                </td>
                                                <td>
                                                    <router-link  :to="{ name: 'player', params: { id: player.id }}">
                                                        [{{ player.nickname}}] {{ player.name}} {{ player.last_name}}
                                                    </router-link>
                                                </td>
                                                <td class="text-center"><span v-if="player.country_id>0 && country!==null"><img :src="'/images/flags/'+player.country.flag" :title="player.country.full_name" /></span><span v-else> - </span></td>
                                                <td class="text-center">{{player.teams.length}}</td>
                                                <td class="text-center"><span v-if="player.fights.length>0">{{player.fights.length}}</span><span v-else>0</span></td>
                                                <td class="text-center"><span v-if="player.team_wins>0">{{player.team_wins}}</span><span v-else>0</span></td>
                                                <td class="text-center"><span v-if="player.person_wins>0">{{player.person_wins}}</span><span v-else>0</span></td>
                                                <td class="text-center" v-if="player.fights.length>0">{{ Number(((player.team_wins+player.person_wins)/player.fights.length).toFixed(2))}}</td>
                                                <td class="text-center" v-else>0</td>
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
            title: 'Players',
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
        },
        data : function() {
            return {
                players: [],
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
                offset: 12
            }
        },
        methods : {
            getVueItems: function(){
                
                var adding = location.search;
                if(adding)
                {
                    adding+="&type=player";
                }else{
                    adding="?type=player";
                }
                axios.get('/api/user/all'+adding).then((response) => {
                    this.$set(this, 'players', response.data.data);
                    
                    delete response.data.data;
                    this.pagination = response.data;
                });
            },
            getLink(page){
                let link = location.search;
                link = this.$route.path + this.updateUrlParameter(link, "page", page);
                return link;
            },
        },
        watch: {
            '$route.query'(newPage, oldPage) {
                this.getVueItems();
            }
        }
    }
</script>