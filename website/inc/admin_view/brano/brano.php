<div class="BranoViewAdmin">
	<form id="add_brano_form">
		<?php if(!empty($this->Brano)):?>
            <div class="form-row nome_data_art">
				<div class="form-group col-md-6">
					<label for="inputTitoloBrano">Titolo</label>
					<input type="text" class="form-control" id="inputTitoloBrano" placeholder="Nome" value="<?php echo $this->Brano['Titolo'];?>">
				</div>
                <div class="form-group col-md-6">
                    <label for="inputAnnoBrano">Anno</label>
					<input type="text" class="form-control datepicker" id="inputAnnoBrano">
                </div>
			</div>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text">Upload brano (mp3)</span>
				</div>
				<div class="custom-file">
					<input type="file" class="custom-file-input" id="inputFile" accept="audio/mpeg">
					<label class="custom-file-label" for="inputFile">Seleziona file brano</label>
				</div>
			</div>
            <div class="form-row nome_data_art">
                <div class="form-group col-md-6">
                    <label for="inputArtistaBrano">Artista <i class="fas fa-plus-circle" id="addArtistaBrano"></i></label>
                    <select class="form-control" required name="inputArtistaBrano" id="selectArtistaBrano">
                        <?php foreach($this->Artista as $k=>$v):?>
							<option value="<?php echo $k;?>"><?php echo $v++;?></option>
						<?php endforeach;?>
                    </select>
				</div>
                <div class="form-group col-md-6 mb-2">
                    <label for="inputGenereBrano">Genere <i class="fas fa-plus-circle" id="addGenereBrano"></i></label>
                    <select class="form-control" name="inputGenereBrano" required id="selectGenereBrano">
						<?php foreach($this->Generi as $k=>$v):?>
							<option value="<?php echo $k;?>"><?php echo $v++;?></option>
						<?php endforeach;?>
                    </select>
					
                </div>
            </div>
			<div class="form-row nome_data_art mb-2">
				<div class="col-md-6" id="tableArtistiBrano">
					<?php foreach($this->ArtistiBrano as $v):?>
							<div><?php echo $v['Name'];?><i class="fas fa-minus-circle" style="color:red; float:left; margin-right:20px; margin-top:3px" data-id="<?php echo $v['idArtist'];?>"></i></div>
					<?php endforeach;?>
					
				</div>
				<div class="col-md-6" id="tableGeneriBrano">
					<?php foreach($this->GeneriBrano as $v):?>
							<div><?php echo $v['Testo'];?><i class="fas fa-minus-circle" style="color:red; float:left; margin-right:20px; margin-top:3px" data-id="<?php echo $v['idGenere'];?>"></i></div>
					<?php endforeach;?>
				</div>
			</div>
			<input type="hidden" id="inputIdBrano" value="<?php echo $this->Brano['idBrano'];?>">
			<button type="submit" name="button" class="btn btn-dark">Salva</button>
			<div class="invalid-feedback"></div>
		<?php else:?>

            <div class="form-row nome_data_art">
				<div class="form-group col-md-6">
					<label for="inputTitoloBrano">Titolo</label>
					<input type="text" class="form-control" id="inputTitoloBrano" placeholder="Nome">
				</div>
                <div class="form-group col-md-6">
                    <label for="inputAnnoBrano">Anno</label>
					<input type="text" class="form-control datepicker" id="inputAnnoBrano">
                </div>
			</div>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text">Upload brano (mp3)</span>
				</div>
				<div class="custom-file">
					<input type="file" class="custom-file-input" id="inputFile" accept="audio/mpeg">
					<label class="custom-file-label" for="inputFile">Seleziona file brano</label>
				</div>
			</div>
            <div class="form-row nome_data_art">
                <div class="form-group col-md-6">
                    <label for="inputArtistaBrano">Artista <i class="fas fa-plus-circle" id="addArtistaBrano"></i></label>
                    <select class="form-control" required name="inputArtistaBrano" id="selectArtistaBrano">
                        <?php foreach($this->Artista as $k=>$v):?>
							<option value="<?php echo $k;?>"><?php echo $v++;?></option>
						<?php endforeach;?>
                    </select>
				</div>
                <div class="form-group col-md-6 mb-2">
                    <label for="inputGenereBrano">Genere <i class="fas fa-plus-circle" id="addGenereBrano"></i></label>
                    <select class="form-control" name="inputGenereBrano" required id="selectGenereBrano">
						<?php foreach($this->Generi as $k=>$v):?>
							<option value="<?php echo $k;?>"><?php echo $v++;?></option>
						<?php endforeach;?>
                    </select>
					
                </div>
            </div>
			<div class="form-row nome_data_art mb-2">
				<div class="col-md-6" id="tableArtistiBrano">
				</div>
				<div class="col-md-6" id="tableGeneriBrano">
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
	$( ".datepicker" ).datepicker(<?php if(!empty($this->Brano['Anno'])) echo "'update', '".$this->Brano['Anno']."'";?>);
});
</script>
