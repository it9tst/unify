<div id="first_box_register">
    <a href="/" class="logo_register"></a>
</div>
<hr id="logo_hr_register">
<div class="form_register">
    <h3 id="title_register">Inserisci l'email con cui ti sei registrato</h3>
    <form>
        <div class="form-group" id="formRecoverEmail">
            <label for="validationServerEmail">E-mail</label>
            <input type="email" class="form-control" id="validationServerEmail" placeholder="Email" value required>
            <div class="invalid-feedback">
				Inserisci un email valida
            </div>
        </div>
        <div class="form-group">
			<label  id="formRecoverTest">Ti manderemo una email con il link per il ripristino della password. Segui le istruzioni riportate nella email.</label>
		</div>
        <button class="btn btn_rounded button_recupera" type="button">RECUPERA PASSWORD</button>
    </form>
</div>


<script>
var recuperButton=document.querySelector(".button_recupera");
var email=document.getElementById("validationServerEmail");
email.onchange=function(){
		checkMail(this);
	};
recuperButton.onclick=function(){
	if(!checkMail(email)){
		email.focus();
		return;
	}
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var resJson=JSON.parse(this.responseText);
				aggiornaRecover(resJson);
			}
	};
	xhttp.open("POST", "/json.php?request=recover", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("email="+email.value);
}

function aggiornaRecover(res){
	if(res.code<0){
		email.classList.add("is-invalid");
		email.classList.remove("is-valid");
		email.classList.add
		document.getElementsByClassName("invalid-feedback")[0].style.visibility="visible";
		document.getElementsByClassName("invalid-feedback")[0].innerHTML=res.error;
	}
	else{
		recuperButton.style.display="none";
		document.getElementById("formRecoverEmail").style.display="none";
		document.getElementById("title_register").innerHTML="Email corretta: abbiamo trovato il tuo account";
		document.getElementById("formRecoverTest").innerHTML="Ti abbiamo mandato una email con il link per il ripristino della password. Segui le istruzioni riportate nella email.";
	}
}
function checkMail(mail){
	if(mail.value==""){
		document.getElementsByClassName("invalid-feedback")[0].style.visibility="visible";
		document.getElementsByClassName("invalid-feedback")[0].innerHTML="Campo Obbligatorio"
		mail.classList.add("is-invalid");
		mail.classList.remove("is-valid");
		return false;
	}
	else{
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if(re.test(mail.value)){
			document.getElementsByClassName("invalid-feedback")[0].style.visibility="hidden";
			email.classList.add("is-valid");
			email.classList.remove("is-invalid");
			return true;
		}
		else
			document.getElementsByClassName("invalid-feedback")[0].style.visibility="visible";
			document.getElementsByClassName("invalid-feedback")[0].innerHTML="Email non valida"
			mail.classList.add("is-invalid");
			mail.classList.remove("is-valid");
	}
	return false;
}
</script>