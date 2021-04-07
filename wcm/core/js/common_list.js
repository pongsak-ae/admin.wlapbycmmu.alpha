//-------------------------------------------------------
//             Filter function
//-------------------------------------------------------
var _filter = new Object();
var _gridcontrol = new Object();
var _listcontrol = new Object();
_listcontrol.fwd_checkbox_click = false;
_listcontrol.link_target = -1;
_listcontrol.link_target_lang = "";
_gridcontrol.lastchoose = -1;
_filter.num = 0;
_filter.running_index = 0;
_filter.lastload = "";

function searchByFilter(obj, clear_lastload) {
	var err = "Unable to update the search filter. Please contact your system administrator to solve the problem.";
	var filter_param = new Object();
	var p_list = "";
	for(i = 0; i < _filter.running_index; i++){
		if($(obj).parent().parent().parent().find("#parm_" + i + "_f").val() != undefined){
			filter_param[i+"_f"] = $(obj).parent().parent().parent().find("#parm_" + i + "_f").val();
			filter_param[i+"_o"] = $(obj).parent().parent().parent().find("#parm_" + i + "_o").val();
			filter_param[i+"_v"] = $(obj).parent().parent().parent().find("#parm_" + i + "_v").val();
			filter_param[i+"_l"] = $(obj).parent().parent().parent().find("#parm_" + i + "_l").val();

			if($(obj).parent().parent().parent().find("#parm_" + i + "_v2").length != 0) {
				filter_param[i+"_v"] += ":" + $(obj).parent().parent().parent().find("#parm_" + i + "_v2").val();
			}

			p_list += i + ",";
		}
	}
	var s_mode = $(obj).parent().parent().find("#s_mode").val();
	var s_keyword = $(obj).parent().parent().find("#s_keyword").val();

	$.ajax({
			url: 'list_cmd.php',
			data: {'c':'nowfilter', 'l':p_list , 'p':filter_param, 'm': s_mode, 'k':s_keyword, 'cf':clear_lastload, '__':(new Date()).getTime()},
			type: 'POST', cache: false, timeout: 30000,
			dataType: 'json',
			error: function(){
				Dialog("Error",err,{"OK":close});
				return false;
			},
			success: function(result){
				if(result.c == "OK"){
					loadList("list_data");
				} else if(result.e == "SESSION_TIMEOUT") {
					Redirect(_redirect_timeout);
				} else if(result.e == "PERMISSION_DENIED") {
					Redirect(result.url);
				} else {
					Dialog("Error", err, {"OK":close});
				}
				return false;
			}
	});
}

function addFilter(obj, set_value){
	var field_set_value = new Object();

	if(set_value == ''){
		field_set_value.f = "";
		field_set_value.o = "";
		field_set_value.v = "";
        field_set_value.l = "";
	} else {
		field_set_value = set_value;
	}

	var filter_ret = "";
	filter_ret += "<div class=\"field\" id=\"f" + _filter.running_index + "\">";
	filter_ret += "	<div class=\"middleline fleft\">";
	filter_ret += createFilterField(obj, _filter.running_index, field_set_value);
	filter_ret += "	<span class=\"spn\">&nbsp;</span></div>";
	filter_ret += "	<div class=\"middleline fright\">";
	filter_ret += "		<a href=\"#remove\" class=\"cmdbutton remove\" onclick=\"removeFilter(this)\"></a>";
	filter_ret += "		<a href=\"#remove\" class=\"cmdbutton add\" onclick=\"addFilter(this,'')\"></a>";
	filter_ret += "	</div>";
	filter_ret += "</div>";

	$(obj).parent().parent().after(filter_ret);

	bindDatePicker("#f" + _filter.running_index + " .datepicker");

	_filter.num++;
	_filter.running_index++;

	if(_filter.num >= 1){
		$(obj).parent().parent().find('.mode1').css("display","none");
		$(obj).parent().parent().find('.mode1_ib').css("display","none");
		$(obj).parent().parent().find('.mode2').css("display","inline");
		$(obj).parent().parent().find('.mode2_ib').css("display","inline-block");
	}
}

