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
						<h2 class="panel-title txt-dark">
                            {{team.title}}
                            <button class="btn btn-primary btn-icon left-icon btn-xs ml-10" v-if="authenticated && !checkInTeam(user.id)" @click="invite()"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Connect to the team</button>
                            <button class="btn btn-primary btn-icon left-icon btn-xs ml-10" v-else-if="authenticated"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Connected</button>
                       
                            <button class="btn btn-primary btn-xs ml-10" v-if="authenticated" @click="sendToInvestor()">&nbsp; Send to investor</button>
                        </h2> 
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="panel-wrapper collapse in">
                    
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="panel-body">
        						<dl>
                                  <dt class="mb-10">Created at</dt>
        						  <dd class="mb-10">{{ moment(team.created_at, "YYYY-MM-DD h:mm:ss").fromNow()}}</dd> 
        						  <dt class="mb-10">Status</dt>
        						  <dd class="mb-10">
                                    <span v-if="team.status==0">pending</span>
                                    <span v-if="team.status==1">accepted</span>
                                  </dd>
                                  <dt class="mb-10">Players in team</dt>
        						  <dd class="mb-10">{{ team.users.length}}</dd>
        						  <dt class="mb-10">Need players</dt>
        						  <dd>{{team.quantity}}</dd>
        						</dl>
        					</div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="panel-body">
        						<dl>
                                  <dt class="mb-10">Captain</dt>
        						  <dd class="mb-10"><span v-if="team.users.length>0">{{team.users[0].name}}</span><span v-else>-</span></dd>
                                  <dt class="mb-10">Game</dt>
        						  <dd class="mb-10">{{team.game.title}}</dd>
        						  <dt class="mb-10">Total fights</dt>
        						  <dd class="mb-10">{{team.count_fights}}</dd>
                                  <dt class="mb-10">Count wins</dt>
        						  <dd class="mb-10">{{ team.count_wins}}</dd>
        						</dl>
        					</div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="panel-body">
        						<dl>
                                  <dt class="mb-10">Victory rate</dt>
        						  <dd class="mb-10"><span v-if="team.count_fights>0">{{ Number((team.count_wins/team.count_fights).toFixed(2))}}</span><span v-else>0</span></dd>
                                  <dt class="mb-10">Earned</dt>
        						  <dd class="mb-10">{{team.total_sum}}</dd>
        						  <dt class="mb-10">Amount paid to investors</dt>
        						  <dd class="mb-10">{{team.payed_dividents}}</dd>
        						</dl>
        					</div>
                        </div>
                    </div>
				</div>
			</div>
        </div>
    </div>
    
    <div class="row" v-if="team!==null">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="panel panel-default card-view">
        		<div class="panel-heading">
        			<div class="pull-left">
        				<h3 class="panel-title txt-dark">
                            Fights
                        </h3>
        			</div>
        			<div class="clearfix"></div>
        		</div>
        		<div class="panel-wrapper collapse in">
                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Created at</th>
                                        <th>Logo</th>
                                        <th>Name</th>
                                        <th>Players in team</th>
                                        <th>Need players</th>
                                        <th>Game</th>
                                        <th>Total fights</th>
                                    </tr>
                                </thead>
							    <tbody>
                                </tbody>
                            </table>
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
import swal from 'sweetalert2'

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
        sendToInvestor()
        {
            var team_id = this.team.id;
            swal({
                title: 'Enter investor\'s email to send team link to.',
                input: 'email',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: function (email) 
                {
                    return new Promise(function (resolve, reject) {
                        setTimeout(function() {
                            
                            axios.post('/api/teams/'+team_id+'/toInvestor', {email: email}).then(response => {
                                resolve()
                            });
                            
                        }, 2000)
                    })
                },
                allowOutsideClick: false
            }).then(function (email) {
                
                swal({
                    type: 'success',
                    title: 'Link sent!',
                    html: 'Team\'s link sent to email: ' + email
                })
                
            })
        }
    },
}
</script>