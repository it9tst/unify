<?php

class Login extends Controller {
    public function __construct() {
        parent::__construct();

		$this->addModel("account");
		$this->loginFromSession();
		$this->redirectLogged();
    }



    public function index(){
		if(empty($this->getPost("submit"))){
			$this->loadLogin();
			return;
		}
		$email=$this->getPost("email");
		if(empty($email)){
			$this->setViewValue("error", "Inserisci un email valida");
			$this->loadLogin();
			return;
		}
		$pass=$this->getPost("pass");
		if(empty($pass)){
			$this->setViewValue('error', 'Inserisci una password');
			$this->loadLogin();
			return;
		}
		
		
		try{
			$this->login($email, $pass);
			header("Location:/");
			die();
		}catch(Exception $e){
			$this->setViewValue('error', $e->getMessage());
			$this->loadLogin();
		}

	}
	
	private function loadLogin(){
		$this->setViewValue("title", "Login - Unify");
		$this->getView()->loadBootstrap();

		$this->getView()->setCssModule("admin");

		$this->renderTemplate('head');
		$this->render('login/index');
	}
	
	private function login($email, $password){
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
		if($result['Tipo']!=2){
			throw new Exception("Non sei un admin", -4);
		}
		$this->setSessionValue("idAccount",$result['IdAccount']);
		return true;
    }
	private function verifyPassword($pass, $salt, $hash){
		return password_verify(STANDARDSALT.$pass.$salt, $hash);
	}
}
