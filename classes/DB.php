<?php

class DB {

	private static $_instance = null;
	private $_pdo,
					$_query,
					$_error = false,
					$_results,
					$_count = 0,
					$_lastid = 0,
					$_prefix = '';

	private function __construct(){
		$this->_prefix = Config::get('mysql/prefix');
		try{
			$this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/dbname'),Config::get('mysql/user'),Config::get('mysql/pass'),array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
			//$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e){
			die($e->getMessage());
		}
	}

	public static function getInstance(){
		/*if(!isset(self::$_instance)){
		}*/
		self::$_instance = new DB();
		return self::$_instance;
	}

	public function query($sql, $params=array()){
		$this->_error = false;
		$query = preg_replace('/{(.*?)}/', $this->_prefix.'$1', $sql);
		if($this->_query = $this->_pdo->prepare($query)){
			if(count($params)){
				$nm = 1;
				foreach ($params as $param) {
					$this->_query->bindValue($nm,$param);
					$nm++;
				}
			}
			if($this->_query->execute()){

				//if(!preg_match('/(INSERT )\w/', $sql)){
				$this->_count = $this->_query->rowCount();
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				//}
				//}else{
				//$this->_lastid = $this->_pdo->lastInsertId();
				//}

			}else{
				$this->_error = true;
			}
		}
		return $this;
	}
	public function getquery(){
		return $this->_query;
	}
	public function columnexists($table='',$column=''){
		$this->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = ? AND COLUMN_NAME = ?",array($this->_prefix.$table,$column));
		if(!$this->count()) return false;
		return true;
	}


	/******* STANDARD QUERIES *********/

	public function action($action, $table, $where=array()){
		if(count($where)===3){
			$operators = array('=','>','<','>=','<=','LIKE','!=','NOT LIKE');

			$field 			= $where[0];
			$operator 	= $where[1];
			$value 			= $where[2];

			if(in_array($operator,$operators)){
				$sql = "{$action} FROM {{$table}} WHERE {$field} {$operator} ?";
				if(!$this->query($sql, array($value))->error()) return $this;
			}
		}else{
			$sql = "{$action} FROM {{$table}}";
			if(!$this->query($sql)->error()) return $this;
		}
		return false;
	}

	public function get($table, $where){
		return $this->action("SELECT *", $table, $where);
	}

	public function delete($table, $where){
		return $this->action("DELETE ", $table, $where);
	}

	public function update($table, $id, $fields=array()){
		$set = '';
		$nm = 1;
		foreach ($fields as $name=>$value) {
			$set .= "{$name} = ?";
			if($nm < count($fields)){
				$set .= ', ';
			}
			$nm++;
		}
		if(is_array($id)){
			$where = $id[0].$id[1].$id[2];
		}else{
			$where = "id={$id}";
		}
		$sql = "UPDATE {{$table}} SET {$set} WHERE {$where}";
		if(!$this->query($sql,$fields)->error()){
			return true;
		}
		return false;
	}

	public function insert($table,$fields=array()){
		$keys = array_keys($fields);
		$values = '';
		$nm = 1;
		foreach ($fields as $field) {
			$values .= '? ';
			if($nm < count($fields)){
				$values .= ', ';
			}
			$nm++;
		}
		$sql = "INSERT INTO {{$table}} (`".implode('`, `', $keys)."`) VALUES ({$values})";
		if(!$this->query($sql,$fields)->error()){
			$this->_lastid = $this->_pdo->lastInsertId();
			return true;
		}
		return false;
	}

	public function insertmultiple($table='',$arrcols=array(), $rows=array()){
		$values = '';
		$nmr = 1;
		$arrvalues = array();
		foreach($rows as $row){
			$values .= '(';
			$nm = 1;
			foreach($row as $field){
				$values .= '?';
				$arrvalues[] = $field;
				if($nm < count($row)){
					$values .= ',';
				}
				$nm++;
			}
			$values .= ')';
			if($nmr < count($rows)){
				$values .= ',';
			}
			if($nmr == count($rows)){
				if(count($rows) > 1){
					$values .= ';';
				}
			}
			$nmr++;
		}
		$sql = "INSERT INTO {{$table}} (`".implode('`,`', $arrcols)."`) VALUES {$values}";

		if(!$this->query($sql,$arrvalues)->error()){
			$this->_lastid = $this->_pdo->lastInsertId();
			return true;
		}
		return false;
	}

	public function getLastId(){
		return $this->_lastid;
	}

	public function results(){
		return $this->_results;
	}

	public function first(){
		return $this->results()[0];
	}

	public function count(){
		return $this->_count;
	}

	public function error(){
		return $this->_error;
	}
}
