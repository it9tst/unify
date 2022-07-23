<?php

class Dashboard extends Controller {
    function __construct() {
        parent::__construct();
		$this->addModel("account");
		if($this->loginFromSession()){
			return;
		}
		$this->loginFromCookie();
		$this->redirectNotLogged("https://unify-unipa.it/?action=login");

    }

    function index() {
		$generi=$this->getDatabase()->Select("Genere","1");

		$myGeneri=$this->getDatabase()->Select("Genere G,BranoGenere BG, BraniAggiunti BA","G.idGenere=BG.RefGenere AND BG.RefBrano=BA.RefBrano AND BA.RefAccount=".$this->account->getIdAccount(),Array("DISTINCT G.idGenere","G.Testo"));

		$this->setViewValue("title", "Dashboard - Unify");
		$this->setViewValue("generi",$generi);
		$this->setViewValue("myGeneri",$myGeneri);

		$this->getView()->loadBootstrap();

		$this->getView()->setJsModule("player");
		$this->getView()->setJsModule("playlist");
		$this->getView()->setJsModule("friend");
		$this->getView()->setJsModule("dashboard");

		$this->getView()->setCssModule("dashboard");

		$this->getView()->setCssModule("myBrani");
		$this->getView()->setCssModule("artist");
        $this->getView()->setCssModule("album");
		$this->getView()->setCssModule("playlist");

		$this->renderTemplate('head');

		$this->render('dashboard/index');

	}

	private function newMusic($data=Array()){
		$db=$this->getDatabase();
		$idAcc=$this->account->getIdAccount();
		/** album **/
		$newAlbums=$db->raw("SELECT A.Nome,A.Image,A.idAlbum, GROUP_CONCAT(DISTINCT Ar.idArtist ORDER BY AA.Indice) AS ArtistiId, GROUP_CONCAT(DISTINCT Ar.Name ORDER BY AA.Indice) AS Artisti  FROM (SELECT * FROM Album A WHERE A.NumeroBrani>1) AS A,ArtistiAlbum AA, Artista Ar WHERE A.idAlbum=AA.RefAlbum AND AA.RefArtista=Ar.idArtist GROUP BY A.Nome,A.Image,A.idAlbum ORDER BY A.Anno DESC LIMIT 24");

		if(empty($newAlbums))
			$newAlbums=Array();

		$album=Array();
		foreach($newAlbums as $k=>$v){
			$artisti=explode(",", $v['Artisti']);
			$idArtisti=explode(",", $v['ArtistiId']);

			$album[$v['idAlbum']]['idAlbum']=$v['idAlbum'];
			$album[$v['idAlbum']]['Nome']=$v['Nome'];
			$album[$v['idAlbum']]['Image']=$v['Image'];
			$album[$v['idAlbum']]['Artisti']=Array();

			foreach($artisti as $k1=>$v1){
				$album[$v['idAlbum']]['Artisti'][]=Array("id"=>$idArtisti[$k1], "Name"=>$v1);
			}
		}

		$this->SetViewValue("NewAlbums",$album);
		/** album **/

		/** singoli **/
		$newSingoli=$db->raw("SELECT A.Nome, A.Image,A.idAlbum,BA.RefBrano as idBrano, GROUP_CONCAT(DISTINCT Ar.idArtist ORDER BY AA.Indice) AS ArtistiId, GROUP_CONCAT(DISTINCT Ar.Name ORDER BY AA.Indice) AS Artisti FROM (SELECT * FROM Album A WHERE A.NumeroBrani=1 ORDER BY A.Anno DESC) AS A,ArtistiAlbum AA, Artista Ar,BraniAlbum BA WHERE A.idAlbum=BA.RefAlbum AND A.idAlbum=AA.RefAlbum AND AA.RefArtista=Ar.idArtist GROUP BY A.Nome, A.Image,A.idAlbum,BA.RefBrano ORDER BY A.Anno DESC LIMIT 24");

		if(empty($newSingoli))
			$newSingoli=Array();

		$singoli=Array();
		foreach($newSingoli as $k=>$v){
			$artisti=explode(",", $v['Artisti']);
			$idArtisti=explode(",", $v['ArtistiId']);

			$singoli[$v['idAlbum']]['idAlbum']=$v['idAlbum'];
			$singoli[$v['idAlbum']]['idBrano']=$v['idBrano'];
			$singoli[$v['idAlbum']]['Nome']=$v['Nome'];
			$singoli[$v['idAlbum']]['Image']=$v['Image'];
			$singoli[$v['idAlbum']]['Artisti']=Array();

			foreach($artisti as $k1=>$v1){
				$singoli[$v['idAlbum']]['Artisti'][]=Array("id"=>$idArtisti[$k1], "Name"=>$v1);
			}
		}

		$this->SetViewValue("NewSingoli",$singoli);
		/** singoli **/

		/** playlist **/

		$newPlaylists=$db->raw("SELECT P.idPlaylist, P.Titolo, GROUP_CONCAT(DISTINCT A.Image ORDER BY BP.Indice) AS Image, Ac.Nickname AS Creatore FROM Account Ac JOIN Playlist P ON P.Creatore=Ac.IdAccount JOIN BraniPlaylist BP ON BP.RefPlaylist=P.idPlaylist LEFT JOIN BraniAlbum BA ON BA.RefBrano=BP.RefBrano LEFT JOIN Album A ON A.idAlbum=BA.RefAlbum WHERE P.Condivisa=1 AND P.idPlaylist>9 AND Ac.idAccount<>".$idAcc." GROUP BY P.idPlaylist, P.Titolo ORDER BY P.DataCreazione DESC LIMIT 24");
		$this->SetViewValue("NewPlaylist",$newPlaylists);
		/** playlist **/

		$this->renderTemplate("newMusic");
	}

