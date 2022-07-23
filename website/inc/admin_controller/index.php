<?php

class Index extends Controller {
    public function __construct() {
        parent::__construct();

		$this->addModel("account");
		$this->loginFromSession();
		$this->redirectNotLogged();
		$this->redirectNotAdmin();
    }



    public function index(){
        $this->setViewValue("Artisti",Array());

        $this->getView()->setJsModule("admin");
        $this->getView()->loadBootstrap();

        $this->getView()->setCssModule("admin");

		$this->renderTemplate('head');
        $this->render('index/index');
		$this->renderTemplate('footer');
	}


	public function addSong(){
		$this->render('admin/addsong');
	}
}
