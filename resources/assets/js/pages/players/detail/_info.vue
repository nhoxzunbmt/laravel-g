<template>
<div>
    <div class="row" v-if="player!==null">
    
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="panel panel-default card-view">
                <div class="panel-wrapper collapse in">
                
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                
                        <h6>Invite friends:</h6>
                        <social-sharing :title="'Detail page of '+player.nickname+' on Sparta.games'" 
                            description = "Lead your team into eSports Pro League"
                        inline-template>
                        
                            <div class="button-list mb-15">
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
             </div>
        </div>
        
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="panel panel-default card-view">
                <div class="panel-wrapper collapse in">
                
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                
                            <h6>Send invitation by email:</h6>
        
                            <div class="input-group mb-15 mt-10">
                                <input type="text" :value="copyData" class="form-control" disabled>
                                <span class="input-group-btn">
                                    <button v-clipboard="copyData" class="btn btn-primary"><i class="fa fa-files-o" aria-hidden="true"></i></button>
                                </span>
                            </div>
                    
                        </div>
                    </div>
                </div>
             </div>
        </div>
        
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="panel panel-default card-view">
                <div class="panel-wrapper collapse in">
                
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                
                            <h6>Let's be a friends:</h6>
        
                            <div class="input-group mb-15 mt-10">
                                <button @click="beFriend" class="btn btn-primary"><i class="zmdi zmdi-account-add"></i> Send request</button>
                            </div>
                    
                        </div>
                    </div>
                </div>
             </div>
        </div>
    
    	<div class="col-lg-12 col-md-12 col-xs-12">
        
            <div class="panel panel-default card-view">
				<div class="panel-wrapper collapse in">
                    
                    <div class="row">
                        <div class="col-md-12 col-xs-12 mb-20" v-if="player.description!=null">
                            <p>{{player.description}}</p>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="panel-body">
        						<dl>
                                  <dt class="mb-10">Registered at</dt>
        						  <dd class="mb-10">{{ moment(player.created_at, "YYYY-MM-DD h:mm:ss").fromNow()}}</dd> 
                                  <dt class="mb-10">Name</dt>
        						  <dd class="mb-10">
                                    {{player.last_name}} {{player.name}} {{player.second_name}}
                                  </dd>
                                  <dt class="mb-10">Nickname</dt>
        						  <dd class="mb-10">
                                    {{player.nickname}}
                                  </dd>
        						</dl>
        					</div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="panel-body">
        						<dl>
                                  <dt class="mb-10">Streams</dt>
        						  <dd class="mb-10">
                                    <div v-for="stream in player.streams" v-if="player.streams!=null"><a :href="stream.value">{{stream.value}}</a></div>
                                    <span v-else>none</span>
                                  </dd>
        						</dl>
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
import swal from 'sweetalert2'
import VueClipboards from 'vue-clipboards'

export default {
    computed: {
        ...mapGetters({
            user: 'authUser',
            authenticated: 'authCheck',
        })     
    },
    ready() {
        Event.listen('playerDetailLoad', event => {
            this.player = event.team;
        });
    },
    data() {
        return {
            success: false,
            error: false,
            player: null,
            response: null,
            copyData: window.location.href//this.$route.fullPath
        }
    },
    mounted() {
        
        if(this.player===null)
        {
            Event.listen('playerDetailLoad', event => {
                this.player = event.player;
            });
            
            this.player = this.$parent.player;
        }
    
    },
    methods: {       
        beFriend: function()
        {
            if(!this.authenticated)
            {
                this.$router.push({ name: 'auth.login' });
                return false;
            }
                
                
            if(this.user.id==this.player.id)
            {
                swal({
                    type: 'error',
                    html: "You can't send request to yourself."
                })
                return false;
            }
            
            axios.post('/api/friends/befriend',
            {
                id: this.player.id,
            }).then(response => {
                
                if(response.data.error!==undefined)
                {
                    swal({
                        type: 'error',
                        html: response.data.error
                    })
                }else{
                    swal({
                        type: 'success',
                        html: 'We sent your request!'
                    })
                }
            });
        }
    },
}
</script>