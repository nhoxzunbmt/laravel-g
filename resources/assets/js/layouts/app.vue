<template>
    <div class="wrapper theme-5-active pimary-color-blue">
        <navigation></navigation>
        <sidebar></sidebar>
        
        <!-- Main Content -->
		<div class="page-wrapper">
            <div class="container-fluid">
                <navbuttons></navbuttons>
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
            
            <team-create-modal v-if="authenticated && user.type=='player'"></team-create-modal>
		</div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import api from '../api'
import axios from 'axios'
import swal from 'sweetalert2';

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
            siteName: "Sparta.games",
            logo: '/images/logo-2.png',
            genres: [],
            popularGames: [],
            games: []
        }
    },
    methods: {
        logout () {
            this.$store.dispatch('logout')
            .then(() => {
                this.$router.push({ name: 'auth.login' })
            })
        },
        getPopularGames () 
        {
            var query = this.ArrayToUrl({
                '_limit' : 3,
                "_sort" : 'id'
            });
            
            axios.get('/api/games?'+query).then((res) => {
                this.popularGames = res.data.data;
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
        	$('.nicescroll-bar').slimscroll({height:'100%',color: '#878787', disableFadeOut : true,borderRadius:0,size:'4px',alwaysVisible:false});

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
        
        //First on site
        let confirm_cookie = localStorage.getItem('confirm_cookie')
        if (confirm_cookie === null) 
        {
            swal({
                title: 'First time on SPARTA.games?',
                html: '<p>You can search your favourite games using genres!  Create team! Play with your friends! We hope you\'ll enjoy it!</p><br/>'+
                '<p>Be advised that cookies are used to ensure you get the best experience on our website. If you continue to use this site, you consent to this use of cookies. Learn more about our Privacy and Cookie Policy.</p>'+
                '<p>This website stores cookies on your computer. These cookies are used to improve your website experience and provide more personalized services to you. </p>',
                type: 'info',
                showCancelButton: true,
                confirmButtonText: 'Accept!'
            }).then(function () {
                localStorage.setItem('confirm_cookie', true)
            })
        }
        
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

<style>

.col-item .photo img{
    width: 100%;
}
.col-item .info, .dropdown-menu{
    background: #284152;
}

.select2-container--default .select2-search--dropdown .select2-search__field,
.select2-container--default .select2-selection--single .select2-selection__rendered,
.select2-selection.select2-selection--multiple, 
.form-control, 
.wizard .content > .body input, .mce-floatpanel .mce-textbox, .mce-floatpanel .mce-btn, .dataTables_wrapper .dataTables_filter input, .dataTables_wrapper .dataTables_length select, .jsgrid-cell input, .jsgrid-cell select, .tablesaw-bar .btn-select select, .dd-handle, .dd3-content, .app-search .form-control:focus, .app-search .select2-container--default .select2-selection--single:focus, .select2-container--default .app-search .select2-selection--single:focus, .app-search .select2-selection.select2-selection--multiple:focus, .app-search .mce-floatpanel .mce-textbox:focus, .mce-floatpanel .app-search .mce-textbox:focus, .app-search .mce-floatpanel .mce-btn:focus, .mce-floatpanel .app-search .mce-btn:focus, .app-search .dataTables_wrapper .dataTables_filter input:focus, .dataTables_wrapper .dataTables_filter .app-search input:focus, .app-search .dataTables_wrapper .dataTables_length select:focus, .dataTables_wrapper .dataTables_length .app-search select:focus, .app-search .jsgrid-cell input:focus, .jsgrid-cell .app-search input:focus, .app-search .jsgrid-cell select:focus, .jsgrid-cell .app-search select:focus, .app-search .tablesaw-bar .btn-select select:focus, .tablesaw-bar .btn-select .app-search select:focus, .app-search .dd-handle:focus, .app-search .dd3-content:focus {
    border: 1px solid rgba(255, 255, 255, 0.18);
    background-color: #102e42;
}

/**
** Profile
*/
.profile-box .profile-cover-pic .profile-image-overlay{
    background-position: 50% !important;
    background-repeat: no-repeat !important;
    background-size: cover !important;
    opacity: 1 !important;
    background-color: #102e42;
}
.profile-box .profile-info .profile-img-wrap {
    bottom: 0px !important;
    margin: -8px 15px !important;
    position: absolute !important;
}

.profile-box .profile-info .profile-img-wrap img, .profile-box .profile-info .profile-img-wrap {
    height: auto;
}

/*.profile-box .profile-cover-pic{
    min-height: 250px !important;
}*/
.profile-box .profile-title{
    position: absolute;
    bottom: 60px;
    left: 160px;
    color: #fff;
    padding: 10px;
    text-align: left;
    text-transform: none;
}
.profile-box .tab-struct{
    margin-left: 170px;
}

.sticky{
    background: #284152; /*#fff;*/
}

.profile-box .sticky .tab-struct{
    margin-left: 0px !important;
}

.profile-box .sticky.profile-info .profile-img-wrap{
    display: none;
}

.profile-cover-pic .g-core-image-upload-btn{
    color: #fff !important;
}
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
.g-core-image-upload-btn.fileupload.btn:hover
{
    color: rgba(220, 70, 102, 0.85);
}
.g-resize-bar{
    background-color: #177ec1 !important;
}
.g-core-image-upload-btn{
    font-size: 20px !important;
}
.g-core-image-upload-btn i{
    font-size: 20px;
}

.table-responsive .team-image{
    width: 55px;
}

/**
** Navigation top
*/
.navbar.navbar-inverse.navbar-fixed-top .nav > li > a .user-online-status{
    left: 40px !important;
}
.navbar.navbar-inverse.navbar-fixed-top .nav > li > a .user-title{
    margin-left: 10px;
    display: inline-block;
    vertical-align: top;
    line-height: 55px;
    margin-right: 10px;
}
.navbar.navbar-inverse.navbar-fixed-top .nav > li > a .user-balance{
    position: absolute;
    top: 13px;
    left: 65px;
    font-size: 16px;
}

@media (min-width: 1025px){
    .navbar.navbar-inverse.navbar-fixed-top .top-nav-search {
        width: 150px;
    }
    .navbar.navbar-inverse.navbar-fixed-top .top-nav-search .input-group input {
        border-radius: 2px;
    }
}

@media (max-width: 1024px){
    .navbar.navbar-inverse.navbar-fixed-top .btn-warning{
        display: none !important;
    }
}

.navbar.navbar-inverse.navbar-fixed-top .nav-header .logo-wrap {
    padding-top: 10px;
}
.navbar.navbar-inverse.navbar-fixed-top .nav-header .logo-wrap .brand-img {
    margin-right: 5px;
    top: 8px;
}

.page-wrapper {
    background: #102e42;
}
.card-view {
    background: #284152;
    margin-bottom: 12px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    box-shadow: none;
    padding: 15px 15px 0;
    margin-left: -9px;
    margin-right: -9px;
}
.txt-dark {
    color: #fff !important;
}
.control-label {
    color: #fff;
}

.datepicker .input, .time-picker input.display-time{
    border: 1px solid rgba(255, 255, 255, 0.12);
    background-color: #102e42 !important;
    color: #fff !important;
}

.select2-dropdown{
    background: #284152;
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background-color: rgba(255,255,255,.15);
    opacity: 1;
}

/**Modal**/
.v--modal-overlay{
    background: rgba(0, 0, 0, 0.7) !important;
    z-index: 10000 !important;
}
.v--modal-overlay .v--modal-box
{
    background: #102e42 !important;
    padding: 10px 30px !important;
}
</style>