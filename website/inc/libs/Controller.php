<?php
class Controller {
	private $database;
	private $session;
	private $post;
	private $view;
	private $logged=false;
	function __construct() {
		$this->session=new Session();
		$this->database=new Database(DBHOST, DBUSER, DBPASS, DBNAME);
		$this->post=new Post();
		$this->view = new View();
	}

	public function addModel($name){
		$file = MODEL . $name.'Model.php';
		if (file_exists($file)) {
			require_once $file;
			$modelName = $name . 'Model';
			$this->$name = new $modelName();
		}        
	}
	protected function setViewValue($key, $value){
		$this->view->$key=$value;
	}
	protected function getView(){
		return $this->view;
	}
	protected function getDatabase(){
		return $this->database;
	}
	protected function getViewValue($key){
		if(isset($this->view->$key))
			return $this->view->$key;
		return null;
	}
	
	protected function render($name){
		$this->view->loadView($name);
	}
	protected function renderTemplate($name){
		$this->view->loadViewTemplate($name);
	}
	
	protected function getPost($key){
		return $this->post->get($key);
	}
	protected function getSessionValue($key){
		return $this->session->get($key);
	}
	protected function setSessionValue($key, $value){
		$this->session->set($key, $value);
	}
	
	protected function loginFromSession(){
		$this->setViewValue("isLogged", false);
		$id=$this->getSessionValue("idAccount");
		if(empty($id)){
			return false;
		}
		$db=$this->getDatabase();
		$result= $db->select("Account", Array("IdAccount"=>$id), null, 1)[0];
		if(count($result)<1){
			return false;
		}
		$user=new AccountModel();
		try{
			$user->setIdAccount($result['IdAccount']);
			$user->setNickname($result['Nickname']);
			$user->setEmail($result['Email']);
			(!empty($result['Nome'])?$user->setNome($result['Nome']):"");
			((!empty($result['Cognome']))?$user->setCognome($result['Cognome']):"");
			((!empty($result['DataNascita']))?$user->setDataNascita($result['DataNascita']):"");
			((!empty($result['Sesso']))?$user->setSesso($result['Sesso']):"");
			((!empty($result['Paese']))?$user->setPaese($result['Paese']):"");
			((!empty($result['Cellulare']))?$user->setCellulare($result['Cellulare']):"");
			((!empty($result['Foto']))?$user->setFoto($result['Foto']):"");
			((!empty($result['TentativiAccesso']))?$user->setTentativiAccesso($result['TentativiAccesso']):"");
			((!empty($result['Tipo']))?$user->setTipo($result['Tipo']):"");
			$this->logged=true;
			$this->account=$user;
			$this->setViewValue("isLogged", true);
			$this->setViewValue("Nickname", $this->account->getNickname());
			$this->setViewValue("Account", $this->account);
			$db->Update("Account", Array("UltimaConnessione"=>date("Y-m-d H:i:s")), Array("IdAccount"=>$id));
			return true;
		}catch(Exception $e){
			$this->user=null;
		}
		return false;
	}
	
	protected function loginFromCookie(){
		$this->setViewValue("isLogged", false);
		
		if(!isset($_COOKIE['a']) || empty($_COOKIE['a']) || !isset($_COOKIE['b'])){
			return false;
		}
		$code=$_COOKIE['a'];
		$token=$_COOKIE['b'];

		$db=$this->getDatabase();
		$result= $db->select("Account", Array("Code"=>$code), null, 1)[0];
		if(count($result)<1){
			return false;
		}
		if($result['CookieToken']!=$token){
			return false;
		}
		if(empty($result['CookieExpires']) || strtotime($result['CookieExpires'])<strtotime("now")){
			return false;
		}
		
		$user=new AccountModel();
		try{
			$user->setIdAccount($result['IdAccount']);
			$user->setNickname($result['Nickname']);
			$user->setEmail($result['Email']);
			(!empty($result['Nome'])?$user->setNome($result['Nome']):"");
			((!empty($result['Cognome']))?$user->setCognome($result['Cognome']):"");
			((!empty($result['DataNascita']))?$user->setDataNascita($result['DataNascita']):"");
			((!empty($result['Sesso']))?$user->setSesso($result['Sesso']):"");
			((!empty($result['Paese']))?$user->setPaese($result['Paese']):"");
			((!empty($result['Cellulare']))?$user->setCellulare($result['Cellulare']):"");
			((!empty($result['Foto']))?$user->setFoto($result['Foto']):"");
			((!empty($result['TentativiAccesso']))?$user->setTentativiAccesso($result['TentativiAccesso']):"");
			((!empty($result['Tipo']))?$user->setTipo($result['Tipo']):"");
			$this->logged=true;
			$this->account=$user;
			$this->setViewValue("isLogged", true);
			$this->setViewValue("Nickname", $this->account->getNickname());
			$this->setViewValue("Account", $this->account);
			$db->Update("Account", Array("UltimaConnessione"=>date("Y-m-d H:i:s")), Array("IdAccount"=>$result['IdAccount']));
			$this->setSessionValue("idAccount",$result['IdAccount']);
			return true;
		}catch(Exception $e){
			$this->user=null;
		}
	}
	
	
	public function isLogged(){
		return $this->logged;
	}
	
	public function redirectNotLogged($page= "/login"){
		if(!$this->isLogged()){
			header("Location: ".$page);
			die();
		}
	}
	
	public function redirectLogged($page= "/"){
		if($this->isLogged()){
			header("Location: ".$page);
			die();
		}
	}
	public function redirectNotAdmin($page= "https://unify-unipa.it"){
		
		if($this->account->getTipo()!=2){
			header("Location: ".$page);
			die();
		}
	}
	protected function destroySession(){
		$this->session->destroy();
	}
	
	protected function destroyCookie(){
		if(isset($_COOKIE['a'])){
			unset($_COOKIE['a']);
			setcookie('a', null, -1, "/", ".unify-unipa.it");
		}
		if(isset($_COOKIE['b'])){
			unset($_COOKIE['b']);
			setcookie('b', null, -1, "/", ".unify-unipa.it");
		}
		if(isset($_COOKIE['PHPSESSID'])){
			unset($_COOKIE['PHPSESSID']);
			setcookie('PHPSESSID', null, -1, "/", ".unify-unipa.it");
		}
	}
}