function removeAllFilterNow(obj){
	$(obj).parent().parent().parent().find('.field').remove();
	$(obj).parent().parent().parent().find('#head .mode1').css("display","inline");
	$(obj).parent().parent().parent().find('#head .mode1_ib').css("display","inline-block");
	$(obj).parent().parent().parent().find('#head .mode2').css("display","none");
	$(obj).parent().parent().parent().find('#head .mode2_ib').css("display","none");
	_filter.lastload = "";
	_filter.num = 0;
}

function removeAllFilter(obj){
	if(_filter.num < 2){
		removeAllFilterNow(obj);
		$(obj).parent().parent().find('#s_keyword').val("");
		searchByFilter(obj,'Y');
	} else {
		Dialog('Confirmation','Are you sure you want to remove all the criterias?',
			{
				"Clear":function(){
					destroyDialog();
					removeAllFilterNow(obj);
					$(obj).parent().parent().find('#s_keyword').val("");
					searchByFilter(obj,'Y');
				}, "Cancel":close
			});
	}
}

function removeFilter(obj){
	_filter.num--;
	if(_filter.num < 1){
		$(obj).parent().parent().parent().find('#head .mode1').css("display","inline");
		$(obj).parent().parent().parent().find('#head .mode1_ib').css("display","inline-block");
		$(obj).parent().parent().parent().find('#head .mode2').css("display","none");
		$(obj).parent().parent().parent().find('#head .mode2_ib').css("display","none");
	}
	$(obj).parent().parent().remove();
}

function loadFilter(filter_id){

	drawLoading("list_data");
	$.ajax({
			url: 'list_cmd.php',
			data: {'c':'loadfilter', 'i': filter_id, '__':(new Date()).getTime()},
			type: 'POST', cache: false, timeout: 30000,
			dataType: 'json',
			error: function(){
				return false;
			},
			success: function(result){
				if(result.c == "OK"){
					loadList("list_data");
				} else if(result.e == "SESSION_TIMEOUT") {
					Redirect(_redirect_timeout);
				} else if(result.e == "PERMISSION_DENIED") {
					Redirect(result.url);
				}
				return false;
			}
	});
}

function findNextField(obj) {
	for(i = 0; i < filterField.length; i++){
		found = false;
		$(obj).parent().parent().parent().find('select.f').each(function(index){
			if($(this).val() == filterField[i][2]) { found = true; }
		});
		if(!found){ return filterField[i][2]; }
	}
	return filterField[0][2];
}

function createFilterField(obj, running_index, set_value){
	var ret = "";
	var selected = "";
	var filter_type = "";
	var set_default_value_from_type = false;
	ret += '<select class="f" name="parm_' + running_index + '_f" id="parm_' + running_index + '_f" onchange="changeFilterField(this,' + running_index + ');">';

	if(set_value.f == ""){
		set_value.f = findNextField(obj);
		set_default_value_from_type = true;
	}

	for(i = 0; i < filterField.length; i++){
		if (filterField[i][2] == set_value.f){
			selected = "selected";
			filter_type = filterField[i][1];
		} else {
			selected = "";
		}
		ret += '<option value="' + filterField[i][2] + '" ' + selected + '>' + filterField[i][0] + '</option>';
	}

	if(filter_type == "") { filter_type = filterField[0][1];}
	if(set_default_value_from_type) {
		var len = filterOperator[filter_type].length;
		var set_value = new Object();
		set_value.l = "";
		set_value.v = filterOperator[filter_type][len-1];
	}

	ret += '</select>';
	ret += createFilterOperator(running_index, filter_type, set_value);
	return(ret);
}

