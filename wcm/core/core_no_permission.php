<?php
	require_once('../core/lib/all.php');
	$session->checkSession("redirect");
	$key_group = OMStringUtils::_TRIMGET("kg");
	$key_name = OMStringUtils::_TRIMGET("kn");
?>
<?=OMTemplateWCM::printHeader("Permission denied","")?>
<div style="width: 600px; padding: 60px 100px 60px 80px; display: block;">
<table border="0" width="100%" align="center" cellpaddding="0" cellspacing="0">
	<tr>
	  <td colspan="2" align="left"><span class="welcome1_txt default_gray_color">Operation failed</span></td>
	</tr>
	<tr>
	  <td width="5"></td>
	  <td align="left"><span class="welcome3_txt default_darkgray_color">Permission denied to call your method</span></td>
	</tr>
	<tr>
	  <td></td>
	  <td align="left"><span class="default_darkgray_color">&quot;<strong><?=$key_name?></strong>&quot; permission key in &quot;<strong><?=$key_group?></strong>&quot; module are required to perform this operation. If you feel sure of your permission to use, please sign out and try again. If you need to perform this operation, please contact your administrator to allow the permission above.</span>
	  <br/><br/>
        <br/>
        <br/>
         If you have any question about this software, please feel free to contact us via <br/>
        <br/>
        <img src="../core/images/small_ifirstfix_logo.png" vspace="2"/><br/>
        <span class="default_darkgray_color">Phone: 0-2187-2591<br/>Fax: 0-2187-2593<br/>
        Email: <a href="mailto:support@ifirstfix.com" class="default_black_color">support@ifirstfix.com</a></span><br/><br/>
        <span class="default_lightgray_color">Warning: To protect the system from unauthorized use and to ensure that the system is functioning properly, activities on this system are monitored and recorded and subject to audit. Use of this system is expressed consent to such monitoring and recording. Any unauthorized access or use of this Automated Information System is prohibited and could be subject to criminal and civil penalties.</span>
</table>
</div>
<?=OMTemplateWCM::printFooter()?>