<div class="playlist_column">
    <div class="album_header">
        <div class="playlist_img_ext">
            <div class="playlist_img">
				<?php if(intval($this->Playlist["idPlaylist"])<10):?>
				<img src="/images/moods/<?php echo strtolower(str_replace(' ', '', $this->Playlist["Titolo"])).".jpg";?>" style="width:100%">
				<?php else:?>
					<?php if(count($this->Images)<4):?>
					<img src="/images/albums/<?php echo ((!empty($this->Images[0]))?$this->Images[0]:"defaultAlbum.jpg");?>" style="width:100%">
					<?php else:?>
					<div class="playlist_top_img">
						<div class="album_img_playlist"><img src="/images/albums/<?php echo $this->Images[0];?>" style="width:100%"></div>
						<div class="album_img_playlist"><img src="/images/albums/<?php echo $this->Images[1];?>" style="width:100%"></div>
					</div>
					<div class="playlist_bottom_img">
						<div class="album_img_playlist"><img src="/images/albums/<?php echo $this->Images[2];?>" style="width:100%"></div>
						<div class="album_img_playlist"><img src="/images/albums/<?php echo $this->Images[3];?>" style="width:100%"></div>
					</div>
					<?php endif;?>
				<?php endif;?>
            </div>
            <div class="button_play"><i class="fas fa-play-circle play_playlist" data-playlist="<?php echo $this->Playlist["idPlaylist"];?>"></i></div>
        </div>
        <div class="playlist_info">
            <span class="playlist_title"><?php echo $this->Playlist["Titolo"]?></span>
            <span class="playlist_autor"><?php echo $this->Playlist["Nickname"]?></span>
            <div class="checkbox_casual">
				<?if($this->Posseduta):?>
                <div class="input-group">
                    <i id="checkboxPlaylistCondivisa" style="line-height: 24px; margin-right: 5px;" class="far <?php echo ($this->Playlist["Condivisa"]==1)?"fa-check-square":"fa-square" ?>"></i>
                    <span class="album_data">Playlist pubblica</span>
                </div>
				<?php endif;?>
            </div>
        </div>
    </div>
    <table class="table table-hover brani_list">

        <thead>
            <tr>
                <th scope="col">Brano</th>
                <th scope="col">Artista</th>
                <th scope="col">Album</th>
                <th scope="col">Genere</th>
                <th scope="col">Durata</th>
            </tr>
        </thead>
        <tbody>
			<?php $durTot=0; $count=0;?>
			<?php foreach($this->Brani as $brano):?>
            <tr>
                <td><i class="fas fa-play-circle play_song" data-song="<?php echo $brano["idBrano"]?>"></i> <a class="play_song" data-song="<?php echo $brano["idBrano"]?>"><?php echo $brano["Titolo"]?></a></td>
                <td><?php
				echo implode('<span>, </span>', array_map(function($v) {
						return '<a class="center_link" data-link="artist" data-exec="artistPage" data-params="'.$v['id'].'" data-addict="'.$v['id'].'">'.$v['Name'].'</a>';
					}, $brano['Artisti']));
				?>
                <td><a class="center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $brano["idAlbum"]?>"><?php echo $brano["Nome"]?></a></td>
                <td><a class="center_link" data-link="genre" data-addict="<?php echo $brano["idGenere"]?>"><?php echo $brano["Testo"]?></a></td>
                <td><?php $dur=intval($brano["Durata"]); $durTot+=$dur; $count++; $min=intval($dur/60); $sec=$dur%60; if($sec<10) $sec="0".$sec; elseif($sec==0) $sec="00"; echo $min.":".$sec; ?></td>
            </tr>
			<?php endforeach;?>
			<caption id="playlist_tot">
			<?php
				$min=intval($durTot/60); 
				$sec=$durTot%60;
				echo "<span>$count</span> ".(($count==1)?"brano":"brani")." durata totale: <span>$min</span> ".(($min==1)?"minuto":"minuti")." e <span>$sec</span> ".(($sec==1)?"secondo":"secondi").".";
			?>
		</caption>
    </table>
</div>
