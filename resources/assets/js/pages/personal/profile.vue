<template>
<div>
<div class="row heading-bg">
	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h5 class="txt-dark">Profile</h5>
	</div>
</div>
<div class="row">
	<div class="col-lg-3 col-md-3 col-xs-12">
		<div class="panel panel-default card-view  pa-0">
			<div class="panel-wrapper collapse in">
				<div class="panel-body  pa-0">
					<div class="profile-box">
						<div class="profile-cover-pic">
                            <vue-core-image-upload
                                crop-ratio="1:1"
                                class="fileupload btn btn-default"
                                :crop="false"
                                :headers="header"
                                text="edit"
                                @imageuploaded="overlayuploaded"
                                :maxWidth="1000"
                                url="/api/user/overlay">
                            </vue-core-image-upload>
							<div class="profile-image-overlay" v-if="user.overlay!==null" v-bind:style="{ 'background-image': 'url(' + user.overlay + ')' }"></div>
                            <div class="profile-image-overlay" v-else></div>
						</div>
						<div class="profile-info text-center">
							<div class="profile-img-wrap">
								<img class="inline-block mb-10" :src="user.avatar" alt="user"/>
                                <vue-core-image-upload
                                    crop-ratio="1:1"
                                    class="fileupload btn btn-primary"
                                    :crop="true"
                                    :headers="header"
                                    text="edit"
                                    @imageuploaded="imageuploaded"
                                    :maxWidth="150"
                                    url="/api/user/avatar">
                                </vue-core-image-upload>
							</div>
							<h5 class="block mt-10 mb-25 weight-500 capitalize-font txt-danger">{{ user.nickname }}</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-9 col-md-9 col-xs-12">
        <div class="panel panel-default card-view">
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
                    <div class="row">
    					<div class="col-sm-12 col-xs-12">
    						<div class="form-wrap">
                                <div class="alert alert-warning alert-dismissable" v-if="user.type==null">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<i class="zmdi zmdi-alert-circle-o pr-15 pull-left"></i><p class="pull-left">You should set person's type. </p>
									<div class="clearfix"></div>
								</div>
                                <div class="alert alert-success alert-dismissable" v-if="success">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <p>Profile data is updated.</p>
                                </div>
    							<form autocomplete="off" @submit="save">
                                    <h6 class="txt-dark capitalize-font"><i class="zmdi zmdi-account mr-10"></i>Person's Info</h6>
                                    <hr class="light-grey-hr">
                                    <div class="row">
    									<div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label mb-10">Type*</label>
                                                <div class="radio-list">
                                                    <div class="radio-inline pl-0" v-for="(value, key) in types">
                                                        <span class="radio radio-info">
                                                        	<input type="radio" :value="key" v-model="user.type" :id="'radio_'+key">
                                                        	<label :for="'radio_'+key">{{ value }}</label>
                                                        </span>
                                                    </div>
												</div>
                                            </div>
    									</div>
                                    </div>                                                
                                    <div class="row">
                                        <div class="col-md-6" :class="{ 'has-error': error && response.nickname }">
                                            <div class="form-group">
    											<label class="control-label mb-10">Nickname*</label>
    								             <input type="text" class="form-control" placeholder="nickname" v-model="user.nickname">
                                                <span class="help-block" v-if="error && response.nickname">{{ response.nickname[0] }}</span>
    										</div>
                                        </div>
    									<div class="col-md-6">
    										<div class="form-group">
                                                <label class="control-label mb-10">Name</label>
                                                <input type="text" class="form-control" placeholder="name" v-model="user.name">
    										</div>
    									</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label mb-10">Surnname</label>
    											<input type="text" class="form-control" placeholder="Surnname" v-model="user.last_name">
    										</div>
    									</div>
                                        <div class="col-md-6">
    										<div class="form-group">
                                                <label class="control-label mb-10">Middle name</label>
    											<input type="text" class="form-control" placeholder="Middle name" v-model="user.second_name">
    										</div>
    									</div>
                                    </div>
                                    <div class="row">
    									<div class="col-md-6" :class="{ 'has-error': error && response.email }">
    										<div class="form-group">
                                                <label class="control-label mb-10">Email address*</label>
    											<input type="email" class="form-control" v-model="user.email">
                                                <span class="help-block" v-if="error && response.email">{{ response.email[0] }}</span>
                                            </div>
                                        </div>
    									<div class="col-md-6" :class="{ 'has-error': error && response.phone }">
    										<div class="form-group">
                                                <label class="control-label mb-10">Contact number</label>
    											<input type="text" class="form-control" v-model="user.phone">
                                                <span class="help-block" v-if="error && response.phone">{{ response.phone[0] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
    										<div class="form-group">
                                                <label class="control-label mb-10">Country</label>
    											<select v-model="user.country_id" class='form-control' data-style="form-control btn-default btn-outline" id="country_list">
                                                    <option v-for="country in countries" v-bind:value="country.id" v-bind:data-image="country.flag">
                                                        {{ country.name }}
                                                    </option>
                                                </select>
                                            </div>
    									</div>
                                    </div>
                                    <div class="row">
    									<div class="col-md-12">
                                            <div class="form-group">
    											<label class="control-label mb-10 text-left">Description</label>
    											<textarea class="form-control" rows="5" placeholder="Write about yourself, links to games accounts, etc." v-model="user.description"></textarea>
    										</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6" v-if="user.type=='commentator'">
    										<div class="form-group">
                                                <label class="control-label mb-10">Minimal sponsor fee</label>
    											<input type="text" class="form-control" placeholder="0" v-model="user.min_sponsor_fee">
                                            </div>
    									</div>
                                    </div>
                                    <h6 class="txt-dark capitalize-font mt-30"><i class="zmdi zmdi-lock mr-10"></i>Password change</h6>
                                    <hr class="light-grey-hr">
                                    <div class="row">
    									<div class="col-md-6" :class="{ 'has-error': error && response.password }">
    										<div class="form-group">
                                                <label class="control-label mb-10">Password</label>
    											<input type="password" class="form-control" value="" v-model="user.password">
                                                <span class="help-block" v-if="error && response.password">{{ response.password[0] }}</span>
                                            </div>
    									</div>
                                        <div class="col-md-6">
    										<div class="form-group">
                                                <label class="control-label mb-10">Confirm Password</label>
    											<input type="password" class="form-control" value="" v-model="user.password_confirmation">
                                            </div>
    									</div>
                                    </div>
    								<div class="form-actions mt-10">
                                        <button type="submit" class="btn btn-primary btn-icon left-icon mr-10">
                                            <i class="zmdi zmdi-edit"></i> <span>Update profile</span>
                                        </button>			
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
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'
import Cookies from 'js-cookie'
import VueCoreImageUpload from 'vue-core-image-upload'

export default {
    metaInfo: {
        title: 'Personal settings'
    },
    computed: mapGetters({
        user: 'authUser',
        authenticated: 'authCheck',
    }),
    components: {
        'vue-core-image-upload': VueCoreImageUpload,
    },
    data() {
        return {
            types: {
                investor: 'investor',
                player: 'player'
            },
            nickname: null,
            success: false,
            error: false,
            response: null,
            countries: null,
            header: {
                Authorization: 'Bearer ' + Cookies.get('token')
            }
        }
    },
    mounted() {
        this.getCountries();
        
        var self = this;
        Vue.nextTick(function(){ 
            
            $("#country_list").select2({
                placeholder: "Select country",
                templateResult: formatState,
                templateSelection: formatState,
                allowClear: true
            }).on("select2:select", function(e) { 
                self.user.country_id = $(e.currentTarget).find("option:selected").val();
            }).on("select2:unselecting", function (e) {
                self.user.country_id = 0;
            });
            
            function formatState (opt) 
            {
                if (!opt.id) {
                    return opt.text;
                }               
                var optimage = $(opt.element).data('image'); 
                if(!optimage){
                    return opt.text;
                } else {                    
                    var $opt = $(
                        '<span><img src="/images/flags/' + optimage + '" width="23px" /> ' + opt.text + '</span>'
                    );
                    return $opt;
                }
            };
        });
    },
    methods: {
        
        save(event) {
            event.preventDefault()
            
            axios.post('/api/user', this.user).then(response => {
                this.error = false;
                this.success = true;
            }).catch(error => {
                this.response = error.response.data
                this.error = true
                this.success = false;                
            });
        },
        imageuploaded(response) {
            this.user.avatar = response.data.avatar;
            //Event.fire('changeAvatar', this.user.avatar);
        },
        overlayuploaded(response) {
            this.user.overlay = response.data.overlay;
        },
        getCountries: function()
        {
            if(this.$parent.countries==undefined || this.$parent.countries.length==0)
            {
                axios.get('/api/countries').then((response) => {
                    this.$set(this, 'countries', response.data);
                    this.$parent.countries = this.countries;
                });
            }else{
                this.countries = this.$parent.countries;
            }  
        },
        
    },
}
</script>
<style>
.g-core-image-corp-container .info-aside{
    top: 65px !important;
    background: #000 !important;
    height: 60px !important;
}
.g-core-image-corp-container .info-aside .btn-groups{
    margin:15px auto 0;
    width: 200px;
}
.g-core-image-corp-container .btn-upload
{
    background: #177ec1 !important;
    border: solid 1px #177ec1 !important;
}
.g-resize-bar{
    background-color: #177ec1 !important;
}
.profile-cover-pic .g-core-image-upload-btn{
    color: #fff !important;
}
</style>