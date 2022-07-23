String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};
var myPlaylist;
var closeMenu=false;
var searchBar;

var urlSite="https://dashboard.unify-unipa.it/";


/** proprietà di window **/
window.onload=initFunction;

// gestire la cronologia per navigare tra i link
window.onpopstate = function(e){
	var state=e.state;
	if(typeof state.link!="undefined" && state.link!=null){
		loadCenterTemplateFunc(state.link, state.addict, state.exec, state.params, false);
		clearAllClicked();
		document.querySelector(".left_link[data-link='"+state.link+"']").classList.add("activated");
	}
	
};
window.onresize=widthScreenDependencies;
window.addEventListener('click', function(e){ // per mobile il click sullo schermo fa chiudere le nav bar
	if(closeMenu){
		
		for(var i=0; i<e.path.length; i++){
			if(e.path[i]==rightBar)
				return;
		}

		leftBar.classList.remove("open_nav");
		leftBar.classList.add("close_nav");
		toggleButtonLeft.classList.remove("open_nav");
		toggleButtonLeft.classList.add("close_nav");
		
		
		rightBar.classList.remove("open_nav");
		rightBar.classList.add("close_nav");
		toggleButton.classList.remove("open_nav");
		toggleButton.classList.add("close_nav");
	}
	
})


/** proprietà di window **/
function clearAllClicked(){
	for(var i=0;i<leftLink.length;i++){
		leftLink[i].classList.remove("activated");
	}	
}



function initFunction(){
	var sessid=window.localStorage.getItem("phpsessid");
	if(typeof sessid=="undefined" && sessid==null){
		window.location.href="index.php";
		return;
	}
	
	document.querySelector("#dash_leftbar .avatar_user .user_img").style.backgroundImage="url('"+urlSite+"images/accounts/"+window.localStorage.getItem("Foto")+"')";
	document.querySelector("#dash_leftbar .username_dashnav").innerHTML=window.localStorage.getItem("Nickname");
	
	rebuildAllLink();
	
	
	// left bar e right bar
	toggleButton=document.getElementById("toggleButton");
	toggleButtonLeft=document.getElementById("dash_hamb_mobile");
	
	rightBar=document.getElementById("dash_rightbar");
	leftBar=document.getElementById("dash_leftbar");
	
	dashCenter=document.getElementById("dash_center");
	toggleButton.addEventListener("click", toggleRightBar);
	toggleButtonLeft.addEventListener("click", toggleLeftBar);
	// left bar e right bar
	
	
	// logout button
	document.getElementById("logoutButton").addEventListener("click", function(){
		_request(function(res){
			if(res.code>0){
				window.location.href="https://unify-unipa.it";
			}
		},"logout",Array(), "index");
	});
	// logout button
	
	
	// load new music all'inizio


	loadCenterTemplateFunc("newMusic");
						
	new Friend();
	myPlaylist= new Playlist();

	// load new music all'inizio
	
	
	
	// init component and view

	myGenreListShow()
	widthScreenDependencies()
	userAgentInit()
	// init component and view
	
	
	//ricerca
	
	searchBar=document.querySelector(".dash_search_container .dash_search");
	searchBar.addEventListener("input",searchFunction);

}


function widthScreenDependencies(){
	var w = window.outerWidth;
	var playerBox= document.getElementsByClassName("player_box")[0];
	var dashNav= document.getElementById("first_box_dash");
	if(w<1180){
		closeMenu=true;
		if(playerBox.parentElement.tagName!="BODY"){
			document.body.appendChild(playerBox);
		}
	}else{
		closeMenu=false;
		if(playerBox.parentElement.tagName=="BODY"){
			dashNav.insertBefore(playerBox, dashNav.querySelector(".dash_nav_sc"));
		}
	}
	
	
	if(w<=768){
		if($(".card-deck ~ .scroll_bar").length==0){
			scrollBarMobile();
		}else{
			$(".card-deck").each(function(index){
				
				var scroll=this.parentElement.querySelector(".scroll_bar");
				if(this.offsetWidth>=this.scrollWidth){
					if(scroll){
						scroll.remove();
					}
					return;
				}

				if(scroll){
					scroll.childNodes[0].style.width=this.offsetWidth/this.scrollWidth*100+"%";
				}
			});	
		}
	}else{
		$(".card-deck ~ .scroll_bar").remove()
	}
	
}


function userAgentInit(){
	if(navigator.userAgent.toLowerCase().indexOf('firefox') > -1){

		$(".vertical-menu").each(function(){
			this.style.overflowY="hidden";
			this.addEventListener("wheel", function(e){this.scrollTo(0, this.scrollTop+e.deltaY*10)});
		});
		
		$("#dash_center")[0].addEventListener("wheel", function(e){this.scrollTo(0, this.scrollTop+e.deltaY*19)});
		$("#dash_center")[0].style.overflowY="hidden";
		
	}
}


// functione chiamata quando si clicca un link per cambiare la view centrale
function loadCenterTemplate(){
	if(this.classList.contains("activated")){
		return;
	}
	
	var dataLink=this.getAttribute("data-link");
	var dataAddict=this.getAttribute("data-addict");
	var dataExec=this.getAttribute("data-exec");
	var dataParams=this.getAttribute("data-params");
	
	clearAllClicked();
	this.classList.add("activated");
	if(dataLink=="genre" || dataLink=="myGenre"){
		document.getElementById(dataLink).classList.add("activated");
	}
	
	loadCenterTemplateFunc(dataLink, dataAddict, dataExec, dataParams);
}

