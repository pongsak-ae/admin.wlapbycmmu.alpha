<?php
	// $configSystemCheck["apache_version"] = "2.2";
	$configSystemCheck["mod_rewrite"] = "mod_rewrite";
	// $configSystemCheck["database_support"] = array("mysql","mongo");
	$configSystemCheck["php_version"] = "5.6.0";
	// $configSystemCheck["send_mail"] = array("0" ,"namenut@ifirstfix.com"); // 0 disalbed debug / 1,2,4  for detail
	$configSystemCheck["write_folder"] = "stocks";
	$configSystemCheck["php_ini"] = array(
      "short_open_tag"=>"On"
      ,"display_errors"=>"Off"
      ,"date_timezone"=>"Asia/Bangkok"
      ,"disable_functions"=>""
      ,"post_max_size"=>"60M"
      ,"upload_max_filesize"=>"30M"
      ,"memory_limit"=>"128M");
	$configSystemCheck["php_module"] = array("libxml","openssl","hash","ftp","gettext","gearman","iconv","gd","session","SimpleXML","sockets","mbstring","xml","mongo","apc","curl","imap","json","ldap","exif","mcrypt","mysql","odbc","PDO","pdo_mysql","PDO_ODBC","pdo_dblib","ibm_db2","imagick","soap","mssql","odbc","pcntl","posix","mhash");
	$configSystemCheck["dateTime"] = "";
	$configSystemCheck["ldap"] = "";
	$configSystemCheck["php_function"] = array("imagejpeg","json_decode","imagegif");

	// $configSystemCheck["display_phpinfo"] = "true";

?>