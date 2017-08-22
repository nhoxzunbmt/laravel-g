<template>
<div>
<div class="row heading-bg">
	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h5 class="txt-dark">Fight create</h5>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-xs-12">
        <div class="panel panel-default card-view">
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
                    <div class="row">
    					<div class="col-md-12 col-sm-12 col-xs-12">
                        
                            <div class="alert alert-warning alert-dismissable" v-if="user.type!='player'">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<i class="zmdi zmdi-alert-circle-o pr-15 pull-left"></i><p class="pull-left">Only player can create teams. </p>
								<div class="clearfix"></div>
							</div>
                        
    						<div class="form-wrap" v-else>
                                <div class="alert alert-success alert-dismissable" v-if="success">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <p>Team is created!</p>
                                </div>
    							<form autocomplete="off" @submit="save">                                              
                                    <div class="row">
    									<div class="col-md-6" :class="{ 'has-error': error && response.title }">
    										<div class="form-group">
                                                <label class="control-label mb-10">Title</label>
                                                <input type="text" class="form-control" placeholder="name" v-model="title">
                                                <span class="help-block" v-if="error && response.title">{{ response.title[0] }}</span>
    										</div>
    									</div>
                                        <div class="col-md-6">
    										<div class="form-group" :class="{ 'has-error': error && response.game_id }">
                                                <label class="control-label mb-10">Game</label>
    											<select v-model="game_id" class='form-control' data-style="form-control btn-default btn-outline" id="game_list">
                                                    <option v-for="game in games" v-bind:value="game.id">
                                                        {{ game.title }}
                                                    </option>
                                                </select>
                                                <span class="help-block" v-if="error && response.game_id">{{ response.game_id[0] }}</span>
                                            </div>
    									</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
    										<div class="form-group" :class="{ 'has-error': error && response.type }">
                                                <label class="control-label mb-10">Type</label>
    											<select v-model="type" class='form-control' data-style="form-control btn-default btn-outline">
                                                    <option v-for="type in types" v-bind:value="type.id">
                                                        {{ type.title }}
                                                    </option>
                                                </select>
                                                <span class="help-block" v-if="error && response.type">{{ response.type[0] }}</span>
                                            </div>
    									</div>
    									
                                        <div class="col-md-6">
    										<div class="form-group" :class="{ 'has-error': error && response.start_at }">
                                                <label class="control-label mb-10">Date & time</label>
                                                <div class="row">
        											<div class="col-md-8 col-sm-12 col-xs-12 form-group">
                                                        <date-picker v-model="start_at_date" lang="en"></date-picker>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                                        <vue-timepicker format="HH:mm:ss" v-model="start_at_time" :minute-interval="5"></vue-timepicker>
                                                    </div>
                                                </div>
                                                <span class="help-block" v-if="error && response.start_at">{{ response.start_at[0] }}</span>
                                            </div>
    									</div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6" v-if="type=='team'">
    										<div class="form-group" :class="{ 'has-error': error && response.team_id }">
                                                <label class="control-label mb-10">Team</label>
                                                <select v-model="team_id" class='form-control' data-style="form-control btn-default btn-outline">
                                                    <option v-for="team in teams" v-bind:value="team.team_id">
                                                        {{ team.title }}
                                                    </option>
                                                </select>
                                                <span class="help-block" v-if="error && response.team_id">{{ response.team_id[0] }}</span>  
                                            </div>
    									</div>
                                        <div class="col-md-6">
    										<div class="form-group" :class="{ 'has-error': error && response.count_parts }">
                                                <label class="control-label mb-10">Quantity</label>
                                                <input v-model.number="count_parts" type="number" class="form-control" placeholder="quantity">
                                                <span class="help-block" v-if="error && response.count_parts">{{ response.count_parts[0] }}</span>  
                                            </div>
    									</div>
                                    </div>
                                      
    								<div class="form-actions mt-10">
                                        <button type="submit" class="btn btn-primary mr-10">
                                            <span>Save</span>
                                        </button>			
    								</div>				
    							</form>
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
import { mapGetters } from 'vuex'
import axios from 'axios'
import DatePicker from 'vue2-datepicker'
import VueTimepicker from 'vue2-timepicker'

export default {
    metaInfo: {
        title: 'Fight create'
    },
    components: { DatePicker, VueTimepicker },
    computed: {
        ...mapGetters({
            user: 'authUser',
            authenticated: 'authCheck',
        })
    },
    data() {
        return {
            title: null,
            game_id: null,
            games: [],
            type: null,
            teams: [],
            count_parts: 2,
            success: false,
            error: false,
            response: null,
            types: [
                {id: 'personal', title: 'personal'},
                {id: 'team', title: 'team'}
            ],
            start_at_date: null,
            start_at_time: {
                HH: "00",
                mm: "05",
                ss: "00"
            }
        }
    },
    mounted() {
        this.getGames();
        this.getUserTeams();
        
        var self = this;
        Vue.nextTick(function(){ 
            $("#game_list").select2({
                placeholder: "Select game",
                allowClear: true
            }).on("select2:select", function(e) { 
                self.game_id = $(e.currentTarget).find("option:selected").val();
            }).on("select2:unselecting", function (e) {
                self.game_id = 0;
            });
        });
    },
    methods: {
        save(event) {
            event.preventDefault()
            
            axios.post('/api/fights', {
                title : this.title,
                type: this.type,
                start_at_date: this.start_at_date,
                start_at_time: this.start_at_time.HH+":"+this.start_at_time.mm+":"+this.start_at_time.ss,
                game_id: this.game_id,
                count_parts: this.count_parts,
                team_id: this.team_id
            }).then(response => {
                this.error = false;
                this.success = true;
                
                this.$router.push({ name: 'fights'});
                //this.$router.push({ name: 'fights.edit', params: { id: response.data.id } })
                
            }).catch(error => {
                this.response = error.response.data
                this.error = true
                this.success = false;                
            });
        },
        getGames: function()
        {
            if(this.$parent.games==undefined || this.$parent.games.length==0)
            {
                var queryStartParams = {
                    '_limit' : 0,
                    "_sort" : 'title'
                };
                
                var query = this.ArrayToUrl(queryStartParams);
                
                axios.get('/api/games?'+query).then((response) => {
                    this.$set(this, 'games', response.data);
                    this.$parent.games = this.games;
                });
            }else{
                this.games = this.$parent.games;
            }  
        },
        getUserTeams: function()
        {
            var query = this.ArrayToUrl({
                'status' : 1
            });
            axios.get('/api/user/'+this.user.id+'/teams?'+query).then((response) => {
                this.$set(this, 'teams', response.data);
                this.$parent.teams = this.teams;
            });
        }
    },
}
</script>

<style>
    .datepicker .input, .time-picker input.display-time{
        border: 1px solid rgba(33, 33, 33, 0.12) !important;
        border-radius: 0 !important;
        background-color: #fff !important;
        box-shadow: none !important;
        color: #212121 !important;
        height: 42px !important;
        width: 100% !important;
    }
    .datepicker, .time-picker, .time-picker .dropdown .select-list, .time-picker .dropdown{
        width: 100% !important;
    }
    .time-picker .dropdown{
        top: 42px !important;
    }
    .time-picker .dropdown ul li.active, .time-picker .dropdown ul li.active:hover{
        background: #177ec1 !important;
    }
</style>