function loadCenterTemplateFunc(dataLink, dataAddict, dataExec, dataParams, pushState=true){
	var link=urlSite+"/json.php?request=dashboard&action=loadtemplate&datalink=";
	if(dataLink){
		var state={};
		state.link=dataLink;
		link+=dataLink;
		if(typeof dataAddict!="undefined" && dataAddict!=null){
			link+="&addict="+dataAddict;
			state.addict=dataAddict;
		}
		if(typeof dataExec!="undefined" && dataExec!=null){
			state.exec=dataExec;
		}
		if(typeof dataParams!="undefined" && dataParams!=null){
			state.params=dataParams;
		}
		$(".overlay").show();
		
		$.ajax({url: link+"&phpsess="+window.localStorage.getItem("phpsessid")}).done(function(data){
			responseFromLoadTemplate(data, pushState, state, state.exec, state.params);
		});
	}
	
}

function responseFromLoadTemplate(data, pushState, state, exec, params){
	$(".overlay").hide();
	data=data.replaceAll('/images', 'images');
	dashCenter.innerHTML=data.replaceAll('images', urlSite+'images');
	dashCenter.scrollTo(0,0);

	
	if(pushState){
		history.pushState(state, state.link, "");
	}
	document.querySelector(".dash_search_container .dash_search").value="";
	if(window.outerWidth<=768){
		scrollBarMobile();
	}


	if(closeMenu){
		leftBar.classList.remove("open_nav");
		leftBar.classList.add("close_nav");
		toggleButtonLeft.classList.remove("open_nav");
		toggleButtonLeft.classList.add("close_nav");

		rightBar.classList.remove("open_nav");
		rightBar.classList.add("close_nav");
		toggleButton.classList.remove("open_nav");
		toggleButton.classList.add("close_nav");
	}
	if(typeof exec!="undefined"){
		if(typeof params!="undefined"){
			eval(exec+"("+params+")");
		}else{
			eval(exec+"()");
		}
	}
	
	
	rebuildAllLink();
}



/** funzioni per gestire i link **/


function rebuildAllLink(){
	aggiornaSongLink();
	aggiornaAlbumLink();
	aggiornaArtistLink();
	aggiornaPlaylistLink();
	aggiornaCenterLink();
	aggiornaLeftLink();
}

function aggiornaCenterLink(){
	var c=document.getElementsByClassName("center_link");
	for(var i=0;i<c.length;i++) {
		c[i].addEventListener('click', loadCenterTemplate, false);
	}
}
function aggiornaLeftLink(){
	var l=document.getElementsByClassName("left_link");
	for(var i=0;i<l.length;i++){
		if(l[i].getAttribute("id")=="genre"||l[i].getAttribute("id")=="myGenre"){
			continue;
		}
		l[i].addEventListener('click', loadCenterTemplate, false);
	}
}
function aggiornaSongLink(){
	playSong=document.getElementsByClassName("play_song");
	for(var i=0;i<playSong.length;i++) {		
		if(playSong[i].getAttribute("data-song")==Player.idSong){
			var trSong=playSong[i].parentElement.parentElement;
			if(trSong.tagName=="TR"){
				trSong.classList.add("activated-song");
				if(Player.isPlaying){
					trSong.querySelector("td i").classList.remove("fa-play-circle");
					trSong.querySelector("td i").classList.add("fa-pause-circle");
				}else{
					trSong.querySelector("td i").classList.add("fa-play-circle");
					trSong.querySelector("td i").classList.remove("fa-pause-circle");
				}
				trSong.style.backgroundColor="rgba(0,0,0,.075)";
			}
		}
		playSong[i].onclick=playSongClick;
	}
}

function playSongClick(e){
	var dataSong=this.getAttribute("data-song");
	var trSong=this.parentElement.parentElement;
	
	if(Player.idSong!=dataSong){
		var trOld=document.querySelector("tr.activated-song");
		if(trOld){
			trOld.classList.remove("activated-song");
			trOld.querySelector("td i").classList.add("fa-play-circle");
			trOld.querySelector("td i").classList.remove("fa-pause-circle");
			trOld.style.backgroundColor="";
		}
	}

	if(Player.load(dataSong)){
		if(trSong.tagName=="TR"){
			trSong.classList.add("activated-song");
			trSong.querySelector("td i").classList.remove("fa-play-circle");
			trSong.querySelector("td i").classList.add("fa-pause-circle");
			trSong.style.backgroundColor="rgba(0,0,0,.075)";
		}
	}else{
		if(trSong.tagName=="TR"){
			trSong.classList.add("activated-song");
			trSong.querySelector("td i").classList.add("fa-play-circle");
			trSong.querySelector("td i").classList.remove("fa-pause-circle");
			trSong.style.backgroundColor="rgba(0,0,0,.075)";
		}
	}
	e.preventDefault();
	e.stopPropagation();
};


