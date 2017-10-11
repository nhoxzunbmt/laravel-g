<template>
<div>
<div class="row heading-bg">
	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h5 class="txt-dark">Investor's info</h5>
	</div>
</div>
<div class="row" v-if="!authenticated">
	<div class="col-md-4">
        <router-link :to="{ name: 'auth.register', query: { type: 'investor' } }" class="btn btn btn-primary ml-0">
            <span class="btn-text">Register</span>
        </router-link>
    </div>
</div>
</div>
					
</template>

<script> 
    import { mapGetters } from 'vuex'
    
    export default {
        metaInfo: {
            title: 'Investor\'s info',
            titleTemplate: null
        },
        computed: {
            ...mapGetters({
                user: 'authUser',
                authenticated: 'authCheck',
            })
        }
    }
</script>