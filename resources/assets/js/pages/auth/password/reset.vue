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
                                    <h6 class="panel-title txt-dark">Reset Password</h6>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div v-if="message" class="alert alert-success">
                                {{ message }}
                            </div>
                    		<div class="panel-wrapper collapse in">
                    			<div class="panel-body">
                                    <form autocomplete="off" v-on:submit="resetPassword">
                                        <div class="form-group" v-bind:class="{ 'has-error': error && response.email }">
                                            <label for="email">E-mail</label>
                                            <input type="email" id="email" class="form-control" v-model="email" required>
                                            <span class="help-block" v-if="error && response.email">{{ response.email }}</span>
                                        </div>
                                        <div class="form-group" v-bind:class="{ 'has-error': error && response.password }">
                                            <label for="password">Password</label>
                                            <input type="password" id="password" class="form-control" v-model="password" required>
                                            <span class="help-block" v-if="error && response.password">{{ response.password[0] }}</span>
                                        </div>
                                        <div class="form-group" v-bind:class="{ 'has-error': error && response.password }">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <input type="password" id="password_confirmation" class="form-control" v-model="password_confirmation" required>
                                            <span class="help-block" v-if="error && response.password_confirmation">{{ response.password_confirmation[0] }}</span>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Reset Password</button>
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

export default {
    metaInfo: { titleTemplate: 'Reset Password | %s' },
    data() {
        return {
            token: null,
            email: null,
            password: null,
            password_confirmation: null,
            success: false,
            error: false,
            response: null,
            message: null
        }
    },
    methods: {
        resetPassword(event) {
            event.preventDefault()
            
            this.token = this.$route.params.token
            axios.post(
                '/api/password/reset',
                {
                    token: this.token,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation
                }
            ).then(response => {
                this.success = true
                this.error = false
                this.message = response.data.message
            }).catch(error => {
                this.message = null
                this.response = error.response.data
                this.error = true
            });
        }
    }
}
</script>