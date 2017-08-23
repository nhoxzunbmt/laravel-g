<template>
    <div>
    
        <div class="row heading-bg">
        	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h5 class="txt-dark">{{fight.title}}</h5>
        	</div>
        </div>
        
    </div>    
</template>

<script> 
    import { mapGetters } from 'vuex'
    import axios from 'axios'
    
    export default {
        metaInfo: {
            title: 'Fight detail',
        },
        computed: {
            ...mapGetters({
                user: 'authUser',
                authenticated: 'authCheck',
            }),
        },
        mounted() {
            this.getFight();
        },
        data : function() {
            return {
                fight: null,
                teams: [],
                players: []
            }
        },
        methods : {
            getFight: function()
            {
                var query = this.ArrayToUrl({
                    '_with' : 'game,createBy,judge,commentator,canceledBy'
                });
                
                axios.get('/api/fights/'+this.$route.params.id+"?"+query).then((response) => {
                    this.$set(this, 'fight', response.data);
                    
                    this.title = this.fight.title;
                    this.$meta().refresh();
                    
                    if(this.fight.type=='personal')
                    {
                        this.getPlayers();
                    }else{
                        this.getTeams();
                    }
                });
            },
            getTeams: function()
            {
                var query = '';
                axios.get('/api/fights/'+this.$route.params.id+"/teams?"+query).then((response) => {
                    this.$set(this, 'teams', response.data);
                });
            },
            getPlayers: function()
            {
                var query = '';
                axios.get('/api/fights/'+this.$route.params.id+"/users?"+query).then((response) => {
                    this.$set(this, 'players', response.data);
                });
            }
        },
        watch: {
            '$route.query'(newPage, oldPage) {
                this.getVueItems();
            }
        }
    }
</script>