function aggiornaAlbumLink(){
	playAlbum=document.getElementsByClassName("play_album");
	for(var i=0;i<playAlbum.length;i++) {
		if(playAlbum[i].getAttribute("data-album")==Player.idAlbum){
			var alb=playAlbum[i];
			alb.classList.add("activated-album");
			if(Player.isPlaying){
				alb.classList.remove("fa-play-circle");
				alb.classList.add("fa-pause-circle");
			}else{
				alb.classList.add("fa-play-circle");
				alb.classList.remove("fa-pause-circle");
			}
		}
		playAlbum[i].onclick=function(e){
			var dataAlbum=this.getAttribute("data-album");
			
			if(Player.idAlbum!=dataAlbum){
				var albOld=document.querySelector(".play_album.activated-album");
				if(albOld){
					albOld.classList.remove("activated-album");
					albOld.classList.add("fa-play-circle");
					albOld.classList.remove("fa-pause-circle");
				}
			}
			
			
			if(Player.loadAlbum(dataAlbum)){
				this.classList.add("activated-album");
				this.classList.remove("fa-play-circle");
				this.classList.add("fa-pause-circle");
				
			}else{
				this.classList.add("activated-album");
				this.classList.add("fa-play-circle");
				this.classList.remove("fa-pause-circle");
				
			}
			e.preventDefault();
			e.stopPropagation();
		}
	}
}

function aggiornaArtistLink(){
	playArtist=document.getElementsByClassName("play_artist");
	for(var i=0;i<playArtist.length;i++) {
		playArtist[i].onclick=function(e){
			var dataArtist=this.getAttribute("data-artist");
			
			
			
			if(Player.idArtist!=dataArtist){
				var artOld=document.querySelector(".play_artist.activated-artist");
				if(artOld){
					artOld.classList.remove("activated-artist");
					artOld.classList.add("fa-play-circle");
					artOld.classList.remove("fa-pause-circle");
				}
			}
			if(Player.loadArtist(dataArtist)){
				this.classList.add("activated-artist");
				this.classList.remove("fa-play-circle");
				this.classList.add("fa-pause-circle");
				
			}else{
				this.classList.add("activated-artist");
				this.classList.add("fa-play-circle");
				this.classList.remove("fa-pause-circle");
				
			}
			e.preventDefault();
			e.stopPropagation();
		}
	}
}

function aggiornaPlaylistLink(){
	playPlaylist=document.getElementsByClassName("play_playlist");
	for(var i=0;i<playPlaylist.length;i++) {
		playPlaylist[i].onclick=function(e){
			var dataPlaylist=this.getAttribute("data-playlist");
			
			if(Player.idPlaylist!=dataPlaylist){
				var playOld=document.querySelector(".play_playlist.activated-playlist");
				if(playOld){
					playOld.classList.remove("activated-playlist");
					playOld.classList.add("fa-play-circle");
					playOld.classList.remove("fa-pause-circle");
				}
			}
			if(Player.loadPlaylist(dataPlaylist)){
				this.classList.add("activated-playlist");
				this.classList.remove("fa-play-circle");
				this.classList.add("fa-pause-circle");
				
			}else{
				this.classList.add("activated-playlist");
				this.classList.add("fa-play-circle");
				this.classList.remove("fa-pause-circle");
				
			}
			e.preventDefault();
			e.stopPropagation();
		}
	}
	return playPlaylist;
}


/** funzioni per gestire i link **/

















/** animation function **/

function toggleRightBar(e){
	if(toggleButton.classList.contains("open_nav")){
		rightBar.classList.remove("open_nav");
		rightBar.classList.add("close_nav");
		toggleButton.classList.remove("open_nav");
		toggleButton.classList.add("close_nav");
		
		
	}else{
		rightBar.classList.remove("close_nav");
		rightBar.classList.add("open_nav");
		toggleButton.classList.remove("close_nav");
		toggleButton.classList.add("open_nav");
		if(toggleButtonLeft.classList.contains("open_nav")){
			leftBar.classList.remove("open_nav");
			leftBar.classList.add("close_nav");
			toggleButtonLeft.classList.remove("open_nav");
			toggleButtonLeft.classList.add("close_nav");
		}
	}
	e.stopPropagation();
	document.getSelection().removeAllRanges();
}

function toggleLeftBar(e){
	if(toggleButtonLeft.classList.contains("open_nav")){
		leftBar.classList.remove("open_nav");
		leftBar.classList.add("close_nav");
		toggleButtonLeft.classList.remove("open_nav");
		toggleButtonLeft.classList.add("close_nav");
		
	}else{
		leftBar.classList.remove("close_nav");
		leftBar.classList.add("open_nav");
		toggleButtonLeft.classList.remove("close_nav");
		toggleButtonLeft.classList.add("open_nav");
		if(toggleButton.classList.contains("open_nav")){
			rightBar.classList.remove("open_nav");
			rightBar.classList.add("close_nav");
			toggleButton.classList.remove("open_nav");
			toggleButton.classList.add("close_nav");
		}
	}
	e.stopPropagation();
	document.getSelection().removeAllRanges();
}

function myGenreListShow(){
	var myGenreButton=document.getElementById("myGenre").parentElement;
	myGenreButton.onmouseover=function(){
		var myGenreList=document.getElementById("list_my_genre");
		if(!myGenreList){
			return;
		}

		myGenreList.style.top=myGenreButton.offsetTop- document.getElementById("dash_leftbar").querySelector(".vertical-menu").scrollTop+"px";
		myGenreList.style.height=myGenreList.querySelectorAll("a").length*myGenreList.querySelector("a").offsetHeight+"px";
		
		var off= getOffsetTop(myGenreList);
		if(off+myGenreList.offsetHeight>window.innerHeight){
			myGenreList.style.height=window.innerHeight-off+"px";
		}
	};
}

