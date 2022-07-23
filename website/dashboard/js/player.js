var Player;
window.addEventListener("load", function(){
	Player = (function(){
		function Player(){
			function defineConstant(value){
				return {value:value,writable:false,configurable: false,enumerable: false};
			}

			Object.defineProperties(Player.prototype, {
				"ID_PLAYER_WEB":defineConstant("player_web"), // il player

				// la barra di progresso
				"ID_PLAYER_BAR_BACK":defineConstant("player_bar_back"),
				"ID_PLAYER_BAR_PROGRESS":defineConstant("player_bar_progress"),
				"ID_PLAYER_BAR_GRIP":defineConstant("player_bar_grip"),
				"ID_PLAYER_TIME":defineConstant("player_time"),

				// la barra del volume
				"ID_VOLUME_BAR_BACK":defineConstant("volume_bar_back"),
				"ID_VOLUME_BAR_PROGRESS":defineConstant("volume_bar_progress"),
				"ID_VOLUME_BAR_GRIP":defineConstant("volume_bar_grip"),

				// icona del volume
				"ID_VOLUME_ICON":defineConstant("volume_icon"),

				// bottoni
				"ID_BACKWARD_BUTTON":defineConstant("backward_button"),
				"ID_PLAY_PAUSE_BUTTON":defineConstant("play_pause_button"),
				"ID_FORWARD_BUTTON":defineConstant("forward_button"),
				"ID_RANDOM_BUTTON":defineConstant("random_button"),
				"ID_REPEAT_BUTTON":defineConstant("repeat_button"),

				// immagine player
				"ID_COVER_PLAYER":defineConstant("cover_player"),

				// titolo canzone
				"ID_SONG_TITLE":defineConstant("song_title"),
				"ID_ARTIST_TITLE":defineConstant("artist_title"),
				
				"ID_PLAYER_QUEUE":defineConstant("player_queue"),
				"ID_QUEUE_BUTTON":defineConstant("queue_button"),

			});
			var _playerWeb=document.getElementById(this.ID_PLAYER_WEB);

			var _playerBarBack=document.getElementById(this.ID_PLAYER_BAR_BACK);
			var _playerBarProgress=document.getElementById(this.ID_PLAYER_BAR_PROGRESS);
			var _playerBarGrip=document.getElementById(this.ID_PLAYER_BAR_GRIP);
			var _playerTime=document.getElementById(this.ID_PLAYER_TIME);

			var _volumeBarBack=document.getElementById(this.ID_VOLUME_BAR_BACK);
			var _volumeBarProgress=document.getElementById(this.ID_VOLUME_BAR_PROGRESS);
			var _volumeBarGrip=document.getElementById(this.ID_VOLUME_BAR_GRIP);

			var _volumeIcon=document.getElementById(this.ID_VOLUME_ICON);

			var _backwardButton=document.getElementById(this.ID_BACKWARD_BUTTON);
			var _playPauseButton=document.getElementById(this.ID_PLAY_PAUSE_BUTTON);
			var _forwardButton=document.getElementById(this.ID_FORWARD_BUTTON);
			var _randomButton=document.getElementById(this.ID_RANDOM_BUTTON);
			var _repeatButton=document.getElementById(this.ID_REPEAT_BUTTON);

			var _coverPlayer=document.getElementById(this.ID_COVER_PLAYER);
			var _coverPlayer=document.getElementById(this.ID_COVER_PLAYER);
			var _coverPlayer=document.getElementById(this.ID_COVER_PLAYER);
			var _songTitle=document.getElementById(this.ID_SONG_TITLE);
			var _artistTitle=document.getElementById(this.ID_ARTIST_TITLE);
			
			var _playerQueue=document.getElementById(this.ID_PLAYER_QUEUE);
			var _playerQueueList=_playerQueue.querySelector("ul.player_playlist");
			var _queueButton=document.getElementById(this.ID_QUEUE_BUTTON);

			var _playerAudio=new Audio;
			_playerAudio.loop=false;
			_playerAudio.preload="none";
			var _playerGripMouseDown=false;
			var _volumeGripMouseDown=false;
			var _playingMusic=false;
			var _link="/json.php?request=player";
			var _linkAlbum= "images/albums/";
			var _idSong=-1;
			var _idAlbum=-1;
			var _idArtist=-1;
			var _idPlaylist=-1;
			var _duration;
			var _currentTime;
			var _playing=false;
			var _actualeVolume=100;
			var _muted=false;
			var _loop=0;
			var _disabled=false;
			var _coda;
			var _codaShuffle;
			var _shuffle=false;

			Object.defineProperties(Player.prototype, {
				"loadSong":{set:function(){return;}, get:function(){return _load}},
				"loadPlaylist":{set:function(){return;}, get:function(){return _loadPlaylist}},
				"loadAlbum":{set:function(){return;}, get:function(){return _loadAlbum}},
				"loadArtist":{set:function(){return;}, get:function(){return _loadArtist}},
				"idSong":{set:function(){return;}, get:function(){return _idSong}},
				"idPlaylist":{set:function(){return;}, get:function(){return _idPlaylist}},
				"idAlbum":{set:function(){return;}, get:function(){return _idAlbum}},
				"idArtist":{set:function(){return;}, get:function(){return _idArtist}},
				"isPlaying":{set:function(){return;}, get:function(){return !_playerAudio.paused}},

			});
			//var actualObj= this; //lo uso per risolvere il problema del this dentro gli eventi
			//clicco sulla barra si sposta il grip
			function CreateGrip(barBack, barGrip, barProgress, func1, func2, func3, func4, disableFunction=function(){return false;}){
				function getOffsetLeft(elem){  // prende l'offset di un elemento risalendo la catena di parentela
					var offsetLeft = 0;
					do{
						if (!isNaN(elem.offsetLeft)){
							offsetLeft += elem.offsetLeft;
						}
					}while(elem = elem.offsetParent);
					return offsetLeft;
				}
				var _gripMouseDown=false;
				Object.defineProperties(this, {
					"gripMouseDown":{set:function(){return;}, get:function(){return _gripMouseDown}}
				});
				
				barBack.addEventListener('mousedown', function(e){
					if(disableFunction()){
						return;
					}
					if(e.target!==barGrip){
						var perc= Math.round(e.offsetX/this.offsetWidth*1000000)/10000;
						barProgress.style.width=perc+"%";
						barGrip.style.left=perc+"%";
						func1(perc);
					}
					_gripMouseDown=true;
					document.body.classList.add("disable_select");
				}, true);
				barBack.addEventListener('touchstart', function(e){
					if(disableFunction()){
						return;
					}
					if(e.target!==barGrip){
						var perc= Math.round((e.changedTouches[0].clientX-getOffsetLeft(e.target))/this.offsetWidth*1000000)/10000;
						barProgress.style.width=perc+"%";
						barGrip.style.left=perc+"%";
						func1(perc);
					}
					_gripMouseDown=true;
					document.body.classList.add("disable_select");
				}, true);
				barGrip.onclick=function(e){
					e.preventDefault();
					e.stopPropagation();
				};
				barGrip.addEventListener('mousedown', function(e){
					if(disableFunction()){
						return;
					}
					_gripMouseDown=true;
					func1(-1);
					document.body.classList.add("disable_select");
					e.stopPropagation();
				}, true);
				barGrip.addEventListener('touchstart', function(e){
					if(disableFunction()){
						return;
					}
					_gripMouseDown=true;
					func1(-1);
					document.body.classList.add("disable_select");
					e.stopPropagation();
				}, true);

				document.addEventListener('mouseup', function(e) {
					if(disableFunction()){
						return;
					}
					if(_gripMouseDown){
						var per=barGrip.style.left;
						per=per.substring(0, per.length - 1);
						func3(parseFloat(per));
					}
					func2(per);
					_gripMouseDown=false;
					document.body.classList.remove("disable_select");
				}, true);
				document.addEventListener('touchend', function(e) {
					if(disableFunction()){
						return;
					}
					if(_gripMouseDown){
						var per=barGrip.style.left;
						per=per.substring(0, per.length - 1);
						func3(parseFloat(per));
					}
					func2(per);
					_gripMouseDown=false;
					document.body.classList.remove("disable_select");
				}, true);

				document.addEventListener('mousemove', function(e) {
					e.preventDefault();
					if(disableFunction()){
						return;
					}
					if(_gripMouseDown){
						var offsetEle=getOffsetLeft(barBack);

						var perc= Math.round((e.clientX-offsetEle)/barBack.offsetWidth*1000000)/10000;
						if(perc<0){
							perc=0;
						}
						if(perc>100){
							perc=100;
						}
						func4(perc);
						barProgress.style.width=perc+"%";
						barGrip.style.left=perc+"%";
					}
				}, true);
				document.addEventListener('touchmove', function(e) {
					
					if(disableFunction()){
						return;
					}
					if(_gripMouseDown){
						var offsetEle=getOffsetLeft(barBack);

						var perc= Math.round((e.changedTouches[0].clientX-offsetEle)/barBack.offsetWidth*1000000)/10000;
						if(perc<0){
							perc=0;
						}
						if(perc>100){
							perc=100;
						}
						func4(perc);
						barProgress.style.width=perc+"%";
						barGrip.style.left=perc+"%";
					}
				}, true);
			}
			var _playerGripObj=new CreateGrip(_playerBarBack, _playerBarGrip, _playerBarProgress, function(){
						if(!_playerAudio.paused){
							_playing=true;
						}
						_playerAudio.pause();
					},function(){
						if(_playing){
							_playerAudio.play();
						}
					}, _seekPlayer, function(per){
						var sec=_duration*per/100;
						var minutes=Math.floor(sec/60);
						if(minutes<10){
							minutes="0"+minutes;
						}
						var seconds=Math.floor(sec%60);
						if(seconds<10){
							seconds="0"+seconds;
						}
						var pt=_playerTime.innerHTML;
						_playerTime.innerHTML=minutes+":"+seconds+" / "+pt.split("/")[1].trim();
					}, function(){
						return _disabled;
					});
			var _volumeGripObj=new CreateGrip(_volumeBarBack, _volumeBarGrip, _volumeBarProgress, _changeVolume ,function(){

					}, function(){}, _changeVolume);

			function _load(id){
				var albActive=document.querySelector(".play_album.activated-album");
				if(_idSong==id){
					if(!_playing){
						_playerAudio.play();
						_playPauseButton.classList.remove("fa-play");
						_playPauseButton.classList.add("fa-pause");
						_playing=true;
						if(albActive){
							albActive.classList.remove("fa-play-circle");
							albActive.classList.add("fa-pause-circle");
						}
						return true;
					}else{
						_playing=false;
						_playerAudio.pause();
						_playPauseButton.classList.add("fa-play");
						_playPauseButton.classList.remove("fa-pause");
						if(albActive){
							albActive.classList.add("fa-play-circle");
							albActive.classList.remove("fa-pause-circle");
						}
						return false;
					}
				}
				_idPlaylist=-1;
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						if(this.responseText) {
							try {
								_parseResponse(JSON.parse(this.responseText));
							} catch(e) {
								_disablePlayer();
							}
						}

					}
				};
				xhttp.open("GET",_link+"&action=info&id="+id, true);
				xhttp.send();
				return true;
			}

			function _loadPlaylist(id){
				var trActive=document.querySelector("tr.activated-song");
				if(_idPlaylist==id){
					if(!_playing){
						_playerAudio.play();
						_playPauseButton.classList.remove("fa-play");
						_playPauseButton.classList.add("fa-pause");
						_playing=true;
						if(trActive){
							trActive.querySelector("td i").classList.remove("fa-play-circle");
							trActive.querySelector("td i").classList.add("fa-pause-circle");
						}
						return true;
					}else{
						_playing=false;
						_playerAudio.pause();
						_playPauseButton.classList.add("fa-play");
						_playPauseButton.classList.remove("fa-pause");
						if(trActive){
							trActive.querySelector("td i").classList.add("fa-play-circle");
							trActive.querySelector("td i").classList.remove("fa-pause-circle");
						}
						return false;
					}
				}
				_idPlaylist=id;
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						if(this.responseText) {
							try {
								_parseResponse(JSON.parse(this.responseText));
							} catch(e) {
								_disablePlayer();
							}
						}

					}
				};
				xhttp.open("GET",_link+"&action=info&type=playlist&id="+id, true);
				xhttp.send();
				return true;
			}
			function _loadAlbum(id){
				var trActive=document.querySelector("tr.activated-song");
				if(_idAlbum==id){
					if(!_playing){
						_playerAudio.play();
						_playPauseButton.classList.remove("fa-play");
						_playPauseButton.classList.add("fa-pause");
						_playing=true;
						if(trActive){
							trActive.querySelector("td i").classList.remove("fa-play-circle");
							trActive.querySelector("td i").classList.add("fa-pause-circle");
						}
						return true;
					}else{
						_playing=false;
						_playerAudio.pause();
						_playPauseButton.classList.add("fa-play");
						_playPauseButton.classList.remove("fa-pause");
						if(trActive){
							trActive.querySelector("td i").classList.add("fa-play-circle");
							trActive.querySelector("td i").classList.remove("fa-pause-circle");
						}
						return false;
					}
				}
				_idAlbum=id;
				_idPlaylist=-1;
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						if(this.responseText) {
							try {
								_parseResponse(JSON.parse(this.responseText));
							} catch(e) {
								_disablePlayer();
							}
						}

					}
				};
				xhttp.open("GET",_link+"&action=info&type=album&id="+id, true);
				xhttp.send();
				return true;
			}
			function _loadArtist(id){
				var trActive=document.querySelector("tr.activated-song");
				if(_idArtist==id){
					if(!_playing){
						_playerAudio.play();
						_playPauseButton.classList.remove("fa-play");
						_playPauseButton.classList.add("fa-pause");
						_playing=true;
						if(trActive){
							trActive.querySelector("td i").classList.remove("fa-play-circle");
							trActive.querySelector("td i").classList.add("fa-pause-circle");
						}
						return true;
					}else{
						_playing=false;
						_playerAudio.pause();
						_playPauseButton.classList.add("fa-play");
						_playPauseButton.classList.remove("fa-pause");
						if(trActive){
							trActive.querySelector("td i").classList.add("fa-play-circle");
							trActive.querySelector("td i").classList.remove("fa-pause-circle");
						}
						return false;
					}
				}
				_idArtist=id;
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						if(this.responseText) {
							try {
								_parseResponse(JSON.parse(this.responseText));
							} catch(e) {
								_disablePlayer();
							}
						}

					}
				};
				xhttp.open("GET",_link+"&action=info&type=artist&id="+id, true);
				xhttp.send();
				return true;
			}
			
			
			
			function _parseResponse(json){
				_coverPlayer.style.backgroundImage="url('"+_linkAlbum+json.Image+"')";
				_songTitle.innerHTML=json.Titolo;
				_artistTitle.innerHTML=json.Artisti.join(", ");
				_playerAudio.src="/json.php?request=player&idSong="+json.Id;
				_setDuration(json.Durata);
				_idSong=json.Id;
				_idAlbum=json.IdAlbum;
				_playerAudio.play();
				_disabled=false;
				if(json.Coda!="undefined" && json.Coda!=null){
					_coda=json.Coda;
					_fillCoda();
				}
				
				_reloadTr();
				
			}
			
			function _reloadTr(){
				var trOld=document.querySelector("tr.activated-song");
				if(trOld){
					trOld.classList.remove("activated-song");
					trOld.querySelector("td i").classList.add("fa-play-circle");
					trOld.querySelector("td i").classList.remove("fa-pause-circle");
					trOld.style.backgroundColor="";
				}
				var trSong=document.querySelector(".play_song[data-song='"+_idSong+"']");
				if(trSong){
					trSong=trSong.parentElement.parentElement
					if(trSong.tagName=="TR"){
						trSong.classList.add("activated-song");
						trSong.querySelector("td i").classList.remove("fa-play-circle");
						trSong.querySelector("td i").classList.add("fa-pause-circle");
						trSong.style.backgroundColor="rgba(0,0,0,.075)";
					}
				}
			}
			
			
			function _disablePlayer(){
				_coverPlayer.style.backgroundImage="url('"+_linkAlbum+'defaultAlbum.jpg'+"')";
				_songTitle.innerHTML=" - ";
				_artistTitle.innerHTML=" - ";
				_playerAudio.src="";
				_playerTime.innerHTML="00:00";
				_idSong=-1;
				_idAlbum=-1;
				_playPauseButton.classList.add("fa-play");
				_playPauseButton.classList.remove("fa-pause");
				_repeatButton.classList.remove("fa-repeat-1");
				_repeatButton.classList.add("fa-repeat");
				_disabled=true;
				_playing=false;
				_loop=0;
				var trOld=document.querySelector("tr.activated-song");
				if(trOld){
					trOld.classList.remove("activated-song");
					trOld.querySelector("td i").classList.add("fa-play-circle");
					trOld.querySelector("td i").classList.remove("fa-pause-circle");
					trOld.style.backgroundColor="";
				}
			}

			_playerAudio.addEventListener("timeupdate", function (e){
				if(!(_playerGripObj.gripMouseDown)){
					perc=this.currentTime/_duration*100;
					_setCurrentTime(this.currentTime);
					_playerBarProgress.style.width=perc+"%";
					_playerBarGrip.style.left=perc+"%";
				}

			}, true);
			_playerAudio.addEventListener("loadedmetadata", function (e){
				this.play();

			}, true);

			_playerAudio.addEventListener("ended", function (e){
				if(_loop==0){
					_forWard();
					return;
				}
				if(_loop==1){
					_playPauseButton.classList.add("fa-play");
					_playPauseButton.classList.remove("fa-pause");
					return;
				}
				if(_loop==2){
					if(_coda!="undefined" && _coda!=null){
						if(_coda.length==1){
							
						}else{
							if(_coda[_coda.length-1].idBrano==_idSong){
								_loadCoda(_coda[0].idBrano);
							}else{
								_forWard();
							}
						}

					}
					
				}
				
				
			}, true);
			_playerAudio.addEventListener("play", function (e){
				_playing=true;
				_playPauseButton.classList.remove("fa-play");
				_playPauseButton.classList.add("fa-pause");

			}, true);
			function _setDuration(duration){
				_duration=parseInt(duration);
				var minutes=Math.floor(_duration/60);
				if(minutes<10){
					minutes="0"+minutes;
				}
				var seconds=Math.floor(_duration%60);
				if(seconds<10){
					seconds="0"+seconds;
				}
				var pt=_playerTime.innerHTML;

				_playerTime.innerHTML="00:00 / "+minutes+":"+seconds;
			}

			function _setCurrentTime(time){
				_currentTime=parseInt(time);
				var minutes=Math.floor(_currentTime/60);
				if(minutes<10){
					minutes="0"+minutes;
				}
				var seconds=Math.floor(_currentTime%60);
				if(seconds<10){
					seconds="0"+seconds;
				}
				var pt=_playerTime.innerHTML;
				if(pt.split("/").length>1)
					_playerTime.innerHTML=minutes+":"+seconds+" / "+pt.split("/")[1].trim();
			}

			function _seekPlayer(perc){
				var sec=_duration*perc/100;
				_playerAudio.currentTime=sec;
			}

			_playPauseButton.onclick=function(e){
				if(_disabled)
					return;
				
				var trActive=document.querySelector("tr.activated-song");
				var albActive=document.querySelector(".play_album.activated-album");
				if(!_playing){
					_playerAudio.play();
					_playPauseButton.classList.remove("fa-play");
					_playPauseButton.classList.add("fa-pause");
					_playing=true;
					if(trActive){
						trActive.querySelector("td i").classList.remove("fa-play-circle");
						trActive.querySelector("td i").classList.add("fa-pause-circle");
					}
					if(albActive){
						albActive.classList.remove("fa-play-circle");
						albActive.classList.add("fa-pause-circle");
					}
				}else{
					_playing=false;
					_playerAudio.pause();
					_playPauseButton.classList.add("fa-play");
					_playPauseButton.classList.remove("fa-pause");
					if(trActive){
						trActive.querySelector("td i").classList.add("fa-play-circle");
						trActive.querySelector("td i").classList.remove("fa-pause-circle");
					}
					if(albActive){
						albActive.classList.add("fa-play-circle");
						albActive.classList.remove("fa-pause-circle");
					}
				}
			};
			function _playPause(e){
				if(!_playing){
					_playerAudio.play();
					_playPauseButton.classList.remove("fa-play");
					_playPauseButton.classList.add("fa-pause");
					_playing=true;
				}else{
					_playing=false;
					_playerAudio.pause();
					_playPauseButton.classList.add("fa-play");
					_playPauseButton.classList.remove("fa-pause");
				}
			}
			function _changeVolume(perc){
				if(perc<0)
					return;
				_volumeIcon.classList.remove("fa-volume-up");
				_volumeIcon.classList.remove("fa-volume-down");
				_volumeIcon.classList.remove("fa-volume-middle");
				_volumeIcon.classList.remove("fa-volume-mute");
				if(perc==0){
					_volumeIcon.classList.add("fa-volume-mute");
				}else if(perc>66){
					_muted=false;
					_volumeIcon.classList.add("fa-volume-up");
				}else if(perc>33){
					_muted=false;
					_volumeIcon.classList.add("fa-volume-middle");
				}else{
					_volumeIcon.classList.add("fa-volume-down");
					_muted=false;
				}
				_playerAudio.volume=perc/100;
			}

			_volumeIcon.onclick= function(){
				var per=_volumeBarGrip.style.left;
				per=per.substring(0, per.length - 1);
				per=parseFloat(per);
				if(isNaN(per) || per>100 || per<0){
					per=100;
				}
				if(_muted){
					_changeVolume(_actualeVolume);
					_muted=false;
					_volumeBarProgress.style.width=_actualeVolume+"%";
					_volumeBarGrip.style.left=_actualeVolume+"%";
				}else{
					_muted=true;
					_actualeVolume=per;
					_changeVolume(0);
					_volumeBarProgress.style.width="0%";
					_volumeBarGrip.style.left="0%";
				}
			};
			window.addEventListener("beforeunload", function(e){
				_playerAudio.pause();
				_playerAudio.src="";
				_playerAudio.remove();
			});
			_repeatButton.addEventListener("click", function(){
				if(_disabled)
					return;
				if(_loop==0){
					_loop=1;
					_playerAudio.loop=true;
					this.classList.add("fa-repeat-1");
					this.classList.remove("fa-repeat");
					this.classList.remove("activated");
					return;
				}
				if(_loop==1){
					_loop=2;
					if(_coda.length==1){
						_playerAudio.loop=true;
					}else{
						_playerAudio.loop=false;
					}
					this.classList.remove("fa-repeat-1");
					this.classList.add("fa-repeat");
					this.classList.add("activated");
					return;
				}				
				_playerAudio.loop=false;
				_loop=0;
				this.classList.remove("fa-repeat-1");
				this.classList.add("fa-repeat");
				this.classList.remove("activated");
			});

			_backwardButton.onclick=_backWard;
			function _backWard(){
				if(_playerAudio.currentTime>2){
					_playerAudio.currentTime=0;
				}else{
					if(_coda!="undefined" && _coda!=null){
						var BreakException = {};
						try {
							_coda.forEach(function(li, index){
								if(_idSong==li.idBrano){
									if(index!=0){
										_playerAudio.pause();
										_playerAudio.src="";
										_playerAudio.remove();
										_parseResponse({
											Artisti: _coda[index-1].Artisti.split(","),
											Coda: _coda,
											Id:_coda[index-1].idBrano,
											Titolo:_coda[index-1].Titolo,
											Image:_coda[index-1].Image,
											idAlbum:_coda[index-1].idAlbum,
											Durata:_coda[index-1].Durata,
											
										});
										throw BreakException;
									}
								}
							});
							
						}catch (e) {
							;
						}
						_playerAudio.currentTime=0;
					}else{
						_playerAudio.currentTime=0;
					}
						
				}
			}
			document.onkeydown=function(e){
				switch(e.keyCode){
					case 179:
						_playPause();
						e.preventDefault();
					break;
					case 178:
						_disablePlayer();
						e.preventDefault();
					break;
					case 177:
						_backWard();
						e.preventDefault();
					break;
					case 176:
						_forWard();
						e.preventDefault();
					break;
					case 174:
						var volume=_playerAudio.volume*100-2;
						if(volume<0)
							volume=0;
						_changeVolume(volume);
						_volumeBarProgress.style.width=volume+"%";
						_volumeBarGrip.style.left=volume+"%";
						e.preventDefault();
					break;
					case 175:
						var volume=_playerAudio.volume*100+2;
						if(volume>100)
							volume=100;
						_changeVolume(volume);
						_volumeBarProgress.style.width=volume+"%";
						_volumeBarGrip.style.left=volume+"%";
						e.preventDefault();
					break;
				}
			}
			_queueButton.onclick=function(e){
				if(_disabled){
					return;
				}
				if(_playerQueueList.classList.contains("opened")){
					_playerQueueList.classList.remove("opened");
					_playerQueueList.classList.add("closed");
				}else{
					_playerQueueList.classList.add("opened");
					_playerQueueList.classList.remove("closed");
				}
				e.stopPropagation();
			}
			
			document.addEventListener('click', function(e){
				_playerQueueList.classList.remove("opened");
				_playerQueueList.classList.add("closed");
			});
			_playerQueueList.onclick=function(e){
				e.stopPropagation();
			}
			
			function _fillCoda(){
				_playerQueueList.innerHTML="";
				if(_coda!="undefined" && _coda!=null){
					var printOk=false;
					_coda.forEach(function(li){
						if(_idSong==li.idBrano)
							printOk=true;
						
						if(!printOk)
							return;
						
						var elem=_createQueueElement(li.Titolo, li.Artisti, li.Image);
						elem.onclick=function(){
							_loadCoda(li.idBrano);
						}
						_playerQueueList.appendChild(elem);
					});
				}
				var li=_playerQueueList.querySelector("li");
				li.style.backgroundColor="#f2f2f2";
				li.querySelector("i.fas").classList.remove("fa-play");
				li.querySelector("i.fas").classList.add("fa-volume-up");
			}
			function _createQueueElement(titolo, artista, image){
				var li=document.createElement("li");
				li.innerHTML='<img src="images/albums/'+image+'"><span class="music">'+titolo+'</span><span class="artist">'+artista.replace(",", ", ")+'</span><span style="flex-grow:1"></span><i class="fas fa-play" style="color:#a3a3a3"></i>';
				return li;
			}
			
			_forwardButton.onclick=_forWard;
			
			function _forWard(){
				if(_coda!="undefined" && _coda!=null){
					var BreakException = {};
					try {
						
						_coda.forEach(function(li, index){
								if(_idSong==li.idBrano){
									if(index!=_coda.length-1){
										_playerAudio.pause();
										_playerAudio.src="";
										_playerAudio.remove();
										_parseResponse({
											Artisti: _coda[index+1].Artisti.split(","),
											Coda: _coda,
											Id:_coda[index+1].idBrano,
											Titolo:_coda[index+1].Titolo,
											Image:_coda[index+1].Image,
											idAlbum:_coda[index+1].idAlbum,
											Durata:_coda[index+1].Durata,
											
										});
										throw BreakException;
									}else{
										_disablePlayer();
									}
								}
							});
						}catch (e) {
							;
						}
				}
			}
			
			function _loadCoda(id){
				if(_coda!="undefined" && _coda!=null){
					var BreakException = {};
					try {
						
						_coda.forEach(function(li, index){
								if(id==li.idBrano){
									_playerAudio.pause();
									_playerAudio.src="";
									_playerAudio.remove();
									_parseResponse({
										Artisti: li.Artisti.split(","),
										Coda: _coda,
										Id:li.idBrano,
										Titolo:li.Titolo,
										Image:li.Image,
										idAlbum:li.idAlbum,
										Durata:li.Durata,
									});
									throw BreakException;
								}
							});
					}catch (e) {
						;
					}
				}
			}
			
			
			_randomButton.onclick=function(){
				if(!_shuffle){
					_codaShuffle=_coda.slice();
					_coda=Array();
					var codaTmp=_codaShuffle.slice();
					var len=codaTmp.length
					for(var i=0; i<len; i++){
						var scelta=Math.floor(Math.random() * codaTmp.length);
						_coda[i]=codaTmp.splice(scelta, 1)[0];
						if(_coda[i].idBrano==_idSong && i!=0){
							var tmp=JSON.parse(JSON.stringify(_coda[i]));
							_coda[i]=_coda[0];
							_coda[0]=tmp;
						}
					}
					_fillCoda();
					_shuffle=true;
					_randomButton.classList.add("activated");
					
				}else{
					_coda=_codaShuffle;
					_fillCoda();
					_shuffle=false;
					_randomButton.classList.remove("activated");
				}
				
				
			}
			
			
			
			
			_disablePlayer();
		}
		Object.defineProperties(Player.prototype, {
				"start":{
					value:function(){
						if(this.playerAudio.readyState==4){
							this.playerAudio.play();
						}
					},
					writable:false,
					configurable: false,
					enumerable: true
				},
				"pause":{
					value:function(){
						if(this.playerAudio.readyState==4){
							this.playerAudio.pause();
						}
					},
					writable:false,
					configurable: false,
					enumerable: true
				},
				"load":{
					value:function(id){
						return this.loadSong(id);
					},
					writable:false,
					configurable: false,
					enumerable: true
				}
			});
		function defineConstant(value){
			return {value:value,writable:false,configurable: false,enumerable: false};
		}
		var instance;
		return {
			getInstance: function(){
				if (instance == null) {
					instance = new Player();
					instance.constructor = null;
				}
				return instance;
			}
	   };
	})().getInstance();
})
