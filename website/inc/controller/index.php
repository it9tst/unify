<?php

class Index extends Controller {

    function __construct() {
        parent::__construct();
		$this->addModel("account");
		if($this->loginFromSession()){
			return;
		}
		$this->loginFromCookie();
    }

    function index() {

		$this->setViewValue("title", "Home - Unify");

		$this->getView()->loadBootstrap();

		$this->getView()->setJsModule("login");
        $this->getView()->setCssModule("home");

		$this->renderTemplate('head');
		$this->render('index/index');

    }
	public function logoutAjax($array){
		$this->destroySession();
		$this->destroyCookie();
		$this->getView()->setJsonValue('code', '1');
	}
}
