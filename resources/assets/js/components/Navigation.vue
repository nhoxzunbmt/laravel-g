<template>
    <nav class="navbar navbar-inverse navbar-fixed-top" v-bind:class="{'navbar-authenticated':authenticated}">
    	<div class="mobile-only-brand pull-left">
    		<div class="nav-header pull-left">
    			<div class="logo-wrap">
                    <router-link :to="{ name: 'home' }">
                        <img class="brand-img" :src="this.$parent.logo" alt="brand"/>
    					<span class="brand-text">{{this.$parent.siteName}}</span>
                    </router-link>
    			</div>
    		</div>	
    		<a id="toggle_nav_btn" class="toggle-left-nav-btn inline-block ml-20 pull-left" href="javascript:void(0);"><i class="zmdi zmdi-menu"></i></a>
    		<a id="toggle_mobile_search" data-toggle="collapse" data-target="#search_form" class="mobile-only-view" href="javascript:void(0);"><i class="zmdi zmdi-search"></i></a>
    		<a id="toggle_mobile_nav" class="mobile-only-view" href="javascript:void(0);"><i class="zmdi zmdi-more"></i></a>
    		
            <router-link :to="{ name: 'streams' }" class="btn btn-warning inline-block ml-20 mt-15 pull-left btn-sm btn-outline">Online streams</router-link>
            
            <router-link :to="{ name: 'teams' }" class="btn btn-warning inline-block ml-20 mt-15 pull-left btn-sm btn-outline">Top teams</router-link>
            
            <router-link :to="{ name: 'players' }" class="btn btn-warning inline-block ml-20 mt-15 pull-left btn-sm btn-outline">Top players</router-link>
            
            <router-link :to="{ name: 'investors-info' }" class="btn btn-warning inline-block ml-20 mt-15 pull-left btn-sm btn-outline">For Investors</router-link>
            
            <!--<form id="search_form" role="search" class="top-nav-search collapse pull-left">
    			<div class="input-group">
    				<input type="text" name="example-input1-group2" class="form-control" placeholder="Search">
    				<span class="input-group-btn">
    				<button type="button" class="btn  btn-default"  data-target="#search_form" data-toggle="collapse" aria-label="Close" aria-expanded="true"><i class="zmdi zmdi-search"></i></button>
    				</span>
    			</div>
    		</form>-->
    	</div>
    	<div id="mobile_only_nav" class="mobile-only-nav pull-right">
    		<ul class="nav navbar-right top-nav pull-right">
            
                <li v-if="!authenticated">
                    <router-link :to="{ name: 'auth.login' }" class="btn btn-primary inline-block ml-20 mt-15 pull-left btn-sm btn-outline">Login</router-link>
                </li>
                <li v-if="!authenticated">
                    <router-link :to="{ name: 'auth.register' }" class="btn btn-danger inline-block ml-20 mt-15 pull-left btn-sm btn-outline">Register</router-link>
                </li>
				<li class="dropdown alert-drp" v-if="authenticated">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" @click="showDropdown"><i class="zmdi zmdi-notifications top-nav-icon"></i><span class="top-nav-icon-badge">0</span></a>
					<ul class="dropdown-menu alert-dropdown" data-dropdown-in="bounceIn" data-dropdown-out="bounceOut">
						<li>
							<div class="notification-box-head-wrap">
								<span class="notification-box-head pull-left inline-block">notifications</span>
								<a class="txt-danger pull-right clear-notifications inline-block" href="javascript:void(0)"> clear all </a>
								<div class="clearfix"></div>
								<hr class="light-grey-hr ma-0"/>
							</div>
						</li>
						<li>
							<div class="streamline message-nicescroll-bar">
								<div class="sl-item">
									<a href="javascript:void(0)">
										<div class="icon bg-green">
											<i class="zmdi zmdi-flag"></i>
										</div>
										<div class="sl-content">
											<span class="inline-block capitalize-font  pull-left truncate head-notifications">
											New subscription created</span>
											<span class="inline-block font-11  pull-right notifications-time">2pm</span>
											<div class="clearfix"></div>
											<p class="truncate">Your customer subscribed for the basic plan. The customer will pay $25 per month.</p>
										</div>
									</a>	
								</div>
							</div>
						</li>
						<li>
							<div class="notification-box-bottom-wrap">
								<hr class="light-grey-hr ma-0"/>
								<a class="block text-center read-all" href="javascript:void(0)"> read all </a>
								<div class="clearfix"></div>
							</div>
						</li>
					</ul>
				</li>
				<li class="dropdown auth-drp" v-if="authenticated">
					<a href="#" class="dropdown-toggle pr-0" data-toggle="dropdown" @click="showDropdown">
                        <img :src="getImageLink(user.avatar)" alt="user_auth" class="user-auth-img img-circle"/>
                        <span class="user-online-status"></span>
                        <span class="user-title">{{user.nickname}}<br />
                        <span class="user-balance text-info">
                            <span class="weight-500" style="font-size: 18px;" >{{formatPrice(user.balance)}}</span> SPA</i>
                        </span>
                        </span>
                    </a>
					<ul class="dropdown-menu user-auth-dropdown" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
                        <li>
                            <router-link :to="{ name: 'profile' }"><i class="zmdi zmdi-account"></i><span>Profile</span></router-link>
                        </li>
                        <li>
                            <router-link :to="{ name: 'friends.search' }"><i class="zmdi zmdi-accounts"></i><span>Friends</span></router-link>
                        </li>
                        <li>
                            <router-link :to="{ name: 'personal.teams' }" v-if="user.type=='player'"><i class="fa fa-users" style="font-size: 14px" aria-hidden="true"></i><span>Teams</span></router-link>
                        </li>
                        <li>
                            <router-link :to="{ name: 'personal.calendar' }" v-if="user.type=='player'"><i class="fa fa-calendar" style="font-size: 14px" aria-hidden="true"></i><span>Calendar</span></router-link>
                        </li>
                        <li>
                            <router-link :to="{ name: 'personal.stream' }" v-if="user.type=='player'"><i class="fa fa-video-camera" style="font-size: 14px" aria-hidden="true"></i><span>Streaming</span></router-link>
                        </li>
                        <li>
                            <router-link :to="{ name: 'personal.billing' }"><i class="fa fa-money" style="font-size: 14px" aria-hidden="true"></i><span>Billing</span></router-link>
                        </li>
						<li class="divider"></li>                        
                        <li>
                            <a href="javascript:void(0)" v-on:click="this.$parent.logout">
                                <i class="zmdi zmdi-power"></i><span>Logout</span>
                            </a>
                        </li>
					</ul>
				</li>
    		</ul>
    	</div>	
    </nav>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    name: 'navigation',
    computed: mapGetters({
        user: 'authUser',
        authenticated: 'authCheck'
    }),
    mounted: function () {
        Vue.nextTick(function(){
            setTimeout(function(){
                $('.message-nicescroll-bar').slimscroll({height:'229px',size: '4px',color: '#878787',disableFadeOut : true,borderRadius:0});
                $('.message-box-nicescroll-bar').slimscroll({height:'350px',size: '4px',color: '#878787',disableFadeOut : true,borderRadius:0});
            }, 1000);
        });
    },
    methods: {
        showDropdown(event){
            if(!$(event.currentTarget).closest("li").hasClass("open"))
            {
                $(event.currentTarget).closest("li").addClass("open");
            }
        },
        formatPrice(value) {
            let val = (value/1).toFixed(2).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        }
    }
};
</script>