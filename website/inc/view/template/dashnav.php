<div class="dash_nav_first_column col-auto">
	<i class="fas fa-bars" id="dash_hamb_mobile"></i>
	<a href="/" class="logo_dash" target="_self">
		<div class="logo"></div>
	</a>
</div>

<div class="col player_box">
	<?php $this->loadViewTemplate("audioPlayer");?>
</div>

<div class="col-auto dash_nav_sc row">
	<div class="form-inline dash_search_container">
		<i class="far fa-search"></i>
		<input class="form-control mr-sm-2 dash_search" type="search" placeholder="Cerca" aria-label="Search">
	</div>
	<i class="fas fa-bell dash_notify" id="dash_notify" data-after="0">
		<div class="dropdown-menu"></div>
	</i>
	<i class="fas fa-user-friends dash_hamb" id="toggleButton"></i>
</div>