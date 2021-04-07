<?php


$DISPLAY_TEXT = array();

/*------------------ FORMAT ------------------*/
/* 											  */
/* $DISPLAY_TEXT[KEY][LANG][SUBKEY] = VALUE;  */
/*											  */
/*--------------------------------------------*/

$DISPLAY_TEXT["GLOBAL"]["TH"]["DELETE_MODAL_BODY"]= "Do you want to delete this campaign";
$DISPLAY_TEXT["GLOBAL"]["EN"]["DELETE_MODAL_BODY"] = "Do you want to delete this campaign";

$DISPLAY_TEXT["GLOBAL"]["TH"]["DELETE_MODAL_HEADER"]= "Are you sure?";
$DISPLAY_TEXT["GLOBAL"]["EN"]["DELETE_MODAL_HEADER"] = "Are you sure?";



//--- CAMPAIGN_DETAIL ---//

$DISPLAY_TEXT["CAMPAIGN_DETAIL"]["TH"]["CHANGE_KEY"]= "If you submit change event key, your old event key will revorked immediately.<br>Do you still want to do this?";
$DISPLAY_TEXT["CAMPAIGN_DETAIL"]["EN"]["CHANGE_KEY"] = "If you submit change event key, your old event key will revorked immediately.<br>Do you still want to do this?";

$DISPLAY_TEXT["CAMPAIGN_DETAIL"]["TH"]["HEAD_MODAL_CHANGE_KEY"]= "Do you want to request a new Event key?";
$DISPLAY_TEXT["CAMPAIGN_DETAIL"]["EN"]["HEAD_MODAL_CHANGE_KEY"] = "Do you want to request a new Event key?";



define("DISPLAY_TEXT", json_encode($DISPLAY_TEXT));


?>