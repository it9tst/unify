function Playlist(){
	function defineConstant(value){
		return {value:value,writable:false,configurable: false,enumerable: false};
	}

	Object.defineProperties(Playlist.prototype, {
		"ID_ADD_PLAYLIST":defineConstant("buttonAddPlaylist"),
		"ID_CARD_PLAYLIST":defineConstant("card_playlist"),
		"ID_PLAYLIST_RECENTI":defineConstant("playlist_recenti"),
	});
	var _playlist;
	var _xhttp = new XMLHttpRequest();
	var _playlistRecenti=document.getElementById(this.ID_PLAYLIST_RECENTI);
	function _retrivePlaylist(){
		_request(_retrivePlaylistCallback, "retrivePlaylist");
		
	}
	function _retrivePlaylistCallback(res){
		_playlist=res.Playlist;
		var max=3;
		if(_playlist.length<3){
			max=_playlist.length;
		}
		for(var i=0; i<max; i++){
			_playlistRecenti.appendChild(_createPlaylistRecenti(_playlist[i]));
		}
		
	}
	function _createPlaylistRecenti(playlist){
		var a=document.createElement("a");
		a.className="nav-link left_link";
		a.setAttribute("data-link", "playlist");
		a.setAttribute("data-exec", "playlistPage");
		a.setAttribute("data-params", playlist.idPlaylist);
		a.setAttribute("data-addict", playlist.idPlaylist);
		a.innerHTML='<div class="nav_elem1"><i class="fab fa-itunes-note"></i></div><div class="nav_elem2">'+playlist.Titolo+'</div>';
		a.onclick=loadCenterTemplate;
		return a;
	}
	
	
	Object.defineProperties(Playlist.prototype, {
		"getPlaylist":{set:function(){return;}, get:function(){return _playlist}},
	});
	this.addBranoPlaylist= function(idPlaylist, idBrano, func){
		_request(func, "addBranoPlaylist", Array({key:"idPlaylist", value:idPlaylist},{key:"idBrano", value:idBrano}));
	}
	this.removeBranoPlaylist= function(idPlaylist, idBrano, func){
		_request(func, "removeBranoPlaylist", Array({key:"idPlaylist", value:idPlaylist},{key:"idBrano", value:idBrano}));
	}
	_retrivePlaylist();
	this.playlistPage= function(){
		var _buttonAddPlaylist=document.getElementById(this.ID_ADD_PLAYLIST);
		var _cardPlaylist=document.getElementById(this.ID_CARD_PLAYLIST);
		var _actualFocus;
		var _rmenu=document.getElementById("rmenu");

		
		_buttonAddPlaylist.onclick=function(){
			_request(_createCardCallback, "createPlaylist")
		}
		
		function _createCardCallback(res){
			var card=_createCardPlaylist(res.idPlaylist);
			_cardPlaylist.appendChild(card);
			card.querySelector(".playlist_title").setAttribute("contenteditable","true");
			card.querySelector(".playlist_title").focus();
			card.querySelector(".playlist_title").ondblclick=doubleClickCard;
			card.oncontextmenu=_rightClick;
			_playlistRecenti.insertBefore(_createPlaylistRecenti({Titolo:"Playlist", idPlaylist:res.idPlaylist}), _playlistRecenti.querySelector("a"));
			if(_playlistRecenti.querySelectorAll("a").length>3){
				_playlistRecenti.querySelectorAll("a")[3].remove();
			}
		}
		
		function _createCardPlaylist(id){
			var elem= document.createElement("div");
			elem.className="card card_album";
			elem.setAttribute("data-id", id);
			elem.innerHTML='<div class="playlist_img_ext"><div class="playlist_img"><img src="/images/albums/defaultAlbum.jpg" style="width:100%"></div><div class="button_play"><i class="fas fa-play-circle play_album" data-album=""></i></div></div><div class="container album_container"><span class="playlist_title">Playlist</span></div></div>'
			return elem;
		}
		
		var elem=_cardPlaylist.getElementsByClassName("playlist_title");
		for(var i=0;i<elem.length;i++){
			elem[i].ondblclick=doubleClickCard;
			elem[i].parentElement.parentElement.oncontextmenu=_rightClick;
		}
		window.addEventListener("click", function(){
			var elem=_cardPlaylist.querySelectorAll('.playlist_title[contenteditable="true"]');
			for(var i=0;i<elem.length;i++){
				elem[i].setAttribute("contenteditable","false");
			}
			if(typeof _actualFocus!="undefined" && _actualFocus!=null){
				var id=_actualFocus.parentElement.parentElement.getAttribute("data-id");
				var name=_actualFocus.innerHTML;
				_request(function(){
					var a=_playlistRecenti.querySelector("a[data-params='"+id+"']");
					if(typeof a!="undefined" && a!=null){
						a.querySelector(".nav_elem2").innerHTML=name;
					}
					
				}, "changePlaylistName", Array({key:"id", value:id},{key:"titolo", value:name}));
				_actualFocus=null;
			}
		});
		
		
		
		function doubleClickCard(e){
			this.setAttribute("contenteditable","true");
			this.focus();
			_actualFocus=this;
			e.preventDefault();
			e.stopPropagation();
		}
		
		function _showRightMenu(x, y){
			_rmenu.style.display="block";
			_rmenu.style.position="absolute";
			_rmenu.style.top=y+"px";
			if(x+_rmenu.offsetWidth>document.body.offsetWidth){
				_rmenu.style.left=x-_rmenu.offsetWidth+"px";
			}else{
				_rmenu.style.left=x+"px";
			}
		}
		function _setRightMenu(action){
			var option=_rmenu.querySelectorAll("li");

			for (var i = 0; i < option.length; ++i) {
				option[i].remove();
			}
			action.forEach(function(elem){
				var li=document.createElement("li");
				li.onclick=elem.func;
				li.innerHTML=elem.text;
				_rmenu.querySelector("ul").append(li);
			});
		}
		function _rightClick(e){
			var self=this;
			_setRightMenu(Array({text:"Elimina", func:function(){
				_rimuoviPlaylist(self);
			}}));
			_showRightMenu(e.pageX, e.pageY);
			e.preventDefault();
		}
		function _rimuoviPlaylist(card){
			var id=card.getAttribute("data-id");
			_request(function(res){
				if(res.code>0){
					card.remove();
					var a=_playlistRecenti.querySelector("a[data-params='"+id+"']");
					if(typeof a!="undefined" && a!=null){
						a.remove();
					}
					
				}
			}, "removePlaylist", Array({key:"id", value:id}));
		}
	}
	function _request(func, action, params=Array()){
		_xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					try{
						var resJson=JSON.parse(this.responseText);
						func(resJson);
					}catch(e){
						
					}
				}
			};
		_xhttp.open("POST", "/json.php?request=dashboard&action="+action, true);
		_xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		_xhttp.send(params.map(function(elem){return elem.key+"="+elem.value;}).join("&"));
	}
}