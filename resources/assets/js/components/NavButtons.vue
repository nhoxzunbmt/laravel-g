<template>
<div>
    <div class="row nav-buttons" v-if="authenticated && user.type=='player'">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <!--<router-link :to="{ name: 'teams.create' }"  v-if="authenticated && user.type=='player'" class="btn btn-info inline-block pull-left btn-sm btn-outline">
                <span class="btn-text">Create Team</span>
            </router-link>-->
            
            <a v-if="authenticated && user.type=='player'" class="btn btn-info inline-block pull-left btn-sm btn-outline" @click="show">
                <span class="btn-text">Create Team</span>
            </a>
            
            <router-link :to="{ name: 'fights.create' }" v-if="authenticated && user.type=='player'" class="btn btn-primary inline-block ml-20 pull-left btn-sm btn-outline">
                <span class="btn-text">Create Fight</span>
            </router-link>
        </div>
    </div>
</div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    name: 'navbuttons',
    computed: mapGetters({
        user: 'authUser',
        authenticated: 'authCheck'
    }),
    methods: {
      show () {
        this.$modal.show('teams-create');
      },
      hide () {
        this.$modal.hide('teams-create');
      }
    }
};
</script>
<style lang="css">
.nav-buttons{
    margin: 10px -39px -5px;
    padding: 13px 15px;
}
</style>