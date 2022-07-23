<div class="artist_page_container">
    <div class="first_row">
        <div class="card card_artist">
	        <div class="artist_img center_link" style="background-image:url('images/artists/<?php echo empty($this->Artist["Image"])? "defaultArtist.jpg" :$this->Artist["Image"];?>')"></div>
	    </div>
    </div>
    <div class="second_row">
        <span class="artist_name"><?php echo $this->Artist["Name"];?></span>
        <span class="add_library">
            <button class="btn btn_rounded" type="button" id="<?php echo ($this->Aggiunto=="true")? "addedArtist": "addArtist"?>">Aggiungi alla libreria</button>
        </span>
    </div>
</div>

<div class="row artist_page_single_popolari">
    <div class="col-3 single">
        <span class="title_page_1" style="margin: 0px; margin-left: 5px;">Ultima uscita</span>
    	<div class="artist_page_album_ext">
    		<div class="card card_album">
    	        <div class="album_img center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $this->Last["idAlbum"];?>">
    	            <img src="/images/albums/<?php echo empty($this->Last["Image"])? "defaultAlbum.jpg" :$this->Last["Image"];?>" style="width:100%">
                    <div class="button_play"><i class="fas fa-play-circle play_album" data-album="<?php echo $this->Last["idAlbum"];?>"></i></div>
    	        </div>
    	        <div class="container album_container">
    	            <span class="music_title"><a class="center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $this->Last["idAlbum"];?>"><?php echo $this->Last["Titolo"]?></a></span>
    	            <span class="music_artist"><?php echo $this->Last["Anno"]?></span>
    	        </div>
    	    </div>
    	</div>
    </div>
    <div class="col-9 popolari">
        <span class="title_page_1" style="margin: 0px; margin-left: 5px;">Popolari</span>
        <table class="table table-hover popolari_list">
            <thead>
                <tr>
                    <th scope="col">Titolo</th>
                    <th scope="col">Durata</th>
                    <th scope="col">Album</th>
                    <th scope="col">Genere</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
			<?php $b=""; $c=0;?>
			<?php for($i=0;$c<4&& $i< count($this->Popolari);$i++):?>
			<?php if($this->Popolari[$i]["idBrano"]==$b)continue; else{$b=$this->Popolari[$i]["idBrano"]; $c++;}?>
                <tr>
                    <td><i class="fas fa-play-circle play_song" data-song="<?php echo $this->Popolari[$i]["idBrano"]; ?>"></i> <a class="play_song" data-song="<?php echo $this->Popolari[$i]["idBrano"]; ?>"><?php echo $this->Popolari[$i]["Titolo"];?></a></td>
                    <td><?php $dur=intval($this->Popolari[$i]["Durata"]); $min=intval($dur/60); $sec=$dur%60; if($sec<10) $sec="0".$sec; elseif($sec==0) $sec="00"; echo $min.":".$sec; ?></a></td>
                    <td><a class="center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $this->Popolari[$i]["idAlbum"];?>"><?php echo $this->Popolari[$i]["Nome"];?></a></td>
                    <td><a class="center_link" data-link="genre" data-addict="<?php echo $this->Popolari[$i]["idGenere"];?>"><?php echo $this->Popolari[$i]["Testo"];?></a></td>
                    <td style="text-align: center;width: 70px;"><i class="btnSong fas fa-<?php echo($this->Popolari[$i]["added"])? "check":"plus" ?>" data-id="<?php echo $this->Popolari[$i]["idBrano"];?>" style="color: #343434;"></i></td>
                </tr>
            <?php endfor;?>
            </tbody>
        </table>
    </div>
</div>

<span class="title_page_1">Informazioni</span>
<div class="artist_page_album">
    <div class="card-deck">
    	<span><?php echo $this->Artist["Informazioni"];?></span>
    </div>
</div>

<span class="title_page_1">Singoli</span>
<div class="artist_page_album">
	<div class="card-deck">
		<?php if(count($this->Singoli)<1) echo "Non esistono singoli per l'artista ".$this->Artist["Name"].".";?>
		<?php foreach($this->Singoli as $album): ?>
		<div class="card card_album">
	        <div class="album_img center_link"  data-link="album" data-exec="albumPage" data-addict="<?php echo $album["idAlbum"];?>">
	            <img src="/images/albums/<?php echo empty($album["Image"])? "defaultAlbum.jpg" :$album["Image"];?>" style="width:100%">
                <div class="button_play"><i class="fas fa-play-circle play_album" data-album="<?php echo $album["idAlbum"];?>"></i></div>
	        </div>
	        <div class="container album_container">
	            <span class="music_title"><a class="center_link"  data-link="album" data-exec="albumPage" data-addict="<?php echo $album["idAlbum"];?>"><?php echo $album["Nome"]?></a></span>
	            <span class="music_age"><?php echo $album["Anno"];?></span>
	        </div>
	    </div>
		<?php endforeach;?>
	</div>
</div>

<span class="title_page_1">Album</span>
<div class="artist_page_album">
	<div class="card-deck">
		<?php if(count($this->Albums)<1) echo "Non esistono album per l'artista ".$this->Artist["Name"].".";?>
		<?php foreach($this->Albums as $album): ?>
		<div class="card card_album">
	        <div class="album_img center_link"  data-link="album" data-exec="albumPage" data-addict="<?php echo $album["idAlbum"];?>">
	            <img src="/images/albums/<?php echo empty($album["Image"])? "defaultAlbum.jpg" :$album["Image"];?>" style="width:100%">
                <div class="button_play"><i class="fas fa-play-circle play_album" data-album="<?php echo $album["idAlbum"];?>"></i></div>
	        </div>
	        <div class="container album_container">
	            <span class="music_title"><a class="center_link"  data-link="album" data-exec="albumPage" data-addict="<?php echo $album["idAlbum"];?>"><?php echo $album["Nome"];?></a></span>
	            <span class="music_age"><?php echo $album["Anno"];?></span>
	        </div>
	    </div>
		<?php endforeach;?>
	</div>
</div>

<span class="title_page_1">Compare in</span>
<div class="playlist_ext">
	<div class="card-deck">
		<?php if(count($this->Playlist)<1) echo "Non esistono playlist in cui compare l'artista ".$this->Artist["Name"]."."?>
		<?php foreach($this->Playlist as $playlist): ?>
			<?php $image=explode(",",$playlist["Image"]);?>
		<div class="card card_album">
			<div class="playlist_img_ext center_link" data-link="playlist" data-exec="playlistPage" data-params="<?php echo $playlist["idPlaylist"];?>" data-addict="<?php echo $playlist["idPlaylist"]?>">
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
				<div class="button_play"><i class="fas fa-play-circle play_playlist" data-playlist="<?php echo $playlist["idPlaylist"];?>"></i></div>
			</div>
			<div class="container album_container">
	            <span class="playlist_title center_link" data-link="playlist" data-exec="playlistPage" data-params="<?php echo $playlist["idPlaylist"];?>" data-addict="<?php echo $playlist["idPlaylist"]?>"><?php echo $playlist["Titolo"]?></span>
				<span class="playlist_autor"><?php echo $playlist["Creatore"];?></span>
	        </div>
		 </div>
		<?php endforeach;?>
    </div>
</div>