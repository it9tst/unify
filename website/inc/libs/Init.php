<?php
if(isset($adminSetup)){
	require_once "admin_config.php";
}else{
	require_once "config.php";
}
class Init {
	private $errorFile = 'error.php';
	private $defaultFile = 'index.php';
	
	private $url = null;
	private $controller = null;
	   
	
	public function __construct($type="page", $dafaultController="index"){
		switch($type){
			case "page":
				$this->parseUrl();
				if (empty($this->url[0])) {
					$this->url[0]=$dafaultController;
				}
				
				$this->loadController();
				$this->callControllerMethod();
			break;
			case "ajax":
				$this->parseUrlAjax();

				if (empty($this->url['request'])){
					$this->errorAjax();
					exit;
				}
				
				$this->loadControllerAjax();
				$this->callControllerMethodAjax();
			break;
		}
	}
	
	private function parseUrl(){
		$url = isset($_GET['url'])?$_GET['url']:null;
		$url = rtrim($url, '/');
		$url = filter_var($url, FILTER_SANITIZE_URL);
		$this->url = explode('/', $url);
	}
	
	private function parseUrlAjax(){
		$this->url = $_GET;
	}


	private function loadController(){
		$file = CONTROLLER. $this->url[0] . '.php';
		if (file_exists($file)) {
			require $file;
			$this->controller = new $this->url[0]();
		} else {
			$this->error();
			exit;
		}
	}
	
	private function loadControllerAjax(){
		$file = CONTROLLER. $this->url['request'] . '.php';
		if (file_exists($file)) {
			require $file;
			$this->controller = new $this->url['request']();
		} else {
			$this->errorAjax();
			exit;
		}
	}
	
	private function callControllerMethod(){
		$length = count($this->url);

		if ($length > 1){
			if (!method_exists($this->controller, $this->url[1])) {
				$this->error();
				exit;
			}
			$reflection = new ReflectionMethod($this->controller, $this->url[1]);
			if(!$reflection->isPublic()){
				$this->error();
				exit;
			}
		}
		
		
		if($length>2){
			$this->controller->{$this->url[1]}(array_slice($this->url,2));
		}else if($length==2){
			$this->controller->{$this->url[1]}();
		}else{
			$this->controller->index();
		}
	}
	private function callControllerMethodAjax(){

		if(empty($this->url['action'])){
			$method="ajax";
		}else{
			$method=$this->url['action']."Ajax";
		}
		if (!method_exists($this->controller, $method)) {
			$this->errorAjax();
			exit;
		}
		unset($this->url['request']);
		unset($this->url['action']);
		$this->controller->$method($this->url);
	}

	private function error(){
		require CONTROLLER.$this->errorFile;
		$this->controller = new PageError();
		$this->controller->notFound();
	}
	private function errorAjax(){
		require CONTROLLER.$this->errorFile;
		$this->controller = new PageError();
		$this->controller->ajaxError();
	}
	
}