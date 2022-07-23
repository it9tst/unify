window.onload=function(){
	$(".nav_link").click(navLinkClick);

}

function navLinkClick(){
	if(this.classList.contains("active")){
		return;
	}

	var dataLink=this.getAttribute("data-link");
	var dataAction=this.getAttribute("data-action");
	var dataAddict=this.getAttribute("data-addict");


	var oldAct=document.querySelector(".nav_link.active");
	if(oldAct!=null){
		oldAct.classList.remove("active");
	}
	this.classList.add("active");

	if(dataLink){
		$(".overlay").show();
		loadCenterTemplate(dataLink, dataAction, dataAddict);
	}

}
function loadCenterTemplate(dataLink, dataAction= null, dataAddict= null){
	var link="/"+dataLink+"/";
	if(dataAction){
		link+=dataAction+"/";
	}
	if(dataAddict){
		link+=dataAddict+"/";
	}
	$.ajax({url: link})
	.done(function( data ) {
		$(".overlay").hide();
		$("#second_box_admin").html(data);
		if(dataAction){
			eval("load"+dataLink+dataAction+"()");
		}else{
			eval("load"+dataLink+"()");
		}
		$(".nav_link").click(navLinkClick);
	});

}
function loadartista(){
	$("#search_button").click(function(e){
		search($("#search_val").val(), "artista", "Artisti", createArtistRow);
	});
	$("#search_val").keypress(function(e){
		if(e.keyCode==13){
			search($("#search_val").val(), "artista", "Artisti", createArtistRow);
		}
	});
}
function loadalbum(){
	$("#search_button").click(function(e){
		search($("#search_val").val(), "album", "Album", createAlbumRow);
	});
	$("#search_val").keypress(function(e){
		if(e.keyCode==13){
			search($("#search_val").val(), "album", "Album", createAlbumRow);
		}
	});
}
function loadbrano(){
	$("#search_button").click(function(e){
		search($("#search_val").val(), "brano", "Brani", createBranoRow);
	});
	$("#search_val").keypress(function(e){
		if(e.keyCode==13){
			search($("#search_val").val(), "brano", "Brani", createBranoRow);
		}
	});
}


function clearTable(){
	$("#table_row").html("");
}


// artista
function loadartistadelete(){
	loadCenterTemplate("artista");
}

function loadartistaadd(){
	$("#add_artista_form").submit(function(e){
		var formData = new FormData();
		formData.append("Nome",$("#inputNomeArtista").val());
		formData.append("DataNascita",$("#inputDataNascitaArtista").val());
		formData.append("Informazioni",$("#inputInfoArtista").val());
		formData.append("submit",true);
		var image=$("#add_artista_form input[name='image']")[0];
		if (image.files && image.files[0]) {
			formData.append("image", image.files[0], image.files[0].name)
		}
		request("artista", "add",function(res){
				if(res.code<0){
					$(".invalid-feedback").html(res.error);
					$(".invalid-feedback").show();
				}else{
					loadCenterTemplate("artista");
				}
			}, formData);
		event.preventDefault();
	});
	$("#add_artista_form input[name='image']").change(function(e) {
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$("#loaded_image").attr("src", e.target.result);
			};
			reader.readAsDataURL(this.files[0]);
		}
	});
}

