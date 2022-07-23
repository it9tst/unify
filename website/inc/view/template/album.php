<div class="row" id="album_container">
    <div class="col-9">
		<div class="album_column1">
			<div class="album_header">
				<div class="album_cover" style="background-image:url('images/albums/<?php echo empty($this->Album["Image"])? "defaultAlbum.jpg" :$this->Album["Image"];?>')">
					<div class="button_play"><i class="fas fa-play-circle play_album" data-album="<?php echo $this->Album["idAlbum"];?>"></i></div>
				</div>
				<div class="album_info">
					<span class="album_title"><?php echo $this->Album["Nome"];?></span>
					<div class="artista_c_n_box">
						<?php foreach($this->Artisti as $artista):?>
						<div class="artista_c_n">
							<div class="artista_cover" style="background-image:url('images/artists/<?php echo empty($artista["Image"])? "defaultArtist.jpg" :$artista["Image"];?>')"></div>
							<span class="artista_nome center_link" data-link="artist" data-exec="artistPage" data-params="<?php echo $artista["idArtist"];?>" data-addict="<?php echo $artista["idArtist"];?>"><a><?php echo $artista["Name"];?></a></span>
						</div>
						<?php endforeach; ?>
					</div>
					<span class="album_genere">Genere: <?php echo implode(", ",array_map(function($val){ return('<a class="album_genere center_link" data-link="genre" data-addict="'.$val["idGenere"].'">'.$val["Testo"]."</a>" );},$this->Generi));?></span>
					<span class="album_data">Data di uscita: <?php echo $this->Album["Anno"];?></span>
					<span class="album_etichetta">®<?php echo date("Y",strtotime($this->Album["Anno"]))." ".$this->Album["Etichetta"];?></span>
				</div>
			</div>
			<table class="table table-hover brani_list">

				<thead>
					<tr>
						<th scope="col">Brano</th>
						<th scope="col">Artista</th>
						<th scope="col">Durata</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php $b=""; $durTot=0; $count=0;?>
					<?php foreach($this->Brani as $brano):?>
					<?php if($brano["idBrano"]==$b)continue; else {$b=$brano["idBrano"]; $durTot+=intval($brano["Durata"]); $count++;}?>
					<tr>
						<td><i class="fas fa-play-circle play_song" style="color: #343434;" data-song="<?php echo $brano["idBrano"];?>"></i> <a class="play_song" data-song="<?php echo $brano["idBrano"];?>"><?php echo $brano["Titolo"];?></a></td>
						<td><?php
					echo implode(', ', array_map(function($v) {
							return '<a class="center_link" data-link="artist" data-params="'.$v['id'].'" data-exec="artistPage" data-addict="'.$v['id'].'">'.$v['Name'].'</a>';
						}, $brano['Artisti']));
					?></td>
						<td><?php $dur=intval($brano["Durata"]); $min=intval($dur/60); $sec=$dur%60; if($sec<10) $sec="0".$sec; elseif($sec==0) $sec="00"; echo $min.":".$sec; ?></td>
						<td style="text-align: center;width: 70px;"><i class="btnSong fas fa-<?php echo($brano["added"])? "check":"plus" ?>" data-id="<?php echo $brano["idBrano"];?>" style="color: #343434;"></i></td>
					</tr>
				
					<?php endforeach;?>
				</tbody>
				<caption>
					<?php
						$min=intval($durTot/60); $sec=$durTot%60;
						$s=$count;
						if($s==1) $s=$s." brano"; else $s=$s." brani";
						$s=$s.", durata totale: ".$min;
						if($min==1)$s=$s." minuto e "; else $s=$s." minuti e ";
						$s=$s.$sec;
						if($sec==1)$s=$s." secondo."; else $s=$s." secondi.";
						echo $s;
					?>
				</caption>
			</table>
		</div>
    </div>
    <div class="col-3">
		<div class="album_column2">
			<div class="suggerimenti_list">
				<div class="artisti_correlati">Artisti correlati</div>
				<div style="margin:12px;"><?php if(empty($this->ArtistiCorrelati))echo"Non ci sono artisti correlati all'album corrente";?></div>
				<?php foreach($this->ArtistiCorrelati as $artCorr):?>
					<div class="artista_c_n_s">
						<div class="artista_cover" style="background-image:url('images/artists/<?php echo empty($artCorr["Image"])? "defaultArtist.jpg" :$artCorr["Image"];?>')"></div>
						<a class="artista_nome_corr center_link" data-link="artist" data-exec="artistPage" data-params="<?php echo $artCorr["idArtist"];?>" data-addict="<?php echo $artCorr["idArtist"];?>"><?php echo $artCorr["Name"];?></a>
					</div>
				<?php endforeach;?>
			</div>
			<div class="suggerimenti_list">
				<div class="album_correlati">Album correlati</div>
				<div style="margin:12px;"><?php if(empty($this->AlbumCorrelati))echo"Non ci sono album correlati all'album corrente";?></div>
				<?php foreach($this->AlbumCorrelati as $albCorr):?>
					<div class="album_c_n_s">
						<div class="album_cover_min" style="background-image:url('images/albums/<?php echo empty($albCorr["Image"])? "defaultAlbum.jpg" :$albCorr["Image"];?>')"></div>
						<div class="album_nome_corr">
							<a class="album_nome_corr1 center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $albCorr["idAlbum"]?>"><?php echo $albCorr["Nome"]?></a><br>
							<?php
									echo implode('<span style="color:#a1a1a1">, </span>', array_map(function($v) {
											return '<a class="album_nome_corr2 center_link" data-link="artist" data-exec="artistPage" data-params="'.$v['id'].'" data-addict="'.$v['id'].'">'.$v['Name'].'</a>';
										}, $albCorr['Artisti']));
									?>
						</div>
					</div>
				<?php endforeach;?>
			</div>
		</div>
    </div>
</div>
