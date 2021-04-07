<?php
namespace OMCore;

class OMLdap {
	private $_conn = null;
	private $_server = "";
	private $_base_dn = "";
	private $_account_suffix = "";
	public function __construct($server, $base_dn, $account_suffix="") {
		$this->_server = $server;
		$this->_base_dn = $base_dn;
		$this->_account_suffix = $account_suffix;
	}
	public function connect() {
		$this->_conn = ldap_connect($this->_server);
        ldap_set_option($this->_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->_conn, LDAP_OPT_REFERRALS, 0);
	}
	public function close() {
		if ($this->_conn) {
			ldap_close($this->_conn);
		}
    }

	public function get_last_error() {
        return @ldap_error($this->_conn);
    }

    public function get_last_errno() {
        return @ldap_errno($this->_conn);
    }

	function __destruct(){
		$this->close();
	}

	public function authenticate($username,$password){
        if ($username===NULL || $password===NULL){ return (false); }
        if (empty($username) || empty($password)){ return (false); }

        $this->_bind = @ldap_bind($this->_conn, $username . $this->_account_suffix, $password);

        if (!$this->_bind){
			return (false);
		}

        return (true);
    }
	public function getUserInfo($username,$fields = null) {
		if ($username===NULL){
			return (false);
		}
        if (!$this->_bind){
			return (false);
		}
		$filter="samaccountname=" . $username;
		if ($fields===NULL){ $fields=array("samaccountname","mail","memberof","department","displayname","telephonenumber","primarygroupid","objectsid"); }
		$sr=ldap_search($this->_conn, $this->_base_dn, $filter,$fields);
		$entries = ldap_get_entries($this->_conn, $sr);

        if ($entries[0]['count'] >= 1) {
            // AD does not return the primary group in the ldap query, we may need to fudge it
            if (@$this->_real_primarygroup && isset($entries[0]["primarygroupid"][0]) && isset($entries[0]["objectsid"][0])){
                //$entries[0]["memberof"][]=$this->group_cn($entries[0]["primarygroupid"][0]);
                $entries[0]["memberof"][]=$this->get_primary_group($entries[0]["primarygroupid"][0], $entries[0]["objectsid"][0]);
            } else {
                $entries[0]["memberof"][]="CN=Domain Users,CN=Users,".$this->_base_dn;
            }
        }

        @$entries[0]["memberof"]["count"]++;
        return ($entries);
	}


	public function ms_error_code($ldap_error_code,&$error){
		// reference: http://msdn.microsoft.com/en-us/library/ms677840(v=VS.85).aspx
		// reference: http://msdn.microsoft.com/en-us/library/ms680832(v=VS.85).aspx

		$ldap_error[] = array(0x00000001,"The logon script is executed",0);
		$ldap_error[] = array(0x00000002,"The user account is disabled",1);
		$ldap_error[] = array(0x00000008,"The home directory is required",1);
		$ldap_error[] = array(0x00000010,"The account is currently locked out",1);
		$ldap_error[] = array(0x00000020,"No password is required",1);
		$ldap_error[] = array(0x00000040,"The user cannot change the password",0);
		$ldap_error[] = array(0x00000080,"The user can send an encrypted password",0);
		$ldap_error[] = array(0x00000100,"This is an account for users whose primary account is in another domain. This account provides user access to this domain, but not to any domain that trusts this domain. Also known as a local user account",0);
		$ldap_error[] = array(0x00000200,"This is a default account type that represents a typical user",0);
		$ldap_error[] = array(0x00000800,"This is a permit to trust account for a system domain that trusts other domains",0);
		$ldap_error[] = array(0x00001000,"This is a computer account for a computer that is a member of this domain",0);
		$ldap_error[] = array(0x00002000,"This is a computer account for a system backup domain controller that is a member of this domain",0);
		$ldap_error[] = array(0x00010000,"The password for this account will never expire",0);
		$ldap_error[] = array(0x00020000,"This is an MNS logon account",0);
		$ldap_error[] = array(0x00040000,"The user must log on using a smart card",1);
		$ldap_error[] = array(0x00080000,"The service account (user or computer account), under which a service runs, is trusted for Kerberos delegation. Any such service can impersonate a client requesting the service",0);
		$ldap_error[] = array(0x00100000,"The security context of the user will not be delegated to a service even if the service account is set as trusted for Kerberos delegation",0);
		$ldap_error[] = array(0x00200000,"Restrict this principal to use only Data Encryption Standard (DES) encryption types for keys",0);
		$ldap_error[] = array(0x00400000,"This account does not require Kerberos pre-authentication for logon",0);
		$ldap_error[] = array(0x00800000,"The user password has expired",1);
		$ldap_error[] = array(0x01000000,"The account is enabled for delegation",0);
		$ldap_error[] = array(0x04000000,"UF_PARTIAL_SECRETS_ACCOUNT",0);
		$ldap_error[] = array(0x08000000,"UF_USE_AES_KEYS",0);

		// var_dump((0x00000002 & 7)!=0);
		// $ldap_error_code = 514;
		$is_status_ok = true;
		for ($i = 0 ; $i < count($ldap_error); $i ++){
			if ( ( $ldap_error[$i][0] & $ldap_error_code) != 0) {
				if ($ldap_error[$i][2] == 1){
					$error[] = array($ldap_error[$i][0],$ldap_error[$i][1]);
					$is_status_ok = false;
				}
			}
		}
		return $is_status_ok;
	}

}

?>