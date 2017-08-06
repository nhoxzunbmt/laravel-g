<template>
<!-- Left Sidebar Menu -->
<div class="fixed-sidebar-left">
	<ul class="nav navbar-nav side-nav nicescroll-bar">
		<li class="navigation-header">
			<span>Popular games</span> 
			<i class="zmdi zmdi-more"></i>
		</li>
        <li v-for="popularGame in this.$parent.popularGames" >
            <router-link :to="'/games/' + popularGame.id" class="">
                <div class="pull-left">
                    <img :src="storagePath + '' + popularGame.logo" width="17" class="zmdi mr-20" :title="popularGame.title"/>
                    <span class="right-nav-text">{{ popularGame.title | truncate(20, '...') }}</span></div><div class="clearfix">
                </div>
            </router-link>
        </li>
        
        <li v-if="authenticated && user.type=='player'"><hr class="light-grey-hr mb-10"/></li>
        
        <li v-if="authenticated && user.type=='player'">
            <router-link :to="{ name: 'teams.create' }">
                <div class="mr-10">
                    <button class="btn btn-info w-100"> 
                        <span class="btn-text">Create Team</span>
                    </button>
                </div>
                <div class="clearfix"></div>
            </router-link>
        </li>
        
        <li v-if="authenticated && user.type=='player'"><hr class="light-grey-hr mb-10"/></li>
        
        <li v-if="authenticated && user.type=='player'">
            <router-link :to="{ name: 'fights.create' }">
                <div class="mr-10">
                    <button class="btn btn-primary w-100"> 
                        <span class="btn-text">Create Fight</span>
                    </button>
                </div>
                <div class="clearfix"></div>
            </router-link>
        </li>
        
		<li><hr class="light-grey-hr mb-10"/></li>
		<li class="navigation-header">
			<span>left menu</span> 
			<i class="zmdi zmdi-more"></i>
		</li>
        <li>
            <router-link :to="{ name: 'games' }"><div class="pull-left"><i class="fa fa-gamepad mr-20" aria-hidden="true"></i><span class="right-nav-text">Games</span></div><div class="clearfix"></div></router-link>
        </li>
        <li>
            <router-link :to="{ name: 'teams' }"><div class="pull-left"><i class="fa fa-users mr-20" aria-hidden="true"></i><span class="right-nav-text">Teams</span></div><div class="clearfix"></div></router-link>
        </li>
        <li>
            <router-link :to="{ name: 'players' }"><div class="pull-left"><i class="fa fa-user mr-20" aria-hidden="true"></i><span class="right-nav-text">Players</span></div><div class="clearfix"></div></router-link>
        </li>
        <li>
            <router-link :to="{ name: 'investors' }"><div class="pull-left"><i class="fa fa-user-secret mr-20" aria-hidden="true"></i><span class="right-nav-text">Investors</span></div><div class="clearfix"></div></router-link>
        </li>
        <li>
            <router-link :to="{ name: 'fights' }"><div class="pull-left"><i class="ti-shield mr-20"></i><span class="right-nav-text">Fights</span></div><div class="clearfix"></div></router-link>
        </li>
        <li>
            <router-link to="/faq" class=""><div class="pull-left"><i class="zmdi zmdi-info-outline mr-20"></i><span class="right-nav-text">FAQ</span></div><div class="clearfix"></div></router-link>
        </li>
	</ul>
    
</div>
<!-- /Left Sidebar Menu -->
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    name: 'sidebar',
    computed: mapGetters({
        user: 'authUser',
        authenticated: 'authCheck'
    }),
    data() {
        return {
            storagePath: '/storage/'
        }
    }
};
</script>
<style lang="css">
.w-100{
    width: 100% !important;
}
</style>