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

export default {
    components: { Navigation, Sidebar },
    created() {
        this.loadPopularGames();
    },
    data() {
        return {
            auth: auth,
            siteName: "ToPlay.tv",
            logo: '/images/logo.png',
            popularGames : []
        }
    },
    methods: {
        signout() {
            auth.signout()
        },
        loadPopularGames()
        {
            this.$http.get('/api/games/popular').then((response) => {
                console.log(response.data);
                this.$set(this, 'popularGames', response.data.games )
            });
        }
    },
    mounted: function () {
        this.$nextTick(function () {
            auth.check()
        })
    }
}
</script>