function createFilterOperator(running_index, filter_type, set_value) {
	var ret = "";
	var selected = "";
	ret += '<select name="parm_' + running_index + '_o" id="parm_' + running_index + '_o" >';
    if (filter_type.indexOf("LOOKUP_DYNAMIC") == 0) {
        var _fn = filterOperator[filter_type][0];
        var vlist = _fn();
        for(i = 0; i < vlist.length; i++){
            if (vlist[i][0] == set_value.o){ selected = "selected"; } else { selected = "";}
            ret += '<option value="' + vlist[i][0] + '" ' + selected + '>' + vlist[i][1] + '</option>';
        }
    } else if (filter_type.indexOf("LOOKUP_FINDER") == 0) {
            for(i = 0; i < filterOperator[filter_type].length - 2; i++){
            if (filterOperator[filter_type][i][0] == set_value.o){ selected = "selected"; } else { selected = "";}
            ret += '<option value="' + filterOperator[filter_type][i][0] + '" ' + selected + '>' + filterOperator[filter_type][i][1] + '</option>';
        }
    } else {
        for(i = 0; i < filterOperator[filter_type].length - 2; i++){
            if (filterOperator[filter_type][i][0] == set_value.o){ selected = "selected"; } else { selected = "";}
            ret += '<option value="' + filterOperator[filter_type][i][0] + '" ' + selected + '>' + filterOperator[filter_type][i][1] + '</option>';
        }
    }
	ret += '</select>';
	ret += createFilterValue(running_index, filter_type, set_value);
	return(ret);
}

function changeFilterField(obj, running_index){
	var filter_type = "";
	var filter_name = $(obj).parent().find("#parm_" + running_index + "_f").val();
	for(var i = 0; i < filterField.length; i++){
		if(filter_name == filterField[i][2]){
            filter_type = filterField[i][1];
        }
	}
	var len = filterOperator[filter_type].length;
	var set_value = new Object();
	set_value.v = filterOperator[filter_type][len-1];
	set_value.o = "";
	set_value.f = "";
	set_value.l = "";
    if (filter_type.indexOf("LOOKUP_FINDER") == 0) {
        $(obj).parent().find("#parm_" + running_index + "_l").remove();
        $(obj).parent().find("#parm_" + running_index + "_v").remove();
        $(obj).parent().find("#parm_" + running_index + "_o").replaceWith(createFilterOperator(running_index, filter_type,set_value));
        return;
    } else if (filter_type.indexOf("LOOKUP_DYNAMIC") == 0) {
        $(obj).parent().find("#parm_" + running_index + "_l").remove();
        $(obj).parent().find("#parm_" + running_index + "_v").remove();
        $(obj).parent().find("#parm_" + running_index + "_o").replaceWith("<span id='parm_" + running_index + "_o' class='filter_op_loading'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>");
        $(obj).parent().find("#parm_" + running_index + "_o").replaceWith(createFilterOperator(running_index, filter_type,set_value));
        $(obj).parent().find("#parm_" + running_index + "_v").remove();
    } else {
		$(obj).parent().find("#parm_" + running_index + "_l").remove();
		$(obj).parent().find("#parm_" + running_index + "_v").remove();
		$(obj).parent().find("#parm_" + running_index + "_v2").remove();
		$(obj).parent().find("#parm_" + running_index + "_o").replaceWith(createFilterOperator(running_index, filter_type,set_value));
		$(obj).parent().find("#parm_" + running_index + "_v").remove();
		$(obj).parent().find("#parm_" + running_index + "_v2").remove();

        if(set_value.v != null){
            $(obj).parent().find("#parm_" + running_index + "_o").after(createFilterValue(running_index,filter_type,set_value));
        }
    }

    bindDatePicker("#f" + running_index + " .datepicker");
}

