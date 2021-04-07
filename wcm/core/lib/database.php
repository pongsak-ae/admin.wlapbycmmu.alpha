<?php


Class OMPDO extends PDO{

		private $_host = "127.0.0.1";
		private $_port = "3306";
		private $_database;
		private $_username = "";
		private $_password = "";
		private $_type = "mysql";

		private $_DB;
		private $pdo;

		function __construct($ConnectionString = null) {
			if($ConnectionString == null && $ConnectionString == ""){
				$ConnectionString = WCMSetting::$DEFAULT_DATABASE_CONNECTION_STRING;
			}
			$connect = explode("::",$ConnectionString);
			$hostInfo = explode(":",$connect[0]);
			$this->_host = $hostInfo[0];
			if(isset($hostInfo[1])) $this->_port = $hostInfo[1];
			$this->_database = $connect[1];
			$this->_username = $connect[2];
			$this->_password = trim($connect[3]);
			if($this->_password != ""){
				$decrypt = new OMCrypto(WCMSetting::$ENCRYPT_INIT_KEY, WCMSetting::$ENCRYPT_INIT_VECTOR);
				$this->_password = $decrypt->Decrypt($this->_password);
			}

			$this->_type = $connect[4];
			$this->_attributes = array(
	         	PDO::ATTR_PERSISTENT => true,
	         	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	         	PDO::ATTR_EMULATE_PREPARES => true,
	         	PDO::ATTR_ORACLE_NULLS => PDO::NULL_TO_STRING,
	         	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			);
			if($this->_type == "mysql" || $this->_type == "mssql" || $this->_type == "sybase"  || $this->_type == "sqlsrv"){
				$dsn = $this->_type.":dbname=".$this->_database.";host=".$this->_host.";port=".$this->_port ;
				if($this->_type == "sqlsrv"){
					$dsn = $this->_type.":Database=".$this->_database.";Server=".$this->_host;
				}
				if($this->_type == "sqlsrv" || $this->_type == "mysql" ){
					$this->addAttribute("MYSQL_ATTR_INIT_COMMAND" , "SET NAMES 'UTF8'");
					$this->addAttribute("MYSQL_ATTR_MAX_BUFFER_SIZE" , 16777216);
					$this->addAttribute("MYSQL_ATTR_USE_BUFFERED_QUERY" , true);
				}

			}else if($this->_type == "dblib") {
				$dsn = $this->_type.":dbname=".$this->_database.";host=".$this->_host;
			}else{
		  		$dsn = $this->_type;
			}

			try{
			    parent::__construct( $dsn, $this->_username, $this->_password,$this->_attributes) ;
			}catch(PDOException $e) {
			    echo $e->getMessage();
			    exit();
			}
		}
		public function addAttribute($key,$val) {
			if( defined( "PDO::$key" ) ) { // note the quotes
				$this->_attributes[ (constant("PDO::$key")) ] = $val;
			}
		}
	}
class OMDatabase
{
    private $_HostName;
    private $_DatabaseName;
    private $_UserName;
    private $_Password;

    public $_DB;

    private $_LastErrorMessage;
	private $_SQLQueryString;

	private $_RowCount = 0;
    // Constructor

    function __construct($ConnectionString = null) {
		if ($ConnectionString == null || $ConnectionString == "") {
			$ConnectionString = WCMSetting::$DEFAULT_DATABASE_CONNECTION_STRING;
		}

        try{
    		$this->_DB = new OMPDO($ConnectionString);
        } catch( PDOException $Exception ) {
		    $this->_LastErrorMessage = $Exception->getMessage( );
		}
    }