function loadartistaedit(){
	var edited=false;
	var editedPhoto=false;
	var editedName=false;
	var editedInfo=false;
	var editedData=false;
	$("#add_artista_form").submit(function(e){
		e.preventDefault();
		if(!edited){
			return;
		}
		var formData = new FormData();
		if(editedName){
			formData.append("Name",$("#inputNomeArtista").val());
		}
		if(editedInfo){
			formData.append("Informazioni",$("#inputInfoArtista").val());
		}
		if(editedData){
			formData.append("DataNascita",$("#inputDataNascitaArtista").val());
		}
		var id=$("#inputIdArtista").val();
		formData.append("submit",true);
		var image=$("#add_artista_form input[name='image']")[0];
		if (image.files && image.files[0] && editedPhoto) {
			formData.append("Image", image.files[0], image.files[0].name)
		}
		request("artista", "edit/"+id,function(res){
				if(res.code<0){
					$(".invalid-feedback").html(res.error);
					$(".invalid-feedback").show();
				}else{
					loadCenterTemplate("artista");
				}
			}, formData);
	});
	$("#add_artista_form input[name='image']").change(function(e){
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$("#loaded_image").attr("src", e.target.result);
				edited=true;
				editedPhoto=true;
			};
			reader.readAsDataURL(this.files[0]);
		}
	});

	$("#inputNomeArtista").on("input", function(){edited=true; editedName=true;});
	$("#inputDataNascitaArtista").on("input", function(){edited=true; editedData=true;})
	$("#inputInfoArtista").on("input", function(){edited=true; editedInfo=true;})
}
// artista






// album
function loadalbumdelete(){
	loadCenterTemplate("album");
}


function loadalbumadd(){
	var artisti=Array();
	var brani=Array();
	var generi=Array();
	$("#addArtistaAlbum").click(function(){
		var id=$("#selectArtistaAlbum").val();
		var nome=$("#selectArtistaAlbum option:selected").text();
		if(artisti.indexOf(id)<0){
			var div=document.createElement("div");
			div.style.display="float";
			div.innerHTML='<i class="fas fa-minus-circle" style="color:red; float:left; margin-right:20px; margin-top:3px" data-id="'+id+'"></i><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">'+nome+'</div>';
			$("#tableArtistiAlbum").append(div);
			$(div).find("i").click(function(){
				var index = artisti.indexOf($(this).attr("data-id"));
				if (index > -1) {
				  artisti.splice(index, 1);
				}
				$(this).parent().remove();
			});
			artisti.push(id);
		}
	});
	$("#addBrano").click(function(){
		var id=$("#selectBrano").val();
		var nome=$("#selectBrano option:selected").text();
		if(brani.indexOf(id)<0){
			var div=document.createElement("div");
			div.style.display="float";
			div.innerHTML='<i class="fas fa-minus-circle" style="color:red; float:left; margin-right:20px; margin-top:3px" data-id="'+id+'"></i><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">'+nome+'</div>';
			$("#tableBrano").append(div);
			$(div).find("i").click(function(){
				var index = brani.indexOf($(this).attr("data-id"));
				if (index > -1) {
				  brani.splice(index, 1);
				}
				$(this).parent().remove();
			});
			brani.push(id);
		}
	});
	$("#addGenereAlbum").click(function(){
		var id=$("#selectGenereAlbum").val();
		var nome=$("#selectGenereAlbum option:selected").text();
		if(generi.indexOf(id)<0){
			var div=document.createElement("div");
			div.style.display="float";
			div.innerHTML='<i class="fas fa-minus-circle" style="color:red; float:left; margin-right:20px; margin-top:3px" data-id="'+id+'"></i><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">'+nome+'</div>';
			$("#tableGeneriAlbum").append(div);
			$(div).find("i").click(function(){
				var index = generi.indexOf($(this).attr("data-id"));
				if (index > -1) {
				  generi.splice(index, 1);
				}
				$(this).parent().remove();
			});
			generi.push(id);
		}
	});

	$("#add_album_form").submit(function(e){
		var formData = new FormData();
		formData.append("Nome",$("#inputTitoloAlbum").val());
		formData.append("Anno",$("#inputAnnoAlbum").val());
		formData.append("Etichetta",$("#inputEtichettaAlbum").val());
		for(var i=0;i<artisti.length;i++){
			formData.append("Artisti[]",artisti[i]);
		}
		for(var i=0;i<brani.length;i++){
			formData.append("Brani[]",brani[i]);
		}
		for(var i=0;i<generi.length;i++){
			formData.append("Generi[]",generi[i]);
		}
		formData.append("submit",true);
		var image=$("#add_album_form input[name='image']")[0];
		if (image.files && image.files[0]) {
			formData.append("Image", image.files[0], image.files[0].name)
		}
		request("album", "add",function(res){
				if(res.code<0){
					$(".invalid-feedback").html(res.error);
					$(".invalid-feedback").show();
				}else{
					loadCenterTemplate("album");
				}
			}, formData);
		event.preventDefault();
	});
	$("#add_album_form input[name='image']").change(function(e) {
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$("#loaded_image").attr("src", e.target.result);
			};
			reader.readAsDataURL(this.files[0]);
		}
	});
}

