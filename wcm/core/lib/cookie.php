<?php
class OMSecureCookie {
	private $_Cryptor = null;
	function __construct($Cryptor) {
		$this->_Cryptor = $Cryptor;
	}
	function getValue($CookieName) {
		return OMSecureCookie::getSecureCookie($CookieName, $this->_Cryptor);
	}
	static function getSecureCookie($CookieName, $Cryptor) {

		if (isset($_COOKIE[$CookieName])) {
			return $Cryptor->Decrypt($_COOKIE[$CookieName]);
		} else {
			return "";
		}
	}

	function setValue($CookieName, $Value, $ExpiredSecond=31536000) {
		OMSecureCookie::setSecureCookie($CookieName, $Value, $this->_Cryptor, $ExpiredSecond);
	}
	static function setSecureCookie($CookieName, $Value, $Cryptor, $ExpiredSecond=31536000, $EncodingMode="ANSI") {
		$value = $Cryptor->Encrypt($Value);
		//TODO: must be WCM absoulte path  not root path
		setcookie($CookieName, $value, time()+$ExpiredSecond , '/');
	}
}
class OMCookieDB {
    private $_UserId = 0;
    private $_DB;
    private $_TableName = "wcm_user_cookie";
    private $_ExpiredSecond = 31536000;
    private $_CookieCache = array();
    private $_CookieCacheByPrefix = array();

	function __construct($DB, $UserId) {
		$this->_DB = $DB;
		$this->_UserId = $UserId;
	}
	//TODO: Caching
	function getCookie($CookieGroup, $CookieName) {
		$sql = "SELECT cookie_name, cookie_value FROM " . $this->_TableName . " WHERE user_id = @userid AND cookie_group = @cookie_group AND cookie_name = @cookie_name AND expired_date > @expireddate order by cookie_name";
		$r = $this->_DB->query($dt, $sql, array("@userid"=>$this->_UserId, "@cookie_group"=>$CookieGroup, "@cookie_name"=>$CookieName, "@expireddate"=>OMDateTime::Now()));
		$this->_CookieCache = array();
		if ($r > 0 && $dt != null) {
			return $dt[0]["cookie_value"];
		}
		return "";
	}

