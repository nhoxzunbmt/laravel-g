<template>
<div>
    <div class="row" v-if="streams!==null">
    	<div class="col-lg-12 col-md-12 col-xs-12">
            <div class="panel panel-default card-view">
    			<div class="panel-wrapper collapse in">
    				<div class="panel-body">
                        <div class="row" v-if="streams.length>0">
                        
                            <div class="col-md-12">
								<div class="product-detail-wrap">
                                    <h3 class="mb-15 weight-500">Broadcasts</h3>
                                    <div class="col-md-12 pb-20">
                                        <div class="row">
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
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import axios from 'axios'
    export default {
        mounted() {
            this.getStreams(this.$route.params.gameId);
        },
        data : function() {
            return {
                streams: [],
            }
        },
        methods : {
            getStreams: function(id){
                axios.get('/api/twitch/search/' + id).then((response) => {
                    this.$set(this, 'streams', response.data);
                });
            }
        },
        watch: {
            '$route.params.gameId'(newId, oldId) {
                this.getStreams(newId)
            }
        }
    }
</script>