
    tinyMCE.init({
                
        // General options
        mode : "specific_textareas",
        editor_selector : "fulltext",
        theme : "advanced",
        setup : function(ed) {                  
                    if ($("#" + ed.editorId).attr("readonly")) {
                        ed.settings["readonly"] = true;
                    }
					if (typeof(onTinyMCETemplateSetup) == "function" && onTinyMCETemplateSetup(ed) == true) {
					} else {
						ed.settings["theme_advanced_buttons2"] = ed.settings["theme_advanced_buttons2"].replace(/,template/g,"")
					}					
					ed.onInit.add(function(ed) {
						if (typeof(GLOBAL_FORM_TINY_ONINIT) == "function") {
							GLOBAL_FORM_TINY_ONINIT(ed.editorId);
						}						
					});
                    if (typeof(GLOBAL_FORM_TINY_ONSETUP) == "function") {
                        GLOBAL_FORM_TINY_ONSETUP(ed);
                    }
                },
        //plugins : "media,ommedia,-ribbon,fullscreen,advhr",
        //plugins : "ommedia,safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
        plugins : "ommedia,safari,style,layer,table,advhr,advimage,advlink,inlinepopups,contextmenu,paste,media,fullscreen,xhtmlxtras,template",
        // Theme options
        //theme_advanced_buttons1 : "myribbon1,myribbon2,myribbon3,table,",
        theme_advanced_buttons1 : "fullscreen,code,visualaid,|,undo,redo,|,paste,pastetext,pasteword,|,link,unlink,anchor,media,ommedia,|,tablecontrols,|,insertlayer,moveforward,movebackward,absolute",
        theme_advanced_buttons2 : "styleprops,attribs,fontselect,fontsizeselect,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,outdent,indent,|,forecolor,backcolor,|,charmap,advhr,|,cleanup,removeformat,template",
        theme_advanced_buttons3 : "",
        theme_advanced_buttons4 : "",
        //theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect,forecolor,backcolor",
        //theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        //theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        //theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : false,
		dialog_type : "modal"

        
    });
    