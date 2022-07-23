<?php

class Admin extends Controller {
    public function __construct() {
        parent::__construct();
		
		$this->addModel("account");
		$this->loginFromSession();
		$this->redirectNotLogged();
		$this->redirectNotAdmin();
    }
    
	
	
    public function index(){
		echo "Admin";
	}
	
	
	public function addSong(){
		$this->render('admin/addsong');
	}
}