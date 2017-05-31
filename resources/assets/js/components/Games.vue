<template>
    <div>
        <div class="row heading-bg">
        	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h5 class="txt-dark">Games</h5>
        	</div>
        </div>
        
        <div class="row">
        	<div class="col-md-12">
        		<div class="panel panel-default card-view">
        			<div class="panel-wrapper collapse in">
        				<div class="panel-body">
                            <div class="form-wrap">
                                <form autocomplete="off" v-on:submit="filterByGenre">
                                    <div class="row">
        								<div class="col-md-3">
        									<div class="form-group">
        										<label class="control-label mb-10">Select genre</label>
                                                <select name="genre_id" class='' data-style="form-control btn-default btn-outline">
                                                    <option v-for="genre in genres" v-bind:value="genre.id">
                                                        {{ genre.title }}
                                                    </option>
                                                </select>
        									</div>	
        								</div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label mb-10">&nbsp;</label><br />
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
        					</div>
        				</div>
        			</div>
        		</div>
        	</div>
        </div>
        
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6" v-for="game in games">
    			<div class="panel panel-default card-view pa-0">
    				<div class="panel-wrapper collapse in">
    					<div class="panel-body pa-0">
    						<article class="col-item">
    							<div class="photo">
                                    <router-link :to="{ name: 'game', params: { gameId: game.id }}">
                                        <img :src="'/storage/' + game.logo" class="img-responsive" :alt="game.title" />
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
        	                <a href="#" aria-label="Previous"
        	                   @click.prevent="changePage(pagination.current_page - 1)">
        	                    <span aria-hidden="true">«</span>
        	                </a>
        	            </li>
        	            <li v-for="page in pagesNumber"
        	                v-bind:class="[ page == isActived ? 'active' : '']">
        	                <a href="#"
        	                   @click.prevent="changePage(page)">{{ page }}</a>
        	            </li>
        	            <li v-if="pagination.current_page < pagination.last_page">
        	                <a href="#" aria-label="Next"
        	                   @click.prevent="changePage(pagination.current_page + 1)">
        	                    <span aria-hidden="true">»</span>
        	                </a>
        	            </li>
        	        </ul>
        	    </nav>
            </div>
        </div>
    </div>    
</template>

<script>
    export default {
        mounted() {
            this.getVueItems(this.pagination.current_page);
        },
        data : function() {
            return {
                games: [],
                genres: [],
                pagination: {
                    total: 0, 
                    per_page: 2,
                    from: 1, 
                    to: 0,
                    current_page: 1
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
        ready : function(){
        	this.getVueItems(this.pagination.current_page);
        },
        methods : {
            getVueItems: function(page){
                this.$http.get('/api/games?page='+page).then((response) => {
                    this.$set(this, 'games', response.data.data.data);
                    this.$set(this, 'pagination', response.data.pagination);
                    this.$set(this, 'genres', response.data.genres);
                });
            },
            changePage: function (page) {
                this.pagination.current_page = page;
                this.getVueItems(page);
            },
            filterByGenre: function()
            {
                
            }
        }
    }
</script>