	private function suggestion($data=Array()){
		$db=$this->getDatabase();
		$idAcc=$this->account->getIdAccount();

		/** album **/
		$suggestionAlbums=$db->raw("SELECT DISTINCT A.Nome,A.Image,A.idAlbum, GROUP_CONCAT(DISTINCT AR.Name) AS Artisti, GROUP_CONCAT(DISTINCT AR.idArtist) AS ArtistiId FROM Album A, BraniAlbum BA, BranoGenere BG, ArtistiAlbum AA, Artista AR WHERE A.NumeroBrani>1 AND A.idAlbum=BA.RefAlbum AND BA.RefBrano=BG.RefBrano AND BG.RefGenere IN (SELECT DISTINCT BG1.RefGenere FROM BraniAggiunti BAG, BranoGenere BG1 WHERE BAG.RefAccount=".$idAcc." AND BAG.RefBrano=BG1.RefBrano) AND BA.RefBrano NOT IN (SELECT DISTINCT BAG.RefBrano FROM BraniAggiunti BAG WHERE BAG.RefAccount=".$idAcc.") AND A.idAlbum=AA.RefAlbum AND AA.RefArtista=AR.idArtist GROUP BY A.Nome,A.Image,A.idAlbum LIMIT 15");

		if(empty($suggestionAlbums))
			$suggestionAlbums=Array();

		$album=Array();
		foreach($suggestionAlbums as $k=>$v){
			$artisti=explode(",", $v['Artisti']);
			$idArtisti=explode(",", $v['ArtistiId']);

			$album[$v['idAlbum']]['idAlbum']=$v['idAlbum'];
			$album[$v['idAlbum']]['Nome']=$v['Nome'];
			$album[$v['idAlbum']]['Image']=$v['Image'];
			$album[$v['idAlbum']]['Artisti']=Array();

			foreach($artisti as $k1=>$v1){
				$album[$v['idAlbum']]['Artisti'][]=Array("id"=>$idArtisti[$k1], "Name"=>$v1);
			}
		}

		$this->SetViewValue("SuggestionAlbums",$album);
		/** album **/


		/** singoli **/

		$suggestionSingoli=$db->raw("SELECT DISTINCT A.Nome,A.Image,A.idAlbum, GROUP_CONCAT(DISTINCT AR.Name) AS Artisti, GROUP_CONCAT(DISTINCT AR.idArtist) AS ArtistiId, BA.RefBrano AS idBrano FROM Album A, BraniAlbum BA, BranoGenere BG, ArtistiAlbum AA, Artista AR WHERE A.NumeroBrani=1 AND A.idAlbum=BA.RefAlbum AND BA.RefBrano=BG.RefBrano AND BG.RefGenere IN (SELECT DISTINCT BG1.RefGenere FROM BraniAggiunti BAG, BranoGenere BG1 WHERE BAG.RefAccount=".$idAcc." AND BAG.RefBrano=BG1.RefBrano) AND BA.RefBrano NOT IN (SELECT DISTINCT BAG.RefBrano FROM BraniAggiunti BAG WHERE BAG.RefAccount=".$idAcc.") AND A.idAlbum=AA.RefAlbum AND AA.RefArtista=AR.idArtist GROUP BY A.Nome,A.Image,A.idAlbum LIMIT 15");


		if(empty($suggestionSingoli))
			$suggestionSingoli=Array();

		$singoli=Array();
		foreach($suggestionSingoli as $k=>$v){
			$artisti=explode(",", $v['Artisti']);
			$idArtisti=explode(",", $v['ArtistiId']);

			$singoli[$v['idAlbum']]['idAlbum']=$v['idAlbum'];
			$singoli[$v['idAlbum']]['idBrano']=$v['idBrano'];
			$singoli[$v['idAlbum']]['Nome']=$v['Nome'];
			$singoli[$v['idAlbum']]['Image']=$v['Image'];
			$singoli[$v['idAlbum']]['Artisti']=Array();

			foreach($artisti as $k1=>$v1){
				$singoli[$v['idAlbum']]['Artisti'][]=Array("id"=>$idArtisti[$k1], "Name"=>$v1);
			}
		}

		$this->SetViewValue("SuggestionSingoli",$singoli);
		/** singoli **/

		/** playlist **/
		$queryPlaylist = <<<EOT
			SELECT P.idPlaylist, P.Titolo, GROUP_CONCAT(DISTINCT A.Image ORDER BY BP.Indice) As Image, Ac.Nickname AS Creatore FROM Account Ac,
			(SELECT P1.idPlaylist, COUNT(BP1.RefBrano) AS Num
			FROM Playlist P1
			JOIN BraniPlaylist BP1 ON BP1.RefPlaylist=P1.idPlaylist
			JOIN BranoGenere BG ON BG.RefBrano= BP1.RefBrano
			WHERE BG.RefGenere IN (SELECT BG1.RefGenere
								FROM BraniAggiunti BA, BranoGenere BG1
								WHERE BA.RefBrano=BG1.RefBrano
								AND BA.RefAccount=$idAcc)
			GROUP BY P1.idPlaylist LIMIT 15) AS Res,
			Playlist P, BraniPlaylist BP, BraniAlbum BA, Album A
			WHERE BP.RefPlaylist=P.idPlaylist
			AND BA.RefBrano=BP.RefBrano
			AND A.idAlbum=BA.RefAlbum
			AND P.idPlaylist=Res.idPlaylist
			AND P.Creatore=Ac.IdAccount
			AND Ac.IdAccount<>$idAcc
			GROUP BY P.idPlaylist, P.Titolo
			ORDER BY Res.Num DESC
EOT;

		$newPlaylists=$db->raw($queryPlaylist);

		$this->SetViewValue("SuggestionPlaylist",$newPlaylists);
		/** playlist **/


		$this->renderTemplate("suggestion");
	}

	private function playlists($data=Array()){
		$db=$this->getDatabase();
		$result=$db->raw("SELECT P.idPlaylist, P.Titolo, GROUP_CONCAT(DISTINCT A.Image) Image, AC.Nickname AS Creatore FROM Account AC JOIN Playlist P ON P.Creatore=AC.IdAccount JOIN BraniPlaylist BP ON BP.RefPlaylist=P.idPlaylist LEFT JOIN BraniAlbum BA ON BA.RefBrano=BP.RefBrano LEFT JOIN Album A ON A.idAlbum=BA.RefAlbum WHERE P.Condivisa=1 AND P.idPlaylist>9 GROUP BY P.idPlaylist, P.Titolo ORDER BY BP.Indice");
		if(empty($result))
			$result=Array();
		$this->SetViewValue("Playlists",$result);
		$this->renderTemplate("playlistPage");
	}

	private function genre($data=Array()){
		$id=$data["addict"];
		$genere=$this->getDatabase()->Select("Genere G","G.idGenere=".$id);


		/** album **/
		$genreAlbum=$this->getDatabase()->Select("AlbumGenere AG,Album A, ArtistiAlbum AA, Artista Ar","AG.RefAlbum=A.idAlbum AND A.idAlbum=AA.RefAlbum AND AA.RefArtista=Ar.idArtist AND A.NumeroBrani>1 AND AG.RefGenere=".$id." GROUP BY A.Nome, A.Image, A.idAlbum ",Array("DISTINCT A.Nome","A.Image","A.idAlbum","GROUP_CONCAT(DISTINCT Ar.Name ORDER BY AA.Indice) AS Artisti","GROUP_CONCAT(DISTINCT Ar.idArtist ORDER BY AA.Indice) AS ArtistiId"));

		if(empty($genreAlbum))
			$genreAlbum=Array();

		$album=Array();
		foreach($genreAlbum as $k=>$v){
			$artisti=explode(",", $v['Artisti']);
			$idArtisti=explode(",", $v['ArtistiId']);

			$album[$v['idAlbum']]['idAlbum']=$v['idAlbum'];
			$album[$v['idAlbum']]['Nome']=$v['Nome'];
			$album[$v['idAlbum']]['Image']=$v['Image'];
			$album[$v['idAlbum']]['Artisti']=Array();

			foreach($artisti as $k1=>$v1){
				$album[$v['idAlbum']]['Artisti'][]=Array("id"=>$idArtisti[$k1], "Name"=>$v1);
			}
		}

		$this->SetViewValue("genreAlbum",$album);
		/** album **/


		/** singoli **/
		$genreSingoli=$this->getDatabase()->Select("AlbumGenere AG,Album A, ArtistiAlbum AA, Artista Ar,BraniAlbum BA","AG.RefAlbum=A.idAlbum AND A.idAlbum=AA.RefAlbum AND BA.RefAlbum=A.idAlbum AND AA.RefArtista=Ar.idArtist AND A.NumeroBrani=1 AND AG.RefGenere=".$id." GROUP BY A.Nome, A.Image, A.idAlbum, BA.RefBrano",Array("A.Nome","A.Image","A.idAlbum","BA.RefBrano as idBrano","GROUP_CONCAT(DISTINCT Ar.Name ORDER BY AA.Indice) AS Artisti","GROUP_CONCAT(DISTINCT Ar.idArtist ORDER BY AA.Indice) AS ArtistiId"));

		if(empty($genreSingoli))
			$genreSingoli=Array();

		$singolo=Array();
		foreach($genreSingoli as $k=>$v){
			$artisti=explode(",", $v['Artisti']);
			$idArtisti=explode(",", $v['ArtistiId']);
			$singolo[$v['idAlbum']]['idAlbum']=$v['idAlbum'];
			$singolo[$v['idAlbum']]['idBrano']=$v['idBrano'];
			$singolo[$v['idAlbum']]['Nome']=$v['Nome'];
			$singolo[$v['idAlbum']]['Image']=$v['Image'];
			$singolo[$v['idAlbum']]['Artisti']=Array();

			foreach($artisti as $k1=>$v1){
				$singolo[$v['idAlbum']]['Artisti'][]=Array("id"=>$idArtisti[$k1], "Name"=>$v1);
			}
		}

		$this->SetViewValue("genreSingoli",$singolo);
		/** singoli **/

		$this->SetViewValue("Genere",$genere);
		$this->renderTemplate("genre");
	}