function createFilterValue(running_index, filter_type, set_value){
	var ret = "";

	if(filter_type == "TEXT") {
		ret += '<input type="text" style="width:200px;" value="' + set_value.v + '" id="parm_' + running_index + '_v" name="parm_' + running_index + '_v" maxlength="100">';
	} else if((filter_type == "NUMBER")) {
		ret += '<input style="width:80px;" type="text" value="' + set_value.v + '" id="parm_' + running_index + '_v" name="parm_' + running_index + '_v" maxlength="20">';
	} else if (filter_type.indexOf("LOOKUP_FINDER") == 0) {
        ret += "<input type=\"text\" value=\"" + set_value.l + "\" id=\"parm_" + running_index + "_l\" name=\"parm_" + running_index + "_l\" style=\"width:300px;\" readonly onclick='openFinder(this,\"" + filterOperator[filter_type][(filterOperator[filter_type]).length-2] + "\")'>";
        ret += "<input type=\"hidden\" value=\"" + set_value.v + "\" id=\"parm_" + running_index + "_v\" name=\"parm_" + running_index + "_v\">";
    } else if((filter_type == "DATE")){
		ret += '<input class="datepicker" type="text" value="' + set_value.v + '" id="parm_' + running_index + '_v" name="parm_' + running_index + '_v" style="width:80px;" maxlength="10">';
	} else if((filter_type == "TIME")){
		var hr_txt = "00";
		var mn_txt = "00";
		if((set_value.v.length == 5) && (set_value.v.substring(2,3) == ":")) {
			hr_txt = set_value.v.substring(0,2);
			mn_txt = set_value.v.substring(3,5);
		}
		var set_select;
		ret += '<select id="parm_' + running_index + '_v" name="parm_' + running_index + '_v">';
		for(var j = 0; j < 24; j++){
			if(hr_txt == PadLeft(j,2,'0')) { set_select = " selected=\"selected\""} else { set_select = ""; }
			ret += '<option' + set_select + '>' + PadLeft(j,2,'0') + '</option>';
		}
		ret += '</select>';
		ret += '<select id="parm_' + running_index + '_v2" name="parm_' + running_index + '_v2">';
		for(var j = 0; j < 60; j++){
			if(mn_txt == PadLeft(j,2,'0')) { set_select = " selected=\"selected\""} else { set_select = ""; }
			ret += '<option' + set_select + '>' + PadLeft(j,2,'0') + '</option>';
		}
		ret += '</select>';
    } else {
		ret += '<input type="hidden" value="' + set_value.v + '" id="parm_' + running_index + '_v" name="parm_' + running_index + '_v">';
	}
	return(ret);
}

function refreshSearchList(){
	$.ajax({
			url: 'list_cmd.php',
			data: {'c':'loadsavedlist', '__':(new Date()).getTime()},
			type: 'POST', cache: false, timeout: 30000,
			dataType: 'html',
			error: function(){
				return false;
			},
			success: function(result){
				$('#searchlist ul').html(result);
				return false;
			}
	});
}

