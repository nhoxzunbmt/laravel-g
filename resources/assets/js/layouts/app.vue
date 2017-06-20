<template>
    <div class="wrapper theme-5-active pimary-color-blue">
        <navigation></navigation>
        <sidebar></sidebar>
        
        <!-- Main Content -->
		<div class="page-wrapper">
            <div class="container-fluid">
                <child/>
            </div>
			
			<!-- Footer -->
			<footer class="footer container-fluid pl-30 pr-30">
				<div class="row">
					<div class="col-sm-12">
						<p>2017 &copy; {{siteName}}.</p>
					</div>
				</div>
			</footer>
			<!-- /Footer -->
		</div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import api from '../api'
import axios from 'axios'

export default {
    computed: mapGetters({
        user: 'authUser',
        authenticated: 'authCheck'
    }),
    created () {
        this.getPopularGames();
    },
    data() {
        return {
            siteName: "ToPlay.tv",
            logo: '/images/logo.png',
            genres: [],
            popularGames: []
        }
    },
    methods: {
        logout () {
            this.$store.dispatch('logout')
            .then(() => {
                this.$router.push({ name: 'auth.login' })
            })
        },
        getPopularGames () {
            axios.get('/api/games/popular').then((res) => {
                this.popularGames = res.data
            })
            .catch((error) => {
                console.log(error)
            })   
        }
    },
    mounted: function () {
        
        Vue.nextTick(function(){
            
            var $wrapper = $(".wrapper");
       	    /*Counter Animation*/
        	var counterAnim = $('.counter-anim');
        	if( counterAnim.length > 0 ){
        		counterAnim.counterUp({ delay: 10,
                time: 1000});
        	}
        	
        	/*Tooltip*/
        	if( $('[data-toggle="tooltip"]').length > 0 )
        		$('[data-toggle="tooltip"]').tooltip();
        	
        	/*Popover*/
        	if( $('[data-toggle="popover"]').length > 0 )
        		$('[data-toggle="popover"]').popover()
        	
        	
        	/*Sidebar Collapse Animation*/
        	var sidebarNavCollapse = $('.fixed-sidebar-left .side-nav  li .collapse');
        	var sidebarNavAnchor = '.fixed-sidebar-left .side-nav  li a';
        	$(document).on("click",sidebarNavAnchor,function (e) {
        		if ($(this).attr('aria-expanded') === "false")
        				$(this).blur();
        		$(sidebarNavCollapse).not($(this).parent().parent()).collapse('hide');
        	});
        	
        	/*Panel Remove*/
        	$(document).on('click', '.close-panel', function (e) {
        		var effect = $(this).data('effect');
        			$(this).closest('.panel')[effect]();
        		return false;	
        	});
        	
        	/*Accordion js*/
        		$(document).on('show.bs.collapse', '.panel-collapse', function (e) {
        		$(this).siblings('.panel-heading').addClass('activestate');
        	});
        	
        	$(document).on('hide.bs.collapse', '.panel-collapse', function (e) {
        		$(this).siblings('.panel-heading').removeClass('activestate');
        	});
        	
        	/*Sidebar Navigation*/
        	$(document).on('click', '#toggle_nav_btn, #open_right_sidebar, #setting_panel_btn', function (e) {
        		$(".dropdown.open > .dropdown-toggle").dropdown("toggle");
        		return false;
        	});
        	$(document).on('click', '#toggle_nav_btn', function (e) {
        		$wrapper.removeClass('open-right-sidebar open-setting-panel').toggleClass('slide-nav-toggle');
        		return false;
        	});
        	
        	$(document).on('click', '#open_right_sidebar', function (e) {
        		$wrapper.toggleClass('open-right-sidebar').removeClass('open-setting-panel');
        		return false;
        	});
        	
        	$(document).on('click', 'body', function (e) {
        		if($(e.target).closest('.fixed-sidebar-right,.setting-panel').length > 0) {
        			return;
        		}
        		$('body > .wrapper').removeClass('open-right-sidebar open-setting-panel');
        		return;
        	});
            
        	$(document).on('show.bs.dropdown', '.nav.navbar-right.top-nav .dropdown', function (e) {
        		$wrapper.removeClass('open-right-sidebar open-setting-panel');
        		return;
        	});
        	
        	$(document).on('click', '#setting_panel_btn', function (e) {
        		$wrapper.toggleClass('open-setting-panel').removeClass('open-right-sidebar');
        		return false;
        	});
        	$(document).on('click', '#toggle_mobile_nav', function (e) {
        		$wrapper.toggleClass('mobile-nav-open').removeClass('open-right-sidebar');
        		return;
        	});
        	
        	$(document).on("mouseenter mouseleave",".wrapper > .fixed-sidebar-left", function(e) {
        		if (e.type == "mouseenter") {
        			$wrapper.addClass("sidebar-hover"); 
        		}
        		else { 
        			$wrapper.removeClass("sidebar-hover");  
        		}
        		return false;
        	});
        	
        	$(document).on("mouseenter mouseleave",".wrapper > .setting-panel", function(e) {
        		if (e.type == "mouseenter") {
        			$wrapper.addClass("no-transition"); 
        		}
        		else { 
        			$wrapper.removeClass("no-transition");  
        		}
        		return false;
        	});
        
        	/*Horizontal Nav*/
        	$(document).on("show.bs.collapse",".top-fixed-nav .fixed-sidebar-left .side-nav > li > ul",function (e) {
        		e.preventDefault();
        	});
            
            /*Slimscroll*/
        	/*$('.nicescroll-bar').slimscroll({height:'100%',color: '#878787', disableFadeOut : true,borderRadius:0,size:'4px',alwaysVisible:false});
        	$('.message-nicescroll-bar').slimscroll({height:'229px',size: '4px',color: '#878787',disableFadeOut : true,borderRadius:0});
        	$('.message-box-nicescroll-bar').slimscroll({height:'350px',size: '4px',color: '#878787',disableFadeOut : true,borderRadius:0});
        	$('.product-nicescroll-bar').slimscroll({height:'346px',size: '4px',color: '#878787',disableFadeOut : true,borderRadius:0});
        	$('.app-nicescroll-bar').slimscroll({height:'162px',size: '4px',color: '#878787',disableFadeOut : true,borderRadius:0});
        	$('.todo-box-nicescroll-bar').slimscroll({height:'310px',size: '4px',color: '#878787',disableFadeOut : true,borderRadius:0});
        	$('.users-nicescroll-bar').slimscroll({height:'370px',size: '4px',color: '#878787',disableFadeOut : true,borderRadius:0});
        	$('.users-chat-nicescroll-bar').slimscroll({height:'257px',size: '4px',color: '#878787',disableFadeOut : true,borderRadius:0});
        	$('.chatapp-nicescroll-bar').slimscroll({height:'543px',size: '4px',color: '#878787',disableFadeOut : true,borderRadius:0});
        	$('.chatapp-chat-nicescroll-bar').slimscroll({height:'483px',size: '4px',color: '#878787',disableFadeOut : true,borderRadius:0});*/
        	
        	/*Nav Tab Responsive Js*/
        	$(document).on('show.bs.tab', '.nav-tabs-responsive [data-toggle="tab"]', function(e) {
        		var $target = $(e.target);
        		var $tabs = $target.closest('.nav-tabs-responsive');
        		var $current = $target.closest('li');
        		var $parent = $current.closest('li.dropdown');
        			$current = $parent.length > 0 ? $parent : $current;
        		var $next = $current.next();
        		var $prev = $current.prev();
        		$tabs.find('>li').removeClass('next prev');
        		$prev.addClass('prev');
        		$next.addClass('next');
        		return;
        	});
            
            $(window).on("resize", function () {
            	setHeightWidth();
            }).resize();
        });
        
        /*Event.listen('changeAvatar', (avatar) => {
            console.log('App (avatar changed listener) - '+avatar);
            this.auth.user.profile.avatar = avatar;
        });*/
    }
}