	private function rank($data=Array()){ // manca playlist
		//implementare query per playlist
		$db=$this->getDatabase();
		$idAcc=$this->account->getIdAccount();

		/** singoli **/
		$topSingoli=$db->raw("SELECT Res.Nome,Res.Image,Res.idBrano, Res.idAlbum, GROUP_CONCAT(DISTINCT Ar.idArtist ORDER BY AB.Indice) AS ArtistiId, GROUP_CONCAT(DISTINCT Ar.Name ORDER BY AB.Indice) AS Artisti FROM ArtistaBrano AB, Artista Ar, (SELECT A.Nome, A.Image,B.idBrano, A.idAlbum, B.TimeCount FROM Album A, Brano B, BraniAlbum BA WHERE A.idAlbum=BA.RefAlbum AND BA.RefBrano=B.idBrano AND A.NumeroBrani=1 ORDER BY B.TimeCount DESC) AS Res WHERE AB.RefArtista=Ar.idArtist AND AB.RefBrano=Res.idBrano GROUP BY Res.Nome,Res.Image,Res.idBrano, Res.idAlbum ORDER BY Res.TimeCount DESC LIMIT 10");

		if(empty($topSingoli))
			$topSingoli=Array();

		$singoli=Array();
		foreach($topSingoli as $k=>$v){
			$artisti=explode(",", $v['Artisti']);
			$idArtisti=explode(",", $v['ArtistiId']);

			$singoli[$v['idAlbum']]['idAlbum']=$v['idAlbum'];
			$singoli[$v['idAlbum']]['idBrano']=$v['idBrano'];
			$singoli[$v['idAlbum']]['Nome']=$v['Nome'];
			$singoli[$v['idAlbum']]['Image']=$v['Image'];
			$singoli[$v['idAlbum']]['Artisti']=Array();

			foreach($artisti as $k1=>$v1){
				$singoli[$v['idAlbum']]['Artisti'][]=Array("id"=>$idArtisti[$k1], "Name"=>$v1);
			}
		}

		$this->SetViewValue("topSingoli",$singoli);
		/** singoli **/

		/** album **/
		$topAlbum=$db->raw("SELECT A.Nome,A.Image,A.idAlbum, GROUP_CONCAT(DISTINCT Ar.idArtist ORDER BY AA.Indice) AS ArtistiId, GROUP_CONCAT(DISTINCT Ar.Name ORDER BY AA.Indice) AS Artisti FROM (SELECT * FROM Album A WHERE A.NumeroBrani>1) AS A,ArtistiAlbum AA, Artista Ar, BraniAlbum BA, Brano B WHERE A.idAlbum=AA.RefAlbum AND AA.RefArtista=Ar.idArtist AND A.idAlbum=BA.RefAlbum AND BA.RefBrano=B.idBrano GROUP BY A.Nome,A.Image,A.idAlbum ORDER BY SUM(B.TimeCount) DESC LIMIT 10");

		if(empty($topAlbum))
			$topAlbum=Array();

		$album=Array();
		foreach($topAlbum as $k=>$v){
			$artisti=explode(",", $v['Artisti']);
			$idArtisti=explode(",", $v['ArtistiId']);

			$album[$v['idAlbum']]['idAlbum']=$v['idAlbum'];
			$album[$v['idAlbum']]['Nome']=$v['Nome'];
			$album[$v['idAlbum']]['Image']=$v['Image'];
			$album[$v['idAlbum']]['Artisti']=Array();

			foreach($artisti as $k1=>$v1){
				$album[$v['idAlbum']]['Artisti'][]=Array("id"=>$idArtisti[$k1], "Name"=>$v1);
			}
		}

		$this->SetViewValue("topAlbum",$album);
		/** album **/

		/** playlist **/
		$queryPlaylist = <<<EOT
			SELECT P.idPlaylist, P.Titolo, GROUP_CONCAT(DISTINCT A.Image ORDER BY BP.Indice) As Image, Ac.Nickname AS Creatore FROM Account Ac,
				(SELECT P1.idPlaylist, SUM(B2.TimeCount) AS Num
				FROM Playlist P1
				JOIN BraniPlaylist BP1 ON BP1.RefPlaylist=P1.idPlaylist
				JOIN Brano B2 ON B2.idBrano= BP1.RefBrano
				WHERE 1
				GROUP BY P1.idPlaylist ORDER BY Num LIMIT 10) AS Res,
			Playlist P, BraniPlaylist BP, BraniAlbum BA, Album A
			WHERE BP.RefPlaylist=P.idPlaylist
			AND BA.RefBrano=BP.RefBrano
			AND A.idAlbum=BA.RefAlbum
			AND P.idPlaylist=Res.idPlaylist
			AND P.Creatore=Ac.IdAccount
			AND Ac.IdAccount<>2
			GROUP BY P.idPlaylist, P.Titolo
			ORDER BY Res.Num DESC
EOT;

		$topPlaylists=$db->raw($queryPlaylist);



		$this->SetViewValue("topPlaylist",$topPlaylists);
		/** playlist **/

		$this->renderTemplate("rank");


	}

	private function myRecently($data=Array()){ // ok
		$idAcc=$this->account->getIdAccount();

		$myRecently=$this->getDatabase()->raw("SELECT Res.Titolo,Res.Image,Res.idBrano,Res.idAlbum,GROUP_CONCAT(DISTINCT Ar.idArtist ORDER BY AB.Indice) AS ArtistiId, GROUP_CONCAT(DISTINCT Ar.Name ORDER BY AB.Indice) AS Artisti FROM (SELECT B.Titolo,A.Image, B.idBrano, A.idAlbum,ASCO.DataAscolto FROM  Brano B, Ascoltati ASCO,BraniAlbum BA, Album A WHERE BA.RefBrano=B.idBrano AND B.idBrano=ASCO.RefBrano AND A.idAlbum=BA.RefAlbum AND ASCO.RefAccount=".$idAcc.") AS Res, ArtistaBrano AB, Artista Ar WHERE AB.RefArtista=Ar.idArtist AND AB.RefBrano=Res.idBrano GROUP BY Res.Titolo,Res.idBrano ORDER BY Res.DataAscolto DESC LIMIT 15");

		if(empty($myRecently))
			$myRecently=Array();

		$recently=Array();
		foreach($myRecently as $k=>$v){
			$artisti=explode(",", $v['Artisti']);
			$idArtisti=explode(",", $v['ArtistiId']);

			$recently[$v['idBrano']]['idAlbum']=$v['idAlbum'];
			$recently[$v['idBrano']]['idBrano']=$v['idBrano'];
			$recently[$v['idBrano']]['Titolo']=$v['Titolo'];
			$recently[$v['idBrano']]['Image']=$v['Image'];
			$recently[$v['idBrano']]['Artisti']=Array();

			foreach($artisti as $k1=>$v1){
				$recently[$v['idBrano']]['Artisti'][]=Array("id"=>$idArtisti[$k1], "Name"=>$v1);
			}
		}

		$this->SetViewValue("myRecently",$recently);
		$this->renderTemplate("myRecently");
	}

	private function myArtist($data=Array()){
		$db=$this->getDatabase();
		$idAcc=$this->account->getIdAccount();


		$artisti=$db->Select("ArtistiPiaciuti AP, Artista Ar","AP.RefArtista=Ar.idArtist AND AP.RefAccount=".$idAcc,Array("Ar.Name","Ar.Image","Ar.idArtist"));

		if(empty($artisti))
			$artisti=Array();
		$this->setViewValue("Artisti",$artisti);
		$this->renderTemplate("myArtist");
	}

