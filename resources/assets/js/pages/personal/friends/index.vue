<template>
<div>
	<div class="row">
		<div class="col-lg-12 col-sm-12">
			<div class="panel panel-default card-view">
				<div class="panel-wrapper collapse in">
					<div class="panel-body">
						<div class="tab-struct custom-tab-1">
							<ul class="nav nav-tabs">
                                <li v-for="tab in tabs" class="nav-item">
                                    <router-link :to="{ name: tab.route }" class="nav-link" active-class="active">
                                        {{ tab.name }}
                                    </router-link>
                                </li>
							</ul>
                            <div class="tab-content">
                                <div class="tab-pane fade active in">
                                    <transition name="fade" mode="out-in">
                                        <router-view></router-view>
                                    </transition>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Row -->
</div>
</template>

<script>
export default {
    loading: false,
    
    data: () => ({
        tabs: [
            {
                name: 'All friends',
                route: 'friends.all'
            },
            /*{
                name: 'Friends online',
                route: 'friends.online'
            },*/
            {
                name: 'Requests income',
                route: 'friends.request.in'
            },
            {
                name: 'Requests outcome',
                route: 'friends.request.out'
            },
            {
                name: 'Search',
                route: 'friends.search'
            },
        ]
    })
}
</script>
<style>
.custom-tab-1 .nav-tabs > li > a.active {
    border-bottom: 3px solid #469408;
}
</style>
