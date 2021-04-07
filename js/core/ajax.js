var dimmer_loader = null;
var cacheAjaxData = {};

$(document).ajaxSuccess(function(event, XMLHttpRequest, ajaxOptions, data){
	var full_requestUrl = ajaxOptions.url;
	if(ajaxOptions.cacheUrl){
		cacheAjaxData[full_requestUrl] = data;
	}
});

$.ajaxSetup({
	autoLoading:true,
	cacheUrl:true,
	error:function(XMLHttpRequest, textStatus, errorThrown){
		if(XMLHttpRequest.readyState > 1){
			this["success"]("["+textStatus+"] We're sorry, a server has encountered a server error. If reloading the page doesn't help, please contact us.");
		}
	},
	beforeSend:function(xhr) {
		if(this.cacheUrl && this.type.toLowerCase() == "get"){
			if(cacheAjaxData[this.url]){
				xhr.abort();
				this["success"](cacheAjaxData[this.url]);
			}
		}
	}
});