	private function myAlbum($data=Array()){ // paginazione
		$idAcc=$this->account->getIdAccount();

		/** album **/
		$myAlbum=$this->getDatabase()->Select("Album A, BraniAlbum AB, BraniAggiunti B,ArtistiAlbum AA, Artista Ar", "A.idAlbum=AB.RefAlbum AND AB.RefBrano=B.RefBrano AND A.idAlbum=AA.RefAlbum AND AA.RefArtista=Ar.idArtist AND B.RefAccount=".$idAcc." AND A.NumeroBrani>1 GROUP BY A.Nome,A.Image,A.idAlbum",Array("A.Nome","A.Image","A.idAlbum","GROUP_CONCAT(DISTINCT Ar.idArtist ORDER BY AA.Indice) AS ArtistiId", "GROUP_CONCAT(DISTINCT Ar.Name ORDER BY AA.Indice) AS Artisti"));

		if(empty($myAlbum))
			$myAlbum=Array();

		$album=Array();
		foreach($myAlbum as $k=>$v){
			$artisti=explode(",", $v['Artisti']);
			$idArtisti=explode(",", $v['ArtistiId']);

			$album[$v['idAlbum']]['idAlbum']=$v['idAlbum'];
			$album[$v['idAlbum']]['Nome']=$v['Nome'];
			$album[$v['idAlbum']]['Image']=$v['Image'];
			$album[$v['idAlbum']]['Artisti']=Array();

			foreach($artisti as $k1=>$v1){
				$album[$v['idAlbum']]['Artisti'][]=Array("id"=>$idArtisti[$k1], "Name"=>$v1);
			}
		}

		$this->SetViewValue("Album",$album);
		/** album **/

		/** singoli **/
		$mySingoli=$this->getDatabase()->Select("Album A, BraniAlbum AB, BraniAggiunti B,ArtistiAlbum AA, Artista Ar", "A.idAlbum=AB.RefAlbum AND AB.RefBrano=B.RefBrano AND A.idAlbum=AA.RefAlbum AND AA.RefArtista=Ar.idArtist AND B.RefAccount=".$idAcc." AND A.NumeroBrani=1 GROUP BY A.Nome,A.Image,A.idAlbum",Array("A.Nome","A.Image","A.idAlbum","AB.RefBrano as idBrano","GROUP_CONCAT(DISTINCT Ar.idArtist ORDER BY AA.Indice) AS ArtistiId", "GROUP_CONCAT(DISTINCT Ar.Name ORDER BY AA.Indice) AS Artisti"));

		if(empty($mySingoli))
			$mySingoli=Array();

		$singoli=Array();
		foreach($mySingoli as $k=>$v){
			$artisti=explode(",", $v['Artisti']);
			$idArtisti=explode(",", $v['ArtistiId']);

			$singoli[$v['idAlbum']]['idAlbum']=$v['idAlbum'];
			$singoli[$v['idAlbum']]['idBrano']=$v['idBrano'];
			$singoli[$v['idAlbum']]['Nome']=$v['Nome'];
			$singoli[$v['idAlbum']]['Image']=$v['Image'];
			$singoli[$v['idAlbum']]['Artisti']=Array();

			foreach($artisti as $k1=>$v1){
				$singoli[$v['idAlbum']]['Artisti'][]=Array("id"=>$idArtisti[$k1], "Name"=>$v1);
			}
		}

		$this->SetViewValue("Singoli",$singoli);
		/** singoli **/

		$this->renderTemplate("myAlbum");
	}

	public function myBrani($data=Array()){
		$idAcc=$this->account->getIdAccount();

		/** brani **/
		$myBrani=$this->getDatabase()->Select("Brano B,Genere AS G, BraniAlbum BA,Album A,ArtistaBrano AB, Artista Ar,BraniAggiunti BAgg"," B.idBrano=BA.RefBrano AND BA.RefAlbum=A.idAlbum AND AB.RefBrano=B.idBrano AND AB.RefArtista=Ar.idArtist AND BAgg.RefBrano=B.idBrano AND BAgg.RefAccount=".$idAcc." AND G.idGenere = (select BG.RefGenere FROM Genere G, BranoGenere BG WHERE B.idBrano=BG.RefBrano LIMIT 1) GROUP BY B.idBrano,B.Titolo, B.Durata ORDER BY B.Titolo",Array("DISTINCT B.idBrano","B.Titolo"," B.Durata","A.Nome","G.Testo","A.idAlbum","G.idGenere","GROUP_CONCAT(DISTINCT Ar.idArtist ORDER BY AB.Indice) AS ArtistiId", "GROUP_CONCAT(DISTINCT Ar.Name ORDER BY AB.Indice) AS Artisti"));


		if(empty($myBrani))
			$myBrani=Array();

		$brani=Array();
		foreach($myBrani as $k=>$v){
			$artisti=explode(",", $v['Artisti']);
			$idArtisti=explode(",", $v['ArtistiId']);

			$brani[$v['idBrano']]['idAlbum']=$v['idAlbum'];
			$brani[$v['idBrano']]['idBrano']=$v['idBrano'];
			$brani[$v['idBrano']]['Durata']=$v['Durata'];
			$brani[$v['idBrano']]['idGenere']=$v['idGenere'];
			$brani[$v['idBrano']]['Titolo']=$v['Titolo'];
			$brani[$v['idBrano']]['Testo']=$v['Testo'];
			$brani[$v['idBrano']]['Nome']=$v['Nome'];
			$brani[$v['idBrano']]['Artisti']=Array();

			foreach($artisti as $k1=>$v1){
				$brani[$v['idBrano']]['Artisti'][]=Array("id"=>$idArtisti[$k1], "Name"=>$v1);
			}
		}

		$this->SetViewValue("myBrani",$brani);
		/** brani **/

		$this->renderTemplate("myBrani");

	}

	private function myGenre($data=Array()){
		$id=$data["addict"];
		$genere=$this->getDatabase()->Select("Genere G","G.idGenere=".$id);
		$idAcc=$this->account->getIdAccount();

		$myGenreAlbum=$this->getDatabase()->Select("AlbumGenere AG,Album A, ArtistiAlbum AA, Artista Ar, BraniAggiunti BAgg, BraniAlbum BA","BA.RefAlbum=A.idAlbum AND BA.RefBrano=BAgg.RefBrano AND BAgg.RefAccount=".$idAcc." AND AG.RefAlbum=A.idAlbum AND A.NumeroBrani>1 AND A.idAlbum=AA.RefAlbum AND AA.RefArtista=Ar.idArtist AND AG.RefGenere=".$id." ORDER BY A.idAlbum",Array("DISTINCT A.Nome","A.Image","A.idAlbum","Ar.Name","Ar.idArtist"));

		$myGenreSingoli=$this->getDatabase()->Select("AlbumGenere AG,Album A, ArtistiAlbum AA, Artista Ar, BraniAggiunti BAgg, BraniAlbum BA","BA.RefAlbum=A.idAlbum AND BA.RefBrano=BAgg.RefBrano AND BAgg.RefAccount=".$idAcc." AND AG.RefAlbum=A.idAlbum AND A.NumeroBrani=1 AND A.idAlbum=AA.RefAlbum AND AA.RefArtista=Ar.idArtist AND AG.RefGenere=".$id." ORDER BY A.idAlbum",Array("DISTINCT A.Nome","A.Image","A.idAlbum","Ar.Name","Ar.idArtist,BA.RefBrano as idBrano"));

		if(empty($myGenreAlbum))
			$myGenreAlbum=Array();
		if(empty($myGenreSingoli))
			$myGenreSingoli=Array();

		$album=Array();
		foreach($myGenreAlbum as $k=>$v){
			if(isset($album[$v['idAlbum']])){
				$album[$v['idAlbum']]['Artisti'][]=Array("id"=>$v['idArtist'], "Name"=>$v['Name']);
			}else{
				$album[$v['idAlbum']]['idAlbum']=$v['idAlbum'];
				$album[$v['idAlbum']]['Nome']=$v['Nome'];
				$album[$v['idAlbum']]['Image']=$v['Image'];
				$album[$v['idAlbum']]['Artisti']=Array(Array("id"=>$v['idArtist'], "Name"=>$v['Name']));
			}
		}
		$singolo=Array();
		foreach($myGenreSingoli as $k=>$v){
			if(isset($singolo[$v['idAlbum']])){
				$singolo[$v['idAlbum']]['Artisti'][]=Array("id"=>$v['idArtist'], "Name"=>$v['Name']);
			}else{
				$singolo[$v['idAlbum']]['idAlbum']=$v['idAlbum'];
				$singolo[$v['idAlbum']]['Nome']=$v['Nome'];
				$singolo[$v['idAlbum']]['Image']=$v['Image'];
				$singolo[$v['idAlbum']]['idBrano']=$v['idBrano'];
				$singolo[$v['idAlbum']]['Artisti']=Array(Array("id"=>$v['idArtist'], "Name"=>$v['Name']));
			}
		}
		$this->SetViewValue("myGenreAlbum",$album);
		$this->SetViewValue("myGenreSingoli",$singolo);
		$this->SetViewValue("Genere",$genere);
		$this->renderTemplate("myGenre");
	}

