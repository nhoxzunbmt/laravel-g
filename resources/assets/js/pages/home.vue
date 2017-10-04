<template>
    <div>
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
        </div>
            
        <comments :comment-url="pageId"></comments>
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