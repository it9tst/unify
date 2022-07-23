<?php

class Friend extends Controller {

    function __construct() {
        parent::__construct();
		$this->addModel("account");
		if($this->loginFromSession()){
			return;
		}
		$this->loginFromCookie();
		$this->redirectNotLogged("https://unify-unipa.it/?action=login");
    }

	private function retriveFriend(){
		$db=$this->getDatabase();
		$now = new DateTime();
		$idAcc=$this->account->getIdAccount();

		$friends=$db->raw("SELECT Ac.Nickname,Ac.idAccount,Ac.UltimaConnessione,B.Titolo, B.idBrano, Am.AmicoA, Am.AmicoB, Am.Stato, Ac.Foto, GROUP_CONCAT(DISTINCT AR.Name ORDER BY AB.Indice) AS Artisti, GROUP_CONCAT(DISTINCT AR.idArtist ORDER BY AB.Indice) AS idArtisti FROM Amici Am JOIN Account Ac ON (Am.AmicoA=".$idAcc." AND Am.AmicoB=Ac.IdAccount) OR (Am.AmicoA=Ac.IdAccount AND Am.AmicoB=".$idAcc.") LEFT JOIN Brano B ON B.idBrano = (SELECT Asco.RefBrano FROM Ascoltati Asco WHERE Asco.RefAccount=Ac.IdAccount ORDER BY Asco.DataAscolto DESC LIMIT 1) LEFT JOIN ArtistaBrano AB ON AB.RefBrano=B.idBrano LEFT JOIN Artista AR ON AR.idArtist=AB.RefArtista GROUP BY B.idBrano, Ac.IdAccount");


		if(!$friends){
			return Array("online"=>Array(), "offline"=>Array(), "sent"=>Array(), "waiting"=>Array(), "blocked"=>Array());
		}

		$onlineFriends= Array();
		$offlineFriends= Array();
		$requestSent= Array();
		$requestWaiting= Array();
		$blockedFriend= Array();

		foreach($friends as $k=>$v){
			if($v['Stato']==2 && $v['AmicoA']==$idAcc){
				$blockedFriend[]=$v;
				continue;
			}
			if($v['Stato']==2 && $v['AmicoB']==$idAcc){
				continue;
			}
			if($v['Stato']==3 && $v['AmicoB']==$idAcc){
				$blockedFriend[]=$v;
				continue;
			}
			if($v['Stato']==3 && $v['AmicoA']==$idAcc){
				continue;
			}
			if($v['Stato']==0 && $v['AmicoB']==$idAcc){
				$requestWaiting[]=$v;
				continue;
			}
			if($v['Stato']==0){
				$requestSent[]=$v;
				continue;
			}
			if(empty($v['UltimaConnessione'])){
				unset($v['UltimaConnessione']);
				$offlineFriends[]=$v;
				continue;
			}
			$datetime = new DateTime($v['UltimaConnessione']);
			$interval = $datetime->diff($now);
			$minutes = $interval->days * 24 * 60;
			$minutes += $interval->h * 60;
			$minutes += $interval->i;
			unset($v['UltimaConnessione']);
			if($minutes<3){
				$onlineFriends[]=$v;
			}else{
				$offlineFriends[]=$v;
			}
		}
		return Array("online"=>$onlineFriends, "offline"=>$offlineFriends, "sent"=>$requestSent, "waiting"=>$requestWaiting, "blocked"=>$blockedFriend);
	}
	public function searchFriendAjax(){
		$idAcc=$this->account->getIdAccount();
		$name=$this->getPost("name");
		if(empty($name)){
			$this->getView()->setJsonValue('error', "Inserisci una nome corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$db=$this->getDatabase();
		$result=$db->Select("Account", "Nickname='".$db->escapeString($name)."' AND IdAccount NOT IN (SELECT AmicoA FROM Amici WHERE AmicoB=".$idAcc." UNION DISTINCT SELECT AmicoB FROM Amici WHERE AmicoA=".$idAcc.")", Array("Nickname", "IdAccount"))[0];
		if($result){
			$this->getView()->setJsonValue('message', "Utente trovato");
			$this->getView()->setJsonValue('code', 1);
			$this->getView()->setJsonValue('Utente', $result);
		}else{
			$this->getView()->setJsonValue('error', "Utente non trovato");
			$this->getView()->setJsonValue('code', -1);
		}

	}
	public function addFriendAjax(){
		$id=$this->getPost("id");
		if(empty($id)){
			$this->getView()->setJsonValue('error', "Inserisci una id corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$db=$this->getDatabase();
		try{
			$result=$db->Insert("Amici", Array("AmicoA"=>$this->account->getIdAccount(), "AmicoB"=>$id));
			$this->getView()->setJsonValue('message', "Utente aggiunto");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}

	public function rimuoviAmicoAjax(){
		$id=$this->getPost("id");
		if(empty($id)){
			$this->getView()->setJsonValue('error', "Inserisci una id corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$db=$this->getDatabase();
		try{
			$result=$db->Delete("Amici", "(AmicoA=".$this->account->getIdAccount()." AND AmicoB=".$id.") OR (AmicoB=".$this->account->getIdAccount()." AND AmicoA=".$id.")");
			$this->getView()->setJsonValue('message', "Utente eliminato");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	public function accettaAmicoAjax(){
		$id=$this->getPost("id");
		if(empty($id)){
			$this->getView()->setJsonValue('error', "Inserisci una id corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$db=$this->getDatabase();
		try{
			$result=$db->Update("Amici", Array("Stato"=>1), "(AmicoA=".$this->account->getIdAccount()." AND AmicoB=".$id.") OR (AmicoB=".$this->account->getIdAccount()." AND AmicoA=".$id.")");
			$this->getView()->setJsonValue('message', "Utente accettato");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	public function bloccaAmicoAjax(){
		$id=$this->getPost("id");
		if(empty($id)){
			$this->getView()->setJsonValue('error', "Inserisci una id corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$db=$this->getDatabase();
		try{
			$result=$db->Update("Amici", Array("Stato"=>2), "(AmicoA=".$this->account->getIdAccount()." AND AmicoB=".$id.")");
			$result=$db->Update("Amici", Array("Stato"=>3), "(AmicoB=".$this->account->getIdAccount()." AND AmicoA=".$id.")");
			$this->getView()->setJsonValue('message', "Utente bloccato");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	public function sbloccaAmicoAjax(){
		$id=$this->getPost("id");
		if(empty($id)){
			$this->getView()->setJsonValue('error', "Inserisci una id corretto");
			$this->getView()->setJsonValue('code', -2);
			return;
		}
		$db=$this->getDatabase();
		try{
			$result=$db->Update("Amici", Array("Stato"=>1), "(AmicoA=".$this->account->getIdAccount()." AND AmicoB=".$id.") OR (AmicoB=".$this->account->getIdAccount()." AND AmicoA=".$id.")");
			$this->getView()->setJsonValue('message', "Utente sbloccato");
			$this->getView()->setJsonValue('code', 1);
		}catch(Exception $e){
			$this->getView()->setJsonValue('error', $e->getMessage());
			$this->getView()->setJsonValue('code', $e->getCode());
		}
	}
	public function retriveFriendAjax(){
		$this->getView()->setJsonValue('result', $this->retriveFriend());
	}
}
