<div class="AlbumViewAdmin">
	<form id="add_album_form">
		<?php if(!empty($this->Album)):?>
			<div class="form-row img_art">
				<img id="loaded_image" width="200" height="200" src="<?php echo "https://dashboard.unify-unipa.it/images/albums/".$this->Album['Image'];?>">
				<div class="form-group">
					<input type="file" accept="image/jpg, image/jpeg ,image/png" class="form-control-file" name="image">
				</div>
			</div>
			<div class="form-row spaz_row">
				<div class="form-group col-md-6">
					<label for="inputTitoloAlbum">Titolo</label>
					<input type="text" class="form-control" id="inputTitoloAlbum" placeholder="Nome" value="<?php echo $this->Album['Nome'];?>">
				</div>
				<div class="form-group col-md-6">
					<label for="inputAnnoAlbum">Anno</label>
					<input type="text" class="form-control datepicker" id="inputAnnoAlbum">
				</div>
			</div>
			<div class="form-row spaz_row">
				<div class="form-group col-md-6">
					<label for="inputArtistaAlbum">Artista<i class="fas fa-plus-circle" id="addArtistaAlbum"></i></label>
                    <select class="form-control" required name="inputArtistaAlbum" id="selectArtistaAlbum">
						<?php foreach($this->Artisti as $k=>$v):?>
							<option value="<?php echo $k;?>"><?php echo $v++;?></option>
						<?php endforeach;?>
                    </select>
				</div>
				<div class="form-group col-md-6">
					<label for="inputEtichettaAlbum">Etichetta</label>
					<input type="text" class="form-control" id="inputEtichettaAlbum" placeholder="Etichetta" value="<?php echo $this->Album['Etichetta'];?>">
				</div>
			</div>
			<div class="form-row spaz_row">
				<div class="form-group col-md-6">
					<label for="inputBranoAlbum">Brani<i class="fas fa-plus-circle" id="addBrano"></i></label>
					<select class="form-control" required name="inputBranoAlbum" id="selectBrano">
						<?php foreach($this->Brani as $k=>$v):?>
							<option value="<?php echo $k;?>"><?php echo $v++;?></option>
						<?php endforeach;?>
					</select>
				</div>
				<div class="form-group col-md-6 mb-2">
					<label for="inputGenereAlbum">Genere <i class="fas fa-plus-circle" id="addGenereAlbum"></i></label>
					<select class="form-control" name="inputGenereAlbum" required id="selectGenereAlbum">
						<?php foreach($this->Generi as $k=>$v):?>
							<option value="<?php echo $k;?>"><?php echo $v++;?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
			<div class="form-row nome_data_art mb-2">
				<div class="col-md-6" id="tableArtistiAlbum">
					<?php foreach($this->ArtistiAlbum as $v):?>
						<div><?php echo $v['Name'];?><i class="fas fa-minus-circle" style="color:red; float:left; margin-right:20px; margin-top:3px" data-id="<?php echo $v['idArtist'];?>"></i></div>
					<?php endforeach;?>
				</div>
				<div class="col-md-6" id="tableBrano">
					<?php foreach($this->BraniAlbum as $v):?>
						<div><?php echo $v['Titolo'];?><i class="fas fa-minus-circle" style="color:red; float:left; margin-right:20px; margin-top:3px" data-id="<?php echo $v['idBrano'];?>"></i></div>
					<?php endforeach;?>
				</div>
			</div>
			<hr>
			<div class="form-row nome_data_art mb-2">
				<div class="col-md-6" id="tableGeneriAlbum">
					<?php foreach($this->GeneriAlbum as $v):?>
						<div><?php echo $v['Testo'];?><i class="fas fa-minus-circle" style="color:red; float:left; margin-right:20px; margin-top:3px" data-id="<?php echo $v['idGenere'];?>"></i></div>
					<?php endforeach;?>
				</div>
			</div>
			<input type="hidden" id="inputIdAlbum" value="<?php echo $this->Album['idAlbum'];?>">
			<button type="submit" name="button" class="btn btn-dark">Salva</button>
			<div class="invalid-feedback"></div>
		<?php else:?>
			<div class="form-row img_art">
				<img id="loaded_image" width="200" height="200">
				<div class="form-group">
					<input type="file" accept="image/jpg, image/jpeg ,image/png" class="form-control-file" name="image">
				</div>
			</div>
			<div class="form-row spaz_row">
				<div class="form-group col-md-6">
					<label for="inputTitoloAlbum">Titolo</label>
					<input type="text" class="form-control" id="inputTitoloAlbum" placeholder="Nome">
				</div>
				<div class="form-group col-md-6">
					<label for="inputAnnoAlbum">Anno</label>
					<input type="text" class="form-control datepicker" id="inputAnnoAlbum">
				</div>
			</div>
			<div class="form-row spaz_row">
				<div class="form-group col-md-6">
					<label for="inputArtistaAlbum">Artista<i class="fas fa-plus-circle" id="addArtistaAlbum"></i></label>
                    <select class="form-control" required name="inputArtistaAlbum" id="selectArtistaAlbum">
						<?php foreach($this->Artisti as $k=>$v):?>
							<option value="<?php echo $k;?>"><?php echo $v++;?></option>
						<?php endforeach;?>
                    </select>
				</div>
				<div class="form-group col-md-6">
					<label for="inputEtichettaAlbum">Etichetta</label>
					<input type="text" class="form-control" id="inputEtichettaAlbum" placeholder="Etichetta">
				</div>
			</div>
			<div class="form-row spaz_row">
				<div class="form-group col-md-6">
					<label for="inputBranoAlbum">Brani<i class="fas fa-plus-circle" id="addBrano"></i></label>
					<select class="form-control" required name="inputBranoAlbum" id="selectBrano">
						<?php foreach($this->Brani as $k=>$v):?>
							<option value="<?php echo $k;?>"><?php echo $v++;?></option>
						<?php endforeach;?>
					</select>
				</div>
				<div class="form-group col-md-6 mb-2">
					<label for="inputGenereAlbum">Genere <i class="fas fa-plus-circle" id="addGenereAlbum"></i></label>
					<select class="form-control" name="inputGenereAlbum" required id="selectGenereAlbum">
						<?php foreach($this->Generi as $k=>$v):?>
							<option value="<?php echo $k;?>"><?php echo $v++;?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
			
			<div class="form-row nome_data_art mb-2">
				<div class="col-md-6" id="tableArtistiAlbum">
				</div>
				<div class="col-md-6" id="tableBrano">
				</div>
			</div>
			<hr>
			<div class="form-row nome_data_art mb-2">
				<div class="col-md-6" id="tableGeneriAlbum">
				</div>
			</div>
			
			<button type="submit" name="button" class="btn btn-dark">Aggiungi</button>
			<div class="invalid-feedback"></div>
		<?php endif;?>
	</form>
</div>


<script>
$(function(){
	$.fn.datepicker.defaults.format = "yyyy-mm-dd";
	$( ".datepicker" ).datepicker(<?php if(!empty($this->Album['Anno'])) echo "'update', '".$this->Album['Anno']."'";?>);
});
</script>
