<template>
    <div>
        <div class="row">
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
                                                <th>Players in team</th>
                                                <th>Need players</th>
                                                <th>Game</th>
                                                <th>Balance, <i aria-hidden="true" class="fa fa-btc"></i></th>
                                                <th class="text-nowrap">Action</th>
                                            </tr>
                                        </thead>
    								    <tbody>
                                            <tr v-for="team in teams">
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
                                                <td class="text-center">{{ team.users.length}}</td>
                                                <td class="text-center">{{ team.quantity}}</td>
                                                <td class="text-center">{{ team.game.title}}</td>
                                                <td class="text-center">{{ team.balance}}</td>
                                                <td class="text-nowrap text-center">
                                                    <router-link  :to="{ name: 'teams.edit', params: { id: team.id }}" class="mr-25" v-if="team.capt_id==user.id">
                                                        <i class="fa fa-pencil text-inverse m-r-10"></i>
                                                    </router-link> 
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
            title: 'Personal teams',
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
                teams: [],
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
            }
        },
        methods : {
            getVueItems: function(){
                axios.get('/api/user/'+this.user.id+'/teams'+location.search).then((response) => {
                    this.$set(this, 'teams', response.data.data);
                    
                    delete response.data.data;
                    this.pagination = response.data;
                });
            },
            getLink(page){
                let link = location.search;
                link = this.$route.path + this.updateUrlParameter(link, "page", page);
                return link;
            }
        },
        watch: {
            '$route.query'(newPage, oldPage) {
                this.getVueItems();
            }
        }
    }
</script>