function showSearchList() {
	if($.browser.msie) {
		$("select").css("visibility","hidden");
	}

	if($('#searchlist').css('display') == "none") {
		$('#searchlist').fadeIn(150);
		$('#searchlist li')
			.mouseover(function() { $(this).addClass("hover"); })
			.mouseout(function() { $(this).removeClass("hover"); })
			.mousemove(function(e) {
				var offset = $(this).find('a').offset();
				var mousex = e.pageX - offset.left;
				if(mousex >= 174 && mousex <= 192) {
					$(this).find('div').addClass('btn-remove');
				} else {
					$(this).find('div').removeClass('btn-remove');
				}
			})
			.find('a').click(function() {
				if($(this).parent().hasClass('btn-remove')){
					_filter.remove_id = $(this).attr('href').substr($(this).attr('href').indexOf("#")+1);
					_filter.remove_name = $(this).html();

					Dialog("Confirmation","Do you want to remove this filter list?",
						{"Yes":function() {
							if(_filter.remove_name == _filter.lastload) { _filter.lastload = ""; }
							$.ajax({
									url: 'list_cmd.php',
									data: {'c':'removefilter', 'i': _filter.remove_id, '__':(new Date()).getTime()},
									type: 'POST', cache: false, timeout: 30000,
									dataType: 'json',
									error: function(){
										return false;
									},
									success: function(result){
										if(result.c == "OK"){
											refreshSearchList();
										} else if(result.e == "SESSION_TIMEOUT") {
											Redirect(_redirect_timeout);
										} else if(result.e == "PERMISSION_DENIED") {
											Redirect(result.url);
										}
										return false;
									}
							});
							destroyDialog();
						}, "Cancel":close});
				} else {
					loadFilter($(this).attr('href').substr($(this).attr('href').indexOf("#")+1));
				}
			});
			setTimeout( function() { // Delay for Mozilla
				$(document).click( function() {
					$(document).unbind('click');
					$('#searchlist').fadeOut(150);
					if($.browser.msie) {
						$("select").css("visibility","visible");
					}
					return false;
				});
			}, 0);
	}
	return false;
}

function saveFilter(obj, step){
	var suggested_name = "Untitled";
	if(_filter.lastload != "") { suggested_name = _filter.lastload; }

	if(step == 1){
		Dialog("Save filters", "Please input your search filter name<br/><input class=\"focus\" id=\"newname\" maxlength=\"60\" type=\"text\" value=\"" + suggested_name + "\" style=\"width:350px; margin:5px 0;\">", {"Save": function(){ _filter.newname = $(".dialog #newname").val(); saveFilter(obj,2); },"Cancel": close});
	} else if(step == 2){
		var err = "Unable to check the search filter. Please contact your system administrator to solve the problem.";
		if(_filter.newname == "") { _filter.newname = "Untitled"; }
		$.ajax({
			url: 'list_cmd.php',
			data: {'c':'checksavefilter', 'n': _filter.newname, '__':(new Date()).getTime()},
			type: 'POST', cache: false, timeout: 30000,
			dataType: 'json',
			error: function(){
				Dialog("Error",err,{"OK":close});
			},
			success: function(rs){
				if(rs.c == "OK"){
					saveFilter(obj, 3);
				} else if(rs.c == "WAR" && rs.w == "DUP") {
					Dialog("Confirmation","<strong>\""+ _filter.newname + "\" already exists Do you want to replace it? </strong><br/>A Saved search with the same name already exists in the list. Replacing it will overwrite its current filters.",{"Overwrite":function(){ saveFilter(obj,3); },"Change name":function(){ saveFilter(obj,1); },"Cancel":close});
				} else if(rs == "SESSION_TIMEOUT") {
					Redirect(_redirect_timeout);
				} else if(result.e == "PERMISSION_DENIED") {
					Redirect(result.url);
				} else {
					Dialog("Error",err,{"OK":close});
					return false;
				}
			}
		});

	} else if(step == 3) {
		var err = "Unable to save the search filter. Please contact your system administrator to solve the problem.";
		var filter_param = new Object();
		var p_list = "";
		for(i = 0; i < _filter.running_index; i++){
			if($(obj).parent().parent().parent().find("#parm_" + i + "_f").val() != undefined){
				filter_param[i+"_f"] = $(obj).parent().parent().parent().find("#parm_" + i + "_f").val();
				filter_param[i+"_o"] = $(obj).parent().parent().parent().find("#parm_" + i + "_o").val();
				filter_param[i+"_v"] = $(obj).parent().parent().parent().find("#parm_" + i + "_v").val();
				p_list += i + ",";
			}
		}
		var s_mode = $(obj).parent().parent().find("#s_mode").val();
		var s_keyword = $(obj).parent().parent().find("#s_keyword").val();

		$.ajax({
				url: 'list_cmd.php',
				data: {'c':'savefilter', 'n':_filter.newname, 'l':p_list , 'p':filter_param, 'm': s_mode, 'k':s_keyword, '__':(new Date()).getTime()},
				type: 'POST', cache: false, timeout: 30000,
				dataType: 'json',
				error: function(){
					Dialog("Error",err,{"OK":close});
					return false;
				},
				success: function(result){
					if(result.c == "OK"){
						_filter.lastload = _filter.newname;
						$('#searchlist ul .empty').remove();
						$("#searchlist ul").append("<li><div><a href=\"#" + result.i + "\">" + _filter.newname + "</a></div></li>");
						Dialog("Save completed", "The \"" + _filter.newname + "\" filter is saved in the \"Saved search\" list completely.",{"OK":close});
						refreshSearchList();
						searchByFilter(obj);
					} else if(result.e == "SESSION_TIMEOUT") {
						Redirect(_redirect_timeout);
					} else if(result.e == "PERMISSION_DENIED") {
						Redirect(result.url);
					} else {
						Dialog("Error",err,{"OK":close});
					}
					return false;
				}
		});
	}
}
//-------------------------------------------------------
//             Sort function
//-------------------------------------------------------

