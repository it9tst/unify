<?php

class Login extends Controller {
    public function __construct() {
        parent::__construct();
		
		$this->addModel("account");
		if(!isset($request['type']) || $request['type']!="mobile"){
			$this->loginFromSession();
			$this->redirectLogged();
		}
		
    }
    
	
	/*****  inizio funzioni per renderizzare il sito web *****/
    public function index(){
        header("Location: /");
		die();
    }
	
	public function recover($array){
		print_r($array);
	}
	
	/*****  fine funzioni per renderizzare il sito web *****/
	
	
	
	
	
	
	
	/*****  inizio funzioni per rispondere alle chiamate ajax *****/
	public function ajax($request= Array()){
		$email=$this->getPost("email");
		if(empty($email)){
			$this->getView()->setJsonValue('error', 'Inserisci una email valida');
			return;
		}
		$pass=$this->getPost("pass");
		if(empty($pass)){
			$this->getView()->setJsonValue('error', 'Inserisci una password');
			return;
		}
		$remember=false;
		if(!empty($this->getPost("remember"))){
			if($this->getPost("remember")=="true"){
				$remember=true;
			}
		}
		
		try{
			if($this->login($email, $pass, $remember)){
				$this->getView()->setJsonValue('success', 'Login effettuato con successo');
				$this->getView()->setJsonValue('code', 1);
				
				if(isset($request['type']) && $request['type']=="mobile"){
					$this->getView()->setJsonValue('sessid', session_id());
					$this->getView()->setJsonValue('Nickname', $this->getSessionValue("Nickname"));
					$this->getView()->setJsonValue('Foto', $this->getSessionValue("Foto"));
				}
				
			}
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
		
	}
	
	public function recoverAjax($array){
		$this->getView()->setJsonValue('recover', 'prova');
	}
	public function logoutAjax($array){
		$this->getView()->setJsonValue('recover', 'prova');
	}
	
	/*****  fine funzioni per rispondere alle chiamate ajax *****/
	
	
	
	
	
	/*****  inizio funzioni utili al controllore *****/
	
	
	// cripto la password
	private function cryptPassword($pass, $salt){
		return password_hash(STANDARDSALT.$pass.$salt, PASSWORD_BCRYPT, array('cost' => 12));
	}

	// varifico la passwrod criptata
	private function verifyPassword($pass, $salt, $hash){
		return password_verify(STANDARDSALT.$pass.$salt, $hash);
	}


	// utilizzo per effettuare il login con login email o login nickname
    private function login($email, $password, $remember=false){
        if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }

		$db = $this->getDatabase();
		$result= $db->select("Account", Array("Email"=>$db->escapeString($email)), null, 1)[0];

		if(count($result)<1){
			throw new Exception("Nessun utente con questa email", -2);
		}

		if(!$this->verifyPassword($password, $result['Nickname'], $result['Password'])){
			throw new Exception("Password errata", -3);
		}
        if($result['Verifica']==0){
			throw new Exception("Non hai verificato la email", -4);
		}

		$this->setSessionValue("idAccount",$result['IdAccount']);

		$this->setSessionValue("Nickname",$result['Nickname']);
		$this->setSessionValue("Foto",$result['Foto']);

		if($remember){
			$token=md5(uniqid($result['Nickname'], true));
			$date=new DateTime();
			$date->add(new DateInterval('P30D'));
			$expires=$date->format("Y-m-d H:i:s");
			$db->Update("Account", Array("CookieToken"=>$token, "CookieExpires"=>$expires), Array("IdAccount"=>$result['IdAccount']));
			setcookie("a", $result['Code'], time()+30*24*60*60, "/", ".unify-unipa.it");
			setcookie("b", $token, time()+30*24*60*60, "/", ".unify-unipa.it"); 
		}
		return true;
    }
	public function conferma($code){
		$db = $this->getDatabase();
		$db->Update("Account",array("Verifica"=>1),array("Code"=>$code[0]));
		header("Location:/?action=login");
		die();
	}
	
	
	/*****  fine funzioni utili al controllore *****/
}