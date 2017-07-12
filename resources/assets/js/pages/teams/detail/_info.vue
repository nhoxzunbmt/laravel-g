<template>
<div>
    <div class="row" v-if="team!==null">
    	<div class="col-lg-12 col-md-12 col-xs-12">
            <div class="panel panel-default card-view pa-0">
                <h6 class="ml-10 mt-10">Share with friends:</h6>
                <social-sharing :title="team.title" inline-template>
                <div class="button-list mb-15 ml-5">
                    <network network="facebook">
                        <button class="btn btn-facebook btn-icon-anim btn-square"><i class="fa fa-facebook"></i></button>
                    </network>
                    <network network="twitter">
                        <button class="btn btn-twitter btn-icon-anim btn-square"><i class="fa fa-twitter"></i></button>
                    </network>
                    <network network="googleplus">
                        <button class="btn btn-googleplus btn-icon-anim btn-square"><i class="fa fa-google-plus"></i></button>
                    </network>
                    <network network="pinterest">
                        <button class="btn btn-pinterest btn-icon-anim btn-square"><i class="fa fa-pinterest"></i></button>
                    </network>
                    <network network="vk">
                        <button class="btn btn-instagram btn-icon-anim btn-square"><i class="fa fa-vk"></i></button>
                    </network>
                </div>
                </social-sharing>
             </div>
        </div>
     </div>      
     <div class="row" v-if="team!==null">
    	<div class="col-lg-12 col-md-12 col-xs-12">
        
            <div class="panel panel-default card-view">
				<div class="panel-heading">
					<div class="pull-left">
						<h3 class="panel-title txt-dark">
                            {{team.title}}
                            <button class="btn btn-primary btn-icon left-icon btn-xs ml-10" v-if="authenticated && !checkInTeam(user.id)" @click="invite()"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Connect to the team</button>
                            <button class="btn btn-primary btn-icon left-icon btn-xs ml-10" v-else-if="authenticated"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Connected</button>
                        </h3> 
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="panel-wrapper collapse in">
					<div class="panel-body">
						<dl>
                          <dt class="mb-10">Captain</dt>
						  <dd class="mb-10">{{team.users[0].name}}</dd>  
						  <dt class="mb-10">Status</dt>
						  <dd class="mb-10">{{team.status}}</dd>
                          <dt class="mb-10">Game</dt>
						  <dd class="mb-10">{{team.game.title}}</dd>
						  <dt class="mb-10">Need players</dt>
						  <dd>{{team.quantity}}</dd>
						</dl>
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
    computed: {
        ...mapGetters({
            user: 'authUser',
            authenticated: 'authCheck',
        }),
        slug: function() {
            var slug = this.slugTitle(this.team.title);
            this.team.slug = slug;
            return slug;
        },
        usersInTeam: function () {
            
            var usersInTeam = [];
            
            if(this.team.users.length)
            {
                this.team.users.forEach(function (user) {
                    usersInTeam.push(user.pivot.user_id);
                });
            }
            
            return usersInTeam;
        }      
    },
    ready() {
        Event.listen('teamEditLoad', event => {
            this.team = event.team;
        });
    },
    data() {
        return {
            success: false,
            error: false,
            team: null,
            games: [],
            response: null
        }
    },
    mounted() {
        Event.listen('teamEditLoad', event => {
            this.team = event.team;
        });
        
        this.team = this.$parent.team;
    },
    methods: {       
        checkInTeam: function (value) {
            return this.usersInTeam.indexOf(value) > -1 ? true : false;
        },
        invite()
        {
            axios.put('/api/teams/'+this.team.id+'/users/'+this.user.id).then(response => {
                console.log(response);
            });
        },
    },
}
</script>