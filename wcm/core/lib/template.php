<?php
class OMTemplateWCM
{
	static function genHtmlSection($section_id, $param = ""){
		$ret = "";
		if($section_id == 1) {
			$datepicker_option_addon = "";
			if(WCMSetting::$CULTUREINFO_FORMAT == "th-TH") {
				$datepicker_option_addon = ", yearOffset: 543, monthNamesShort: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'], dayNamesMin: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.']";
			} else {
				$datepicker_option_addon = ", yearOffset: 0, monthNamesShort: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'], dayNamesMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']";
			}

			$ret = "<!DOCTYPE html>" . "\r\n";
			$ret .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">" . "\r\n";
			$ret .= "<head>" . "\r\n";
			$ret .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />" . "\r\n";
			$ret .= "<title>" . WCMSetting::$WINDOWS_TITLE ."</title>" . "\r\n";
			$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/css/default.css\" />" . "\r\n";
			$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/css/jqueryui/jquery-ui-1.8.1.custom.css\" />" . "\r\n";
			$ret .= "<!--[if !IE]><!-->\r\n";
			$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/css/responsive.css\" />" . "\r\n";
			$ret .= "<!--<![endif]-->\r\n";
			$ret .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/css/cropper.css\" />" . "\r\n";
			$ret .= "<script type=\"text/javascript\" src=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/js/default.min.js\"></" . "script>" . "\r\n";
			$ret .= "<script type=\"text/javascript\" src=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/js/jquery-1.11.1.min.js\"></" . "script>" . "\r\n";
			$ret .= "<script type=\"text/javascript\" src=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/js/jquery-migrate-1.0.0.js\"></" . "script>" . "\r\n";
			$ret .= "<script type=\"text/javascript\" src=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/js/jquery-ui-1.8.2.custom.min.js\"></" . "script>" . "\r\n";
			$ret .= "<script type=\"text/javascript\" src=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/js/jquery.leftmenu_custom.min.js\"></" . "script>" . "\r\n";
			$ret .= "<script type=\"text/javascript\" src=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/js/jquery.getscrollbarwidth.min.js\"></" . "script>" . "\r\n";
			$ret .= "<script type=\"text/javascript\" src=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/js/jquery.contextmenu_custom.min.js\"></" . "script>" . "\r\n";
			$ret .= "<script type=\"text/javascript\" src=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/js/jquery.form.min.js\"></" . "script>" . "\r\n";
			$ret .= "<script type=\"text/javascript\" src=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/js/responsive.min.js\"></" . "script>" . "\r\n";
			$ret .= "<script type=\"text/javascript\" src=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/js/cropper.min.js\"></" . "script>" . "\r\n";
			$ret .= "<script type=\"text/javascript\">" . "\r\n";
			$ret .= "<!--" . "\r\n";
			$ret .= "function bindDatePicker(obj) {" . "\r\n";
			$ret .= "	if(obj == undefined) { obj = \".form_master .datepicker\"; }" . "\r\n";
			$ret .= "	$(obj).datepicker({dateFormat: \"dd/mm/yy\", changeMonth: true, changeYear: true, showOtherMonths: true, selectOtherMonths: true, showAnim: \"fadeIn\" " . $datepicker_option_addon . "});" . "\r\n";
			$ret .= "	return true;" . "\r\n";
			$ret .= "}" . "\r\n";
			$ret .= "$(document).ready(function(){" . "\r\n";
			$ret .= "	$('#leftMenu').initLeftMenu({width: 169,height: 350,content: '.pageContent',title: '.header_section .title',rootText: 'Modules'});" . "\r\n";
			$ret .= "	$('a.button').mousedown(function() { $(this).addClass(\"clicked\"); }).mouseout(function() { $(this).removeClass(\"clicked\"); }).mouseup(function() { $(this).removeClass(\"clicked\"); }); " . "\r\r";
			$ret .= "	bindDatePicker();";
			$ret .= "});" . "\r\n";
			$ret .= "//-->" . "\r\n";
			$ret .= "</" . "script>" . "\r\n";
			$ret .= "</head>" . "\r\n";
		//====> Container
		} else if ($section_id == 2) {
			if(strpos($param, "list") !== false) {
				$ret .= "<body id=\"sticky-container\" class=\"sticky-container wcmLayout list_data\">" . "\r\n";
			} else if($param == "") {
				$ret .= "<body id=\"sticky-container\" class=\"sticky-container wcmLayout unknown\">" . "\r\n";
			} else {
				$ret .= "<body id=\"sticky-container\" class=\"sticky-container wcmLayout form_data\">" . "\r\n";
			}
			$ret .= "<div class=\"container\">" . "\r\n";
			$ret .= "	<div class=\"header_top\">" . "\r\n";
			$ret .= "		<div class=\"logo\"><img src=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/images/client_logo.png\" alt=\"\" /></div>" . "\r\n";
			$ret .= "		<div class=\"title\">" . "\r\n";
			$ret .= "			<h1>" . WCMSetting::$SYSTEM_TITLE_LINE1_1 . "<span style=\"color: #AAAAAA;\">" . WCMSetting::$SYSTEM_TITLE_LINE1_2 . "</span></h1>" . "\r\n";
			$ret .= "			<h2>for <span style=\"color: #DDDDDD;\">" . WCMSetting::$SYSTEM_TITLE_LINE2_1 . "</span> " . WCMSetting::$SYSTEM_TITLE_LINE2_2 . "</h2>" . "\r\n";
			$ret .= "		</div>" . "\r\n";
		//====> Username & Sign out link
		} else if ($section_id == 3) {
			$ret .= "	</div>" . "\r\n";
			$ret .= "	<div class=\"header_section\">" . "\r\n";
			$ret .= "		<div class=\"tl\"></div>" . "\r\n";
			$ret .= "		<div class=\"tr\"></div>" . "\r\n";
			$ret .= "		<div class=\"clearfix\"></div>" . "\r\n";
			$ret .= "		<span title=\"Menu\" id=\"menu_response\" class=\"alert\" style=\"cursor:pointer;display:none;\"><img class=\"img_response\" src=\"../core/images/response_menu.png\" width=\"39\" height=\"40\" /></span>";
			$ret .= "		<div class=\"title\">";
		//====> Page title
		} else if ($section_id == 4) {
			$ret .=              "</div>" . "\r\n";
			if($param == "list") {

				//$ret .= OMTemplateWCM::printButton("Export All Data", "medium2", "javascript:exportData('all');", "style='right: 114px !important; text-align: center; width: 95px;'");
				$ret .= '<div id="wrap_button_import">' . "\r\n";
				global $default_import_export;
				if ($default_import_export == "yes") {
					$style = "style='right: 126px; width: 94px; height:22px;top: 19px;'";
					$ret .= OMTemplateWCM::printFormUpload(1, "list_cmd.php", $style);
				}
				$style = "style='right: 120px !important; text-align: center; width: 82px;'";

				if ($default_import_export == "yes") {
					$ret .= OMTemplateWCM::printButton("Import Data", "medium2", "javascript:uploadCustom1();", $style);
				}
				$ret .= '</div>' . "\r\n";

				$ret .= OMTemplateWCM::printButton("Add new item", "medium2", "form.php?m=add", "");
			} else if($param == "list_noedit") {
				// no button
			} else if($param == "list_message") {
				$ret .= OMTemplateWCM::printButton("Compose new Message", "medium2", "javascript:composeMessage()", "");
			} else if($param == "form_edit") {
				$ret .= OMTemplateWCM::printButton("Back to list", "medium2", "javascript:backToList('list.php')", "");
			} else if($param == "form_add") {
				$ret .= OMTemplateWCM::printButton("Cancel and don't save", "medium2", "list.php", "");
			} else if($param == "form_view") {
				$ret .= OMTemplateWCM::printButton("Back to list", "medium2", "list.php", "");
			}
			$ret .= "	</div>" . "\r\n";
			$ret .= "	<table class=\"mainContent\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">" . "\r\n";
			$ret .= "		<tr><td colspan=\"3\" height=\"1\" bgcolor=\"#D9D9D9\"></td></tr>" . "\r\n";
			$ret .= "		<tr>" . "\r\n";
			$ret .= "			<td width=\"169\" valign=\"top\">" . "\r\n";
		//=====> Left menu
		} else if ($section_id == 5) {
			$ret .= "			</td>" . "\r\n";
			$ret .= "			<td width=\"1\" bgcolor=\"#D9D9D9\"></td>" . "\r\n";
			$ret .= "			<td valign=\"top\" class=\"pageContent\" bgcolor=\"#FFFFFF\">";
			$ret .= "				<input type=\"hidden\" autocomplete=\"off\" id=\"list_item_checked\" name=\"list_item_checked\" value=\"\" />" . "\r\n";
		//=====> Page content
		} else if ($section_id == 6) {
			$ret .= "			</td>" . "\r\n";
			$ret .= "		</tr>" . "\r\n";
			$ret .= "		<tr>" . "\r\n";
			$ret .= "			<td><div class=\"bl\"></div></td>" . "\r\n";
			$ret .= "			<td bgcolor=\"#D9D9D9\"></td>" . "\r\n";
			$ret .= "			<td bgcolor=\"#FFFFFF\"><div class=\"br\"></div></td>" . "\r\n";
			$ret .= "		</tr>" . "\r\n";
			$ret .= "	</table>" . "\r\n";
		//=====> Before footer
		} else if ($section_id == 7) {
			$ret .= "	<div class=\"footer\">" . "\r\n";
			if(!WCMSetting::$HIDE_BRAND){
				$ret .= "		<div class=\"logo\"></div>" . "\r\n";
				$ret .= "		<a href=\"xxx.html\">Bug report</a><a href=\"xxx.html\">Call support</a><a href=\"xxx.html\">Help</a> &copy; 2019 Ifirstfix Technology Co., Ltd. All rights reserved." . "\r\n";
			}
			$ret .= "	</div>" . "\r\n";
			$ret .= "<!-- end #container --></div>" . "\r\n";
			$ret .= "<div id=\"wrap_notify\"><div id=\"contain_notify\"></div></div>" . "\r\n";
			$ret .= "</body>" . "\r\n";
			$ret .= "</html>" . "\r\n";

		// ------------ Sign in template page -------------
		} else if ($section_id == 8) {
			$ret .= "<body>" . "\r\n";
			$ret .= "<script type=\"text/javascript\" src=\"js/core_signin.js\"><" . "/script>" . "\r\n";
			$ret .= "<div style=\"height: 100%\" class=\"wcmLayout sign_in\">" . "\r\n"; // <===???
			$ret .= "<table width=\"100%\" height=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">" . "\r\n";
			$ret .= "	<tr id=\"main_tr\">" . "\r\n";
			$ret .= "	<td valign=\"middle\" align=\"center\" id=\"main_td\">" . "\r\n";
			$ret .= "    <div class=\"container_signin\">" . "\r\n";
			$ret .= "	<div class=\"header_section\">" . "\r\n";
			$ret .= "		<div class=\"tl\"></div>" . "\r\n";
			$ret .= "		<div class=\"tr\"></div>" . "\r\n";
			$ret .= "		<div class=\"title\">" . WCMSetting::$SYSTEM_TITLE_LINE1_1 . WCMSetting::$SYSTEM_TITLE_LINE1_2 . "</div>" . "\r\n";
			$ret .= "		<div class=\"logo\"><img src=\"" . WCMSetting::$ROOT_WCM_FOLDER . "core/images/client_logo.png\" alt=\"\" /></div>" . "\r\n";
			$ret .= "	</div>" . "\r\n";
			$ret .= "	<table class=\"mainContent\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">" . "\r\n";
			$ret .= "		<tr><td colspan=\"3\" height=\"1\" bgcolor=\"#D9D9D9\"></td></tr>" . "\r\n";
			$ret .= "		<tr>" . "\r\n";
			$ret .= "			<td width=\"129\" valign=\"top\">" . "\r\n";
		//=====> Left menu
			$ret .= "			</td>" . "\r\n";
			$ret .= "			<td width=\"1\" bgcolor=\"#D9D9D9\"></td>" . "\r\n";
			$ret .= "			<td valign=\"top\" class=\"pageContent\" bgcolor=\"#FFFFFF\">";
		//=====> Page content
		} else if ($section_id == 9) {
			$ret .= "			</td>" . "\r\n";
			$ret .= "		</tr>" . "\r\n";
			$ret .= "		<tr>" . "\r\n";
			$ret .= "			<td><div class=\"bl\"></div></td>" . "\r\n";
			$ret .= "			<td bgcolor=\"#D9D9D9\"></td>" . "\r\n";
			$ret .= "			<td bgcolor=\"#FFFFFF\"><div class=\"br\"></div></td>" . "\r\n";
			$ret .= "		</tr>" . "\r\n";
			$ret .= "	</table>" . "\r\n";
			$ret .= "	<div class=\"footer\">" . "\r\n";
			if(!WCMSetting::$HIDE_BRAND){
				$ret .= "		<div id=\"logo\"></div>&copy; 2019 Ifirstfix Technology Co., Ltd. All right reserved." . "\r\n";
			}
			$ret .= "	</div>" . "\r\n";
			$ret .= "	</div>" . "\r\n";
			$ret .= "	</td>" . "\r\n";
			$ret .= "	</tr>" . "\r\n";
			$ret .= "	</table>" . "\r\n";
			$ret .= "</div>" . "\r\n";
			$ret .= "</body>" . "\r\n";
			$ret .= "</html>" . "\r\n";
		}
		return($ret);
	}
	static function printHeader($page_title, $active_menu, $param = "") {
		$ret = "";
		$ret .= OMTemplateWCM::genHtmlSection(1);
		$ret .= OMTemplateWCM::genHtmlSection(2,$param);
		$ret .= OMTemplateWCM::printUserInfo(OMSession::Current()->Username);
		$ret .= OMTemplateWCM::genHtmlSection(3);
		$ret .= "<span style=\"cursor:pointer;\" onclick=\"window.location.assign(window.location.pathname);\">" . $page_title . "</span>";
		$ret .= OMTemplateWCM::genHtmlSection(4,$param);
		$ret .= OMTemplateWCM::printMenu($active_menu);
		$ret .= OMTemplateWCM::genHtmlSection(5);
		return($ret);
	}
	static function printHeaderSignin() {
		$ret = "";
		$ret .= OMTemplateWCM::genHtmlSection(1);
		$ret .= OMTemplateWCM::genHtmlSection(8);
		return($ret);
	}
	static function printFooter() {
		return OMTemplateWCM::genHtmlSection(6) . OMTemplateWCM::genHtmlSection(7);
	}
	static function printFooterSignin() {
		return OMTemplateWCM::genHtmlSection(9);
	}
	static function checkNewMessage(){
		$sql = "select * from wcm_message where (owner_user_id = @owner_user_id) and (folder = 'i') and (obj_status = 'active') and (is_read = 'F')";
		$dt=null;
		$DB = new OMDatabase();
		return $DB->query($dt, $sql, array("@owner_user_id"=>OMSession::Current()->UserId));
	}
	static function printUserInfo($username) {
		$ret = "";
		$ret .= "		<div class=\"user\"><span class=\"icon\"></span><strong>" . $username . " </strong>&nbsp;(<a href=\"../core/core_signout.php?" . uniqid() . "\">Sign out</a>)</div>" . "\r\n";
		$ret .= "		<div class=\"shortcut_link\">" . "\r\n";
		$ret .= "		<a title=\"Action log\" class=\"icon_log\" href=\"../action_log/list.php\"></a>" . "\r\n";
		if(OMTemplateWCM::checkNewMessage() > 0) {
			$ret .= "		<a title=\"Message\" class=\"icon_msg alert\" href=\"../messages/list.php?f=i\"></a>" . "\r\n";
		} else {
			$ret .= "		<a title=\"Message\" class=\"icon_msg\" href=\"../messages/list.php?f=i\"></a>" . "\r\n";
		}
		//ret += "		<a title=\"Statistic\" class=\"icon_stat\" href=\"../stat/list.php\"></a>" + "\r\n";
		//ret += "		<a title=\"Backup\" class=\"icon_safe\" href=\"../dashboard/safe.php\"></a>" + "\r\n";
		$ret .= "		</div>" . "\r\n";

		return($ret);
	}
	static function printSubMenu($xml_node, $active_menu){
		$ret = "";
		for($i=0;$i < count($xml_node);$i++) {
			$attributes = $xml_node[$i]->attributes();
			if (isset($attributes['menu'])) {
				$menu_str = $attributes['menu'];
			} else {
				$menu_str = '';
			}
			if (isset($attributes['page'])) {
				$page_str = $attributes['page'];
			} else {
				$page_str = '';
			}
			if (isset($attributes['name'])) {
				$name_str = $attributes['name'];
			} else {
				$name_str = '';
			}
			if (isset($attributes['key'])) {
				$key_str = $attributes['key'];
			} else {
				$key_str = '';
			}
			if (isset($attributes['target'])) {
				$target = $attributes['target'];
			} else {
				$target = '';
			}
			if (count($xml_node[$i]->xpath('NODE')) == 0) {
				if($key_str == "" || (OMSession::Current()->checkPermission((string)$key_str) == true)) {
					$ret .= "<li><a href=\"" .  $page_str . "\" ";
					if($target != ''){ $ret .= " target=\"" . $target . "\" "; }
					if($menu_str == $active_menu){ $ret .= " class=\"active\" "; }
					$ret .= ">" .  $name_str . "</a></li>";
				}

			} else {
				$sub_ret = OMTemplateWCM::printSubMenu($xml_node[$i]->xpath('NODE'), $active_menu);
				if($sub_ret != ""){
					$ret .= "<li><a href=\"#\">" . $name_str . "</a>";
					$ret .=  $sub_ret;
					$ret .= "</li>";
				}
			}

		}
		if ($ret != "") {
			$ret = "<ul>" . $ret . "</ul>";
		}
		return $ret;
	}
	//--------------------------------------------------------------------------------------------------------------