function scrollBarMobile(){   //aggiunge lo scroll bar orizzontale per il mobile
	$(".card-deck").each(function(index){
		if(this.offsetWidth>=this.scrollWidth){
			return;
		}
		
		var scroll=document.createElement("div");
		scroll.className="scroll_bar";
		scroll.innerHTML='<div class="scroll_grip"></div>';
		
		scroll.childNodes[0].style.width=this.offsetWidth/this.scrollWidth*100+"%";
		scroll.childNodes[0].style.left=this.scrollLeft/this.scrollWidth*100+"%";
		this.parentElement.append(scroll);
		
		this.onscroll=function(){
			this.parentElement.querySelector(".scroll_grip").style.transform="translateX("+this.scrollLeft/this.scrollWidth*this.parentElement.querySelector(".scroll_bar").offsetWidth+"px)";
		}
	});	
}







/** funzioni di callback quando si carica la view centrale **/

/** playlist **/
function loadPlaylist(){
	myPlaylist.playlistPage();
}


function playlistPage(idPlaylist,posseduta){
	
	checkboxPlaylist=document.getElementById("checkboxPlaylistCondivisa");
	if(checkboxPlaylist!=null){ //funzione per l'autore della playlist
		idPlaylistPosseduta=idPlaylist;
		checkboxPlaylist.onclick=togglePlaylistCondivisa;
		var song=document.querySelectorAll(".brani_list tbody tr");
		for(var i=0; i<song.length; i++){
			song[i].oncontextmenu=function(e){
				var self=this;
				var id=this.querySelector(".play_song").getAttribute("data-song");
				setRightMenu(Array({text:"Rimuovi da playlist", func:function(){
					myPlaylist.removeBranoPlaylist(idPlaylist, id, function(res){
						if(res.code>0){
							self.remove();
							var playlistTot=document.getElementById("playlist_tot");
							var tot=playlistTot.querySelectorAll("span")[0];
							var min=playlistTot.querySelectorAll("span")[1];
							var sec=playlistTot.querySelectorAll("span")[2];
							var durata=self.querySelectorAll("td")[4].innerHTML.split(":");
							var newTot= parseInt(min.innerHTML)*60 + parseInt(sec.innerHTML)- parseInt(durata[0])*60 - parseInt(durata[1]);
							tot.innerHTML=parseInt(tot.innerHTML)-1;
							min.innerHTML=Math.floor(newTot/60);
							sec.innerHTML=newTot%60;
						}
					});
				}}));
				showRightMenu(e.pageX, e.pageY);
				e.preventDefault();
				
			}
			
		}
		
	}
	else{	//funzione per chi non è l'autore della playlist
		
	}
}
function togglePlaylistCondivisa(){
	_request(function(res){
		if(res.code>0){
			aggiornaCheckboxPlaylist();
		}
	},"togglePlaylistCondivisa",Array({key:"idPlaylist", value:idPlaylistPosseduta}));
}

function setCheckboxPlaylistUnchecked(){
	checkboxPlaylist.classList.remove("fa-check-square");
	checkboxPlaylist.classList.add("fa-square");
}
function aggiornaCheckboxPlaylist(){
	if(checkboxPlaylist.classList.contains("fa-check-square")){
		checkboxPlaylist.classList.remove("fa-check-square");
		checkboxPlaylist.classList.add("fa-square");
	}
	else{
		checkboxPlaylist.classList.remove("fa-square");
		checkboxPlaylist.classList.add("fa-check-square");
	}
}






/** playlist **/


/** artist page **/
function artistPage(id){
	idArtista=id;
	btn=document.getElementsByTagName("button")[0];
	if(btn.id=="addArtist"){
		setButtonAdd();
	}
	else{
		setButtonAdded();
	}
	
	icone=document.getElementsByClassName("btnSong");
	
	for(var i=0;i<icone.length;i++){
		if(icone[i].classList.contains("fa-check")) setBtnAdded(icone[i]);
		else setBtnAdd(icone[i]);
	}
	
}
function aggiungiArtista(){
	_request(function(res){
		if(res.code>0){
			aggiornaBottoneArtista(res);
		}
	},"aggiungiArtista",Array({key:"idArtist", value:idArtista}));	
}
function rimuoviArtista(){
	_request(function(res){
		if(res.code>0){
			aggiornaBottoneArtista(res);
		}
	},"rimuoviArtista",Array({key:"idArtist", value:idArtista}));	
}
function setButtonAdded(){
	btn.id="addedArtist";
	btn.innerHTML="<i class='far fa-check-circle'></i> Aggiunto in libreria";
	btn.onclick=rimuoviArtista;
	btn.onmouseover=function(){
		this.innerHTML="<i class='far fa-times-circle'></i> Rimuovi da libreria";
	}
	btn.onmouseout=function(){
		btn.innerHTML="<i class='far fa-check-circle'></i> Aggiunto in libreria";
	}
}
function setButtonAdd(){
	btn.id="addArtist";
	btn.innerHTML="<i class='far fa-plus-circle'></i> Aggiungi in libreria";
	btn.onclick=aggiungiArtista;
	btn.onmouseover=function(){
		this.innerHTML="<i class='far fa-plus-circle'></i> Aggiungi in libreria";
	}
	btn.onmouseout=function(){
		btn.innerHTML="<i class='far fa-plus-circle'></i> Aggiungi in libreria";
	}
}
function aggiornaBottoneArtista(){
	if(btn.id=="addArtist"){
		setButtonAdded();
	}
	else{
		setButtonAdd();
	}
}
/** artist page **/


