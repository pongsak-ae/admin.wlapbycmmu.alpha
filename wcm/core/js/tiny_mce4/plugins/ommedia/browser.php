<?php
	include "../../../../../setting.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Media Library</title>
    <link rel="stylesheet" type="text/css" href="jquery.treeview.css" />
    <link rel="stylesheet" type="text/css" href="browser.css" />
	<script type="text/javascript" src="../../../tiny_mce4/plugins/compat3x/tiny_mce_popup.js"></script>
    <script type="text/javascript" src="../../../../js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="jquery.treeview_custom.js"></script>
</head>
<body style="margin:0px; background-color: #ffffff;">
<div id="elChooseMediaManager" class="OMBPanel OMBPanelMediaManager">
    <table id="mainContent" width="645" height="448" cellspacing="0" cellpadding="0" border="0" style="width:650px;" >
        <tr>
    	<td height="40" colspan="3" bgcolor="#dddddd">
    		<div style="padding: 5px 10px 5px 10px;">
		    	<!-- <div style="float:left;"><a href="javascript:OMB.createNewFolder();" class="button medium2"><span>New Folder</span></a></div>
		    	<div style="float:left;"><a href="javascript:OMB.renameFolder();" class="button medium2"><span>Rename Folder</span></a></div>
		    	<div style="float:left;"><a href="javascript:OMB.deleteFolder();" class="button medium2"><span>Delete Folder</span></a></div> -->
				<div style="float:left; font-size:11px; font-weight:bold; padding-right:5px; line-height:24px; margin: 0 0 0 6px;">Upload</div>
		    	<div style="float:left; overflow: hidden; background:#dddddd; width:280px; height:24px; vertical-align:middle; padding:0px;">
			    	<div id="divUploader" style="display:none;height:24px;">
				        <iframe frameborder="0" scrolling="no" id="iframeUploadTarget" name="iframeUploadTarget" src="../../../../core_media_service.php?cmd=form" style="background: #dddddd; width:100%; height:24px; border:0;"></iframe>
				    </div>
				</div>
				<div class="clearfix"></div>
			</div>
	    </td>
    </tr>
     <tr>
        <!-- <td width="159" valign="top" bgcolor="#ffffff">
            <div id="folderList" style="display: block;overflow: auto; width: 159px; height: 410px;"></div>
        </td> -->
        <td width="1" bgcolor="#d9d9d9"></td>
        <td valign="top" bgcolor="#ffffff">
        	<div id="file_list_data">
	            <div style="display: block;overflow: auto; width: 637px; height: 400px; padding:5px;">
		            <div id="itemContent" style="height: 390px;">
			            <div id="template_emptyfolder">
				            <table cellspacing="0" cellpadding="0" border="0" style="width: 637px; height: 390px;">
				            <tr><td style="font-size:12px; color:#cccccc; font-weight: bold;" align="center">There is no file in this folder.</td></tr>
				            </table>
			            </div>
			            <div class="clearfix"></div>
		            </div>
		            <div class="multiply_command">
		            	<span class="selected_text"></span>
		            	<span class="command"><a href="javascript:OMB.cmdSelectAll()" >Select All</a></span>
		            	<span class="command"><a href="javascript:OMB.cmdDeselectAll()" >Deselect All</a></span>
		            	<span class="command"><a href="javascript:OMB.cmdDelete()" >Delete</a></span>
		            	<!--
		            	<span class="command"><a href="javascript:OMB.cmdMovetoFolder()" >Move to folder...</a></span>
		            	//-->
		            </div>
	            </div>
	    	</div>
        </td>
    </tr>
    </table>
</div>

