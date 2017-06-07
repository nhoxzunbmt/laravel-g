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
                                    <form autocomplete="off" v-on:submit="login">
                                        <div class="form-group" v-bind:class="{ 'has-error': error && response.email }">
                                            <label for="email">E-mail</label>
                                            <input type="email" id="email" class="form-control" v-model="email" required>
                                            <span class="help-block" v-if="error && response.email">{{ response.email[0] }}</span>
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
import Socials from './Shared/Socials.vue';
import auth from '../auth.js';

export default {
    components: { Socials },
    data() {
            return {
                email: null,
                password: null,
                success: false,
                error: false,
                response: null
            }
        },
        methods: {
            login(event) {
                event.preventDefault()
                auth.login(this)
            }
        }
}
</script>