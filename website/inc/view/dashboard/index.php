<div id="first_box_dash" class="row">
	<?php $this->loadViewTemplate("dashnav");?>
</div>
<div id="second_box_dash">
	<div class="dash_leftbar" id="dash_leftbar">
		<?php $this->loadViewTemplate("leftbar");?>
	</div>
	<div class="dash_center" id="dash_center">

	</div>
	<div class="dash_rightbar close_nav" id="dash_rightbar">
		<?php $this->loadViewTemplate("rightbar");?>
	</div>
	<div class="overlay"><div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>
</div>

<!-- initially hidden right-click menu -->
<div class="hide" id="rmenu">
	<ul>
		<li>Comando1</li>
		<li>Comando2</li>
		<li>Comando3</li>
	</ul>
</div>

<script>
$(document).bind("click", function(event) {
  document.getElementById("rmenu").className = "hide";
  document.getElementById("rmenu").style.display = "none";
});
</script>
