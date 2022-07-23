<?php

class Album extends Controller {
	private $generi=[];
    function __construct() {
        parent::__construct();
    }

    function index(){
		$this->setViewValue("Album",Array());
		$this->render('album/index');
		
    }
	public function delete($data= Array()){
		if(empty($data[0])){
			$this->getView()->setJsonValue('error', "Inserisci un id corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$id=$data[0];
		try{
			$this->eliminaAlbum($id);
			$this->getView()->setJsonValue('success', "Album eliminato");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	
	private function loadBraniArtisti(){
		$db = $this->getDatabase();
		$result= $db->Select("Artista", "1 ORDER BY Name ASC");
		$artista= Array();
		if(!empty($result)){
			foreach($result as $k=>$v){
				$artista[$v['idArtist']]=$v['Name'];
			}
		}
		$this->setViewValue("Artisti",$artista);
		
		$result= $db->Select("Brano B LEFT JOIN ArtistaBrano AB ON AB.RefBrano= B.idBrano LEFT JOIN Artista A ON A.idArtist=AB.RefArtista", "1 GROUP BY B.idBrano, B.Titolo ORDER BY B.Titolo ASC", Array("B.idBrano", "B.Titolo", "GROUP_CONCAT(DISTINCT A.Name) AS Artisti"));
		$brani= Array();
		if(!empty($result)){
			foreach($result as $k=>$v){
				$brani[$v['idBrano']]=$v['Titolo']." - ".$v['Artisti'];
			}
		}
		$this->setViewValue("Brani",$brani);
		
		$result= $db->Select("Genere", "1");
		$generi= Array();
		foreach($result as $k=>$v){
			$generi[$v['idGenere']]=$v['Testo'];
		}
		$this->setViewValue("Generi",$generi);
		
	}
	
	
	
	public function search($data= Array()){
		if(empty($this->getPost("nome"))){
			$this->getView()->setJsonValue('error', "Inserisci un nome corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$nome=$this->getPost("nome");
		if(empty($this->getPost("offset")) || !is_numeric($this->getPost("offset"))){
			$offset=0;
		}else{
			$offset=$this->getPost("offset");
		}
		try{
			$result=$this->searchAlbum($nome, $offset);
			$this->getView()->setJsonValue('success', "Album trovati");
			$this->getView()->setJsonValue('code', 1);

			$this->getView()->setJsonValue('Album', $result);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	public function add($data= Array()){
		$this->loadBraniArtisti();
		
		if(empty($this->getPost("submit"))){
			$this->setViewValue("Album",Array());
			$this->render('album/album');
			return;
		}
		
		$nome=$this->getPost("Nome");
		$etichetta=$this->getPost("Etichetta");
		$artisti=$this->getPost("Artisti");
		$brani=$this->getPost("Brani");
		$generi=$this->getPost("Generi");
		$anno=$this->getPost("Anno");
		
		if(empty($_FILES["Image"])){
			$this->getView()->setJsonValue('error', "Inserisci una foto corretta");
			$this->getView()->setJsonValue('code', -5);
			return;
		}
		if(empty($_FILES["Image"]) || trim($_FILES["Image"]["name"])=="" || !in_array($_FILES["Image"]["type"], Array("image/jpeg", "image/jpg", "image/png")) || !is_uploaded_file($_FILES["Image"]["tmp_name"]) || $_FILES["Image"]["error"]>0){
			$this->getView()->setJsonValue('error', "Inserisci una foto corretta");
			$this->getView()->setJsonValue('code', -5);
			return;
		}
		$dir="/home2/unifyuls/public_html/dashboard/images/albums/";
		$file_name=$_FILES["Image"]["name"];
		$extension=explode(".", $file_name);
		$extension=end($extension);
		do{
			$file_name=md5($file_name);
		}while(file_exists($dir.$file_name.".".$extension));
		move_uploaded_file($_FILES["Image"]["tmp_name"], $dir.$file_name.".".$extension);


		try{
			$this->insertAlbum($nome, $anno, $etichetta, $file_name.".".$extension, $artisti, $brani, $generi);
			$this->getView()->setJsonValue('success', "Album inserito");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	
	public function edit($data=Array()){
		if(empty($data[0])){
			$this->getView()->setJsonValue('error', "Inserisci un id corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		
		
		$this->loadBraniArtisti();

		$id=$data[0];
		
		if(empty($this->getPost("submit"))){
			$db = $this->getDatabase();
			$album=$db->Select("Album", Array("idAlbum"=>$id))[0];
			
			$braniAlbum=$db->Select("BraniAlbum BA, Brano B", "B.idBrano=BA.RefBrano AND BA.RefAlbum=".$id." ORDER BY Indice", Array("B.Titolo", "B.idBrano"));
			$generiAlbum=$db->Select("AlbumGenere AG, Genere G", "G.idGenere=AG.RefGenere AND AG.RefAlbum=".$id, Array("G.Testo", "G.idGenere"));
			$artistiAlbum=$db->Select("ArtistiAlbum AA, Artista A", "A.idArtist=AA.RefArtista AND AA.RefAlbum=".$id." ORDER BY Indice", Array("A.idArtist", "A.Name"));
			
			if(empty($brano)){
				$brano=Array();
			}
			if(empty($braniAlbum)){
				$braniAlbum=Array();
			}
			if(empty($artistiAlbum)){
				$artistiAlbum=Array();
			}
			if(empty($generiAlbum)){
				$generiAlbum=Array();
			}
			if(empty($album)){
				$album=Array();
			}
			$this->setViewValue("Album",$album);
			$this->setViewValue("BraniAlbum",$braniAlbum);
			$this->setViewValue("ArtistiAlbum",$artistiAlbum);
			$this->setViewValue("GeneriAlbum",$generiAlbum);
			$this->render('album/album');
			return;
		}
		
		$edited=Array();
		if(!empty($this->getPost("Nome"))){
			$edited['Nome']=$this->getPost("Nome");
		}
		if(!empty($this->getPost("Etichetta"))){
			$edited['Etichetta']=$this->getPost("Etichetta");
		}
		if(!empty($this->getPost("Anno")) && $this->validateDate($this->getPost("Anno"))){
			$edited['Anno']=$this->getPost("Anno");
		}
		if(!empty($this->getPost("Artisti"))){
			$edited['Artisti']=$this->getPost("Artisti");
		}
		if(!empty($this->getPost("Brani"))){
			$edited['Brani']=$this->getPost("Brani");
		}
		if(!empty($this->getPost("Generi")) && count($this->getPost("Generi"))>0){
			$edited['Generi']=$this->getPost("Generi");
		}
		if(!empty($_FILES["Image"])){
			if(!(trim($_FILES["Image"]["name"])=="" || !in_array($_FILES["Image"]["type"], Array("image/jpeg", "image/jpg", "image/png")) || !is_uploaded_file($_FILES["Image"]["tmp_name"]) || $_FILES["Image"]["error"]>0)){
				$dir="/home2/unifyuls/public_html/dashboard/images/albums/";
				$file_name=$_FILES["Image"]["name"];
				$extension=explode(".", $file_name);
				$extension=end($extension);
				do{
					$file_name=md5($file_name);
				}while(file_exists($dir.$file_name.".".$extension));
				move_uploaded_file($_FILES["Image"]["tmp_name"], $dir.$file_name.".".$extension);
				$edited['Image']=$file_name.".".$extension;
			}
		}

		try{
			$this->modificaAlbum($id,$edited);
			$this->getView()->setJsonValue('success', "Album modificato");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
		
	}
	private function getAllAlbum($offset=0){ // probabilmente la cancello
		if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }
		if(!is_numeric($offset) || intval($offset)<0){
            $offset=0;
        }
		
		$db = $this->getDatabase();
		$result= $db->Select("Album", "1", Array(), 30, $offset*30, "ORDER BY Name ASC");
		print_r($result);
	}
	
	

	
	
	private function searchAlbum($nome, $offset=0){
		if(empty($nome)){
            throw new Exception("Inserisci un nome corretto", -2);
        }
		if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }
		if(!is_numeric($offset) || intval($offset)<0){
            $offset=0;
        }
		
		$db = $this->getDatabase();
		$result= $db->Select("Album", "Nome LIKE '%".$nome."%'", Array(), 30, $offset*30, "ORDER BY Nome ASC");
		
		return $result;
	}
	private function insertAlbum($nome, $data, $etichetta, $image, $artisti, $brani, $generi){
		if(empty($nome)){
            throw new Exception("Inserisci un nome corretto", -2);
        }
		if(empty($data) || !$this->validateDate($data)){
            throw new Exception("Inserisci una data corretta", -3);
        }

		if(empty($image)){
            throw new Exception("Inserisci un immagine corrette", -4);
        }
		if(empty($etichetta)){
            throw new Exception("Inserisci le informazioni corrette", -4);
        }
		if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }
		if(gettype($artisti)!="array" || empty($artisti)){
            throw new Exception("Inserisci almeno un artista", -4);
        }
		if(gettype($brani)!="array" || empty($brani)){
            throw new Exception("Inserisci almeno un brano", -4);
        }
		if(gettype($generi)!="array" || empty($generi)){
            throw new Exception("Inserisci almeno un genere", -4);
        }
		
		
		
		$db = $this->getDatabase();
		$db->startTransactions();
		try{
			$idAlbum=$db->Insert("Album", Array("Nome"=>ucfirst($nome), 
					"Anno"=>$data,
					"Image"=>$image,
					"Etichetta" => $etichetta,
					"NumeroBrani"=> count($brani)));
			
			foreach($artisti as $v){
				$db->Insert("ArtistiAlbum", Array("RefAlbum"=>$idAlbum, "RefArtista"=>$v));
			}
			foreach($brani as $v){
				$db->Insert("BraniAlbum", Array("RefAlbum"=>$idAlbum, "RefBrano"=>$v));
			}
			foreach($generi as $v){
				$db->Insert("AlbumGenere", Array("RefAlbum"=>$idAlbum, "RefGenere"=>$v));
			}
			
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			throw new Exception($e->getMessage(), -1);
		}
		$db->endTransactions();
	}
	
	
	private function modificaAlbum($id, $newData){
		$update=Array();
		if(!empty($newData['Nome'])){
            $update['Nome']=$newData['Nome'];
        }
		if(!empty($newData['Anno']) && $this->validateDate($newData['Anno'])){
            $update['Anno']=$newData['Anno'];
        }
		if(!empty($newData['Image'])){
            $update['Image']=$newData['Image'];
        }
		if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }
		if(empty($update) && empty($newData['Brani']) && empty($newData['Artisti']) && empty($newData['Generi'])){
            throw new Exception("Inserisci informazioni corrette", -2);
        }
		
		$db = $this->getDatabase();
		$db->startTransactions();
		try{
			if(!empty($update)){
				$db->Update("Album", $update, Array("idAlbum"=>$id));
			}
			if(!empty($newData['Artisti'])){
				$db->Delete("ArtistiAlbum", Array("RefAlbum"=>$id));
				foreach($newData['Artisti'] as $v){
					$db->Insert("ArtistiAlbum", Array("RefAlbum"=>$id, "RefArtista"=>$v));
				}
			}
			if(!empty($newData['Brani'])){
				$db->Delete("BraniAlbum", Array("RefAlbum"=>$id));
				foreach($newData['Brani'] as $v){
					$db->Insert("BraniAlbum", Array("RefAlbum"=>$id, "RefBrano"=>$v));
				}
				$db->Update("Album", Array("NumeroBrani"=>count($newData['Brani'])), Array("idAlbum"=>$id));
			}
			if(!empty($newData['Generi'])){
				$db->Delete("AlbumGenere", Array("RefAlbum"=>$id));
				foreach($newData['Generi'] as $v){
					$db->Insert("AlbumGenere", Array("RefAlbum"=>$id, "RefGenere"=>$v));
				}
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			throw new Exception($e->getMessage(), -1);
		}
		$db->endTransactions();
	}
	
	private function eliminaAlbum($idAlbum){
		if(empty($this->getDatabase())){
			throw new Exception("No db connection", -1);
        }
		$db = $this->getDatabase();
		return $db->Delete("Album", Array("idAlbum"=>$idAlbum));
	}
	
	
	private function validateDate($date, $format = 'Y-m-d'){
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}
	
}