function sortBy(obj, field) {

	if($(obj).find("div").hasClass("click_asc")){
		mode = "asc";
	} else if($(obj).find("div").hasClass("click_desc")){
		mode = "desc";
	} else {
		mode = "";
	}

	drawLoading("list_data");
	$.ajax({
			url: 'list_cmd.php',
			data: {'c':'sortby', 'f':field, 'm':mode, '__':(new Date()).getTime()},
			type: 'POST', cache: false, timeout: 30000,
			dataType: 'json',
			error: function() {
				return false;
			},
			success: function(result) {
				if(result.c == "OK"){
					loadList("list_data");
				} else if(result.e == "SESSION_TIMEOUT") {
					Redirect(_redirect_timeout);
				} else if(result.e == "PERMISSION_DENIED") {
					Redirect(result.url);
				}
				return false;
			}
	});
}
//-------------------------------------------------------
//             Paging function
//-------------------------------------------------------

function goToPage(page){
	drawLoading("list_data");
	$.ajax({
			url: 'list_cmd.php',
			data: {'c':'gotopage', 'p':page, '__':(new Date()).getTime()},
			type: 'POST', cache: false, timeout: 30000,
			dataType: 'json',
			error: function() {
				return false;
			},
			success: function(result) {
				if(result.c == "OK"){
					loadList("list_data");
				} else if(result.e == "SESSION_TIMEOUT") {
					Redirect(_redirect_timeout);
				} else if(result.e == "PERMISSION_DENIED") {
					Redirect(result.url);
				}
				return false;
			}
	});
}
//-------------------------------------------------------
//             Listing function
//-------------------------------------------------------
function escapeHtmlEntities(text) {
	return $("<div/>").text(text).html();
}

