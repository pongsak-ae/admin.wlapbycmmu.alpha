var _redirect_timeout = '../core/core_signin.php?refer=%2fwcm%2fmessages%2flist.php';

function loadListSuccess(obj) {
	// prepare field before drawTable (put the custom code here)
	return obj;
}

function getMultiSelectedList(){
	var selected_list = new Array();
	var tmpCheck = getItemChecked();

	for (var key in tmpCheck) {
		if (tmpCheck.hasOwnProperty(key)) {
			var ref_arr = tmpCheck[key].split(":");
			var obj = new Object();
			obj["id"] = ref_arr[0];
			obj["rev"] = ref_arr[1];
			obj["lang"] = ref_arr[2];
			obj["state"] = ref_arr[4]; 
			selected_list.push(obj);
		}
	}
	return selected_list;
}

function contextAction(action, el){
	var ref_arr = $(el).attr('ref').split(':');
	if(action == "checkall") {
		$(el).parent().find('tr').addClass("checked")
		.find('.choose input').attr('checked',true);
		updateItemChecked("check");
	} else if(action == "uncheckall") {
		$(el).parent().find('tr').removeClass("checked")
		.find('.choose input').attr('checked',false);
		updateItemChecked("uncheck");
	} else if(action == "edit") {
		window.location.href = "form.php?m=edit&id=" + ref_arr[0] + "&r=" + ref_arr[1] + "&l=" + ref_arr[2];
	} else if(action == "open") {
		window.location.href = "form.php?m=view&id=" + ref_arr[0] + "&r=" + ref_arr[1] + "&l=" + ref_arr[2];
	} else if(action == "delete") {
		OMWCMUtil.actionDeleteNoApproval(getMultiSelectedList());

	} else {
		// do nothing
		// alert('Action: ' + action + '\n\n' + 'Element ID: ' + $(el).attr('ref') + '\n\n');	
	}
	return false;
}

function contextMenuPopup(menu, el, num_row, num_checked_row) {
	if(num_checked_row > 1) { // multi selected
		var suffix_command = " " + num_checked_row + " Items...";
		$(menu).find('li.open').addClass('hide');
		$(menu).find('li.edit').addClass('hide');
		$(menu).find('li.delete').removeClass('hide');
		$(menu).find('li.delete a').html("Delete" + suffix_command);
	} else {
		$(menu).find('li.open').removeClass('hide');
		$(menu).find('li.edit').removeClass('hide');
		$(menu).find('li.delete').removeClass('hide');
		$(menu).find('li.delete a').html("Delete");
	}

	return true;
}

