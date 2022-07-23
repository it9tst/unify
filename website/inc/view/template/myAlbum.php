<span class="title_page_1">I tuoi singoli preferiti</span>
<div class="brani_album_ext">
	<div class="card-deck">
		<?php if(count($this->Singoli)<=0):?>
			<div>La tua libreria di singoli è vuota! Comincia ad aggiungere qualche singolo..</div>
		<?php endif;?>

		<?php foreach($this->Singoli as $album): ?>
		<div class="card card_album">
	        <div class="album_img center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $album["idAlbum"]; ?>">
	            <img src="/images/albums/<?php echo empty($album["Image"])? "defaultAlbum.jpg" :$album["Image"];?>" style="width:100%">
				<div class="button_play"><i class="fas fa-play-circle play_album" data-album="<?php echo $album["idAlbum"];?>"></i></div>
	        </div>
	        <div class="container album_container">
	            <span class="music_title"><a class="center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $album["idAlbum"] ?>"><?php echo $album["Nome"] ?></a></span>
	            <span class="music_artist"><?php
				echo implode(', ', array_map(function($v) {
						return '<a class="center_link" data-link="artist" data-exec="artistPage" data-params="'.$v['id'].'" data-addict="'.$v['id'].'">'.$v['Name'].'</a>';
					}, $album['Artisti']));
				?></span>
	        </div>
	    </div>
	    <?php endforeach;?>
	</div>
</div>

<span class="title_page_1">I tuoi album preferiti</span>
<div class="brani_album_ext">
	<div class="card-deck">
		<?php if(count($this->Album)<=0):?>
			<div>La tua libreria di album è vuota! Comincia ad aggiungere qualche album..</div>
		<?php endif;?>

		<?php foreach($this->Album as $album): ?>
		<div class="card card_album">
	        <div class="album_img center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $album["idAlbum"]; ?>">
	            <img src="/images/albums/<?php echo empty($album["Image"])? "defaultAlbum.jpg" :$album["Image"];?>" style="width:100%">
				<div class="button_play"><i class="fas fa-play-circle play_album" data-album="<?php echo $album["idAlbum"];?>"></i></div>
	        </div>
	        <div class="container album_container">
	            <span class="music_title"><a class="center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $album["idAlbum"] ?>"><?php echo $album["Nome"] ?></a></span>
	            <span class="music_artist"><?php
				echo implode(', ', array_map(function($v) {
						return '<a class="center_link" data-link="artist" data-exec="artistPage" data-params="'.$v['id'].'" data-addict="'.$v['id'].'">'.$v['Name'].'</a>';
					}, $album['Artisti']));
				?></a></span>
	        </div>
	    </div>
	    <?php endforeach;?>
	</div>
</div>