	private function myPlaylists($data=Array()){
		$db=$this->getDatabase();
		$result=$db->raw("SELECT P.idPlaylist, P.Titolo, GROUP_CONCAT(DISTINCT A.Image ORDER BY BP.Indice ASC) Image FROM Playlist P LEFT JOIN BraniPlaylist BP ON BP.RefPlaylist=P.idPlaylist LEFT JOIN BraniAlbum BA ON BA.RefBrano=BP.RefBrano LEFT JOIN Album A ON A.idAlbum=BA.RefAlbum WHERE P.Creatore=".$this->account->getIdAccount()." GROUP BY P.idPlaylist, P.Titolo ORDER BY BP.Indice ASC");
		if(empty($result))
			$result=Array();
		$this->SetViewValue("Playlists",$result);
		$this->renderTemplate("myPlaylistPage");
	}



	private function album($data=Array()){

		$db=$this->getDatabase();
		$idAlbum=$data["addict"];
		$album=$db->Select("Album A","A.idAlbum=".$idAlbum,Array("A.idAlbum","A.Nome","A.Image","A.Anno","A.Etichetta"));
		$artisti=$db->Select("ArtistiAlbum AA, Artista Ar","AA.RefArtista=Ar.idArtist AND AA.RefAlbum=".$idAlbum,Array("DISTINCT Ar.Image","Ar.Name","Ar.idArtist"));
		$generi=$db->Select("AlbumGenere AG, Genere G","AG.RefGenere=G.idGenere AND AG.RefAlbum=".$idAlbum,Array("G.Testo","G.idGenere"));
		$braniAlbum=$db->Select("Brano B, BraniAlbum BA, ArtistaBrano AB, Artista Ar","B.idBrano=BA.RefBrano AND AB.RefBrano=B.idBrano AND AB.RefArtista=Ar.idArtist AND BA.RefAlbum=".$idAlbum." ORDER BY BA.Indice",Array("DISTINCT B.Titolo","B.Durata","Ar.Name","B.idBrano","Ar.idArtist"));
		$artistiCorrelati=$db->raw("SELECT DISTINCT Ar.Name,Ar.Image,Ar.idArtist FROM Artista Ar, ArtistiAlbum AA, AlbumGenere AG WHERE Ar.idArtist=AA.RefArtista AND AA.RefAlbum=AG.RefAlbum AND AG.RefGenere in (SELECT AG2.RefGenere FROM AlbumGenere AG2 WHERE AG2.RefAlbum=".$idAlbum.") AND Ar.idArtist not in (SELECT AA2.RefArtista FROM ArtistiAlbum AA2 WHERE AA2.RefAlbum=".$idAlbum.") ORDER BY Ar.idArtist LIMIT 3");
		$albumCorrelati=$db->raw("SELECT Ar.Name,A.Nome, A.idAlbum,A.Image,Ar.idArtist FROM Artista Ar,ArtistiAlbum AA,Album A, AlbumGenere AG WHERE Ar.idArtist=AA.RefArtista AND AA.RefAlbum<>".$idAlbum." AND AA.RefAlbum=AG.RefAlbum AND A.idAlbum=AG.RefAlbum AND AG.RefGenere in(SELECT AG2.RefGenere FROM AlbumGenere AG2 WHERE AG2.RefAlbum=".$idAlbum.") ORDER BY A.idAlbum LIMIT 3");

		if(empty($brani))
			$brani=Array();
		if(empty($album))
			$album=Array();
		if(empty($artisti))
			$artisti=Array();
		if(empty($generi))
			$generi=Array();
		if(empty($artistiCorrelati))
			$artistiCorrelati=Array();
		if(empty($albumCorrelati))
			$albumCorrelati=Array();

		$brani=Array();
		foreach($braniAlbum as $k=>$v){
			if(isset($brani[$v['idBrano']])){
				$brani[$v['idBrano']]['Artisti'][]=Array("id"=>$v['idArtist'], "Name"=>$v['Name']);
			}else{
				$brani[$v['idBrano']]['idBrano']=$v['idBrano'];
				$brani[$v['idBrano']]['Titolo']=$v['Titolo'];
				$brani[$v['idBrano']]['Durata']=$v['Durata'];
				$brani[$v['idBrano']]['Artisti']=Array(Array("id"=>$v['idArtist'], "Name"=>$v['Name']));
			}
		}
		foreach($brani as $k=>$v){
			if(empty($db->Select("BraniAggiunti BA","BA.RefBrano=".$v["idBrano"]." AND BA.RefAccount=".$this->account->getIdAccount()))) {
				$brani[$k]["added"]=false;
			}
			else{
				$brani[$k]["added"]=true;
			}
		}
		$albums=Array();
		foreach($albumCorrelati as $k=>$v){
			if(isset($albums[$v['idAlbum']])){
				$albums[$v['idAlbum']]['Artisti'][]=Array("id"=>$v['idArtist'], "Name"=>$v['Name']);
			}else{
				$albums[$v['idAlbum']]['idAlbum']=$v['idAlbum'];
				$albums[$v['idAlbum']]['Nome']=$v['Nome'];
				$albums[$v['idAlbum']]['Image']=$v['Image'];
				$albums[$v['idAlbum']]['Artisti']=Array(Array("id"=>$v['idArtist'], "Name"=>$v['Name']));
			}
		}


		$this->SetViewValue("Album",$album[0]);
		$this->SetViewValue("Generi",$generi);
		$this->SetViewValue("Brani",$brani);
		$this->SetViewValue("Artisti",$artisti);
		$this->SetViewValue("ArtistiCorrelati",$artistiCorrelati);
		$this->SetViewValue("AlbumCorrelati",$albums);
		$this->renderTemplate("album");
	}

	private function artist($data=Array()){
		$idArt=$data["addict"];
		$db=$this->getDatabase();
		$artist=$db->Select("Artista Ar","Ar.idArtist=".$idArt);
		if(empty($db->Select("ArtistiPiaciuti AP","AP.RefArtista=".$idArt." AND AP.RefAccount=".$this->account->getIdAccount()))){
			$aggiunto="false";
		}
		else{
			$aggiunto="true";
		}
		$last=$db->raw("SELECT B.idBrano, B.Titolo,A.Image, B.Anno, A.idAlbum FROM Brano B, BraniAlbum BA, Album A, ArtistaBrano AB WHERE B.idBrano=BA.RefBrano AND BA.RefAlbum=A.idAlbum AND AB.RefBrano=B.idBrano AND AB.RefArtista=".$idArt." ORDER BY B.Anno DESC");
		$popolari=$db->raw("SELECT B.idBrano,B.Titolo,A.Nome,G.Testo,B.Durata,G.idGenere,A.idAlbum FROM Brano B, ArtistaBrano AB,BranoGenere BG, Genere G,BraniAlbum BA, Album A WHERE B.idBrano=AB.RefBrano AND AB.RefArtista=".$idArt." AND BG.RefBrano=B.idBrano AND BG.RefGenere=G.idGenere AND BA.RefBrano=B.idBrano AND BA.RefAlbum=A.idAlbum ORDER BY B.TimeCount DESC");
		$albums=$db->Select("Album A, ArtistiAlbum AA","A.idAlbum=AA.RefAlbum AND AA.RefArtista=".$idArt." AND A.NumeroBrani>1 ORDER BY A.idAlbum",Array("A.idAlbum","A.Nome","A.Anno","A.Image"));
		$singoli=$db->Select("Album A, ArtistiAlbum AA","A.idAlbum=AA.RefAlbum AND AA.RefArtista=".$idArt." AND A.NumeroBrani=1 ORDER BY A.idAlbum",Array("A.idAlbum","A.Nome","A.Anno","A.Image"));

		foreach($popolari as $k=>$v){
			if(empty($db->Select("BraniAggiunti BA","BA.RefBrano=".$v["idBrano"]." AND BA.RefAccount=".$this->account->getIdAccount()))) {
				$popolari[$k]["added"]=false;
			}
			else{
				$popolari[$k]["added"]=true;
			}
		}

		if(empty($albums))
			$albums=Array();
		if(empty($singoli))
			$singoli=Array();


		$playlist=$db->raw("SELECT P.idPlaylist, P.Titolo, GROUP_CONCAT(DISTINCT AL.Image ORDER BY BP.Indice) AS Image, AC.Nickname AS Creatore FROM Playlist P, BraniPlaylist BP, Account AC, BraniAlbum BA, Album AL WHERE P.idPlaylist=BP.RefPlaylist AND AC.IdAccount=P.Creatore AND BP.RefBrano= BA.RefBrano AND BA.RefAlbum=AL.idAlbum AND P.idPlaylist IN (SELECT DISTINCT BP1.RefPlaylist FROM BraniPlaylist BP1, ArtistaBrano AB1 WHERE BP1.RefBrano=AB1.RefBrano AND AB1.RefArtista=".$idArt.") GROUP BY P.idPlaylist");
		if(empty($playlist))
			$playlist=Array();

		$this->setViewValue("Last",$last[0]);
		$this->SetViewValue("Aggiunto",$aggiunto);
		$this->SetViewValue("Artist",$artist[0]);
		$this->SetViewValue("Popolari",$popolari);
		$this->SetViewValue("Albums",$albums);
		$this->SetViewValue("Singoli",$singoli);
		$this->SetViewValue("Playlist",$playlist);
		$this->renderTemplate("artist");
	}
	private function mood(){
		$moods=$this->getDatabase()->Select("Playlist P","P.Creatore=1 ORDER BY DataCreazione Asc LIMIT 9");

		$this->SetViewValue("Moods",$moods);
		$this->renderTemplate("mood");
	}


