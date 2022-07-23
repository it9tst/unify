<?php

class Register extends Controller {

    public function __construct() {
        parent::__construct();
		$this->addModel("account");
		$this->loginFromSession();
		$this->redirectLogged();
    }


	/*****  inizio funzioni per renderizzare il sito web *****/
    public function index(){
        $this->setViewValue("title", "Register - Unify");

		$this->getView()->setJsModule("https://code.jquery.com/jquery-3.3.1.min.js", Array(
					"integrity" => "sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=",
					"crossorigin" => "anonymous"
				));

        $this->getView()->loadBootstrap();

		$this->getView()->setCssModule("register");
		$this->getView()->setJsModule("register");


		$this->renderTemplate('head');

        $this->render('register/index');

		$this->renderTemplate('close');
		
    }

	/*****  fine funzioni per renderizzare il sito web *****/







	/*****  inizio funzioni per rispondere alle chiamate ajax *****/ //POST deve avere nick,email,pass,repass
	public function ajax($request= Array()){
		try{
			$user= new accountModel();
			$user->setNickname($this->getPost("nick"));
			$user->setEmail($this->getPost("email"));
			$user->setDataNascita($this->getPost("dataNascita"));
			$user->setSesso($this->getPost("sesso"));
			$user->setMarketing(($this->getPost("marketing")=="true")? true:false);
			$user->setMarketing3rd(($this->getPost("marketing3rd")=="true")? true:false);
			if($this->register($user,$this->getPost("pass"),$this->getPost("repass"))){
				$this->getView()->setJsonValue('success', 'Registrazione effettuata con successo');
				$this->getView()->setJsonValue('code', 1);	
			}
		}
		catch(Exception $e){
			$this->getView()->setJsonValue('error',$e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	/*****  fine funzioni per rispondere alle chiamate ajax *****/





	/*****  inizio funzioni utili al controllore *****/



	//controllo validità password
	private function checkPass($pass,$repass){
		if(strcmp($pass,$repass)!=0){
			throw new Exception("Le password non coincidono",-11);
		}
		if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,20})/', $pass)) {
			throw new Exception("Password non valida",-12);
		}
	}
	// cripto la password
	private function cryptPassword($pass, $salt){
		return password_hash(STANDARDSALT.$pass.$salt, PASSWORD_BCRYPT, array('cost' => 12));
	}

	// utilizzo per effettuare la register(controllo se email o nick già presente)
    private function register($user, $pass, $repass){

        if(empty($this->getDatabase())){
            throw new Exception("No db connection", -1);
        }

		$db = $this->getDatabase();
		$nickPresente= $db->select("Account", Array("Nickname"=>$db->escapeString($user->getNickname())), null, 1)[0];
		if(count($nickPresente)>0){
			throw new Exception("Nickname non disponibile", -3);
		}
		$mailPresente= $db->select("Account", Array("Email"=>$db->escapeString($user->getEmail())), null, 1)[0];
		if(count($mailPresente)>0){
			throw new Exception("Email non disponibile", -4);
		}
		$user->setCode();
		$this->checkPass($pass,$this->getPost("repass"));
		$db->Insert("Account", Array("Email"=>$user->getEmail(),"Nickname"=>$user->getNickname(),"Password"=>$this->cryptPassword($pass,$user->getNickname()),"DataNascita"=>$user->getDataNascita(),"Marketing"=>$user->getMarketing(),"Marketing3RD"=>$user->getMarketing3rd(),"Code"=>$user->getCode()));
		$this->sendMail($user);
		return true; //register a buon fine
		
    }
	/*****  fine funzioni utili al controllore *****/
	private function sendMail($user){
		require_once UTILS."mail.php";
		$link="https://unify-unipa.it/login/conferma/".$user->getCode();
		$to=$user->getEmail();
		$subject=$mailContent['conferma']["subject"];
		$message=str_replace("%nick%",$user->getNickname(),$mailContent['conferma']["content"]);
		$message=str_replace("%confirmLink%",$link,$message);
		$headers=str_replace("%mailto%",$to,$mailContent['conferma']["headers"]);
		mail($to, $subject, $message,$headers);
	}

}
