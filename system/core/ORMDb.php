<?php
namespace OMCore;

use NotORM;
use WCMSetting;

class ORMDb extends NotORM
{


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

    // Method
	function begin() {
	}
	function commit() {
	}
	function rollBack() {
	}
	function close() {
		if ($this->_DB != null) {
			mysql_close($this->_DB);
			$this->_DB = null;
		}
	}
    function db_escape_string($s /*String*/){

		if (is_object($s) && method_exists ($s, "toDbString")) {
			$ss = $s->toDbString();
		} else {
			$ss = $s;
		}
        if (@get_magic_quotes_gpc()) {
            return mysql_real_escape_string(stripslashes($ss));
        } else {
            return mysql_real_escape_string($ss);
        }
    }
    function query(&$SqlDataTable, $SqlString, $ParamList = null, $StartRecord=0, $MaxRecord=-1, $mode = 'ARRAY') {

        if ($this->_DB == null) {

			unset($SqlDataTable);
        echo     $this->_LastErrorMessage = "DB connection error";
            return -1;
        }
		$SqlString = $this->generateSql($SqlString, $ParamList) ;
        $this->_SQLQueryString = $SqlString;
        $result = mysql_query($SqlString, $this->_DB);
        if ($result == false) {
            $this->_LastErrorMessage = mysql_error($this->_DB);
            return -1;
        }
        $numrows = mysql_affected_rows($this->_DB);
		//TODO: Optimizing
		$this->_RowCount = $numrows;
		$rrows = 0;
		$SqlDataTable = array();
		$seek_row = $StartRecord;
		if ($seek_row <0) {
			$seek_row = 0;
		}
		if ($seek_row > 0) {
			mysql_data_seek($result, $seek_row);
		}
        for($i = $seek_row; ($i < $numrows) && ($MaxRecord == -1 || $rrows < $MaxRecord )  ; $i ++){
			$data = $mode=='ARRAY' ? @mysql_fetch_array($result) : @mysql_fetch_assoc($result);
			if ($i >= $StartRecord && ($MaxRecord == -1 || $rrows < $MaxRecord )) {
				$SqlDataTable[count($SqlDataTable)] = $data;
				$rrows++;
			}
		}
		return $rrows;
    }

    function queryResult(&$SqlDataTable, $SqlString, $ParamList = null) {

        if ($this->_DB == null) {
			unset($SqlDataTable);
            $this->_LastErrorMessage = "";
            return -1;
        }
		$SqlString = $this->generateSql($SqlString, $ParamList) ;
        $this->_SQLQueryString = $SqlString;
        $result = mysql_query($SqlString, $this->_DB);
        if ($result == false) {
            $this->_LastErrorMessage = mysql_error($this->_DB);
            return -1;
        }
        $numrows = mysql_affected_rows($this->_DB);
		//TODO: Optimizing
		$this->_RowCount = $numrows;
		$rrows = $numrows;
		$SqlDataTable = $result;
		return $rrows;
    }

    ///<summary>
    /// Execute SQL string with out retieve data
    ///</sumary>
    function execute($SqlString, $ParamList = null) {
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
		$this->_SQLQueryString = $SqlString;
        $result = mysql_query($SqlString, $this->_DB);
        if ($result == false) {
            $this->_LastErrorMessage = mysql_error($this->_DB);
            return -1;
        }
        $numrows = mysql_affected_rows($this->_DB);
        return $numrows;
    }
	///<summary>
    /// Generate SQL string
    ///</sumary>
    function generateSql($SqlString, $ParamList = null) {
        if ($ParamList != null) {
			$sortedParamList = $ParamList; krsort($sortedParamList);
            foreach ($sortedParamList as $k => $v) {
				if(is_int($v) || is_float($v)){
					if (substr($k, 0, 1) != '@') {
						$SqlString = str_replace('@' . $k, $this->db_escape_string ($v),$SqlString);
					} else {
						$SqlString = str_replace($k,$this->db_escape_string ($v),$SqlString);
					}
				}else{
					if (substr($k, 0, 1) != '@') {
						$SqlString = str_replace('@' . $k,'\'' . $this->db_escape_string ($v) . '\'',$SqlString);
					} else {
						$SqlString = str_replace($k,'\'' . $this->db_escape_string ($v) . '\'',$SqlString);
					}
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
				$sql_v .= "'" . $this->db_escape_string ($v) . "'";
            }
        }
		$SqlString = "INSERT INTO " . $TableName . "(" . $sql_f . ")" . " VALUES(" . $sql_v . ")";
		$this->_SQLQueryString = $SqlString;
        $result = mysql_query($SqlString, $this->_DB);
        if ($result == false) {
            $this->_LastErrorMessage = mysql_error($this->_DB);
            return -1;
        }
		$xid = mysql_insert_id($this->_DB);
		if (isset($newid)) $newid = $xid;
        $numrows = mysql_affected_rows($this->_DB);
        return $numrows;
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
					$sql_condition .= " " . $k  . " = '" . $this->db_escape_string ($v) . "'";
				} else {
					if ($sql_update == "") {
						$sql_update .= " SET ";
					} else {
						$sql_update .= ", ";
					}
					$sql_update .= " " . $k  . " = '" . $this->db_escape_string ($v) . "'";
				}
				$i++;
            }
        }
		$SqlString = "UPDATE " . $TableName . " " . $sql_update . " " . $sql_condition;
		$this->_SQLQueryString = $SqlString;
        $result = mysql_query($SqlString, $this->_DB);
        if ($result == false) {
            $this->_LastErrorMessage = mysql_error($this->_DB);
            return -1;
        }
        $numrows = mysql_affected_rows($this->_DB);
        return $numrows;
    }

    function getRunningNumber($RunningNumberName) {
		$dt = null;
		mysql_query("LOCK TABLES wcm_running_number WRITE;", $this->_DB);
		$sql  = "select * from wcm_running_number where running_name = @running_name";
		$r = $this->query($dt, $sql, array("@running_name"=>$RunningNumberName));
		$current_value   = 1;
		$current_code = "";
		if ($r > 0)  {
			$current_value = $dt[0]["current_value"];
			$current_value += 1;
			$sql = "update wcm_running_number set current_value = @current_value where running_name = @running_name;";
			$r = $this->execute($sql, array("@running_name"=>$RunningNumberName, "@current_value"=>$current_value));
		} else {
			$sql = "insert into wcm_running_number(running_name, current_value) values(@running_name,@current_value);";
			$r = $this->execute($sql, array("@running_name"=>$RunningNumberName, "@current_value"=>$current_value));
		}

		mysql_query("UNLOCK TABLES;", $this->_DB);

        return $current_value;
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