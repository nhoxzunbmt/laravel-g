<template>
<div class="panel panel-default card-view pa-0 pt-25">
	<div class="panel-wrapper collapse in">
		<div  class="panel-body pb-0">                    
        	<div class="row">
        		<div class="col-lg-12">
        			<div class="">
                        <div class="panel-heading">
                            <div class="pull-left">
                                <h6 class="panel-title txt-dark">Profile</h6>
                            </div>
                            <div class="clearfix"></div>
                        </div>
        				<div class="panel-wrapper collapse in">
        					<div class="panel-body pa-0">
        						<div class="col-sm-12 col-xs-12">
        							<div class="form-wrap">
                                        <div class="alert alert-success" v-if="success">
                                            <p>Profile data is updated.</p>
                                        </div>
        								<form autocomplete="off" @submit="save">
        									<div class="form-body overflow-hide">
        										<div class="form-group">
        											<label class="control-label mb-10">Name</label>
        											<div class="input-group">
        												<div class="input-group-addon"><i class="icon-user"></i></div>
        												<input type="text" class="form-control" placeholder="name" v-model="user.name">
        											</div>
        										</div>
                                                <div class="form-group">
        											<label class="control-label mb-10">Last name</label>
        											<div class="input-group">
        												<div class="input-group-addon"><i class="icon-user"></i></div>
        												<input type="text" class="form-control" placeholder="last name" v-model="user.last_name">
        											</div>
        										</div>
        										<div class="form-group">
        											<label class="control-label mb-10">Email address</label>
        											<div class="input-group">
        												<div class="input-group-addon"><i class="icon-envelope-open"></i></div>
        												<input type="email" class="form-control" v-model="user.email">
        											</div>
        										</div>
        										<div class="form-group">
        											<label class="control-label mb-10">Contact number</label>
        											<div class="input-group">
        												<div class="input-group-addon"><i class="icon-phone"></i></div>
        												<input type="text" class="form-control" v-model="user.phone">
        											</div>
        										</div>
        										<div class="form-group">
        											<label class="control-label mb-10">Password</label>
        											<div class="input-group">
        												<div class="input-group-addon"><i class="icon-lock"></i></div>
        												<input type="password" class="form-control" value="password" v-model="user.password">
        											</div>
        										</div>
        									</div>
        									<div class="form-actions mt-10">			
        										<button type="submit" class="btn btn-success mr-10 mb-30">Update profile</button>
        									</div>				
        								</form>
        							</div>
        						</div>
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
export default {
    data() {
        return {
            user: {
                name: null,
                last_name: null,
                second_name: null,
                phone: null,
                email: null,
                password: null,
            },
            success: false,
            error: false,
            response: null
        }
    },
    mounted()
    {
        this.getUser();
    },
    methods: {
        getUser() {
            let token = localStorage.getItem('id_token')
            if (token !== null) 
            {
                this.$http.get(
                    'api/user?token=' + token
                ).then(response => {
                    this.$set(this, 'user', response.data.data);
                })
            }
        },
        save(event) {
            event.preventDefault()
            
            this.$http.post('/api/user', this.user).then((response) => {
                console.log(response.data);
            });
            //auth.login(this)
        },
    }
}
</script>