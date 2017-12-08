<template>
    <div>
        <div class="row" v-if="user!==null">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    			<div class="panel panel-default card-view">
    				<div class="panel-wrapper collapse in">
    					<div class="panel-body">
                            <h4 class="mb-10">Billing</h4>
                            <div class="row">
                            	
            					<div class="col-md-4 col-sm-6 col-xs-12">
                                    <form autocomplete="off" v-on:submit="purchase">
                                        <img src="https://www.skrill.com/fileadmin/content/images/brand_centre/Skrill_Logos/skrill-85x37_en.gif" alt="Skrill banner 85x37" title="Use the Skrill Digital Wallet for all your online transactions."/>
                                        <div class="form-group mt-10">
                                            <label for="password">Amount</label>
                                            <input type="number" class="form-control" name="amount" v-model="amount" required>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Send</button>
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
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    name: 'sidebar',
    computed: mapGetters({
        user: 'authUser',
        authenticated: 'authCheck'
    }),
    data() {
        return {
            amount: 0
        }
    },
    methods: {
        purchase(event) {
            event.preventDefault();
            
            axios.post('/api/payment/skrill/purchase',
            {
                amount: this.amount,
            }).then(response => {
                
                if(response.data.error!==undefined)
                {
                    swal({
                        type: 'error',
                        html: response.data.error
                    })
                }else{
                    
                }
            });
            
            return false;  
        },
    }
};
</script>