function loadalbumedit(){
	var editedPhoto=false;
	var editedName=false;
	var editedData=false;
	var artistiEdit=false;
	var braniEdit=false;
	var generiEdit=false;
	var etichetta=false;

	var artisti=Array();
	var brani=Array();
	var generi=Array();

	$('#tableArtistiAlbum div i').each(function(){artisti.push($(this).attr("data-id"))});
	$('#tableBrano div i').each(function(){brani.push($(this).attr("data-id"))});
	$('#tableGeneriAlbum div i').each(function(){generi.push($(this).attr("data-id"))});
	
	
	$('#tableGeneriAlbum div i').click(function(){
		var index = generi.indexOf($(this).attr("data-id"));
		if (index > -1) {
		  generi.splice(index, 1);
		}
		$(this).parent().remove();
		generiEdit=true;
	});

	$('#tableArtistiAlbum div i').click(function(){
		var index = artisti.indexOf($(this).attr("data-id"));
		if (index > -1) {
		  artisti.splice(index, 1);
		}
		$(this).parent().remove();
		artistiEdit=true;
	});
	$('#tableBrano div i').click(function(){
		var index = brani.indexOf($(this).attr("data-id"));
		if (index > -1) {
		  brani.splice(index, 1);
		}
		$(this).parent().remove();
		braniEdit=true;
	});


	$("#addGenereAlbum").click(function(){
		var id=$("#selectGenereAlbum").val();
		var nome=$("#selectGenereAlbum option:selected").text();
		if(generi.indexOf(id)<0){
			var div=document.createElement("div");
			div.style.display="float";
			div.innerHTML='<i class="fas fa-minus-circle" style="color:red; float:left; margin-right:20px; margin-top:3px" data-id="'+id+'"></i><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">'+nome+'</div>';
			$("#tableGeneriAlbum").append(div);
			$(div).find("i").click(function(){
				var index = generi.indexOf($(this).attr("data-id"));
				if (index > -1) {
				  generi.splice(index, 1);
				}
				$(this).parent().remove();
			});
			generi.push(id);
			generiEdit=true;
		}
	});
	$("#addArtistaAlbum").click(function(){
		var id=$("#selectArtistaAlbum").val();
		var nome=$("#selectArtistaAlbum option:selected").text();
		if(artisti.indexOf(id)<0){
			var div=document.createElement("div");
			div.style.display="float";
			div.innerHTML='<i class="fas fa-minus-circle" style="color:red; float:left; margin-right:20px; margin-top:3px" data-id="'+id+'"></i><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">'+nome+'</div>';
			$("#tableArtistiAlbum").append(div);
			$(div).find("i").click(function(){
				var index = artisti.indexOf($(this).attr("data-id"));
				if (index > -1) {
				  artisti.splice(index, 1);
				}
				$(this).parent().remove();
			});
			artisti.push(id);
			artistiEdit=true;
		}
	});
	$("#addBrano").click(function(){
		var id=$("#selectBrano").val();
		var nome=$("#selectBrano option:selected").text();
		if(brani.indexOf(id)<0){
			var div=document.createElement("div");
			div.style.display="float";
			div.innerHTML='<i class="fas fa-minus-circle" style="color:red; float:left; margin-right:20px; margin-top:3px" data-id="'+id+'"></i><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">'+nome+'</div>';
			$("#tableBrano").append(div);
			$(div).find("i").click(function(){
				var index = brani.indexOf($(this).attr("data-id"));
				if (index > -1) {
				  brani.splice(index, 1);
				}
				$(this).parent().remove();
			});
			brani.push(id);
			braniEdit=true;
		}
	});




	$("#add_album_form").submit(function(e){
		e.preventDefault();
		var formData = new FormData();
		if(editedName){
			formData.append("Nome",$("#inputTitoloAlbum").val());
		}
		if(editedData){
			formData.append("Anno",$("#inputAnnoAlbum").val());
		}
		if(etichetta){
			formData.append("Etichetta",$("#inputEtichettaAlbum").val());
		}
		if(artistiEdit){
			for(var i=0;i<artisti.length;i++){
				formData.append("Artisti[]",artisti[i]);
			}
		}
		if(braniEdit){
			for(var i=0;i<brani.length;i++){
				formData.append("Brani[]",brani[i]);
			}
		}
		if(generiEdit){
			for(var i=0;i<generi.length;i++){
				formData.append("Generi[]", generi[i]);
			}
		}

		var image=$("#add_album_form input[name='image']")[0];
		if (image.files && image.files[0] && editedPhoto) {
			formData.append("Image", image.files[0], image.files[0].name)
		}
		if(editedName || editedData || etichetta || braniEdit || generiEdit || artistiEdit || (image.files && image.files[0] && editedPhoto)){
			var id=$("#inputIdAlbum").val();
			formData.append("submit",true);
			request("album", "edit/"+id,function(res){
					if(res.code<0){
						$(".invalid-feedback").html(res.error);
						$(".invalid-feedback").show();
					}else{
						loadCenterTemplate("album");
					}
				}, formData);
		}
	});
	$("#add_album_form input[name='image']").change(function(e){
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$("#loaded_image").attr("src", e.target.result);
				editedPhoto=true;
			};
			reader.readAsDataURL(this.files[0]);
		}
	});

	$("#inputTitoloAlbum").on("input", function(){editedName=true;});
	$("#inputAnnoAlbum").on("input", function(){editedData=true;});
	$("#inputEtichettaAlbum").on("input", function(){etichetta=true;});
}
//album

