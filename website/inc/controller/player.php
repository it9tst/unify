<?php

class Player extends Controller {

    function __construct() {
        parent::__construct();
		$this->addModel("account");
		$this->loginFromSession();
		$this->redirectNotLogged("/?action=login");
    }

    function infoAjax($request= Array()){
		$idAcc= $this->account->getIdAccount();
		$db=$this->getDatabase();
		if(empty($request['id'])){
			$this->getView()->setJsonValue('error',"id song required");
			$this->getView()->setJsonValue('code', -1);
			return;
		}
		$id=$request['id'];
		
		if(empty($request['type'])){
			$type="song";
		}else{
			$type=$request['type'];
		}
		
		if($type=="album"){
			$codaRes= $db->raw("SELECT B.Titolo, B.Durata, AL.idAlbum, AL.Image, B.idBrano, GROUP_CONCAT(DISTINCT AR.Name ORDER BY AB.Indice) AS Artisti FROM Brano B, ArtistaBrano AB, Artista AR, BraniAlbum BA, Album AL WHERE B.idBrano AND B.idBrano=AB.RefBrano AND AB.RefArtista=AR.idArtist AND B.idBrano=BA.RefBrano AND BA.RefAlbum=AL.idAlbum AND AL.idAlbum=".$id." GROUP BY B.idBrano ORDER BY BA.Indice");

			
			if(count($codaRes)<=0){
				$this->getView()->setJsonValue('error',"no song with this id");
				$this->getView()->setJsonValue('code', -2);
				return;
			}
			
			$idRes=$codaRes[0]['idBrano'];
			$idAlbumRes=$codaRes[0]['idAlbum'];
			$titoloRes=$codaRes[0]['Titolo'];
			$imageRes=$codaRes[0]['Image'];
			$durataRes=$codaRes[0]['Durata'];
			$artistiRes=explode(",",$codaRes[0]['Artisti']);
			
		}elseif($type=="playlist"){
			
			$codaRes= $db->raw("SELECT B.Titolo, B.Durata, AL.idAlbum, AL.Image, B.idBrano, GROUP_CONCAT(DISTINCT AR.Name ORDER BY AB.Indice) AS Artisti FROM Playlist P, BraniPlaylist BP, Brano B, ArtistaBrano AB, Artista AR, BraniAlbum BA, Album AL WHERE P.idPlaylist=BP.RefPlaylist AND BP.RefBrano=B.idBrano AND B.idBrano=AB.RefBrano AND AB.RefArtista=AR.idArtist AND B.idBrano=BA.RefBrano AND BA.RefAlbum=AL.idAlbum AND P.idPlaylist=".$id." GROUP BY B.idBrano ORDER BY BP.Indice");

			
			if(count($codaRes)<=0){
				$this->getView()->setJsonValue('error',"no song with this id");
				$this->getView()->setJsonValue('code', -2);
				return;
			}
			
			$idRes=$codaRes[0]['idBrano'];
			$idAlbumRes=$codaRes[0]['idAlbum'];
			$titoloRes=$codaRes[0]['Titolo'];
			$imageRes=$codaRes[0]['Image'];
			$durataRes=$codaRes[0]['Durata'];
			$artistiRes=explode(",",$codaRes[0]['Artisti']);
			
			
		}elseif($type=="artist"){
			
			$codaRes= $db->raw("SELECT B.Titolo, B.Durata, AL.idAlbum, AL.Image, B.idBrano, GROUP_CONCAT(DISTINCT AR.Name ORDER BY AB.Indice) AS Artisti FROM Brano B, ArtistaBrano AB, Artista AR, BraniAlbum BA, Album AL WHERE B.idBrano AND B.idBrano=AB.RefBrano AND AB.RefArtista=AR.idArtist AND B.idBrano=BA.RefBrano AND BA.RefAlbum=AL.idAlbum AND AR.idArtist=".$id." GROUP BY B.idBrano ORDER BY B.Anno DESC");

			
			if(count($codaRes)<=0){
				$this->getView()->setJsonValue('error',"no song with this id");
				$this->getView()->setJsonValue('code', -2);
				return;
			}
			
			$idRes=$codaRes[0]['idBrano'];
			$idAlbumRes=$codaRes[0]['idAlbum'];
			$titoloRes=$codaRes[0]['Titolo'];
			$imageRes=$codaRes[0]['Image'];
			$durataRes=$codaRes[0]['Durata'];
			$artistiRes=explode(",",$codaRes[0]['Artisti']);
			
			
			
			
			
			
		}else{
			$result= $db->Select("Brano B, BraniAlbum BA, Album Al, ArtistaBrano AB, Artista Ar",
			"B.idBrano=BA.RefBrano AND BA.RefAlbum=Al.idAlbum AND B.idBrano=AB.RefBrano AND AB.RefArtista=Ar.idArtist AND B.idBrano=".$id,
			Array("B.idBrano as id, B.Titolo AS Titolo", "Al.Image AS Image", "Ar.Name AS Artista", "B.Durata AS Durata", "Al.idAlbum AS idAlbum"));
		
			if(count($result)<=0){
				$this->getView()->setJsonValue('error',"no song with this id");
				$this->getView()->setJsonValue('code', -2);
				return;
			}
			
			
			
			$idAlbum=$result[0]['idAlbum'];
			$codaRes= $db->raw("SELECT B.Titolo, AL.idAlbum, AL.Image, B.idBrano, B.Durata, GROUP_CONCAT(DISTINCT AR.Name ORDER BY AB.Indice) AS Artisti FROM Album AL, BraniAlbum BA, Brano B, ArtistaBrano AB, Artista AR WHERE AL.idAlbum=BA.RefAlbum AND BA.RefBrano=B.idBrano AND B.idBrano=AB.RefBrano AND AB.RefArtista=AR.idArtist AND AL.idAlbum=".$idAlbum." GROUP BY AL.idAlbum, AL.Image, BA.RefBrano ORDER BY BA.Indice");
					
			
			$idRes=$result[0]['id'];
			$idAlbumRes=$result[0]['idAlbum'];
			$titoloRes=$result[0]['Titolo'];
			$imageRes=$result[0]['Image'];
			$durataRes=$result[0]['Durata'];
			$artistiRes= array();
			foreach($result as $v){
				$artistiRes[]=$v['Artista'];
			}
			
		}
		
		
		
		/*$db->raw("INSERT INTO Ascoltati (RefAccount, RefBrano) VALUES(".$idAcc.", ".$idBrano.") ON DUPLICATE KEY UPDATE NumeroVolte=NumeroVolte+1");
		
		switch($type){
			case "playlist":
				
			break;
			case "recenti":
			
			break;
			default:
				$idAlbum=$result[0]['idAlbum'];
				$coda= $db->raw("SELECT B.Titolo, AL.idAlbum, AL.Image, B.idBrano, GROUP_CONCAT(DISTINCT AR.Name ORDER BY AB.Indice) AS Artisti FROM Album AL, BraniAlbum BA, Brano B, ArtistaBrano AB, Artista AR WHERE AL.idAlbum=BA.RefAlbum AND BA.RefBrano=B.idBrano AND B.idBrano=AB.RefBrano AND AB.RefArtista=AR.idArtist AND AL.idAlbum=".$idAlbum." GROUP BY AL.idAlbum, AL.Image, BA.RefBrano ORDER BY BA.Indice");
				$this->getView()->setJsonValue('Coda',$coda);
			break;
		}
		*/
		
		
		
		

		
		
		
		$this->getView()->setJsonValue('Id',$idRes);
		$this->getView()->setJsonValue('IdAlbum',$idAlbumRes);
		$this->getView()->setJsonValue('Titolo',$titoloRes);
		$this->getView()->setJsonValue('Image',$imageRes);
		$this->getView()->setJsonValue('Durata',$durataRes);
		$this->getView()->setJsonValue('Coda',$codaRes);
		$this->getView()->setJsonValue('Artisti',$artistiRes);
		
	}
	function ajax($request= Array()){
		$idAcc= $this->account->getIdAccount();
		$db=$this->getDatabase();
		if(empty($request['idSong'])){
			$this->getView()->setJsonValue('error',"id song required");
			$this->getView()->setJsonValue('code', -1);
			return;
		}
		$result = $db->Select("Brano",
			Array("idBrano"=>$request['idSong']),
			Array("Path"));
		
		if(count($result)<=0){
			$this->getView()->setJsonValue('error',"no song with this id");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		
		$db->raw("INSERT INTO Ascoltati (RefAccount, RefBrano) VALUES(".$idAcc.", ".$request['idSong'].") ON DUPLICATE KEY UPDATE NumeroVolte=NumeroVolte+1");
		
		
		$file = "/home2/unifyuls/public_html/music/".$result[0]["Path"];
		$fp = @fopen($file, 'rb');
		$size   = filesize($file); // File size
		$length = $size;           // Content length
		$start  = 0;               // Start byte
		$end    = $size - 1;       // End byte
		header('Content-type: audio/mpeg');
		header("Accept-Ranges: 0-$length");
		if (isset($_SERVER['HTTP_RANGE'])){
			$c_start = $start;
			$c_end   = $end;
			list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
			if (strpos($range, ',') !== false) {
				header('HTTP/1.1 416 Requested Range Not Satisfiable');
				header("Content-Range: bytes $start-$end/$size");
				exit;
			}
			if ($range == '-') {
				$c_start = $size - substr($range, 1);
			}else{
				$range  = explode('-', $range);
				$c_start = $range[0];
				$c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
			}
			$c_end = ($c_end > $end) ? $end : $c_end;
			if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
				header('HTTP/1.1 416 Requested Range Not Satisfiable');
				header("Content-Range: bytes $start-$end/$size");
				exit;
			}
			$start  = $c_start;
			$end    = $c_end;
			$length = $end - $start + 1;
			fseek($fp, $start);
			header('HTTP/1.1 206 Partial Content');
		}
		header("Content-Range: bytes $start-$end/$size");
		header("Cache-Control: no-cache, no-transform");
		header("Content-Length: ".$length);
		$buffer = 1024 * 8;
		$maxChunk=$buffer*120;
		while(!feof($fp) && ($p = ftell($fp)) <= $end) {
			if ($p + $buffer > $end) {
				$buffer = $end - $p + 1;
			}
			set_time_limit(1);
			echo fread($fp, $buffer);
			flush();
		}
		fclose($fp);
		exit();
	}
}
