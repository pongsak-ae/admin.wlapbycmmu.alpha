

$(document).ready(function(){
	$("#ajax-loading")
	.bind("ajaxSend", function(evt, request, settings){
		if(settings.autoLoading)
		dimmer_loader = dimmerElement("dimmer_loader",0.5,{cursor:"wait"});
	})
	.bind("ajaxComplete", function(evt, request, settings){

		if(settings.autoLoading)
		$(dimmer_loader).remove();
		$(this).hide();
	});

});

function dimmerElement(dimmer_id, ratio ,css){
	var opts = $.extend({
		background:"black",
		position:"absolute",
		zIndex:"1000",
		width:"100%",
		top:"0",
		bottom:"0"
	},css);

	var dimmer = document.getElementById(dimmer_id);
	if(dimmer == undefined){
		dimmer = document.createElement("div");
		dimmer.id = dimmer_id||"dimmer";
		dimmer.className = "dialog_dimmer";
		$(dimmer).css(opts);
		$(dimmer).css({opacity:0});
		document.body.appendChild(dimmer);
		$(dimmer).fadeTo(200, ratio||0.75);
	}
	$(dimmer).css("zIndex",1000 + ($(dimmer).size()*10));
	if($(document).width() > window.screen.width){
		dimmer.style.width = parseInt($(document).width())+"px";
	}
	return dimmer;
}
var dimmer_loader;
var loading_img;
function dimRemove(){
	$(loading_img).remove();
	$('#page').fadeIn(300);
	$(dimmer_loader).css('cursor','pointer').fadeOut(150,function(){$(dimmer_loader).remove()});
}
var isInitialDialogStyle = false;
function initialDialogStyle() {
	if(!isInitialDialogStyle){
		isInitialDialogStyle = true;
		var css = "#loading_dialog{position: absolute; top: 150px; text-align: center; z-index: 2000; width: 100%; } #loading_msg {color: white; position: relative; font-size: 13px; display: block; line-height: 1.5em; }";

		var htmlstyle = document.createElement('style');
		htmlstyle.innerHTML = css;
		document.getElementsByTagName('head')[0].appendChild(htmlstyle);
	}
}
function dimScreen(){
	initialDialogStyle();
	var  msg = arguments[0] || '';
	loading_img = $('<div  id="loading_dialog"><span id="loading_msg">'+msg+'</span><img src="images/ajax-loader.gif" /></div>');

	$('body').append(loading_img);
	dimmer_loader = dimmerElement("dimmer_loader",0.8,{cursor:"wait"});
}