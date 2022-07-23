/**  Creatore: Fabio Palmese **/


function getUrlVars(){
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
			vars[key] = value;
		});
	return vars;
}

window.onload=function(){
	modalContainer=document.getElementById("login_modal");
	if(typeof modalContainer!="undefined" && modalContainer!=null){
		modalContainer=modalContainer.children[0];
		var getVar=getUrlVars();
		var modalLogin=$("#login_modal");
		if(getVar['action']== "login"){
			modalLogin.modal('toggle');
		}
			
		var modalAccediButton= document.getElementById("modalAccediButton");
		var modalAccediForm= document.getElementById("modalAccediForm");
		
		modalAccediForm.addEventListener('keypress', function(event) {
			if (event.keyCode == 13) {
				accediClick();
			}
		});
		modalAccediButton.addEventListener('click', function(event) {
			accediClick();
		});
	}
	function accediClick(){
		var remember=document.forms[0].remember.checked
		clearError();
		modalContainer.classList.remove("animatedShake");
		var email=document.forms[0].email.value;
		var password=document.forms[0].password.value;
		var rememberCheck=document.forms[0].remember.value;
		if(!checkMail(email)){
			document.forms[0].email.focus();
			modalContainer.classList.add("animatedShake");
			errorMail();
		}
		else if(password==""){
			document.forms[0].password.focus();
			modalContainer.classList.add("animatedShake");
			errorPass("Inserisci una password.");
		}
		else{
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var resJson=JSON.parse(this.responseText);
					aggiornaLogin(resJson);
				}
			};
			xhttp.open("POST", "/json.php?request=login", true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.send("email="+email+"&pass="+password+"&remember="+remember);
		}
	}

	
	
	function checkMail(email){
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	function errorMail(string="Inserisci un indirizzo email valido."){
		document.getElementById("emailHelp").innerHTML=string;
		document.getElementById("emailHelp").style.visibility="visible";
	}
	function errorPass(string="La password deve avere una lunghezza compresa tra 4 e 60 caratteri."){
		document.getElementById("passHelp").innerHTML=string;
		document.getElementById("passHelp").style.visibility="visible";
	}
	function clearError(){
		document.getElementById("emailHelp").style.visibility="hidden";
		document.getElementById("passHelp").style.visibility="hidden";
	}
	function aggiornaLogin(res){
		console.log(res);
		if(res.code<0){
			modalContainer.classList.add("animatedShake");
		}
		switch(res.code){
			case -2:
			case -4:
				errorMail(res.error);
			break;
			case -3:
				errorPass(res.error);
			break;
			case 1:
				window.location.href= window.location.origin+"/dashboard/";
			break;
		}
		
	}
	
}
