
$(document).ready(function(){
	var all_tiny_target = $("textarea.fulltext");
	for (var i=0;i<all_tiny_target.length;i++) {
		var obj_config = {
			fontsize_formats: "8pt 9pt 10pt 11pt 12pt 26pt 36pt",
			theme: "modern",
			content_css: base_url+"css/site_tiny.css",
			plugins: [
				"advlist autolink link ommedia lists charmap print preview hr anchor pagebreak ",
				"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				"save table contextmenu directionality emoticons template paste textcolor importcss colorpicker"
			],
			contextmenu: " cut copy paste link  | ommedia media | tableprops cell row column deletetable",
			setup : function(ed) {
		                if ($("#" + ed.id).attr("readonly")) {
		                    ed.settings["readonly"] = true;
		                    ed.settings["toolbar"] = false;
		                    ed.settings["toolbar1"] = false;
		                    ed.settings["toolbar2"] = false;
		                    ed.settings["menubar"] = false;
		                    ed.settings["statusbar"] = false;
		                }
		            },
		    toolbar_items_size: 'small',
			add_unload_trigger: false,
			menubar: false,
			convert_urls: false,

			toolbar1: "undo redo  | bold italic underline forecolor backcolor fontselect  fontsizeselect | alignleft aligncenter alignright  bullist numlist outdent indent | ommedia media  | table  code",

			image_advtab: true,

			style_formats: [
				{title: 'Bold text', format: 'h1'},
				{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
				{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
				{title: 'Example 1', inline: 'span', classes: 'example1'},
				{title: 'Example 2', inline: 'span', classes: 'example2'},
				{title: 'Table styles'},
				{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
			],
			body_class: "default_css",
			document_base_url : base_url
		};

		var tiny_target = all_tiny_target[i];
		obj_config.selector = "#"+$(tiny_target).attr("id");

		var config = eval('(' + $(tiny_target).parent().find(".fulltext_config").html() + ')');
		if (config.css != null && config.css != undefined) {
			obj_config.content_css = "";
			var listCSS = config.css.split(",");
			for (var j=0;j<listCSS.length;j++) {
				if (j != 0) {
					obj_config.content_css += ",";
				}
				obj_config.content_css += base_url+listCSS[j];
			}
		}
		tinymce.init(obj_config);
	}
});