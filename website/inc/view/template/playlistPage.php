<span class="title_page_1">Playlist</span>
<div class="playlist_ext">
	<div class="card-deck">
		<?php foreach($this->Playlists as $playlist): ?>
			<?php $image=explode(",",$playlist["Image"]);?>
		<div class="card card_album">
			<div class="playlist_img_ext center_link" data-link="playlist" data-exec="playlistPage" data-params="<?php echo $playlist["idPlaylist"];?>" data-addict="<?php echo $playlist["idPlaylist"];?>">
				<div class="playlist_img">
					<?php if(count($image)<4):?>
					<img src="/images/albums/<?php echo ((!empty($image[0]))?$image[0]:"defaultAlbum.jpg");?>" style="width:100%">
					<?php else:?>
					<div class="playlist_top_img">
						<div class="album_img_playlist"><img src="/images/albums/<?php echo $image[0];?>" style="width:100%"></div>
						<div class="album_img_playlist"><img src="/images/albums/<?php echo $image[1];?>" style="width:100%"></div>
					</div>
					<div class="playlist_bottom_img">
						<div class="album_img_playlist"><img src="/images/albums/<?php echo $image[2];?>" style="width:100%"></div>
						<div class="album_img_playlist"><img src="/images/albums/<?php echo $image[3];?>" style="width:100%"></div>
					</div>
					<?php endif;?>
				</div>
				<div class="button_play"><i class="fas fa-play-circle play_playlist" data-playlist="<?php echo $playlist["idPlaylist"]?>"></i></div>
			</div>
			<div class="container album_container">
	            <span class="playlist_title center_link" data-link="playlist" data-exec="playlistPage" data-params="<?php echo $playlist["idPlaylist"];?>" data-addict="<?php echo $playlist["idPlaylist"];?>"><?php echo $playlist["Titolo"] ?></span>
				<span class="playlist_autor"><?php echo $playlist["Creatore"];?></span>
	        </div>
		 </div>
		<?php endforeach;?>
    </div>
</div>
