<template >

</template>


<script>
    export default {
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
    }
</script>