/** album page **/
function albumPage(){
	icone=document.getElementsByClassName("btnSong");
	
	for(var i=0;i<icone.length;i++){
		if(icone[i].classList.contains("fa-check")) setBtnAdded(icone[i]);
		else setBtnAdd(icone[i]);
	}
	var song=document.querySelectorAll(".brani_list tbody tr");
	for(var i=0; i<song.length; i++){
		song[i].oncontextmenu=function(e){
			var self=this;
			var sub=document.createElement("ul");
			sub.className="sub_menu";
			myPlaylist.getPlaylist.forEach(function(playlist){
				var li=document.createElement("li");
				li.innerHTML=playlist.Titolo;
				li.onclick=function(){
					myPlaylist.addBranoPlaylist(playlist.idPlaylist, self.querySelector(".play_song").getAttribute("data-song"));
				}
				sub.appendChild(li);
				
			});
			setRightMenu(Array({text:"Aggiungi a playlist", func:function(){}, subMenu:sub}));
			showRightMenu(e.pageX, e.pageY);
			e.preventDefault();
			
		}
		
	}
	
}
/** album page **/


/** my brani page **/
function myBraniPage(){
	var song=document.querySelectorAll(".myBrani_list tbody tr");
	for(var i=0; i<song.length; i++){
		song[i].oncontextmenu=function(e){
			var self=this;
			var sub=document.createElement("ul");
			sub.className="sub_menu";
			myPlaylist.getPlaylist.forEach(function(playlist){
				var li=document.createElement("li");
				li.innerHTML=playlist.Titolo;
				li.onclick=function(){
					myPlaylist.addBranoPlaylist(playlist.idPlaylist, self.querySelector(".play_song").getAttribute("data-song"));
				}
				sub.appendChild(li);
				
			});
			setRightMenu(Array({text:"Aggiungi a playlist", func:function(){}, subMenu:sub}, 
				{text:"Rimuovi", func:function(){
					_request(function(res){
						if(res.code>0){
							self.remove();
							aggiornaGeneri(res.generi);
						}
					},"rimuoviBrano",Array({key:"idBrano", value:self.querySelector(".play_song").getAttribute("data-song")}));
				}}
				
			));
			showRightMenu(e.pageX, e.pageY);
			e.preventDefault();
			
		}
		
	}
}
/** my brani page **/










/** funzioni per gestione brani **/

/** 
	
	Ti permette di aggiungere e rimuovere brani nella tua libraria
	
	Usata un po' in tutte le pagine in cui sono presenti dei brani come la pagina di un artista
		l'album etc..
	
	La funzione aggiornaListaBottoni serve ad aggiornare il bottono che si clicca per aggiungere il brano
	
	La funzione aggiornaGeneri aggiorana la lista di my generi (Quando aggiungo un brano con un genere non posseduto
		o rimuovo l'ultimo brano di un genere che possedevo)

 **/

function setBtnAdded(bottone){
	bottone.classList.remove("fa-plus");
	bottone.classList.add("fa-check");
	bottone.onclick=rimuoviBrano;
	bottone.onmouseover=function(){
		this.classList.remove("fa-check");
		this.classList.add("fa-times");
	}
	bottone.onmouseout=function(){
		this.classList.remove("fa-times")
		this.classList.add("fa-check");
	}
}
function setBtnAdd(bottone){
	bottone.classList.remove("fa-check");
	bottone.classList.remove("fa-times");
	bottone.classList.add("fa-plus");
	bottone.onclick=aggiungiBrano;
	bottone.onmouseover=function(){
		return;
	}
	bottone.onmouseout=function(){
		return;
	}
}

function aggiungiBrano(){
	var idBrano=this.getAttribute("data-id");
	_request(function(res){
		if(res.code>0){
			aggiornaListaBottoni(idBrano);
			aggiornaGeneri(res.generi);
		}
	},"aggiungiBrano",Array({key:"idBrano", value:idBrano}));
}


function rimuoviBrano(){
	var idBrano=this.getAttribute("data-id");
	_request(function(res){
		if(res.code>0){
			aggiornaListaBottoni(idBrano);
			aggiornaGeneri(res.generi);
		}
	},"rimuoviBrano",Array({key:"idBrano", value:idBrano}));
}

function aggiornaListaBottoni(id){
	icona=document.querySelector('.btnSong[data-id="'+id+'"]');
	if(icona.classList.contains("fa-check") || icona.classList.contains("fa-times")) setBtnAdd(icona);
	else setBtnAdded(icona);
	
}
function aggiornaGeneri(gen){
	var lista=document.getElementById("list_my_genre");
	if(lista==null){
		lista=document.createElement("ul");
		lista.classList.add("list_genre_open");
		lista.id="list_my_genre";
		document.querySelector("#dash_leftbar a#myGenre").parentElement.appendChild(lista);
	}
	lista.innerHTML="";
	if(!Array.isArray(gen)){
		lista.remove();
		return;
	}
	gen.forEach(function(g){
		var a=document.createElement("a");
		a.classList.add("nav-link");
		a.classList.add("left_link");
		a.setAttribute("data-link","myGenre");
		a.setAttribute("data-addict",g.idGenere);
		a.innerHTML=g.Testo;
		lista.appendChild(a);
	});
	leftLink=aggiornaLeftLink();
}



/** funzioni per gestione brani **/








/** funzioni utili **/

// right menu
function showRightMenu(x, y){
	var rmenu=document.getElementById("rmenu");
	rmenu.style.display="block";
	rmenu.style.position="absolute";
	rmenu.style.top=y+"px";
	if(x+rmenu.offsetWidth>document.body.offsetWidth){
		rmenu.style.left=x-rmenu.offsetWidth+"px";
	}else{
		rmenu.style.left=x+"px";
	}
}

