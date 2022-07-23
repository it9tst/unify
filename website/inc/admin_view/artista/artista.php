<div class="ArtistaViewAdmin">
	<form id="add_artista_form">
		<?php if(!empty($this->Artista)):?>
			<div class="form-row img_art">
				<img id="loaded_image" width="200" height="200" src="<?php echo "https://dashboard.unify-unipa.it/images/artists/".$this->Artista['Image'];?>">
				<div class="form-group">
					<input type="file" accept="image/jpg, image/jpeg ,image/png" class="form-control-file" name="image">
				</div>
			</div>
			<div class="form-row spaz_row">
				<div class="form-group col-md-6">
					<label for="inputNomeArtista">Nome</label>
					<input type="text" class="form-control" id="inputNomeArtista" placeholder="Nome" value="<?php echo $this->Artista['Name'];?>">
				</div>
				<div class="form-group col-md-6">
					<label for="inputDataNascitaArtista">Data di nascita</label>
					<input type="text" class="form-control datepicker" id="inputDataNascitaArtista">
				</div>
			</div>
			<div class="form-group spaz_row">
				<label for="inputInfoArtista">Informazioni artista</label>
				<textarea class="form-control" id="inputInfoArtista" rows="3"><?php echo $this->Artista['Informazioni'];?></textarea>
			</div>
			<input type="hidden" id="inputIdArtista" value="<?php echo $this->Artista['idArtist'];?>">
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
					<label for="inputNomeArtista">Nome</label>
					<input type="text" class="form-control" id="inputNomeArtista" placeholder="Nome">
				</div>
				<div class="form-group col-md-6">
					<label for="inputDataNascitaArtista">Data di nascita</label>
					<input type="text" class="form-control datepicker" id="inputDataNascitaArtista">
				</div>
			</div>
			<div class="form-group spaz_row">
				<label for="inputInfoArtista">Informazioni artista</label>
				<textarea class="form-control" id="inputInfoArtista" rows="3"></textarea>
			</div>
			<button type="submit" name="button" class="btn btn-dark">Aggiungi</button>
			<div class="invalid-feedback"></div>
		<?php endif;?>
	</form>
</div>


<script>
$(function(){
	$.fn.datepicker.defaults.format = "yyyy-mm-dd";
	$( ".datepicker" ).datepicker(<?php if(!empty($this->Artista['DataNascita'])) echo "'update', '".$this->Artista['DataNascita']."'";?>);
});
</script>
