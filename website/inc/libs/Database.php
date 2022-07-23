<?php
class Database{
	
	// ancora da migliorare, però si può già utilizzare (Non l'ho avviata mai non so se funge)
	//Mmmm bene
	// oggetto mysqli
	private $link;
	
	// inizializza connessione
	public function __construct ($host, $user, $pass, $database) {
		if(!isset($host) || !isset($user) || !isset($pass) || !isset($database)){
			throw new Exception("Wrong database init configuration");
		}
		$connection = mysqli_connect($host, $user, $pass,$database);

		if (!$connection){
			throw new Exception("Connection failed: " . mysqli_connect_error(), mysqli_connect_errno());
		}else{
			$this->link = $connection;
			 $this->link->set_charset("utf8");
		}
	}

	public function Select($table, $where, $columns=Array(), $limit=null, $offset=null){
		$link=$this->link;

		if (!$link){
			throw new Exception("Database not initialized");
		}
		if(empty($columns)){
			$cols="*";
		}else{
			foreach($columns as $col ){
				$cols[]=$col;
			}
			$cols=implode(",", $columns);
		}
		
		if(gettype($where)=="array"){
			foreach($where as $key=>$value ){
				$w[]=$key."='".$value."'";
			}
			$where=implode(" AND ", $w);
		}elseif(gettype($where)=="string"){
			;
		}else{
			throw new Exception("Insert a correct where clause");
		}
		
		$sql = "SELECT $cols FROM $table WHERE $where";
		if($limit && preg_match("/^[0-9]+$/", $limit)){
			$sql.=" LIMIT ".$limit;
		}
		
		if($offset && preg_match("/^[0-9]+$/", $offset)){
			$sql.=" OFFSET ".$offset;
		}

		$result = $link->query($sql);
		
		if(!$result){
			throw new Exception("Select error:".$link->error, $link->errno);
		}
		$row = $result->num_rows;

		if($row <1){
			$rows=false;
		}else{
			while ($row = $result->fetch_assoc()){
				$rows[] =$row;
			}
		}
		return $rows;
	}

	public function Insert($table, $rows = array()){
		$link=$this->link;

		if (!$link){
			throw new Exception("Database not initialized");
		}

		foreach(array_keys($rows) as $key ){
			$columns[]="$key";
			$values[]="'".$this->escapeString($rows[$key])."'";
		}
		$columns=implode(",", $columns);
		$values=implode(",", $values);

		$sql = "INSERT INTO $table ($columns) VALUES ($values)";

		$result = $link->query($sql);
		if(!$result){
			throw new Exception("Insert error:".$link->error, $link->errno);
		}
		$result=$link->insert_id;
		return $result;
	}
	
	public function Update($table, $fields, $where){
		$link= $this->link;
		
		if (!$link){
			throw new Exception("Database not initialized");
		}
		$fieldString=Array();
		foreach($fields as $key=>$value){
			if($key){
				$fieldString[]= " $key='".$this->escapeString($value)."'";
			}
		}
		$fieldString=implode( ',', $fieldString );

		if(gettype($where)=="array"){
			foreach($where as $key=>$value ){
				$w[]=$key."='".$value."'";
			}
			$where=implode(" AND ", $w);
		}elseif(gettype($where)=="string"){
			;
		}else{
			throw new Exception("Insert a correct where clause");
		}
		$sql="UPDATE $table SET $fieldString WHERE $where ";
		$result= $link->query($sql);
		if(!$result){
			throw new Exception("Update error:".$link->error, $link->errno);
		}
		return $link->affected_rows;
	}

	public function Delete($table, $where){
		$link= $this->link;
		
		if (!$link){
			throw new Exception("Database not initialized");
		}
		if(gettype($where)=="array"){
			foreach($where as $key=>$value ){
				$w[]=$key."='".$value."'";
			}
			$where=implode(" AND ", $w);
		}elseif(gettype($where)=="string"){
			;
		}else{
			throw new Exception("Insert a correct where clause");
		}
		$sql="DELETE FROM $table WHERE $where";

		$result= $link->query($sql);
		if(!$result){
			throw new Exception("Delete error:".$link->error, $link->errno);
		}
		return $link->affected_rows;
	}




	public function raw($sql){
		$link= $this->link;
		if (!$link){
			throw new Exception("Database not initialized");
		}
		
		$result= $link->query($sql);
		if(!$result){
			throw new Exception("Select error:".$link->error, $link->errno);
		}
		if(gettype($result)=="boolean"){
			return $result;
		}
		$rows=Array();
		while ($row = $result->fetch_assoc()){
			$rows[] =$row;
		}
		return $rows;
	}


	public function escapeString($string){
		$string = $this->link->real_escape_string($string);
		return $string;
	}

	public function escapeHTML($string){
		$string = $this->link->real_escape_string($string);
		$string = preg_replace('/(?:<|&lt;)\/?([a-zA-Z]+) *[^<\/]*?(?:>|&gt;)/', '', $string);
		return $string;
	}
	
	function __destruct(){
		if($this->link){
			$this->link->close();
		}			
	}
	
	public function startTransactions(){
		$link= $this->link;
		if (!$link){
			throw new Exception("Database not initialized");
		}
		$link->autocommit(false);
	}
	public function commit(){
		$link= $this->link;
		if (!$link){
			throw new Exception("Database not initialized");
		}
		$link->commit();
	}
	public function rollBack(){
		$link= $this->link;
		if (!$link){
			throw new Exception("Database not initialized");
		}
		$link->rollback();
	}
	public function endTransactions(){
		$link= $this->link;
		if (!$link){
			throw new Exception("Database not initialized");
		}
		$link->autocommit(true);
	}
	
}
?>
