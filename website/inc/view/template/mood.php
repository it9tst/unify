<span class="title_page_1">Attività e stati d'animo</span>
<div class="playlist_ext">
	<div class="card-deck">
		<?php foreach($this->Moods as $mood):?>
		<div class="card card_album">
	        <div class="album_img center_link" data-link="playlist" data-exec="playlistPage" data-params="<?php echo $mood["idPlaylist"];?>" data-addict="<?php echo $mood["idPlaylist"];?>">
	            <img src="/images/moods/<?php echo strtolower(str_replace(' ', '', $mood["Titolo"])).".jpg";?>" style="width:100%">
				<div class="button_play"><i class="fas fa-play-circle play_playlist" data-playlist="<?php echo $mood["idPlaylist"];?>"></i></div>
	        </div>
	        <div class="container album_container">
	            <span class="playlist_title"><a class="center_link" data-link="album" data-addict=""><?php echo $mood["Titolo"];?></a></span>
	        </div>
	    </div>
		<?php endforeach;?>
	</div>
</div>
