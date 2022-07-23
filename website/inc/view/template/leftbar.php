<div class="vertical-menu">
    <nav class="nav flex-column first_menu">
        <div class="user_prof">
            <div class="avatar_user">
    	        <div class="user_img" style="background-image:url('images/accounts/<?php echo empty($this->Account->getFoto())? "defaultAccount.jpg" :$this->Account->getFoto();?>')"></div>
    	    </div>
        	<span class="username_dashnav"><?php echo $this->Nickname;?></span>
            <i class="fas fa-sign-out-alt icon_user" id="logoutButton"></i>
            <i class="fas fa-cog left_link icon_user" data-link="account" data-exec="accountSettings"></i>
        </div>
        <hr id="user_div_menu">
        <li class="hidden-nav-xs padder m-t m-b-sm text-xs text-muted first_element">Browse</li>
        <a class="nav-link left_link" data-link="newMusic">
            <div class="nav_elem1"><i class="far fa-music"></i></div>
            <div class="nav_elem2">Nuova musica</div>
        </a>
        <a class="nav-link left_link" data-link="suggestion">
            <div class="nav_elem1"><i class="far fa-headphones"></i></div>
            <div class="nav_elem2">Suggerimenti</div>
        </a>
        <a class="nav-link left_link" data-link="playlists">
            <div class="nav_elem1"><i class="far fa-list"></i></div>
            <div class="nav_elem2">Playlist</div>
        </a>
        <a class="nav-link left_link" data-link="mood">
            <div class="nav_elem1"><i class="far fa-images"></i></div>
            <div class="nav_elem2">Mood</div>
        </a>
        <div class="list_genre">
            <a class="nav-link left_link" id="genre">
                <div class="nav_elem1"><i class="far fa-compact-disc"></i></div>
                <div class="nav_elem2">Generi</div>
            </a>
            <ul class="list_genre_open">
				<?php foreach($this->generi as $genere):?>
				<a class="nav-link left_link" data-link="genre" data-addict="<?php echo $genere["idGenere"]?>"><?php echo $genere["Testo"]?></a>
				<?php endforeach;?>
    		</ul>
        </div>
        <a class="nav-link left_link" data-link="rank">
            <div class="nav_elem1"><i class="far fa-list-ol"></i></i></div>
            <div class="nav_elem2">Classifiche</div>
        </a>
        <li class="hidden-nav-xs padder m-t m-b-sm text-xs text-muted first_element">Libreria</li>
        <a class="nav-link left_link" data-link="myRecently">
            <div class="nav_elem1"><i class="fal fa-calendar-alt"></i></div>
            <div class="nav_elem2">Ascoltati di recente</div>
        </a>
        <a class="nav-link left_link" data-link="myArtist">
            <div class="nav_elem1"><i class="fal fa-microphone-alt"></i></div>
            <div class="nav_elem2">Artisti</div>
        </a>
        <a class="nav-link left_link" data-link="myAlbum">
            <div class="nav_elem1"><i class="fal fa-images"></i></div>
            <div class="nav_elem2">Album</div>
        </a>
        <a class="nav-link left_link" data-link="myBrani" data-exec="myBraniPage">
            <div class="nav_elem1"><i class="fal fa-music"></i></div>
            <div class="nav_elem2">Brani</div>
        </a>
        <div class="list_genre">
            <a class="nav-link left_link" id="myGenre">
                <div class="nav_elem1"><i class="fal fa-compact-disc"></i></div>
                <div class="nav_elem2">Generi</div>
            </a>
			<ul class="list_genre_open" id="list_my_genre">
				<?php foreach($this->myGeneri as $genere):?>
				<a class="nav-link left_link" data-link="myGenre" data-addict="<?php echo $genere["idGenere"]?>"><?php echo $genere["Testo"]?></a>
				<?php endforeach;?>
			</ul>
        </div>
        <a class="nav-link left_link" data-link="myPlaylists" data-exec="loadPlaylist">
            <div class="nav_elem1"><i class="fal fa-list"></i></div>
            <div class="nav_elem2">Playlist</div>
        </a>
    </nav>
    <nav class="nav flex-column" id="playlist_recenti">
        <li class="hidden-nav-xs padder m-t m-b-sm text-xs text-muted first_element">Playlist recenti</li>
    </nav>
</div>
<?php if(!empty($this->myGeneri)):?>

<?php endif;?>
