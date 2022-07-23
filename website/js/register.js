/**  Creatore: Fabio Palmese **/



//javascript per register
input=document.getElementsByClassName("form-control");
window.onload=function(){
	input[0].onchange=function(){
		checkUser(this.value);
	};
	input[1].onchange=function(){
		checkMail(this.value);
	};
	input[2].onchange=function(){
		checkPass(this.value);
		if(input[3].value!="")
			checkConfPass(this.value,input[3].value);
	};
	input[3].onchange=function(){
		checkConfPass(input[2].value,this.value);
	};
	input[4].onchange=function(){
		fixDay(this);
		checkDate();
	};
	input[5].onchange=function(){
		changeMonth(this.value);
		checkDate();
	};
	input[6].onchange=function(){
		fixYear(this);
		checkDate();
	};
	input[7].onchange=function(){
		checkSesso();
	}
	input[8].onchange=function(){
		checkSesso();
	}
	
	
	
}
function registratiClick(){
		if(!checkUser(input[0].value))
			input[0].focus();
		else if(!checkMail(input[1].value))
			input[1].focus();
		else if(!checkPass(input[2].value))
			input[2].focus();
		else if(!checkConfPass(input[2].value,input[3].value))
			input[3].focus();
		else if(!checkDate()){
			invalidateDate();
			if( input[4].value!=""){
				if(input[5].value=="") input[5].focus();
				else if(input[6].value=="") input[6].focus();
				
			}
			else
				input[4].focus();
		}
		else if(!checkSesso())
			input[7].focus();
		else if(!checkTerms())
			document.getElementById("shareCheck").focus();
	
		else{	//richiama ajax per il server
			var nick=input[0].value;
			var email=input[1].value;
			var pass=input[2].value;
			var repass=input[3].value;
			var day=(input[4].value <10)? "0"+input[4].value :input[4].value;
			var month=(input[5].value<10)? "0"+input[5].value : input[5].value;
			var year=input[6].value;
			var sesso=(document.getElementById("customRadioInline1").checked)? "M" : "F";
			var marketing=document.getElementById("customCheck1").checked;
			var marketing3rd=document.getElementById("marketingCheck").checked;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var resJson=JSON.parse(this.responseText);
					aggiornaRegister(resJson);
				}
			};
			xhttp.open("POST", "/json.php?request=register", true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.send("nick="+nick+"&email="+email+"&pass="+pass+"&repass="+repass+"&dataNascita="+year+"-"+month+"-"+day+"&sesso="+sesso+"&marketing="+marketing+"&marketing3rd="+marketing3rd);
			
		}
}
function aggiornaRegister(res){
	console.log(res);
	switch(res.code){
		case -3:
			setInvalid(0,res.error);
			input[0].focus();
		break;
		case -4:
			setInvalid(1,res.error);
			input[1].focus();
		break;
		case -5:
			invalidateDate();
			document.getElementsByClassName("invalid-feedback")[4].innerHTML="Per registrarti devi aver compiuto 16 anni in base al Regolamento Europeo in materia di Protezione dei Dati Personali (GDPR).";
			input[4].focus();
		break;
		case -6:
			document.getElementsByClassName("invalid-feedback")[5].style.visibility="visible";
			input[7].focus();
		break;
		case -11:
			setInvalid(3,res.error);
		break;
		case -12:
			setInvalid(2,"La password deve contenere da 8 a 20 caratteri, tra cui lettere e numeri, e non deve contenere caratteri speciali, spazi o emoji.")
		break;
		case 1:
			document.querySelector(".form_register h3").innerHTML="Registrazione effettuata con successo!";
			document.querySelector(".form_register h3").style.color="#00ab00";
			document.querySelector("body").style.backgroundImage="none";
			document.querySelector(".form_register form").remove();
			var div=document.createElement("div");
			div.style.textAlign="center";
			div.innerHTML="Ti abbiamo mandato un'email con il link per verificare l'account.<br>Nel caso in cui non vedessi l'email controlla la tua casella spam.";
			document.querySelector(".form_register").appendChild(div);
		break;
	}
}
function checkUser(user){
	
	if(user==""){
		setInvalid(0,"Campo Obbligatorio");
		return false;
	}
	else{
		var re=/^[a-zA-Z0-9]+([a-zA-Z0-9](_|-| )[a-zA-Z0-9])*[a-zA-Z0-9]+$/;
		if(re.test(user)){
			setValid(0);
			return true;
		}
		else
			setInvalid(0,"Username non valido");
	}
	return false;
}
function checkMail(email){
	if(email==""){
		setInvalid(1,"Campo Obbligatorio");
		return false;
	}
	else{
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if(re.test(email)){
			setValid(1);
			return true;
		}
		else
			setInvalid(1,"Email non valida");
	}
	return false;
}
function checkPass(pass){
	if(pass==""){
		setInvalid(2,"Inserisci una password");
		return false;
	}
	else{
	var re=/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,20})/;
		if(re.test(pass)){
			setValid(2);
			return true;
		}
		else
			setInvalid(2,"La password deve contenere da 8 a 20 caratteri, tra cui lettere e numeri, e non deve contenere caratteri speciali, spazi o emoji.");
	}
	return false;
	
}
function checkConfPass(pass,repass){
	if(repass==""){
		setInvalid(3,"Perfavore reinserisci la password");
		return false;
	}
	else{
		if(pass.localeCompare(repass)==0){
			setValid(3);
			return true;
		}
		else
			setInvalid(3,"Le password non coincidono");
	}
	return false;
}
function fixDay(elem){
	if( isNaN(elem.value)){
		elem.value="";
		return;
	}
	elem.value=parseInt(elem.value);
	if(elem.value=="") return;
	if(elem.value<1)
		elem.value=1;
	if(elem.value>parseInt(elem.max))
		elem.value=parseInt(elem.max);
}
function fixYear(elem){
	if( isNaN(elem.value)){
		elem.value="";
		return;
	}
	elem.value=parseInt(elem.value);
	if(elem.value<parseInt(elem.min))
		elem.value=parseInt(elem.min);
	if(elem.value>parseInt(elem.max))
		elem.value=parseInt(elem.max);
	if(input[5].value==2){
		if((elem.value%4)==0){
			input[4].max="29";
		}
		else
			input[4].max="28"
		fixDay(input[4]);
	}
}
function changeMonth(mese){
	var max;
	switch(mese){
			case "4":
			case "6":
			case "9":
			case "11":
				max="30";
			break;
			case "2":
				if(input[6].value!="" && input[6].value%4!=0)
					max="28";
				else max="29";
			break;
			default:
				max="31";
	}
	input[4].max=max;
	fixDay(input[4]);
}

