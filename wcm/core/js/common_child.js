var _listcontrol = new Object();
var _gridcontrol = new Object();
_listcontrol.fwd_checkbox_click = false;
_gridcontrol.lastchoose = -1;

function loadListChild(objname, child) {

	var lang = $("#fwdparam_lang").val();
	var id = $("#fwdparam_item_id").val();
	var rev;
	if ($("#val_recovery").size() == 1 && $("#val_recovery").val() != "") {
		rev = $("#val_recovery").val();
	} else {
		rev = $("#fwdparam_revision").val();
	}
	var tmp_session = $("#fwdparam_tmp_session").val();
	var form_mode = $("#fwdparam_mode").val();
	
	var sort_mode = _child[child].sm;
	var sort_field = _child[child].sf;
	var page = _child[child].p;
	var remote_url = 'child_list_data.php';
	if (_child[child].type == "custom") {
		remote_url = 'child_list_data_' + child + '.php';
	}

	drawLoading(objname);
	var err = "Unable to load the \"" + child.toUpperCase() + "\" list. Please contact your system administrator to solve the problem.";
	$.ajax({
		url: remote_url,
		data: {'i':id , 'l':lang , 'r':rev ,'ch':child, 'ts':tmp_session, 'sm': sort_mode,'sf': sort_field, 'p':page },
		type: 'POST', dataType: 'json', timeout: 30000, cache: false,
		error: function(){
			showNotification("alert","Error: ", err);
			return false;
		},

		success: function(result){
			if(result.e == "SESSION_TIMEOUT") {
				Redirect(_redirect_timeout);
			} else if(result.e == "PERMISSION_DENIED") {
				Redirect(result.url);
			} else if(result.status == "FAIL") {
				showNotification("alert","Error: ", err);
				return false;
			} else if(result.t == "CUSTOM") {
				$("#" + objname).html(result.Data);
				return false;
			} else {
				result = loadListSuccessChild(result);
				result.Param["child_name"] = result.Child["name"];
				if (form_mode != "view") {
					if (typeof(hasChildCustomButton) == "function" && hasChildCustomButton(result.Child["name"])) {
						$("#" + objname).html(drawTableChild(result.Header, result.Data, result.Param, 750) + printPaginator(result.Page, "goToPageChild", 750, "addNewChild", undefined, getChildCustomButtonConfig(result.Child["name"])));
					} else {
						$("#" + objname).html(drawTableChild(result.Header, result.Data, result.Param, 750) + printPaginator(result.Page, "goToPageChild", 750, "addNewChild"));
					}
				} else {
					$("#" + objname).html(drawTableChild(result.Header, result.Data, result.Param, 750) + 	printPaginator(result.Page, "goToPageChild", 750));
				}
				if(result.Page.total_item > 0) {
					$("#" + objname + " .datagrid tbody tr").contextMenu({ menu: 'childcontextmenu', parentObjName: objname}, function(action, el, pos) { contextAction(action, el); });
				}
				
				$("#" + objname + " .datagrid thead th div").mouseover(function() { $(this).addClass("hover"); }).mouseout(function(){ $(this).removeClass("hover"); });
				
				$("#" + objname).attr('unselectable', 'on').css('MozUserSelect', 'none').css('WebkitUserSelect', 'none').bind('selectstart.ui', function() { return false; });
				if (typeof(onDrawTableChildCompleted) == "function") {
					onDrawTableChildCompleted(result.Child["name"]);
				}
				fixTableScroll(objname);
				return false;
			}
		}
	});
}

function sortByChild(obj, field){
	if($(obj).find("div").hasClass("click_asc")){ mode = "asc"; } 
	else if($(obj).find("div").hasClass("click_desc")){ mode = "desc";} 
	else { mode = ""; }
	
	var row_id = $(obj).parent().parent().parent().parent().parent().parent().parent().attr("id");
	if(row_id != undefined) {
		var child = row_id.substring(4);
		_child[child].sm = mode;
		_child[child].sf = field;
		_child[child].p = 1;
		loadListChild(row_id + " .child_data", child);	
	}
}

function goToPageChild(page,obj) {
	var row_id = $(obj).parent().parent().parent().parent().parent().attr("id");
	if(row_id != undefined) {
		var child = row_id.substring(4);
		_child[child].p = page;
		loadListChild(row_id + " .child_data", child);
	}
}

