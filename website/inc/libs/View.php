<?php
class View {
	private $jsModuleArray;
	private $cssModuleArray;
	private $jsonArray;
	private $media=false;
	
	public function loadView($name){
        require VIEW. $name . '.php';    
    }
	public function loadViewTemplate($name){
        require TEMPLATE. $name . '.php';    
    }
	public function printJsModule(){
		
		if(gettype($this->jsModuleArray)!="array")
			return;
		
		foreach($this->jsModuleArray as $value){
			$src=$value['src'];
			if(!filter_var($src, FILTER_VALIDATE_URL)){
				$src=JSPATH.$src.".js";
			}
			$attrs=Array();
			$attrs[]='src="'.$src.'"';
			$attrs[]='type="text/javascript"';
			foreach($value['attr'] as $key=>$attr){
				$attrs[]=$key.'="'.$attr.'"';
			}
			$attrs=implode(" ", $attrs);
			echo "<script $attrs></script>\r\n";
		}
	}
	public function printCssModule(){
		if(gettype($this->cssModuleArray)!="array")
			return;

		foreach($this->cssModuleArray as $value){
			$href=$value['href'];
			if(!filter_var($href, FILTER_VALIDATE_URL)){
				$href=CSSPATH.$href.".css";
			}
			$attrs=Array();
			$attrs[]='href="'.$href.'"';
			$attrs[]='rel="stylesheet"';
			$attrs[]='type="text/css"';
			foreach($value['attr'] as $key=>$attr){
				$attrs[]=$key.'="'.$attr.'"';
			}
			$attrs=implode(" ", $attrs);
			echo "<link $attrs\>\r\n";
		}
		
	}
	
	public function setJsModule($src, $attr=Array()){
		if(empty($src)){
			throw new Exception("Provide a valid src attribute");
		}
		$this->jsModuleArray[]=Array("src"=>$src, "attr"=>$attr);
	}
	public function setCssModule($href, $attr=Array()){
		if(empty($href)){
			throw new Exception("Provide a valid href attribute");
		}
		$this->cssModuleArray[]=Array("href"=>$href, "attr"=>$attr);
	}
	
	public function setJsonValue($key, $value){
		$this->jsonArray[$key]=$value;
	}
	public function printJson(){
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode($this->jsonArray);
	}
	
	public function __destruct(){
		if(!empty($this->jsonArray) && !$this->media){
			$this->printJson();
		}
	}
	public function enableMedia(){
		$this->media=true;
	}
	
	public function loadBootstrap(){
		$this->setJsModule("https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js");
		$this->setJsModule("https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js");
		$this->setJsModule("https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js");
		$this->setCssModule("https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css");
	}
}