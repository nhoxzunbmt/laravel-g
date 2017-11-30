<template>
<div class="table-struct full-width">
	<div class="table-cell vertical-align-middle auth-form-wrap">
		<div class="auth-form  ml-auto mr-auto no-float">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="panel panel-default card-view">
                            <div class="panel-heading">
                                <div class="pull-left">
                                    <h6 class="panel-title txt-dark">Login</h6>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <socials></socials>
                    		<div class="panel-wrapper collapse in">
                    			<div class="panel-body">
                                    <div class="alert alert-danger" v-if="error">
                                        <p>{{ response.error }}</p>
                                    </div>
                                    <div v-if="error" class="mb-20">
                                        <button class="btn btn-primary btn-sm mr-20" v-for="nick in nicknames" @click="setNickname(nick.nickname)">{{nick.nickname}}</button>
                                    </div>
                                    <form autocomplete="off" v-on:submit="login">
                                        <div class="form-group" v-bind:class="{ 'has-error': error && response.nickname }">
                                            <label for="nickname">Nickname / Email</label>
                                            <input type="nickname" id="nickname" class="form-control" v-model="nickname" required>
                                            <span class="help-block" v-if="error && response.nickname">{{ response.nickname[0] }}</span>
                                        </div>
                                        <div class="form-group" v-bind:class="{ 'has-error': error && response.password }">
                                            <label for="password">Password</label>
                                            <input type="password" id="password" class="form-control" v-model="password" required>
                                            <span class="help-block" v-if="error && response.password">{{ response.password[0] }}</span>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-6 col-sm-6">
                                                <button type="submit" class="btn btn-primary">Login</button>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 text-right mt-10">
                                                <small>
                                                    <router-link :to="{ name: 'password.request' }">Forgot Your Password?</router-link>
                                                </small>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div><!--card-view-->
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import axios from 'axios'
import Socials from '../../components/Socials.vue';

export default {
    name: 'login',
    metaInfo: { titleTemplate: 'Login | %s' },
    components: {Socials},
    data() {
        return {
            nickname: null,
            password: null,
            success: false,
            error: false,
            response: null,
            nicknames: []
        }
    },
    methods: {
        login (event) {
            event.preventDefault()
            axios.post('/api/login',
            {
                nickname: this.nickname,
                password: this.password
            }).then(response => {
                this.error = false
                
                this.$store.dispatch('saveToken', {
                    token: response.data.token,
                    remember: true
                })
            
                this.$store.dispatch('fetchUser').then(response => {
                    
                    if(response.type==null)
                    {
                        this.$router.push({ name: 'profile' })
                    }else{
                        this.$router.push({ name: 'home' })
                    }
                    
                })
                
                Vue.nextTick(function(){
                    setTimeout(function(){
                        $('.message-nicescroll-bar').slimscroll({height:'229px',size: '4px',color: '#878787',disableFadeOut : true,borderRadius:0});
                        $('.message-box-nicescroll-bar').slimscroll({height:'350px',size: '4px',color: '#878787',disableFadeOut : true,borderRadius:0});
                    }, 1000);
                });
            }).catch(error => {
                
                this.response = error.response.data;
                
                if(this.response.data!=undefined)
                {
                    this.nicknames = this.response.data;
                }
                
                this.error = true
                
            });
        },
        setNickname(nickname)
        {
            this.nickname = nickname;
            this.nicknames = [];
            this.error = false;
        }
    }
}
</script>