<template>
    <div>
        <div class="row">
            <div class="alert alert-danger" role="alert" v-if="error">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                {{ error }}
            </div>
			<div class="followers-wrap">
				<ul class="followers-list-wrap">
					<li class="follow-list">
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" v-for="user in users">
    						<div class="follo-body">
    							<div class="follo-data">
                                    <router-link :to="{ name: user.type, params: { id: user.id }}">
                                        <img :src="getImageLink(user.avatar)" class="user-img img-circle" :alt="user.title" />
                                    </router-link>
    								<div class="user-data mt-15">
    									<span class="name block capitalize-font">{{ user.nickname}}</span>
    									<span class="time block truncate txt-grey">{{ user.name}} {{ user.last_name}}</span>
    								</div>
    								<div class="clearfix"></div>
    							</div>
                            </div>
                        </div>
					</li>
				</ul>
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
    import swal from 'sweetalert2';
       
    export default {
        metaInfo: {
            title: 'All friends'
        },
        mounted() {
            this.getVueItems();
        },
        data : function() {
            return {
                loading: false,
                error: false,
                q: this.$route.query.q || '',
                users: [],
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
        methods : {
            getVueItems: function(){
                
                var queryStartParams = {
                    'page' : 1,
                    '_limit' : 12,
                    "_sort" : 'id'
                };
                
                var query = this.UrlParamsMerge(queryStartParams);
                
                axios.get('/api/users/'+this.user.id+'/friends/?'+query).then(response => {
    
                    if(response.data.error!==undefined)
                        this.error = response.data.error
                    
                    this.$set(this, 'users', response.data.data);
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
<style>
    .followers-list-wrap .follo-body .follo-data .user-img{
        height: 70px;
        width: 70px;
    }
</style>