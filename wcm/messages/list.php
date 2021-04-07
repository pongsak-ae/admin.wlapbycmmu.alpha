<?php
	require_once('../core/lib/all.php');
	
	$SESSION->checkSession("redirect");
	$module_name = "wcm_message";
	$permission_prefix = "MESSAGES";

	$dtconv_datepicker = new OMDateTimeConverter("dd/MM/yyyy", WCMSetting::$CULTUREINFO_FORMAT);
	
	$folder = OMStringUtils::_TRIMGET("f");
	$menu_code = "";
	if($folder == "i") { $menu_code = "message_inbox"; }
	else if($folder == "s") { $menu_code = "message_sent"; }
	else if($folder == "t") { $menu_code = "message_trash"; }
	else { $menu_code = "message_inbox"; $folder = "i"; }

	$SESSION->CookieDb->setCookie($module_name, "folder", $folder);
	$date_sent_filter_label = "Sent date";
	if($folder == "i") { $date_sent_filter_label = "Received date"; }
	else if($folder == "s") { $date_sent_filter_label = "Sent date"; }
	else if($folder == "t") { $date_sent_filter_label = "Received/Sent date"; }
?>
<?=OMTemplateWCM::printHeader("Messages", $menu_code, "list_message")?>
<script type="text/javascript" src="../core/js/common_list.min.js"></script>
<script type="text/javascript" src="../core/js/common_child.min.js"></script>
<script type="text/javascript" src="list.min.js"></script>
<script type="text/javascript">
var filterField = [
["From","TEXT", "from_user_id"],
["To","TEXT", "to_user_id"],
["<?=$date_sent_filter_label?>","DATE", "sent_date"]];
var filterOperator = {	
"DATE":[["exactly","Is Exactly"],["before","Is Before"],["after","Is After"], null, "<?=$dtconv_datepicker->toString(OMDateTime::Now())?>"], 
"TEXT":[["contains","Contains"],["notcontain","Does not contain"],["beginswith","Begins with"],["endswith","Ends with"],["isequal","Is equal to"], null, ""]};

	$(document).ready(function() {
		loadList("list_data");
		_listcontrol.now_folder = "<?=$folder?>";
	});
</script>
<?php
	$ret = "";
	$ret .= OMTemplateWCM::printFilter($module_name);
	$ret .= "<div id=\"list_data\" style=\"display: block;\"></div>";
	$ret .= "<div class=\"list_instruction\"><strong>How to search or filter the item above?</strong><blockquote><ul>";
	$ret .= "<li>Use the &quot;Search box&quot; and click &quot;Seach&quot; button for quick search.</li>";
	$ret .= "<li>If you would need more sophisticated filters, click the &quot;+&quot; button on right of search toolbar and select the field which you want.</li>";
	$ret .= "<li>Click &quot;Search&quot; again to update the filtering to your result.</li>";
	$ret .= "<li>Moreover, you can save your search parameters by click &quot;Save&quot; button. And load it back from the &quot;&#9660;&quot; button near search label.</li>";
	$ret .= "</ul></blockquote></div>";
	echo $ret;
	
	$cmenu = "";
	$cmenu .= "<ul id=\"listcontextmenu\" class=\"contextMenu\">";
	$cmenu .= "    <li class=\"checkall\">";
	$cmenu .= "        <a href=\"#checkall\">Select All</a>";
	$cmenu .= "    </li>";
	$cmenu .= "    <li class=\"uncheckall\">";
	$cmenu .= "        <a href=\"#uncheckall\">Deselect All</a>";
	$cmenu .= "    </li>";
	$cmenu .= "    <li class=\"separator line1\"></li>";
	$cmenu .= "    <li class=\"open\">";
	$cmenu .= "        <a href=\"#open\">Open</a>";
	$cmenu .= "    </li>";
	$cmenu .= "    <li class=\"edit\">";
	$cmenu .= "        <a href=\"#reply\">Reply</a>";
	$cmenu .= "    </li>";
	$cmenu .= "    <li class=\"delete\">";
	$cmenu .= "        <a href=\"#delete\">Delete</a>";
	$cmenu .= "    </li>";
	$cmenu .= "    <li class=\"putback\">";
	$cmenu .= "        <a href=\"#putback\">Put Back</a>";
	$cmenu .= "    </li>";
	$cmenu .= "    <li class=\"separator line2\"></li>";
	$cmenu .= "    <li class=\"markasread\">";
	$cmenu .= "        <a href=\"#markasread\">Mark as Read</a>";
	$cmenu .= "    </li>";
	$cmenu .= "    <li class=\"markasunread\">";
	$cmenu .= "        <a href=\"#markasunread\">Mark as Unread</a>";
	$cmenu .= "    </li>";
	$cmenu .= "</ul>";

	echo $cmenu;
?>
<?=OMTemplateWCM::printFooter()?>