function setRightMenu(action){
	var rmenu=document.getElementById("rmenu");
	var option=rmenu.querySelectorAll("li");

	for (var i = 0; i < option.length; ++i) {
		option[i].remove();
	}
	action.forEach(function(elem){
		var li=document.createElement("li");
		li.onclick=elem.func;
		li.innerHTML=elem.text;
		if(elem.subMenu!="undefined" && elem.subMenu!=null){
			li.appendChild(elem.subMenu); 
		}
		rmenu.querySelector("ul").append(li);
	});
}



function _request(func, action, params=Array(), request="dashboard"){
	var _xhttp = new XMLHttpRequest();
	_xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				try{
					var resJson=JSON.parse(this.responseText);
					func(resJson);
				}catch(e){

				}
			}
		};
	_xhttp.open("POST", urlSite+"json.php?request="+request+"&action="+action+"&phpsess="+window.localStorage.getItem("phpsessid"), true);
	_xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	_xhttp.send(params.map(function(elem){return elem.key+"="+elem.value;}).join("&"));
}



function isDescendant(parent, child) {
     var node = child.parentNode;
     while (node != null) {
         if (node == parent) {
             return true;
         }
         node = node.parentNode;
     }
     return false;
}
function getOffsetTop(elem){
	var offsetTop = 0;
	do{
		if (!isNaN(elem.offsetTop)){
			offsetTop += elem.offsetTop;
		}
	}while(elem = elem.offsetParent);
	return offsetTop;
}


/** funzioni utili **/









/** Ricerca **/

function searchFunction(){
	var key=searchBar.value;
	if(typeof key!="undefined" && key!=""){
		searchBar.removeEventListener("input",searchFunction);
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var data=this.responseText;
				data=data.replaceAll('/images', 'images');
				dashCenter.innerHTML=data.replaceAll('images', urlSite+'images');
				

				rebuildAllLink();
				clearAllClicked();
				if(typeof key!="undefined" && key!=null && key!=searchBar.value){
					searchFunction();
				}
			}
			if(this.readyState == 4){
				searchBar.addEventListener("input",searchFunction);
			}
		};
		xhttp.open("GET", urlSite+"json.php?request=dashboard&action=search&key="+key+"&phpsess="+window.localStorage.getItem("phpsessid"), true);
		xhttp.send();
	}else{
		var state=window.history.state;
		if(typeof state.link!="undefined" && state.link!=null){
			loadCenterTemplateFunc(state.link, state.addict, state.exec, state.params, false);
			clearAllClicked();
			document.querySelector(".left_link[data-link='"+state.link+"']").classList.add("activated");
		}
	}
}

/** Ricerca **/



/**   Funzioni per la modifica dell'account  **/

//---------------------------------------//
//INIZIO FUNZIONI PER GESTIONE INFORMAZIONI ACCOUNT
//----------------------------------------//
function accountSettings(){ // funzione chiamata quando viene aperta la schermata delle informazioni dell'account
	input=document.getElementsByClassName("form-control");
	bottoneModifica=document.getElementById("buttonModifica");
	bottoneModifica.onclick=cliccaModifica;
	pass=document.getElementsByClassName("col")[2];
	repass=document.getElementsByClassName("col")[3];
	pass.style.display="none";
	repass.style.display="none";

	accountImage=document.getElementById("loaded_image");
	inputAccountImage=document.getElementById("inputAccountImage");
	
}
function cliccaModifica(){ //gestisce il click del pulsante modifica
		bottoneModifica.onclick=cliccaSalva;
		bottoneModifica.innerHTML="<i class='far fa-save'></i> Salva";
		pass.style.display="block";
		inputAccountImage.style.display="block";
		repass.style.display="block";
		for(var i=3;i<input.length;i++){
			input[i].disabled=false;
		}
		changed=Array();
		for(var i=0;i<7;i++)
			changed[i]=false;
		avviaControlliModifica();
}