// brano
function loadbranodelete(){
	loadCenterTemplate("brano");
}

function loadbranoadd(){
	var artisti=Array();
	var generi=Array();
	$("#addArtistaBrano").click(function(){
		var id=$("#selectArtistaBrano").val();
		var nome=$("#selectArtistaBrano option:selected").text();
		if(artisti.indexOf(id)<0){
			var div=document.createElement("div");
			div.style.display="float";
			div.innerHTML='<i class="fas fa-minus-circle" style="color:red; float:left; margin-right:20px; margin-top:3px" data-id="'+id+'"></i><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">'+nome+'</div>';
			$("#tableArtistiBrano").append(div);
			$(div).find("i").click(function(){
				var index = artisti.indexOf($(this).attr("data-id"));
				if (index > -1) {
				  artisti.splice(index, 1);
				}
				$(this).parent().remove();
			});
			artisti.push(id);
		}
	});
	$("#addGenereBrano").click(function(){
		var id=$("#selectGenereBrano").val();
		var nome=$("#selectGenereBrano option:selected").text();
		if(generi.indexOf(id)<0){
			var div=document.createElement("div");
			div.style.display="float";
			div.innerHTML='<i class="fas fa-minus-circle" style="color:red; float:left; margin-right:20px; margin-top:3px" data-id="'+id+'"></i><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">'+nome+'</div>';
			$("#tableGeneriBrano").append(div);
			$(div).find("i").click(function(){
				var index = generi.indexOf($(this).attr("data-id"));
				if (index > -1) {
				  generi.splice(index, 1);
				}
				$(this).parent().remove();
			});
			generi.push(id);
		}
	});
	$("#inputFile").change(function(e) {
		if (this.files && this.files[0]) {
			$(this).parent().find("label").html(this.files[0].name);
		}
	});

	$("#add_brano_form").submit(function(e){
		var formData = new FormData();
		formData.append("Titolo",$("#inputTitoloBrano").val());
		formData.append("Anno",$("#inputAnnoBrano").val());
		for(var i=0;i<artisti.length;i++){
			formData.append("Artisti[]",artisti[i]);
		}
		for(var i=0;i<generi.length;i++){
			formData.append("Generi[]",generi[i]);
		}
		formData.append("submit",true);
		var music=$("#inputFile")[0];
		if (music.files && music.files[0]) {
			formData.append("Music", music.files[0], music.files[0].name);
		}
		request("brano", "add",function(res){
				if(res.code<0){
					$(".invalid-feedback").html(res.error);
					$(".invalid-feedback").show();
				}else{
					loadCenterTemplate("brano");
				}
			}, formData);
		event.preventDefault();
	});
}

