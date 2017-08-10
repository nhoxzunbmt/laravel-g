<template>
    <div>
        <div class="row heading-bg">
        	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h5 class="txt-dark">Games</h5>
        	</div>
        </div>
    
        <!--
        <div class="row">
        	<div class="col-md-12">
        		<div class="panel panel-default card-view">
        			<div class="panel-wrapper collapse in">
        				<div class="panel-body">
                            <div class="form-wrap">
                                <form autocomplete="off" id="genre-form">
                                    <div class="row">
        								<div class="col-md-3">
                                                <select v-model="genre_id" name="genre_id" class='form-control' data-style="form-control btn-default btn-outline" id="genre_list">
                                                    <option v-for="genre in genres" v-bind:value="genre.id">
                                                        {{ genre.title }}
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
        
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6" v-for="game in games">
    			<div class="panel panel-default card-view pa-0">
    				<div class="panel-wrapper collapse in">
    					<div class="panel-body pa-0">
    						<article class="col-item">
    							<div class="photo">
                                    <router-link :to="{ name: 'game', params: { gameId: game.id }}">
                                        <img :src="getImageLink(game.logo)" class="img-responsive" :alt="game.title" />
                                    </router-link>
    							</div>
    							<div class="info">
    								<h6>{{ game.title | truncate(20, '...') }}</h6>
    							</div>
    						</article>
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
    import axios from 'axios'
       
    export default {
        metaInfo: {
            title: 'Games',
            titleTemplate: null
        },
        mounted() {
            this.getGenres();
            this.getVueItems();
            
            //Select2 for genres
            var self = this;
            Vue.nextTick(function(){
                $("#genre_list").select2({
                    placeholder: "Select genre",
                    allowClear: true
                }).on("select2:select", function(e) { 
                    self.search();
                });
            
                $("#genre_list").on("select2:unselecting", function (e) {
                    self.$router.push(self.$route.path);
                });
            });
        },
        data : function() {
            return {
                games: [],
                genres: [],
                genre_id: this.$route.query.genre_id || '',
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
        computed: {
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
        methods : {
            getVueItems: function(){
                axios.get('/api/games'+location.search).then((response) => {
                    this.$set(this, 'games', response.data.data);
                    
                    delete response.data.data;
                    this.pagination = response.data;
                });
            },
            getGenres: function()
            {
                if(this.$parent.genres==undefined || this.$parent.genres.length==0)
                {
                    axios.get('/api/genres').then((response) => {
                        this.$set(this, 'genres', response.data);
                        
                        //put genres in app.genres
                        this.$parent.genres = this.genres;
                    });
                }else{
                    this.genres = this.$parent.genres;
                }  
            },
            getLink(page){
                let link = location.search;
                link = this.$route.path + this.updateUrlParameter(link, "page", page);
                return link;
            },
            search: function(event)
            {
                this.$router.push(this.$route.path+"?"+$("#genre-form").serialize());
            }
        },
        watch: {
            '$route.query'(newPage, oldPage) {
                this.getVueItems();
            }
        }
    }
</script>