function drawTable(header, data, param, table_width, nocheckbox){
	var i,j;
	var ret = "";
	var ret2 = "";
	var table_width_sum = 0;
	var field_data;
	var column_code;
	var th_class, th_div_class, th_id, tr_class, td_class;

	var num_col = param.num_col;
	var num_row = param.num_row;
	var html2entity = function(str){
        return str.replace(/[<>]/g, function(s){return (s == "<")? "&lt;" :"&gt;"});
    }

	if(nocheckbox == undefined) { nocheckbox = false; }

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
			ret2 += "<th" + th_class + " width=\"" + header[column_code]["width"] + "\" onclick=\"sortBy(this, '" + column_code + "')\" >";
			ret2 +=	"<div" + th_div_class + th_id + ">" + header[column_code]["title"] + "</div>";
			ret2 += "</th>";
			table_width_sum += header[column_code]["width"];
		}

		ret += "<div class=\"table_data\" style=\"overflow:auto;width:" + table_width + "px;\">";
		ret += "<table style=\"width:" + table_width_sum + "px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"datagrid\" >";
		ret += "<thead>";
		ret += "<tr height=\"15\">";
		if(!nocheckbox) {
			if ( $.browser.msie && Number($.browser.version) < 7 ) {
				ret += "<th style=\"text-align: center;\">&nbsp;</th>";
			} else {
				ret += "<th style=\"text-align: center;\">&#10003;</th>";
			}
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

			if(!nocheckbox) {
				ret += "<tr ref=\"" + data[i]["obj_id"] + ":" + data[i]["obj_rev"] + ":" + data[i]["obj_lang"] + ":" + i + ":" + data[i]["obj_state"] + "\" " + tr_class + " onmouseout=\"mouseOutItem(this,'listcontextmenu',false)\" onmouseover=\"mouseOverItem(this,'listcontextmenu',false)\" onclick=\"mouseClickItem(this,event,'listcontextmenu',false)\" >" + "\r\n";

				ret += "<td class=\"choose\"><input onclick=\"checkItem(this,event,'listcontextmenu',false)\" type=\"checkbox\"/></td>" + "\r\n";
			} else {
				ret += "<tr " + tr_class + " onmouseout=\"mouseOutItem(this,'listcontextmenu',true)\" onmouseover=\"mouseOverItem(this,'listcontextmenu',true)\" onclick=\"showLogDetail(" + data[i]["obj_id"] + ")\" >" + "\r\n";

			}
			var blank_html_value = "<em>&#8211;</em>";

			for(j = 0; j < num_col; j++) {
				column_code = param["column_code"][j];
				if(header[column_code]["display_option"] != null) {
					var column_type = header[column_code]["display_option"]["type"];

					if(column_type == "log_type"){
						var label = "";
						icon_filename = "";
						if(data[i][column_code] == "e"){ icon_filename = "error.gif"; label = "Error";}
						else if(data[i][column_code] == "i"){ icon_filename = "info.gif"; label = "Information";}
						else if(data[i][column_code] == "w"){ icon_filename = "warning.gif"; label = "Warning";}
						else { label = data[i][column_code]; }

						field_data = (data[i][column_code] != "")?"<img src=\"../core/images/logtype/" + icon_filename + "\"> " + label:blank_html_value;
					} else if(column_type == "checkbox"){
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
							field_data = (filename_arr[1] != "")?"<div class=\"image\"><img src=\"" + base_url + filename_arr[1] + "\" title=\"" + filename_arr[0] + "\"></div>":blank_html_value;
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

					} else if(column_type == "link"){
						field_data = data[i]["obj_id"];
					} else if(column_type == "obj_chainlink"){
						if(data[i][column_code] > 1){
							field_data = "<img src=\"../core/images/list/link.gif\">";
						} else {
							field_data = blank_html_value;
						}

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
						if (header[column_code]["display_option"]["data_format"] == "html") {
							field_data = (data[i][column_code] != "")?(data[i][column_code]):blank_html_value;
						} else {
							field_data = (data[i][column_code] != "")?escapeHtmlEntities(data[i][column_code]):blank_html_value;
						}
					}

				} else {
					field_data = ((data[i][column_code] != "")?escapeHtmlEntities(data[i][column_code]):blank_html_value);
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
		ret +=	"<div>No item was found</div>";
		ret += "</th>";

		ret += "<tr><td class=\"last bottom empty\" >";
		ret += "<strong>Sorry, No items matching your search criteria were found.</strong><br/>";
		ret += "Check the following items before performing another search:";
		ret += "<blockquote><ul>";
		ret += "<li>Recheck the search criteria you defined to ensure that the values you entered are correct.</li>";
		ret += "<li>In multi-languages, the language criteria, which specified by flag icon, will be paired with another search criteria.</li>";
		ret += "<li>Make the values in the search criteria less specific. For example, if you are searching for some specified state, try to filter item state with \"All items\" to search on.</li>";
		ret += "</ul></blockquote>";
		ret += "</td></tr>";
	}
	ret += "</tbody>";
	ret += "</table></div>";
	return(ret);
}

function addNewItem(obj){
	window.location.assign("form.php?mode=add");
}

function loadList(objname) {
	drawLoading(objname);
	$.ajax({
		url: 'list_data.php',
		type: 'POST', dataType: 'json', timeout: 30000, cache: false,
		data: {'__':(new Date()).getTime()},
		error: function(){
			return false;
		},

		success: function(result){
			if(result.e == "SESSION_TIMEOUT") {
				Redirect(_redirect_timeout);
			} else if(result.e == "PERMISSION_DENIED") {
				Redirect(result.url);
			} else if(!result.Page) {
				Dialog("Error",result.e,{"OK":close});
			} else {
				result = loadListSuccess(result);
				$('.filter .field').remove();
				$('.filter #head .mode1').css("display","inline");
				$('.filter #head .mode1_ib').css("display","inline-block");
				$('.filter #head .mode2').css("display","none");
				$('.filter #head .mode2_ib').css("display","none");
				_filter.num = 0;

				if(result.Filter != null){
	                for(var i = 0; i < result.Filter.length; i++) {
	                    if (i==0) {
	                        addFilter($('.filter #head .add'),{f:result.Filter[i].f, o:result.Filter[i].o, v:result.Filter[i].v, l:result.Filter[i].l});
	                    } else {
	                        addFilter($('.filter .field:last .add'),{f:result.Filter[i].f, o:result.Filter[i].o, v:result.Filter[i].v, l:result.Filter[i].l});
	                    }
					}
				}
				fixIeSelectBoxRecheck();
				if(result.FilterMain != null){
					$('.filter #head #s_keyword').val(result.FilterMain.keyword);
					$('.filter #head #s_mode').val(result.FilterMain.mode);

					if(result.FilterMain.lastload != null) { _filter.lastload = result.FilterMain.lastload; }
				}

				var addfunc = "addNewItem";
				if(result.Page.nocheckbox) { addfunc = ""; }
				if(result.Page.noaddfunc) { addfunc = ""; }
				if(result.Page.customAddFunction) { addfunc = result.Page.customAddFunction; }
				table_with=830;
				if (result["Custom"] != undefined && result["Custom"]["tablewidth"] != undefined) {
					table_with=result["Custom"]["tablewidth"];
				}
				$("#" + objname).html(drawTable(result.Header, result.Data, result.Param, table_with, result.Page.nocheckbox) + printPaginator(result.Page, "goToPage", table_with, addfunc));

				$("#" + objname + " .datagrid thead th div").mouseover(function() { $(this).addClass("hover"); }).mouseout(function(){ $(this).removeClass("hover"); });

				if(!result.Page.nocheckbox) {
					if(result.Page.total_item > 0) {
						$("#" + objname + " .datagrid tbody tr").contextMenu({ menu: 'listcontextmenu', parentObjName:'list_data'}, function(action, el, pos) { contextAction(action, el); });
					}

					$("#" + objname).attr('unselectable', 'on').css('MozUserSelect', 'none').css('WebkitUserSelect', 'none').bind('selectstart.ui', function() { return false; });
				}
				fixTableScroll(objname);
				fixPNG();
				setCheckBoxSelected();
				return false;
			}
		}
	});
}

function openFinder(target, tbl) {
	var target_id = $(target).attr("id");
	target_id = target_id.substring(0, target_id.length - 1);
	var title = $("#" + target_id + "f option:selected").text();
    var v = $(target).parent().find("input[type=hidden]").val();
    drawFinder(tbl, title, 500, v, function(id,label){
        $(target).attr("value",label);
        $(target).parent().find("input[type=hidden]").attr("value", id);
    });
}
