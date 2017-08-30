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
                                    <form autocomplete="off" v-on:submit="forgotPassword">
                                        <div class="form-group" v-bind:class="{ 'has-error': error && response.email }">
                                            <label for="email">E-mail</label>
                                            <input type="email" id="email" class="form-control" v-model="email" required>
                                            <span class="help-block" v-if="error && response.email">{{ response.email }}</span>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
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
            email: null,
            message: null,
            success: false,
            error: null,
            response: null
        }
    },
    methods: {
        forgotPassword(event) {
            event.preventDefault()
            axios.post(
                '/api/password/email',
                {
                    email: this.email
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