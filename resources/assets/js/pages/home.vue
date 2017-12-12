<template>
    <div>
    
        <div class="container-fluid parallax-simple rel mt-30" style="background-image: url(http://spartacoin.com/images/BlizzCon8.jpg);">
    		<div class="color-overlay-dark"></div>
    		<div class="container">
    		    <div class="row">
                    
    		    	<div class="col-md-12 text-center mb-50">
                        <!--<h4 class="mb-20">We are presenting platform for</h4>-->
                        <h2 class="mt-40 mb-40">Lead your team into eSports Pro League</h2>
                                         
                        <router-link :to="{ name: 'auth.register', query: { type: 'player' } }" class="btn btn-warning inline-block ml-0">
                            <span class="btn-text">Start as a Player</span>
                        </router-link>
                        <router-link :to="{ name: 'auth.register', query: { type: 'investor' } }" class="btn btn-danger inline-block ml-20">
                            <span class="btn-text">Start as an Investor</span>
                        </router-link>
                        
                        <h2 class="mt-40 mb-40">Earn your money from gaming</h2>
                    </div>
    		    </div>
    		</div>
    	</div>
        
        <div class="row  mb-30 mt-30">
			<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
				<div class="panel panel-default card-view pa-0">
					<div class="panel-wrapper collapse in">
						<div class="panel-body pa-0">
							<div class="sm-data-box bg-red">
								<div class="container-fluid">
									<div class="row">
										<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                            <router-link :to="{ name: 'players', query: { free_player: 1 } }">
    											<span class="txt-light block counter"><span class="counter-anim"><animated-number :number="countPlayers"></animated-number></span></span>
    											<span class="weight-500 uppercase-font txt-light block font-13">Players in teams</span>
                                            </router-link>
                                        </div>
										<div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
											<i class="fa fa-user txt-light data-right-rep-icon"></i>
										</div>
									</div>	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
				<div class="panel panel-default card-view pa-0">
					<div class="panel-wrapper collapse in">
						<div class="panel-body pa-0">
							<div class="sm-data-box bg-yellow">
								<div class="container-fluid">
									<div class="row">
										<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                            <router-link :to="{ name: 'teams', query: { status: 0 } }">
    											<span class="txt-light block counter"><span class="counter-anim"><animated-number :number="countTeamsNeedPlayers"></animated-number></span></span>
    											<span class="weight-500 uppercase-font txt-light block">Teams need players</span>
                                            </router-link>
                                        </div>
										<div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
											<i class="fa fa-users txt-light data-right-rep-icon"></i>
										</div>
									</div>	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
				<div class="panel panel-default card-view pa-0">
					<div class="panel-wrapper collapse in">
						<div class="panel-body pa-0">
							<div class="sm-data-box bg-green">
								<div class="container-fluid">
									<div class="row">
										<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
                                            <router-link :to="{ name: 'fights', query: { status: 0 } }">
    											<span class="txt-light block counter"><span class="counter-anim"><animated-number :number="countFights"></animated-number></span></span>
    											<span class="weight-500 uppercase-font txt-light block">Fights</span>
                                            </router-link>
										</div>
										<div class="col-xs-6 text-center  pl-0 pr-0 data-wrap-right">
											<i class="ti-shield txt-light data-right-rep-icon"></i>
										</div>
									</div>	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
				<div class="panel panel-default card-view pa-0">
					<div class="panel-wrapper collapse in">
						<div class="panel-body pa-0">
							<div class="sm-data-box bg-blue">
								<div class="container-fluid">
									<div class="row">
										<div class="col-xs-6 text-center pl-0 pr-0 data-wrap-left">
											<span class="txt-light block counter"><span class="counter-anim">46.43</span>%</span>
											<span class="weight-500 uppercase-font txt-light block">growth rate</span>
										</div>
										<div class="col-xs-6 text-center  pl-0 pr-0 pt-25  data-wrap-right">
								            <i class="ti-shield txt-light data-right-rep-icon"></i>
                                        </div>
									</div>	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>-->
		</div>
    
        <!--
        <div class="panel panel-default card-view mb-30 mt-30">
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 text-center">
                            <h3 class="text-warning">{{countPlayers}}</h3>
                            <h6 class="txt-dark">Players in teams</h6>
                    	</div>
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 text-center">
                            <h3 class="text-success">{{countTeamsNeedPlayers}}</h3>
                            <h6 class="txt-dark">Teams need players</h6>
                    	</div>
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 text-center">
                            <h3 class="text-danger">{{countFights}}</h3>
                            <h6 class="txt-dark">Fights</h6>
                    	</div>
                    </div>
                </div>
            </div>
        </div>-->
            
        <!--<comments :comment-url="pageId"></comments>-->
        <news-carousel></news-carousel>
    </div>
</template>

<script>
export default {
    name: 'home',
    
    metaInfo: {
        titleTemplate: 'Home | %s'
    },
    data() {
        return {
            pageId: 'main',// this.$route.path ? this.$route.path : 'main'
            countPlayers: 0,
            countTeamsNeedPlayers: 0,
            countFights: 0
        }
    },
    mounted() {
        this.getPlayersCount();  
        this.getCountTeamsNeedPlayers();
        this.getCountFights();
    },
    methods : {
        
        getPlayersCount()
        {
            var query = this.ArrayToUrl({
                "active" : 1,
                'type' : 'player',
                'free_player' : 0,
                "_limit" : 0,
                '_fields' : 'id'
            });

            axios.get('/api/users/?'+query).then((response) => {
                this.$set(this, 'countPlayers', response.data.length);
            });
        },
        getCountTeamsNeedPlayers()
        {
            var query = this.ArrayToUrl({
                "status-not" : 1,
                "_limit" : 0,
                '_fields' : 'id'
            });

            axios.get('/api/teams/?'+query).then((response) => {
                this.$set(this, 'countTeamsNeedPlayers', response.data.length);
            });
        },
        getCountFights()
        {
            var query = this.ArrayToUrl({
                "_limit" : 0,
                '_fields' : 'id'
            });

            axios.get('/api/fights/?'+query).then((response) => {
                this.$set(this, 'countFights', response.data.length);
            });
        }
    }
    
}
</script>