function drawTableChild(header, data, param, table_width){
	var i,j;
	var ret = "";
	var ret2 = "";
	var table_width_sum = 0;
	var field_data;
	var column_code;
	var th_class, th_div_class, th_id, tr_class, td_class;

	var num_col = param.num_col;
	var num_row = param.num_row;

	if(num_row > 0) {
		for(i = 0; i < num_col; i++) {
			th_class = "";
			th_div_class = "";
			th_id = "";
	
			if(i == (num_col - 1)) { th_class += " class=\"last\" "; } else { th_class = ""; }
			column_code = param["column_code"][i];
			if(header[column_code]["class"] != "") { 
				th_div_class = " class=\"" + header[column_code]["class"] + "\" "; 
			} else { 
				th_div_class = ""; 
			}
			if(header[column_code]["id"] != "") { th_id = " id=\"" + th_id + "\" "; } else { th_id = ""; }
			ret2 += "<th" + th_class + " width=\"" + header[column_code]["width"] + "\" onclick=\"sortByChild(this, '" + column_code + "')\" >";
			ret2 +=	"<div" + th_div_class + th_id + ">" + header[column_code]["title"] + "</div>";
			ret2 += "</th>";
			table_width_sum += header[column_code]["width"];
		}
	
		ret += "<div class=\"table_data\" style=\"overflow:auto;width:" + table_width + "px;\">";
		ret += "<table style=\"width:" + table_width_sum + "px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"datagrid\" >";
		ret += "<thead>";
		ret += "<tr height=\"15\">";
		if ( $.browser.msie && Number($.browser.version) < 7 ) {		
			ret += "<th style=\"text-align: center;\">&nbsp;</th>";
		} else {
			ret += "<th style=\"text-align: center;\">&#10003;</th>";
		}
		ret += ret2;
		ret += "</tr>";
		ret += "</thead>";
		ret += "<tbody>";
		
		for(i = 0; i < num_row; i++) {
			tr_class = "";
			if(i == (num_row -1)){ tr_class += "last"; }
			if((i % 2) == 1) { tr_class += " odd"; } 
			if(tr_class != "") { tr_class = " class=\"" + tr_class + "\" "; }
			ret += "<tr ref=\"" + data[i]["obj_id"] + ":" + data[i]["obj_rev"] + ":" + data[i]["obj_lang"] + ":" + i + ":" + data[i]["obj_state"] + ":" + param.child_name +  "\" " + tr_class + " onmouseout=\"mouseOutItem(this,'childcontextmenu',false)\" onmouseover=\"mouseOverItem(this,'childcontextmenu',false)\" onclick=\"mouseClickItem(this,event,'childcontextmenu',false)\" >" + "\r\n";

			ret += "<td class=\"choose\"><input onclick=\"checkItem(this,event,'childcontextmenu',false)\" type=\"checkbox\"/></td>" + "\r\n";
			var blank_html_value = "<em>&#8211;</em>";
			
			for(j = 0; j < num_col; j++) {
				column_code = param["column_code"][j];
				if(header[column_code]["display_option"] != null) {
					var column_type = header[column_code]["display_option"]["type"];
					
					if(column_type == "checkbox"){
						if(data[i][column_code] == "T") { field_data = header[column_code]["display_option"]["true_label"]; } else { field_data = header[column_code]["display_option"]["false_label"];}
					
					} else if(column_type == "file"){
						filename_arr = data[i][column_code].split("|");
						if(filename_arr.length == 2) {
							field_data = (filename_arr[1] != "")?"<span title=\"" + filename_arr[0] + "\" class=\"file\" style=\"width:" + (header[column_code]["width"] - 20)+ "px\">" + filename_arr[0] + "</span>":blank_html_value;
						} else {
							field_data = blank_html_value;
						}
					
					} else if(column_type == "image"){
						filename_arr = data[i][column_code].split("|");
						if(filename_arr.length == 2) {
							field_data = (filename_arr[1] != "")?"<div class=\"image\"><img src=\"" + base_url+OMImage.readFileName(filename_arr[1],filename_arr[0] ,"d32x32",$("#fwdparam_module_name").val())  + "\" title=\"" + filename_arr[0] + "\"></div>":blank_html_value;
						} else {
							field_data = blank_html_value;
						}

					} else if(column_type == "state"){
						var icon_filename = "";
						if(data[i][column_code] == "draft"){ icon_filename = "draft.png"; }
						else if(data[i][column_code] == "published"){ icon_filename = "published.png"; }
						else if(data[i][column_code] == "published_ch"){ icon_filename = "published_ch.png"; }

						field_data = (icon_filename != "")?"<img src=\"../core/images/list/state/" + icon_filename + "\" >":blank_html_value;
						
					} else if(column_type == "lang"){
						field_data = "<img src=\"../core/images/lang/" + data[i][column_code] + ".png\">";
					
					} else if(column_type == "lookup") {
                        if (header[column_code]["display_option"]["mode"] == "static") {
                            field_data = (header[column_code]["display_option"]["options"][data[i][column_code]] != undefined)?(header[column_code]["display_option"]["options"][data[i][column_code]]):blank_html_value;
                        
                        } else if (header[column_code]["display_option"]["mode"] == "dynamic") {
                            field_data = (data[i]["obj_" + column_code + "_label"] != "")?data[i]["obj_" + column_code + "_label"]:blank_html_value;
                        }
                        
                    } else if (column_type == "user_id") {
                        field_data = (data[i]["" + column_code + "_label"] != "")?data[i]["" + column_code + "_label"]:blank_html_value;

                    } else if (column_type == "path_label") {
                        field_data = data[i][column_code].replace(new RegExp((header[column_code]["display_option"]["separator"]).replace(new RegExp("[.*+?|()\\[\\]{}\\\\]","g"),"\\$&"),"g"),"&nbsp;<em>&#8260;</em>&nbsp;");
                    
                    } else {
                    	field_data = (data[i][column_code] != "")?data[i][column_code]:blank_html_value;
					}
				} else {
					field_data = (data[i][column_code] != "")?data[i][column_code]:blank_html_value;
				}

				td_class = "";
				if(column_type == "state") { td_class = "state "; }
				if(j == (num_col -1)){ td_class += "last "; }
				td_class += header[column_code]["align"] + " ";

				if(td_class != "") { td_class = " class=\"" + td_class + "\" "; }
				if(field_data == "") { field_data = "&nbsp;"; }
				ret += "<td" + td_class + ">" + field_data + "</td>" + "\r\n";
			}
			ret += "</tr>" + "\r\n";
		}
	} else {
		ret += "<div class=\"table_data\" style=\"overflow:auto;width:" + table_width + "px;\">";
		ret += "<table style=\"width:" + table_width + "px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"datagrid\" >";
		ret += "<tr height=\"15\">";
		ret += "<th class=\"last\" width=\"" + table_width + "\" >";
		ret +=	"<div>Empty list</div>";
		ret += "</th>";
		ret += "<tr><td class=\"last bottom empty\" >";
		ret += "<strong>There is not item which be added before.</strong><br/>";
		ret += "You can add a new item by following the instruction:";
		ret += "<blockquote><ul>";
		ret += "<li>Click the &quot;+&quot; button at the bottom of this list to add a new item.</li>";
		ret += "<li>Fill the data in &quot;Add new item&quot; form.</li>";
		ret += "<li>Click the &quot;Add&quot; button when complete.</li>";
		ret += "</ul></blockquote>";
		ret += "</td></tr>";
	}
	ret += "</tbody>";
	ret += "</table></div>";
	return(ret);
}