function loadbranoedit(){
	var artisti=Array();
	var generi=Array();

	var titoloEdit=false;
	var annoEdit=false;
	var musicEdit=false;
	var generiEdit=false;
	var artistiEdit=false;

	$('#tableArtistiBrano div i').each(function(){artisti.push($(this).attr("data-id"))});
	$('#tableGeneriBrano div i').each(function(){generi.push($(this).attr("data-id"))});

	$('#tableArtistiBrano div i').click(function(){
		var index = artisti.indexOf($(this).attr("data-id"));
		if (index > -1) {
		  artisti.splice(index, 1);
		}
		$(this).parent().remove();
		artistiEdit=true;
	});
	$('#tableGeneriBrano div i').click(function(){
		var index = generi.indexOf($(this).attr("data-id"));
		if (index > -1) {
		  generi.splice(index, 1);
		}
		$(this).parent().remove();
		generiEdit=true;
	});

	$("#addArtistaBrano").click(function(){
		var id=$("#selectArtistaBrano").val();
		var nome=$("#selectArtistaBrano option:selected").text();
		if(artisti.indexOf(id)<0){
			var div=document.createElement("div");
			div.style.display="float";
			div.innerHTML='<i class="fas fa-minus-circle" style="color:red; float:left; margin-right:20px; margin-top:3px" data-id="'+id+'"></i><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">'+nome+'</div>';
			$("#tableArtistiBrano").append(div);
			$(div).find("i").click(function(){
				var index = artisti.indexOf($(this).attr("data-id"));
				if (index > -1) {
				  artisti.splice(index, 1);
				}
				$(this).parent().remove();
			});
			artisti.push(id);
			artistiEdit=true;
		}
	});
	$("#addGenereBrano").click(function(){
		var id=$("#selectGenereBrano").val();
		var nome=$("#selectGenereBrano option:selected").text();
		if(generi.indexOf(id)<0){
			var div=document.createElement("div");
			div.style.display="float";
			div.innerHTML='<i class="fas fa-minus-circle" style="color:red; float:left; margin-right:20px; margin-top:3px" data-id="'+id+'"></i><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">'+nome+'</div>';
			$("#tableGeneriBrano").append(div);
			$(div).find("i").click(function(){
				var index = generi.indexOf($(this).attr("data-id"));
				if (index > -1) {
				  generi.splice(index, 1);
				}
				$(this).parent().remove();
			});
			generi.push(id);
			generiEdit=true;
		}
	});
	$("#inputFile").change(function(e) {
		if (this.files && this.files[0]) {
			$(this).parent().find("label").html(this.files[0].name);
			musicEdit=true;
		}
	});
	$("#inputTitoloBrano").on("input", function(){titoloEdit=true;});
	$("#inputAnnoBrano").on("input", function(){annoEdit=true;});

	$("#add_brano_form").submit(function(e){
		event.preventDefault();
		var formData = new FormData();

		if(titoloEdit){
			formData.append("Titolo",$("#inputTitoloBrano").val());
		}
		if(annoEdit){
			formData.append("Anno",$("#inputAnnoBrano").val());
		}
		if(generiEdit){
			for(var i=0;i<generi.length;i++){
				formData.append("Generi[]",generi[i]);
			}
		}
		if(artistiEdit){
			for(var i=0;i<artisti.length;i++){
				formData.append("Artisti[]",artisti[i]);
			}
		}
		var music=$("#inputFile")[0];
		if (music.files && music.files[0] && musicEdit) {
			formData.append("Music", music.files[0], music.files[0].name);
		}
		if(titoloEdit || annoEdit || generiEdit || artistiEdit || (music.files && music.files[0] && musicEdit)){
			var id=$("#inputIdBrano").val();
			formData.append("submit",true);
			request("brano", "edit/"+id,function(res){
					if(res.code<0){
						$(".invalid-feedback").html(res.error);
						$(".invalid-feedback").show();
					}else{
						loadCenterTemplate("brano");
					}
				}, formData);

		}

	});
}
//brano



