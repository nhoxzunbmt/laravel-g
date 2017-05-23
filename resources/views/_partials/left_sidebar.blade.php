<!-- Left Sidebar Menu -->
<div class="fixed-sidebar-left">
	<ul class="nav navbar-nav side-nav nicescroll-bar">
		<li class="navigation-header">
			<span>Popular games</span> 
			<i class="zmdi zmdi-more"></i>
		</li>
        @if (count($popularGames) > 0)
            @foreach ($popularGames as $popularGame)
                <li>
					<a href="/games/{{ $popularGame->id }}">
                        <div class="pull-left"><img src="<?=Storage::disk('public')->url($popularGame->logo)?>" width="18" class="zmdi mr-20" title="{{ $popularGame->title }}"/><span class="right-nav-text">{{ str_limit($popularGame->title, 20) }}</span></div><div class="clearfix"></div>
                    </a>
				</li>
            @endforeach
         @endif
        <li>
			<a href="/games">
                <div class="pull-left"><i class="zmdi zmdi-filter-list mr-20"></i><span class="right-nav-text">show all</span></div><div class="clearfix"></div>
            </a>
		</li>
		<li><hr class="light-grey-hr mb-10"/></li>
		<li class="navigation-header">
			<span>left menu</span> 
			<i class="zmdi zmdi-more"></i>
		</li>
		<li>
			<a href="documentation.html"><div class="pull-left"><i class="zmdi zmdi-book mr-20"></i><span class="right-nav-text">documentation</span></div><div class="clearfix"></div></a>
		</li>
	</ul>
</div>
<!-- /Left Sidebar Menu -->