function cliccaSalva(){ //gestisce il click del pulsante salva
	if(!checkPass(input[3].value))
		input[3].focus();
	else if(!checkConfPass(input[3].value,input[4].value))
		input[4].focus();
	else if(!checkNome(input[5].value))
		input[5].focus();
	else if(!checkCognome(input[6].value))
		input[6].focus();
	else if(!checkCellulare(input[8].value))
		input[8].focus();
	else if(!checkDate()){
		invalidateDate();
			if( input[9].value!=""){
				if(input[10].value=="") input[10].focus();
				else if(input[11].value=="") input[11].focus();
				
			}
			else
				input[9].focus();
	}
	else{
		var formData= new FormData();
		var toSend=false;
		if(changed[0]){
			if (inputAccountImage.files && inputAccountImage.files[0]){
				formData.append("image",inputAccountImage.files[0], inputAccountImage.files[0].name);
				toSend=true;
			}
		}
		if(changed[1]){
			formData.append("pass", input[3].value);
			formData.append("repass", input[4].value);
			toSend=true;
		}
		if(changed[2]){
			formData.append("nome", input[5].value);
			toSend=true;
		}
		if(changed[3]){
			formData.append("cognome", input[6].value);
			toSend=true;
		}
		if(changed[4]){
			formData.append("paese", input[7].value);
			toSend=true;
		}
		if(changed[5]){
			formData.append("cell", input[8].value);
			toSend=true;
		}
		if(changed[6]){
			var day=(input[9].value <10)? "0"+parseInt(input[9].value) : parseInt(input[9].value);
			var month=(input[10].value<10)? "0"+parseInt(input[10].value) : parseInt(input[10].value);
			var year=input[11].value;
			var data=year+"-"+month+"-"+day;
			formData.append("dataNascita", data);
			toSend=true;
		}
		if(!toSend){
			clearAll();
			bottoneModifica.onclick=cliccaModifica;
			bottoneModifica.innerHTML="<i class='fas fa-wrench'></i> Modifica";
			pass.style.display="none";
			repass.style.display="none";
			inputAccountImage.style.display="none";
			for(var i=3;i<input.length;i++){
				input[i].disabled=true;
			}
		}
		else{
			var link=urlSite+"json.php?request=dashboard&action=updateAccountSettings&phpsess="+window.localStorage.getItem("phpsessid");
			$.ajax({
				url: link,
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				method: 'POST',
				type: 'POST', // For jQuery < 1.9
				success: function(data){
					aggiornaModifica(data);
				}
			});
		}
	}
}
function aggiornaModifica(res){
	switch(res.code){
		case -2: //pass non valida
			setInvalid(3,"La password deve contenere da 8 a 20 caratteri, tra cui lettere e numeri, e non deve contenere caratteri speciali, spazi o emoji.");
			input[3].focus();
		break;
		case -3: //le pass non coincidono
			setInvalid(4,res.error);
			input[4].focus();
		break;
		case -11: //nome non valido
			setInvalid(5,res.error);
			input[5].focus();
		break;
		case -12: //cognome non valido
			setInvalid(6,res.error);
			input[6].focus();
		break;
		case -7: //paese non valido
			setInvalid(7,res.error);
			input[7].focus();
		break;
		case -13: //cellulare non valido
			setInvalid(8,"Numero non valido. Inserisci un numero di cellulare valido nel formato: '+Prefisso Numero' es. +39 3211234567");
			input[8].focus();
		break;
		case -5: //data non valida
			invalidateDate();
			document.getElementsByClassName("invalid-feedback")[6].innerHTML=res.error;
			input[9].focus();
		break;
		
		case 1: //success
			clearAll();
			bottoneModifica.onclick=cliccaModifica;
			bottoneModifica.innerHTML="<i class='fas fa-wrench'></i> Modifica";
			pass.style.display="none";
			repass.style.display="none";
			if(changed[0]){
				if (inputAccountImage.files && inputAccountImage.files[0]){
					document.querySelector(".user_img").style.backgroundImage= "url('"+accountImage.getAttribute("src")+"')";
				}
			}
			inputAccountImage.style.display="none";
			for(var i=3;i<input.length;i++){
				input[i].disabled=true;
			}
		break;
	}
	
}

function avviaControlliModifica(){ //inizializza le funzioni di controllo dei dati in input
	input[3].onchange=function(){
		checkPass(this.value);
		checkConfPass(this.value,input[4].value);
		changed[1]=true;
	};
	input[4].onchange=function(){
		checkConfPass(input[3].value,this.value);
		changed[1]=true;
	};
	input[5].onchange=function(){
		checkNome(this.value);	
		changed[2]=true;
	}
	input[6].onchange=function(){
		checkCognome(this.value);	
		changed[3]=true;
	}
	input[7].onchange=function(){
		setValid(7);
		changed[4]=true;
	}
	input[8].onchange=function(){
		checkCellulare(this.value);	
		changed[5]=true;
	}
	input[9].onchange=function(){
		fixDay(this);
		checkDate();
		changed[6]=true;
	};
	input[10].onchange=function(){
		changeMonth(this.value);
		checkDate();
		changed[6]=true;
	};
	input[11].onchange=function(){
		fixYear(this);
		checkDate();
		changed[6]=true;
	};
	inputAccountImage.onchange=function(){
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				accountImage.setAttribute("src", e.target.result);
				changed[0]=true;
			};
			reader.readAsDataURL(this.files[0]);
		}
	};
	
	
}
function checkPass(pass){ //verifica la correttezza della password, segnalando valid/invalid
	if(pass==""){
		clearValidInvalid(3);
		return true;
	}
	else{
	var re=/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,20})/;
		if(re.test(pass)){
			setValid(3);
			return true;
		}
		else
			setInvalid(3,"La nuova password deve contenere da 8 a 20 caratteri, tra cui lettere e numeri, e non deve contenere caratteri speciali, spazi o emoji.");
	}
	return false;
	
}
function checkConfPass(pass,repass){//verifica la correttezza della conferma password, segnalando valid/invalid
	if(repass==""){
		if(pass==""){
			clearValidInvalid(4);
			return true;
		}
		else{
			setInvalid(4,"Perfavore conferma la nuova la password");
			return false;
		}
	}
	else{
		if(pass.localeCompare(repass)==0){
			setValid(4);
			return true;
		}
		else
			setInvalid(4,"Le password non coincidono");
	}
	return false;
}

function checkNome(nome){//verifica la correttezza del nome, segnalando valid/invalid
	
	if(nome==""){
		clearValidInvalid(5);
		return true;
	}
	else{
		var re=/^([a-zA-Z]+ {0,1}[a-zA-Z]*)*$/;
		if(re.test(nome)){
			setValid(5);
			return true;
		}
		else
			setInvalid(5,"Inserisci un nome valido");
	}
	return false;
}
function checkCognome(cognome){//verifica la correttezza del cognome, segnalando valid/invalid
	
	if(cognome==""){
		clearValidInvalid(6);
		return true;
	}
	else{
		var re=/^([a-zA-Z]+ {0,1}[a-zA-Z]*)*$/;
		if(re.test(cognome)){
			setValid(6);
			return true;
		}
		else
			setInvalid(6,"Inserisci un cognome valido");
	}
	return false;
}
function checkCellulare(cellulare){//verifica la correttezza del numero di cellulare, segnalando valid/invalid
	
	if(cellulare==""){
		clearValidInvalid(8);
		return true;
	}
	else{
		var re=/^\+[0-9]{1,3} {0,1}[0-9]*$/;
		if(re.test(cellulare)){
			setValid(8);
			return true;
		}
		else
			setInvalid(8,"Inserisci un numero di cellulare valido nel formato: +Prefisso Numero es. +39 3211234567");
	}
	return false;
}

