<div id="first_box_register">
    <a href="/" class="logo_register"></a>
</div>
<hr id="logo_hr_register">
<div class="form_register">
    <h3 id="title_register">Inserisci la nuova password</h3>
    <form>
		<div class="form-group">
			<label for="validationServerPass">Password</label>
			<input type="password" id="validationServerPass" class="form-control" aria-describedby="passwordHelpBlock" placeholder="Password">
			<small id="passwordHelpBlock" class="form-text invalid-feedback">
				La password deve contenere da 8 a 20 caratteri, tra cui lettere e numeri, e non deve contenere caratteri speciali, spazi o emoji.
			</small>
			</div>
			<div class="form-group">
				<label for="validationServerConfPass">Conferma Password</label>
				<input type="password" class="form-control" id="validationServerConfPass" placeholder="Conferma Password" value="" required>
				<div class="invalid-feedback">
					Per favore scegli una password.
				</div>
			</div>
			<button class="btn btn_rounded button_recupera" type="button">REIMPOSTA PASSWORD</button>
    </form>
</div>


<script>
var recuperButton=document.querySelector(".button_recupera");
pass=document.getElementById("validationServerPass");
repass=document.getElementById("validationServerConfPass");
pass.onchange=function(){
		checkPass();
		if(repass.value!="")
			checkConfPass();
};
repass.onchange=function(){
		checkConfPass();
};
recuperButton.onclick=function(){
	if(!checkPass()){
		pass.focus();
		return;
	}
	if(!checkConfPass()){
		repass.focus();
		return;
	}
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var resJson=JSON.parse(this.responseText);
			if(resJson.code>0)
				window.location.href="/";
		}
	};
	xhttp.open("POST", "/json.php?request=recover&action=change", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("code=<?php echo $this->Code;?>&pass="+pass.value+"&repass="+repass.value);
}
function checkPass(){
	if(pass.value==""){
		document.getElementsByClassName("invalid-feedback")[0].style.visibility="visible";
		document.getElementsByClassName("invalid-feedback")[0].innerHTML="Campo Obbligatorio";
		pass.classList.add("is-invalid");
		pass.classList.remove("is-valid");
		return false;
	}
	else{
	var re=/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/
		if(re.test(pass.value)){
			document.getElementsByClassName("invalid-feedback")[0].style.visibility="hidden";
			pass.classList.remove("is-invalid");
			pass.classList.add("is-valid");
			return true;
		}
		else{
			document.getElementsByClassName("invalid-feedback")[0].style.visibility="visible";
			document.getElementsByClassName("invalid-feedback")[0].innerHTML="La password deve contenere da 8 a 20 caratteri, tra cui lettere e numeri, e non deve contenere caratteri speciali, spazi o emoji.";
			pass.classList.add("is-invalid");
			pass.classList.remove("is-valid");
		}
	}
	return false;
	
}
function checkConfPass(){
	if(repass.value==""){
		document.getElementsByClassName("invalid-feedback")[1].style.visibility="visible";
		document.getElementsByClassName("invalid-feedback")[1].innerHTML="Perfavore reinserisci la password";
		repass.classList.add("is-invalid");
		repass.classList.remove("is-valid");
		return false;
	}
	else{
		if((pass.value).localeCompare(repass.value)==0){
			document.getElementsByClassName("invalid-feedback")[1].style.visibility="hidden";
			repass.classList.remove("is-invalid");
			repass.classList.add("is-valid");
			return true;
		}
		else{
			document.getElementsByClassName("invalid-feedback")[1].style.visibility="visible";
			document.getElementsByClassName("invalid-feedback")[1].innerHTML="Le password non coincidono";
			repass.classList.add("is-invalid");
			repass.classList.remove("is-valid");
		}
	}
	return false;
}
</script>