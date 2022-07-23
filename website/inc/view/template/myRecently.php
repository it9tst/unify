<span class="title_page_1">I tuoi ultimi brani ascoltati</span>
<div class="brani_album_ext">
	<div class="card-deck">
		<?php if(count($this->myRecently)<=0):?>
			<div>Non hai ascoltato nessun brano! Comincia ad esplorare e scoprire nuova musica..</div>
		<?php endif;?>

		<?php foreach($this->myRecently as $recente): ?>
		<div class="card card_album">
	        <div class="album_img center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $recente["idAlbum"];?>">
	            <img src="/images/albums/<?php echo empty($recente["Image"])? "defaultAlbum.jpg" :$recente["Image"];?>" style="width:100%">
				<div class="button_play"><i class="fas fa-play-circle play_song" data-song="<?php echo $recente["idBrano"];?>"></i></div>
	        </div>
	        <div class="container album_container">
	            <span class="music_title"><a class="center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $recente["idAlbum"];?>"><?php echo $recente["Titolo"] ?></a></span>
	            <span class="music_artist"><?php
				echo implode(', ', array_map(function($v) {
						return '<a class="center_link" data-link="artist" data-exec="artistPage" data-params="'.$v['id'].'" data-addict="'.$v['id'].'">'.$v['Name'].'</a>';
					}, $recente['Artisti']));
				?></span>

	        </div>
	    </div>
	    <?php endforeach;?>
	</div>
</div>