OMWCMUtil = {
	getItemListString: function(item_list) {
		var slist="";
		for(var idx in item_list) {
			if (slist != "") slist +=",";
			slist += item_list[idx].id + "," + item_list[idx].lang + "," + item_list[idx].rev;
		}	
		return slist;
	},
	actionPublish: function(item_list, confirm) {
		var total_item = item_list.length;
		var total_actionable = 0;
		var actionable_list = [];		
		for(var idx in item_list) {
			if (item_list[idx].state == "draft" || item_list[idx].state == "published_ch") {
				actionable_list[actionable_list.length] = item_list[idx];
			}			
		}
		total_actionable = actionable_list.length;
		var dlg_msg = "";
		if (confirm == null) {
			if (total_item != total_actionable) {
				dlg_msg = "<strong>Only " + total_actionable + " of " + total_item + " in selected items which can be published.</strong><br/><br/>Do you want to continue?";
			} else {
				if(total_actionable == 1) {
					dlg_msg = "<strong>Are you sure you want to publish the selected item?</strong>";
				} else {
					dlg_msg = "<strong>Are you sure you want to publish the " + total_actionable + " selected items?</strong>";
				}
			}			
			Dialog("Confirmation",
					dlg_msg,
					{
						"Continue":function(){							
								destroyDialog();
								OMWCMUtil.actionPublish(actionable_list, true);
							},
						"Cancel":close
					});
		} else {
			drawLoading("list_data");
			$.ajax({
				url: 'list_cmd.php',
				data: {'c':'publish', 'i': OMWCMUtil.getItemListString(actionable_list)},
				type: 'POST', cache: false, timeout: 10000,
				dataType: 'json',
				error: function(){
					Dialog("Error","ERROR",{"OK":close});
				},
				success: function(rs){
					if(rs.c == "OK"){
						loadList("list_data");
					} else if (rs.c == "WAR") {
						loadList("list_data");
					} else {
						if (rs.r != null && rs.r != undefined && rs.r.detail != null && rs.r.detail != undefined) {
							Dialog("Error",rs.r.detail,{"OK":close});
						} else {
							Dialog("Error","ERR-" + rs.e + ": " + rs.m,{"OK":close});
						}
						return false;
					}
				}
			});
		}
	},
	actionDelete: function(item_list, confirm) {
		var total_item = item_list.length;
		var total_actionable = 0;
		var actionable_list = [];		
		for(var idx in item_list) {
			if (item_list[idx].state == "draft" || item_list[idx].state == "published_ch") {
				actionable_list[actionable_list.length] = item_list[idx];
			}			
		}
		total_actionable = actionable_list.length;
		var dlg_msg = "";
		if (confirm == null) {
			if (total_item != total_actionable) {
				dlg_msg = "<strong>Only \"Draft\" can be deleted immediately. Found " + total_actionable + " of " + total_item + " in selected items which can be deleted.</strong><br/><br/>Do you want to continue? This cannot be undone.";
			} else {
				if(total_actionable == 1) {
					dlg_msg = "<strong>Are you sure you want to delete the selected item?</strong><br/><br/>This cannot be undone.";
				} else {
					dlg_msg = "<strong>Are you sure you want to delete the " + total_actionable + " selected items?</strong><br/><br/>This cannot be undone.";
				}
			}
			Dialog("Confirmation",
					dlg_msg,
					{
						"Continue":function(){							
								destroyDialog();
								OMWCMUtil.actionDelete(actionable_list, true);
							},
						"Cancel":close
					});
		} else {
			drawLoading("list_data");
			$.ajax({
				url: 'list_cmd.php',
				data: {'c':'delete', 'i': OMWCMUtil.getItemListString(actionable_list)},
				type: 'POST', cache: false, timeout: 10000,
				dataType: 'json',
				error: function(){
					Dialog("Error","ERROR",{"OK":close});
				},
				success: function(rs){
					if(rs.c == "OK"){
						loadList("list_data");
					} else if (rs.c == "WAR") {
						loadList("list_data");
					} else {
						if (rs.r != null && rs.r != undefined && rs.r.detail != null && rs.r.detail != undefined) {
							Dialog("Error",rs.r.detail,{"OK":close});
						} else {
							Dialog("Error","ERR-" + rs.e + ": " + rs.m,{"OK":close});
						}
						return false;
					}
				}
			});
		}
	},
	actionDeleteNoApproval: function(item_list, confirm) {
		var total_item = item_list.length;
		var total_actionable = 0;
		var actionable_list = [];
		actionable_list = item_list;
		total_actionable = actionable_list.length;
		var dlg_msg = "";
		if (confirm == null) {
			if (total_item != total_actionable) {
				dlg_msg = "<strong>Only \"Draft\" can be deleted immediately. Found " + total_actionable + " of " + total_item + " in selected items which can be deleted.</strong><br/><br/>Do you want to continue? This cannot be undone.";
			} else {
				if(total_actionable == 1) {
					dlg_msg = "<strong>Are you sure you want to delete the selected item?</strong><br/><br/>This cannot be undone.";
				} else {
					dlg_msg = "<strong>Are you sure you want to delete the " + total_actionable + " selected items?</strong><br/><br/>This cannot be undone.";
				}
			}
			Dialog("Confirmation",
					dlg_msg,
					{
						"Continue":function(){							
								destroyDialog();
								OMWCMUtil.actionDeleteNoApproval(actionable_list, true);
							},
						"Cancel":close
					});
		} else {
			drawLoading("list_data");
			$.ajax({
				url: 'list_cmd.php',
				data: {'c':'delete_noapproval', 'i': OMWCMUtil.getItemListString(actionable_list)},
				type: 'POST', cache: false, timeout: 10000,
				dataType: 'json',
				error: function(){
					Dialog("Error","ERROR",{"OK":close});
				},
				success: function(rs){
					if(rs.c == "OK"){
						loadList("list_data");
					} else if (rs.c == "WAR") {
						loadList("list_data");
					} else {
						if (rs.r != null && rs.r != undefined && rs.r.detail != null && rs.r.detail != undefined) {
							Dialog("Error",rs.r.detail,{"OK":close});
						} else {
							Dialog("Error","ERR-" + rs.e + ": " + rs.m,{"OK":close});
						}
						return false;
					}
				}
			});
		}
	},	
	actionUnpublish: function(item_list, save_draft) {
		var total_item = item_list.length;
		var total_actionable = 0;
		var actionable_list = [];
		var num_published_ch = 0;
		for(var idx in item_list) {
			if (item_list[idx].state == "published" || item_list[idx].state == "published_ch") {
				actionable_list[actionable_list.length] = item_list[idx];
			}
			if(item_list[idx].state == "published_ch") { num_published_ch++ ;}		
		}
		total_actionable = actionable_list.length;
		var dlg_msg = "";
		if (save_draft == null) {
			if (total_item != total_actionable) {
				dlg_msg = "<strong>Only \"Published\" can be unpublished. Found " + total_actionable + " of " + total_item + " in selected items which can be unpublished.</strong><br/><br/>Which option do you want to continue with?<br/>";
			} else {
				if(total_actionable == 1) {
					dlg_msg = "<strong>Which option do you want to continue with the selected item?</strong><br/>";
				} else {
					dlg_msg = "<strong>Which option do you want to continue with the " + total_actionable + " selected items?</strong><br/>";
				}
			}
			if(num_published_ch > 0) {
				dlg_msg += "<input type=\"radio\" id=\"unpublish_op1\" name=\"unpublish_op\"  checked=\"checked\" /><label for=\"unpublish_op1\">Unpublish and keep the \"recent/modified\" version as draft.</label><br/>";
			}
			dlg_msg += "<input type=\"radio\" id=\"unpublish_op2\" name=\"unpublish_op\" ";
			if(num_published_ch <= 0) { dlg_msg += "checked=\"checked\" "; }
			dlg_msg += "/><label for=\"unpublish_op2\">Unpublish and keep the \"published\" version as draft.</label><br/>";
			dlg_msg += "<input type=\"radio\" id=\"unpublish_op3\" name=\"unpublish_op\" /><label for=\"unpublish_op3\">Unpublish and delete (This cannot be undone).</label>";

			Dialog("Confirmation",
					dlg_msg,
					{
						"Continue":function() {
								destroyDialog();
								var option = $(this).parent().parent().find('.main input:checked').attr("id");
								if(option == "unpublish_op1") {
									OMWCMUtil.actionUnpublish(actionable_list, "modified");
								} else if(option == "unpublish_op2") {
									OMWCMUtil.actionUnpublish(actionable_list, "published");
								} else if(option == "unpublish_op3") {
									OMWCMUtil.actionUnpublish(actionable_list, "yes");
								}
							},
						"Cancel":close
					});
		} else {
			drawLoading("list_data");
			var ajax_cmd = "";
			switch(save_draft) {
				case "yes":
					ajax_cmd = "unpublish";
					break;
				case "published":
					ajax_cmd = "unpublishwithdraft";
					break;
				case "modified":
					ajax_cmd = "unpublishwithrecent";
					break;
				default:
					ajax_cmd = "unpublishwithdraft";
					break;
			}
			$.ajax({
				url: 'list_cmd.php',
				data: {'c':ajax_cmd, 'i': OMWCMUtil.getItemListString(actionable_list)},
				type: 'POST', cache: false, timeout: 10000,
				dataType: 'json',
				error: function(){
					Dialog("Error","ERROR",{"OK":close});
				},
				success: function(rs){
					if(rs.c == "OK"){
						loadList("list_data");
					} else if (rs.c == "WAR") {
						loadList("list_data");
					} else {
						if (rs.r != null && rs.r != undefined && rs.r.detail != null && rs.r.detail != undefined) {
							Dialog("Error",rs.r.detail,{"OK":close});
						} else {
							Dialog("Error","ERR-" + rs.e + ": " + rs.m,{"OK":close});
						}
						return false;
					}
				}
			});
		}
	}
};