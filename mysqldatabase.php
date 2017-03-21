<?php

define('PREFIX',"");

class MySQLDatabase {
	private $_connection;
	private $prefix;
	private $last='';
    private $db = array();
       
	function __construct() {
            $this->db["host"] = "localhost";
            $this->db["user"] = "root";
            $this->db["password"] = "";
            $this->db["dbname"] = "zarodolgozat";
            $this->prefix = "";
            $this->_connection = mysql_connect($this->db["host"], $this->db["user"] , $this->db["password"]);
            mysql_select_db($this->db["dbname"], $this->_connection);
            mysql_query("SET NAMES utf8;");
	}
        
	function __destruct() {
		mysql_close($this->_connection);
	}
	
	function query($query_string,$vars=array()) {
		foreach($vars as $key => $value) { //végigmegy a kulcs és értékpárokon
			$query_string = str_replace('{'.$key.'}',mysql_real_escape_string($value),$query_string);//kiszedi a parancs karaktereket
		}
		$query_string=str_replace('{PREFIX}',$this->prefix,$query_string);
		$this->last = $query_string;
		return @mysql_query($query_string);
	}
	
	function num_rows($query_string,$vars) {
		$result = $this->query($query_string,$vars);
		return ($result) ? mysql_num_rows($result) : 0; //
	}
	
	function last_inserted_id() { //az adatbázisba utoljára beszúrt id-t adja vissza
		return mysql_insert_id($this->_connection);
	}
	
	function report() { //Visszatés a MySQL hiba számmal : MySQL hiba, hozzáfűzi az utolsó MySQL lekérdezést
		return "SQL report: " . mysql_errno($this->_connection).': '.mysql_error($this->_connection).'<br>'.$this->last;
	}
        
	function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
	 }
}

?>
