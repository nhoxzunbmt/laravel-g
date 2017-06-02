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
                    		<div class="panel-wrapper collapse in">
                    			<div class="panel-body">
                                    <div class="alert alert-danger" v-if="error">
                                        <p>There was an error, unable to sign in with those credentials.</p>
                                    </div>
                                    <form autocomplete="off" v-on:submit="signin">
                                        <div class="form-group">
                                            <label for="email">E-mail</label>
                                            <input type="email" id="email" class="form-control" placeholder="email" v-model="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" id="password" class="form-control" v-model="password" required>
                                        </div>
                                        <button type="submit" class="btn btn-default">Sign in</button>
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
import auth from '../auth.js';
import Form from 'vform';

export default {
    data() {
            return {
                email: null,
                password: null,
                error: false
            }
        },
        methods: {
            signin(event) {
                event.preventDefault()
                auth.signin(this, this.email, this.password)
            }
        }
}
</script>