function addNewChild(obj){
	var child_name = $(obj).parent().parent().parent().parent().parent().attr("id").substring(4);
	openChild(child_name, "add", 0);
	return false;
}

function actionOpenChild(param){
	var child_name = param.child_name;
	openChild(child_name, "view", param);
	return false;
}

function actionEditChild(param){
	var child_name = param.child_name;
	openChild(child_name, "edit", param);
	return false;
}

function childCheckRecoverMode(child_name) {
	if ($("#val_recovery").size() == 1 && $("#val_recovery").val() != "") {
		Dialog("Error","You cannot modify child data in <strong>Recovery Preview</strong> mode.",{"OK":close});
		return true;
	} else {
		return false;
	}
}

function actionDeleteChild(param, confirm){
	if (childCheckRecoverMode(param.child_name)) {
		return;
	}
	var child_name = param.child_name;
	selected_list = getChildMultiSelectedList("#row_" + child_name);
	if(selected_list.length == 1) {
		dlg_msg = "<strong>Are you sure you want to delete the selected item?</strong><br/><br/>This cannot be undone.";
	} else {
		dlg_msg = "<strong>Are you sure you want to delete the " + selected_list.length + " selected items?</strong><br/><br/>This cannot be undone.";
	}
	Dialog("Confirmation",
		dlg_msg,
		{
			"Continue":function(){							
					destroyDialog();
					$.ajax({
						url: 'form_child_cmd.php',
						data: { 'fwdparam_child_command': 'delete',
								'fwdparam_child_mode': 'delete',
								'fwdparam_child_name': child_name, 
								'fwdparam_item_id': 0, 
								'fwdparam_item_id_list': getItemListString(selected_list), 
								'fwdparam_lang': $("#fwdparam_lang").val(), 
								'fwdparam_parent_id': $("#fwdparam_item_id").val(), 
								'fwdparam_revision': $("#fwdparam_revision").val(), 
								'fwdparam_tmp_session': $("#fwdparam_session").val()},
						type: 'POST', cache: false, timeout: 30000,
						dataType: 'json',
						error: function(){
							Dialog("Error","ERROR",{"OK":close});
						},
						success: function(rs){
							if(rs.c == "OK"){				
								if (rs.r != rs.or) {
									$("#fwdparam_revision").val(rs.r);
									$("#fwdparam_last_modified_date").val(rs.md);
									loadAllListChild();
								} else {
									loadListChild('row_' + _form.child.name + ' .child_data',_form.child.name);
								}
							} else if (rs.c == "WAR") {
								if (result.r != result.or) {
									loadAllListChild();
								} else {
									loadListChild('row_' + _form.child.name + ' .child_data',_form.child.name);
								}
							} else if (rs.c == "ERR") {
								Dialog("Error",rs.c + ": " + rs.m,{"OK":close});
								return false;
							} else if (rs.e != undefined) {
								Dialog("Error","ERR-" + rs.e + ": " + rs.m,{"OK":close});
								return false;
							} else {
								Dialog("Error","ERR: " + rs.m,{"OK":close});
								return false;
							}
						}
					});	
				},
			"Cancel":close
		});
		
						

	return false;
}
getItemListString = function(item_list) {
		var slist="";
		for(var idx in item_list) {
			if (slist != "") slist +=",";
			slist += item_list[idx].id + "," + item_list[idx].lang + "," + item_list[idx].rev;
		}	
		return slist;
}
function getChildMultiSelectedList(selector){
	var selected_list = [];
	var i = 0;
	$(selector).find('tbody tr.checked').each(function(){
		var ref_arr = $(this).attr("ref").split(":");
		if (selected_list[i] == undefined || selected_list[i] == null) selected_list[i] = {};
		selected_list[i]["id"] = ref_arr[0];
		selected_list[i]["rev"] = ref_arr[1];
		selected_list[i]["lang"] = ref_arr[2];
		selected_list[i]["state"] = ref_arr[4];
		selected_list[i]["child_name"] = ref_arr[5];
		i++;
	});
	return selected_list;
}

