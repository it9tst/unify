<span class="title_page_1">I tuoi singoli <?php echo $this->Genere[0]["Testo"]; ?></span>
<div class="brani_album_ext">
    <div class="card-deck">
		<?php if(count($this->myGenreSingoli)<1) echo "La tua libreria di singoli ".$this->Genere[0]["Testo"]." è vuota! Comincia ad aggiungere qualche singolo..";?>
		<?php foreach($this->myGenreSingoli as $singolo): ?>
    	<div class="card card_album">
            <div class="album_img center_link" data-link="album" data-exec="albumPage" data-addict="<? echo $singolo["idAlbum"];?>">
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
		<?php endforeach; ?>
    </div>
</div>

<span class="title_page_1">I tuoi album <?php echo $this->Genere[0]["Testo"]; ?></span>
<div class="brani_album_ext">
    <div class="card-deck">
		<?php if(count($this->myGenreAlbum)<1) echo "La tua libreria di album ".$this->Genere[0]["Testo"]." è vuota! Comincia ad aggiungere qualche album..";?>
		<?php foreach($this->myGenreAlbum as $album): ?>
    	<div class="card card_album">
            <div class="album_img center_link" data-link="album" data-exec="albumPage" data-addict="<? echo $album["idAlbum"];?>">
                <img src="/images/albums/<?php echo empty($album["Image"])? "defaultAlbum.jpg" :$album["Image"];?>" style="width:100%">
                <div class="button_play"><i class="fas fa-play-circle play_album" data-album="<?php echo $album["idAlbum"];?>"></i></div>
            </div>
            <div class="container album_container">
                <span class="music_title"><a class="center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $album["idAlbum"];?>"><?php echo $album["Nome"];?></a></span>
                <span class="music_artist"><?php
				echo implode(', ', array_map(function($v) {
						return '<a class="center_link" data-link="artist" data-exec="artistPage" data-params="'.$v['id'].'" data-addict="'.$v['id'].'">'.$v['Name'].'</a>';
					}, $album['Artisti']));
				?></span>
            </div>
        </div>
		<?php endforeach; ?>
    </div>
</div>
