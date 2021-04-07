<?php
	require_once('../core/lib/all.php');

	echo OMTemplateWCM::printHeaderSignin();

	$PageReferer = OMStringUtils::REQ("refer");
	$str_command = OMStringUtils::REQ("command");
	$last_username = $session->SecureCookie->getValue("wcm_user_c1");

	if($str_command == "change_passwd") {
?>
	<form method="post" action="core_signin_check.php" name="change_passwd_form" id="change_passwd_form" onsubmit="return CheckSubmitChangePasswd(this);">
	<table width="380" border="0" cellspacing="0" cellpadding="0" class="signin_page">
		<tr>
			<td width="60%" height="25" class="module_border_top">&nbsp;</td>
			<td width="40%" class="module_border_top">&nbsp;</td>
		</tr>
	  <tr valign="middle">
		<td align="left" class="header2 default_gray_color" colspan="2" height="60">Change password</td>
	  </tr>
	  <tr valign="middle">
		<td align="left" class="instruction default_gray_color" colspan="2">Enter your username, old password and new password twice<br/>for changing your password.</td>
	  </tr>
	  <tr><td height="30" colspan="2" align="left" id="err_result" class="error_msg"></td></tr>
	  <tr valign="middle">
		<td align="left" colspan="2">
			<label for="loginname" class="inputlabel default_darkgray_color"><img src="../core/images/form/required2.gif" alt="required" /> Username</label>
			<input maxlength="20" type="text" name="loginname" id="loginname" class="inputbox" style="width:220px;" />
		</td>
	  </tr>
	  <tr align="left" valign="middle">
		<td height="40" colspan="2">
			<label for="password" class="inputlabel default_darkgray_color"><img src="../core/images/form/required2.gif" alt="required" /> Old password</label>
			<input maxlength="15" type="password" name="password" id="password" autocomplete="off" class="inputbox" style="width:220px;" />
		</td>
	  </tr>
	  <tr align="left" valign="middle">
		<td height="40" colspan="2">
			<label for="new_password1" class="inputlabel default_darkgray_color"><img src="../core/images/form/required2.gif" alt="required" /> New password</label>
			<input maxlength="15" type="password" name="new_password1" autocomplete="off" id="new_password1" class="inputbox" style="width:220px;" />
		</td>
	  </tr>
	  <tr align="left" valign="middle">
		<td height="40" colspan="2">
			<label for="new_password2" class="inputlabel default_darkgray_color"><img src="../core/images/form/required2.gif" alt="required" /> Confirm new password</label>
			<input maxlength="15" type="password" name="new_password2" autocomplete="off" id="new_password2" class="inputbox" style="width:220px;" />
		</td>
		  </tr>
	  <tr>
			<td colspan="2" height="40" align="left">
		<?=OMTemplateWCM::printButton("Confirm change","medium","javascript:void(0);"," onclick=\"return doSubmitForm($(this).parents('form'))\"") ?><span class="cancel_link"> or <a href="core_signin.php?refer=<?=urlencode($PageReferer)?>">Cancel</a></span></td>
	  </tr>
	</table>
	<input type="submit" name="submit_login" id="submit_login" value="Change" style="visibility:hidden;" />
	<input type="hidden" name="submit_change_passwd" id="submit_change_passwd" value="Change" />
	<input type="hidden" value="change_passwd" name="command" />
	<input type="hidden" name="refer" id="refer" value="<?=htmlspecialchars($PageReferer, ENT_QUOTES)?>" />
	</form>
<?php
	} else {
?>
	<form method="post" action="core_signin_check.php" name="login_form" id="login_form" >
	<!-- return CheckSubmitLogin(this); -->
	<table width="380" border="0" cellspacing="0" cellpadding="0" class="signin_page">
		<tr>
			<td width="60%" height="25" class="module_border_top">&nbsp;</td>
			<td width="40%" class="module_border_top">&nbsp;</td>
		</tr>
	  <tr valign="middle">
		<td align="left" class="header default_gray_color" colspan="2" height="60">Sign in</td>
	  </tr>
	  <tr valign="middle">
		<td align="left" class="instruction default_gray_color" colspan="2">Enter your User ID and password to sign in now

			<br/>
		  or change your password <a href="core_signin.php?command=change_passwd&amp;refer=<?=urlencode($PageReferer)?>" class="link">click here</a>
		</td>
	  </tr>
	  <tr><td height="30" colspan="2" align="left" id="err_result" class="error_msg"></td></tr>
	  <tr valign="middle">
		<td align="left" colspan="2"><label for="loginname" class="inputlabel default_darkgray_color">Username</label><input maxlength="20" type="text" name="loginname" id="loginname" class="inputbox" style="width:220px;" value="<?=OMStringUtils::trim($last_username)?>" /></td>
	  </tr>
	  <tr align="left" valign="middle">
		<td height="50" ><label for="password" class="inputlabel default_darkgray_color">Password</label><input maxlength="15" type="password" name="password" autocomplete="off" id="password" class="inputbox" style="width:220px;" /></td>
		<td style="">
			<div class="submit_button">
				<?=OMTemplateWCM::printButton("Sign in","medium","javascript:void(0);"," onclick=\"return doSubmitForm($(this).parents('form'))\"") ?>
			</div>
		</td>
	  </tr>
	  <tr valign="top">
		<td align="left" class="instruction default_gray_color" colspan="2" height="45">
			Did you forgot your password?<br/><a href="mailto:<?=WCMSetting::$ADMIN_EMAIL_ADDRESS ?>?subject=<?=WCMSetting::$FORGOT_PASSWORD_EMAIL_SUBJECT ?>" class="link">Click here</a> for assistance
		</td>
	  </tr>
	  <tr valign="middle">
		<td align="left" class="border_top warning_txt default_lightgray_color" colspan="2">&nbsp;<br/>
			WARNING: To protect the system from unauthorized use and to ensure that the system is functioning properly, activities on this system are monitored and recorded and subject to audit. Use of this system is expressed consent to such monitoring and recording. Any unauthorized access or use of this Automated Information System is prohibited and could be subject to criminal and civil penalties.
		</td>
	  </tr>
	  <tr><td colspan="2"></td></tr>
	</table>
	<input type="submit" name="submit_login" id="submit_login" value="Login" style="visibility:hidden;" />
	<input type="hidden" value="login" name="command" />
	<input type="hidden" name="refer" id="refer" value="<?=htmlspecialchars($PageReferer, ENT_QUOTES)?>" />
	</form>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#loginname").focus();
			$(document).keydown(function(event) {
				if (event.keyCode == '13') {
					$(".submit_button a").addClass("clicked");
					$("form").submit();
				}
			});
		});
	</script>
<?php
	}

	$str_error = OMStringUtils::REQ("err");
	if($str_error != "") {
			echo "<script type=\"text/javascript\">\r\n";
		if($str_error == "login_fail") {
			echo "\$('#err_result').html('Access denied!');\r\n";
		} else if($str_error == "username") {
			echo "\$('#err_result').html('Error! Username is invalid.');\r\n";
		} else if($str_error == "mismatch") {
			echo "\$('#err_result').html('Error! New password is not same in 2 times.');\r\n";
		} else if($str_error == "passwd") {
			echo "\$('#err_result').html('Error! New password is not valid.');\r\n";
		} else if($str_error == "change_success") {
			echo "\$('#err_result').html('<span class=\"success_msg\">Your password is changed.</span>');\r\n";
		} else if($str_error == "user_passwd") {
			echo "\$('#err_result').html('Error! Username or password is invalid.');\r\n";
		}else{
			$emsg = $session->SecureCookie->getValue("emsg");
			echo "\$('#err_result').html('".htmlspecialchars($emsg,ENT_QUOTES)."');\r\n";
		}
		echo "</script>";
	}

	echo OMTemplateWCM::printFooterSignin();

?>