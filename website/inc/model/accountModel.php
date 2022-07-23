<?php

class AccountModel{
    private $idAccount;
    private $nickname;
	private $email;
	private $nome;
	private $cognome;
	private $dataNascita;
	private $sesso;
	private $paese;
	private $cellulare;
	private $foto;
	private $tentativiAccesso;
	private $tipo;
	private $code;
	private $marketing;
	private $marketing3rd;
	
	public function getIdAccount(){
		return $this->idAccount;
	}

	public function setIdAccount($idAccount){
		if($idAccount<1){
			throw new Exception("Id account non valido",-1);
		}
		$this->idAccount = $idAccount;
	}

	public function getNickname(){
		return $this->nickname;
	}

	public function setNickname($nickname){
		if(!preg_match("/^[a-zA-Z0-9]+([a-zA-Z0-9](_|-| )[a-zA-Z0-9])*[a-zA-Z0-9]+$/",$nickname)|| strlen($nickname)<4 || strlen($nickname)>18){
			throw new Exception("Nickname non valido",-3);
		}
		$this->nickname = $nickname;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
			throw new Exception("Email non valida",-4);
		}
		$this->email = $email;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		if(!preg_match('/^([a-zA-Z]+ {0,1}[a-zA-Z]*)*$/', $nome)) {
			throw new Exception("Nome non valido",-11);
		}
		$this->nome = $nome;
	}

	public function getCognome(){
		return $this->cognome;
	}

	public function setCognome($cognome){
		if(!preg_match('/^([a-zA-Z]+ {0,1}[a-zA-Z]*)*$/', $cognome)) {
			throw new Exception("Cognome non valido",-12);
		}
		$this->cognome = $cognome;
	}

	public function getDataNascita(){
		return $this->dataNascita;
	}

	public function setDataNascita($dataNascita){
		if(!$this->validateDate($dataNascita)){
			throw new Exception("Data di nascita non valida",-5);
		}
		if(!$this->isMaggiorenne($dataNascita)|| date("Y",strtotime($dataNascita))<1910){
			throw new Exception("Per registrarti devi aver compiuto 16 anni in base al Regolamento Europeo in materia di Protezione dei Dati Personali (GDPR).",-5);
		}
		$this->dataNascita = $dataNascita;
	}
	
	private function isMaggiorenne($dataNascita){
		$day= date("d",strtotime($dataNascita));
		$month= date("m",strtotime($dataNascita));
		$year= date("Y",strtotime($dataNascita));
		$currDay=intval(date("d"));
		$currYear=intval(date("Y"));
		$currMonth=intval(date("m"));
		if($currYear-$year>16)
			return true;
		else if($currYear-$year==16){
			if($currMonth<$month)
				return false;
			else if($currMonth>$month)
				return true;
			else{
				if($currDay<$day)
				return false;
			else return true;
			}
		}
		return false;
	}
	public function getSesso(){
		return $this->sesso;
	}

	public function setSesso($sesso){
		if($sesso!="M" && $sesso!="F"){
			throw new Exception("Sesso non valido",-6);
		}
		$this->sesso = $sesso;
	}

	public function getPaese(){
		return $this->paese;
	}

	public function setPaese($paese){
		require UTILS."utils.php";
		if(!array_key_exists($paese, $countries)){
			throw new Exception("Paese errato",-7);
		}
		$this->paese = Array("code"=>$paese, "name"=>$countries[$paese]);
	}

	public function getCellulare(){
		return $this->cellulare;
	}

	public function setCellulare($cellulare){
	if(!preg_match('/^\+[0-9]{1,2} {0,1}[0-9]*$/', $cellulare)) {
			throw new Exception("Numero di cellulare non valido ".$cellulare,-13);
		}
		$this->cellulare = $cellulare;
	}

	public function getFoto(){
		return $this->foto;
	}

	public function setFoto($foto){
		/*if(!file_exists(PHOTO.$foto)){
			$this->foto = "default.png";
		}else{
			$this->foto = $foto;
		}*/
		$this->foto = $foto;
	}

	public function getTentativiAccesso(){
		return $this->tentativiAccesso;
	}

	public function setTentativiAccesso($tentativiAccesso){
		$this->tentativiAccesso = $tentativiAccesso;
	}

	public function getTipo(){
		return $this->tipo;
	}

	public function setTipo($tipo){
		$this->tipo = $tipo;
	}
	
	private function validateDate($date, $format = 'Y-m-d'){
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}
	
	public function getCode(){
		return $this->code;
	}

	public function setCode(){
		if(empty($this->nickname)||empty($this->email)){
			throw new Exception("Codice non calcolabile",-8);
		}
		$this->code=md5($this->getEmail().$this->getNickname());
	}
	public function getMarketing(){
		return $this->marketing;
	}

	public function setMarketing($marketing){
		if(!is_bool($marketing))
			throw new Exception("Checkbox marketing non valida",-9);
		$this->marketing = ($marketing)? 1:0;
	}
	public function getMarketing3rd(){
		return $this->marketing3rd;
	}

	public function setMarketing3rd($marketing3rd){
		if(!is_bool($marketing3rd))
			throw new Exception("Checkbox marketing di terze parti non valida",-10);
		$this->marketing3rd = ($marketing3rd)? 1:0;
	}
}
