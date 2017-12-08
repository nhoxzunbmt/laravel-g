<template>
<div>
    
    <div class="row heading-bg">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		  <h5 class="txt-dark">news</h5>
		</div>
	</div>
    
    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" v-for="post in news">
			<div class="panel panel-default card-view pa-0">
				<div class="panel-wrapper collapse in">
					<div class="panel-body pa-0">
						<article class="col-item">
							<div class="photo">
                                <router-link :to="{ name: 'news.detail', params: { slug: post.slug }}">
                                    <img :src="getImageLink(post.image)" class="img-responsive" :alt="post.title" />
                                </router-link>
							</div>
							<div class="info">
								<h6>{{ post.title }}</h6>
                                <p>{{ post.excerpt | truncate(100, '...') }}</p>
							</div>
						</article>
					</div>
				</div>	
			</div>	
		</div>
    </div>

</div>
</template>

<script>
export default {
    name: 'news-carousel',
    data : function() {
        return {
            news: [],
        }
    },
    mounted: function () {
        this.getNews();
    },
    methods : 
    {
        getNews: function()
        {   
            var queryStartParams = {
                '_limit' : 4
            };
            
            var query = this.UrlParamsMerge(queryStartParams);
                
            axios.get('/api/news?'+query).then((response) => {
                this.$set(this, 'news', response.data.data);
            });
        },
        
    }
};
</script>