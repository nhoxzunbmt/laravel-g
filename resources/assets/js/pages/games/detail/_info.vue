<template>
<div>
    <div class="row" v-if="game!==null">
    	<div class="col-lg-12 col-md-12 col-xs-12">
            <div class="panel panel-default card-view">
    			<div class="panel-wrapper collapse in">
    				<div class="panel-body">
                        <div class="row">
							<div class="col-md-12">
								<div class="product-detail-wrap">
                                    <h3 class="mb-15 weight-500">{{ game.title }}</h3>
                                    <div class="col-md-12 pb-20">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6" v-for="image in game.images">
                                    			<div class="panel panel-default card-view pa-0">
                                    				<div class="panel-wrapper collapse in">
                                    					<div class="panel-body pa-0">
                                    						<article class="col-item">
                                    							<div class="photo">
                                                                    <img class="img-responsive" :src="getImageLink(image)"  :alt="game.title" />
                                    							</div>
                                    						</article>
                                    					</div>
                                    				</div>	
                                    			</div>	
                                    		</div>
                                        </div>
                                    </div>
                                    <p class="mb-10" v-if="game.genre!==undefined"><strong>Genre:</strong> &nbsp;{{ game.genre.title }}</p>
									<p class="mb-50"><strong>Description:</strong> &nbsp;{{ game.body }}</p>
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
    import axios from 'axios'
    
    export default {
        mounted() {
            this.getGame(this.$route.params.gameId);
        },
        data : function() {
            return {
                game: []
            }
        },
        methods : {
            getGame: function(id){
                axios.get('/api/games/' + id).then((response) => {
                    this.$set(this, 'game', response.data);
                });
            },
        },
        watch: {
            '$route.params.gameId'(newId, oldId) {
                this.getGame(newId)
            }
        }
    }
</script>