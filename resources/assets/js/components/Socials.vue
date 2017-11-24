<template>
    <div class="button-list mb-30">
        <a v-for="social in socials" v-bind:href="'/social/'+social.socialId" v-bind:class="'btn btn-'+social.bclass" target="_self">
            <i :class="'fa fa-'+social.fa"></i>&nbsp;{{social.socialId}}
        </a>
    </div>
</template>

<script>
export default {
    data() {
        return {
            socials: [
                /*{
                    socialId : 'vkontakte',
                    bclass: 'vk',
                    fa: 'vk'
                },
                {
                    socialId : 'google',
                    bclass: 'google',
                    fa: 'google-plus'
                },
                {
                    socialId : 'facebook',
                    bclass: 'facebook',
                    fa: 'facebook'
                },*/
                {
                    socialId : 'steam',
                    bclass: 'facebook',
                    fa: 'steam'
                },
                {
                    socialId : 'twitch',
                    bclass: 'facebook',
                    fa: 'twitch'
                },
                {
                    socialId : 'youtube',
                    bclass: 'google',
                    fa: 'youtube'
                }
            ]
        }
    },
};
</script>
<style>
.button-list .btn{
    margin-top: 10px; 
}
.button-list .btn:first-child{
    margin-left: 0;  
}
</style>