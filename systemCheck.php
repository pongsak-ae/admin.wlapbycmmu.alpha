<?php
	require('system/common.php');
	use OMCore\OMMail;
	use OMCore\OMPDO;
	use OMCore\OMMongo;
	use OMCore\OMLdap;

	include "config_systemCheck.php";

	ini_set("display_errors",1);
	error_reporting(E_ALL);
	function testSend($debug = 1 ,$to_email =  "namenut@ifirstfix.com"){
		$mail_checker = new OMCore\OMMail();
		echo "<pre>";

			$mail_checker->Sender = "deveploper@ifirstfix.com";
			$mail_checker->From = "deveploper@ifirstfix.com";
			$mail_checker->FromName = "deveploper";
			$mail_checker->AddAddress($to_email);

			$mail_checker->Subject = "test mail";
			$mail_checker->Body = "test mail";
			$mail_checker->AltBody = "test mail";
			$mail_checker->SMTPDebug  = $debug;

			$isSuccess = false;
			if(!$mail_checker->sendMail()){
				echo "Error: " . $mail_checker->ErrorInfo;
				exit();
			}else{
				$isSuccess = true;
				echo "Mail was send to: " . $to_email  ;
			}

			$mail_checker->SmtpClose();
			echo "</pre>";
			return $isSuccess;
	}
	// var_dump($configSystemCheck);
	foreach ($configSystemCheck as $key => $value) {
		// echo "Key : " . $key . " ..... Value : " . $value . "<br/>";
		if($key == "apache_version"){
			// Apache ------------------------------------
			echo "<strong>Apache Version</strong><br/>";
			ob_start();
			passthru("httpd -v");
			$out2 = ob_get_contents();
			ob_end_clean();
			$ar_apv = explode("/",$out2);
			$ar_apv = explode(" ",$ar_apv[1]);
			if($ar_apv[0] >= $value){
				echo "<span style='color: #04B431;'>OK</span><br/><br/>";
			}else{
				echo "<span style='color: #ff0000;'>not oK</span><br/><br/>";
			}

		}else if($key == "mod_rewrite"){
			// Mod Rewrite ------------------------------------
			echo "<strong>Mod Rewrite</strong><br/>";
			if(in_array($value, apache_get_modules())){
				echo "<span style='color: #04B431;'>Can use.</span><br/><br/>";
			}else{
				echo "<span style='color: #ff0000;'>Cannot use.</span><br/><br/>";
			}
		// var_dump(apache_get_modules());

		}else if($key == "database_support"){
			// Database Connect -------------------------------
			echo "<strong>Database Connect</strong><br/>";
				foreach ($value as $k => $v) {
					if ($v == "mysql") {
						// mySQL +++++++++++++++++++++++++++++
						$db_checker = new OMPDO(WCMSetting::$DEFAULT_DATABASE_CONNECTION_STRING);

						if ($db_checker->errorInfo()) {
							  echo "<span style='color: #ff0000;'>Failed to connect to MySQL: " ;
							   var_dump($db_checker->errorInfo() );
							   echo  "</span><br/>";
						}else{
							echo "<span style='color: #04B431;'>mySql can connect.</span><br/>";
						}
					}else if ($v == "mongo") {
						// Mongo +++++++++++++++++++++++++++++
						try {
							$dt = OMCore\OMMongo::table("template_builder");
							echo "<span style='color: #04B431;'>Mongo can connect.</span><br/><br/>";
						} catch (Exception $e) {
							echo "<span style='color: #ff0000;'>Mongo cannot connect : " . $e->getMessage() . "</span><br/><br/>";
						}
					}
				}

		}else if($key == "php_version"){
			// PHP version -----------------------------
			echo "<strong>PHP Version ".phpversion()."</strong>";
			if(version_compare(phpversion(), $value, '>=')){
				echo "<span style='color: #04B431;'> is OK </span><br/><br/>";
			}else{
				echo "<span style='color: #ff0000;'> &lt; ${value} </span><br/><br/>";
			}

		}else if($key == "send_mail"){
			// SMTP (send mail) -------------------------------
			echo "<strong>SMTP (send mail)</strong><br/>";

			if(testSend($value[0] , $value[1])){
				echo "<span style='color: #04B431;'>Can use SMTP</span>";
			}else{
				echo "<span style='color: #ff0000;'>Cannot use SMTP</span>";
			}
			echo "<br/><br/>";

		}else if($key == "write_folder"){
			// Write Stock --------------------------------
			echo "<strong>Permission Write to Stocks Directory: </strong>";
			$new_file_name = ROOT_DIR . $value . '/';
			if (!is_writable(($new_file_name))) {
			    echo "<span style='color: #ff0000;'> ".($new_file_name) . "</span> is denied.<br/>";
			} else {
					echo "<span style='color: #04B431;'>is OK</span><br/>";
			}
			echo "<br/>";

		}else if($key == "php_ini"){
			// PHP.ini --------------------------------
			echo "<strong>PHP.ini</strong><br/>";
			foreach ($value as $k => $v) {
				if($k == "short_open_tag"){
					echo (ini_get('short_open_tag') == "1")? "short_open_tag : On<br/>" : "short_open_tag : <span style='color: #ff0000;'>Off</span><br/>";

				}else if($k == "display_errors"){
					echo (ini_get('display_errors') == "1")? "display_errors : <span style='color: #ff0000;'>On</span><br/>" : "display_errors : Off<br/>";

				}else if($k == "date_timezone"){
					$msg = "<span style='color: #ff0000;'>" .$v ."</span>";
					if( strtolower(ini_get('date.timezone')) == "asia/bangkok" ){
						$msg = "<span >" .$v ."</span>";
					}

					echo "date.timezone : ".$msg."<br/>";

				}else {
					$current_value = ini_get($k);
					$msg = "<span style='color: #ff0000;'>" .$current_value ."</span>";
					if( intval($current_value) >= intval($v) ){
						$msg = "<span > " .$current_value ."</span>";
					}
					echo $k .": ".$msg."<br/>";
				}
			}
			echo "<br/>";

		}else if($key == "php_module"){
			// PHP Module --------------------------------
			echo "<strong>PHP Module</strong><br/>";
			echo "<pre>";
			$arr_module = get_loaded_extensions();
			echo "</pre>";
			// foreach ($arr_module as $key => $value) {
			// 	echo $value . "<br/>";
			// }
			foreach ($value as $k => $v) {
				if(in_array($v, $arr_module)){
					if($v == "gd"){
						$arr_gd = gd_info();
						echo $v." : ".$arr_gd["GD Version"];
					}else if($v == "libxml"){
						echo $v." : ".LIBXML_DOTTED_VERSION;
					}else if($v == "pcre"){
						echo $v." : ".PCRE_VERSION;
					}else if($v == "gmp"){
						echo $v." : ".GMP_VERSION;
					}else if($v == "iconv"){
						echo $v." : ".ICONV_VERSION;
					}else if($v == "xml"){
						echo $v." : ".LIBXML_DOTTED_VERSION;
					}else if($v == "curl"){
						$arr_curl = curl_version();
						echo $v." : ".$arr_curl["version"];
					}else{
						if(phpversion($v)){
							echo $v." : ".(phpversion($v));
						}else{
							echo $v." : N/A";
						}
					}
				}else{
					echo $v." : <span style='color: #ff0000;'>Required!</span>";
				}

				echo "<br/>";

			}
			echo "<br/>";

			// 	passthru("php -m");

		}else if($key == "dateTime"){
			// DateTime --------------------------------
			echo "<strong>Date Time</strong><br/>";

			?>
			<script>
				var d = new Date();
				var dd = d.getDate();
				var mm = d.getMonth()+1; //January is 0!
				var yyyy = d.getFullYear();
				var hh = d.getHours();
				var minutes = d.getMinutes();
				if(mm >=1 && mm <= 9){ mm = "0" + mm; }
				var js_current_time = yyyy+"-"+mm+"-"+dd+" "+hh+":"+minutes;
				document.write("JS time:" + js_current_time);
				document.write('<br>Server time: <?=date("Y-m-d H:i")?><br/>');
				if(js_current_time == '<?=date("Y-m-d H:i")?>'){
					document.write("<span style=color:#04B431 >OK</span>");
				}else{
					document.write("<span style=color:red >not OK</span>");
				}
			</script>

			<?php
			echo "<br/><br/>";
			// if(strtotime($_POST["chk_dateTime_val"]) == strtotime(date("Y-m-d H:i"))){
			// 	echo "Date Time of server is same with client.<br/><br/>";
			// }else{
			// 	echo "Date Time of server is not same with client.<br/><br/>";
			// }

		}else if($key == "ldap"){
			// LDAP --------------------------------
			echo "<strong>LDAP</strong><br/>";
			echo WCMSetting::$LDAP_SETTING_SERVER;
			echo WCMSetting::$LDAP_SETTING_BASE_DN;
			echo WCMSetting::$LDAP_SETTING_ACCOUNT_SUFFIX;
				$ldap_checker = new OMLdap(WCMSetting::$LDAP_SETTING_SERVER,WCMSetting::$LDAP_SETTING_BASE_DN,WCMSetting::$LDAP_SETTING_ACCOUNT_SUFFIX);
				$ldap_checker->connect();
				echo "<br/>";

				$ldap_checker->authenticate("s40001","P@ssw0rd");
				if( $ldap_checker->get_last_error() == "Success"){
					echo " <span style='color: #04B431;'>is OK!</span>";
				}else{
					echo " <span style='color: #ff0000;'>".$ldap_checker->get_last_error()."!</span>";
				}
				// echo $ldap_checker->get_last_errno();

			echo "<br/><br/>";
		}else if($key == "php_function"){
			if($value != ""){
				// Function -----------------------------
				echo "<strong>Function</strong><br/>";
				// $arr_function = explode(",",$value);
				// var_dump($arr_function);
				foreach ($value as $kk => $vv) {
					if (function_exists($vv)) {
					    echo $vv . " functions are available.<br/>";
					} else {
					    echo "<span style='color: #ff0000;'>" . $vv . "functions are not available.</span><br/>";
					}
				}
			}
		}else if($key == "display_phpinfo"){
			echo "<hr />";
			phpinfo();
		}
	}

?>