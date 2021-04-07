//-------------------------------------------------------
//             String function
//-------------------------------------------------------
var JSON = JSON || {};

// implement JSON.stringify serialization
JSON.stringify = JSON.stringify || function (obj) {

var t = typeof (obj);
if (t != "object" || obj === null) {

    // simple data type
    if (t == "string") obj = '"'+obj+'"';
    return String(obj);

}
else {

    // recurse array or object
    var n, v, json = [], arr = (obj && obj.constructor == Array);

    for (n in obj) {
        v = obj[n]; t = typeof(v);

        if (t == "string") v = '"'+v+'"';
        else if (t == "object" && v !== null) v = JSON.stringify(v);

        json.push((arr ? "" : '"' + n + '":') + String(v));
    }

    return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
}
};
// implement JSON.parse de-serialization
JSON.parse = JSON.parse || function (str) {
if (str === "") str = '""';
eval("var p=" + str + ";");
return p;
 };
//-------------------------------------------------------

if (!Object.keys) {
  Object.keys = (function () {
    'use strict';
    var hasOwnProperty = Object.prototype.hasOwnProperty,
        hasDontEnumBug = !({toString: null}).propertyIsEnumerable('toString'),
        dontEnums = [
          'toString',
          'toLocaleString',
          'valueOf',
          'hasOwnProperty',
          'isPrototypeOf',
          'propertyIsEnumerable',
          'constructor'
        ],
        dontEnumsLength = dontEnums.length;

    return function (obj) {
      if (typeof obj !== 'object' && (typeof obj !== 'function' || obj === null)) {
        throw new TypeError('Object.keys called on non-object');
      }

      var result = [], prop, i;

      for (prop in obj) {
        if (hasOwnProperty.call(obj, prop)) {
          result.push(prop);
        }
      }

      if (hasDontEnumBug) {
        for (i = 0; i < dontEnumsLength; i++) {
          if (hasOwnProperty.call(obj, dontEnums[i])) {
            result.push(dontEnums[i]);
          }
        }
      }
      return result;
    };
  }());
}


if (typeof JSON.stringify !== 'function') {
	var JSON = JSON || {};

	// implement JSON.stringify serialization
	JSON.stringify = JSON.stringify || function (obj) {

	var t = typeof (obj);
	if (t != "object" || obj === null) {

	    // simple data type
	    if (t == "string") obj = '"'+obj+'"';
	    return String(obj);

	}
	else {

	    // recurse array or object
	    var n, v, json = [], arr = (obj && obj.constructor == Array);

	    for (n in obj) {
	        v = obj[n]; t = typeof(v);

	        if (t == "string") v = '"'+v+'"';
	        else if (t == "object" && v !== null) v = JSON.stringify(v);

	        json.push((arr ? "" : '"' + n + '":') + String(v));
	    }

	    return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
	}
	};

	// implement JSON.parse de-serialization
	JSON.parse = JSON.parse || function (str) {
	if (str === "") str = '""';
	eval("var p=" + str + ";");
	return p;
	 };

}


