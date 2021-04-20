<?php
namespace OMCore;

use NotORM;
use PDO;
use WCMSetting;
use DateTime;

class OMDb {

	private static $OMDb = null;
	public static $_SQLQueryString = null;
	public static $_LastErrorMessage = null;

	public static function singleton($CONNECTION_STRING = null){
		if($CONNECTION_STRING == null){
			if ( self::$OMDb == null ){
				self::$OMDb = new OMDatabase();
			}
		}else{
		  	self::$OMDb = new OMDatabase($CONNECTION_STRING);
		}

		return self::$OMDb;
	}

	public static function table($tb_name){
		$DB = self::singleton();
		return 	$DB->$tb_name();
	}

	public static function bindArrayValue($req, $array, $typeArray = false)
		{
		    if(is_object($req))
		    {
		        foreach($array as $key => $value)
		        {
		            if($typeArray)
		                $req->bindValue(":$key",$value,$typeArray[$key]);
		            else
		            {
		                if(is_int($value))
		                    $param = PDO::PARAM_INT;
		                elseif(is_bool($value))
		                    $param = PDO::PARAM_BOOL;
		                elseif(is_null($value))
		                    $param = PDO::PARAM_NULL;
		                elseif(is_file($value)){
		                	$value = fopen($value, 'rb');
		                    $param = PDO::PARAM_LOB;
		                }
		                elseif(is_string($value))
		                    $param = PDO::PARAM_STR;
		                else
		                    $param = FALSE;

		                if($param)
		                    $req->bindValue(":$key",$value,$param);
		            }
		        }
		    }
		}


}

class OMDatabase extends NotORM
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
		//$decrypt = new OMCrypto(WCMSetting::$ENCRYPT_INIT_KEY, WCMSetting::$ENCRYPT_INIT_VECTOR);
		//var_dump($decrypt->Encrypt("qO$s5k64"));
		//exit();
        try{
    		$this->_DB = new OMPDO($ConnectionString);
    		parent::__construct($this->_DB);
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
    function db_escape_string($s /*String*/){

		if (is_object($s) && method_exists ($s, "toDbString")) {
			$ss = $s->toDbString();
		} else {
			$ss = $s;
		}
        if (get_magic_quotes_gpc()) {
            return mysql_real_escape_string(stripslashes($ss));
        } else {
            return mysql_real_escape_string($ss);
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
    	$stmt = $this->_DB->prepare ( $this->_SQLQueryString , array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		$stmt -> execute ( $this->param2PDO($param , $this->_SQLQueryString) );
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

    function executeQuary($SqlString, $ParamList = null) {
       	$stmt = $this->pdo_query( $SqlString , $ParamList);
		$this->_RowCount = $stmt->fetchAll();
        return $this->_RowCount;
    }
    function execute_spf($SqlString, $ParamList = null) {
       	$stmt = $this->pdo_query( $SqlString , $ParamList);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function execute($SqlString, $ParamList = null) {
       	$stmt = $this->pdo_query( $SqlString , $ParamList);
		$this->_RowCount = $stmt->rowCount();
        return $this->_RowCount;
    }

    function generateSql($SqlString, $ParamList = null) {
        if ($ParamList != null) {
			$sortedParamList = $ParamList; krsort($sortedParamList);
            foreach ($sortedParamList as $k => $v) {
                if (substr($k, 0, 1) != '@') {
					$SqlString = str_replace('@' . $k,'\'' . $this->db_escape_string ($v) . '\'',$SqlString);
				} else {
					$SqlString = str_replace($k,'\'' . $this->db_escape_string ($v) . '\'',$SqlString);
				}
            }
        }

		return $SqlString;
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
		$this->_SQLQueryString = "INSERT IGNORE INTO " . $TableName . "(" . $sql_f . ")" . " VALUES(" . $sql_v . ")";

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
					$sql_condition .= " " . $k  . " = '" . $v ."'" ;
				} else {
					if ($sql_update == "") {
						$sql_update .= " SET ";
					} else {
						$sql_update .= ", ";
					}
					$v1 = (explode("::",$v));
					// var_dump("------".count($v1));
     //      		 	exit();
					if (count($v1) == 1){
						$sql_update .= " " . $k  . " = '" . $v ."'" ;
					}else{
						$sql_update .= " " . $k  . " = " . $k."".$v1[0]."'".$v1[1] ."'" ;
					}
					
				}
				$i++;
            }
        }
        // var_dump("UPDATE " . $TableName . " " . $sql_update . " " . $sql_condition);
        // exit();
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