function checkSesso(){
	var uomo=document.getElementById("customRadioInline1").checked;
	var donna=document.getElementById("customRadioInline2").checked;
	if(uomo|| donna){
		document.getElementsByClassName("invalid-feedback")[5].style.visibility="hidden";
		return true;
		}
	else{
		document.getElementsByClassName("invalid-feedback")[5].style.visibility="visible";
		return false;
	}
}
function checkTerms(){
	if(document.getElementById("shareCheck").checked){
		document.getElementsByClassName("invalid-feedback")[6].style.visibility="hidden";
		return true;
	}
	document.getElementsByClassName("invalid-feedback")[6].style.visibility="visible";
	return false;
}
function checkDate(){
	var g=input[4].value;
	var m=input[5].value;
	var a=input[6].value;
	if(a=="" || g=="" || m==""){
			normalizeDate();
			document.getElementsByClassName("invalid-feedback")[4].innerHTML="Campo Obbligatorio";
			return false
	}
	else if(isMaggiorenne(parseInt(g),parseInt(m),parseInt(a))){
		validateDate();
		return true;
	}
	else {
		document.getElementsByClassName("invalid-feedback")[4].innerHTML="Per registrarti devi aver compiuto 16 anni in base al Regolamento Europeo in materia di Protezione dei Dati Personali (GDPR).";
		invalidateDate();
		return false;
	}
}
function invalidateDate(){
	for(var i=0;i<3;i++){
		if ( input[4+i].className.match(/(?:^|\s)is-invalid(?!\S)/) ) continue;
		if ( input[4+i].className.match(/(?:^|\s)is-valid(?!\S)/)) input[4+i].classList.remove('is-valid');
		input[4+i].classList.add('is-invalid');
		
	}
	document.getElementsByClassName("invalid-feedback")[4].style.visibility="visible";
	
}
function validateDate(){
	for(var i=0;i<3;i++){
		if ( input[4+i].className.match(/(?:^|\s)is-valid(?!\S)/) ) continue;
		if ( input[4+i].className.match(/(?:^|\s)is-invalid(?!\S)/)) input[4+i].classList.remove('is-invalid');
		input[4+i].classList.add('is-valid');
	}
	document.getElementsByClassName("invalid-feedback")[4].style.visibility="hidden";
}
function normalizeDate(){
	for(var i=0;i<3;i++){
		if ( input[4+i].className.match(/(?:^|\s)is-valid(?!\S)/) ) input[4+i].classList.remove('valid');
		if ( input[4+i].className.match(/(?:^|\s)is-invalid(?!\S)/)) input[4+i].classList.remove('is-invalid');
	}
	document.getElementsByClassName("invalid-feedback")[4].style.visibility="hidden";
}
function isMaggiorenne(giorno, mese, anno){
	var currentYear=new Date().getFullYear();
	var currentDay=new Date().getDate();	
	var currentMonth=new Date().getMonth()+1;
	if(currentYear-anno>16)
		return true;
	else{
		if(currentMonth<mese)
			return false;
		else if(currentMonth>mese)
			return true;
		else{
			if(currentDay<giorno)
				return false;
			else return true;
		}
	
	}
}

function setValid(indice){
		elemento=input[indice];
		if ( elemento.className.match(/(?:^|\s)is-valid(?!\S)/) ) return;
		if ( elemento.className.match(/(?:^|\s)is-invalid(?!\S)/))	elemento.classList.remove('is-invalid');
		elemento.classList.add('is-valid');
		document.getElementsByClassName("invalid-feedback")[indice].style.visibility="hidden";
	
}
function setInvalid(indice,message){
		elemento=input[indice];
		document.getElementsByClassName("invalid-feedback")[indice].innerHTML=message;
		if ( elemento.className.match(/(?:^|\s)is-invalid(?!\S)/) ) return;
		if ( elemento.className.match(/(?:^|\s)is-valid(?!\S)/)) elemento.classList.remove('is-valid');
		elemento.classList.add('is-invalid');
		document.getElementsByClassName("invalid-feedback")[indice].style.visibility="visible";
	
}
