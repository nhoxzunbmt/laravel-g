<template>
<div class="panel panel-default border-panel card-view">
	<div class="panel-heading">
		<div class="pull-left">
			<h6 class="panel-title pull-left">users</h6>
		</div>
	</div>
	<div class="panel-wrapper collapse in">
		<div class="panel-body row pa-0">
			<div class="chat-cmplt-wrap chat-for-widgets">
				<div class="chat-box-wrap">
					<div>
						<div class="users-nicescroll-bar">
							<ul class="chat-list-wrap">
								<li class="chat-list">
									<div class="chat-body">
										<a href="javascript:void(0)">
											<div class="chat-data">
												<img class="user-img img-circle"  src="dist/img/user.png" alt="user"/>
												<div class="user-data">
													<span class="name block capitalize-font">Clay Masse</span>
													<span class="time block truncate txt-grey">No one saves us but ourselves.</span>
												</div>
												<div class="status away"></div>
												<div class="clearfix"></div>
											</div>
										</a>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

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
    Vue.component('friend-list', {
        mounted() {
            this.fetchData()
        },
        data : function() {
            return {
                popularGames : []
            }
        },
        computed()
        {
            
        },
        methods : {
            fetchData : function(){
                this.$http.get('/api/user/friends').then((response) => {
                    console.log(response.data);
                    this.$set(this, 'popularGames', response.data.games )
                });
            }
        }
    })
</script>