var Base64 = {
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
    encode: function(input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },


    decode: function(input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    _utf8_encode: function(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    _utf8_decode: function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

}

var OMImage = {
    readFileName: function(file_name,file_original_name,path,module) {
    	if (path == null || path == undefined) {
    		path = "";
    	}
        if (file_name != null && file_name != undefined) {
			var chkFile = file_name.split(".");
			if(chkFile.length > 1){
				return "stocks/"+file_name;
			}else{
				var hasFile = file_name.match(/.{1,4}/g);
				var has_folder = hasFile[1].match(/.{1,2}/g);
				var fo_name = file_original_name.split(" ").join("_");
				if(path == ""){
					path = "/";
				}else{
					path = "/"+path+"/";
				}
				return "stocks/"+module+path+has_folder[0]+"/"+has_folder[1]+"/"+file_name+"/"+fo_name;
			}
		}else{
			return false;
		}
    }

}

function literalText(str){
	var tmp = document.createElement("div");
	tmp.innerHTML = str;
	return (tmp.firstChild ? tmp.firstChild.nodeValue : "");
}

function trim (str) {
	var str = str.replace(/^\s\s*/, ''),
		ws = /\s/,
		i = str.length;
	while (ws.test(str.charAt(--i)));
	return str.slice(0, i + 1);
}

function PadLeft(txtValue, totalDigits,padTxt) {
	txtValue = txtValue.toString(); 
	var pd = '';
	if (totalDigits > txtValue.length) { 
		for (i=0; i < (totalDigits - txtValue.length); i++) pd += padTxt.toString();
	}
	return pd + txtValue.toString() ;
}
function addCommas(nStr) {
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}
function Redirect(url){
	window.location.assign(url);
}

//-------------------------------------------------------
//             Ajax function
//-------------------------------------------------------

function drawLoading(objname){$("#" + objname).html("<div class=\"loadingbar\"><div class=\"bar\"><div class=\"txt\">LOADING...</div><img src=\"../core/images/list/loading.gif\" alt=\"loading\" /></div></div>");
}

function setCenterScreen(obj) {
	$(obj).css("top", ( $(window).height() - $(obj).height() ) / 2 + $(window).scrollTop() - 20 + "px");
    $(obj).css("left", "50%");
	$(obj).css("margin-left", -($(obj).width()/2)+"px");
}

function setTopScreen(obj) {
	$(obj).css("top", "16px");
    $(obj).css("left", "50%");
	$(obj).css("margin-left", -($(obj).width()/2)+"px");
}

function printButton(cmd, size, id) {
	var ret = "";
	if(size != "medium" && size != "medium2") { size = "medium"; }
	ret += "<a id=\"" + id + "\" class=\"button " + size + "\" ><span>" + cmd + "</span></a>";
	return(ret);
}

function Dialog(title, body, button, width){
	$("body").find(".dialog").remove();
	$("body").find(".dimmer").remove();




	fixIeSelectBoxHide();

	var btn_ret = "";
	ret = "<div class=\"dimmer\"></div><div class=\"dialog\">" 
	ret += "<div class=\"head\">"+ title + "</div>";
	ret += "<div class=\"main\">" + body + "</div>";
	
	if(button != null){
		ret += "<div class=\"foot\">";
		var running_id = 0;
		for (var i in button) {
			btn_ret = printButton(i,"middle","btn-"+running_id) + btn_ret;
			running_id++;
		}
		ret += btn_ret;
		ret += "</div>";
	}
	ret += "</div>";
	var height_dimmer = $(document).height();

	$("body").append(ret).find(".dimmer").css("height", height_dimmer);
	if(width > 0){ $("body .dialog").css("width", width);}
	setCenterScreen($(".dialog"));
	$("body .dialog .focus").focus();

	var running_id = 0;
	for(var i in button){
		if(button[i] != close) {
			$("body").find(".dialog #btn-"+running_id).bind('click', button[i] );
		} else {
			$("body").find(".dialog #btn-"+running_id).bind('click', function() { destroyDialog(); });
		}
		running_id++;
	}
}

function fixIeSelectBoxHide() {
	if($.browser.msie) {
		$("select").css("visibility","hidden");
	}
}
function isAnyDialogExists() {
	if ($(".dimmer, .dialog, #childDimmer, #childDialog").length > 0) {
		return true;
	} else {
		return false;
	}
}
function isChildDialogExists() {
	if ($("#childDimmer, #childDialog").length > 0) {
		return true;
	} else {
		return false;
	}
}
function isDialogExists() {
	if ($(".dimmer, .dialog").length > 0) {
		return true;
	} else {
		return false;
	}
}
function fixIeSelectBoxRecheck() {
	if ($.browser.msie && isAnyDialogExists() ) {
		fixIeSelectBoxHide();
	}
}
function fixIeSelectBoxShow() {
	
	if($.browser.msie) {
		if (!isAnyDialogExists()) {
			$("select").css("visibility","visible");
		} else if (isChildDialogExists()) {			
			$("#childDialog").find("select").css("visibility","visible");
		} 
	} 
}
function DialogEx(params){

	$("body").find(".dialog").remove();
	$("body").find(".dimmer").remove();




	fixIeSelectBoxHide();

	var btn_ret = "";
	ret = "<div class=\"dimmer\"></div><div class=\"dialog\">" 
	ret += "<div class=\"head\">"+ params["title"] + "</div>";
	ret += "<div class=\"main\">" + params["body"] + "</div>";
	if (params["width"] != undefined) {
		width = params["width"];
	} else {	
		width = 300;
	}
	
	if (params["foot"] != undefined) {
		ret += "<div class=\"foot\">" + params["foot"] + "</div>";
	}
	ret += "</div>";
	var height_dimmer = $(document).height();

	$("body").append(ret).find(".dimmer").css("height", height_dimmer);
	if(width > 0){ $("body .dialog").css("width", width);}
	setCenterScreen($(".dialog"));
	$("body .dialog .focus").focus();

	var running_id = 0;
	
	if (params["init"] != undefined) {
		params["init"]();
	}
}

function destroyDialog(){
	$("body").find(".dialog").remove();
	$("body").find(".dimmer").remove();



	fixIeSelectBoxShow();
}

function childDialog(title, body, button, width){

	$("body").find("#childDialog").remove();
	$("body").find("#childDimmer").remove();




	fixIeSelectBoxHide();

	var btn_ret = "";
	ret = "<div id=\"childDimmer\"></div><div id=\"childDialog\">" 
	ret += "<div class=\"head\">"+ title + "</div>";
	ret += "<div class=\"main\">" + body + "</div>";
	
	if(button != null){
		ret += "<div class=\"foot\">";
		var running_id = 0;
		for (var i in button) {
			btn_ret = printButton(i,"middle","btn-ch-"+running_id) + btn_ret;
			running_id++;
		}
		ret += btn_ret;
		ret += "</div>";
	}
	ret += "</div>";
	var height_dimmer = $(document).height();

	$("body").append(ret).find("#childDimmer").css("height", height_dimmer);
	if(width > 0){ $("body #childDialog").css("width", width);}
	setCenterScreen($("#childDialog"));
	$("body #childDialog .focus").focus();

	var running_id = 0;
	for(var i in button){
		if(button[i] != close) {
			$("body").find("#childDialog #btn-ch-"+running_id).bind('click', button[i] );
		} else {
			$("body").find("#childDialog #btn-ch-"+running_id).bind('click', function() { destroyChildDialog(); });
		}
		running_id++;
	}
}

function childDialogEx(params){

	$("body").find("#childDialog").remove();
	$("body").find("#childDimmer").remove();




	fixIeSelectBoxHide();

	var btn_ret = "";
	ret = "<div id=\"childDimmer\"></div><div id=\"childDialog\">" 
	ret += "<div class=\"head\">"+ params["title"] + "</div>";
	ret += "<div class=\"main\">" + params["body"] + "</div>";
	if (params["width"] != undefined) {
		width = params["width"];
	} else {	
		width = 300;
	}
	if (params["foot"] != undefined) {
		ret += "<div class=\"foot\">" + params["foot"] + "</div>";
	}	
		
	ret += "</div>";
	var height_dimmer = $(document).height();

	$("body").append(ret).find("#childDimmer").css("height", height_dimmer);
	if(width > 0){ $("body #childDialog").css("width", width);}
	setCenterScreen($("#childDialog"));
	$("body #childDialog .focus").focus();

	var running_id = 0;
	if (params["init"] != undefined) {
		params["init"]();
	}
}
function destroyChildDialog(){
	$("body").find("#childDialog").remove();
	$("body").find("#childDimmer").remove();



	fixIeSelectBoxShow();
}

function printPaginator(paginator, gotopage_func, width, add_func, context_func, custom_button) {

	var total_item = paginator.total_item;
	var item_per_page = paginator.item_per_page;
	var current_page = paginator.current;

	var from_item = 0;
	var to_item = 0;
	var total_page = 0;
	var ret = "";
	var ret2 = "";
	
	if(gotopage_func == undefined || gotopage_func == "") { gotopage_func = "goToPage"; }
	if(gotopage_func == "goToPageChild") { plus_obj_for_child = ",this"; } else { plus_obj_for_child = ""; }
	
	from_item = ((current_page - 1) * item_per_page) + 1;
	to_item = from_item + item_per_page - 1;
	if(to_item > total_item) { to_item = total_item; }
	total_page = Math.ceil(total_item/item_per_page);

	var start_draw_page = current_page - 3;
	if(start_draw_page < 1) { start_draw_page = 1; }
	
	var stop_draw_page = start_draw_page + 6;
	if(stop_draw_page > total_page) { stop_draw_page = total_page; }

	ret += "<div class=\"datagridfooter\" style=\"width:" + width + "px;\">";
	var empty_link = "javascript:void(0);"
	if ( $.browser.msie && Number($.browser.version) < 7 ) {
		empty_link = "#";
	}
	if(add_func != undefined && add_func != "") {
		ret += "<div class=\"paginator left\">";
		ret += "	<a href=\"" + empty_link + "\" onclick=\"" + add_func + "(this);\" class=\"btn-add\"></a>";
		ret += "</div>";
	}

	if(context_func != undefined && context_func != "") {
		ret += "<div class=\"paginator left\">";
		ret += "	<a href=\"" + empty_link + "\" onclick=\"" + context_func + "(this);\" class=\"btn-cmd\"></a>";
		ret += "</div>";
	}
	if(custom_button != undefined &&  custom_button["fn_name"] != undefined && custom_button["fn_name"] != "") {
		ret += "<div class=\"paginator left\">";
		ret += "	<a href=\"" + empty_link + "\" onclick=\"" + custom_button["fn_name"] + "(this);\" class=\"" + custom_button["btn_class"] + "\"></a>";
		ret += "</div>";
	}	

	if(item_per_page < total_item) {
		ret += "<div class=\"paginator right\">";
		if(current_page > 1){ ret+= "<a href=\"#\" onclick=\"" + gotopage_func + "(" + (current_page-1) + plus_obj_for_child + ")\" class=\"prevnext\">&#9668;</a>"; } else { ret+= "<span class=\"none\">&#9668;</span>"; }
		if(start_draw_page > 4){ 
			ret += "<a href=\"#\" onclick=\"" + gotopage_func + "(1" + plus_obj_for_child + ")\">1</a><a href=\"#\" onclick=\"" + gotopage_func + "(2" + plus_obj_for_child + ")\">2</a><span class=\"none\">...</span>"; 
		} else { 
			start_draw_page = 1; 
		}
		
		if(stop_draw_page < (total_page - 3)){
			ret2 += "<span class=\"none\">...</span><a href=\"#\" onclick=\"" + gotopage_func + "(" + (total_page-1) + plus_obj_for_child + ")\">" + (total_page-1) + "</a><a href=\"#\" onclick=\"" + gotopage_func + "(" + total_page + plus_obj_for_child + ")\">" + total_page + "</a>";
		} else { 
			stop_draw_page = total_page; 
		}

		for(i = start_draw_page; i <= stop_draw_page; i++){
			if(i == current_page) { 
				ret += "<span class=\"current\">" + i + "</span>";
			} else {
				ret += "<a href=\"#\" onclick=\"" + gotopage_func + "(" + i + plus_obj_for_child + ")\">" + i + "</a>";
			}
		}
	
		ret += ret2;
		if(current_page < total_page){ ret+= "<a href=\"#\" onclick=\"" + gotopage_func + "(" + (current_page+1) + plus_obj_for_child + ")\"class=\"prevnext\">&#9658;</a>"; } else { ret+= "<span class=\"none\">&#9658;</span>"; }
		ret += "</div>";
	}

	if(total_item > 0) {
		ret += "<div class=\"paginator left\"><span class=\"item_number\">";
		ret += addCommas(from_item) + " - " + addCommas(to_item) + " of " + addCommas(total_item) + " item";
		if(total_item > 1) { ret += "s"; }
		ret += "</span></div>";
	}

	ret += "<div class=\"clearfix\"></div>";
	ret += "</div>";
	return(ret);
}

//-------------------------------------------------------
//             Listing function
//-------------------------------------------------------
function mouseOverItem(obj, contextobj, contextcallback){
	if($('#' + contextobj).css("display") == "none" || contextcallback){
		$(obj).addClass('over');
	}
}

function mouseOutItem(obj, contextobj, contextcallback){
	if($('#' + contextobj).css("display") == "none" || contextcallback){
		$(obj).removeClass('over');
	}
}

function setCheckBoxSelected() {
	var tmpCheck = getItemChecked();

	for (var key in tmpCheck) {
		if (tmpCheck.hasOwnProperty(key) && $("#list_data tr[ref='"+tmpCheck[key]+"']").size() == 1) {
			manualCheckItem($("#list_data tr[ref='"+tmpCheck[key]+"'] .choose input"));
		}
	}
}

function updateItemChecked(mode) {
	if (mode == "uncheck") {
		clearItemChecked();
	}
	var tmpCheck = getItemChecked();
	var listChecked = $("#list_data input[type='checkbox']");

	for (var i=0;i<listChecked.length;i++) {
		var refStr = $(listChecked[i]).parent().parent().attr("ref");
		var refObj = refStr.split(":");

		if($(listChecked[i]).is(':checked')) {
			tmpCheck[refObj[0]+"_"+refObj[2]] = refStr;
		} else {
			delete(tmpCheck[refObj[0]+"_"+refObj[2]]);
		}
	}

	setItemChecked(tmpCheck);
}

function clearItemChecked() {
	setItemChecked(new Object());
}

function setItemChecked(data) {
	$("#list_item_checked").val(Base64.encode(JSON.stringify(data)));
}

function getItemChecked(mode) {
	var listCheck = new Object();
	if ($("#list_item_checked").val() != "") {
		listCheck = eval('('+Base64.decode($("#list_item_checked").val())+')');
	}
	if (mode == "size") {
		return Object.keys(listCheck).length;
	} else {
		return listCheck;
	}
}

function mouseClickItem(obj, event, contextobj, contextcallback){

	if(_listcontrol.customMouseClickItem != undefined && !_listcontrol.fwd_checkbox_click && (event.button != 2)) {
		_listcontrol.customMouseClickItem(obj);
	} else {
		if($('#' + contextobj).css("display") == "none" || contextcallback) {
			if(event.stopPropagation) { event.stopPropagation(); } else { window.event.cancelBubble = true; }
			var ref_arr = $(obj).attr('ref').split(':');
			
			if(event.shiftKey) {
				if(_gridcontrol.lastchoose == -1) {
				} else {
					var diff = ref_arr[3] - _gridcontrol.lastchoose;
					var start_shift_choose;
					var stop_shift_choose;
					if(diff > 0) { 
						start_shift_choose = _gridcontrol.lastchoose;
						stop_shift_choose = ref_arr[3];				
					} else {
						start_shift_choose = ref_arr[3];
						stop_shift_choose = _gridcontrol.lastchoose;
					}
					var row_index = ref_arr[3] - diff;
					$(obj).parent().find('tr').each(function(index){
						if(start_shift_choose <= index && index <= stop_shift_choose) {
							$(this).addClass('checked').find('.choose input[type=checkbox]').attr('checked',true);
							updateItemChecked("check");
						} else {
							if(!(event.ctrlKey || event.metaKey)) { 
								$(this).removeClass('checked').find('.choose input[type=checkbox]').attr('checked',false); 
							}
						}
					});
				}
			} else {
				if(!(event.ctrlKey || event.metaKey)) {
					$(obj).parent().find('tr.checked').removeClass('checked').find('.choose input[type=checkbox]:checked').attr('checked',false);
				}
				if($(obj).hasClass('checked')){
					$(obj).removeClass('checked').find('.choose input[type=checkbox]').attr('checked',false);
				} else {
					$(obj).addClass('checked').find('.choose input[type=checkbox]').attr('checked',true);
					_gridcontrol.lastchoose = ref_arr[3];
					updateItemChecked("check");
				}
			}
		}
	}

	_listcontrol.fwd_checkbox_click = false;
	return false;
}

function manualCheckItem(obj) {
	$(obj).attr('checked',true);
	$(obj).parent().parent().addClass('checked');
	var ref_arr = $(obj).parent().parent().attr('ref').split(':');
	_gridcontrol.lastchoose = ref_arr[3];
}

function checkItem(obj,event, contextobj, contextcallback) {
	if(!event.shiftKey) {
		if($('#' + contextobj).css("display") == "none" || contextcallback){
			if(event.stopPropagation) { event.stopPropagation(); } else { window.event.cancelBubble = true; }
			if($(obj).is(":checked")){
				manualCheckItem(obj);
				updateItemChecked("check");
			} else {
				$(obj).parent().parent().removeClass('checked');
			}
		} else {
			if($(obj).is(":checked")){
				$(obj).attr('checked',false);
			} else {
				$(obj).attr('checked',true);
			}
		}
	} else {
		_listcontrol.fwd_checkbox_click = true;
	}
	return false;
}
//-------------------------------------------------------
//             Finder function
//-------------------------------------------------------

function loadFinderLevel(tbl, referer_id, load_id, lang) {

	$.ajax({
			url: 'finder_data.php',
			data: {'r':referer_id, 't':tbl, 's':load_id, 'l':lang},
			type: 'POST', cache: false, timeout: 10000,
			dataType: 'json',
			error: function() {
				return false;
			},
			success: function(result) {
				if(result == "ERR"){
					alert("ERR");
				} else if(result == "SESSION_TIMEOUT") {
					Redirect(_redirect_timeout);
				} else {
					var ul_class;
					var drawnext = true;
					while(drawnext){
						var ret = "";
						for(var i = 0; i < result["lv" + referer_id].num; i++){
							if(result["lv" + referer_id].dat[i].c != 0){ ul_class = " class=\"haschild\""; } else { ul_class = ""; }
							ret += "<ul rel=\"" + result["lv" + referer_id].dat[i].i + "\" " + ul_class + ">" + result["lv" + referer_id].dat[i].l + "</ul>";
                            if(load_id > 0 && load_id == result["lv" + referer_id].dat[i].i) {
                                $("#finderLastSelectedLabel").html(result["lv" + referer_id].dat[i].l);
                            }
						}
	
						$('#finderContainer').find('.column.pid' + referer_id).html(ret)
						.find('ul').click(function(){
						
                                $("#finderLastSelectedId").html($(this).attr("rel"));
                                $("#finderLastSelectedLabel").html($(this).html());
								$(this).parent().find('ul').removeClass("selected");
								$(this).addClass("selected");
								$(this).parent().parent().nextAll().remove();
	
								if($(this).hasClass("haschild")) {
									var ret = "<td><img src=\"../core/images/blank.gif\" width=\"200\" height=\"1\" alt=\"\"><div class=\"column pid" + $(this).attr("rel") + "\"><div class=\"loading\"><br/><br/><br/><img src=\"../core/images/finder/loading.gif\"></div></div></td>";
									$(this).parent().parent().after(ret);
									
									loadFinderLevel(tbl, $(this).attr("rel"), -1, $("#fwdparam_lang").val());
								}
						});
						
						var contentwidth = $('#finderContainer').scrollLeft() + $('#finderContainer').find('.column.pid' + referer_id).position().left + $('#finderContainer').find('.column.pid' + referer_id).width();
						var container_width = $('#finderContainer').width();
						var newscrollLeft = contentwidth - container_width;
						if(newscrollLeft > 0) {
							$('#finderContainer').animate({"scrollLeft":newscrollLeft}, "fast");
						}
						
						if(result["lv" + referer_id] != undefined && result["lv" + referer_id].sel != undefined){
							var next_referer_id = result["lv" + referer_id].sel;
							
							$('#finderContainer').find('.column.pid' + referer_id + ' ul[rel=' + next_referer_id + ']').addClass("selected");
							
							if(result["lv" + next_referer_id] != undefined) {
								var ret = "<td><img src=\"../core/images/blank.gif\" width=\"200\" height=\"1\" alt=\"\"><div class=\"column pid" + next_referer_id + "\"><div class=\"loading\" style=\"text-align:center;\"><br/><br/><br/><img src=\"../core/images/finder/loading.gif\"></div></div></td>";
								$('#finderContainer').find('.column.pid' + referer_id).parent().after(ret);
								referer_id = next_referer_id;
								drawnext = true;
							} else {
								drawnext = false;
							}
						} else {
							drawnext = false;
						}
					}
				}
				return false;
			}
	});
}

function drawFinder(tbl, title, width, load_id, callBackChoose){
	if($.browser.msie) {
		$("select").css("visibility","hidden");
	}
	var ret = "";
	ret += "<div id=\"finderDimmer\"></div>";
	ret += "<div id=\"finderDialog\">";
	ret += "<div class=\"head\">" + title + "</div>";
	ret += "<div id=\"finderContainer\">";
	ret += "<table class=\"allcolumn\" cellspacing=\"0\" cellpadding=\"0\">";
	ret += "<tr>";
	ret += "<td><img src=\"../core/images/blank.gif\" width=\"200\" height=\"1\" alt=\"\"><div class=\"column pid0\"><div class=\"loading\" style=\"text-align:center;\"><br/><br/><br/><img src=\"../core/images/finder/loading.gif\"></div></div></td>";
	ret += "</tr></table>";
	ret += "</div>";    
	ret += printButton("Choose","medium2", "finderChoose");
	ret += printButton("Cancel","medium2", "finderCancel");
    ret += "<span id=\"finderLastSelectedId\" style=\"visibility:hidden;\">" + load_id + "</span>";
    ret += "<span id=\"finderLastSelectedLabel\" style=\"visibility:hidden;\"></span>";    
	ret += "</div>";

	var height_dimmer = $(document).height();
	
	$("body").append(ret).
	find("#finderDimmer").css("height", height_dimmer);
	if(width > 0){ 
		$("body #finderDialog").css("width", width);
		$("body #finderDialog #finderContainer").css("width", width - 10);
	}

	setCenterScreen($("#finderDialog"));
	$("body").find("#finderDialog #finderChoose").bind('click', function() {        
        if(callBackChoose != undefined && callBackChoose != null) {
            callBackChoose($("#finderLastSelectedId").html(), $("#finderLastSelectedLabel").html());
        }
        destroyFinder(); 
    });
	$("body").find("#finderDialog #finderCancel").bind('click', function() { destroyFinder(); });
	loadFinderLevel(tbl, 0, load_id, $("#fwdparam_lang").val());
}

function destroyFinder(){
	$("body").find("#finderDialog").remove();
	$("body").find("#finderDimmer").remove();

	if($.browser.msie) {
		$("select").css("visibility","visible");
	}
}
clearFinderInput = function(elname) {
	$("#"+elname).val("0");
	$("#"+elname+"_value").val("");
}

function destroyLoadingFullPage(){
	$("body").find("#loadingDimmer").remove();
	$("body").find("#loadingIcon").remove();
}

function drawLoadingFullPage(){
	$("body").find("#loadingDimmer").remove();
	$("body").find("#loadingIcon").remove();

	if($.browser.msie) {
		$("select").css("visibility","hidden");
	}
	
	var ret = "<div id=\"loadingDimmer\"></div><div id=\"loadingIcon\"><img src=\"../core/images/loading_big.gif\" alt=\"\" /></div>";

	var height_dimmer = $(document).height();
	
	$("body").append(ret).
	find("#loadingDimmer").css("height", height_dimmer);
	setCenterScreen($("#loadingIcon"));
}
//-------------------------------------------------------
//             Fix PNG Transparent
//-------------------------------------------------------


function fixPNG() {
	if ($.browser.msie) {
		var arVersion = navigator.appVersion.split("MSIE");
		var version = parseFloat(arVersion[1]);
		
		if ((version >= 5.5) && (version <= 6) && (document.body.filters)) {
			for(var i=0; i<document.images.length; i++) {
				var img = document.images[i];
				var imgName = img.src.toUpperCase();
				if (imgName.substring(imgName.length-3, imgName.length) == "PNG") {
					var imgID = (img.id) ? "id='" + img.id + "' " : "";
					var imgClass = (img.className) ? "class='" + img.className + "' " : "";
					var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' ";
					var imgStyle = "display:inline-block;" + img.style.cssText ;
					if (img.align == "left") imgStyle = "float:left;" + imgStyle;
					if (img.align == "right") imgStyle = "float:right;" + imgStyle;
					if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle;
					var strNewHTML = "<span " + imgID + imgClass + imgTitle
					+ " style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";"
					+ "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
					+ "(src=\'" + img.src + "\', sizingMethod='image');\"></span>";
					img.outerHTML = strNewHTML;
					i = i-1;
				}
			}
		}
	}
}

//-------------------------------------------------------
//             Fix Table Scrollbar
//-------------------------------------------------------


function fixTableScroll(objname) {
	if ($.browser.msie) {
		var scrollbar_width = $.getScrollbarWidth();
		var table_data_obj = '#' + objname + ' .table_data'
		$(table_data_obj).scrollLeft(1);
		if ($(table_data_obj).scrollLeft() != 0) {
			if (parseInt($.browser.version) < 8){
				$(table_data_obj).css({ 'padding-bottom' : (scrollbar_width) +'px', 'overflow-y' : 'hidden' });
			}else{
				$(table_data_obj).height((parseInt($(table_data_obj).css("height") ) +scrollbar_width )+ "px");
			}
		}
	}
}

function loadjscssfile(filename, filetype){
 if (filetype=="js"){ //if filename is a external JavaScript file
  var fileref=document.createElement('script')
  fileref.setAttribute("type","text/javascript")
  fileref.setAttribute("src", filename)
 }
 else if (filetype=="css"){ //if filename is an external CSS file
  var fileref=document.createElement("link")
  fileref.setAttribute("rel", "stylesheet")
  fileref.setAttribute("type", "text/css")
  fileref.setAttribute("href", filename)
 }
 if (typeof fileref!="undefined")
  document.getElementsByTagName("head")[0].appendChild(fileref)
}
var filesadded="" //list of files already added
function checkloadjscssfile(filename, filetype){
 if (filesadded.indexOf("["+filename+"]")==-1){
  loadjscssfile(filename, filetype)
  filesadded+="["+filename+"]" //List of files added in the form "[filename1],[filename2],etc"
 }
 else
  alert("file already added!")
}

//-------------------------------------------------------
//             Import Export function
//-------------------------------------------------------
function exportData(mode) {
	var allTarget = getItemChecked();
	var listExport = new Array();

	if (mode != null && mode != undefined && mode == "all") {
		listExport.push("all");
	} else {
		for (var key in allTarget) {
			if (allTarget.hasOwnProperty(key)) {
				listExport.push(allTarget[key]);
			}
		}
	}
	
	if (listExport.length > 0) {
		var list_export = JSON.stringify(listExport);
		ajaxDownload('list_cmd.php', { 'c': 'export_data' , '__': (new Date()).getTime(),'list_export':list_export } );
	} else {
		Dialog("Error", "Please select item for export.",{"OK":close});
	}
}

function ajaxDownload(url, data) {
    var $iframe,
        iframe_doc,
        iframe_html;

    if (($iframe = $('#download_iframe')).length === 0) {
        $iframe = $("<iframe id='download_iframe' style='display: none' src='about:blank'></iframe>").appendTo("body");
    }

    iframe_doc = $iframe[0].contentWindow || $iframe[0].contentDocument;
    if (iframe_doc.document) {
        iframe_doc = iframe_doc.document;
    }

    var indexForm = $(iframe_doc).find('form').size();
    indexForm++;

    iframe_html  = "<html><head></head><body><form id='form_download_"+indexForm+"' method='POST' action='" + url +"'>";
    for (var k in data){
	    if (data.hasOwnProperty(k)) {
			iframe_html += "<input type=hidden name='" + k + "' value='" + data[k] +"'/>";
	    }
	}
    iframe_html += "</form></body></html>";
    iframe_doc.open();
    iframe_doc.write(iframe_html);

    $(iframe_doc).find('#form_download_'+indexForm).submit();
}

//-------------------------------------------------------
//             Notify function
//-------------------------------------------------------

function showNotify(cmd,msg,type,width) {
	clearItemChecked();
	$("#notify-wrapper").stop(true,true);

	if (msg == null || msg == undefined) {
		if (cmd == "import_data") {
			msg = "Import data";
		} else if (cmd == "publish") {
			msg = "Publish data";
		} else if (cmd == "delete") {
			msg = "Delete data";
		} else if (cmd == "delete_noapproval") {
			msg = "Delete data";
		} else if (cmd == "unpublish") {
			msg = "Unpublish data";
		} else if (cmd == "unpublishwithdraft") {
			msg = "Unpublish data";
		} else if (cmd == "unpublishwithrecent") {
			msg = "Unpublish data";
		} else if (cmd == "unpublishwithdraft") {
			msg = "Unpublish data";
		} else if (cmd == "mergelang") {
			msg = "Merge lang data ";
		}
	}

	if (type == null || type == undefined || type == "OK") {
		type = "default";
		msg += " success.";
	} else if (type == "WAR") {
		type = "warning";
		msg += " success.";
	} else {
		type = "error";
		msg += " error.";
	}

	if (width == null || width == undefined) {
		width = 200;
	}

	var ret = "";
	ret += '<div id="notify-wrapper" class="'+type+'" style="width:'+width+'px;">';
	ret += '	<div id="notify" class="server-success" style="display: inline-block;">';
	ret += '		<div id="notify-msg">';
	ret += 				msg;
	ret += '		</div>';
	ret += '	</div>';
	ret += '</div>';

	$("#contain_notify").html(ret);
	setTopScreen($("#notify-wrapper"));

	if ($.browser.msie) {
		$("#notify-wrapper").fadeIn("fast").delay( 3000 ).fadeOut( "fast" );
	} else {
		var backupTop = $("#notify-wrapper").css("top").split("px").join("");
		$("#notify-wrapper").css("top",backupTop-50);
		$("#notify-wrapper").css("opacity",0);
		$("#notify-wrapper").show();

		$("#notify-wrapper").animate({
			opacity: 1,
			top: backupTop
		}, 500, function() {
			$("#notify-wrapper").delay( 3000 ).animate({
				opacity: 0,
				top: backupTop-50
			}, 500, function() {
				$("#notify-wrapper").hide();
			});
		});
	}
}

function hideNotify() {
	if ($.browser.msie) {
		$("#notify-wrapper").stop(true,true).fadeOut("fast");
	}
}

