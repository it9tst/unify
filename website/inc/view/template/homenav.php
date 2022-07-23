<nav class="navbar navbar-light">
    <a href="/">
        <div class="logo"></div>
    </a>
    <div class="button_log_reg">
        <span style="font-size: 18px; color: #f4f4f4;">
		<?php if($this->isLogged):?>
            Ciao
			<?php echo $this->Nickname;?>
        </span>
			<a href="/dashboard/" target="_self"><button class="btn btn_rounded" type="button">DASHBOARD</button></a>
			<button class="btn btn_rounded" type="button" id="logoutButton">LOGOUT</button>
		<?php else:?>
			<button class="btn btn_rounded" type="button" data-toggle="modal" data-target="#login_modal">ACCEDI</button>
			<a href="/register" target="_self"><button class="btn btn_rounded" type="button">REGISTRATI</button></a>
		<?php endif;?>
    </div>
</nav>
<?php if(!$this->isLogged):?>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" id="login_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <?php $this->loadViewTemplate("login");?>
        </div>
    </div>
</div>
<?php endif;?>
<?php if($this->isLogged):?>
<script>
var logoutButton= document.getElementById("logoutButton");
logoutButton.addEventListener("click", function(){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if (this.readyState == 4 && this.status == 200) {
			var resJson=JSON.parse(this.responseText);
			if(resJson.code==1){
				window.location.reload();
			}
		}
	};
	xhttp.open("POST", "/json.php?request=index&action=logout", true);
	xhttp.send();
})

</script>
<?php endif;?>
