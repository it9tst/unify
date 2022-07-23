function Friend(){
	function defineConstant(value){
		return {value:value,writable:false,configurable: false,enumerable: false};
	}

	Object.defineProperties(Friend.prototype, {
		"ID_SEARCH":defineConstant("friend_nav"),
		"ID_SEARCH_INPUT":defineConstant("searchFriend"),
		"ID_SEARCH_RESULT":defineConstant("searchedFriend"),
		"ID_DASH_NOTIFY":defineConstant("dash_notify"),
	});


	var _searchNav=document.getElementById(this.ID_SEARCH); // nav bar degli amici

	var _friendContatti=_searchNav.querySelectorAll("nav")[0];
	var _friendOnline=_searchNav.querySelectorAll("nav")[1];

	var _searhInput=_searchNav.querySelector("#"+this.ID_SEARCH_INPUT);


	var _searhResult=_searchNav.querySelector("#"+this.ID_SEARCH_RESULT);
	var _dashNotify=document.querySelector("#"+this.ID_DASH_NOTIFY);
	var _addSearchButton= _searhResult.querySelector("i");

	var _rmenu=document.getElementById("rmenu");

	var _blocked=Array();
	var _offline=Array();
	var _online=Array();
	var _sent=Array();
	var _waiting=Array();
	var _account=Array();

	var _xhttp = new XMLHttpRequest();
	_searhInput.onkeypress=function(e){
		if(e.keyCode==13){
			_request(_aggiornaSearchFriend, "searchFriend",Array({key:"name", value:$(this).val()}));
		}
	};
	_addSearchButton.onclick=function(e){
		var id=this.getAttribute("data-id");
		if(id!=null && typeof id!="undefined"){
			_request(_aggiornaAddFriend, "addFriend", Array({key:"id", value:id}));
		}
	}
	function _aggiornaAddFriend(res){
		if(res.code>0){
			_updateFriend();
			_searhResult.querySelector("span").innerHTML=res.message;
			_searhResult.querySelector("i").style.display="none";
			setTimeout(function(){
				_searhResult.style.display="none";
				_searhInput.value="";
			}, 2000);
		}

	}


	function _aggiornaSearchFriend(res){
		if(res.code>0){
			_searhResult.querySelector("span").innerHTML=res.Utente.Nickname;
			_searhResult.querySelector("i").style.display="flex";
			_searhResult.querySelector("i").setAttribute("data-id", res.Utente.IdAccount);
		}else{
			_searhResult.querySelector("span").innerHTML=res.error;
			_searhResult.querySelector("i").style.display="none";
			_searhResult.querySelector("i").setAttribute("data-id", "");
		}
		_searhResult.style.display="flex";
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
		_xhttp.open("POST", urlSite+"/json.php?request=friend&action="+action+"&phpsess="+window.localStorage.getItem("phpsessid"), true);
		_xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		_xhttp.send(params.map(function(elem){return elem.key+"="+elem.value;}).join("&"));
	}
	function _updateFriend(){
		_request(_updateFriendBar, "retriveFriend");
	}

	function _updateFriendBar(res){
		res=res.result;
		var resBlocked=res.blocked;
		var resOffline=res.offline;
		var resSent=res.sent;
		var resWaiting=res.waiting;
		var resOnline=res.online;

		var blocked=Array();
		var offline=Array();
		var sent=Array();
		var waiting=Array();
		var online=Array();

		var countAccount=0;
		var account=Array();
		resBlocked.forEach(function(elem){
			if(_account.indexOf(elem.idAccount)>-1){
				if(_blocked.indexOf(elem.idAccount)<0){
					_searchNav.querySelector('div[data-id="'+elem.idAccount+'"]').remove();
					var divAccount=_buildAccountDiv({"name":elem.Nickname, "id":elem.idAccount, "Foto":elem.Foto}, "Bloccato");
					_friendContatti.append(divAccount);
					divAccount.oncontextmenu=function(e){
						_setRightMenu(Array({text:"Sblocca", func:function(){
							_sbloccaAmico(elem.idAccount);
						}}, {text:"Rimuovi", func:function(){
							_rimuoviAmico(elem.idAccount);
						}},));
						_showRightMenu(e.pageX, e.pageY);
						e.preventDefault();
						e.stopPropagation();
					}
				}
			}else{
				var divAccount=_buildAccountDiv({"name":elem.Nickname, "id":elem.idAccount, "Foto":elem.Foto}, "Bloccato");
				_friendContatti.append(divAccount);
				divAccount.oncontextmenu=function(e){
					_setRightMenu(Array({text:"Sblocca", func:function(){
						_sbloccaAmico(elem.idAccount);
					}}, {text:"Rimuovi", func:function(){
						_rimuoviAmico(elem.idAccount);
					}},));
					_showRightMenu(e.pageX, e.pageY);
					e.preventDefault();
						e.stopPropagation();
				}
			}
			blocked.push(elem.idAccount);
			countAccount++;
			account.push(elem.idAccount);
		});
		resOffline.forEach(function(elem){
			if(_account.indexOf(elem.idAccount)>-1){
				if(_offline.indexOf(elem.idAccount)<0){
					_searchNav.querySelector('div[data-id="'+elem.idAccount+'"]').remove();
					var divAccount=_buildAccountDiv({"name":elem.Nickname, "id":elem.idAccount, "Foto":elem.Foto}, "Offline");
					_friendContatti.append(divAccount);
					divAccount.oncontextmenu=function(e){
						var id=this.getAttribute("data-id");
						_setRightMenu(Array({text:"Blocca", func:function(){
							_bloccaAmico(elem.idAccount);
						}}, {text:"Rimuovi", func:function(){
							_rimuoviAmico(elem.idAccount);
						}}, {text:"Playlist", func:function(){
							loadCenterTemplateFunc("friendPlaylistPage", id);
						}}));
						_showRightMenu(e.pageX, e.pageY);
						e.preventDefault();
						e.stopPropagation();
					}
				}
			}else{
				var divAccount=_buildAccountDiv({"name":elem.Nickname, "id":elem.idAccount, "Foto":elem.Foto}, "Offline");
				_friendContatti.append(divAccount);
				divAccount.oncontextmenu=function(e){
					var id=this.getAttribute("data-id");
					_setRightMenu(Array({text:"Blocca", func:function(){
						_bloccaAmico(elem.idAccount);
					}}, {text:"Rimuovi", func:function(){
						_rimuoviAmico(elem.idAccount);
					}},{text:"Playlist", func:function(){
						loadCenterTemplateFunc("friendPlaylistPage", id);
					}}));
					_showRightMenu(e.pageX, e.pageY);
					e.preventDefault();
						e.stopPropagation();
				}
			}
			offline.push(elem.idAccount);
			account.push(elem.idAccount);
			countAccount++;
		});
		resSent.forEach(function(elem){
			if(_account.indexOf(elem.idAccount)>-1){
				if(_sent.indexOf(elem.idAccount)<0){
					_searchNav.querySelector('div[data-id="'+elem.idAccount+'"]').remove();
					var divAccount=_buildAccountDiv({"name":elem.Nickname, "id":elem.idAccount, "Foto":elem.Foto}, "Richiesta in corso");
					_friendContatti.append(divAccount);
					divAccount.oncontextmenu=function(e){
						_setRightMenu(Array({text:"Blocca", func:function(){
							_bloccaAmico(elem.idAccount);
						}}, {text:"Rimuovi", func:function(){
							_rimuoviAmico(elem.idAccount);
						}},));
						_showRightMenu(e.pageX, e.pageY);
						e.preventDefault();
						e.stopPropagation();
					}
				}

			}else{
				var divAccount=_buildAccountDiv({"name":elem.Nickname, "id":elem.idAccount, "Foto":elem.Foto}, "Richiesta in corso");
				_friendContatti.append(divAccount);
				divAccount.oncontextmenu=function(e){
					_setRightMenu(Array({text:"Blocca", func:function(){
						_bloccaAmico(elem.idAccount);
					}}, {text:"Rimuovi", func:function(){
						_rimuoviAmico(elem.idAccount);
					}},));
					_showRightMenu(e.pageX, e.pageY);
					e.preventDefault();
						e.stopPropagation();
				}
			}
			sent.push(elem.idAccount);
			account.push(elem.idAccount);
			countAccount++;
		});
		
		resWaiting.forEach(function(elem){
			if(_account.indexOf(elem.idAccount)>-1){
				if(_waiting.indexOf(elem.idAccount)<0){
					_searchNav.querySelector('div[data-id="'+elem.idAccount+'"]').remove();
					var divAccount=_buildAccountDiv({"name":elem.Nickname, "id":elem.idAccount, "Foto":elem.Foto}, "Ti ha chiesto l'amicizia");
					_dashNotify.querySelector(".dropdown-menu").append(divAccount.cloneNode(true));
					_friendContatti.append(divAccount);
					divAccount.oncontextmenu=function(e){
						_setRightMenu(Array({text:"Accetta", func:function(){
							_accettaAmico(elem.idAccount);
						}},{text:"Blocca", func:function(){
							_bloccaAmico(elem.idAccount);
						}}, {text:"Rimuovi", func:function(){
							_rimuoviAmico(elem.idAccount);
						}},));
						_showRightMenu(e.pageX, e.pageY);
						e.preventDefault();
						e.stopPropagation();
					}
				}
			}else{
				var divAccount=_buildAccountDiv({"name":elem.Nickname, "id":elem.idAccount, "Foto":elem.Foto}, "Ti ha chiesto l'amicizia");
				_dashNotify.querySelector(".dropdown-menu").append(divAccount.cloneNode(true));
				_friendContatti.append(divAccount);
				divAccount.oncontextmenu=function(e){
					_setRightMenu(Array({text:"Accetta", func:function(){
						_accettaAmico(elem.idAccount);
					}},{text:"Blocca", func:function(){
						_bloccaAmico(elem.idAccount);
					}}, {text:"Rimuovi", func:function(){
						_rimuoviAmico(elem.idAccount);
					}},));
					_showRightMenu(e.pageX, e.pageY);
					e.preventDefault();
					e.stopPropagation();
				}
			}
			waiting.push(elem.idAccount);
			account.push(elem.idAccount);
			countAccount++;
		});
		_dashNotify.setAttribute("data-after",resWaiting.length);
		
		_dashNotify.querySelectorAll(".dropdown-menu .user_prof").forEach(function(elem){
			if(waiting.indexOf(elem.getAttribute("data-id"))<0){
				elem.remove();
			}
		});
		
		
		resOnline.forEach(function(elem){
			if(_account.indexOf(elem.idAccount)>-1){
				if(_online.indexOf(elem.idAccount)<0){
					_searchNav.querySelector('div[data-id="'+elem.idAccount+'"]').remove();
					var divAccount=_buildAccountDiv({"name":elem.Nickname, "id":elem.idAccount, "Foto":elem.Foto}, "", {"name":elem.Titolo, "id":elem.idBrano, "idArtisti":elem.idArtisti, "artisti":elem.Artisti});
					_friendOnline.append(divAccount);
					divAccount.oncontextmenu=function(e){
						var id=this.getAttribute("data-id");
						_setRightMenu(Array({text:"Rimuovi", func:function(){
							_rimuoviAmico(elem.idAccount);
						}},{text:"Blocca", func:function(){
							_bloccaAmico(elem.idAccount);
						}}, {text:"Playlist", func:function(){
							loadCenterTemplateFunc("friendPlaylistPage", id);
						}}));
						_showRightMenu(e.pageX, e.pageY);
						e.preventDefault();
						e.stopPropagation();
					}
				}else{
					var playSongSpan= _searchNav.querySelector('div[data-id="'+elem.idAccount+'"] .play_song');
					var loadArtistSpan= _searchNav.querySelector('div[data-id="'+elem.idAccount+'"] .artist_link');
					if(typeof playSongSpan !="undefined" && playSongSpan!=null && typeof loadArtistSpan !="undefined" && loadArtistSpan!=null){
						playSongSpan.innerHTML=elem.Titolo;
						playSongSpan.setAttribute("data-song", elem.idBrano);

						
						loadArtistSpan.innerHTML="";
						
						var artisti=elem.Artisti.split(",");
						var idArtisti=elem.idArtisti.split(",");
						
						
						for(var i=0;i<artisti.length;i++){
							var a= document.createElement("a");
							a.setAttribute("class","center_link");
							a.setAttribute("data-link","artist");
							a.setAttribute("data-exec","artistPage");
							a.setAttribute("data-params",idArtisti[i]);
							a.setAttribute("data-addict",idArtisti[i]);
							a.style.display="inline";
							a.style.padding="0";
							a.innerHTML=artisti[i];
							a.addEventListener('click', loadCenterTemplate, false);
							loadArtistSpan.appendChild(a);
							if(i==artisti.length-1)
								break;
							loadArtistSpan.innerHTML+=", ";
						}
					}else{
						_searchNav.querySelector('div[data-id="'+elem.idAccount+'"]').remove();
						var divAccount=_buildAccountDiv({"name":elem.Nickname, "id":elem.idAccount, "Foto":elem.Foto}, "", {"name":elem.Titolo, "id":elem.idBrano, "idArtisti":elem.idArtisti, "artisti":elem.Artisti});
						_friendOnline.append(divAccount);
						divAccount.oncontextmenu=function(e){
							var id=this.getAttribute("data-id");
							_setRightMenu(Array({text:"Rimuovi", func:function(){
								_rimuoviAmico(elem.idAccount);
							}},{text:"Blocca", func:function(){
								_bloccaAmico(elem.idAccount);
							}}, {text:"Playlist", func:function(){
								loadCenterTemplateFunc("friendPlaylistPage", id);
							}}));
							_showRightMenu(e.pageX, e.pageY);
							e.preventDefault();
							e.stopPropagation();
						}
					}
				}
			}else{
				var divAccount=_buildAccountDiv({"name":elem.Nickname, "id":elem.idAccount, "Foto":elem.Foto}, "", {"name":elem.Titolo, "id":elem.idBrano, "idArtisti":elem.idArtisti, "artisti":elem.Artisti});
				_friendOnline.append(divAccount);
				divAccount.oncontextmenu=function(e){
					var id=this.getAttribute("data-id");
					_setRightMenu(Array({text:"Rimuovi", func:function(){
						_rimuoviAmico(elem.idAccount);
					}},{text:"Blocca", func:function(){
						_bloccaAmico(elem.idAccount);
					}}, {text:"Playlist", func:function(){
						loadCenterTemplateFunc("friendPlaylistPage", id);
					}}));
					_showRightMenu(e.pageX, e.pageY);
					e.preventDefault();
						e.stopPropagation();
				}
			}
			online.push(elem.idAccount);
			account.push(elem.idAccount);
		});
		if(online.length>0){
			_friendOnline.style.display="block";
		}else{
			_friendOnline.style.display="none";
		}
		if(countAccount>0){
			_friendContatti.style.display="block";
		}else{
			_friendContatti.style.display="none";
		}

		_account.forEach(function(elem){
			if(account.indexOf(elem)<0){
				_searchNav.querySelector('div[data-id="'+elem+'"]').remove();
			}
		});
		_account=account;
		_blocked=blocked;
		_offline=offline;
		_sent=sent;
		_waiting=waiting;
		_online=online;
	}

	function _rimuoviAmico(id){
		_request(function(res){_updateFriend();}, "rimuoviAmico", Array({key:"id", value:id}));

	}
	function _bloccaAmico(id){
		_request(function(){_updateFriend();}, "bloccaAmico", Array({key:"id", value:id}));

	}
	function _accettaAmico(id){
		_request(function(res){_updateFriend();}, "accettaAmico", Array({key:"id", value:id}));

	}
	function _sbloccaAmico(id){
		_request(function(res){_updateFriend();}, "sbloccaAmico", Array({key:"id", value:id}));

	}
	function _buildAccountDiv(account, textAccount, songAccount){
		var elem= document.createElement("div");
		elem.setAttribute("data-id", account.id);
		elem.classList.add("user_prof");
		elem.innerHTML='<div class="avatar_friend"><div class="friend_img"></div></div><div class="avatar_info"><span class="avatar_username">'+account.name+'</span><span class="avatar_text">'+textAccount+'</span></div>';
		if(typeof account.Foto!="undefined" && account.Foto!=null){
			elem.querySelector(".friend_img").style.backgroundImage="url('"+urlSite+"images/accounts/"+account.Foto+"')";
		}
		if(typeof songAccount !="undefined" && songAccount.name!=null && songAccount.id!=null ){
			var sp1= document.createElement("span");
			var sp2= document.createElement("span");
			sp1.setAttribute("class","avatar_text play_song");
			sp1.onclick=playSongClick;
			sp1.setAttribute("data-song",songAccount.id);
			sp1.innerHTML=songAccount.name;
			
			sp2.setAttribute("class","avatar_text");
			
			var artisti=songAccount.artisti.split(",");
			var idArtisti=songAccount.idArtisti.split(",");
			
			sp2.setAttribute("class","avatar_text artist_link");
			
			for(var i=0;i<artisti.length;i++){
				var a= document.createElement("a");
				a.setAttribute("class","center_link");
				a.setAttribute("data-link","artist");
				a.setAttribute("data-exec","artistPage");
				a.setAttribute("data-params",idArtisti[i]);
				a.setAttribute("data-addict",idArtisti[i]);
				a.style.display="inline";
				a.style.padding="0";
				a.innerHTML=artisti[i];
				
				a.addEventListener('click', loadCenterTemplate, false);
				sp2.appendChild(a);
				if(i==artisti.length-1)
					break;
				sp2.innerHTML+=", ";
			}
			
			elem.querySelector(".avatar_info").insertBefore(sp2, elem.querySelector(".avatar_text"));
			elem.querySelector(".avatar_info").insertBefore(sp1, elem.querySelector(".avatar_text"));
		}		
		return elem;
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


	_updateFriend();
	setInterval(_updateFriend,7000);
}
