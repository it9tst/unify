<?php

class Genere extends Controller {
	private $generi=[];
    function __construct() {
        parent::__construct();
		$this->getAllGeneri();
    }

    function index() {
		$this->setViewValue("Generi",$this->generi);
		
		
		$this->setViewValue("title", "Admin - Unify");
		$this->getView()->loadBootstrap();

		$this->renderTemplate('head');
		$this->render('genere/index');

    }
	
	public function add($data=Array()){
		if(empty($this->getPost("testo"))){
			$this->getView()->setJsonValue('error', "Inserisci un nome corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$testo=$this->getPost("testo");
		try{
			$id=$this->inserisciGenere($testo);
			$this->getView()->setJsonValue('success', "Genere creato con successo");
			$this->getView()->setJsonValue('code', 1);
			$this->getView()->setJsonValue('Text', $testo);
			$this->getView()->setJsonValue('Id', $id);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
		
	}
	public function remove($data=Array()){
		if(empty($this->getPost("id"))){
			$this->getView()->setJsonValue('error', "Specifica un id");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$id=$this->getPost("id");
		try{
			$this->eliminaGenere($id);
			$this->getView()->setJsonValue('success', "Genere eliminato con successo");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
		
	}
	public function edit($data=Array()){
		if(!isset($data[0])){
			$this->getView()->setJsonValue('error', "Specifica un id");
			$this->getView()->setJsonValue('code', -4);
			return;
		}
		$id=$data[0];
		if(!isset($data[1])){
			$this->getView()->setJsonValue('error', "Inserisci un nome corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$testo=$data[1];
		try{
			$this->modificaGenere($id, $testo);
			$this->getView()->setJsonValue('success', "Genere modificato con successo");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	
	private function getAllGeneri(){
		if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }

		$db = $this->getDatabase();
		$result= $db->Select("Genere", "1");
		foreach($result as $k=>$v){
			$this->generi[$v['idGenere']]=$v['Testo'];
		}
	}
	
	private function inserisciGenere($nome){
		if(empty($nome)){
            throw new Exception("Inserisci un nome corretto", -2);
        }
		if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }
		if(preg_grep( "/^".$nome."$/i" , $this->generi )){
			throw new Exception("Il genere esiste già", -3);
		}
		$db = $this->getDatabase();
		return $db->Insert("Genere", Array("Testo"=>ucfirst($nome)));
	}
	private function modificaGenere($idGenere, $nome){
		if(empty($nome)){
            throw new Exception("Inserisci un nome corretto", -2);
        }
		if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }
		if(preg_grep( "/".$nome."/i" , $this->generi )){
			throw new Exception("Il genere esiste già", -3);
		}
		$db = $this->getDatabase();
		return $db->Update("Genere", Array("Testo"=>ucfirst($nome)), Array("idGenere"=>$idGenere));
	}
	
	private function eliminaGenere($idGenere){
		if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }
		$db = $this->getDatabase();
		return $db->Delete("Genere", Array("idGenere"=>$idGenere));
	}
}
