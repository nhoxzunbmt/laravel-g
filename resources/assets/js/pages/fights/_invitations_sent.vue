<template>
    <div  v-if="user!==null && invitations.length>0">
        <h4 class="mb-10">Invitations to battles (sent)</h4>
                       
        <div class="table-wrap">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Team</th>
                            <th>Datetime</th>
                            <th>Bet</th>
                            <th>Battle status</th>
                            <th>Status</th>
                        </tr>
                    </thead>
				    <tbody>
                        <tr v-for="invitation in invitations">
                            
                            <td>
                                <router-link  :to="{ name: 'team.detail', params: { slug: invitation.team.slug }}" class="vm-title">
                                    <img :src="getImageLink(invitation.team.image, 'avatar_team')" class="img-responsive team-image" /> 
                                    <span>{{ invitation.team.title}}</span>
                                </router-link>
                            </td>
                            <td class="text-center">{{invitation.fight.start_at}}</td>
                            <td class="text-center">{{invitation.fight.bet}}</td>
                            <td class="text-center">
                                <span v-if="invitation.fight.status==0">
                                    waiting for invitations answers
                                </span>
                                <span v-if="invitation.fight.status==2">
                                    canceled, {{invitation.fight.cancel_text}}
                                </span>
                                <span v-if="invitation.fight.status==1">
                                    active
                                </span>                     
                            </td>
                            <td class="text-center">
                                <span v-if="invitation.status==0">
                                    waiting for answers
                                </span>
                                <span v-if="invitation.status==2">
                                    canceled
                                </span>
                                <span v-if="invitation.status==1">
                                    confirmed
                                </span> 
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

export default {
    data() {
        return {
            invitations: [],
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
            this.getInvitations(this.user.team_id);
        }
    },
    methods: 
    {
        getInvitations(team_id)
        {
            var query = this.ArrayToUrl({
                'page' : 1,
                '_limit' : 12,
                "_sort" : '-id',
                "_with" : 'fight,team,fight.createdByTeam'
            });
            
            axios.get('/api/teams/'+team_id + '/fights/invitations/out?'+query).then((response) => {
                
                var invitations = response.data.data;
                var _self = this;
                
                invitations.forEach(function(invitation)
                {
                    invitation.fight.start_at = _self.dateConvertUTC(invitation.fight.start_at, -1);
                });
                
                this.$set(this, 'invitations', invitations);
                
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