	static function printMenu($active_menu) {
		$ret = "";
		$filename = WCMSetting::$ROOT_WCM_FOLDER . "core/structure/modules.conf";
		$XMLdoc = simplexml_load_file($filename);
		$ret .= "<div id=\"groupLeftMenu\">";
		$ret .= "<div id=\"fadeLeftMenu\" class=\"hide\">";
		$ret .= "	<div id=\"bgLeftMenu\"></div>";
		$ret .= "	<div id=\"wrapLeftMenu\">";
		$ret .= "	<div class=\"corner_top\"></div>";
		$ret .= "<div id=\"leftMenu\">";
		$node = $XMLdoc->xpath("/MENU/NODE");
		$ret .=  OMTemplateWCM::printSubMenu($node, $active_menu);
		$ret .= "</div>";
		$ret .= "	</div>";
		$ret .= "</div>";
		$ret .= "</div>";
		return $ret;
	}

	static function printButton($cmd_txt, $button_size, $url, $add_on) {
		$ret = "";
		if($button_size != "medium" && $button_size != "medium2") { $button_size = "medium"; }

		$custom_class = "";
		if ($cmd_txt == "Back to list") {
			$custom_class = " btn_back_to_list";
		} else if ($cmd_txt == "Add new item") {
			$custom_class = " btn_add_new_item";
		} else if ($cmd_txt == "Delete") {
			$custom_class = " btn_delete";
		}
		$ret .= "<a class=\"button " . $button_size . $custom_class . "\" href=\"" . $url . "\" " . $add_on . "><span>" . $cmd_txt . "</span></a>";
		return($ret);
	}
	static function printFormUpload($ref_id, $action, $add_on) {
		$ret = "";
		$ret .= '<form method="post" action="' . $action . '" id="upload_custom_' . $ref_id . '" class="upload_custom" enctype="multipart/form-data">' . "\r\n";
		$ret .= '    <input type="hidden" name="c" value="import_data" />' . "\r\n";
		$ret .= '    <input type="hidden" name="status_upload" class="status_upload" value="n" />' . "\r\n";
        $ret .= '    <input ' . $add_on . ' name="upload_custom_file" type="file" class="upload_custom_file" onchange="uploadCustom' . $ref_id . 'Change();" />' . "\r\n";
        $ret .= '    <input type="submit" class="upload_custom_submit"  />' . "\r\n";
       	$ret .= '</form>' . "\r\n";

		$ret .= '<script type="text/javascript">' . "\r\n";
        $ret .= '	function uploadCustom' . $ref_id . 'Change(){' . "\r\n";
        $ret .= '		$( "#upload_custom_' . $ref_id . '").submit(); ' . "\r\n";
        $ret .= '	}' . "\r\n";
        $ret .= '	function uploadCustom' . $ref_id . '(){' . "\r\n";
        $ret .= '		$( "#upload_custom_' . $ref_id . ' .upload_custom_file").click(); ' . "\r\n";
        $ret .= '	}' . "\r\n";

        $ret .= '	$( "#upload_custom_' . $ref_id . ' .upload_custom_file" ).hover(function() {' . "\r\n";
		$ret .= '		$(this).parent().parent().find(".button").addClass("hover");' . "\r\n";
		$ret .= '	}, function() {' . "\r\n";
		$ret .= '		$(this).parent().parent().find(".button").removeClass("hover");' . "\r\n";
		$ret .= '	});' . "\r\n";
    	$ret .= '</script>';
		return($ret);
	}
	static function printFilterList($module_name) {
		$num_search_list = 0;
		$i=0;
		$ret = "";
		if(OMSession::Current()->CookieDb->getCookie($module_name,"saved_list") != null){
			$plist = OMSession::Current()->CookieDb->getCookie($module_name,"saved_list");
			if($plist != ""){
				$savedfilter = explode(',',$plist);
				for($i=0;$i < count($savedfilter);$i++){
					$ret .= "<li><div><a href=\"#" . $savedfilter[$i] . "\">" . OMSession::Current()->CookieDb->getCookie($module_name,"saved_listname_" . $savedfilter[$i]) . "</a></div></li>";
					$num_search_list++;
				}
			}
		}
		if($num_search_list == 0) {
			$ret .= "<div class=\"empty\"><strong>This list does not contain any saved filter list.</strong><br/><br/>Click &quot;+&quot; button to add the filter and click &quot;Save&quot; button to save a new filter list.</div>";
		}
		return($ret);
	}
	//--------------------------------------------------------------------------------------------------------------

