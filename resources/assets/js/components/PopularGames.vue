<template>
    <li v-for="popularGame in this.popularGames">
        <router-link :to="'/games/' + popularGame.id" class="">
            <div class="pull-left">
                <img :src="storagePath + '' + popularGame.logo" width="17" class="zmdi mr-20" :title="popularGame.title"/>
                <span class="right-nav-text">{{ popularGame.title | truncate(20, '...') }}</span></div><div class="clearfix">
            </div>
        </router-link>
    </li>
</template>

<script>
    Vue.component('game-popular-list', {
        mounted() {
            console.log('PopularGames Component ready.')
            this.fetchPopularGameList()
        },
        data : function() {
            return {
                popularGames : []
            }
        },
        methods : {
            fetchPopularGameList : function(){
                this.$http.get('/api/games/popular').then((response) => {
                    console.log(response.data);
                    this.$set(this, 'popularGames', response.data.games )
                });
            }
        }
    })
</script>