/***** Full height function start *****/
var setHeightWidth = function () {
	var height = $(window).height();
	var width = $(window).width();
	$('.full-height').css('height', (height));
	$('.page-wrapper').css('min-height', (height));
	
	/*Right Sidebar Scroll Start*/
	if(width<=1007){
		$('#chat_list_scroll').css('height', (height - 270));
		$('.fixed-sidebar-right .chat-content').css('height', (height - 279));
		$('.fixed-sidebar-right .set-height-wrap').css('height', (height - 219));
		
	}
	else {
		$('#chat_list_scroll').css('height', (height - 204));
		$('.fixed-sidebar-right .chat-content').css('height', (height - 213));
		$('.fixed-sidebar-right .set-height-wrap').css('height', (height - 153));
	}

	var verticalTab = $(".vertical-tab");
	if( verticalTab.length > 0 ){ 
		for(var i = 0; i < verticalTab.length; i++){
			var $this =$(verticalTab[i]);
			$this.find('ul.nav').css(
			  'min-height', ''
			);
			$this.find('.tab-content').css(
			  'min-height', ''
			);
			height = $this.find('ul.ver-nav-tab').height();
			$this.find('ul.nav').css(
			  'min-height', height + 40
			);
			$this.find('.tab-content').css(
			  'min-height', height + 40
			);
		}
	}
};
</script>