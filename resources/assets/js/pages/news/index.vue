<template>
    <div>
        <div class="row heading-bg">
        	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h5 class="txt-dark">News</h5>
        	</div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6" v-for="post in news">
    			<div class="panel panel-default card-view pa-0">
    				<div class="panel-wrapper collapse in">
    					<div class="panel-body pa-0">
    						<article class="col-item">
    							<div class="photo">
                                    <router-link :to="{ name: 'news.detail', params: { slug: post.slug }}">
                                        <img :src="getImageLink(post.image)" class="img-responsive" :alt="post.title" />
                                    </router-link>
    							</div>
    							<div class="info">
    								<h6>{{ post.title }}</h6>
                                    <p>{{ post.excerpt | truncate(100, '...') }}</p>
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
            title: 'News',
            titleTemplate: null
        },
        mounted() {
            this.getVueItems();
        },
        data : function() {
            return {
                news: [],
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
                
                var queryStartParams = {
                    'page' : 1,
                    '_limit' : 12,
                    "_sort" : 'id'
                };
                
                var query = this.UrlParamsMerge(queryStartParams);
                
                axios.get('/api/news?'+query).then((response) => {
                    this.$set(this, 'news', response.data.data);
                    
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