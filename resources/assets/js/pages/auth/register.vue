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
                                    <h6 class="panel-title txt-dark">Register</h6>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <socials></socials>
                    		<div class="panel-wrapper collapse in">
                    			<div class="panel-body">
                                    <div class="alert alert-danger" v-if="error && !success">
                                        <p>There was an error, unable to complete registration.</p>
                                    </div>
                                    <div class="alert alert-success" v-if="success">
                                        <p>Registration completed. You can now sign in.</p>
                                    </div>
                                    <form autocomplete="off" v-on:submit="register" v-if="!success">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" v-model="name" required>
                                        </div>
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
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
    components: { Socials },
    metaInfo: {
        title: 'Register Page',
        titleTemplate: null
    },
    data() {
        return {
            name: null,
            email: null,
            password: null,
            success: false,
            error: false,
            response: null
        }
    },
    methods: {
        register (event) {
            event.preventDefault()
            axios.post(
                '/api/register',
                {
                    name: this.name,
                    email: this.email,
                    password: this.password
                }
            ).then(response => {
                this.success = true
                
            }).catch(error => {
                
                this.response = error.response.data
                this.error = true

            });
        }
    }
}
</script>