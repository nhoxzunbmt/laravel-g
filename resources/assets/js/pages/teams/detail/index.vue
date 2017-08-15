<template>
<div data-sticky-container>
    <div class="row mt-20">
        <div class="col-lg-12 col-md-12 col-xs-12">
    		<div class="panel panel-default card-view  pa-0">
    			<div class="panel-wrapper collapse in">
    				<div class="panel-body  pa-0">
    					<div class="profile-box">
    						<div class="profile-cover-pic" v-if="team!==null">
    							<div class="profile-image-overlay" v-if="team.overlay!==null" v-bind:style="{ 'background-image': 'url(' + getImageLink(team.overlay) + ')' }"></div>
                                <div class="profile-image-overlay" v-else></div>
    						</div>
    						<div class="profile-info text-center stickyNav">
                            
                                <div class="tab-struct custom-tab-1 mt-5 mb-10 weight-600">
        							<ul class="nav nav-tabs">
                                        <li v-for="tab in tabs" class="nav-item">
                                            <router-link :to="{ name: tab.route }" class="nav-link" active-class="active">
                                                {{ tab.name }}
                                            </router-link>
                                        </li>
        							</ul>
        						</div>
                            
    							<div class="profile-img-wrap" v-if="team!==null">
    								<img class="inline-block" :src="getImageLink(team.image)" alt="logo"/>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    
    <div class="tab-content">
        <div class="tab-pane fade active in">
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </div>
    </div>
</div>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'
var Sticky = require('sticky-js');

export default {
    metaInfo () {
        return {
            title: this.title,
        }
    },
    computed: {
        ...mapGetters({
            user: 'authUser',
            authenticated: 'authCheck',
        })      
    },
    data() {
        return {
            title: 'Detail page of team',
            tabs: [
                {
                    name: 'Info',
                    route: 'team.detail'
                },
                {
                    name: 'Players',
                    route: 'team.detail.players'
                }
            ],
            team: null,
            stickyOptions: {
                marginTop: 20,
                forName: 0,
                className: 'stuck'
            },
            response: null
        }
    },
    mounted() {
        //var sticky = new Sticky('.stickyNav');        
        this.getTeam();
    },
    methods: {
        getTeam()
        {
            axios.get('/api/teams/'+this.$route.params.slug).then((response) => {
                this.$set(this, 'team', response.data);
                
                this.title = "Team: "+this.team.title;
                this.$meta().refresh();
                
                Event.fire('teamEditLoad', {
                    team: response.data
                });
            });
        },
    },
}
</script>