//genere
function eliminaGenereFunc(e){
	e.stopPropagation();
	var id=this.getAttribute("data-id");
	var formData = new FormData();
	var self=this;
	formData.append("id", id);
	if(id!=null){
		request("genere","remove",function(res){
				if(res.code==1){
					self.parentElement.parentElement.remove();
					$('#genere_row  tr').each(function(idx){$(this).children(":eq(0)").html(idx + 1);});
				}
			},formData);
	}
}
function loadgenere(){
	$("#add_genere_button").click(function(){
		var formData = new FormData();
		formData.append("testo", $("#add_genere").val());

		request("genere","add",function(res){
				if(res.code==1){
					var tr=document.createElement("tr");
					tr.innerHTML='<td>'+($("#genere_row>tr").length+1)+'</td><td>'+res.Text+'</td><td><button type="button" class="btn btn-dark elimina_genere" data-id="'+res.Id+'">Elimina</button></td>'
					$("#genere_row").append(tr.outerHTML);
					console.log($(tr).find('.elimina_genere'));
					$('.elimina_genere').click(eliminaGenereFunc);
				}
			}, formData)

	});
	$('.elimina_genere').click(eliminaGenereFunc);

};
//genere



// search brano
function createBranoRow(num, nome, data, info, id){
	var elem=document.createElement("tr");
	var inner="<td>"+num+"</td><td>"+nome+"</td><td>"+data+"</td>";
	if(info.length>120){
		inner+='<td title="'+info+'">'+info.substring(0,117)+'...</td>';
	}else{
		inner+="<td>"+info+"</td>";
	}
	elem.innerHTML=inner
	td=document.createElement("td");
	td.style.minWidth="200px";
	td.style.maxWidth="200px";
	td.style.width="200px";
	td.innerHTML='<button style="float:left" type="submit" name="button" class="btn btn-dark nav_link" data-link="artista" data-action="edit" data-addict="'+id+'">Modifica</button><button style="float:right" type="submit" name="button" class="btn btn-dark nav_link" data-link="artista" data-action="delete" data-addict="'+id+'">Elimina</button>';
	elem.appendChild(td);
	return elem;
}
// serch brano



// search
function search(name, action, resName, createFunction, func= function(){}){
	var formData = new FormData();
	formData.append("nome", name);
	if(typeof name != "undefined" && name!=null){
		request(action, "search",function(res){
				if(res.code==1){
					clearTable();
					var obj=res[resName];
					if(!obj){
						var elem= document.createElement("tr");
						elem.innerHTML='<td colspan="5" style="text-align:center">Nessun '+action+' trovato</td>';
						$("#table_row").append(elem);
					}else{
						for(var i=0; i<obj.length;i++){
							$("#table_row").append(createFunction(obj[i]));
						}
						$('#table_row  tr').each(function(idx){$(this).children(":eq(0)").html(idx + 1);});
					}

					func();
					$(".nav_link").click(navLinkClick);
				}
			}, formData);
	}
}
// search

