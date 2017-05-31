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
        
        <li>
            <router-link to="/games">                
                <div class="pull-left"><i class="zmdi zmdi-filter-list mr-20"></i><span class="right-nav-text">show all</span></div><div class="clearfix"></div>
            </router-link>
		</li>

		<li><hr class="light-grey-hr mb-10"/></li>
		<li class="navigation-header">
			<span>left menu</span> 
			<i class="zmdi zmdi-more"></i>
		</li>
        
        <li>
            <router-link :to="{ name: 'home' }"><div class="pull-left"><i class="zmdi zmdi-book mr-20"></i><span class="right-nav-text">Home</span></div><div class="clearfix"></div></router-link>
        </li>
        <li>
            <router-link to="/foo" class=""><div class="pull-left"><i class="zmdi zmdi-book mr-20"></i><span class="right-nav-text">Foo</span></div><div class="clearfix"></div></router-link>
        </li>
        
        <li>
			<a href="documentation.html"><div class="pull-left"><i class="zmdi zmdi-book mr-20"></i><span class="right-nav-text">contacts</span></div><div class="clearfix"></div></a>
		</li>
        <li>
			<a href="documentation.html"><div class="pull-left"><i class="zmdi zmdi-book mr-20"></i><span class="right-nav-text">FAQ</span></div><div class="clearfix"></div></a>
		</li>
	</ul>
</div>
<!-- /Left Sidebar Menu -->
</template>

<script>
import auth from '../../auth.js'

export default {
    data() {
        return {
            storagePath: '/storage/'
        }
    },
};
</script>