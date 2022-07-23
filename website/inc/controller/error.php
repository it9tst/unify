<?php

class PageError extends Controller {

	function __construct(){
		parent::__construct();
	}

	function index(){
		$this->getView()->setCssModule("error");
	}

	function notFound(){
		$this->setViewValue("title", "404 Error");
		$this->setViewValue("baseUrl", "https://unify-unipa.it/");

		$this->getView()->setCssModule("error");
		
		$this->renderTemplate("head");
		$this->render('error/index');
	}
	function ajaxError(){
		$this->getView()->setJsonValue('error', 'Request format error');
		$this->getView()->setJsonValue('code', -1);
	}

}
