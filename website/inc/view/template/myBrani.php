<span class="title_page_1">I tuoi brani preferiti</span>

<div class="my_brani_ext">

	<?php if(count($this->myBrani)<=0):?>
			<div>La tua libreria è vuota! Comincia ad aggiungere qualche brano..</div>
	<?php else:?>
    <table class="table table-hover myBrani_list">
        <thead>
            <tr>
                <th scope="col">Titolo</th>
                <th scope="col">Durata</th>
                <th scope="col">Artista</th>
                <th scope="col">Album</th>
                <th scope="col">Genere</th>
            </tr>
        </thead>
        <tbody>
			<?php foreach($this->myBrani as $brano): ?>
            <tr>
                <td><i class="fas fa-play-circle play_song" data-song="<?php echo $brano["idBrano"]?>"></i> <a class="play_song" data-song="<?php echo $brano["idBrano"]?>"><?php echo $brano["Titolo"]?></a></th>
                <td><?php $dur=intval($brano["Durata"]); $min=intval($dur/60); $sec=$dur%60; if($sec<10) $sec="0".$sec; elseif($sec==0) $sec="00"; echo $min.":".$sec; ?></td>
                <td><?php
				echo implode(', ', array_map(function($v) {
						return '<a class="center_link" data-link="artist" data-exec="artistPage" data-params="'.$v['id'].'" data-addict="'.$v['id'].'">'.$v['Name'].'</a>';
					}, $brano['Artisti']));
				?></td>
                <td><a class="center_link" data-link="album" data-exec="albumPage" data-addict="<?php echo $brano["idAlbum"]?>"><?php echo $brano["Nome"]?></a></td>
                <td><a class="center_link" data-link="genre" data-addict="<?php echo $brano["idGenere"]?>"><?php echo $brano["Testo"]?></a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<?php endif; ?>
</div>
