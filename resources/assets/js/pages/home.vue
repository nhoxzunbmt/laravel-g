<template>
    <div>
        <div class="row heading-bg">
        	<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h5 class="txt-dark">Beta</h5>
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
            pageId: 'main'// this.$route.path ? this.$route.path : 'main' 
        }
    },
    
}
</script>