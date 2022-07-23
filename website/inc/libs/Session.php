<?php

class Session{
	public function __construct(){
		if (session_status() == PHP_SESSION_NONE) {
			session_set_cookie_params(0, '/', '.unify-unipa.it');
			
			if(isset($_GET['phpsess'])){
				session_id($_GET['phpsess']);
			}
			
			session_start();
		}
	}
    
	public function set($key, $value){
		$_SESSION[$key]=$value;
	}
    
	public function get($key){
		if (isset($_SESSION[$key]))
			return $_SESSION[$key];
	}
    
    public function destroy(){
        session_unset();
		session_destroy();
    }
}