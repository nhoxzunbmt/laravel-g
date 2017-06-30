<template>
    <div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="input-group mb-15">
                	<input type="text" v-model="q" name="q" @keyup.enter="search()" class="form-control" placeholder="Search by friend's name">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-primary" v-if="!loading" @click="search()"><i class="fa fa-search"></i></button>
                        <button class="btn btn-default" type="button" disabled="disabled" v-if="loading">Searching...</button>
                	</span>
                </div>
                <div class="alert alert-danger" role="alert" v-if="error">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    {{ error }}
                </div>
            </div>
        </div>
        
        <div class="row friend-list">
            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6" v-for="user in users">
    			<div class="panel panel-default card-view pa-0 ml-0 mr-0">
    				<div class="panel-wrapper collapse in">
    					<div class="panel-body pa-0">
    						<article class="col-item">
    							<div class="photo">
                                    <router-link :to="{ name: user.type, params: { id: user.id }}">
                                        <img :src="getImageLink(user.avatar)" class="img-responsive" :alt="user.title" />
                                    </router-link>
    							</div>
    							<div class="info">
                                    <router-link :to="{ name: user.type, params: { id: user.id }}">
    								<h6>{{ user.nickname}}</h6>
                                    </router-link>
                                    <p>{{ user.name}} {{ user.last_name}}</p>
                                    
                                    <div class="options">
										<a href="javascript:void(0);" @click="befriend(user)" class="font-20 mr-10 pull-left" v-show="!user.activated">
                                            <i class="zmdi zmdi-account-add"></i>
                                        </a>
                                        <span v-show="user.activated" class="font-20 mr-10 pull-left">
                                            <i class="zmdi zmdi-check"></i>
                                        </span>
									</div>
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
    import swal from 'sweetalert2';
       
    export default {
        metaInfo: {
            title: 'Search users',
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
            },
        },
        methods : {       
            getVueItems: function(){
                
                if(this.q.length<2)
                    return false;
                
                this.error = false;
                this.loading = true;
                
                axios.get('/api/friends/searchPotential'+location.search).then(response => {
                    
                    if(response.data.error!==undefined)
                        this.error = response.data.error
                    
                    this.$set(this, 'users', response.data.data);
                    this.loading = false;
                    delete response.data.data;
                    this.pagination = response.data;
                });
            },
            befriend(recipient)
            {
                axios.post('/api/friends/befriend',
                {
                    id: recipient.id,
                }).then(response => {
                    
                    if(response.data.error!==undefined)
                    {
                        swal({
                            type: 'error',
                            html: response.data.error
                        })
                    }else{
                        var index = this.users.indexOf(recipient);
                        this.$set(this.users[index], 'activated', true)
                    }
                });
            },
            getLink(page){
                let link = location.search;
                link = this.$route.path + this.updateUrlParameter(link, "page", page);
                return link;
            },
            search: function(event)
            {
                this.$router.push(this.$route.path+"?q="+this.q);
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
    .friend-list .options {
        position: absolute;
        top: 12px;
        right: 5px;
    }
    .friend-list .options a:hover{
        color: #177ec1;
    }
    .friend-list .col-item .info{
        position: relative;
    }
</style>