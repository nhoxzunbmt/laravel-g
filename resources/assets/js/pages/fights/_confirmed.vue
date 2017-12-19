<template>
    <div  v-if="user!==null && fights.length>0">
        <h4 class="mb-10">Matches</h4>
                       
        <div class="table-wrap">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead>
                        <tr>
                            <!--<th>Title</th>-->
                            <th>ID</th>
                            <th>Teams</th>
                            <th>Datetime</th>
                            <th>Bet</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
				    <tbody>
                        <tr v-for="fight in fights">
                            <!--<td>
                                {{fight.title}}
                            </td>-->
                            <td>
                                <router-link  :to="{ name: 'fight', params: { id: fight.id }}">        
                                    {{fight.id}}
                                </router-link>
                            </td>
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
                            <td class="text-center">
                                <a href="javascript:void(0)" @click="cancelFight(fight.id)" title="reject" v-if="user.id==fight.created_id && fight.status==1"><i class="fa fa-times text-danger"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
import swal from 'sweetalert2'

export default {
    data() {
        return {
            fights: [],
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
    mounted() {
        if(this.user.team_id!=null)
        {
            this.getFights(this.user.team_id);
        }
    },
    methods: 
    {
        getFights(team_id)
        {
            var query = this.ArrayToUrl({
                //'status' : 1,
                'page' : 1,
                '_limit' : 12,
                "_sort" : '-start_at',
                "_with" : 'invitations.team'
            });
            
            axios.get('/api/teams/'+team_id + '/fights?'+query).then((response) => {
                var fights = response.data.data;
                var _self = this;
                
                fights.forEach(function(fight)
                {
                    fight.start_at = _self.dateConvertUTC(fight.start_at, -1);
                });
                
                this.$set(this, 'fights', fights);
                
                delete response.data.data;
                this.pagination = response.data;
            });
        },
        getLink(page){
            let link = location.search;
            link = this.$route.path + this.updateUrlParameter(link, "page", page);
            return link;
        },
        cancelFight(id)
        {
            swal({
                title: 'Are you sure you want to cancel the battle?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then(function () {
              
                axios.put('/api/fight/'+id, {
                    status: 2
                }).then((response) => {
    
                    swal({
                        type: 'success',
                        html: response.data.message
                    });
                    
                });
            });            
        }
    },
    watch: {
        '$route.query'(newPage, oldPage) {
            this.getVueItems();
        }
    }
}
</script>