	private function account($data=Array()){
		$account=$this->getDatabase()->Select("Account A","A.idAccount=".$this->account->getIdAccount())[0];
		if($account["Nome"]!="" && $account["Nome"]!=$this->account->getNome())
			$this->account->setNome($account["Nome"]);
		if($account["Cognome"]!="" && $account["Cognome"]!=$this->account->getCognome())
			$this->account->setCognome($account["Cognome"]);
		if($account["Paese"]!="")
			$this->account->setPaese($account["Paese"]);
		if($account["Cellulare"]!="" && $account["Nome"]!=$this->account->getNome())
			$this->account->setCellulare($account["Cellulare"]);
		if($account["Foto"]!="")
			$this->account->setFoto($account["Foto"]);
		if($account["DataNascita"]!=$this->account->getDataNascita())
			$this->account->setDataNascita($account["DataNascita"]);

		$this->SetViewValue("account",$this->account);
		$this->renderTemplate("account");
	}



	public function searchAjax($data=Array()){
		if(empty($data["key"])){
			$this->getView()->setJsonValue('error', "Inserisci una chiave di ricerca corretta");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$db=$this->getDatabase();
		$key=$data["key"];
		$artistiCercati=$db->Select("Artista","Name LIKE '%".$key."%'");
		$albumCercati=$db->Select("Album A, ArtistiAlbum AA, Artista Ar","A.NumeroBrani>1 AND A.idAlbum=AA.RefAlbum AND AA.RefArtista=Ar.idArtist AND A.Nome LIKE '%".$key."%'",Array("A.*","Ar.Name","Ar.idArtist"));
		$braniCercati=$db->Select("Brano B, BraniAlbum BA,Album A, ArtistiAlbum AA, Artista Ar","B.idbrano=BA.RefBrano AND BA.RefAlbum=A.idAlbum AND AA.RefAlbum=A.idAlbum AND AA.RefArtista=Ar.idArtist AND B.Titolo LIKE '%".$key."%'",Array("B.idBrano","B.Titolo","A.Image","Ar.Name","Ar.idArtist","A.idAlbum"));
		$playlists=$db->Select("Playlist","Titolo LIKE '%".$key."%'");
		if(empty($artistiCercati))
			$artistiCercati=Array();
		if(empty($albumCercati))
			$albumCercati=Array();
		if(empty($braniCercati))
			$braniCercati=Array();
		if(empty($playlist))
			$playlists=Array();


		$album=Array();
		foreach($albumCercati as $k=>$v){
			if(isset($album[$v['idAlbum']])){
				$album[$v['idAlbum']]['Artisti'][]=Array("id"=>$v['idArtist'], "Name"=>$v['Name']);
			}else{
				$album[$v['idAlbum']]['idAlbum']=$v['idAlbum'];
				$album[$v['idAlbum']]['Nome']=$v['Nome'];
				$album[$v['idAlbum']]['Image']=$v['Image'];
				$album[$v['idAlbum']]['Artisti']=Array(Array("id"=>$v['idArtist'], "Name"=>$v['Name']));
			}
		}
		$brani=Array();
		foreach($braniCercati as $k=>$v){
			if(isset($brani[$v['idBrano']])){
				$brani[$v['idBrano']]['Artisti'][]=Array("id"=>$v['idArtist'], "Name"=>$v['Name']);
			}else{
				$brani[$v['idBrano']]['idBrano']=$v['idBrano'];
				$brani[$v['idBrano']]['Titolo']=$v['Titolo'];
				$brani[$v['idBrano']]['Image']=$v['Image'];
				$brani[$v['idBrano']]['idAlbum']=$v['idAlbum'];
				$brani[$v['idBrano']]['Artisti']=Array(Array("id"=>$v['idArtist'], "Name"=>$v['Name']));
			}
		}


		$this->SetViewValue("Key",$key);
		$this->SetViewValue("Artists",$artistiCercati);
		$this->SetViewValue("Albums",$album);
		$this->SetViewValue("Brani",$brani);
		$this->SetViewValue("playlists",$playlists);
		$this->renderTemplate("search");

	}






	public function updateAccountSettingsAjax($data=Array()){ //funzione che controlla le informazioni da modificare dell'account e risponde alla chiamata AJAX
		$edited=Array();
		$idAcc=$this->account->getIdAccount();
		try{
			if(!empty($this->getPost("pass")) && !empty($this->getPost("repass"))){
				$this->checkPass($this->getPost("pass"),$this->getPost("repass"));
				$edited['Password']=$this->cryptPassword($this->getPost("pass"),$this->account->getNickname());
			}
			if(null !==$this->getPost("nome")){
				$edited['Nome']=$this->getPost("nome");
				$this->account->setNome($edited["Nome"]);
			}
			if(null !==$this->getPost("cognome")){
				$edited['Cognome']=$this->getPost("cognome");
				$this->account->setCognome($edited["Cognome"]);
			}
			if(null!==$this->getPost("paese")){

				$edited['Paese']=$this->getPost("paese");
				$this->account->setPaese($edited['Paese']);
			}
			if(null!==$this->getPost("cell")){
					$cell=$this->getPost("cell");
					//$cell=str_replace('+ ','+','+'.$this->getPost("cell"));
					$edited['Cellulare']=$cell;
					$this->account->setCellulare($cell);
			}
			if(!empty($this->getPost("dataNascita"))){
				$edited['DataNascita']=$this->getPost("dataNascita");
				$this->account->setDataNascita($edited['DataNascita']);
			}

			if(!empty($_FILES["image"])){
				if(!(trim($_FILES["image"]["name"])=="" || !in_array($_FILES["image"]["type"], Array("image/jpeg", "image/jpg", "image/png")) || !is_uploaded_file($_FILES["image"]["tmp_name"]) || $_FILES["image"]["error"]>0)){
					$dir="/home2/unifyuls/public_html/dashboard/images/accounts/";
					$file_name=$_FILES["image"]["name"];
					$extension=explode(".", $file_name);
					$extension=end($extension);
					do{
						$file_name=md5($file_name);
					}while(file_exists($dir.$file_name.".".$extension));
					move_uploaded_file($_FILES["image"]["tmp_name"], $dir.$file_name.".".$extension);
					$edited['Foto']=$file_name.".".$extension;
				}
			}



			$this->modificaAccount($idAcc,$edited);

			$this->getView()->setJsonValue('success', "Account modificato");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	private function modificaAccount($idAcc,$edited){
		if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }
		$db=$this->getDatabase();
		return $db->Update("Account", $edited, Array("idAccount"=>$idAcc));
	}
	private function checkPass($pass,$repass){
		if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,20})/', $pass)) {
			throw new Exception("Password non valida",-2);
		}
		if(strcmp($pass,$repass)!=0){
			throw new Exception("Le password non coincidono",-3);
		}
	}
	// funzione per criptare la password
	private function cryptPassword($pass, $salt){
		return password_hash(STANDARDSALT.$pass.$salt, PASSWORD_BCRYPT, array('cost' => 12));
	}

	public function aggiungiArtistaAjax($data=Array()){ //funzione che risponde alla chiamata AJAX per aggiungere in libreria un artista
		$idAcc=$this->account->getIdAccount();

		if(empty($this->getDatabase())){
            $this->getView()->setJsonValue('error', "No db connection");
			$this->getView()->setJsonValue('code',"-1");
			return;
        }
		$db=$this->getDatabase();
		if(empty($this->getPost("idArtist"))){
			$this->getView()->setJsonValue('error', "Errore nella selezione dell'artista");
			$this->getView()->setJsonValue('code',"-2");
			return;
		}
		try{
			$idArt=$this->getPost("idArtist");
			$ins['RefArtista']=$idArt;
			$ins['RefAccount']=$idAcc;
			if($db->Insert("ArtistiPiaciuti",$ins)!=0){
				throw new Exception("Artista già aggiunto",-3);
				return;
			}
			$this->getView()->setJsonValue('success', "Artista aggiunto");
			$this->getView()->setJsonValue('code', 1);
		}
		catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	public function rimuoviArtistaAjax($data=Array()){ //funzione che risponde alla chiamata AJAX per rimuovere un artista dalla libreria
		$idAcc=$this->account->getIdAccount();
		if(empty($this->getDatabase())){
            $this->getView()->setJsonValue('error', "No db connection");
			$this->getView()->setJsonValue('code',"-1");
			return;
        }
		$db=$this->getDatabase();
		if(empty($this->getPost("idArtist"))){
			$this->getView()->setJsonValue('error', "Errore nella selezione dell'artista");
			$this->getView()->setJsonValue('code',"-2");
			return;
		}
		$idArt=$this->getPost("idArtist");
		try{
			if($db->Delete("ArtistiPiaciuti","RefArtista=".$idArt." AND RefAccount=".$idAcc)!=1){
				throw new Exception("Artista non presente",-3);
				return;
			}

			$this->getView()->setJsonValue('success', "Artista rimosso");
			$this->getView()->setJsonValue('code', 1);
		}
		catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	public function aggiungiBranoAjax($data=Array()){ //funzione che risponde alla chiamata AJAX per aggiungere in libreria un brano
		$idAcc=$this->account->getIdAccount();

		if(empty($this->getDatabase())){
            $this->getView()->setJsonValue('error', "No db connection");
			$this->getView()->setJsonValue('code',"-1");
			return;
        }
		$db=$this->getDatabase();
		if(empty($this->getPost("idBrano"))){
			$this->getView()->setJsonValue('error', "Errore nella selezione del brano");
			$this->getView()->setJsonValue('code',"-2");
			return;
		}
		$idBrano=$this->getPost("idBrano");
		$ins['RefBrano']=$idBrano;
		$ins['RefAccount']=$idAcc;
		try{
			if($db->Insert("BraniAggiunti",$ins)!=0){
				throw new Exception("Brano già aggiunto",-3);
				return;
			}
			$myGeneri=$this->getDatabase()->Select("Genere G,BranoGenere BG, BraniAggiunti BA","G.idGenere=BG.RefGenere AND BG.RefBrano=BA.RefBrano AND BA.RefAccount=".$this->account->getIdAccount(),Array("DISTINCT G.idGenere","G.Testo"));
			$this->getView()->setJsonValue('generi',$myGeneri);
			$this->getView()->setJsonValue('success', "Brano aggiunto");
			$this->getView()->setJsonValue('code', 1);
		}
		catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	public function rimuoviBranoAjax($data=Array()){ //funzione che risponde alla chiamata AJAX per rimuovere un brano dalla libreria
		$idAcc=$this->account->getIdAccount();
		if(empty($this->getDatabase())){
            $this->getView()->setJsonValue('error', "No db connection");
			$this->getView()->setJsonValue('code',"-1");
			return;
        }
		$db=$this->getDatabase();
		if(empty($this->getPost("idBrano"))){
			$this->getView()->setJsonValue('error', "Errore nella selezione del brano");
			$this->getView()->setJsonValue('code',"-2");
			return;
		}
		$idBrano=$this->getPost("idBrano");
		try{
			if($db->Delete("BraniAggiunti","RefBrano=".$idBrano." AND RefAccount=".$idAcc)!=1){
				throw new Exception("Brano non presente",-3);
			}
			$myGeneri=$this->getDatabase()->Select("Genere G,BranoGenere BG, BraniAggiunti BA","G.idGenere=BG.RefGenere AND BG.RefBrano=BA.RefBrano AND BA.RefAccount=".$this->account->getIdAccount(),Array("DISTINCT G.idGenere","G.Testo"));
			$this->getView()->setJsonValue('generi',$myGeneri);
			$this->getView()->setJsonValue('success', "Brano rimosso");
			$this->getView()->setJsonValue('code', 1);
		}
		catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}

	public function createPlaylistAjax($data=Array()){ //funzione che risponde alla chiamata AJAX per rimuovere un brano dalla libreria
		$idAcc=$this->account->getIdAccount();
		if(empty($this->getDatabase())){
            $this->getView()->setJsonValue('error', "No db connection");
			$this->getView()->setJsonValue('code',"-1");
			return;
        }
		$db=$this->getDatabase();
		try{
			$result=$db->Insert("Playlist", Array("Creatore"=>$idAcc, "DataCreazione"=> date("Y-m-d H:i:s"), "Titolo"=>"Playlist"));
			$this->getView()->setJsonValue('message', "Playlist creata");
			$this->getView()->setJsonValue('code', 1);
			$this->getView()->setJsonValue('idPlaylist', $result);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	public function changePlaylistNameAjax($data=Array()){
		$idAcc=$this->account->getIdAccount();
		if(empty($this->getDatabase())){
            $this->getView()->setJsonValue('error', "No db connection");
			$this->getView()->setJsonValue('code',-1);
			return;
        }
		$db=$this->getDatabase();

		if(empty($this->getPost("id"))){
            $this->getView()->setJsonValue('error', "Id non valido");
			$this->getView()->setJsonValue('code',-2);
			return;
        }
		$id=$this->getPost("id");

		if(empty($this->getPost("titolo"))){
            $this->getView()->setJsonValue('error', "titolo non valido");
			$this->getView()->setJsonValue('code',-2);
			return;
        }
		$titolo=$this->getPost("titolo");

		try{
			$result=$db->Update("Playlist", Array("Titolo"=>$db->escapeString($titolo)), Array("idPlaylist"=>$id, "Creatore"=>$idAcc));
			$this->getView()->setJsonValue('message', "Nome playlist cambiata");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}

	}

	public function removePlaylistAjax($data=Array()){
		$idAcc=$this->account->getIdAccount();
		if(empty($this->getDatabase())){
            $this->getView()->setJsonValue('error', "No db connection");
			$this->getView()->setJsonValue('code',-1);
			return;
        }
		$db=$this->getDatabase();

		if(empty($this->getPost("id"))){
            $this->getView()->setJsonValue('error', "Id non valido");
			$this->getView()->setJsonValue('code',-2);
			return;
        }
		$id=$this->getPost("id");

		try{
			$result=$db->Delete("Playlist", Array("idPlaylist"=>$id, "Creatore"=>$idAcc));
			$this->getView()->setJsonValue('message', "Playlist eliminata");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}

	}
	public function retrivePlaylistAjax($data=Array()){
		$idAcc=$this->account->getIdAccount();
		if(empty($this->getDatabase())){
            $this->getView()->setJsonValue('error', "No db connection");
			$this->getView()->setJsonValue('code',-1);
			return;
        }
		$db=$this->getDatabase();

		try{
			$result=$db->Select("Playlist", "Creatore=".$idAcc." ORDER BY DataCreazione DESC", Array("Titolo", "idPlaylist"));
			$this->getView()->setJsonValue('message', "Playlist trovate");
			$this->getView()->setJsonValue('code', 1);
			$this->getView()->setJsonValue('Playlist', $result);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}

	}
	public function addBranoPlaylistAjax($data=Array()){
		$idAcc=$this->account->getIdAccount();
		if(empty($this->getDatabase())){
            $this->getView()->setJsonValue('error', "No db connection");
			$this->getView()->setJsonValue('code',-1);
			return;
        }
		$db=$this->getDatabase();
		if(empty($this->getPost("idPlaylist"))){
            $this->getView()->setJsonValue('error', "Id Playlist non valido");
			$this->getView()->setJsonValue('code',-2);
			return;
        }
		$idPlaylist=$this->getPost("idPlaylist");
		if(empty($this->getPost("idBrano"))){
            $this->getView()->setJsonValue('error', "Id Brano non valido");
			$this->getView()->setJsonValue('code',-3);
			return;
        }

		$idBrano=$this->getPost("idBrano");
		try{
			$result=$db->Select("Playlist", Array("Creatore"=>$idAcc, "idPlaylist"=>$idPlaylist));
			if(count($result)<1){
				throw new Exception("Playlist non tua", -4);
			}

			$result=$db->Insert("BraniPlaylist", Array("RefBrano"=>$idBrano, "RefPlaylist"=>$idPlaylist));
			$this->getView()->setJsonValue('message', "Brano aggiunto a playlist");
			$this->getView()->setJsonValue('code', 1);
			$this->getView()->setJsonValue('Playlist', $result);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}

	}
	public function removeBranoPlaylistAjax($data=Array()){
		$idAcc=$this->account->getIdAccount();
		if(empty($this->getDatabase())){
            $this->getView()->setJsonValue('error', "No db connection");
			$this->getView()->setJsonValue('code',-1);
			return;
        }
		$db=$this->getDatabase();
		if(empty($this->getPost("idPlaylist"))){
            $this->getView()->setJsonValue('error', "Id Playlist non valido");
			$this->getView()->setJsonValue('code',-2);
			return;
        }
		$idPlaylist=$this->getPost("idPlaylist");
		if(empty($this->getPost("idBrano"))){
            $this->getView()->setJsonValue('error', "Id Brano non valido");
			$this->getView()->setJsonValue('code',-3);
			return;
        }

		$idBrano=$this->getPost("idBrano");
		try{
			$result=$db->Select("Playlist", Array("Creatore"=>$idAcc, "idPlaylist"=>$idPlaylist));
			if(count($result)<1){
				throw new Exception("Playlist non tua", -4);
			}

			$result=$db->Delete("BraniPlaylist", Array("RefBrano"=>$idBrano, "RefPlaylist"=>$idPlaylist));
			$this->getView()->setJsonValue('message', "Brano rimosso da playlist");
			$this->getView()->setJsonValue('code', 1);
			$this->getView()->setJsonValue('Playlist', $result);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}

	}

	private function playlist($data=Array()){
		$idPlaylist=$data["addict"];
		$idAcc=$this->account->getIdAccount();
		$db=$this->getDatabase();
		$playlist=$db->raw("SELECT P.Descrizione, P.idPlaylist, P.Creatore, P.DataCreazione, P.Condivisa, P.Titolo, GROUP_CONCAT(DISTINCT A.Image ORDER BY BP.Indice) AS Image, Ac.Nickname FROM Account Ac JOIN Playlist P ON P.Creatore=Ac.IdAccount LEFT JOIN BraniPlaylist BP ON BP.RefPlaylist=P.idPlaylist LEFT JOIN BraniAlbum BA ON BA.RefBrano=BP.RefBrano LEFT JOIN Album A ON A.idAlbum=BA.RefAlbum WHERE P.idPlaylist=".$idPlaylist." GROUP BY P.idPlaylist, P.Titolo")[0];
		$posseduta=($playlist["Creatore"]==$idAcc);
		$braniPlaylist=$db->raw("SELECT AL.Image, AL.Nome, AL.idAlbum, B.Titolo, G.Testo,B.idBrano, B.Durata, G.idGenere, GROUP_CONCAT(DISTINCT A.idArtist ORDER BY AB.Indice) AS ArtistiId, GROUP_CONCAT(DISTINCT A.Name ORDER BY AB.Indice) AS Artisti FROM Brano B, BraniPlaylist BP, ArtistaBrano AB, Artista A, BranoGenere BG, Genere G, BraniAlbum BA, Album AL WHERE B.idBrano=BP.RefBrano AND BP.RefPlaylist=".$idPlaylist." AND B.idBrano=AB.RefBrano AND AB.RefArtista=A.idArtist AND B.idBrano=BG.RefBrano AND B.idBrano=BA.RefBrano AND BA.RefAlbum=AL.idAlbum GROUP BY B.idBrano ORDER BY BP.Indice");


		if(empty($braniPlaylist))
			$braniPlaylist=Array();

		$images=Array();
		$brani=Array();
		foreach($braniPlaylist as $k=>$v){
			$artisti=explode(",", $v['Artisti']);
			$idArtisti=explode(",", $v['ArtistiId']);

			$brani[$v['idBrano']]['idBrano']=$v['idBrano'];
			$brani[$v['idBrano']]['Titolo']=$v['Titolo'];
			$brani[$v['idBrano']]['Durata']=$v['Durata'];
			$brani[$v['idBrano']]['Nome']=$v['Nome'];
			$brani[$v['idBrano']]['Testo']=$v['Testo'];
			$brani[$v['idBrano']]['idGenere']=$v['idGenere'];
			$brani[$v['idBrano']]['idAlbum']=$v['idAlbum'];
			$brani[$v['idBrano']]['Artisti']=Array();

			foreach($artisti as $k1=>$v1){
				$brani[$v['idBrano']]['Artisti'][]=Array("id"=>$idArtisti[$k1], "Name"=>$v1);
			}
			if(count($images)<4){
				if(in_array($v['Image'], $images)){
					continue;
				}else{
					$images[]=$v['Image'];
				}
			}

		}

		$this->SetViewValue("Images",$images);
		$this->SetViewValue("Brani",$brani);
		$this->SetViewValue("Posseduta",$posseduta);
		$this->SetViewValue("Playlist",$playlist);
		$this->renderTemplate("playlist");

	}
	public function togglePlaylistCondivisaAjax($data=Array()){
		$idPlaylist=$this->getPost("idPlaylist");
		$idAcc=$this->account->getIdAccount();
		$db=$this->getDatabase();
		try{
			$entry=$db->Select("Playlist","idPlaylist=".$idPlaylist)[0];
			if(empty($entry)){
				throw new Exception("Playlist inesistente",-3);
				return;
			}
			if($entry["Creatore"]!=$idAcc){
				throw new Exception("Permesso playlist negato",-3);
				return;
			}
			$upd['Condivisa']= ($entry["Condivisa"]=="0")? "1":"0";
			$db->Update("Playlist",$upd,"idPlaylist=".$idPlaylist);
			$this->getView()->setJsonValue('success', "Playlist modificata");
			$this->getView()->setJsonValue('code', 1);
		}
		catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}



	}


	function loadtemplateAjax($data=Array()){
		$function=$data['datalink'];
		$this->$function($data);
	}


	public function friendPlaylistPage($data=Array()){
		$id=$data["addict"];
		$db=$this->getDatabase();
		$account=$db->Select("Account", Array("IdAccount"=>$id));
		if(!$account){
			return;
		}
		
		
		$result=$db->raw("SELECT P.idPlaylist, P.Titolo, GROUP_CONCAT(DISTINCT A.Image ORDER BY BP.Indice ASC) Image FROM Playlist P LEFT JOIN BraniPlaylist BP ON BP.RefPlaylist=P.idPlaylist LEFT JOIN BraniAlbum BA ON BA.RefBrano=BP.RefBrano LEFT JOIN Album A ON A.idAlbum=BA.RefAlbum WHERE P.Creatore=".$id." AND P.Condivisa=1 GROUP BY P.idPlaylist, P.Titolo ORDER BY BP.Indice ASC");
		if(empty($result))
			$result=Array();
		$this->SetViewValue("Playlists",$result);
		$this->SetViewValue("Creatore",$account[0]['Nickname']);
		$this->renderTemplate("friendPlaylistPage");
	}
}