// search artisti
function createArtistRow(res){
	var elem=document.createElement("tr");
	var inner="<td></td><td>"+res.Name+"</td><td>"+res.DataNascita+"</td>";
	if(res.Informazioni.length>120){
		inner+='<td title="'+res.Informazioni+'">'+res.Informazioni.substring(0,117)+'...</td>';
	}else{
		inner+="<td>"+res.Informazioni+"</td>";
	}
	elem.innerHTML=inner
	td=document.createElement("td");
	td.style.minWidth="200px";
	td.style.maxWidth="200px";
	td.style.width="200px";
	td.innerHTML='<button style="float:left" type="submit" name="button" class="btn btn-dark nav_link" data-link="artista" data-action="edit" data-addict="'+res.idArtist+'">Modifica</button><button style="float:right" type="submit" name="button" class="btn btn-dark nav_link" data-link="artista" data-action="delete" data-addict="'+res.idArtist+'">Elimina</button>';
	elem.appendChild(td);
	return elem;
}
// serch artisti


// search album
function createAlbumRow(res){
	var elem=document.createElement("tr");
	var inner="<td></td><td>"+res.Nome+"</td><td>"+res.Anno+"</td>";
	elem.innerHTML=inner
	td=document.createElement("td");
	td.style.minWidth="200px";
	td.style.maxWidth="200px";
	td.style.width="200px";
	td.innerHTML='<button style="float:left" type="submit" name="button" class="btn btn-dark nav_link" data-link="album" data-action="edit" data-addict="'+res.idAlbum+'">Modifica</button><button style="float:right" type="submit" name="button" class="btn btn-dark nav_link" data-link="album" data-action="delete" data-addict="'+res.idAlbum+'">Elimina</button>';
	elem.appendChild(td);
	return elem;
}
// search album

// search brano
function createBranoRow(res){
	var elem=document.createElement("tr");
	var inner="<td></td><td>"+res.Titolo+"</td><td>"+res.Anno+"</td><td>"+res.NomeAlbum+"</td>";
	elem.innerHTML=inner
	td=document.createElement("td");
	td.style.minWidth="200px";
	td.style.maxWidth="200px";
	td.style.width="200px";
	td.innerHTML='<button style="float:left" type="submit" name="button" class="btn btn-dark nav_link" data-link="brano" data-action="edit" data-addict="'+res.idBrano+'">Modifica</button><button style="float:right" type="submit" name="button" class="btn btn-dark nav_link" data-link="brano" data-action="delete" data-addict="'+res.idBrano+'">Elimina</button>';
	elem.appendChild(td);
	return elem;
}
// search brano






/*
function addElementTable(obj){
	var artisti=obj.Artisti;
	if(!artisti){
		var elem= document.createElement("tr");
		elem.innerHTML='<td colspan="5" style="text-align:center">Nessun artista trovato</td>';
		$("#artista_row").append(elem);
	}
	for(var i=0; i<artisti.length;i++){
		$("#artista_row").append(createArtistRow(i+1, artisti[i].Name, artisti[i].DataNascita, artisti[i].Informazioni, artisti[i].idArtist));
	}
}
*/

// search



function request(link, action, callback=function(){}, formData=new FormData() ){
	$(".overlay").show();
	$.ajax({
		url: document.location.origin+"/"+link+"/"+action+"/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		method: 'POST',
		type: 'POST', // For jQuery < 1.9
		success: function(data){
			$(".overlay").hide();
			callback(data);
		}
	});
}
