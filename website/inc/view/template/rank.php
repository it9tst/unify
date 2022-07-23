<span class="title_page_1">Top singoli</span>
<div class="brani_album_ext SR_ext">
	<div class="card-deck SR_card-deck">
		<?php foreach($this->topSingoli as $singolo): ?>
		<div class="card card_album">
	        <div class="album_img center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $singolo["idAlbum"];?>">
	            <img src="/images/albums/<?php echo empty($singolo["Image"])? "defaultAlbum.jpg" :$singolo["Image"];?>" style="width:100%">
				<div class="button_play"><i class="fas fa-play-circle play_album" data-album="<?php echo $singolo["idAlbum"];?>"></i></div>
	        </div>
	        <div class="container album_container">
	            <span class="music_title"><a class="center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $singolo["idAlbum"];?>"><?php echo $singolo["Nome"];?></a></span>
	            <span class="music_artist"><?php
				echo implode(', ', array_map(function($v) {
						return '<a class="center_link" data-link="artist" data-exec="artistPage" data-params="'.$v['id'].'" data-addict="'.$v['id'].'">'.$v['Name'].'</a>';
					}, $singolo['Artisti']));
				?></span>
	        </div>
	    </div>
		<?php endforeach;?>
	</div>
</div>

<span class="title_page_1">Top album</span>
<div class="brani_album_ext SR_ext">
	<div class="card-deck SR_card-deck">
		<?php $a="";?>
		<?php foreach($this->topAlbum as $album): ?>
		<?php if($album["idAlbum"]==$a)continue; else $a=$album["idAlbum"];?>
		<div class="card card_album">
	        <div class="album_img center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $album["idAlbum"];?>">
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

<span class="title_page_1">Top playlist</span>
<div class="playlist_ext SR_ext">
	<div class="card-deck SR_card-deck">
		<?php foreach($this->topPlaylist as $playlist): ?>
			<?php $image=explode(",",$playlist["Image"]);?>
		<div class="card card_album">
			<div class="playlist_img_ext center_link" data-link="playlist" data-exec="playlistPage" data-params="<?php echo $playlist["idPlaylist"];?>" data-addict="<?php echo $playlist["idPlaylist"] ?>">
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
				<div class="button_play"><i class="fas fa-play-circle play_playlist" data-playlist="<?php echo $playlist["idPlaylist"] ?>"></i></div>
			</div>
			<div class="container album_container">
	            <span class="playlist_title center_link" data-link="playlist" data-exec="playlistPage" data-params="<?php echo $playlist["idPlaylist"];?>" data-addict="<?php echo $playlist["idPlaylist"] ?>"><?php echo $playlist["Titolo"] ?></span>
				<span class="playlist_autor"><?php echo $playlist["Creatore"] ?></span>
	        </div>
		 </div>
		<?php endforeach;?>
    </div>
</div>