    // Properties Handle
    function __get($Name) {
        switch ($Name) {
            case 'LastErrorMessage':
                return $this->_LastErrorMessage;
                break;
			case 'LastSQLQueryString':
				return $this->_SQLQueryString;
				break;
			case 'RowCount':
				return $this->_RowCount;
				break;
        }
        user_error("Invalid property: " . __CLASS__ . "->" . $Name);
    }
    function __set($Name, $Value) {
        user_error("Can't set property: " . __CLASS__ . "->" . $Name);
    }
	function begin() {
	}
	function commit() {
	}
	function rollBack() {
	}
	function close() {
		if ($this->_DB != null) {
			$this->_DB = null;
		}
	}
    function param2PDO($ParamList,$sql){
		$sortedParamList = array();
        if (! empty($ParamList) ){
            foreach ($ParamList as $k => $v) {
            	$key = str_replace("@", "", $k);
            	if( strpos($sql, ":".$key) !== FALSE){
            		$sortedParamList[$key] = $v;
            	}

            }
        }
    	return $sortedParamList;
    }
    public function pdo_query($sql , $param , $skipBC = false) {
    	if(!$skipBC){
    		$this->_SQLQueryString = str_replace("@", ":",$sql) ;
    	}else{
    		$this->_SQLQueryString = $sql ;
    	}
    	$stmt = false;
    	try {
	    	$stmt = $this->_DB->prepare ( $this->_SQLQueryString , array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$param_exe =  $this->param2PDO($param , $this->_SQLQueryString) ;
			$stmt -> execute ($param_exe);
    	} catch (PDOException $e) {
    		throw $e;
	    	return false;
    	}
    	return $stmt;
    }
    function query(&$SqlDataTable, $SqlString, $ParamList = array(), $StartRecord=0, $MaxRecord=-1, $mode = 'ARRAY',$skipBC = false) {
        try{
	        $stmt = $this->pdo_query( $SqlString , $ParamList , $skipBC);
			$this->_RowCount = $stmt->rowCount();
			if($MaxRecord > 0){
		        $stmt = $this->pdo_query( $SqlString . " LIMIT ${StartRecord},${MaxRecord}" , $ParamList,$skipBC);
			}
			$PDO_MODE = PDO::FETCH_ASSOC;
	        if($mode == 'ARRAY'){
					$PDO_MODE = PDO::FETCH_BOTH;
	        }
			$SqlDataTable = $stmt->fetchAll($PDO_MODE);
			$stmt -> closeCursor ( ) ;
		} catch(PDOException $e){
            $this->_LastErrorMessage = $e->getMessage();
            return -1;
        }
		return count($SqlDataTable);
    }

    ///<summary>
    /// Execute SQL string with out retieve data
    ///</sumary>
    function execute($SqlString, $ParamList = null) {
        try{
           	$stmt = $this->pdo_query( $SqlString , $ParamList);
    		$this->_RowCount = $stmt->rowCount();
            return $this->_RowCount;
        } catch(PDOException $e){
            $this->_LastErrorMessage = $e->getMessage();
            return -1;
        }
    }

	function executeInsert($TableName, $ParamList = null, &$newid = null) {
		$xid = 0;
		$sql_f = "";
		$sql_v = "";
        if ($ParamList != null) {
			$sortedParamList = $ParamList; krsort($sortedParamList);
            foreach ($sortedParamList as $k => $v) {
				if ($sql_f != "") {
					$sql_f .= ",";
					$sql_v .= ",";
				}
				$sql_f .= $k;
				$sql_v .= (":".$k);
            }
        }
		$this->_SQLQueryString = "INSERT INTO " . $TableName . "(" . $sql_f . ")" . " VALUES(" . $sql_v . ")";

		    $stmt = $this->_DB->prepare($this->_SQLQueryString);

		    try {
		        $this->_DB->beginTransaction();
		        $stmt->execute($this->param2PDO($ParamList,$this->_SQLQueryString) );
		        $xid = $this->_DB->lastInsertId();
		        $this->_DB->commit();
		    } catch(PDOExecption $e) {
		        $this->_DB->rollback();
		        $this->_LastErrorMessage = $e->getMessage();
	            return -1;
		    }
		if (isset($newid)) $newid = $xid;
        return $stmt->rowCount();
    }
	function executeUpdate($TableName, $KeyCount, $ParamList = null) {
		$sql_update = "";
		$sql_condition = "";
		$i = 0;
        if ($ParamList != null) {

            foreach ($ParamList as $k => $v) {
				if ($i<$KeyCount) {
					if ($sql_condition == "") {
						$sql_condition .= " WHERE ";
					} else {
						$sql_condition .= " AND ";
					}
					$sql_condition .= " " . $k  . " = " . $this->_DB->quote($v) ." " ;
				} else {
					if ($sql_update == "") {
						$sql_update .= " SET ";
					} else {
						$sql_update .= ", ";
					}
					$sql_update .= " " . $k  . " = " . $this->_DB->quote($v) ." " ;
				}
				$i++;
            }
        }
		$this->_SQLQueryString = "UPDATE " . $TableName . " " . $sql_update . " " . $sql_condition;

		$stmt = $this->_DB->prepare($this->_SQLQueryString);

		    try {
		        $this->_DB->beginTransaction();
		        $stmt->execute($this->param2PDO($ParamList,$this->_SQLQueryString) );
		        $this->_DB->commit();

		    } catch(PDOExecption $e) {
		        $this->_DB->rollback();
		        $this->_LastErrorMessage = $e->getMessage();
	            return -1;
		    }
		return $stmt->rowCount();
    }
    function getRunningNumber($RunningNumberName) {
        return $this->generateUUID();
    }

   	function generateUUID() {
        $date = new DateTime();
        return (($date->format('U') * 1000) + mt_rand(0,999));
    }

	//TODO: Implement all type
	function getString($DT, $RowIndex, $FieldName, $DefaultValue="") {
		if (isset($DT[$RowIndex][$FieldName])) {
			return $DT[$RowIndex][$FieldName];
		} else {
			return $DefaultValue;
		}
    }

    function getDateTime($DT, $RowIndex, $FieldName, $DefaultValue=null) {
		if ($DefaultValue == null) {
			$DefaultValue = OMDateTime::Now();
		}
		return new OMDateTime($this->getString($DT, $RowIndex, $FieldName, $DefaultValue));
    }

    function getInt($DT, $RowIndex, $FieldName, $DefaultValue=0) {
        return $this->getString($DT, $RowIndex, $FieldName, $DefaultValue);
    }

    function getInt32($DT, $RowIndex, $FieldName, $DefaultValue=0) {
		return $this->getString($DT, $RowIndex, $FieldName, $DefaultValue);
    }
    function getLong($DT, $RowIndex, $FieldName, $DefaultValue=0) {
        return $this->getString($DT, $RowIndex, $FieldName, $DefaultValue);
    }
    function getInt64($DT, $RowIndex, $FieldName, $DefaultValue=0) {
        return $this->getString($DT, $RowIndex, $FieldName, $DefaultValue);
    }

    function getFloat($DT, $RowIndex, $FieldName, $DefaultValue=0) {
        return $this->getString($DT, $RowIndex, $FieldName, $DefaultValue);
    }

    function getDecimal($DT, $RowIndex, $FieldName, $DefaultValue=0) {
		return $this->getString($DT, $RowIndex, $FieldName, $DefaultValue);
    }

}

?>