function fixDay(elem){ //funzione che sistema il giorno inserito qualora non sia corretto
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
function fixYear(elem){//funzione che sistema l'anno inserito qualora non sia corretto
	if( isNaN(elem.value)){
		elem.value="";
		return;
	}
	elem.value=parseInt(elem.value);
	if(elem.value<parseInt(elem.min))
		elem.value=parseInt(elem.min);
	if(elem.value>parseInt(elem.max))
		elem.value=parseInt(elem.max);
	if(input[10].value==2){
		if((elem.value%4)==0){
			input[9].max="29";
		}
		else
			input[9].max="28"
		fixDay(input[9]);
	}
}
function changeMonth(mese){//funzione che modifica i parametri del mese
	var max;
	switch(mese){
			case "4":
			case "6":
			case "9":
			case "11":
				max="30";
			break;
			case "2":
				if(input[11].value!="" && input[11].value%4!=0)
					max="28";
				else max="29";
			break;
			default:
				max="31";
	}
	input[9].max=max;
	fixDay(input[9]);
}
function checkDate(){ // funzione che controlla la correttezza della data di nascita inserita
	var g=input[9].value;
	var m=input[10].value;
	var a=input[11].value;
	if(a=="" || g=="" || m==""){
			normalizeDate();
			document.getElementsByClassName("invalid-feedback")[6].innerHTML="Campo Obbligatorio";
			return false;
	}
	else if(isMaggiorenne(parseInt(g),parseInt(m),parseInt(a))){
		validateDate();
		return true;
	}
	else {
		document.getElementsByClassName("invalid-feedback")[6].innerHTML="Per registrarti devi aver compiuto 16 anni in base al Regolamento Europeo in materia di Protezione dei Dati Personali (GDPR).";
		invalidateDate();
		return false;
	}
}

function invalidateDate(){//funzione che imposta a invalid i campi della data segnalando un errore
	for(var i=0;i<3;i++){
		if ( input[9+i].className.match(/(?:^|\s)is-invalid(?!\S)/) ) continue;
		if ( input[9+i].className.match(/(?:^|\s)is-valid(?!\S)/)) input[9+i].classList.remove('is-valid');
		input[9+i].classList.add('is-invalid');
		
	}
	document.getElementsByClassName("invalid-feedback")[6].style.visibility="visible";
	
}
function validateDate(){ // funzione che imposta a valid i campi della data
	for(var i=0;i<3;i++){
		if ( input[9+i].className.match(/(?:^|\s)is-valid(?!\S)/) ) continue;
		if ( input[9+i].className.match(/(?:^|\s)is-invalid(?!\S)/)) input[9+i].classList.remove('is-invalid');
		input[9+i].classList.add('is-valid');
	}
	document.getElementsByClassName("invalid-feedback")[6].style.visibility="hidden";
}
function normalizeDate(){	//funzione che cancella i segnali di valid/invalid dai campi della data
	
	clearValidInvalid(9);
	clearValidInvalid(10);
	clearValidInvalid(11);
}
function isMaggiorenne(giorno, mese, anno){ //funzione che verifica se la data inserita corrisponde a un età maggiore di 16 anni
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


function setValid(indice){ //segnala il campo indicato dall'indice come valido
		elemento=input[indice];
		if ( elemento.className.match(/(?:^|\s)is-valid(?!\S)/) ) return;
		if ( elemento.className.match(/(?:^|\s)is-invalid(?!\S)/))	elemento.classList.remove('is-invalid');
		elemento.classList.add('is-valid');
		document.getElementsByClassName("invalid-feedback")[indice-3].style.visibility="hidden";
	
}
function setInvalid(indice,message){//segnala il campo indicato dall'indice come non-valido e mostra l'errore corrispondente
		elemento=input[indice];
		document.getElementsByClassName("invalid-feedback")[indice-3].innerHTML=message;
		if ( elemento.className.match(/(?:^|\s)is-invalid(?!\S)/) ) return;
		if ( elemento.className.match(/(?:^|\s)is-valid(?!\S)/)) elemento.classList.remove('is-valid');
		elemento.classList.add('is-invalid');
		document.getElementsByClassName("invalid-feedback")[indice-3].style.visibility="visible";
	
}
function clearValidInvalid(indice){ //pulisce il campo dell'indice specificato dai segnali di valid/invalid
	elemento=input[indice];
	if ( elemento.className.match(/(?:^|\s)is-invalid(?!\S)/) ){
		elemento.classList.remove('is-invalid');
		document.getElementsByClassName("invalid-feedback")[indice-3].style.visibility="hidden";
	}
	if ( elemento.className.match(/(?:^|\s)is-valid(?!\S)/))
		elemento.classList.remove('is-valid');
}
function clearAll(){ //pulisce tutti i campi di input dai segnali di valid/invalid
	for(var i=3;i<12;i++)
		clearValidInvalid(i);
}



