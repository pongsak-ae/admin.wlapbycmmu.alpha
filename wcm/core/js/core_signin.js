
function CheckSubmitLogin(obj){
	var is_correct = true
	if ( trim(document.getElementById('loginname').value) == '')  {
		if ( is_correct == true){
			document.getElementById('err_result').innerHTML = '<span class=\"text_error_msg\">Please input Username.</span>';
			document.getElementById('loginname').focus();
			is_correct = false;
		}
	}
	if ( trim(document.getElementById('password').value) == '')  {
		if ( is_correct == true){
			document.getElementById('err_result').innerHTML = '<span class=\"text_error_msg\">Please input password.</span>';
			document.getElementById('password').focus();
			is_correct = false;
		}
	}

	$(obj).find('a.button').addClass("clicked");
	if (is_correct) {
		document.login_form.submit();
	} else {
		setTimeout(function () { $(obj).find('a.button').removeClass("clicked"); },500);
		return false;
	}
}
function doSubmitForm(frm) {
    frm.submit();
    return false;
}
//---------------------------------------------------------------------------

function CheckSubmitChangePasswd(obj){
	var is_correct = true
	document.getElementById('err_result').innerHTML = '';
	if ( trim(document.getElementById('loginname').value) == '')  {
		if ( is_correct == true){
			document.getElementById('err_result').innerHTML = '<span class=\"error_msg\">Please input Username.</span>';
			document.getElementById('loginname').focus();
			is_correct = false;
		}
	}
	if ( trim(document.getElementById('password').value) == '')  {
		if ( is_correct == true){
			document.getElementById('err_result').innerHTML = '<span class=\"error_msg\">Please input old password.</span>';
			document.getElementById('password').focus();
			is_correct = false;
		}
	}
	var passwd1 = trim(document.getElementById('new_password1').value);
	if ( passwd1 == '')  {
		if ( is_correct == true){
			document.getElementById('err_result').innerHTML = '<span class=\"error_msg\">Please input new password.</span>';
			document.getElementById('new_password1').focus();
			is_correct = false;
		}
	}
	if ( passwd1.length < 4 )  {
		if ( is_correct == true){
			document.getElementById('err_result').innerHTML = '<span class=\"error_msg\">The password must contain at least four characters.</span>';
			document.getElementById('new_password1').focus();
			is_correct = false;
		}
	}
	var passwd2 = trim(document.getElementById('new_password2').value);
	if ( passwd2 == '')  {
		if ( is_correct == true){
			document.getElementById('err_result').innerHTML = '<span class=\"error_msg\">Please input confirm new password.</span>';
			document.getElementById('new_password2').focus();
			is_correct = false;
		}
	}
	if ( passwd1 !=  passwd2)  {
		if ( is_correct == true){
			document.getElementById('err_result').innerHTML = '<span class=\"error_msg\">New password is not same in 2 times.</span>';
			document.getElementById('new_password2').focus();
			is_correct = false;
		}
	}
	
        $(obj).find('a.button').addClass("clicked");
	if (is_correct) {
		document.change_passwd_form.submit();
	} else {
	        setTimeout(function () { $(obj).find('a.button').removeClass("clicked"); },500);
	        return false;
	}
}