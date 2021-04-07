/*
 * 
 */
function OMValidate() {
}

OMValidate.prototype.check = function(value, params) {
	var MSG_TEMPLATE = {
		"TEXTLIMIT": "\"[[name]]\" field must be between [[min]] and [[max]] characters long.",
		"TEXTLIMITGTE": "\"[[name]]\" field must be longer than or equal to [[min]] characters long.",
		"TEXTLIMITLTE": "\"[[name]]\" field must be shorter than or equal to [[max]] characters long.",
		"REQUIRE": "\"[[name]]\" field is required.",
		"INTEGER": "\"[[name]]\" field must be integer.",
		"NUMBER": "\"[[name]]\" field must be number.",
		"NUMBERLIMIT": "\"[[name]]\" field must be between [[min]] and [[max]] .",		
		"CHARSET_NUMERIC": "Only numbers are allowed",
		"CHARSET_ALPHANUMERIC": "Only letters and numbers are allowed",
		"CHARSET_ALPHABETIC": "Only letters are allowed",
		"CHARSET_NUMERIC_TH": "Only numbers are allowed",
		"CHARSET_ALPHANUMERIC_TH": "Only letters and numbers are allowed",
		"CHARSET_ALPHABETIC_TH": "Only letters are allowed",
		"CHARSET_REGULAR": "[[error_message]]",
		"VALIDATE_JS": "[[error_message]]",
		"INVALID_DATE": "\"[[name]]\" field must be date.",
		"INVALID_TIME": "\"[[name]]\" field must be time.",
		"INVALID_DATETIME": "\"[[name]]\" field must be datetime.",
		"INVALID_IPADDRESS": "\"[[name]]\" field must be ip address.",
		"UNKNOW":"UNKNOW"
	};
	var formatMessage = function(key, params) {
		var msg;
		var i;
		if (MSG_TEMPLATE[key] == undefined || MSG_TEMPLATE[key] == null) {
			return "UNKNOW";
		}
		msg = MSG_TEMPLATE[key];
		if (params != null) {
			for (k in params) {
				msg = msg.replace("[[" + k + "]]", params[k]);
			}
		}
		return msg;
	}
	var isSet = function(v) {
		if (v == null || v == undefined) return false;
		return true;
	};
	var isTrue = function(v) {
		if (v == null || v == undefined || v == false) return false;
		if (v == true) return true;
		vl = v.toLowerCase();
		if (vl == "yes") return true;
		if (vl == "true") return true;
		if (vl == "t") return true;		
		return false;
	};
	var ptype="";	
	ptype = params["type"].toLowerCase();
	var retval = null;
	if (isSet(params["validate_type"]) && params["validate_type"].toLowerCase() == "js") {
		if (!eval(params["validate_js_function"] + "(value,params)")) return formatMessage("VALIDATE_JS",{"name":params["title"],"error_message":eval(params["validate_js_error_function"] + "(value,params)")});
		return null;
	}	
	switch (ptype) {
		case "string":
		case "text":
			if (!isTrue(params["require"]) && (value == undefined || value == null || value == "" || value.length==0)) {
				return null;
			}			
			if (isTrue(params["require"]) && (value == undefined || value == null || value == "" || value.length==0)) {
				return formatMessage("REQUIRE",{"name":params["title"]});								
			}

			if (isSet(params["max"]) && params["max"]>=0 && params["max"] < value.length)  {
				if (params["min"]==null || params["min"] == undefined || params["min"]<=0) {
					return formatMessage("TEXTLIMITLTE",{"name":params["title"], "max":params["max"], "min":params["min"]});					
				} else {
					return formatMessage("TEXTLIMIT",{"name":params["title"], "max":params["max"], "min":params["min"]});									
				}
			}
			if (isSet(params["min"]) && params["min"]>=0 && params["min"] > value.length)  {
				if (params["max"]==null || params["max"] == undefined || params["max"] <= 0) {
					return formatMessage("TEXTLIMITGTE",{"name":params["title"], "max":params["max"], "min":params["min"]});					
				} else {
					return formatMessage("TEXTLIMIT",{"name":params["title"], "max":params["max"], "min":params["min"]});									
				}
			}
			
			if (isSet(params["charset"])) {
				switch (params["charset"].toLowerCase()) {
					case "numeric":
						var regexp = /^\d+$/;
						if (!regexp.test(value)) return formatMessage("CHARSET_NUMERIC",{"name":params["title"]});																	
						break;
					case "alphanumeric":
						var regexp = /^[a-zA-Z0-9]+$/;
						if (!regexp.test(value)) return formatMessage("CHARSET_ALPHANUMERIC",{"name":params["title"]});	
						break;
					case "alphanumeric_th":
						var regexp = /^[a-zA-Z0-9\u0e00-\u0e7f]+$/;
						if (!regexp.test(value)) return formatMessage("CHARSET_ALPHANUMERIC_TH",{"name":params["title"]});	
						break;						
					case "alpha":
					case "alphabetic":
						var regexp = /^[a-zA-Z]+$/;
						if (!regexp.test(value)) return formatMessage("CHARSET_ALPHABETIC",{"name":params["title"]});	
						break;
					case "alpha_th":
					case "alphabetic_th":
						var regexp = /^[a-zA-Z\u0e00-\u0e7f]+$/;
						if (!regexp.test(value)) return formatMessage("CHARSET_ALPHABETIC_TH",{"name":params["title"]});
						break;
					case "ipaddress":					
						var regexp;						
						regexp = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
						if (!regexp.test(value)) return formatMessage("INVALID_IPADDRESS",{"name":params["title"]});							
						break;
					case "regular":
						var regexp = new RegExp(params["regular"]);
						if (!regexp.test(value)) return formatMessage("CHARSET_REGULAR",{"name":params["title"],"error_message":params["regular_error_message"]});
						break;
					default:
						break;						
				}
			}
			return null;
			break;
		case "email":
			if (!isTrue(params["require"]) && value.length==0) {
				return null;
			}		
			if (isTrue(params["require"]) && value.length==0) {
				return formatMessage("REQUIRE",{"name":params["title"]});								
			}		
			var regexp = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
			if (!regexp.test(value)) return formatMessage("EMAIL");
			return null;
			break;
		case "integer_without_comma":
			if (!isTrue(params["require"]) && value.length==0) {
				return null;
			}		
			if (isTrue(params["require"]) && value.length==0) {
				return formatMessage("REQUIRE",{"name":params["title"]});								
			}		
			var regexp = /^(-?)(\d+|(\d{1,3}(,\d{3})*))$/;    
			if (!regexp.test(value)) return formatMessage("INTEGER",{"name":params["title"]});
			var value = value.replace(/,/g,"");
			if (isNaN(value)) return formatMessage("INTEGER",{"name":params["title"]});
			var v = parseInt(value,10);
			if (isSet(params["max"]) && params["max"] < v)  {
				return formatMessage("NUMBERLIMIT",{"name":params["title"],"max":params["max"],"min":params["min"]});
			}
			if (isSet(params["min"]) && params["min"] > v)  {
				return formatMessage("NUMBERLIMIT",{"name":params["title"],"max":params["max"],"min":params["min"]});
			}
			return null;
			break;
		case "integer":
			if (!isTrue(params["require"]) && value.length==0) {
				return null;
			}		
			if (isTrue(params["require"]) && value.length==0) {
				return formatMessage("REQUIRE",{"name":params["title"]});								
			}		
			value = value.replace(/,/g,"");
			var regexp = /^(-?)(\d+|(\d{1,3}(,\d{3})*))$/;    
			if (!regexp.test(value)) return formatMessage("INTEGER",{"name":params["title"]});
			var value = value.replace(/,/g,"");
			if (isNaN(value)) return formatMessage("INTEGER",{"name":params["title"]});
			var v = parseInt(value,10);
			if (isSet(params["max"]) && params["max"] < v)  {
				return formatMessage("NUMBERLIMIT",{"name":params["title"],"max":params["max"],"min":params["min"]});
			}
			if (isSet(params["min"]) && params["min"] > v)  {
				return formatMessage("NUMBERLIMIT",{"name":params["title"],"max":params["max"],"min":params["min"]});
			}	
			return null;			
			break;
		case "number_without_comma":
		case "numeric_without_comma":
			if (!isTrue(params["require"]) && value.length==0) {
				return null;
			}		
			if (isTrue(params["require"]) && value.length==0) {
				return formatMessage("REQUIRE",{"name":params["title"]});								
			}		
			var regexp = /^(-?)(\d+|(\d{1,3}(,\d{3})*))(\.\d+)?$/;			
			if (!regexp.test(value)) return formatMessage("NUMBER",{"name":params["title"]});
			value = value.replace(/,/g,"");
			if (isNaN(value)) return formatMessage("NUMBER",{"name":params["title"]});
			var v = parseFloat(value);
			if (isSet(params["max"]) && params["max"] < v)  {
				return formatMessage("NUMBERLIMIT",{"name":params["title"],"max":params["max"],"min":params["min"]});
			}
			if (isSet(params["min"]) && params["min"] > v)  {
				return formatMessage("NUMBERLIMIT",{"name":params["title"],"max":params["max"],"min":params["min"]});
			}		
			return null;
			break;
		case "decimal":
		case "number":
		case "numeric":
			if (!isTrue(params["require"]) && value.length==0) {
				return null;
			}		
			if (isTrue(params["require"]) && value.length==0) {
				return formatMessage("REQUIRE",{"name":params["title"]});								
			}		
			value = value.replace(/,/g,"");
			var regexp = /^(-?)(\d+|(\d{1,3}(,\d{3})*))(\.\d+)?$/;			
			if (!regexp.test(value)) return formatMessage("NUMBER",{"name":params["title"]});
			value = value.replace(/,/g,"");
			if (isNaN(value)) return formatMessage("NUMBER",{"name":params["title"]});
			var v = parseFloat(value);
			if (isSet(params["max"]) && params["max"] < v)  {
				return formatMessage("NUMBERLIMIT",{"name":params["title"],"max":params["max"],"min":params["min"]});
			}
			if (isSet(params["min"]) && params["min"] > v)  {
				return formatMessage("NUMBERLIMIT",{"name":params["title"],"max":params["max"],"min":params["min"]});
			}
			return null;
			break;
		case "date":
			if (!isTrue(params["require"]) && value.length==0) {
				return null;
			}		
			if (isTrue(params["require"]) && value.length==0) {
				return formatMessage("REQUIRE",{"name":params["title"]});								
			}		
			var regexp;
			var d,m,y;
			var format = params["format"];
			switch (format) {
				case "ymd":
					regexp = /^(\d{2}|\d{4})[\/-](\d{1,2})[\/-](\d{1,2})$/;
					if (!regexp.test(value)) return formatMessage("INVALID_DATE",{"name":params["title"]});
					info = regexp.exec(value);
					y = parseInt(info[1],10);
					m = parseInt(info[2],10);
					d = parseInt(info[3],10);
					break;

				case "dmy":
					regexp = /^(\d{1,2})[\/-](\d{1,2})[\/-](\d{2}|\d{4})$/;
					if (!regexp.test(value)) return formatMessage("INVALID_DATE",{"name":params["title"]}); 
					info = regexp.exec(value);
					d = parseInt(info[1],10);
					m = parseInt(info[2],10);
					y = parseInt(info[3],10);
					break;
				case "":
				case "yyyymmdd":
				default:            
					regexp = /^(\d{4})[\/-]?(\d{2})[\/-]?(\d{2})$/;
					if (!regexp.test(value)) return formatMessage("INVALID_DATE",{"name":params["title"]}); 
					info = regexp.exec(value);
					y = parseInt(info[1],10);
					m = parseInt(info[2],10);
					d = parseInt(info[3],10);
					break;            
			}  
			if (d < 1 || d > this.maxDayOfMonth(m,y)) return formatMessage("INVALID_DATE",{"name":params["title"]}); 
			if (m < 1 || m > 12) return formatMessage("INVALID_DATE",{"name":params["title"]});  
			return null;  			
			break;
		case "time":
			if (!isTrue(params["require"]) && value.length==0) {
				return null;
			}		
			if (isTrue(params["require"]) && value.length==0) {
				return formatMessage("REQUIRE",{"name":params["title"]});								
			}		
			var regexp;
			var h,m,s;
			
			regexp = /^(\d{1,2})[:](\d{1,2})([:](\d{0,2}))?$/;
			if (!regexp.test(value)) return formatMessage("INVALID_TIME",{"name":params["title"]});	
			info = regexp.exec(value);
			h = parseInt(info[1],10);
			m = parseInt(info[2],10);
			if ( info[4] != undefined) {
				s = parseInt(info[4],10);
			} else {
				s = 0;
			}
			if (h < 0 || h > 23) return formatMessage("INVALID_TIME",{"name":params["title"]});	
			if (m < 0 || m > 59) return formatMessage("INVALID_TIME",{"name":params["title"]});	
			if (s < 0 || s > 59) return formatMessage("INVALID_TIME",{"name":params["title"]});	
			return null;
			break;
		case "datetime":
			if (!isTrue(params["require"]) && value.length==0) {
				return null;
			}		
			if (isTrue(params["require"]) && value.length==0) {
				return formatMessage("REQUIRE",{"name":params["title"]});								
			}		
			var regexp;
			var format = params["format"];
			switch (format) {
				case "dmy":				
				default:
					regexp = /^(\d{1,2})[\/-](\d{1,2})[\/-](\d{2}|\d{4}) (\d{1,2})[:](\d{1,2})([:](\d{0,2}))?$/;
					if (!regexp.test(value)) return formatMessage("INVALID_DATETIME",{"name":params["title"]});
					break;
			}			
			return null;
			break;
		case "dropdown":
			if (!isTrue(params["require"]) && (value == null || value == undefined || value == "")) {
				return null;
			}		
			if (isTrue(params["require"]) && (value == null || value == undefined || value == "")) {
				return formatMessage("REQUIRE",{"name":params["title"]});								
			}
			break;
		case "radio":
			if (!isTrue(params["require"]) && (value == null || value == undefined)) {
				return null;
			}		
			if (isTrue(params["require"]) && (value == null || value == undefined)) {
				return formatMessage("REQUIRE",{"name":params["title"]});								
			}
			break;		
		case "file":
			if (!isTrue(params["require"]) && (value == null || value == undefined || value == "")) {
				return null;
			}		
			if (isTrue(params["require"]) && (value == null || value == undefined || value == "")) {
				return formatMessage("REQUIRE",{"name":params["title"]});								
			}
			break;
		case "image":
			if (!isTrue(params["require"]) && (value == null || value == undefined || value == "")) {
				return null;
			}		
			if (isTrue(params["require"]) && (value == null || value == undefined || value == "")) {
				return formatMessage("REQUIRE",{"name":params["title"]});								
			}
			break;
		default:							
			return "Invalid parameters.";
	}

	return retval;
};

OMValidate.prototype.maxDayOfMonth = function (m, y) {
    switch (m) {
        case 4:
        case 6:
        case 9:
        case 11:
            return 30;
        case 2:
            if (y != null) {
                var yy = y;
                if (yy > 2300) yy = yy - 543;
                if (yy > 1000 && yy < 2200) {
                    if ((yy % 400 ==0) || ((yy % 4 == 0) && (yy % 100 !=0))) {
                        return 29;
                    }  else {
                        return 28;
                    }
                }
            }
            return 29;
        default:
            return 31;
    }
}


$V = new OMValidate();
VALIDATE = $V;
