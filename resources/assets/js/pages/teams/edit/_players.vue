<template>
    <div>
        <div class="row" v-if="team!==null">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    			<div class="panel panel-default card-view">
    				<div class="panel-wrapper collapse in">
    					<div class="panel-body">
        
                            <div class="table-wrap" v-if="team.users!==null">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>avatar</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Captain</th>
                                                <th>Status</th>
                                                <th class="text-nowrap">Action</th>
                                            </tr>
                                        </thead>
    								    <tbody>
                                            <tr v-for="user in team.users">
                                                <td>
                                                    <router-link  :to="{ name: 'player', params: { id: user.pivot.user_id }}">
                                                        <img :src="getImageLink(user.avatar)" class="img-responsive team-image" />
                                                    </router-link>
                                                </td>
                                                <td>
                                                    <router-link  :to="{ name: 'player', params: { id: user.pivot.user_id }}">
                                                        {{ user.name}}
                                                    </router-link>
                                                </td>
                                                <td class="text-center">{{ user.email}}</td>
                                                <td class="text-center"><i class="fa fa-check text-danger" v-if="user.pivot.user_id==team.capt_id"></i></td>
                                                <td class="text-center"></td>
                                                <td class="text-nowrap text-center">
                                                   
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
    </div>    
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    metaInfo () {
        return {
            title: 'Team',
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
            success: false,
            error: false,
            team: null,
            response: null
        }
    },
    mounted() {
        Event.listen('teamEditLoad', event => {
            this.team = event.team;
        });
        
        this.team = this.$parent.team;
    },
    methods: {},
}
</script>