
<span class="title_page_1">Artisti per <?echo "'".$this->Key."'"?></span>
<div class="artist_ext search_page">
	<div class="card-deck">
		<?php if(empty($this->Artists)):?>
		<span>Non abbiamo trovato artisti per la tua chiave di ricerca.</span></br>
		<?php endif;?>
		<?php foreach($this->Artists as $artista): ?>
	    <div class="card card_artist">
	        <div class="artist_img center_link" style="background-image:url('images/artists/<?php echo empty($artista["Image"])? "defaultArtist.jpg" :$artista["Image"];?>')" data-link="artist" data-exec="artistPage" data-params="<?php echo $artista["idArtist"];?>" data-addict="<?php echo $artista["idArtist"];?>">
				<div class="button_play"><i class="fas fa-play-circle play_artist" data-artist="<?php echo $artista["idArtist"];?>"></i></div>
			</div>
	        <div class="container artist_container">
	            <a class="artist_name center_link" data-link="artist" data-params="<?php echo $artista["idArtist"];?>"  data-exec="artistPage" data-addict="<?php echo $artista["idArtist"];?>"><?php echo $artista["Name"];?></a>
	        </div>
	    </div>
		<?php endforeach;?>
	</div>
</div>

<span class="title_page_1">Brani per <?echo "'".$this->Key."'"?></span>
<div class="brani_album_ext search_page">
	<div class="card-deck">
		<?php if(empty($this->Brani)):?>
		<span>Non abbiamo trovato brani per la tua chiave di ricerca.</span></br>
		<?php endif;?>
		<?php foreach($this->Brani as $brano): ?>
		<div class="card card_album">
	        <div class="album_img center_link"  data-link="album" data-exec="albumPage" data-addict="<?php echo $brano["idAlbum"];?>">
	            <img src="/images/albums/<?php echo empty($brano["Image"])? "defaultAlbum.jpg" :$brano["Image"];?>" style="width:100%">
				<div class="button_play"><i class="fas fa-play-circle play_song" data-song="<?php echo $brano["idBrano"];?>"></i></div>
	        </div>
	        <div class="container album_container">
	            <span class="music_title"><a class="center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $brano["idAlbum"];?>"><?php echo $brano["Titolo"];?></a></span>
	            <span class="music_artist">
					<?php
						echo implode(' , ', array_map(function($v) {
							return '<a class="center_link" data-link="artist" data-exec="artistPage" data-addict="'.$v['id'].'">'.$v['Name'].'</a>';
						}, $brano['Artisti']));
					?>
				</span>
	        </div>
	    </div>
		<?php endforeach;?>
	</div>
</div>


<span class="title_page_1">Album per <?echo "'".$this->Key."'"?></span>
<div class="brani_album_ext search_page">
	<div class="card-deck">
		<?php if(empty($this->Albums)):?>
		<span>Non abbiamo trovato album per la tua chiave di ricerca.</span></br>
		<?php endif;?>
		<?php foreach($this->Albums as $album): ?>
		<div class="card card_album">
	        <div class="album_img center_link"  data-link="album" data-exec="albumPage" data-addict="<?php echo $album["idAlbum"];?>">
	            <img src="/images/albums/<?php echo empty($album["Image"])? "defaultAlbum.jpg" :$album["Image"];?>" style="width:100%">
				<div class="button_play"><i class="fas fa-play-circle play_album" data-album="<?php echo $album["idAlbum"];?>"></i></div>
	        </div>
	        <div class="container album_container">
	            <span class="music_title"><a class="center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $album["idAlbum"];?>"><?php echo $album["Nome"];?></a></span>
	            <span class="music_artist">
					<?php
						echo implode(' , ', array_map(function($v) {
						return '<a class="center_link" data-exec="artistPage" data-link="artist" data-addict="'.$v['id'].'" data-params="'.$v['id'].'">'.$v['Name'].'</a>';
						}, $album['Artisti']));
					?>
				</span>
	        </div>
	    </div>
		<?php endforeach;?>
	</div>
</div>