<div id="elMediaProperty" class="OMBPanel OMBPanelProperty" >
	<table cellpadding="10" cellspacing="0" border="0" style="width:645px; height:448px; background-color:#ffffff;">
		<tr height="270">
			<td width="240" valign="top">
			    <div id="elPreviewArea" >
			        <div id="elPreviewMediaBox"></div>
			    </div>
			    <span id="elPreviewMediaInfo1"></span>
			</td>
			<td width="405" valign="top">
				<table class="fileInfo" cellpadding="4" cellspacing="0" border="0" width="100%">
					<tr>
						<td class="label">Image URL:</td>
						<td class="value"><input type="text" value="" id="frmPropertyInputUrl" style="width:270px;" onchange="OMB.onChangePropertyUrl(this);"></td>
					</tr>
					<tr>
						<td class="label">Alternative text:</td>
						<td class="value"><input type="text" value="" id="frmPropertyInputAlt" style="width:270px;"><br/>Alt text for the image, e.g. “The Mona Lisa”</td>
					</tr>
					<tr>
						<td class="label">Display Size:</td>
						<td class="value"><input type="text" value="" id="frmPropertyInputWidth" style="width:40px;" onchange="OMB.onChangePropertyWidth();" > x <input type="text" value="" id="frmPropertyInputHeight" style="width:40px;" onchange="OMB.onChangePropertyHeight();"> pixels</td>
					</tr>
					<tr>
						<td class="label">Keep Aspect Ratio:</td>
						<td class="value"><input type="checkbox" id="frmPropertyInputKeepAspectRatio" name="frmPropertyInputKeepAspectRatio" value="Y" checked><label for="frmPropertyInputKeepAspectRatio">Yes</label></td>
					</tr>
					<tr>
						<td class="label">Vertical Space:</td>
						<td class="value"><input type="text" value="" id="frmPropertyInputVerticalSpace" style="width:40px;"> pixels</td>
					</tr>
					<tr>
						<td class="label">Horizontal Space:</td>
						<td class="value"><input type="text" value="" id="frmPropertyInputHorizontalSpace" style="width:40px;"> pixels</td>
					</tr>
					<tr>
						<td class="label">Border Size:</td>
						<td class="value"><input type="text" value="0" id="frmPropertyInputBorder" style="width:40px;"> pixels</td>
					</tr>
					<tr>
						<td class="label">Alignment:</td>
						<td class="value">
							<select type="text" value="" id="frmPropertyInputAlignment">
							    <option value="">-- Not set --</option>
							    <option value="baseline">Baseline</option>
							    <option value="top">Top</option>
							    <option value="middle">Middle</option>
							    <option value="bottom">Bottom</option>
							    <option value="text-top">Text top</option>
							    <option value="text-bottom">Text bottom</option>
							    <option value="left">Left</option>
							    <option value="right">Right</option>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr height="100">
			<td colspan="2">
				<div class="OMBHowto">
				In the Property inspector, You can set any of the following properties:<br/>
				<strong>Image URL</strong> Specifies the source file for the image.<br/>
				<strong>Alternative text</strong> Specifies alternative text that appears in place of the image for text-only browsers or for browsers that have been set to download images manually. For visually impaired users who use speech synthesizers with text-only browsers, the text is spoken out loud. In some browsers, this text also appears when the pointer is over the image.<br/>
				<strong>Display Size</strong> Set the width and height of the image placeholder, in pixels.
				</div>
			</td>
		</tr>
		<tr >
			<td class="OMBfooter" colspan="2" align="right">
				<a href="javascript:OMB.showMediaManager();" class="button medium"><span>Change Image</span></a>
				<a href="javascript:OMB.applyMedia();" class="button medium"><span>OK</span></a>
			</td>
		</tr>
	</table>
</div>
<div id="elMediaPropertyUnsupported" class="OMBPanel OMBPanelProperty" >
	<table cellpadding="10" cellspacing="0" border="0" width="100%">
		<tr height="400">
			<td valign="middle" style="text-align:center;">
			    <div id="elUnsupportedThumb"></div>
				<div id="elUnsupportedName"></div>
				<div id="elUnsupportedDesc"></div>
			    <div style="font-size:14px; color:#444444; font-weight: bold; margin-top:5px;">This file type did not supported.</div>
			</td>
		</tr>
		<tr height="50">
			<td class="OMBfooter" align="right">
				<a href="javascript:OMB.showMediaManager();" class="button medium"><span>Choose Other</span></a>
			</td>
		</tr>
	</table>
</div>
<script type="text/javascript">
var BASE_URL = "<?=WCMSetting::$WEB_BASE_URL?>";
var lang = parent.document.getElementById("fwdparam_lang").value;
var content_id = parent.document.getElementById("fwdparam_item_id").value;
var temp_id = parent.document.getElementById("fwdparam_temp_id").value;
var module_name = parent.document.getElementById("fwdparam_module_name").value;

