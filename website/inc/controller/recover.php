<?php

class Recover extends Controller {

    public function __construct() {
        parent::__construct();
		$this->addModel("account");
		$this->loginFromSession();
		$this->redirectLogged();
    }


    public function index(){
        $this->setViewValue("title", "Recover - Unify");

        $this->getView()->loadBootstrap();
		$this->getView()->setCssModule("recover");
		
		$this->renderTemplate('head');
		
        $this->render('recover/index');

		$this->renderTemplate('close');
		
    } // https://unify-unipa.it/recover/change/869ca5895281ec6800e58750b53846b8
	public function ajax($request= Array()){
		if(empty($this->getPost("email"))){
			$this->getView()->setJsonValue('error',"Inserisci un'email valida");
			$this->getView()->setJsonValue('code', -1);
			return;
		}
		$email=$this->getPost("email");
		
		$db=$this->getDatabase();
		$result=$db->Select("Account", Array("Email"=>$email));

		if(empty($result)){
			$this->getView()->setJsonValue('error',"Nessun account associata a questa email");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$result=$result[0];
		
		
		if($this->sendMail($result['Nickname'], $result['Email'], $result['Code'])){
			$this->getView()->setJsonValue('error',"Email inviata");
			$this->getView()->setJsonValue('code', 1);
		}else{
			$this->getView()->setJsonValue('error',"Errore temporaneo, riprovare più tardi");
			$this->getView()->setJsonValue('code', -3);
		}
	}
    public function change($request= Array()){
		if(empty($request[0])){
			header("Location: /");
			die();
		}
		$this->setViewValue("Code", $request[0]);
        $this->setViewValue("title", "Recover - Unify");

        $this->getView()->loadBootstrap();
		$this->getView()->setCssModule("recover");
		
		$this->renderTemplate('head');
		
        $this->render('recover/change');

		$this->renderTemplate('close');
		
    }
	public function changeAjax(){
		if(empty($this->getPost("code"))){
			$this->getView()->setJsonValue('error',"Codice non riconosciuto");
			$this->getView()->setJsonValue('code', -1);
			return;
		}
		$code=$this->getPost("code");
		
        if(empty($this->getPost("pass"))){
			$this->getView()->setJsonValue('error',"Inserisci una password");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$pass=$this->getPost("pass");
		
		if(empty($this->getPost("repass"))){
			$this->getView()->setJsonValue('error',"Inserisci di nuovo la password");
			$this->getView()->setJsonValue('code', -3);
			return;
		}
		$repass=$this->getPost("repass");
		
		if(!$this->checkPass($pass, $repass)){
			$this->getView()->setJsonValue('error',"Inserisci una password corretta");
			$this->getView()->setJsonValue('code', -4);
			return;
		}
		
		$db=$this->getDatabase();
		$result=$db->Select("Account", Array("Code"=>$code));

		if(empty($result)){
			$this->getView()->setJsonValue('error',"Nessun account associato a questo codice");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$result=$result[0];
		
		
		try{
			$db->Update("Account", Array("Password"=>$this->cryptPassword($pass, $result['Nickname'])), Array("Code"=> $code));
			$this->getView()->setJsonValue('message', "Password reimpostata");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error',$e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
    }
		
	private function cryptPassword($pass, $salt){
		return password_hash(STANDARDSALT.$pass.$salt, PASSWORD_BCRYPT, array('cost' => 12));
	}


	private function sendMail($nick, $email, $code){
		require_once UTILS."mail.php";
		$link="https://unify-unipa.it/recover/change/".$code;
		$to=$email;
		$subject=$mailContent['recover']["subject"];
		$message=str_replace("%nick%",$nick,$mailContent['recover']["content"]);
		$message=str_replace("%confirmLink%",$link,$message);
		$headers=str_replace("%mailto%",$to,$mailContent['recover']["headers"]);
		return mail($to, $subject, $message,$headers);
	}

	private function checkPass($pass,$repass){
		if(strcmp($pass,$repass)!=0){
			return false;
		}
		if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{6,20}$/', $pass)) {
			return false;
		}
		return true;
	}
	
	
}