	static function printFilter($module_name) {

		$ret = "";

		$ret .= "<form action='#' method='post' onsubmit='$(\"#headsearch\").click();return false;'><div class=\"filter\">\r\n";
		$ret .= "  <div id=\"head\">\r\n";
		$ret .= "	<div id=\"searchlist\"><ul>\r\n";
		$ret .= OMTemplateWCM::printFilterList($module_name);
		$ret .= "	</ul></div>\r\n";
		$ret .= "	<div class=\"middleline fleft\">\r\n";
		$ret .= "		<span class=\"txt1 spn\" onclick=\"showSearchList();\">Search <small>&#9660;</small></span>\r\n";
		$ret .= "		<input type=\"text\" id=\"s_keyword\" style=\"width:200px;\" value=\"\"/>\r\n";
		$ret .= "		<span class=\"mode2 txt2 spn\">and</span>\r\n";
		$ret .= "		<select class=\"mode2\" id=\"s_mode\" style=\"width:auto;\">\r\n";
		$ret .= "			<option>all</option><option>any</option>\r\n";
		$ret .= "		</select>\r\n";
		$ret .= "		<span class=\"mode2 txt2 spn\">of the following condition are met&nbsp;&nbsp;</span>\r\n";
		$ret .= "	</div>\r\n";
		$ret .= "	<div class=\"middleline fleft\">\r\n";
		$ret .= "		<a href=\"#search\" class=\"cmdbutton search\" id=\"headsearch\" onclick=\"searchByFilter(this)\"></a>\r\n";
		$ret .= "	</div>";
		$ret .= "	<div class=\"middleline fright\">";
		$ret .= "		";
		$ret .= "		<a href=\"#clear\" class=\"cmdbutton clear\" onclick=\"removeAllFilter(this)\"></a>";
		$ret .= "		<a href=\"#add\" class=\"cmdbutton mode1_ib add\" onclick=\"addFilter(this,'')\"></a>";
		$ret .= "		<a href=\"#save\" class=\"cmdbutton mode2_ib save\" onclick=\"saveFilter(this,1)\"></a>";
		$ret .= "	</div>";
		$ret .= "  </div>";
		$ret .= "</div></form>";

		return($ret);
	}
	static function createWhereFromFilter(&$p_arr, $p_key, $fieldname, $dbtype, $oper, $value) {

		$oper_sql = "=";
		$dtconv = new OMDateTimeConverter("dd/MM/yyyy HH:mm:ss", WCMSetting::$CULTUREINFO_FORMAT);

		if(($dbtype == "text") || ($dbtype == "moretext")){
			$keyword_length = strlen($value);
			$new_keyword = "";
			$tmp_keyword = $value;
			$last_is_space = false;
			for ($i=0;$i<$keyword_length;$i++) {
				$char_keyword = substr($tmp_keyword, $i,1);

				if ($char_keyword == " ") {
					if (!$last_is_space) {
						$new_keyword .= "%";
					}
					$last_is_space = true;
				} else {
					$new_keyword .= $char_keyword;
					$last_is_space = false;
				}

			}
			if ($oper != "isequal") {
				$value = $new_keyword;
			}

			switch($oper) {
				case "contains": 	$p_arr["@" . $p_key] = "%" . OMStringUtils::mySqlLikeEscape($value) . "%"; $oper_sql = "LIKE"; break;
				case "notcontain":	$p_arr["@" . $p_key] = "%" . OMStringUtils::mySqlLikeEscape($value) . "%"; $oper_sql = "NOT LIKE"; break;
				case "beginswith":	$p_arr["@" . $p_key] = OMStringUtils::mySqlLikeEscape($value) . "%"; $oper_sql = "LIKE"; break;
				case "endswith":	$p_arr["@" . $p_key] = "%" . OMStringUtils::mySqlLikeEscape($value); $oper_sql = "LIKE"; break;
				case "isequal":		$p_arr["@" . $p_key] = $value; $oper_sql = "="; break;
				default:			$p_arr["@" . $p_key] = "%" . OMStringUtils::mySqlLikeEscape($value) . "%"; $oper_sql = "LIKE"; break;
			}
		} else if ($dbtype == "number"){
			$long_number = 0;
			$valid = false;
			$valid = true;
			$long_number = $value;
			switch($oper) {
				case "gteq": 		$p_arr["@" . $p_key] = ($valid)?$long_number:PHP_INT_MAX; $oper_sql = ">="; break;
				case "greater": 	$p_arr["@" . $p_key] = ($valid)?$long_number:PHP_INT_MAX; $oper_sql = ">"; break;
				case "lteq":		$p_arr["@" . $p_key] = ($valid)?$long_number:PHP_INT_MAX; $oper_sql = "<="; break;
				case "less":		$p_arr["@" . $p_key] = ($valid)?$long_number:PHP_INT_MAX; $oper_sql = "<"; break;
				case "is":			$p_arr["@" . $p_key] = ($valid)?$long_number:PHP_INT_MAX; $oper_sql = "="; break;
				case "isnot":		$p_arr["@" . $p_key] = ($valid)?$long_number:PHP_INT_MAX; $oper_sql = "<>"; break;
				default:			$p_arr["@" . $p_key] = ($valid)?$long_number:PHP_INT_MAX; $oper_sql = "="; break;
			}
		} else if ($dbtype == "decimal"){
			$dec_number;
			$valid = false;
			$valid = true;
			$dec_number = $value;
			switch($oper) {
				case "gteq": 		$p_arr["@" . $p_key] = ($valid)?$dec_number:PHP_INT_MAX; $oper_sql = ">="; break;
				case "greater": 	$p_arr["@" . $p_key] = ($valid)?$dec_number:PHP_INT_MAX; $oper_sql = ">"; break;
				case "lteq":		$p_arr["@" . $p_key] = ($valid)?$dec_number:PHP_INT_MAX; $oper_sql = "<="; break;
				case "less":		$p_arr["@" . $p_key] = ($valid)?$dec_number:PHP_INT_MAX; $oper_sql = "<"; break;
				case "is":			$p_arr["@" . $p_key] = ($valid)?$dec_number:PHP_INT_MAX; $oper_sql = "="; break;
				case "isnot":		$p_arr["@" . $p_key] = ($valid)?$dec_number:PHP_INT_MAX; $oper_sql = "<>"; break;
				default:			$p_arr["@" . $p_key] = ($valid)?$dec_number:PHP_INT_MAX; $oper_sql = "="; break;
			}
		} else if (($dbtype == "datetime") || ($dbtype == "date")) {
			switch($oper) {
				case "exactly": 	$p_arr["@" . $p_key] = $dtconv->toDateTime($value . " 00:00:00"); $oper_sql = "="; break;
				case "before": 		$p_arr["@" . $p_key] = $dtconv->toDateTime($value . " 00:00:00"); $oper_sql = "<"; break;
				//case "after":		$p_arr["@" . $p_key] = $dtconv->toDateTime($value . " 00:00:00")->AddDays(1); $oper_sql = ">"; break;
				//** Remove AddDay(1) by Jed
				case "after":		$p_arr["@" . $p_key] = $dtconv->toDateTime($value . " 00:00:00"); $oper_sql = ">"; break;
				default:			$p_arr["@" . $p_key] = $dtconv->toDateTime($value . " 00:00:00"); $oper_sql = "="; break;
			}
			if($oper_sql == "=") {
				$p_arr["@" . $p_key . "_part2"] = $dtconv->toDateTime($value . " 00:00:00")->AddDays(1);
				return("((" . $fieldname . " >= @" . $p_key . ") and (" . $fieldname . " < @" . $p_key . "_part2))");
			} else {
				return("(" . $fieldname . " " . $oper_sql . " @" . $p_key . ") ");
			}
		} else if (($dbtype == "time")) {
			switch($oper) {
				case "exactly": 	$p_arr["@" . $p_key] = $value; $oper_sql = "="; break;
				case "before": 		$p_arr["@" . $p_key] = $value; $oper_sql = "<"; break;
				case "after":		$p_arr["@" . $p_key] = $value; $oper_sql = ">"; break;
				default:			$p_arr["@" . $p_key] = $value; $oper_sql = "="; break;
			}
		} else if (($dbtype == "checkbox")) {
			switch($oper) {
				case "checked": 	$p_arr["@". $p_key] = "T"; $oper_sql = "="; break;
				case "uncheck": 	$p_arr["@". $p_key] = "T"; $oper_sql = "<>"; break;
				default:			$p_arr["@". $p_key] = "T"; $oper_sql = "="; break;
			}
		} else if (($dbtype == "file") || ($dbtype == "image")) {

			switch($oper) {
				case "empty": 		$fieldname = $fieldname . "_gen"; $p_arr["@" . $p_key] = ""; $oper_sql = "="; break;
				case "notempty": 	$fieldname = $fieldname . "_gen"; $p_arr["@" . $p_key] = ""; $oper_sql = "<>"; break;
				default:			$p_arr["@" . $p_key] = "%" . OMStringUtils::mySqlLikeEscape($value) . "%"; $oper_sql = "LIKE"; break;
			}
		} else if(($dbtype == "user")){
			switch($oper) {
				case "contains": 	$p_arr["@" . $p_key] = "%" . OMStringUtils::mySqlLikeEscape($value) . "%"; $oper_sql = "LIKE"; break;
				case "notcontain":	$p_arr["@" . $p_key] = "%" . OMStringUtils::mySqlLikeEscape($value) . "%"; $oper_sql = "NOT LIKE"; break;
				case "beginswith":	$p_arr["@" . $p_key] = OMStringUtils::mySqlLikeEscape($value) . "%"; $oper_sql = "LIKE"; break;
				case "endswith":	$p_arr["@" . $p_key] = "%" . OMStringUtils::mySqlLikeEscape($value); $oper_sql = "LIKE"; break;
				case "isequal":		$p_arr["@" . $p_key] = $value; $oper_sql = "="; break;
				default:			$p_arr["@" . $p_key] = "%" . OMStringUtils::mySqlLikeEscape($value) . "%"; $oper_sql = "LIKE"; break;
			}
			return("( " . $fieldname . " in (select user_id from wcm_user where concat(firstname , ' ', lastname, ' ', username) " . $oper_sql . " @" . $p_key . ") )");

		} else {
			return("");
		}
		return("(" . $fieldname . " " . $oper_sql . " @" . $p_key . ") ");
	}
	static function printFormField($id, $modify_permission, $set_value_object, $param, $DB=null, $filter_lang="", $module_name = "") {

		$set_value = "";
		$set_value_om = array();
		if (is_array($set_value_object))  {
			$set_value_om = $set_value_object;
		} else {
			$set_value = $set_value_object;
		}

		$pixel_per_char = 8;
		$field_width = 0;
		$maxlength = 0;

		//---- must have parameter ----//
		$field_type = $param["type"];
		if (isset($param["bgcolor"])) {
			$bgcolor = $param["bgcolor"];
		}
		if (isset($param["css"])) {
			$css = $param["css"];
		}
		$label = $param["label"];
		$hint = isset($param["hint"])?$param["hint"]:'';
		$example = isset($param["example"])?$param["example"]:'';
		$required = $param["required"];

		//---- optional parameter ----//
		$limit_array = array();
		$min = 0;
		$max = 0;
		if(isset($param["limit"] ) && $param["limit"] != null){
			$limit_array = explode(',', $param["limit"]);
			$min = $limit_array[0];
			$max = $limit_array[1];
		}

		$readonly_field = "";
		if(isset($param["readonly"]) &&  $param["readonly"] != null) {
			$readonly_field = strtolower($param["readonly"]);
			if($readonly_field == "t" || $readonly_field == "true") { $modify_permission = false; }
		}

		$checkbox_label = "";
		if(isset($param["checkbox_label"]) && $param["checkbox_label"] != null) {
			$checkbox_label = $param["checkbox_label"];
		}

		$digit = 0;
		if(isset($param["digit"]) && $param["digit"] != null){
			$digit = $param["digit"];
		}

		$fileoption_support = "";
		if(isset($param["fileoption"]["support"]) && $param["fileoption"]["support"] != null) {
			$fileoption_support = $param["fileoption"]["support"];
		}

		$imgoption_support = "";
		if(isset($param["imgoption"]["support"]) && $param["imgoption"]["support"] != null) {
			$imgoption_support = $param["imgoption"]["support"];
			$fileoption_support = $param["imgoption"]["support"];
		}

		$inputoption = "";
		if(isset($param["inputoption"]) && $param["inputoption"] != null) {
			$inputoption = $param["inputoption"];
		}
		$default_color = "";
		if ($inputoption == "icolor") {
			if ((isset($param["default"]))){
				$default_color = $param["default"];
			}
		}

		$image_width_height = "";
		$image_option = "";
		if(isset($param["imgoption"]["mode"]) && $param["imgoption"]["mode"] != null) {
			$image_option = $param["imgoption"]["mode"];
			$tmp1 = explode(',', $param["imgoption"]["mode"]);
			if (count($tmp1) >=3) {
				$image_width_height = $tmp1[1] . "," . $tmp1[2];
			} else if (count($tmp1) == 2) {
				$image_width_height = $tmp1[0] . "," . $tmp1[1];
			} else {
				$image_width_height = $tmp1[0];
			}
		}

		$hour_step = 1;
		$minute_step = 15;

		if(isset($param["hour_step"]) && $param["hour_step"] != null){
			$hour_step = $param["hour_step"];
		}
		if(isset($param["minute_step"]) && $param["minute_step"] != null){
			$minute_step = $param["minute_step"];
		}

		$lookup_field = false;
		if(isset($param["lookup"]) && $param["lookup"] != null){
			$lookup_field = true;
		}
		//--------------------------//

		$hintword = OMTemplateWCM::hintWord($field_type, $label, $min, $max, $hint, $fileoption_support, $image_width_height . "|:|" . $image_option , $lookup_field);
		if ($modify_permission == false){
			$hintword = "";
			$example = "";
		}
		$common_attr = "";

		$common_attr = "class=\"" . $field_type . "\" id=\"fi_" . $id . "\" name=\"fi_" . $id . "\" ";
		$common_attr_verify = "class=\"" . $field_type . "\" id=\"fi_" . $id . "_verify\" name=\"fi_" . $id . "_verify\" ";

		if ($inputoption == "icolor") {
			$common_attr = "class=\"" . $field_type . " input_icolor\" id=\"fi_" . $id . "\" name=\"fi_" . $id . "\" ";
		}

		$ret = "";
		$ret_more = "";
		$required_string = "";

		if($required == "T"){ $required_string = "<img src=\"../core/images/form/required.gif\" alt=\"required\" />"; }

		$ret .= "<tr class=\"row\" id=\"row_" . $id . "\" onmouseover=\"showHint(this)\" onmouseout=\"hideHint(this)\">";
		if($field_type == "fulltext" ){
			$ret .= "	<td colspan=\"3\" class=\"wysiwyg\">";
		} else if ($field_type == "moretext" && $inputoption == "bigarea") {
			$ret .= "	<td colspan=\"3\" >";
		} else {
			$ret .= "	<td class=\"label\">" . $required_string . "&nbsp;" . htmlspecialchars($label) . ":</td>";
			$ret .= "	<td class=\"collection\">";
		}

		if($lookup_field) {
			$selected_txt = "";
			$lookup_mode = "";
			if (isset($param["lookup"]["mode"] ) && $param["lookup"]["mode"] != null){
				$lookup_mode = $param["lookup"]["mode"];
			}
			$lookup_input = "";
			if (isset($param["lookup"]["input"]) && $param["lookup"]["input"] != null){
				$lookup_input = $param["lookup"]["input"];
			}

			if ($lookup_mode == "static" && isset($param["lookup"]["options"]) && $param["lookup"]["options"] != null){
				if ($lookup_input == "radio"){
					foreach($param["lookup"]["options"]["option"] as $option_key=>$option_value){
						if ($modify_permission == true){
							if ($set_value == $option_value["value"]){
								$selected_txt = " checked=\"checked\" ";
							} else {
								$selected_txt = " ";
							}
							$ret .= "<input type=\"radio\" id=\"fi_" . $id . "_" . $option_key . "\" name=\"fi_" . $id . "\" value=\"" . htmlspecialchars($option_value["value"]) . "\" " . $selected_txt . "/><label for=\"fi_" . $id . "_" . $option_key . "\">" . htmlspecialchars($option_value["label"]) . "</label>&nbsp;&nbsp;";
						} else {
							if ($set_value == $option_value["value"]){
								$ret .= htmlspecialchars($option_value["label"]);
							}
						}
					}
				} else if ($lookup_input == "dropdown"){
					if ($modify_permission == true){
						$ret .= "<select " . $common_attr . ">";
						$ret .= "<option value=\"\"> -- Please select -- </option>";
					}
					foreach($param["lookup"]["options"]["option"] as $option_key=>$option_value ){
						if ($modify_permission == true){
							if ($set_value == $option_value["value"]){
								$selected_txt = " selected ";
							} else {
								$selected_txt = " ";
							}
							$ret .= "<option value=\"" . htmlspecialchars($option_value["value"]) . "\" " . $selected_txt . ">" . htmlspecialchars(OMStringUtils::trimWithDotPixel($option_value["label"],450)) . "</option>";
						} else {
							if ($set_value == $option_value["value"]){
								$ret .= htmlspecialchars($option_value["label"]);
							}
						}
					}
					if ($modify_permission == true){
						$ret .= "</select>";
					}
				}
			} else if ($lookup_mode == "dynamic" && isset($param["lookup"]["dbsource"]) && $param["lookup"]["dbsource"] != null){
				if ($lookup_input == "dropdown"){
					$dbsource = $param["lookup"]["dbsource"];
					$sql_param = array();
					$dt=null;
					$sql_string = "";
					if ($dbsource["type"] == "internal"){
						$sql_string = "select " . $dbsource["field_value"] . " as field_value ," . $dbsource["field_label"] . " as field_label from " . $dbsource["tblname"] . "_draft where obj_status = @obj_status and ( obj_state = @obj_state or obj_state = @obj_state2 )";

						if ($filter_lang != ""){
							$sql_string .= " and obj_lang = @obj_lang ";
							$sql_param["@obj_lang"] = $filter_lang;
						}

						if (isset($dbsource["nopreload"]) && $dbsource["nopreload"] == 'T'){
							$sql_string .= " and " . $dbsource["field_value"] . " = @set_value ";
							$sql_param["@set_value"] = $set_value;
						}
						if (isset($dbsource["whereby"]) && $dbsource["whereby"] != null){
							$sql_string .= ' and (' . $dbsource["whereby"] . ') ';
						}
						if ($dbsource["sortby"] != null){
							$sql_string .= " order by " . $dbsource["sortby"];
						}
						$sql_string = str_replace('[[tblname]]', $dbsource["tblname"] . "_draft", $sql_string);
						$sql_param["@obj_status"] = "active";
						$sql_param["@obj_state2"] = "published_ch";
						$sql_param["@obj_state"] = "published";

					} else if ($dbsource["type"] == "external"){
						$sql_string = "select " . $dbsource["field_value"] . " as field_value ," . $dbsource["field_label"] . " as field_label from " . $dbsource["tblname"]; // . " where " . dbsource["field_condition"];
						if ($dbsource["whereby"] != null){
							$sql_string .= ' where (' . $dbsource["whereby"] . ') ';
						}
						if ($dbsource["sortby"] != null){
							$sql_string .= " order by " . $dbsource["sortby"];
						}
						$sql_string = str_replace('[[tblname]]', $dbsource["tblname"], $sql_string);
						if (isset($dbsource["nopreload"]) && $dbsource["nopreload"] == 'T'){
							$sql_string = "";
						}
					}
					if ($sql_string != "") {
						$rs = $DB->query($dt, $sql_string, $sql_param);
					} else {
						$rs = 0;
						$dt = array();
					}
					//$ret .= "SQL = " . $DB->LastSQLQueryString;
					$field_value = "";
					$field_label = "";
					if ($rs >= 0){
						if ($modify_permission == true){
							$ret .= "<select " . $common_attr . ">";
							$ret .= "<option value=\"\"> -- Please select -- </option>";
						}
						for ($k = 0 ; $k < $rs ; $k++){
							$field_label = $DB->getString($dt,$k,"field_label");
							$field_value = $DB->getString($dt,$k,"field_value");
							if ($modify_permission == true){
								if ($set_value == $field_value ){
									$selected_txt = " selected=\"selected\" ";
								} else {
									$selected_txt = " ";
								}

								$ret .= "<option value=\"" . htmlspecialchars($field_value) . "\" " . $selected_txt . ">" . htmlspecialchars(OMStringUtils::trimWithDotPixel($field_label,450)) . "</option>";
							} else {
								if ($set_value == $field_value ){
									$ret .= htmlspecialchars($field_label);
								}
							}
						}
						if ($modify_permission == true){
							$ret .= "</select>";
						}
					}
				} else if ($lookup_input == "custom_list") {
					$dbsource = $param["lookup"]["dbsource"];
					$sql_param = array();
					$dt=null;
					$sql_string = "";
					if ($dbsource["type"] == "internal"){
						$sql_string = "select " . $dbsource["field_value"] . " as field_value , ";
						$sql_string .=  $dbsource["field_label"] . " as field_label ";
						$sql_string .= " from " . $dbsource["tblname"] . "_draft ";
						$sql_string .= " where obj_status = @obj_status and ( obj_state = @obj_state or obj_state = @obj_state2 ) ";
						$sql_string .= " and " . $dbsource["field_value"] . " = @field_value ";

						if ($filter_lang != ""){
							$sql_string .= " and obj_lang = @obj_lang ";
							$sql_param["@obj_lang"] = $filter_lang;
						}

						if ($dbsource["sortby"] != null){
							$sql_string .= " order by " . $dbsource["sortby"];
						}

						$sql_param["@field_value"] = $set_value;
						$sql_param["@obj_status"] = "active";
						$sql_param["@obj_state2"] = "published_ch";
						$sql_param["@obj_state"] = "published";

					} else if ($dbsource["type"] == "external"){

					}
					$custom_list_field_label = "";
					$rs = $DB->query($dt, $sql_string, $sql_param);
					if ($rs > 0){
						$custom_list_field_label = $DB->getString($dt, 0, "field_label");
					}
					if ($modify_permission == true){
						$common_attr = "class=\"" . $field_type . " custom_list\" id=\"fi_" . $id . "\" name=\"fi_" . $id . "\" ";
						$ret .= "<input type=\"hidden\" " . $common_attr . " value=\"" . $set_value . "\" style=\"width: 250px;\" />";

						$common_attr = "class=\"" . $field_type . " custom_list\" id=\"fi_" . $id . "_value\" name=\"fi_" . $id . "_value\" onClick=\"openCustomList('" . $id . "','fi_" . $id . "_value','fi_" . $id . "','" . htmlspecialchars($label) . "'); \" ";
						$ret .= "<input type=\"text\" " . $common_attr . " value=\"" . $custom_list_field_label . "\" readonly=\"readonly\" style=\"width: 250px;\" />";
						$ret .= "<a class=\"custom_list-clear\" onclick=\"clearCustomListInput('fi_" . $id . "')\"></a>";
					} else {
						$ret .=  htmlspecialchars($custom_list_field_label) ;
					}
				} else if ($lookup_input == "finder"){
					$dbsource = $param["lookup"]["dbsource"];
					$sql_param = array();
					$dt=null;
					$sql_string = "";
					if ($dbsource["type"] == "internal"){
						$sql_string = "select " . $dbsource["field_value"] . " as field_value , ";
						$sql_string .=  $dbsource["field_label"] . " as field_label  ,";
						$sql_string .=  $dbsource["field_referer"] . " as field_referer ";
						$sql_string .= " from " . $dbsource["tblname"] . "_draft ";
						$sql_string .= " where obj_status = @obj_status and ( obj_state = @obj_state or obj_state = @obj_state2 ) ";
						$sql_string .= " and " . $dbsource["field_value"] . " = @field_value ";

						if ($filter_lang != ""){
							$sql_string .= " and obj_lang = @obj_lang ";
							$sql_param["@obj_lang"] = $filter_lang;
						}

						if ($dbsource["sortby"] != null){
							$sql_string .= " order by " . $dbsource["sortby"];
						}

						$sql_param["@field_value"] = $set_value;
						$sql_param["@obj_status"] = "active";
						$sql_param["@obj_state2"] = "published_ch";
						$sql_param["@obj_state"] = "published";

					} else if ($dbsource["type"] == "external"){

					}
					$finder_field_label = "";
					$rs = $DB->query($dt, $sql_string, $sql_param);
					if ($rs > 0){
						$finder_field_label = $DB->getString($dt, 0, "field_label");
					}
					if ($modify_permission == true){
						$common_attr = "class=\"" . $field_type . " finder\" id=\"fi_" . $id . "\" name=\"fi_" . $id . "\" ";
						$ret .= "<input type=\"hidden\" " . $common_attr . " value=\"" . $set_value . "\" style=\"width: 250px;\" />";

						$common_attr = "class=\"" . $field_type . " finder\" id=\"fi_" . $id . "_value\" name=\"fi_" . $id . "_value\" onClick=\"newFinder('" . $id . "','fi_" . $id . "_value','fi_" . $id . "','" . htmlspecialchars($label) . "'); \" ";
						$ret .= "<input type=\"text\" " . $common_attr . " value=\"" . $finder_field_label . "\" readonly=\"readonly\" style=\"width: 250px;\" />";
						$ret .= "<a class=\"finder-clear\" onclick=\"clearFinderInput('fi_" . $id . "')\"></a>";
					} else {
						$ret .=  htmlspecialchars($finder_field_label) ;
					}
				}
			}
		} else if($field_type == "text") {
			$set_value = htmlspecialchars($set_value);
			if ($inputoption == "icolor") {
				$ret .= "<div class='wrap_icolor'>";
				if ($set_value == "" && $default_color != "") {
					$set_value = $default_color;
				}
			}
			if ($modify_permission == true){
				$field_width = $max * $pixel_per_char;
				if($field_width > 280) { $field_width = 280; }
				$type_mode = "type=\"text\"";
				if ($inputoption == "password" || $inputoption == "password_verify") {
					$type_mode = "type=\"password\"";
					$set_value = "";
					$field_width = 280;
				}
				$ret .= "<input " . $type_mode . " " . $common_attr . " value=\"" . $set_value . "\" maxlength=\"" . $max . "\" style=\"width: " . $field_width . "px;\" />";
				if ($inputoption == "password_verify") {
					$ret_more .= "<tr class=\"row\" id=\"row_" . $id . "_verify\" onmouseover=\"showHint($('#row_" . $id . "').get(0))\" onmouseout=\"hideHint($('#row_" . $id . "').get(0))\">";
					$ret_more .= "	<td class=\"label\">" . $required_string . "&nbsp;Confirm " . htmlspecialchars(strtolower($label)) . ":</td>";
					$ret_more .= "	<td class=\"collection\">";

					$ret_more .= "<input " . $type_mode . " " . $common_attr_verify . " value=\"" . $set_value . "\" maxlength=\"" . $max . "\" style=\"width: " . $field_width . "px;\" />";

					$ret_more .= "</td></tr>\r\n";
				}
			} else {
				$ret .= $set_value;
			}
			if ($inputoption == "icolor") {
				$ret .= "</div>";
			}
		} else if($field_type == "number") {
			if ($modify_permission == true){
				$maxlength = strlen($max);
				$field_width = $maxlength * $pixel_per_char;
				$ret .= "<input type=\"text\" " . $common_attr . " value=\"" . $set_value . "\" maxlength=\"" . $maxlength . "\" style=\"width: " . $field_width . "px;\" />";
			} else {
				$ret .= $set_value;
			}

		} else if ($field_type == "decimal") {
			if ($modify_permission == true){
				$maxlength = strlen($max) + $digit + 1;
				$field_width = $maxlength * $pixel_per_char;
				$ret .= "<input type=\"text\" " . $common_attr . " value=\"" . $set_value . "\" maxlength=\"" . $maxlength . "\" style=\"width: " . $field_width . "px;\" />";
			} else {
				$ret .= $set_value;
			}

		} else if ($field_type == "checkbox") {
			if ($modify_permission == true){
				$check_str = "";
				if ($set_value == "T") { $check_str = "checked"; }
				$ret .= "<input type=\"checkbox\" " . $common_attr . " value=\"T\" " . $check_str . " /><label for=\"fi_" . $id . "\">" . $checkbox_label . "</label>";
			} else {

				if(isset($param["list_value"]) && $param["list_value"] != null) {
					$checkbox_list_value_label = $param["list_value"];
					$checkbox_list_value = explode(',', $checkbox_list_value_label);
					if (count($checkbox_list_value) == 2) {
						$ret .= ($set_value == "T")? $checkbox_list_value[0]:$checkbox_list_value[1];
					} else {
						$ret .= ($set_value == "T")? $checkbox_label : '';
					}
				} else {
					$ret .= ($set_value == "T")? $checkbox_label : '';
				}

			}

		} else if ($field_type == "moretext") {
			$set_value = htmlspecialchars($set_value);
			if ($modify_permission == true){
				$rows = "3";
				if($max > 300) {
					$rows = "5";
				} else if ($max > 240) {
					$rows = "4";
				}

				if ($inputoption == "bigarea") {
					$ret .= "<textarea cols=\"80\" rows=\"" . $rows . "\" " . $common_attr . " style=\"width:750px;height:400px;\">" . $set_value . "</textarea>";
				} else {
					$ret .= "<textarea " . $common_attr . " rows=\"" . $rows . "\" cols=\"80\" style=\"width:280px;\" >" . $set_value . "</textarea>";
				}
			} else {
				$ret .= $set_value;
			}

		} else if ($field_type == "autocomplete") {

		} else if (($field_type == "time") || ($field_type == "date") || ($field_type == "datetime")) {

			$id_split = "";
			$set_value_time=array();
			$set_value_hr = 0;
			$set_value_mn = 0;
			$set_value_date = "";

			if (($field_type == "date") || ($field_type == "datetime")) {
				$dtc = new OMDateTimeConverter();
				$set_value_dt_tmp = OMDateTime::Now();
				if ($set_value_om[$id] != null) {
					$set_value_dt_tmp = $set_value_om[$id];
				}
				$set_value_date = $dtc->toString($set_value_dt_tmp, "dd/MM/yyyy", WCMSetting::$CULTUREINFO_FORMAT);
				$set_value = $dtc->toString($set_value_dt_tmp, "HH:mm", "en-US");
			}
			if (($field_type == "time") || ($field_type == "datetime")){
				if ($set_value == "" || strpos(':', $set_value) == -1){
					$set_value = "00:00";
				}
				$set_value_time = explode(':', $set_value);
				$set_value_hr = intval($set_value_time[0]);
				$set_value_mn = intval($set_value_time[1]);
			}
			$maxlength = 10;
			$field_width = $maxlength * $pixel_per_char;

			$common_attr = " class=\"" . $field_type . " datepicker\" ";

			$show_date = "";
			$show_time = "";
			$i=0;

			if ($field_type == "datetime" || $field_type == "time") {
				if ($modify_permission == true){
					$id_split = "id=\"fi_" . $id . "_hr\" name=\"fi_" . $id . "_hr\"";

					$show_time .= "<div class=\"fl\"><img src=\"../core/images/form/clock.gif\" alt=\"\" /></div><div class=\"fl\"><select " . $id_split . $common_attr . ">";
					for($i = 0; $i < 24; $i += $hour_step){
						$show_time .= "<option value=\"" . str_pad($i, 2, '0', STR_PAD_LEFT) . "\"";
						if($i == $set_value_hr) { $show_time .= " selected=\"selected\""; }
						$show_time .= ">" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>";
					}
					$show_time .= "</select>";

					$id_split = "id=\"fi_" . $id . "_mn\" name=\"fi_" . $id . "_mn\"";
					$show_time .= "<select " . $id_split  . $common_attr . ">";
					for($i = 0; $i < 60; $i += $minute_step){
						$show_time .= "<option value=\"" . str_pad($i, 2, '0', STR_PAD_LEFT) . "\"";
						if ($i == $set_value_mn) { $show_time .= " selected=\"selected\""; }
						$show_time .= ">" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>";
					}
					$show_time .= "</select></div>";
				} else {
					$show_time = str_pad($set_value_hr, 2, '0', STR_PAD_LEFT) . ":" . str_pad($set_value_mn ,2 ,'0', STR_PAD_LEFT);
				}
			}

			if ($field_type == "datetime" || $field_type == "date") {
				if ($modify_permission == true){
					$id_split = "id=\"fi_" . $id . "_dmy\" name=\"fi_" . $id . "_dmy\"";
					$show_date = "<div class=\"fl\"><img src=\"../core/images/form/calendar.gif\" alt=\"\" style=\"cursor:pointer;\" onclick=\"$(this).parent().parent().find('input').focus();\"/></div><div class=\"fl\"><input type=\"text\" " . $id_split . $common_attr . " value=\"" . $set_value_date . "\" style=\"width:" . $field_width . "px;\" maxlength=\"" . $maxlength . "\" /></div>";
				} else {
					$show_date = $set_value_date;
				}
			}

			$space = "";
			if ($field_type == "datetime") {
				if ($modify_permission){
					$space = "<div class=\"fl\">&nbsp;</div>";
				} else {
					$space = "&nbsp;";
				}
			}
			$ret .= $show_date . $space . $show_time;

		} else if ($field_type == "fulltext") {
			$fulltext_config = array();
			if (isset($bgcolor)) {
				$fulltext_config["bgcolor"] = $bgcolor;
			}
			if (isset($css)) {
				$fulltext_config["css"] = $css;
			}
			$ret .= "<div class=\"fulltext_config\" style=\"display:none;\">" . json_encode($fulltext_config) . "</div>";
			if ($modify_permission == true){
				$ret .= "<textarea cols=\"80\" rows=\"4\" " . $common_attr . " style=\"width:750px;height:400px;\">" . $set_value . "</textarea>";
			} else {
				$ret .= "<textarea cols=\"80\" rows=\"4\" " . $common_attr . " style=\"width:750px;height:400px;\" readonly>" . $set_value . "</textarea>";
			}

		} else if ($field_type == "image") {
			//$ret .= '<div style="background-color:red;padding:20px;">' . json_encode($param) . '</div>';
			$set_value_dt_tmp = OMStringUtils::trim($set_value_om[$id]);
			$set_value_dt_tmp_gen = OMStringUtils::trim($set_value_om[$id . "_gen"]);
			if ($modify_permission == true){
				$state_show = "";
				$state_show = ($set_value_dt_tmp_gen == "")?"blank":"preview";
				$ret .= "<iframe frameborder=\"0\" class=\"iframeupload\" src=\"form_fileupload.php?id=" . $id . "&amp;f=" . $required . "&amp;st=" . $imgoption_support . "&amp;s=" . $state_show . "\" scrolling=\"no\"></iframe>\r\n";
				$image_mode  = "";
				$arr_image_option = explode(",",$image_option);
				if ($arr_image_option[0] == "dynamic") {
					if (count($arr_image_option) == 3) {
						$image_mode  = $image_option;
					} else if (count($arr_image_option) == 2) {
						$image_option .= ",500";
					}
				}
				$ret .= "<input type=\"hidden\" class=\"" . $field_type . " file_image_mode\" id=\"fi_" . $id . "_image_mode\" name=\"fi_" . $id . "_image_mode\" value=\"" . $image_mode . "\" />\r\n";
				$ret .= "<input type=\"hidden\" class=\"" . $field_type . " file_id\" id=\"fi_" . $id . "_id\" name=\"fi_" . $id . "_id\" value=\"\" />\r\n";
				$ret .= "<input type=\"hidden\" class=\"" . $field_type . " file_name\" id=\"fi_" . $id . "\" name=\"fi_" . $id . "\" value=\"" . $set_value_dt_tmp . "\" />\r\n";
				$ret .= "<input type=\"hidden\" class=\"" . $field_type . " file_gen\" id=\"fi_" . $id . "_gen\" name=\"fi_" . $id . "_gen\" value=\"" . $set_value_dt_tmp_gen . "\" />\r\n";
				$ret .= "<input type=\"hidden\" class=\"" . $field_type . " file_name_current\" id=\"fi_" . $id . "_current\" name=\"fi_" . $id . "_current\" value=\"" . $set_value_dt_tmp . "\" />\r\n";
				$ret .= "<input type=\"hidden\" class=\"" . $field_type . " file_gen_current\" id=\"fi_" . $id . "_gen_current\" name=\"fi_" . $id . "_gen_current\" value=\"" . $set_value_dt_tmp_gen . "\" />\r\n";
				$ret .= "<div class=\"loading\">\r\n";
				$ret .= "	<div class=\"fl\"><img src=\"../core/images/form/loading2.gif\" alt=\"loading\" /></div>\r\n";
				$ret .= "</div>\r\n";
				$ret .= "<div class=\"uploadprogress\" style=\"display:none;\">\r\n";
				$ret .= "	<div class=\"name fl\"></div>\r\n";
				$ret .= "	<div class=\"meter fl\"><img src=\"../core/images/form/loading.gif\" alt=\"loading\" /></div>\r\n";
				$ret .= "	<div class=\"cancel fl\">(<a onclick=\"cancelUploading(this)\">Cancel</a>)</div>\r\n";
				$ret .= "</div>\r\n";
				$ret .= "<div class=\"uploadcomplete\" style=\"display:none;\">\r\n";
				$ret .= "	<div class=\"fl\"><img src=\"../core/images/form/imagemime.gif\" alt=\"file\" align=\"middle\" /></div>\r\n";
				$ret .= "	<div class=\"name fl\"></div>\r\n";

				$ret .= "	<div class=\"cancel fl\">\r\n";
				$ret .= "		(<a onclick=\"changeUploaded(this,false)\">Change</a>\r\n";
				if ($set_value_dt_tmp_gen != "")
				{
					$ret .= " <em>or</em> <a onclick=\"iframeLoadCompleted('" . $id . "','preview')\">Reset</a>)\r\n";
				} else {
					$ret .= ")";
				}
				$ret .= "	</div>\r\n";
				$ret .= "</div>\r\n";

				$ret .= "<div class=\"uploadpreview\" style=\"display:none;\">\r\n";
				if ($set_value_dt_tmp_gen != ""){
					$ret .= "	<img class=\"thumbnail\" src=\"" . WCMSetting::$WEB_BASE_URL . OMImage::readFileName($set_value_dt_tmp_gen, $set_value_dt_tmp,"d200x200",$module_name) . "\" alt=\"" . htmlspecialchars($set_value_dt_tmp) .  "\" align=\"middle\" /><br/>\r\n";
				} else {
					$ret .= "	<img class=\"thumbnail\" src=\"\" alt=\"\" align=\"middle\" /><br/>\r\n";
				}
				$ret .= "	<div class=\"cancel\">(<a onclick=\"changeUploaded(this,false)\">Change</a> or <a onclick=\"changeUploaded(this,true)\">Remove</a>)</div>\r\n";
				$ret .= "</div>\r\n";

			} else {
				if ($set_value_dt_tmp_gen != "") {
					$ret .= "<img class=\"thumbnail\" src=\"" . WCMSetting::$WEB_BASE_URL . OMImage::readFileName($set_value_dt_tmp_gen, $set_value_dt_tmp,"d200x200",$module_name) . "\" alt=\"" . htmlspecialchars($set_value_dt_tmp) .  "\" align=\"middle\" />\r\n";
				} else {
					$ret .= "<span style=\"color: #999999;\">[NO IMAGE]</span>";
				}
			}
		} else if ($field_type == "file") {
			$set_value_dt_tmp = OMStringUtils::trim($set_value_om[$id]);
			$set_value_dt_tmp_gen = OMStringUtils::trim($set_value_om[$id . "_gen"]);

			if ($modify_permission == true){
				$state_show = "";
				$state_show = ($set_value_dt_tmp_gen == "")?"blank":"preview";
				$ret .= "<iframe frameborder=\"0\" class=\"iframeupload\" src=\"form_fileupload.php?id=" . $id . "&amp;f=" . $required . "&amp;st=" . $fileoption_support . "&amp;s=" . $state_show . "\" scrolling=\"no\"></iframe>\r\n";
				$ret .= "<input type=\"hidden\" class=\"" . $field_type . " file_id\" id=\"fi_" . $id . "_id\" name=\"fi_" . $id . "_id\" value=\"\" />\r\n";
				$ret .= "<input type=\"hidden\" class=\"" . $field_type . " file_name\" id=\"fi_" . $id . "\" name=\"fi_" . $id . "\" value=\"" . $set_value_dt_tmp . "\" />\r\n";
				$ret .= "<input type=\"hidden\" class=\"" . $field_type . " file_gen\" id=\"fi_" . $id . "_gen\" name=\"fi_" . $id . "_gen\" value=\"" . $set_value_dt_tmp_gen . "\" />\r\n";
				$ret .= "<input type=\"hidden\" class=\"" . $field_type . " file_name_current\" id=\"fi_" . $id . "_current\" name=\"fi_" . $id . "_current\" value=\"" . $set_value_dt_tmp . "\" />\r\n";
				$ret .= "<input type=\"hidden\" class=\"" . $field_type . " file_gen_current\" id=\"fi_" . $id . "_gen_current\" name=\"fi_" . $id . "_gen_current\" value=\"" . $set_value_dt_tmp_gen . "\" />\r\n";
				$ret .= "<div class=\"loading\">";
				$ret .= "	<div class=\"fl\"><img src=\"../core/images/form/loading2.gif\" alt=\"loading\" /></div>\r\n";
				$ret .= "</div>";
				$ret .= "<div class=\"uploadprogress\" style=\"display:none;\">\r\n";
				$ret .= "	<div class=\"name fl\"></div>\r\n";
				$ret .= "	<div class=\"meter fl\"><img src=\"../core/images/form/loading.gif\" alt=\"loading\" /></div>\r\n";
				$ret .= "	<div class=\"cancel fl\">(<a onclick=\"cancelUploading(this)\">Cancel</a>)</div>\r\n";
				$ret .= "</div>";
				$ret .= "<div class=\"uploadcomplete\" style=\"display:none;\">\r\n";
				$ret .= "	<div class=\"fl\"><img src=\"../core/images/form/filemime.gif\" alt=\"file\" align=\"middle\" /></div>\r\n";
				$ret .= "	<div class=\"name fl\"></div>\r\n";

				$ret .= "	<div class=\"cancel fl\">\r\n";
				$ret .= "		(<a onclick=\"changeUploaded(this,false)\">Change</a>\r\n";
				if ($set_value_dt_tmp_gen != "") {
					$ret .= " <em>or</em> <a onclick=\"iframeLoadCompleted('" . $id . "','preview')\">Reset</a>)\r\n";
				} else {
					$ret .= ")";
				}
				$ret .= "	</div>\r\n";
				$ret .= "</div>\r\n";

				$ret .= "<div class=\"uploadpreview\" style=\"display:none;\">";
				if ($set_value_dt_tmp_gen != ""){
					$ret .= "<div class=\"fl\"><img src=\"../core/images/input_filemime.gif\" alt=\"file\" align=\"middle\" /></div>\r\n";
					$ret .= "<div class=\"name fl\"><a href=\"" . WCMSetting::$WEB_BASE_URL . OMImage::readFileName($set_value_dt_tmp_gen, $set_value_dt_tmp,"o0x0",$module_name) . "\" alt=\"" . htmlspecialchars($set_value_dt_tmp) .  "\" target=\"_blank\">" . htmlspecialchars($set_value_dt_tmp) . "</a></div>\r\n";
				}

				$ret .= "	<div class=\"cancel fl\">(<a onclick=\"changeUploaded(this,false)\">Change</a> or <a onclick=\"changeUploaded(this,true)\">Remove</a>)</div>\r\n";
				$ret .= "</div>";

			} else {
				if ($set_value_dt_tmp_gen != "") {
					$ret .= "<div class=\"fl\"><img src=\"../core/images/input_filemime.gif\" alt=\"file\" align=\"middle\" /></div>\r\n";
					$ret .= "<div class=\"name fl\"><a href=\"" . WCMSetting::$WEB_BASE_URL . OMImage::readFileName($set_value_dt_tmp_gen, $set_value_dt_tmp,"o0x0",$module_name) . "\" alt=\"" . htmlspecialchars($set_value_dt_tmp) .  "\" target=\"_blank\">" . htmlspecialchars($set_value_dt_tmp) . "</a></div>\r\n";
				} else {
					$ret .= "<span style=\"color: #999999;\">[NO FILE]</span>";
				}
			}
		}
		$ret .= "<div class=\"clearfix\"></div>";
		if ($field_type != "fulltext" && $inputoption != "bigarea") {
			$ret .= "<div class=\"example\">" . $example . "</div>";
			$ret .= "</td>";
			$ret .= "<td class=\"hint\">";
			if ($hintword != null && $hintword != "") {
				$ret .= "<div class=\"msg hidden\"><div>&#9668;</div><div style=\"padding-left:15px;\">" . $hintword . "</div>";
			}
			$ret .= "</div></td>";
		} else {
			$ret .= "</td>";
		}

		$ret .= "</tr>" . "\r\n";
		$ret .= $ret_more;
		return($ret);
	}
	static function hintWord($field_type, $field_name, $min, $max, $custom_hint, $type_support, $image_width_height, $is_lookup) {
		$image_option = explode("|:|",$image_width_height)[1];
		$image_width_height = explode("|:|",$image_width_height)[0];

		$ret;
		$i;

		if($custom_hint == "*" && !$is_lookup) {
			if($field_type == "text" || $field_type == "moretext") {
				if($min == $max && $max == 0) {
					$ret = "\"" . $field_name . "\" can be any length.";
				} else if ( $min == $max ) {
					$ret = "\"" . $field_name . "\" must be " . $min . " characters long.";
				} else {
					$ret = "\"" . $field_name . "\" must be between " . $min . " and " . $max . " characters long.";
				}
			} else if ($field_type == "number") {
				$ret = "\"" . $field_name . "\" must be an integer which between " . OMStringUtils::formatNumber($min,0)  . " and " . OMStringUtils::formatNumber($max,0) . ".";
			} else if ($field_type == "decimal") {
				$ret = "\"" . $field_name . "\" must be a real number which between " . OMStringUtils::formatNumber($min) . " and " . OMStringUtils::formatNumber($max) . ".";
			} else if ($field_type == "image" || $field_type == "file") {

				$type_support_array = explode(',', $type_support);
				$ret = "\"" . $field_name . "\"";
				if($type_support == "") {
					$ret .= " supports all";
				} else if (count($type_support_array) <= 1) {
					$ret .= " only supports " . strtoupper($type_support);
				} else {
					$ret .= " supports ";
					for($i = 0; $i < count($type_support_array); $i++) {
						$ret .= strtoupper($type_support_array[$i]);
						if ($i < (count($type_support_array) - 2)) {
						 	$ret .= ", ";
						} else if ($i == (count($type_support_array) - 2)) {
							$ret .= " and ";
						}
					}
				}
				if($field_type == "image") {
					$arr_image_option = explode(",",$image_option);
					if ($arr_image_option[0] == "dynamic") {
						$ret .= " file format.";
					} else {
						$image_width_height_array = explode(',', $image_width_height);
						$ret .= " file format. Native size is " . @$image_width_height_array[0] . "x" . @$image_width_height_array[1] . " pixels.";
					}
				} else {
					$ret .= " file format.";
				}
			} else {
				$ret = "";
			}
		} else {
			if($is_lookup && $custom_hint == "*") {
				$ret = "";
			} else {
				$ret = $custom_hint;
			}
		}
		return($ret);
	}
}
?>