<template>
<div>
    
</div>
</template>


<script>
import { mapGetters } from 'vuex'
import axios from 'axios'
import swal from 'sweetalert2'

export default {
    computed: {
        ...mapGetters({
            user: 'authUser',
            authenticated: 'authCheck',
        })     
    },
    ready() {
        Event.listen('playerDetailLoad', event => {
            this.player = event.team;
        });
    },
    data() {
        return {
            success: false,
            error: false,
            player: null,
            response: null
        }
    },
    mounted() {
        
        if(this.player===null)
        {
            Event.listen('playerDetailLoad', event => {
                this.player = event.player;
            });
            
            this.player = this.$parent.player;
        }
    
    },
    methods: {       
        
    },
}
</script>