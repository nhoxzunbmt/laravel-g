<template>
<div class="table-struct full-width">
	<div class="table-cell vertical-align-middle auth-form-wrap"  v-if="error">
		<div class="auth-form  ml-auto mr-auto no-float">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="panel panel-default card-view">
                    		<div class="panel-wrapper collapse in">
                    			<div class="panel-body">
                                    <div class="alert alert-danger">
                                        <p>{{ response.error }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--card-view-->
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import auth from '../auth.js';

export default {
    data() {
        return {
            success: false,
            error: false,
            response: null
        }
    },
    mounted: function () {
        this.$nextTick(function () {
            auth.socialAuth(this)
        })
    }
}
</script>