function openChild(childname, mode, param) {
	if (childCheckRecoverMode(childname)) {
		return;
	}
	if ($("#val_recovery").size() == 1 && $("#val_recovery").val() != "") {
		param.rev = $("#val_recovery").val();
	}
	var lang = $("#fwdparam_lang").val();
	var parent_id = $("#fwdparam_item_id").val();
	var tmp_session = $("#fwdparam_session").val();
	
	_form.child.name = childname;
	_form.child.mode = mode;
	_form.child.item_id = param.id;
	
	drawLoadingFullPage();
	
	$.ajax({
		url: 'form_child.php',
		data: {'m':mode, 'ch':childname, 'p':parent_id, 'l':lang, 'i':param.id, 'r':param.rev, 'ts':tmp_session},
		type: 'POST', cache: false, timeout: 30000,
		dataType: 'json',
		error: function(){
			destroyLoadingFullPage();
			Dialog("Error","Error wording",{"OK":close});
			return false;
		},
		success: function(result){
			destroyLoadingFullPage();
			if(result.c == "OK"){
				if (typeof(onCustomOpenChildLoadSucces) == "function") {
					result = onCustomOpenChildLoadSucces(childname, mode, param, result);
				}
				if(mode == "view") {
					childDialog(result.title, result.body, {"Close":close},800);
				} else {
					childDialog(result.title, result.body, {"Cancel":close,"Save":function(){saveChildItem(mode,1);}},800);
				}
				if (typeof(onCustomOpenChildInit) == "function") {
					onCustomOpenChildInit(childname, mode, param, result);
				}
			} else if(result.e == "SESSION_TIMEOUT") {
				Redirect(_redirect_timeout);
			} else if(result.e == "PERMISSION_DENIED") {
				Redirect(result.url);
			} else {
				Dialog("Error", result.e, {"OK":close});
			}
			return false;
		}
	});
	// return false;
}