<template>
<div> 
    <div class="row heading-bg">
    	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h5 class="txt-dark">{{ game.title }}</h5>
    	</div>
    </div>

	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default card-view">
				<div class="panel-wrapper collapse in">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-2">
								<div class="item-big">
									<div class="carousel">
										<div role="listbox" class="carousel-inner">
										      <div class="item active"> <img :src="getImageLink(game.logo)" :alt="game.title"> </div>
                                        </div>
									</div>
								</div>
							</div>
							<div class="col-md-10">
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

    <div class="row" v-if="streams.length>0">
		<div class="col-sm-12">
			<div class="panel panel-default card-view">
				<div class="panel-wrapper collapse in">
					<div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h5 class="txt-dark">Broadcasts</h5>
                                <hr class="mb-0"/>
                        	</div>
                        </div>
                        <div class="row mt-20">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6" v-for="stream in streams">
                                <div class="panel panel-default card-view pa-0">
                                	<div class="panel-wrapper collapse in">
                                		<div class="panel-body pa-0">
                                			<article class="col-item">
                                				<div class="photo">
                                                    <router-link :to="{ name: 'stream', params: { stream: stream.channel.display_name }}">
                                					    <img :src="stream.preview.medium" class="img-responsive"/>
                                                    </router-link>
                                				</div>
                                			</article>
                                		</div>
                                	</div>	
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
            this.getStreams(this.$route.params.gameId);
        },
        data : function() {
            return {
                game: [],
                streams: []
            }
        },
        methods : {
            getGame: function(id){
                axios.get('/api/games/' + id).then((response) => {
                    this.$set(this, 'game', response.data);
                });
            },
            getStreams: function(id){
                axios.get('/api/twitch/search/' + id).then((response) => {
                    this.$set(this, 'streams', response.data);
                });
            }
        },
        watch: {
            '$route.params.gameId'(newId, oldId) {
                this.getStreams(newId)
                this.getGame(newId)
            }
        }
    }
</script>