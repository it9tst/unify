<span class="title_page_1">I tuoi artisti preferiti</span>
<div class="artist_ext">
	<div class="card-deck">
		<?php if(count($this->Artisti)<=0):?>
			<div>La tua libreria di artisti è vuota! Comincia ad aggiungere qualche artista..</div>
		<?php endif;?>


		<?php foreach($this->Artisti as $artista): ?>
	    <div class="card card_artist">
	        <div class="artist_img center_link" style="background-image:url('images/artists/<?php echo empty($artista["Image"])? "defaultArtist.jpg" :$artista["Image"];?>')" data-link="artist" data-exec="artistPage" data-params="<?php echo $artista["idArtist"];?>" data-addict="<?php echo $artista["idArtist"];?>">
				<div class="button_play"><i class="fas fa-play-circle play_artist" data-artist="<?php echo $artista["idArtist"];?>"></i></div>
			</div>
	        <div class="container artist_container">
	            <a class="artist_name center_link" data-link="artist" data-exec="artistPage" data-params="<?php echo $artista["idArtist"];?>" data-addict="<?php echo $artista["idArtist"];?>"><?php echo $artista["Name"];?></a>
	        </div>
	    </div>
		<?php endforeach;?>
	</div>
</div>