	function getCookies($CookieGroup, &$ReturnCookieList = null) {
		$sql = "SELECT cookie_name, cookie_value FROM " . $this->_TableName . " WHERE user_id = @userid AND cookie_group = @cookie_group AND expired_date > @expireddate order by cookie_name";
		$r = $this->_DB->query($dt, $sql, array("@userid"=>$this->_UserId, "@cookie_group"=>$CookieGroup, "@expireddate"=>OMDateTime::Now()));
		if ($ReturnCookieList == null) {
			$rr = array();
		} else {
			$rr = $ReturnCookieList;
		}

		$this->_CookieCache = array();
		foreach ($dt as $row) {
			$rr[$row['cookie_name']] = $row['cookie_value'];
		}
		return $rr;
	}
	function getCookieByPrefix($CookieGroup, $CookieName, &$ReturnCookieList = null) {
		if ($ReturnCookieList == null) {
			$rr = array();
		} else {
			$rr = $ReturnCookieList;
		}
		$CookieName = str_replace('%', '', $CookieName);
        $CookieName .= '%';
		$sql = "SELECT cookie_name, cookie_value FROM " . $this->_TableName . " WHERE user_id = @userid AND cookie_group = @cookie_group AND cookie_name like @cookie_name AND expired_date > @expireddate order by cookie_name";
		$r = $this->_DB->query($dt, $sql, array("@userid"=>$this->_UserId, "@cookie_group"=>$CookieGroup, "@cookie_name"=>$CookieName, "@expireddate"=>OMDateTime::Now()));
		$this->_CookieCache = array();
		foreach ($dt as $row) {
			$rr[$row['cookie_name']] = $row['cookie_value'];
		}
		return $rr;
    }
	function setCookie($CookieGroup, $CookieName, $CookieValue, $ExpiredSecond = 31536000) {
		$p = array();
		if ( $ExpiredSecond == 31536000) {
			$ExpiredSecond = $this->_ExpiredSecond;
		}
		$sql = "SELECT * FROM " . $this->_TableName . " WHERE user_id = @userid AND cookie_group = @cookie_group and cookie_name = @key";
		$r = $this->_DB->query($dt, $sql, array("@userid"=>$this->_UserId, "@cookie_group"=>$CookieGroup, "@key"=>$CookieName));
		if ($r>0) {
			$p["user_id"] = $this->_UserId;
            $p["cookie_group"] = $CookieGroup;
            $p["cookie_name"] = $CookieName;
            $p["cookie_value"] = $CookieValue;
            $p["expired_date"] = (OMDateTime::Now()->AddSeconds($ExpiredSecond));
            $p["last_updated"] = OMDateTime::Now();
			$r=$this->_DB->executeUpdate($this->_TableName, 3, $p);
			if ($r < 1) {
                return false;
            }
		} else {
            $p["user_id"] = $this->_UserId;
            $p["cookie_group"] = $CookieGroup;
            $p["cookie_name"] = $CookieName;
            $p["cookie_value"] = $CookieValue;
            $p["expired_date"] = OMDateTime::Now()->AddSeconds($ExpiredSecond);
            $p["last_updated"] = OMDateTime::Now();
            $r = $this->_DB->executeInsert($this->_TableName, $p);
            if ($r < 1) {
                return false;
            }
		}
		return true;
	}
	function setCookies($CookieGroup, $CookieList, $ExpiredSecond = 31536000) {
		$sql="";
        $r=0;
        $p = array();
		//TODO: Caching
		/*
        if (_CookieCache[CookieGroup] != null) {
            ((OMKeyArray)_CookieCache[CookieGroup]).Clear();
            _CookieCache.getKeyValue(CookieGroup).Tag = null;
        }
        if (_CookieCacheByPrefix[CookieGroup] != null) {
            ((OMKeyArray)_CookieCacheByPrefix[CookieGroup]).Clear();
            _CookieCacheByPrefix.getKeyValue(CookieGroup).Tag = null;
        }
		*/
        foreach ($CookieList as $kv_key => $kv_value) {
			$dt = null;
            $sql = "SELECT * FROM " . $this->_TableName . " WHERE user_id = @userid AND cookie_group = @groupname and cookie_name = @key";
            $r = $this->_DB->query($dt, $sql, array("@userid"=>$this->_UserId, "@groupname"=>$CookieGroup, "@key"=>$kv_key));
            $p = array();
            if ($r > 0) {
                $p["user_id"] = $this->_UserId;
                $p["cookie_group"] = $CookieGroup;
                $p["cookie_name"] = $kv_key;
                if ($kv_value != null) {
                    $p["cookie_value"] = $kv_value;
                } else {
                    $p["cookie_value"] = "";
                }
                $p["expired_date"] = OMDateTime::Now()->AddSeconds($ExpiredSecond);
                $p["last_updated"] = OMDateTime::Now();
                $r = $this->_DB->executeUpdate($this->_TableName, 3, $p);
            } else {
                $p["user_id"] = $this->_UserId;
                $p["cookie_group"] = $CookieGroup;
                $p["cookie_name"] = $kv_key;
                if ($kv_value != null) {
                    $p["cookie_value"] = $kv_value;
                } else {
                    $p["cookie_value"] = "";
                }
                $p["expired_date"] = OMDateTime::Now()->AddSeconds($ExpiredSecond);
                $p["last_updated"] = OMDateTime::Now();
                $r = $this->_DB->executeInsert($this->_TableName, $p);
            }
        }
        return true;
	}
	function delete($CookieGroup, $CookieName) {
        $sql="";
        $iret=0;
		//TODO: Caching
		/*
        if (_CookieCache[CookieGroup] != null) {
            ((OMKeyArray)_CookieCache[CookieGroup]).Clear();
            _CookieCache.getKeyValue(CookieGroup).Tag = null;
        }
        if (_CookieCacheByPrefix[CookieGroup] != null) {
            ((OMKeyArray)_CookieCacheByPrefix[CookieGroup]).Clear();
            _CookieCacheByPrefix.getKeyValue(CookieGroup).Tag = null;
        }
		*/
        $sql = "DELETE FROM " . $this->_TableName . " WHERE user_id = @userid AND cookie_group = @CookieGroup AND cookie_name = @CookieName ";
        $iret = $this->_DB->execute($sql, array("@userid"=>$this->_UserId, "@CookieGroup"=>$CookieGroup, "@CookieName"=>$CookieName));
        return $iret;
    }
	function deleteByPrefix($CookieGroup, $CookieName) {
        $sql = "";
        $iret = 0;
        $CookieName = str_replace("%", "", $CookieName);
        $CookieName .= '%';
		//TODO: Caching
		/*
        if (_CookieCache[CookieGroup] != null) {
            ((OMKeyArray)_CookieCache[CookieGroup]).Clear();
            _CookieCache.getKeyValue(CookieGroup).Tag = null;
        }
        if (_CookieCacheByPrefix[CookieGroup, CookieName] != null) {
            ((OMKeyArray)_CookieCacheByPrefix[CookieGroup, CookieName]).Clear();
            _CookieCacheByPrefix.getKeyValue(CookieGroup, CookieName).Tag = null;
        }
		*/
        $sql = "DELETE FROM " . $this->_TableName . " WHERE user_id = @userid AND cookie_group = @CookieGroup AND cookie_name like @CookieName ";
        $iret = $this->_DB->execute($sql, array("@userid"=>$this->_UserId, "@CookieGroup"=>$CookieGroup, "@CookieName"=>$CookieName));
        return $iret;
    }
}
?>