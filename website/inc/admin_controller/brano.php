<?php
require_once LIBS."mp3/mp3.php";
class Brano extends Controller {
	private $generi=[];
    function __construct() {
        parent::__construct();
    }

    function index(){
		$this->setViewValue("Brani",Array());
		$this->render("brano/index");
    }
	public function delete($data= Array()){
		if(empty($data[0])){
			$this->getView()->setJsonValue('error', "Inserisci un id corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$id=$data[0];
		try{
			$this->eliminaBrano($id);
			$this->getView()->setJsonValue('success', "Brano eliminato");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	
	public function edit($data= Array()){
		if(empty($data[0])){
			$this->getView()->setJsonValue('error', "Inserisci un id corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$id=$data[0];
		$db = $this->getDatabase();
		$result= $db->Select("Genere", "1");
		$generi= Array();
		foreach($result as $k=>$v){
			$generi[$v['idGenere']]=$v['Testo'];
		}
		$this->setViewValue("Generi",$generi);
		
		
		$result= $db->Select("Artista", "1 ORDER BY Name ASC");
		$artista= Array();
		foreach($result as $k=>$v){
			$artista[$v['idArtist']]=$v['Name'];
		}
		$this->setViewValue("Artista",$artista);
		
		if(empty($this->getPost("submit"))){
			$brano= $db->Select("Brano", Array("idBrano"=>$id))[0];
			$generiBrano=$db->Select("BranoGenere BG, Genere G", "G.idGenere=BG.RefGenere AND BG.RefBrano=".$id, Array("G.Testo", "G.idGenere"));
			$artistiBrano=$db->Select("ArtistaBrano AR, Artista A", "A.idArtist=AR.RefArtista AND AR.RefBrano=".$id." ORDER BY Indice", Array("A.idArtist", "A.Name"));
			if(empty($brano)){
				$brano=Array();
			}
			if(empty($generiBrano)){
				$generiBrano=Array();
			}
			if(empty($artistiBrano)){
				$artistiBrano=Array();
			}
			$this->setViewValue("Brano",$brano);
			$this->setViewValue("GeneriBrano",$generiBrano);
			$this->setViewValue("ArtistiBrano",$artistiBrano);
			$this->render('brano/brano');
			return;
		}
		$edited=Array();
		if(!empty($this->getPost("Titolo"))){
			$edited['Titolo']=$this->getPost("Titolo");
		}
		if(!empty($this->getPost("Anno"))){
			$edited['Anno']=$this->getPost("Anno");
		}
		if(!empty($this->getPost("Artisti")) && count($this->getPost("Artisti"))>0){
			$edited['Artisti']=$this->getPost("Artisti");
		}
		if(!empty($this->getPost("Generi")) && count($this->getPost("Generi"))>0){
			$edited['Generi']=$this->getPost("Generi");
		}
		
		if(!empty($_FILES["Music"])){
			if(!(trim($_FILES["Music"]["name"])=="" || !in_array($_FILES["Music"]["type"], Array("audio/mpeg", "audio/mp3")) || !is_uploaded_file($_FILES["Music"]["tmp_name"]) || $_FILES["Music"]["error"]>0)){
				$dir="/home2/unifyuls/public_html/music/";
				$file_name=$_FILES["Music"]["name"];
				$extension=explode(".", $file_name);
				$extension=end($extension);
				do{
					$file_name=md5($file_name);
				}while(file_exists($dir.$file_name.".".$extension));
				move_uploaded_file($_FILES["Music"]["tmp_name"], $dir.$file_name.".".$extension);
				$edited['Path']=$file_name.".".$extension;
				$audio = new wapmorgan\Mp3Info\Mp3Info($dir.$file_name.".".$extension, true);
				$edited['Duration']=floor($audio->duration);
			}
		}

		try{
			$this->modificaBrano($id,$edited);
			$this->getView()->setJsonValue('success', "Brano modificato");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	
	
	public function add($data= Array()){
		$db = $this->getDatabase();
		$result= $db->Select("Genere", "1");
		$generi= Array();
		foreach($result as $k=>$v){
			$generi[$v['idGenere']]=$v['Testo'];
		}
		$this->setViewValue("Generi",$generi);
		
		
		$result= $db->Select("Artista", "1 ORDER BY Name ASC");
		$artista= Array();
		foreach($result as $k=>$v){
			$artista[$v['idArtist']]=$v['Name'];
		}
		$this->setViewValue("Artista",$artista);
		
		if(empty($this->getPost("submit"))){
			$this->setViewValue("Brano",Array());
			$this->render('brano/brano');
			return;
		}
		
		if(empty($this->getPost("Titolo"))){
			$this->getView()->setJsonValue('error', "Inserisci un titolo corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$titolo=$this->getPost("Titolo");

		if(empty($this->getPost("Anno"))){
			$this->getView()->setJsonValue('error', "Inserisci una data corretta");
			$this->getView()->setJsonValue('code', -4);
			return;
		}
		$anno=$this->getPost("Anno");
		
		if(empty($this->getPost("Artisti")) || count($this->getPost("Artisti"))==0){
			$this->getView()->setJsonValue('error', "Inserisci almeno un artista");
			$this->getView()->setJsonValue('code', -5);
			return;
		}
		$artisti=$this->getPost("Artisti");
		if(empty($this->getPost("Generi")) || count($this->getPost("Generi"))==0){
			$this->getView()->setJsonValue('error', "Inserisci almeno un genere");
			$this->getView()->setJsonValue('code', -6);
			return;
		}
		$generi=$this->getPost("Generi");
		
		if(empty($_FILES["Music"])){
			$this->getView()->setJsonValue('error', "Inserisci una canzone corretta");
			$this->getView()->setJsonValue('code', -7);
			return;
		}
		if(empty($_FILES["Music"]) || trim($_FILES["Music"]["name"])=="" || !in_array($_FILES["Music"]["type"], Array("audio/mpeg", "audio/mp3")) || !is_uploaded_file($_FILES["Music"]["tmp_name"]) || $_FILES["Music"]["error"]>0){
			$this->getView()->setJsonValue('error', "Inserisci una canzone corretta");
			$this->getView()->setJsonValue('code', -7);
			return;
		}
		$dir="/home2/unifyuls/public_html/music/";
		$file_name=$_FILES["Music"]["name"];
		$extension=explode(".", $file_name);
		$extension=end($extension);
		do{
			$file_name=md5($file_name);
		}while(file_exists($dir.$file_name.".".$extension));
		move_uploaded_file($_FILES["Music"]["tmp_name"], $dir.$file_name.".".$extension);
		
		
		$audio = new wapmorgan\Mp3Info\Mp3Info($dir.$file_name.".".$extension, true);
		
		try{
			$this->insertBrano($titolo, $anno, $file_name.".".$extension, floor($audio->duration), $artisti, $generi);
			$this->getView()->setJsonValue('success', "Brano inserito");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	public function search(){
		try{
			$nome=$this->getPost("nome");
			$result=$this->searchBrani($nome);
			$this->getView()->setJsonValue('success', "Brani trovati");
			$this->getView()->setJsonValue('code', 1);

			$this->getView()->setJsonValue('Brani', $result);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	
	
	
	private function searchBrani($nome){
		if(empty($nome)){
            throw new Exception("Inserisci un nome corretto", -2);
        }
		if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }

		$db = $this->getDatabase();
		$result= $db->select("Brano B LEFT JOIN BraniAlbum BA ON B.idBrano=BA.RefBrano LEFT JOIN Album A ON A.idAlbum=BA.RefAlbum", "B.Titolo LIKE '%".$nome."%' ORDER BY B.Titolo DESC" , Array("B.Titolo", "B.Anno", "B.idBrano", "A.Nome AS NomeAlbum"));

		return $result;
	}
	
	private function insertBrano($titolo, $data, $path, $durata=120, $artisti, $generi){
		if(empty($titolo)){
            throw new Exception("Inserisci un titolo corretto", -2);
        }
		if(empty($data) || !$this->validateDate($data)){
            throw new Exception("Inserisci una data corretta", -3);
        }
		if(empty($path)){
            throw new Exception("Path errata", -4);
        }
		if(empty($durata) || !is_numeric($durata) || intval($durata)<=0){
            throw new Exception("Path errata", -4);
        }
		if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }

		$db = $this->getDatabase();

		$db->startTransactions();
		try{
			$idBrano=$db->Insert("Brano", Array("Titolo"=>ucfirst($titolo),
					"Anno"=>$data,
					"Path"=>$path,
					"Durata"=>$durata));

			foreach($generi as $v){
				$db->Insert("BranoGenere", Array("RefBrano"=>$idBrano, "RefGenere"=>$v));
			}
			foreach($artisti as $v){
				$db->Insert("ArtistaBrano", Array("RefBrano"=>$idBrano, "RefArtista"=>$v));
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			throw new Exception($e->getMessage(), -1);
		}
		$db->endTransactions();
	}

	// da sistemare modifica
	private function modificaBrano($id, $newData){
		$update=Array();
		if(!empty($newData['Titolo'])){
            $update['Titolo']=$newData['Titolo'];
        }
		if(!empty($newData['Anno']) && $this->validateDate($newData['Anno'])){
            $update['Anno']=$newData['Anno'];
        }
		if(!empty($newData['Path'])){
            $update['Path']=$newData['Path'];
			$update['Duration']=$newData['Duration'];
        }
		if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }
		if(empty($update)){
            throw new Exception("Inserisci informazioni corrette", -2);
        }
		$db = $this->getDatabase();
		$db->startTransactions();
		try{
			if(!empty($update)){
				$db->Update("Brano", $update, Array("idBrano"=>$id));
			}
			if(!empty($newData['Artisti'])){
				$db->Delete("ArtistaBrano", Array("RefBrano"=>$id));
				foreach($newData['Artisti'] as $v){
					$db->Insert("ArtistaBrano", Array("RefBrano"=>$id, "RefArtista"=>$v));
				}
			}
			if(!empty($newData['Generi'])){
				$db->Delete("BranoGenere", Array("RefBrano"=>$id));
				foreach($newData['Generi'] as $v){
					$db->Insert("AlbumGenere", Array("RefBrano"=>$id, "RefGenere"=>$v));
				}
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			throw new Exception($e->getMessage(), -1);
		}finally{
			$db->endTransactions();
		}
	}

	private function eliminaBrano($idBrano){
		if(empty($this->getDatabase())){
			throw new Exception("No db connection", -1);
        }
		$db = $this->getDatabase();
		return $db->Delete("Brano", Array("idBrano"=>$idBrano));
	}


	private function validateDate($date, $format = 'Y-m-d'){
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}

}
