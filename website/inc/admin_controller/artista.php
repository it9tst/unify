<?php

class Artista extends Controller {
	private $generi=[];
    function __construct() {
        parent::__construct();
    }

    public function index() {
		$this->setViewValue("Artisti",Array());
		$this->render('artista/index');


    }
	
	public function edit($data=Array()){
		if(empty($data[0])){
			$this->getView()->setJsonValue('error', "Inserisci un id corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$id=$data[0];
		if(empty($this->getPost("submit"))){
			$db = $this->getDatabase();
			$artista=$db->Select("Artista", Array("idArtist"=>$id))[0];
			if(empty($artista)){
				$artista=Array();
			}
			$this->setViewValue("Artista",$artista);
			$this->render('artista/artista');
			return;
		}
		
		$edited=Array();
		if(!empty($this->getPost("Name"))){
			$edited['Name']=$this->getPost("Name");
		}
		if(!empty($this->getPost("Informazioni"))){
			$edited['Informazioni']=$this->getPost("Informazioni");
		}
		if(!empty($this->getPost("DataNascita")) && $this->validateDate($this->getPost("DataNascita"))){
			$edited['DataNascita']=$this->getPost("DataNascita");
		}
		
		if(!empty($_FILES["Image"])){
			if(!(trim($_FILES["Image"]["name"])=="" || !in_array($_FILES["Image"]["type"], Array("image/jpeg", "image/jpg", "image/png")) || !is_uploaded_file($_FILES["Image"]["tmp_name"]) || $_FILES["Image"]["error"]>0)){
				$dir="/home2/unifyuls/public_html/dashboard/images/artists/";
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
			$this->modificaArtista($id,$edited);
			$this->getView()->setJsonValue('success', "Artista modificato");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
		
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
			$result=$this->searchArtisti($nome, $offset);
			$this->getView()->setJsonValue('success', "Artisti trovati");
			$this->getView()->setJsonValue('code', 1);

			$this->getView()->setJsonValue('Artisti', $result);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	public function add($data= Array()){
		if(empty($this->getPost("submit"))){
			$this->setViewValue("Artista",Array());
			$this->render('artista/artista');
			return;
		}
		
		
		if(empty($this->getPost("Nome"))){
			$this->getView()->setJsonValue('error', "Inserisci un nome corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$nome=$this->getPost("Nome");
		if(empty($this->getPost("Informazioni"))){
			$this->getView()->setJsonValue('error', "Inserisci informazioni corrette");
			$this->getView()->setJsonValue('code', -3);
			return;
		}
		$informazioni=$this->getPost("Informazioni");
		if(empty($this->getPost("DataNascita"))){
			$this->getView()->setJsonValue('error', "Inserisci una data corretta");
			$this->getView()->setJsonValue('code', -4);
			return;
		}
		$dataNascita=$this->getPost("DataNascita");
		if(!$this->validateDate($dataNascita)){
			$this->getView()->setJsonValue('error', "Inserisci una data corretta");
			$this->getView()->setJsonValue('code', -4);
			return;
		}
		if(empty($_FILES["image"])){
			$this->getView()->setJsonValue('error', "Inserisci una foto corretta");
			$this->getView()->setJsonValue('code', -5);
			return;
		}
		if(empty($_FILES["image"]) || trim($_FILES["image"]["name"])=="" || !in_array($_FILES["image"]["type"], Array("image/jpeg", "image/jpg", "image/png")) || !is_uploaded_file($_FILES["image"]["tmp_name"]) || $_FILES["image"]["error"]>0){
			$this->getView()->setJsonValue('error', "Inserisci una foto corretta");
			$this->getView()->setJsonValue('code', -5);
			return;
		}
		$dir="/home2/unifyuls/public_html/dashboard/images/artists/";
		$file_name=$_FILES["image"]["name"];
		$extension=explode(".", $file_name);
		$extension=end($extension);
		do{
			$file_name=md5($file_name);
		}while(file_exists($dir.$file_name.".".$extension));
		move_uploaded_file($_FILES["image"]["tmp_name"], $dir.$file_name.".".$extension);


		try{
			$this->insertArtisti($nome, $dataNascita, $informazioni, $file_name.".".$extension);
			$this->getView()->setJsonValue('success', "Artista inserito");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}

	private function getAllArtisti($offset=0){
		if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }
		if(!is_numeric($offset) || intval($offset)<0){
            $offset=0;
        }

		$db = $this->getDatabase();
		$result= $db->Select("Artista", "1", Array(), 30, $offset*30, "ORDER BY Name ASC");

		print_r($result);
	}


	private function searchArtisti($nome, $offset=0){
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
		return $db->Select("Artista", "Name LIKE '%".$nome."%'", Array(), 30, $offset*30, "ORDER BY Name ASC");

	}
	private function insertArtisti($nome, $data, $informazioni, $image){
		if(empty($nome)){
            throw new Exception("Inserisci un nome corretto", -2);
        }
		if(empty($data) || !$this->validateDate($data)){
            throw new Exception("Inserisci una data corretta", -3);
        }
		if(empty($informazioni)){
            throw new Exception("Inserisci le informazioni corrette", -4);
        }
		if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }

		$db = $this->getDatabase();
		return $db->Insert("Artista", Array("Name"=>ucfirst($nome),
				"DataNascita"=>$data,
				"Informazioni"=>$informazioni,
				"Image"=>$image));
	}


	private function modificaArtista($id, $newData){
		$update=Array();
		if(!empty($newData['Name'])){
            $update['Name']=$newData['Name'];
        }
		if(!empty($newData['DataNascita']) && $this->validateDate($newData['DataNascita'])){
            $update['DataNascita']=$newData['DataNascita'];
        }
		if(!empty($newData['Informazioni'])){
            $update['Informazioni']=$newData['Informazioni'];
        }
		if(!empty($newData['Image'])){
            $update['Image']=$newData['Image'];
        }
		if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }
		if(empty($update)){
            throw new Exception("Inserisci informazioni corrette", -2);
        }


		$db = $this->getDatabase();
		return $db->Update("Artista", $update, Array("idArtist"=>$id));
	}

	private function eliminaArtista($idArtista){
		if(empty($this->getDatabase())){
			throw new Exception("No db connection", -1);
        }
		$db = $this->getDatabase();
		return $db->Delete("Artista", Array("idArtist"=>$idArtista));
	}


	private function validateDate($date, $format = 'Y-m-d'){
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}


}