OMB = {
	ServiceUrl : "../../../../core_media_service.php",
	HtmlTreeId : "#folderList",
	HtmlFileListId : "#itemContent",
	ActiveFolderId : "",
    Template : [],
	Tree : [],
    CurrentSelectedMedia : {},
	frmSrc : "",
	loadedMediaManager: false,
	init: function(ed) {
        var ed = "", dom = "", n = "";

        this.Template.EmptyFolder = $("#template_emptyfolder").html();
        $("#iframeUploadTarget").attr("src",this.ServiceUrl + "?cmd=form&module_name="+ module_name +"&content_id="+ content_id + "&temp_id="+ temp_id+"&lang="+ lang);
        $("#divUploader").show();

        if (n.nodeName == 'IMG') {
            var media = {};
            var v,v2;
            media["alt"] = dom.getAttrib(n, 'alt');
            media["title"] = dom.getAttrib(n, 'title');
            media["url"] = tinyMCEPopup.editor.documentBaseURI.toAbsolute(dom.getAttrib(n, 'src'),false);
            media["width"] = dom.getAttrib(n, 'width');
			media["height"] = dom.getAttrib(n, 'height');
            media["style"] = dom.getAttrib(n, "style");
            if (v = dom.getStyle(n, 'float')) {
                media["style_align"] = v;
            } else if (v = dom.getStyle(n, 'vertical-align')) {
                media["style_align"] = v;
            }
            v = dom.getStyle(n, 'margin-top')
            v2 = dom.getStyle(n, 'margin-bottom');
            if (v && v == v2) {
                media["style_vspace"] = parseInt(v.replace(/[^0-9]/g, ''));
            }
            v = dom.getStyle(n, 'margin-left')
            v2 = dom.getStyle(n, 'margin-right');
            if (v && v == v2) {
                media["style_hspace"] = parseInt(v.replace(/[^0-9]/g, ''));
            }
            v = 0;
            tinymce.each(['top', 'right', 'bottom', 'left'], function(sv) {
                sv = dom.getStyle(n, 'border-' + sv + '-width');

                // False or not the same as prev
                if (!sv || (sv != v && v !== 0)) {
                    v = 0;
                    return false;
                }

                if (sv)
                    v = sv;
            });
            if (v) {
                media["style_border"] = parseInt(v.replace(/[^0-9]/g, ''));
            }
            OMB.selectMedia(media, "image");
        } else {
            OMB.showMediaManager();
        }
	},
	createNewFolder: function(step) {
		OMB.Dialog("Create new folder", "Please type the new folder name in input below.<br/><input id=\"input_newfolder\" value=\"untitled\" class=\"focus\" style=\"width:300px;\"/>",
		{
			"Create Folder":function() {
				var folder_name = $("#input_newfolder").val();
				OMB.destroyDialog();
				$.post(OMB.ServiceUrl,{
					cmd:"createfolder",
					"parentid": OMB.ActiveFolderId,
					"foldername": folder_name },
					function(result) {
						if(result.status == "OK") {
							var id = result.folderid;
				        	if($("#folder_" + OMB.ActiveFolderId + " >ul").length <= 0){
								$("#folder_" + OMB.ActiveFolderId).append("<ul class=\"folder\"></ul>");
							}
							var branches = $("<li id=\"folder_" + id + "\" ><span class='folder'><a href=\"javascript:void(0)\" onclick=\"OMB.loadContent(this," + id + ")\">" + folder_name + "</a></span></li>").appendTo("#folder_" + OMB.ActiveFolderId + " >ul");

							if($("#folder_" + OMB.ActiveFolderId).find('>div.hitarea').length < 1) {
								if($("#folder_" + OMB.ActiveFolderId).next("li").length > 0) {
									$("#folder_" + OMB.ActiveFolderId).addClass("collapsable");
									$("#folder_" + OMB.ActiveFolderId).prepend("<div class=\"hitarea closed-hitarea collapsable-hitarea\"></div>");
								} else {
									$("#folder_" + OMB.ActiveFolderId).addClass("collapsable lastCollapsable");
									$("#folder_" + OMB.ActiveFolderId).prepend("<div class=\"hitarea closed-hitarea collapsable-hitarea lastCollapsable-hitarea\"></div>");
								}
							}

							var folderBrowserObj = $("#folderBrowser").treeview({
								add: branches
							});

						} else {
							OMB.Dialog("Error", result.message, {"OK":close});
						}
					},"json");
			},
			"Cancel":close
		});
    },

    renameFolder: function() {
    	var id = OMB.ActiveFolderId;
    	if(id == 0) {
    		OMB.Dialog("Error","Unable to rename the Media Library.",{"OK":close});
    	} else {
    		var oldfolder_name = $("#folder_" + id + " span.folder a").html();
    		OMB.Dialog("Rename folder", "Please type the new folder name in input below.<br/><input id=\"input_newfolder\" value=\"" + oldfolder_name + "\" class=\"focus\" style=\"width:300px;\"/>",
			{
				"Rename":function() {
	    			var new_name = $("#input_newfolder").val();
					OMB.destroyDialog();
					$.post(OMB.ServiceUrl,{
	    				cmd:"renamefolder",
						"id": id,
						"foldername": new_name },
						function(result) {
							if(result.status == "OK") {
								$("#folder_" + id + " >span.folder a").html(new_name);
							} else {
								OMB.Dialog("Error", result.message, {"OK":close});
							}
						},"json");
				},
				"Cancel":close
			});
	    }
    },

    deleteFolder: function() {
    	if(OMB.ActiveFolderId == 0) {
    		OMB.Dialog("Error","Unable to delete the Media Library from the system.",{"OK":close});
    	} else {
    		var id = OMB.ActiveFolderId;
	    	OMB.Dialog("Confirmation", "Are you sure to delete the selected folder?",
	    	{
	    		"Delete":function() {
	    			OMB.destroyDialog();
		    		$.post(OMB.ServiceUrl,{
		    			cmd:"deletefolder",
						"id": id },
						function(result) {
							if(result.status == "OK") {
								if($("#folder_" + id).siblings().length < 1){
									$("#folder_" + id).parent().parent()
									.removeClass("expandable")
									.removeClass("collapsable")
									.removeClass("lastExpandable")
									.removeClass("lastCollapsable")
									.find('.hitarea,ul').remove();

								} else {
									if($("#folder_" + id).hasClass("last") || $("#folder_" + id).hasClass("lastCollapsable") || $("#folder_" + id).hasClass("lastExpandable")) {
										if($("#folder_" + id).prev().hasClass("expandable")) {
											$("#folder_" + id).prev().addClass("lastExpandable");
											$("#folder_" + id).prev().find('div.hitarea').addClass("lastExpandable-hitarea");
										} else if($("#folder_" + id).prev().hasClass("collapsable")) {
											$("#folder_" + id).prev().addClass("lastCollapsable");
											$("#folder_" + id).prev().find('div.hitarea').addClass("lastCollapsable-hitarea");
										} else {
											$("#folder_" + id).prev().addClass("last");
										}
									}
									$("#folder_" + id).remove();
								}
								OMB.loadContent("",0);
							} else {
								OMB.Dialog("Error", result.message, {"OK":close});
							}
						},"json");
				},
				"Cancel":close
			});
	    }
    },

	generateTree: function(folder, parent) {
	    var html = "";
		var subhtml = "";
		var sub = null;
		var name = "";
		var id = 0;

		for(f in folder) {
			subhtml = "";
			sub = folder[f].sub;
			name = folder[f].name;
			id = folder[f].id;
			if (sub != null) {
				subhtml = "<ul class=\"folder\">" + this.generateTree(sub, parent + name + "/") + "</ul>";
			}
			html += "<li id=\"folder_" + id + "\" class=\"closed\"><span class=\"folder\" ><a href=\"javascript:void(0)\" onclick=\"OMB.loadContent(this," + id + ")\">" + name + "</a></span>" + subhtml + "</li>";
		}
		return html;
	},
	loadTree : function() {
		this.loadedMediaManager = true;
        $(OMB.HtmlTreeId).html("&nbsp;");
        $(OMB.HtmlTreeId).addClass("OMBLoading");
		$.post(this.ServiceUrl + "?cmd=gettree",null,function(result) {
			if(result.status == "OK"){
				html = "<ul id=\"folderBrowser\" class=\"filetree\"><li id=\"folder_0\" class=\"root active\"><span class=\"folder\"><a href=\"javascript:void(0)\" onclick=\"OMB.loadContent(this,0)\" >Media Library</a></span><ul>" + OMB.generateTree(result.tree,"") + "</ul></li></ul>";
				this.Tree = result.tree;
				$(OMB.HtmlTreeId).html(html);
	            $(OMB.HtmlTreeId).removeClass("OMBLoading");
				$(OMB.HtmlTreeId).find('#folderBrowser').treeview();
				OMB.loadContent("",0);
			} else {
				OMB.Dialog("Error", result.message, {"OK":close});
			}
		},"json");
	},

	loadContent : function(obj, id) {
		$("#file_list_data .multiply_command").css("display","none");
		if(obj != "") {
			$(OMB.HtmlTreeId).find('li').removeClass('active');
			$(obj).parent().parent().addClass('active');
		} else {
			if(id == 0){ $("#folder_0").addClass('active'); }
		}

		this.ActiveFolderId = id;
        $(OMB.HtmlFileListId).html("&nbsp;");
        $(OMB.HtmlFileListId).addClass("OMBLoading");
		$.ajax({
			url: this.ServiceUrl + "?cmd=getfiles&id=" + id + "&module_name=" + module_name + "&content_id=" + content_id + "&temp_id="+ temp_id + "&lang="+ lang,
			cache: false,
			dataType: "json",
			success: function(result) {
				if(result.status == "OK") {

					var html = "";
					html_template1 = "<div class=\"OMBItemBox\" onmouseover='OMB.mouseOverCheckBox(this)' onmouseout='OMB.mouseOutCheckBox(this)' onclick='OMB.selectMedia({id:\"__TEMPLATE_MEDIA_ID__\",url:\"__TEMPLATE_MEDIA_URL__\",width:__TEMPLATE_MEDIA_WIDTH__,height:__TEMPLATE_MEDIA_HEIGHT__,original_filename:\"__TEMPLATE_MEDIA_NAME__\"},\"image\");'>" +
						"<img class=\"OMBItemIcon\" src=\"__TEMPLATE_THUMBNAIL_URL__\" style=\"margin: __TEMPLATE_MEDIA_PAD_TOP__px __TEMPLATE_MEDIA_PAD_LEFTRIGHT__px 0;\" alt=\"\" />" +
						"<div class=\"OMBCheckbox\" style=\"margin: __TEMPLATE_MEDIA_PAD_TOP__px __TEMPLATE_MEDIA_PAD_LEFTRIGHT__px 0;\">" +
							"<input type=\"checkbox\" ref=\"__TEMPLATE_MEDIA_ID__|__TEMPLATE_MEDIA_MODULE__\" onclick=\"OMB.clickCheckBox(event)\" />" +
						"</div>" +
						"<div class=\"OMBItemInfo\">" +
							"<div class=\"OMBItemInfo1\">__TEMPLATE_MEDIA_NAME__</div>" +
							// "<div class=\"OMBItemInfo2\">__TEMPLATE_MEDIA_WIDTH__ x __TEMPLATE_MEDIA_HEIGHT__</div>" +
						"</div>" +
					"</div>";

					html_template2 = "<div class=\"OMBItemBox\" onmouseover='OMB.mouseOverCheckBox(this)' onmouseout='OMB.mouseOutCheckBox(this)' onclick='OMB.selectMedia({id:\"__TEMPLATE_MEDIA_ID__\",url:\"__TEMPLATE_MEDIA_URL__\",width:__TEMPLATE_MEDIA_WIDTH__,height:__TEMPLATE_MEDIA_HEIGHT__,original_filename:\"__TEMPLATE_MEDIA_NAME__\",thumb_url:\"__TEMPLATE_THUMBNAIL_URL__\"},\"\");'>" +
						"<img src=\"__TEMPLATE_THUMBNAIL_URL__\" style=\"margin: 8px 3px 0;\" alt=\"\" />" +
						"<div class=\"OMBCheckbox\" style=\"margin: 10px 10px 0;\">" +
							"<input type=\"checkbox\" ref=\"__TEMPLATE_MEDIA_ID__|__TEMPLATE_MEDIA_MODULE__\" onclick=\"OMB.clickCheckBox(event)\" />" +
						"</div>" +
						"<div class=\"OMBItemInfo\">" +
							"<div class=\"OMBItemInfo1\">__TEMPLATE_MEDIA_NAME__</div>" +
							"<div class=\"OMBItemInfo2\">__TEMPLATE_MEDIA_FILESIZE__ (__TEMPLATE_MEDIA_EXT__)</div>" +
						"</div>" +
					"</div>";

					var files = result.files;
					var num_item_per_row = 5;
					var maxheight = [];
					var i = 0;
					for (f in files) {
						var row = Math.floor(i / num_item_per_row);
						var ext = files[f].ext.toLowerCase();
						if((ext != ".jpg") && (ext != ".jpeg") && (ext != ".png") && (ext != ".gif")) {
							files[f].width = 78;
							files[f].height = 78;
						}
						var ratio = files[f].width / files[f].height;
						var now_height = 0;

						if(ratio < 1) {
							now_height = 78;
						} else {
							now_height = Math.round(78/ratio);
						}

						if(maxheight[row] == null || maxheight[row] == undefined){
							maxheight[row] = now_height;
						} else {
							if(now_height > maxheight[row]) { maxheight[row] = now_height; }
						}
						i++;
					}
					i = 0;
					for (f in files) {
						var row = Math.floor(i / num_item_per_row);
						var ratio = files[f].width / files[f].height;
						var padlr = 0;
						var padt = 0;
						var now_height = 0;
						if(ratio < 1) {
							now_height = 78;
							padlr = Math.floor((78 - (78*ratio))/2);
						} else {
							now_height = Math.round(78/ratio);
							padlr = 0;
						}
						if(now_height < maxheight[row]) {
							padt = (maxheight[row] - now_height);
						}

						// var thumbnail_url = "../../../../../../stocks/" + files[f].folder + "thumb/" + files[f].filename;
						var thumbnail_url = BASE_URL + files[f].filename;
						var ext = files[f].ext.toLowerCase();
						var th_width = 78;
						var th_height = 78;
						var th_filesize = files[f].size;

						if(th_filesize >= 1048576) { th_filesize = (Math.round((th_filesize*100)/1048576)/100) + " MB"; }
						else if(th_filesize >= 1024) { th_filesize = (Math.round((th_filesize*100)/1024)/100) + " KB"; }
						else { th_filesize = th_filesize + " Bytes"; }

						html_template = html_template2;
						switch(ext) {
							case ".jpg"	:
							case ".jpeg":
							case ".png"	:
							case ".gif"	: th_width = files[f].width;
										  th_height = files[f].height;
										  html_template = html_template1;
										  break;
							case ".doc"	:
							case ".docx":
							case ".odt"	: thumbnail_url = "img/mime/word.png"; break;
							case ".ppt"	:
							case ".pptx":
							case ".odp"	: thumbnail_url = "img/mime/presentation.png"; break;
							case ".xls"	:
							case ".xlsx":
							case ".ods"	: thumbnail_url = "img/mime/sheet.png"; break;
							case ".rtf"	: thumbnail_url = "img/mime/rtf.png"; break;
							case ".txt"	: thumbnail_url = "img/mime/txt.png"; break;
							case ".swf"	: thumbnail_url = "img/mime/swf.png"; break;
							case ".mp3"	:
							case ".wav"	:
							case ".midi":
							case ".aac"	:
							case ".flac":
							case ".wma"	:
							case ".mid"	: thumbnail_url = "img/mime/audio.png"; break;
							case ".mp4"	:
							case ".wmv"	:
							case ".avi"	:
							case ".flv"	:
							case ".mpg"	:
							case ".mpeg":
							case ".rm"	:
							case ".mkv"	:
							case ".3gp"	: thumbnail_url = "img/mime/video.png"; break;
							case ".xml"	: thumbnail_url = "img/mime/xml.png"; break;
							case ".mdb"	:
							case ".sql"	: thumbnail_url = "img/mime/db.png"; break;
							case ".ai"	: thumbnail_url = "img/mime/drawing.png"; break;
							case ".csv"	: thumbnail_url = "img/mime/csv.png"; break;
							case ".iso"	: thumbnail_url = "img/mime/dvd.png"; break;
							case ".xhtml":
							case ".html": thumbnail_url = "img/mime/html.png"; break;
							case ".ttf"	:
							case ".fon"	: thumbnail_url = "img/mime/font.png"; break;
							case ".zip"	:
							case ".rar"	:
							case ".7z"	: thumbnail_url = "img/mime/archived.png"; break;
							case ".pdf"	: thumbnail_url = "img/mime/pdf.png"; break;
							default		: thumbnail_url = "img/mime/unknown.png"; break;
						}


						p = {
							"__TEMPLATE_MEDIA_ID__" : files[f].id,
							"__TEMPLATE_MEDIA_MODULE__" : files[f].module_name,
							"__TEMPLATE_THUMBNAIL_URL__" : thumbnail_url,
							"__TEMPLATE_MEDIA_URL__" :   files[f].media_original_filename,
							"__TEMPLATE_MEDIA_NAME__" : files[f].name,
							"__TEMPLATE_MEDIA_WIDTH__" : 200,
							"__TEMPLATE_MEDIA_HEIGHT__" : 200,
							"__TEMPLATE_MEDIA_EXT__" : files[f].ext.substring(1).toUpperCase(),
							"__TEMPLATE_MEDIA_FILESIZE__" : th_filesize,
							"__TEMPLATE_MEDIA_PAD_LEFTRIGHT__" : padlr,
							"__TEMPLATE_MEDIA_PAD_TOP__" : padt
						};
							html += OMB.replateTemplate(html_template,p);
							i++;
					}
					if (html =="") {
						html = OMB.Template.EmptyFolder;
					}

					$(OMB.HtmlFileListId).html(html);
					$(OMB.HtmlFileListId).removeClass("OMBLoading");
				} else {
					OMB.Dialog("Error",result.message, {"OK":close });
				}
			}
		});
	},
    replateTemplate : function(str, params) {
        newstr = str;
        for(p in params) {
            newstr = newstr.replace( new RegExp(p, "g" ), params[p] );
        }
        return newstr;
    },
    showMediaProperty : function() {
        $("#elChooseMediaManager").hide();
        $("#elMediaPropertyUnsupported").hide();
        $("#elMediaProperty").show();
    },
    showMediaPropertyUnsupported : function() {
        $("#elChooseMediaManager").hide();
        $("#elMediaProperty").hide();
        $("#elMediaPropertyUnsupported").show();
    },
    showMediaManager : function() {
        $("#elMediaProperty").hide();
        $("#elMediaPropertyUnsupported").hide();
        $("#elChooseMediaManager").show();
        if(!this.loadedMediaManager) { OMB.loadContent("",0); }
    },
    getAbsolutePathName: function(url) {
    	return url;
		var l = document.location;
		var i = document.createElement('img');
		var a = document.createElement('a');
		i.src = url;
		a.href  = i.src;
		i.src = null;
        if (a.hostname == l.hostname && a.protocol == l.protocol && a.port == l.port) {
            return a.pathname;
        } else {
            return a.href;
        }
    },
    reloadImageInfo : function (url) {
        var media = OMB.CurrentSelectedMedia;
        media.url = url;
        $("#elPreviewMediaBox").html("");
        $("#elPreviewMediaInfo1").html("");
        var img = new Image();
        img.onload = function() {
            var media = OMB.CurrentSelectedMedia;
            var t = this;

            if (t) {
                if (t.width > 0) {
                    media.width = t.width;
                }
                if (t.height > 0) {
                    media.height = t.height;
                }
            }
            var pw,ph;
            var ratio = media.width / media.height;
            var maxsize = 220;
            if (ratio > 1) {
                if (media.width > maxsize) {
                    pw = maxsize;
                    ph = Math.round(pw / ratio);
                } else {
                    pw = media.width;
                    ph = media.height;
                }
            } else {
                if (media.height > maxsize) {
                    ph = maxsize;
                    pw = Math.round(ph * ratio);
                } else {
                    pw = media.width;
                    ph = media.height;
                }
            }
            var padl = Math.floor((maxsize - pw)/2) + 10;
            var padt = Math.floor((maxsize - ph)/2) + 10;
            $("#elPreviewMediaBox").html("<img id='elPreviewMediaBoxImage' src='" + media.url + "' width='" + pw + "' height='" + ph + "' style='padding:" + padt + "px " + padl + "px;'>");
            $("#elPreviewMediaInfo1").html("Original size: " + media.width + " x " + media.height + " pixels");
            $("#frmPropertyInputWidth").val(media.width);
            $("#frmPropertyInputHeight").val(media.height);
            OMB.onChangePropertyWidth();

        };
        img.src = media.url;
    },
    selectMedia : function (media, mediatype) {
        OMB.CurrentSelectedMedia = media;
        media["element_width"] = media.width;
        media["element_height"] = media.height;
        if (media["alt"] == null || media["alt"] == undefined || media["alt"] == "") {
            media["alt"] = media.original_filename;
        }
        if(mediatype == "image") {
			this.showMediaProperty();
	        $("#frmPropertyInputUrl").attr("value", "/" + media.url);
	        $("#frmPropertyInputUrl").attr("alt", media.url);
	        $("#frmPropertyInputAlt").attr("value",media.alt);
	        $("#frmPropertyInputWidth").attr("value",media.element_width);
	        $("#frmPropertyInputHeight").attr("value",media.element_height);
	        $("#frmPropertyInputAlignment").val(media.style_align);
	        $("#frmPropertyInputVerticalSpace").attr("value",media.style_vspace);
	        $("#frmPropertyInputHorizontalSpace").attr("value",media.style_hspace);
	        $("#frmPropertyInputBorder").attr("value",media.style_border);
	        //$("#elPreviewMediaInfo2").html(media.original_filename);
	        OMB.reloadImageInfo(BASE_URL + media.url);
		} else {
			this.showMediaPropertyUnsupported();
			$("#elUnsupportedThumb").html("<img src='" + media.thumb_url + "' alt=\"\" />");
			$("#elUnsupportedName").html("<strong>Filename:</strong> " + media.original_filename);
			$("#elUnsupportedDesc").html("<strong>Location:</strong> " + BASE_URL + media.url);
		}

    },
    onChangePropertyUrl : function (t) {
        OMB.reloadImageInfo(t.value);
    },
    onChangePropertyWidth : function() {
        var ratio = OMB.CurrentSelectedMedia.width / OMB.CurrentSelectedMedia.height;
        if ($("#frmPropertyInputKeepAspectRatio").attr("checked")) {
            $("#frmPropertyInputHeight").attr("value",Math.round($("#frmPropertyInputWidth").attr("value") / ratio));
        }
    },
    onChangePropertyHeight : function() {
         var ratio = OMB.CurrentSelectedMedia.width / OMB.CurrentSelectedMedia.height;
        if ($("#frmPropertyInputKeepAspectRatio").attr("checked")) {
            $("#frmPropertyInputWidth").attr("value",Math.round($("#frmPropertyInputHeight").attr("value") * ratio));
        }
    },
    convertS2I2S : function (s) {
        var x = parseInt(s);
        if (isNaN(x)) return "";
        return x.toString();
    },
    applyMedia : function () {
        var args = {};
        var ed = tinyMCEPopup.editor;
        tinyMCEPopup.restoreSelection();

        var style_args =  {
            align : $("#frmPropertyInputAlignment :selected").attr("value"),
            vspace : OMB.convertS2I2S($("#frmPropertyInputVerticalSpace").attr("value")),
            hspace : OMB.convertS2I2S($("#frmPropertyInputHorizontalSpace").attr("value")),
            border : OMB.convertS2I2S($("#frmPropertyInputBorder").attr("value"))
        };

        var style = "";

        switch (style_args.align) {
            case "baseline":
                style+="vertical-align:baseline;";
                break;
            case "top":
                style+="vertical-align:top;";
                break;
            case "middle":
                style+="vertical-align:middle;";
                break;
            case "buttom":
                style+="vertical-align:buttom;";
                break;
            case "text-top":
                style+="vertical-align:text-top;";
                break;
            case "text-buttom":
                style+="vertical-align:text-buttom;";
                break;
            case "left":
                style+="float:left;";
                break;
            case "right":
                style+="float:right;";
                break;
            case "":
            default:
                break;
        }
        if (style_args.vspace != "" && style_args.hspace == "") {
            style+="margin-top:" + style_args.vspace + "px;margin-bottom:" + style_args.vspace + "px;";
        }
        if (style_args.vspace == "" && style_args.hspace != "") {
             style+="margin-left:" + style_args.hspace + "px;margin-right:" + style_args.hspace + "px;";
        }
        if (style_args.vspace != "" && style_args.hspace != "") {
            style+="margin:" + style_args.vspace + "px " + style_args.hspace + "px;";
        }
        if (style_args.border !="") {
            style+="border:" + style_args.border + "px solid #000000;";
        }
		tinymce.extend(args, {
			src : $("#frmPropertyInputUrl").attr("alt"),
			alt : $("#frmPropertyInputAlt").attr("value"),
			width : $("#frmPropertyInputWidth").attr("value"),
			height : $("#frmPropertyInputHeight").attr("value"),
            style : style
		});


        el = ed.selection.getNode();
        if (el && el.nodeName == 'IMG') {
			ed.dom.setAttribs(el, args);
		} else {
			ed.execCommand('mceInsertContent', false, '<img id="__mce_tmp" />', {skip_undo : 1});
			ed.dom.setAttribs('__mce_tmp', args);
			ed.dom.setAttrib('__mce_tmp', 'id', '');
			ed.undoManager.add();
		}
        tinyMCEPopup.close();
			return;
    },

    onUploadCompleted : function() {
		$("#divUploader").removeClass("OMBLoading");
		$("#iframeUploadTarget").show();
		$("#iframeUploadTarget").attr("src",this.ServiceUrl + "?cmd=form&module_name="+ module_name +"&content_id="+ content_id + "&temp_id="+ temp_id+"&lang="+ lang);
        OMB.loadContent("", OMB.ActiveFolderId);
    },
	onStartUploading : function(filename) {
		$("#divUploader").addClass("OMBLoading");
		$("#iframeUploadTarget").hide();
	},

    onUploadError : function (msg) {
    	$("#divUploader").removeClass("OMBLoading");
		$("#iframeUploadTarget").show();
		$("#iframeUploadTarget").attr("src",this.ServiceUrl + "?cmd=form&module_name="+ module_name +"&content_id="+ content_id + "&temp_id="+ temp_id+"&lang="+ lang);
        OMB.Dialog("Error", msg, {"OK":close });
    },

    mouseOverCheckBox: function(obj) {
    	$(obj).find('.OMBCheckbox').addClass("showCheckbox");
    },

    mouseOutCheckBox: function(obj) {
    	if($(obj).find('.OMBCheckbox input').attr("checked") != true){
	    	$(obj).find('.OMBCheckbox').removeClass("showCheckbox");
	    }
    },

    cmdSelectAll: function() {
    	var num_all = $("#file_list_data").find('.OMBCheckbox input[type=checkbox]').length;
    	$("#file_list_data").find('.OMBCheckbox').addClass("showCheckbox")
		.find('input[type=checkbox]').attr("checked",true);
    	$("#file_list_data .multiply_command .selected_text").html(num_all + " of " + num_all + " selected");
    },

    cmdDeselectAll: function() {
    	$("#file_list_data").find('.OMBCheckbox').removeClass("showCheckbox")
		.find('input[type=checkbox]').attr("checked",false);
    	$("#file_list_data .multiply_command").css("display","none");
    },

    cmdDelete: function() {
    	OMB.Dialog("Confirmation", "Are you sure to delete the selected items?",
		{
			"Delete":function() {
				OMB.destroyDialog();
	    		var delete_list = "";
	    		$("#file_list_data").find('.OMBCheckbox input[type=checkbox]:checked').each( function() {
	    			if(delete_list != "") { delete_list += ","; }
	    			delete_list += $(this).attr("ref");
	    		});

	    		$.post(OMB.ServiceUrl,{
					cmd:"delete",
					"i": delete_list },
					function(result) {
						if(result.status == "OK") {
							OMB.loadContent("", OMB.ActiveFolderId);
						} else {
							OMB.Dialog("Error", result.message, {"OK":close});
						}
					},"json");
			},
			"Cancel": close
		});
    },

	cmdMovetoFolder: function() {
    },

    clickCheckBox: function(event){
    	if(event.stopPropagation) { event.stopPropagation(); } else { window.event.cancelBubble = true; }
		var num_checked = $("#file_list_data").find('.OMBCheckbox input[type=checkbox]:checked').length;
		var num_all = $("#file_list_data").find('.OMBCheckbox input[type=checkbox]').length;
    	if(num_checked > 0){
    		$("#file_list_data .multiply_command").css("display","block")
    		.find('.selected_text').html(num_checked + " of " + num_all + " selected");
    	} else {
    		$("#file_list_data .multiply_command").css("display","none");
    	}
    },

    Dialog: function(title, body, button, width) {

		$("body").find("#OMBDialog").remove();
		$("body").find("#OMBDimmer").remove();

		if($.browser.msie) {
			$("select").css("visibility","hidden");
		}

		var btn_ret = "";
		ret = "<div id=\"OMBDimmer\"></div><div id=\"OMBDialog\">"
		ret += "<div class=\"head\">"+ title + "</div>";
		ret += "<div class=\"main\">" + body + "</div>";

		if(button != null){
			ret += "<div class=\"foot\">";
			var running_id = 0;
			for (var i in button) {
				btn_ret = OMB.printButton(i,"middle","btn-"+running_id) + btn_ret;
				running_id++;
			}
			ret += btn_ret;
			ret += "</div>";
		}
		ret += "</div>";
		var height_dimmer = $(document).height();

		$("body").append(ret).find("#OMBDimmer").css("height", height_dimmer);
		if(width > 0){ $("#OMBDialog").css("width", width);}
		OMB.setCenterScreen($("#OMBDialog"));
		$("#OMBDialog .focus").focus();

		var running_id = 0;
		for(var i in button){
			if(button[i] != close) {
				$("#OMBDialog #btn-"+running_id).bind('click', button[i] );
			} else {
				$("#OMBDialog #btn-"+running_id).bind('click', function() { OMB.destroyDialog(); });
			}
			running_id++;
		}
	},

	destroyDialog: function() {
		$("#OMBDialog").remove();
		$("#OMBDimmer").remove();
		if($.browser.msie) {
			$("select").css("visibility","visible");
		}
	},

	setCenterScreen: function(obj) {
		$(obj).css("top", ( $(window).height() - $(obj).height() ) / 2 + $(window).scrollTop() - 20 + "px");
		$(obj).css("left", ( $(window).width() - $(obj).width() ) / 2+$(window).scrollLeft() + "px");
	},

	printButton: function(cmd, size, id) {
		var ret = "";
		if(size != "medium" && size != "medium2") { size = "medium"; }
		ret += "<a id=\"" + id + "\" class=\"button " + size + "\" ><span>" + cmd + "</span></a>";
		return(ret);
	},

	getDimention: function(file,callback){
		var img = new Image();
		img.src = BASE_URL + file;
		img.onload = function() {
			var p = [];
				p["width"] = this.width;
			    p["height"] = this.height;
			callback(p);
		};
	}
};
OMB.init();
$(".mce-title").html("Media Library");

</script>
</body>
</html>
