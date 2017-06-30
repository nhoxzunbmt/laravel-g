<template>
<div>
<div class="row heading-bg">
	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h5 class="txt-dark">Team create</h5>
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
    										<div class="form-group">
                                                <label class="control-label mb-10">Slug</label>
    											<input type="text" class="form-control" v-model="slug">
                                                <span class="help-block">Team's link will be: {{ getLink(slug) }}</span>
    										</div>
    									</div>
                                    </div>
                                    <div class="row">
    									<div class="col-md-6">
    										<div class="form-group" :class="{ 'has-error': error && response.quantity }">
                                                <label class="control-label mb-10">Quantity</label>
                                                <input v-model.number="quantity" type="number" class="form-control" placeholder="quantity">
                                                <span class="help-block" v-if="error && response.quantity">{{ response.quantity[0] }}</span>  
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

export default {
    metaInfo: {
        title: 'Team create'
    },
    computed: {
        ...mapGetters({
            user: 'authUser',
            authenticated: 'authCheck',
        }),
        slug: function() {
            var slug = this.slugTitle(this.title);
            return slug;
        }
    },
    data() {
        return {
            title: null,
            quantity: 2,
            game_id: null,
            games: [],
            success: false,
            error: false,
            response: null
        }
    },
    mounted() {
        this.getGames();
        
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
            
            axios.post('/api/teams', {
                title : this.title,
                slug: this.slug,
                quantity: this.quantity,
                game_id: this.game_id
            }).then(response => {
                this.error = false;
                this.success = true;
                
                this.$router.push({ name: 'teams.edit', params: { id: response.data.id } })
                
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
                axios.get('/api/games?show_all=1').then((response) => {
                    this.$set(this, 'games', response.data);
                    this.$parent.games = this.games;
                });
            }else{
                this.games = this.$parent.games;
            }  
        },
        getLink(slug)
        {
            let props = this.$router.resolve({ 
                name: 'team.detail',
                params: { slug: slug },
            });
            
            return location.origin+props.href;
        }
    },
}
</script>