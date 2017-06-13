<template>
    <div class="wrapper theme-5-active pimary-color-blue">
        <navigation></navigation>
        <sidebar></sidebar>
        <!-- Main Content -->
		<div class="page-wrapper">
            <div class="container-fluid">
                <router-view></router-view>
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
import auth from '../auth.js'
import Navigation from './Shared/Navigation.vue';
import Sidebar from './Shared/Sidebar.vue';
import Profile from './Personal/Profile.vue';

export default {
    components: { Navigation, Sidebar, Profile },
    created() {
        this.getPopularGames();
    },
    data() {
        return {
            auth: auth,
            siteName: "ToPlay.tv",
            logo: '/images/logo.png',
            popularGames : [],
            genres: []
        }
    },
    methods: {
        signout() {
            auth.signout()
        },
        getPopularGames()
        {
            this.$http.get('/api/games/popular').then((response) => {
                this.$set(this, 'popularGames', response.data )
            });
        }
    },
    mounted: function () {
        this.$nextTick(function () {
            auth.check();
        });
        
        Event.listen('changeAvatar', (avatar) => {
            console.log('App (avatar changed listener) - '+avatar);
            this.auth.user.profile.avatar = avatar;
        });
    }
}
</script>