<?php
namespace OMCore;

use PDO;
use WCMSetting;

Class OMPDO extends PDO {

	private $_host;
	private $_database;
	private $_username;
	private $_password;
	private $_type;
	private $_DB;

	function __construct($ConnectionString = null) {
		if ($ConnectionString == null && $ConnectionString == "") {
			$ConnectionString = WCMSetting::$DEFAULT_DATABASE_CONNECTION_STRING;
		}
		$connect = explode("::", $ConnectionString);
		$this->_host = $connect[0];
		$this->_database = $connect[1];
		$this->_username = $connect[2];
		$this->_password = trim($connect[3]);
		$decrypt = new OMCrypto();
// $codw = $decrypt->Encrypt("x6Uddhkgr]@sql99");
// var_dump($codw );
// var_dump($decrypt->Decrypt($codw));
// exit();
		if ($this->_password != "") {
			$decrypt = new OMCrypto(WCMSetting::$ENCRYPT_INIT_KEY, WCMSetting::$ENCRYPT_INIT_VECTOR);
			$this->_password = $decrypt->Decrypt($this->_password);
		}
		$this->_type = $connect[4];

		$options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
			PDO::MYSQL_ATTR_FOUND_ROWS   => TRUE
		);
		// $attributes = array(
		//                     // "ATTR_ERRMODE"=>"ERRMODE_EXCEPTION"
		//                     // "ATTR_EMULATE_PREPARES"=>false,
		//                     // "ATTR_ORACLE_NULLS"=>"NULL_TO_STRING"
		//                     );

		if ($this->_type == "mysql" || $this->_type == "mssql" || $this->_type == "sybase" || $this->_type == "sqlsrv") {
			$dsn = $this->_type . ":dbname=" . $this->_database . ";host=" . $this->_host;
			if ($this->_type == "sqlsrv") {
				$dsn = $this->_type . ":server=" . $this->_host ." ; "."Database=" . $this->_database ;
			}
		} else if ($this->_type == "dblib") {
			$dsn = $this->_type . ":dbname=" . $this->_database . ";host=" . $this->_host;
			$options = array();
		} else {
			$dsn = $this->_type;
		}

		try {
			parent::__construct($dsn, $this->_username, $this->_password, $options);
		} catch (PDOException $e) {
			echo $e->getMessage();
			exit();
		}
	}

}
?>