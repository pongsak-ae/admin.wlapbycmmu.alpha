var _form = {};
_form.saveState = {};

_form.state_show = "";
_form.step = 0;
_form.extra_step = 0;
_form.action = "";
_form.log = {};
_form.log.sf = "log_timestamp";
_form.log.sm = "desc";
_form.log.p = 1;
_form.log.loaded = false;

_form.data = {};

_form.child = {};
_form.child.name = "";
_form.child.mode = "";
_form.child.command = "";
_form.child.item_id = 0;
_form.child.step = 0;

$(document).ready(function(){
  if (getParameterByName("recovery") != "") {
    showNotificationPreview("alert","Warning: ","Current data preview revision " + getParameterByName("recovery"));
  }
});

function GLOBAL_FORM_TINY_ONINIT(id){
  _form.saveState[id] = tinyMCE.get(id).getContent();
}

function GLOBAL_FORM_TINY_ONSETUP(ed) {

}

function checkNumFileUploading(){
  var num_loading = 0;
  $("#edit_form .row .uploadprogress").each(function(){
    if($(this).css("display") != "none") { num_loading++; }
  });
  return num_loading;
}

function hideHint(obj) {
  $(obj).find('.hint .msg').addClass("hidden");
}

function showHint(obj) {
  $(obj).find('.hint .msg').removeClass("hidden");
}

function showTab(tab_index){
  if (tab_index == 100) {
    showButton("recovery");
  } else {
    showButton("normal");
  }
  $("#edit_form .tabcontainer .tab li").removeClass("active");
  $("#edit_form #tablabel" + tab_index).addClass("active");
  $("#edit_form .form_container").addClass("hidden");
  $("#edit_form .form_container#tab" + tab_index).removeClass("hidden");
}

function showButton(mode) {
  if (mode == "normal") {
    $("#command .button").show();
    $("#command #button_recovery").hide();
  } else if (mode == "recovery") {
    $("#command .button").hide();
    $("#command #button_recovery").show();
    $("#command #button_recovery .button").show();
  }
}

function refrechListRevision(id, lang) {
  $.ajax({
    url: 'form_cmd.php',
    data: {'fwdparam_command':'getlistrevision', 'fwdparam_item_id': id, 'fwdparam_lang': lang},
    type: 'POST', cache: false, timeout: 30000,
    dataType: 'json',
    error: function(){
      Dialog("Error","ERROR",{"OK":close});
    },
    success: function(rs){
      if(rs.c == "OK"){
        var strListRevision = JSON.stringify(rs.revision);
        var tmp_data = $("#val_list_revision").val();
        if (tmp_data != strListRevision) {
          $("#val_list_revision").val(strListRevision);
          $("#fi_revision_target option:not(.default_choice)").remove();
          for (var i=0;i<rs.revision.length;i++) {
            var obj = rs.revision[i];
            $("#fi_revision_target").append('<option value="'+obj.obj_rev+'">Revision:' + obj.obj_rev + " (Last Modified " + obj.obj_modified_date + ')</option>');
          }
        }
      } else if(rs.e == "SESSION_TIMEOUT") {
        Redirect(_redirect_timeout);
      } else if(rs.e == "PERMISSION_DENIED") {
        Redirect(rs.url);
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

function recoveryPreview() {
  var m = getParameterByName("m");
  if (m == "") {
    m = "edit";
  }
  var l = getParameterByName("l");
  if (l == "") {
    l = "MAS";
  }
  var id = getParameterByName("id");
  var recovery = $("#fi_revision_target").val();
  if (recovery == "") {
    $("#row_revision_target .label").addClass("invalid");
    Dialog("Error",'"Revision target" field is required.',{"OK":close});
  } else {
    $("#row_revision_target .label").removeClass("invalid");
    window.location.assign("form.php?m=" + getParameterByName("m") + "&id=" + getParameterByName("id") + "&r=" + getParameterByName("r") + "&recovery=" + recovery);
  }
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function startUploading(id, filename) {
  $('#row_' + id).find('iframe').css("display","none");
  $('#row_' + id).find('.uploadcomplete').css("display","none");
  $('#row_' + id).find('.uploadprogress').css("display","inline").find('.name').html("Uploading: " + filename);
}

function completeUploading(id, file_id, filename, filename_gen ,bypass_crop) {
  $('#row_' + id).find('iframe').css("display","none");
  $('#row_' + id).find('.file_id').val(file_id);
  // $('#row_' + id).find('.file_name').val(filename);
  $('#row_' + id).find('.file_name').val($("<div>"+filename+"</div>").html());
  $('#row_' + id).find('.file_gen').val(filename_gen);
  $('#row_' + id).find('.uploadprogress').css("display","none");

  var filename_display = filename;
  if(filename.length > 25) {
    filename_display = filename.substring(0,17) + "..." + filename.substring(filename.length-9,filename.length);
  }
  $('#row_' + id).find('.uploadcomplete').css("display","inline").find('.name').html($("<div>"+filename_display+"</div>").html());

  if ($('#row_' + id).find(".file_image_mode").size() == 1) {
    var image_mode = $('#row_' + id).find(".file_image_mode").val().split(",");
    if (bypass_crop) {

    } else {
      if (image_mode[0] == "dynamic") {
        initCropImage($('#row_' + id), id, file_id, filename, filename_gen, image_mode);
        return;
      }
    }
    showImagePreview($('#row_' + id), id, file_id, filename, filename_gen, image_mode);
  }
}

function showImagePreview(target, id, file_id, filename, filename_gen, image_mode) {
  $('#row_' + id).find('.uploadpreview img').attr("src",base_url+OMImage.readFileName(filename_gen,filename,"d200x200",$("#fwdparam_module_name").val()));
  $('#row_' + id).find('iframe').css("display","none");
  $('#row_' + id).find('.loading').css("display","none");
  $('#row_' + id).find('.uploadcomplete').css("display","none");

  $('#row_' + id).find('.uploadpreview').css("display","block");
}

function initCropImage(target, id, file_id, filename, filename_gen, image_mode) {
  $(".wrap_cropper").remove();

  var image_data = id+"|:|"+file_id+"|:|"+filename+"|:|"+filename_gen+"|:|"+$("#fwdparam_module_name").val();

  var strHTML = '';
  strHTML += '<div class="wrap_cropper" style="position:fixed;width:100%;height:100%;top:0;left:0;">';
  strHTML += '<div class="dimmer" style="width:100%;height:100%;"></div>';
  strHTML += '<div style="z-index:10000;position: absolute; top:50%; left:50%;margin-left: -300px;margin-top:-228px;">';
  strHTML += '  <div class="cropper-container" style="width:600px;height:400px;position:relative;padding: 10px;">';
  strHTML += '    <img class="cropper" src="'+base_url+OMImage.readFileName(filename_gen,filename,"",$("#fwdparam_module_name").val())+'">';
  strHTML += '  </div>';
  strHTML += '  <div style="background-color:#ffffff;padding:10px 10px 10px 10px;">';
  strHTML += '    <div style="float:left;padding-top: 5px;">';
  strHTML += '      <span class="show_data_crop" style="font-size:14px;"></span>';
  strHTML += '      <input type="hidden" class="image_mode" name="image_mode" value="'+image_mode+'" /> ';
  strHTML += '      <input type="hidden" class="image_data" name="image_data" value="'+image_data+'" /> ';
  strHTML += '      <input type="hidden" class="crop_data" name="crop_data" value="" /> ';
  strHTML += '    </div>';
  strHTML += '    <a style="float:right;" class="button medium" href="javascript:cancelCrop();">';
  strHTML += '    <span>Cancel</span>';
  strHTML += '    </a>';
  strHTML += '    <a style="float:right;" class="button medium" href="javascript:cropImage();">';
  strHTML += '    <span>Crop Image</span>';
  strHTML += '    </a>';
  strHTML += '    <div class="clearfix"></div>';
  strHTML += '  </div>';
  strHTML += '</div>';
  strHTML += '</div>';
  $("body").append(strHTML);
  $(".wrap_cropper").hide();

  var ratio_data = image_mode[1].split(":");
  var ratio;
  if (ratio_data.length == 2) {
    ratio = ratio_data[0]/ratio_data[1];
  } else {
    ratio = ratio_data;
  }

  $(".cropper-container img").load(function() {
    var config = {
      aspectRatio: ratio ,
      done: function(data) {
        $(".wrap_cropper .crop_data").val(JSON.stringify(data));
        $(".wrap_cropper .show_data_crop").html("<span style='font-weight: bold;'>X :</span> <span style='color:#999999;'>"+data.x+"</span> <span style='font-weight: bold;'>Y :</span> <span style='color:#999999;'>"+data.y+"</span> <span style='font-weight: bold;'>WIDTH :</span> <span style='color:#999999;'>"+data.width+"</span> <span style='font-weight: bold;'>HEIGHT :</span> <span style='color:#999999;'>"+data.height+"</span>");
      }
    };

    $(".cropper-container img").cropper(config);
    $(".wrap_cropper").fadeIn();
    changeUploaded($($(target).find(".uploadcomplete a")[0])[0],true);
  });
}

function cropImage() {
  var crop_data = $(".wrap_cropper .crop_data").val();
  var image_data = $(".wrap_cropper .image_data").val();
  var image_mode = $(".wrap_cropper .image_mode").val().split(",");
  var max_width = image_mode[2];

  $.ajax({
    url: 'form_fileupload_cmd.php',
    data: {'cmd':'crop_image', 'image_data':image_data, 'crop_data':crop_data, 'max_width':max_width},
    type: 'POST', cache: false, timeout: 30000,
    dataType: 'json',
    error: function(){
      cancelCrop();
      Dialog("Error", "Cannot crop image. Please try to upload again or contact your system administrator to solve the problem.", {"OK":close});
      return false;
    },
    success: function(result){
      cancelCrop();
      if(result.c == "OK"){
        var image_data = $(".wrap_cropper .image_data").val().split("|:|");
        completeUploading(image_data[0], image_data[1], image_data[2], image_data[3], true);
      } else if(result.e == "SESSION_TIMEOUT") {
        Redirect(_redirect_timeout);
      } else if(result.e == "PERMISSION_DENIED") {
        Redirect(result.url);
      } else {
        Dialog("Error", "Cannot crop image. Please try to upload again or contact your system administrator to solve the problem.", {"OK":close});
      }

      return false;
    }
  });
}

function cancelCrop() {
  $(".wrap_cropper").fadeOut(400, function() {
    $(".wrap_cropper").remove();
  });
}

function errorUploading(id, error_type) {
  cancelUploading($('#row_' + id).find('.uploadprogress .cancel').find('a'));
  if (error_type != undefined) {
    switch (error_type) {
      case "UNSUPPORT_FILE_TYPE":
        Dialog("Error","Unable to upload the file. Unsupported file type.",{"OK":close});
        break;
      default:
        break;
    }
  } else {
    Dialog("Error","Unable to upload the file. Please try to upload again or contact your system administrator to solve the problem.",{"OK":close});
  }
}

function cancelUploading(obj) {
  var src_str = $(obj).parent().parent().parent().find('iframe').attr("src");
  $(obj).parent().parent().parent().find('.loading').css("display","inline");
  $(obj).parent().parent().parent().find('iframe').attr("src",src_str);
  $(obj).parent().parent().css("display","none");
}

function changeUploaded(obj, setnull) {
  var src_str = $(obj).parent().parent().parent().find('iframe').attr("src");
  $(obj).parent().parent().parent().find('.loading').css("display","inline");
  $(obj).parent().parent().parent().find('iframe').attr("src",src_str);
  $(obj).parent().parent().parent().find('input.file_id').val("");
  if(setnull) {
    $(obj).parent().parent().parent().find('input.file_name').val("[SET-TO-NULL]");
  } else {
    $(obj).parent().parent().parent().find('input.file_name').val("");
  }
  $(obj).parent().parent().parent().find('input.file_gen').val("");
  $(obj).parent().parent().css("display","none");
  var id = $(obj).parent().parent().parent().parent().attr("id").substring(4);
  _form.state_show = "blank";
}

function iframeLoadCompleted(id,state) {
  if (state == "blank" || _form.state_show == "blank") {
    _form.state_show = "";
    $('#row_' + id).find('iframe').css("display","inline");
    $('#row_' + id).find('.loading').css("display","none");
    $('#row_' + id).find('.uploadpreview').css("display","none");
  } else if (state == "preview") {
    $('#row_' + id).find('iframe').css("display","none");
    $('#row_' + id).find('.loading').css("display","none");
    $('#row_' + id).find('.uploadcomplete').css("display","none");

    $('#row_' + id).find('.uploadpreview').css("display","block");
    $('#row_' + id).find('input.file_id').val("");
  }
}

function validateComposeMessage() {
  if($("#composeToUserId").val() == "") { return false; }
  if($("#composeSubject").val() == "") { return false; }
  if($("#composeMessage").val() == "") { return false; }
  return true;
}

function updateMessageList(new_message) {
  if(new_message > 0) {
    $("body .header_top .shortcut_link a.icon_msg").addClass('alert');
  } else {
    $("body .header_top .shortcut_link a.icon_msg").removeClass('alert');
  }
}

function requestMessage(to_user_id, subject) {
  var err = "Unable to create request message. Please contact your system administrator to solve the problem.";
  drawLoadingFullPage();

  var current_item_id = $('#edit_form').find('#fwdparam_item_id').val();
  var current_revision = $('#edit_form').find('#fwdparam_revision').val();
  var current_lang = $('#edit_form').find('#fwdparam_lang').val();

  $.ajax({
    url: 'form_cmd.php',
    data: {'fwdparam_command':'getComposeForm', 'to':to_user_id, 'sj':subject},
    type: 'POST', cache: false, timeout: 30000,
    dataType: 'json',
    error: function(){
      destroyLoadingFullPage();
      Dialog("Error",err,{"OK":close});
      return false;
    },
    success: function(result){
      destroyLoadingFullPage();
      if(result.c == "OK"){
        childDialog(
          "Send Request",
          result.compose_form,
          {
            "Send":function() {
              if(validateComposeMessage()) {
                var to = $("#composeToUserId").val();
                var sj = $("#composeSubject").val();
                var msg = $("#composeMessage").val();
                destroyChildDialog();
                drawLoadingFullPage();
                $.ajax({
                  url: 'form_cmd.php',
                  data: {'fwdparam_command':'sendMessage', 'to':to, 'sj':sj, 'msg':msg,'fwdparam_item_id': current_item_id,'fwdparam_revision': current_revision,'fwdparam_lang': current_lang },
                  type: 'POST', cache: false, timeout: 30000,
                  dataType: 'json',
                  error: function(){
                    destroyLoadingFullPage();
                    Dialog("Error",err,{"OK":close});
                    return false;
                  },
                  success: function(result){
                    if(result.c == "OK") {
                      destroyLoadingFullPage();
                      updateMessageList(result.new_message);
                    } else if(result.e == "SESSION_TIMEOUT") {
                      Redirect(_redirect_timeout);
                    } else if(result.e == "PERMISSION_DENIED") {
                      Redirect(result.url);
                    } else {
                      Dialog("Error", err, {"OK":close});
                    }
                  }
                });

              } else {
                Dialog("Error","Please specific the \"To\", \"Subject\" and \"Message\" before.", {"OK":close});
              }
            },
            "Cancel":function(){
              if($("#composeToUserId").val() != "" || $("#composeSubject").val() != "" || $("#composeMessage").val() != "") {
                Dialog(
                  "Confirmation",
                  "<strong>Are you sure to cancel and discard all changes?</strong><br/><br/>This message has not been sent and contains some changes.",
                  {
                    "Cancel and Discard change": function() {
                      destroyDialog();
                      destroyChildDialog();
                    },
                    "Back to Compose": close
                  }
                );
              } else {
                destroyChildDialog();
              }
            }
          },
          650
        );
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

function publishItem(step){
  if(step == 1 || step == undefined || step == null) {
    var now_state = getFormData();
    if(checkDiffEditState(now_state, _form.saveState)) {
      Dialog("Confirmation","<strong>Are you sure to publish and discard all changes?</strong><br/><br/>This item has not been saved and contains some changes.", { "Discard Changes":function() { destroyDialog(); publishItem(2); }, "Cancel":close });
    } else {
      Dialog("Confirmation", "<strong>Are you sure you want to publish this item?</strong>", {
        "No":close,
        "Yes": function(){ publishItem(2); }
      });
    }
  } else if(step == 2) {
    var approval_mode = $('#edit_form').find('#fwdparam_approval_mode').val();
    var item_state = $('#edit_form').find('#fwdparam_state').val();
    var tmp_item_id = $('#edit_form').find('#fwdparam_item_id').val();
    var tmp_lang = $('#edit_form').find('#fwdparam_lang').val();
    var tmp_revision = $('#edit_form').find('#fwdparam_revision').val();
    var tmp_item_list = tmp_item_id +"," + tmp_lang + "," + tmp_revision;
    var tmp_c = "publish";

    drawLoadingFullPage();
    $.ajax({
      url: 'list_cmd.php',
      data: {'c': tmp_c , 'i': tmp_item_list},
      type: 'POST', cache: false, timeout: 30000,
      dataType: 'json',
      error: function(){
        Dialog("Error","ERROR",{"OK":close});
      },
      success: function(rs){
        destroyLoadingFullPage();
        if(rs.c == "OK"){
          Dialog("Information",
            "<strong>The item was published completely.</strong><br/><br/>What do you want to do next?",
            { "Back to list": function(){window.location.assign("list.php");},
              "Edit it": function(){window.location.assign("form.php?m=edit&id=" + tmp_item_id + "&l=" + tmp_lang);}
            });
        } else if(rs.e == "SESSION_TIMEOUT") {
          Redirect(_redirect_timeout);
        } else if(rs.e == "PERMISSION_DENIED") {
          Redirect(rs.url);
        } else if(rs.e == "REFERENCE_MAPPING_DUPLICATE_ACTIVE" || rs.e == "REFERENCE_MAPPING_DUPLICATE_DRAFT") {
          $("#fi_"+rs.target).parent().parent().find(".label").addClass("invalid")

          Dialog("Error", "<strong>The items was error.</strong><br/><br/>" + rs.m , { "Close": close } );
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

function unpublishItem(){
  var now_state = getFormData();
  if(checkDiffEditState(now_state, _form.saveState)) {
    Dialog("Confirmation","<strong>Are you sure to unpublish and discard all changes?</strong><br/><br/>This item has not been saved and contains some changes.", { "Discard Changes":function() { destroyDialog(); unpublishItemNext(); }, "Cancel":close });
  } else {
    unpublishItemNext();
  }
}

function unpublishItemNext() {
  var approval_mode = $('#edit_form').find('#fwdparam_approval_mode').val();
  var item_state = $('#edit_form').find('#fwdparam_state').val();
  var tmp_item_id = $('#edit_form').find('#fwdparam_item_id').val();
  var tmp_lang = $('#edit_form').find('#fwdparam_lang').val();
  var tmp_revision = $('#edit_form').find('#fwdparam_revision').val();
  var tmp_item_list = tmp_item_id +"," + tmp_lang + "," + tmp_revision;
  var tmp_c = "";

  var dlg_msg = "";
  dlg_msg = "<strong>Which option do you want to continue with the selected item?</strong><br/>";

  if (item_state == "published_ch") {
    dlg_msg += "<input type=\"radio\" id=\"unpublish_op1\" name=\"unpublish_op\"  checked=\"checked\" /><label for=\"unpublish_op1\">Unpublish and keep the \"recent/modified\" version as draft.</label><br/>";
  }
  dlg_msg += "<input type=\"radio\" id=\"unpublish_op2\" name=\"unpublish_op\" ";
  if(item_state == "published") { dlg_msg += "checked=\"checked\" "; }
  dlg_msg += "/><label for=\"unpublish_op2\">Unpublish and keep the \"published\" version as draft.</label><br/>";
  dlg_msg += "<input type=\"radio\" id=\"unpublish_op3\" name=\"unpublish_op\" /><label for=\"unpublish_op3\">Unpublish and delete (This cannot be undone).</label>";
  Dialog("Confirmation",
      dlg_msg,
      {
        "Continue":function() {
            destroyDialog();
            var save_draft = "";
            var option = $(this).parent().parent().find('.main input:checked').attr("id");
            if(option == "unpublish_op1") {
              save_draft = "modified";
            } else if(option == "unpublish_op2") {
              save_draft = "published";
            } else if(option == "unpublish_op3") {
              save_draft = "yes";
            }
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
              data: {'c':ajax_cmd, 'i': tmp_item_list},
              type: 'POST', cache: false, timeout: 30000,
              dataType: 'json',
              error: function(){
                Dialog("Error","ERROR",{"OK":close});
              },
              success: function(rs){
                destroyLoadingFullPage();
                if(rs.c == "OK"){
                  if (option != "unpublish_op3"){
                    Dialog("Information",
                      "<strong>The item was unpublished completely.</strong><br/><br/>What do you want to do next?",
                      { "Back to list": function(){window.location.assign("list.php");},
                        "Edit it": function(){window.location.assign("form.php?m=edit&id=" + tmp_item_id + "&l=" + tmp_lang);}
                      });
                  } else {
                    Dialog("Information",
                      "<strong>The item was unpublished and deleted completely.</strong><br/><br/>What do you want to do next?",
                      { "Back to list": function(){window.location.assign("list.php");}});
                  }
                } else if(rs.e == "SESSION_TIMEOUT") {
                  Redirect(_redirect_timeout);
                } else if(rs.e == "PERMISSION_DENIED") {
                  Redirect(rs.url);
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
          },
        "Cancel":close
  });
}

function removeItem(){
  var approval_mode = $('#edit_form').find('#fwdparam_approval_mode').val();
  var item_state = $('#edit_form').find('#fwdparam_state').val();
  var tmp_item_id = $('#edit_form').find('#fwdparam_item_id').val();
  var tmp_lang = $('#edit_form').find('#fwdparam_lang').val();
  var tmp_revision = $('#edit_form').find('#fwdparam_revision').val();
  var tmp_item_list = tmp_item_id +"," + tmp_lang + "," + tmp_revision;
  var tmp_c = "";

  if (approval_mode == "on")  {
    tmp_c = "delete";
  } else {
    tmp_c = "delete_noapproval";
  }
  Dialog("Confirmation", "<strong>Are you sure you want to delete this item?</strong><br/><br/>This cannot be undone.",  {
    "No":close,
    "Yes": function(){
        drawLoadingFullPage();
        $.ajax({
          url: 'list_cmd.php',
          data: {'c': tmp_c , 'i': tmp_item_list},
          type: 'POST', cache: false, timeout: 30000,
          dataType: 'json',
          error: function(){
            destroyLoadingFullPage();
            Dialog("Error","ERROR",{"OK":close});
          },
          success: function(rs){
            destroyLoadingFullPage();
            if(rs.c == "OK"){
              window.location.assign('list.php');
            } else if(rs.e == "SESSION_TIMEOUT") {
              Redirect(_redirect_timeout);
            } else if(rs.e == "PERMISSION_DENIED") {
              Redirect(rs.url);
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
  });
}

function saveItem(command,step,extra_step) {

  if(checkNumFileUploading() > 0) {
    Dialog("Error", "There are some files or images uploading. Please wait for a while and try again.", {"OK":close});
    return;
  }

  _form.step = step;
  if (extra_step != undefined) {
    _form.extra_step = extra_step;
  } else {
    _form.extra_step = 0;
  }
  _form.action = $('#edit_form').find('#fwdparam_mode').val() + "|" + command;
  var err = "Unable to get last modified date. Please try again or contact your system administrator to solve the problem.";

  var last_modified_date = $('#edit_form').find('#fwdparam_last_modified_date').val();
  var last_modified_username = $('#edit_form').find('#fwdparam_last_modified_username').val();

  var current_item_id = $('#edit_form').find('#fwdparam_item_id').val();
  var current_revision = $('#edit_form').find('#fwdparam_revision').val();
  var current_lang = $('#edit_form').find('#fwdparam_lang').val();

  if (command != "saveandpublish" && command != "save"){
    Dialog("Error", "Invalid command. reload and try again!!! ", {"OK":close});
    _form.step = 0;
  }
  if (_form.step == undefined) {_form.step = 1};

  /*if($('#fwdparam_mode').val() == "add") {
    drawLoadingFullPage();
    $('#edit_form').find('#fwdparam_command').val(command);
    _form.step = 1;
    submitFormData();
  } else {*/
    if (_form.step == 1){
      if(checkValidation()){
        drawLoadingFullPage();
        $.ajax({
          url: 'form_cmd.php',
          data: {'fwdparam_command':'check_last_modified','fwdparam_mode':'check','fwdparam_item_id': current_item_id,'fwdparam_revision': current_revision,'fwdparam_lang': current_lang, 'mode':$('#fwdparam_mode').val()},
          type: 'POST', cache: false, timeout: 30000,
          dataType: 'json',
          error: function(){
            destroyLoadingFullPage();
            Dialog("Error",err,{"OK":close});
            return false;
          },
          success: function(result){
            if(result.c == "OK"){
              if ((result.step == 5) || (last_modified_date == result.last_modified_date)){
                $('#edit_form').find('#fwdparam_command').val(command);
                _form.step = 1;
                submitFormData();
              } else {
                destroyLoadingFullPage();

                if ($("#val_recovery").size() == 1 && $("#val_recovery").val() != "") {
                  Dialog("Warning",
                    "Do you want to replace it with the one you are editing?",
                    {"Replace":function() {saveItem(command,2,extra_step);},
                    "Cancel":close}
                  );
                } else {
                  Dialog("Warning",
                    "<strong>This item has been modified by \"" + result.last_modified_username + "\" after you loaded it.</strong><br/><br/>Do you want to replace it with the one you are editing or reload it?<br/>If you reload the content, all changes will be discarded.",
                    {"Replace":function() {saveItem(command,2,extra_step);},
                    "Reload it":function(){window.location.assign(window.location.pathname + window.location.search);},
                    "Cancel":close}
                  );
                }
              }
            } else if(result.e == "SESSION_TIMEOUT") {
              Redirect(_redirect_timeout);
            } else if(result.e == "PERMISSION_DENIED") {
              Redirect(result.url);
            } else {
              destroyLoadingFullPage();
              Dialog("Error", result.e, {"OK":close});
            }
            return false;
          }
        });
      }
    } else if (_form.step == 2){
      if(checkValidation()){
        drawLoadingFullPage();
        $('#edit_form').find('#fwdparam_command').val(command);
        _form.step = 1;
        submitFormData();
      }
    }
  /*}*/
}

function submitActionText(){
  var action_display = _form.action.split("|");
  if (action_display[0] == "add" && action_display[1] == "save"){
    return "created";
  } else if (action_display[0] == "add" && action_display[1] == "saveandpublish"){
    return "created and published";
  } else if (action_display[0] == "edit" && action_display[1] == "save"){
    return "modified";
  } else if (action_display[0] == "edit" && action_display[1] == "saveandpublish"){
    return "modified and published";
  }
}

function formOriginalData(){
  var inputdata = {};
  $("#edit_form .row input").each(function(){
    if ($(this).attr('name') != "") inputdata[$(this).attr('name')] = $(this).val();
  });
  $("#edit_form .row select").each(function(){
    if ($(this).attr('name') != "") inputdata[$(this).attr('name')] = $(this).val();
  });
  $("#edit_form .row textarea").each(function(){
    if ( tinyMCE.get($(this).attr('id')) != undefined){
      if ($(this).attr('name') != "") inputdata[$(this).attr('name')] = tinyMCE.get($(this).attr('id')).getContent();
    } else {
      if ($(this).attr('name') != "") inputdata[$(this).attr('name')] = $(this).val();
    }
  });
  _form.data = inputdata;
}

function backToList(url) {
  var now_state = getFormData();
  if(checkDiffEditState(now_state, _form.saveState)) {
    Dialog("Confirmation","<strong>Are you sure to back to list and discard all changes?</strong><br/><br/>This item has not been saved and contains some changes.",{"Discard Changes":function() { window.location.assign(url); }, "Cancel":close});
  } else {
    window.location.assign(url);
  }
}

function checkDiffEditState2(before,after) {
  for(k in before){ if(before[k] != after[k]) return k; }
  for(k in after){ if(before[k] != after[k]) return k; }
  return "ok";
}

function checkDiffEditState(before,after) {
  for(k in before){ if(before[k] != after[k]) return true; }
  for(k in after){ if(before[k] != after[k]) return true; }
  return false;
}

function getFormData() {
  var data = {};

  $("#edit_form .row input").each(function(){
    if($(this).is(":checkbox")) {
      if ($(this).is(':checked')) data[$(this).attr('name')] = $(this).val();
    } else if($(this).is(":radio")) {
      if ($(this).is(':checked')) data[$(this).attr('name')] = $(this).val();
    } else {
      if ($(this).attr('name') != "") data[$(this).attr('name')] = $(this).val();
    }
  });
  $("#edit_form .row select").each(function(){
    if ($(this).attr('name') != "") data[$(this).attr('name')] = $(this).val();
  });
  $("#edit_form .row textarea").each(function(){
    if ( tinyMCE.get($(this).attr('id')) != undefined){
      if ($(this).attr('name') != "") data[$(this).attr('name')] = tinyMCE.get($(this).attr('id')).getContent();
    } else {
      if ($(this).attr('name') != "") data[$(this).attr('name')] = $(this).val();
    }
  });
  return data;
}

function submitFormData(){
  var err = "Unable to connect your server. Please try again or contact your system administrator to solve the problem.";
  if (_form.step != 1){
    return false;
  }
  var inputdata = getFormData();

  inputdata["fwdparam_command"] = $('#edit_form').find('#fwdparam_command').val();
  inputdata["fwdparam_mode"] = $('#edit_form').find('#fwdparam_mode').val();
  inputdata["fwdparam_item_id"] = $('#edit_form').find('#fwdparam_item_id').val();
  inputdata["fwdparam_revision"] = $('#edit_form').find('#fwdparam_revision').val();
  inputdata["fwdparam_lang"] = $('#edit_form').find('#fwdparam_lang').val();
  inputdata["fwdparam_tmp_session"] = $('#edit_form').find('#fwdparam_tmp_session').val();
  inputdata["fwdparam_temp_id"] = $('#edit_form').find('#fwdparam_temp_id').val();

  $.ajax({
    url: 'form_cmd.php',
    data: inputdata,
    type: 'POST', cache: false, timeout: 30000,
    dataType: 'json',
    error: function(){
      destroyLoadingFullPage();
      Dialog("Error",err,{"OK":close});
      return false;
    },
    success: function(result){
      destroyLoadingFullPage();
      if(result.c == "OK") {
        if ($('#edit_form').find('#fwdparam_mode').val() == "add") {
          if (_form.extra_step == 1) {
            window.location.assign("form.php?m=edit&id=" + result.id + "&r=" + result.r + "&l=" + result.l + "&cn=" + _form.child.name);
          } else {
            if(inputdata["fwdparam_item_id"] != 0) {
              Dialog("Information",
                "<strong>The item was " + submitActionText() + " completely.</strong><br/><br/>What do you want to do next?",
                { "Back to list": function(){window.location.assign("list.php");},
                  "Edit it": function(){window.location.assign("form.php?m=edit&id=" + result.id + "&r=" + result.r + "&l=" + result.l);}

                });
            } else {
              Dialog("Information",
                "<strong>The item was " + submitActionText() + " completely.</strong><br/><br/>What do you want to do next?",
                { "Back to list": function(){window.location.assign("list.php");},
                  "Edit it": function(){window.location.assign("form.php?m=edit&id=" + result.id + "&r=" + result.r + "&l=" + result.l);},
                  "Add More": function(){window.location.assign("form.php?m=add&l=" + result.l);}
                });
            }
          }
        } else {
          Dialog("Information",
            "<strong>The item was " + submitActionText() + " completely.</strong><br/><br/>What do you want to do next?",
            { "Back to list": function(){window.location.assign("list.php");},
              "Continue Edit": function(){window.location.assign("form.php?m=edit&id=" + result.id + "&r=" + result.r + "&l=" + result.l);}
            });
        }
      } else if(result.e == "SESSION_TIMEOUT") {
        Redirect(_redirect_timeout);
      } else if(result.e == "PERMISSION_DENIED") {
        Redirect(result.url);
      } else if(result.e == "REFERENCE_MAPPING_DUPLICATE_ACTIVE" || result.e == "REFERENCE_MAPPING_DUPLICATE_DRAFT") {
        $("#fi_"+result.target).parent().parent().find(".label").addClass("invalid")

        Dialog("Error", "<strong>The items was error.</strong><br/><br/>" + result.m , { "Close": close } );
      } else if(result.e == "PARENT_NODE_PATH_PROBLEM") {
        $("#fi_obj_referer_id").parent().parent().find(".label").addClass("invalid")

        Dialog("Error", "<strong>The items was error.</strong><br/><br/>" + result.m , { "Close": close } );
      } else {
        var splitword = result.m.split("|");
        for (var j = 0 ; j < splitword.length ;j ++){

        }
        Dialog("Error", "<strong>The items was error.</strong><br/><br/>" + result.m , { "Close": close } );
      }
    }
  });
}

function newFinder(field,fi_value, fi_id, title) {
    var v = $('#' + fi_id).val();
    drawFinder(field, title, 500, v, function(id,label){
        $('#' + fi_value).attr("value",label);
        $('#' + fi_id).attr("value", id);
    });
}

function showNotification(mode, head, msg){
  $("#edit_form .notification strong").html(head);
  $("#edit_form .notification span").html(msg);
  if(mode == "alert") {
    $("#edit_form .notification").addClass("alert");
  } else {
    $("#edit_form .notification").removeClass("alert");
  }
  $("#edit_form .notification").fadeIn(600);
}

function hideNotification(){
  $("#edit_form .notification").fadeOut(600);
}

function showNotificationPreview(mode, head, msg){
  $("#edit_form .notification_preview_mode strong").html(head);
  $("#edit_form .notification_preview_mode span").html(msg);
  if(mode == "alert") {
    $("#edit_form .notification_preview_mode").addClass("alert");
  } else {
    $("#edit_form .notification_preview_mode").removeClass("alert");
  }
  $("#edit_form .notification_preview_mode").fadeIn(600);
}

function hideNotificationPreview() {
  window.location.assign("form.php?m=" + getParameterByName("m") + "&id=" + getParameterByName("id") + "&r=" + getParameterByName("r"));
}

function showChangeLogDetail(log_id) {
  var err = "Unable to load the change log detail. Please contact your system administrator to solve the problem.";
  drawLoadingFullPage();
  $.ajax({
    url: 'log_data.php',
    data: {'c':'detail', 'i':log_id },
    type: 'POST', dataType: 'json', timeout: 30000, cache: false,
    error: function(){
      destroyLoadingFullPage();
      showNotification("alert","Error: ", err);
      return false;
    },

    success: function(result){
      destroyLoadingFullPage();
      if(result.e == "SESSION_TIMEOUT") {
        Redirect(_redirect_timeout);
      } else if(result.e == "PERMISSION_DENIED") {
        Redirect(result.url);
      } else if(result.status == "FAIL") {
        showNotification("alert","Error: ", err);
        return false;
      } else {
        Dialog("Log detail:" + log_id, result.msg, {"Close":close}, 550);
        return false;
      }
    }
  });
}

function refrechChangeLog(objname, id, lang) {

  if(!_form.log.loaded) {
    _form.log.id = id;
    _form.log.lang = lang;
    _form.log.target_objname = objname;
    drawLoading(objname);
    var err = "Unable to load the change log. Please contact your system administrator to solve the problem.";
    $.ajax({
      url: 'log_data.php',
      data: {'c':'load', 'i':id , 'l':lang , 'sf':_form.log.sf, 'sm':_form.log.sm, 'p':_form.log.p },
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
        } else {
          _form.log.loaded = true;
          $("#" + objname).html(drawTableLog(result.Header, result.Data, result.Param, 780) + printPaginator(result.Page, "goToLogPage", 780));

          $("#" + objname + " .datagrid thead th div").mouseover(function() { $(this).addClass("hover"); }).mouseout(function(){ $(this).removeClass("hover"); });

          fixTableScroll(objname);
          return false;
        }
      }
    });
  }
}

function drawTableLog(header, data, param, table_width){
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
      ret2 += "<th" + th_class + " width=\"" + header[column_code]["width"] + "\" onclick=\"sortLogBy(this, '" + column_code + "')\" >";
      ret2 += "<div" + th_div_class + th_id + ">" + header[column_code]["title"] + "</div>";
      ret2 += "</th>";
      table_width_sum += header[column_code]["width"];
    }

    ret += "<div class=\"table_data\" style=\"overflow:auto;width:" + table_width + "px;\">";
    ret += "<table style=\"width:" + table_width_sum + "px;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"datagrid\" >";
    ret += "<thead>";
    ret += "<tr height=\"15\">";
    ret += ret2;
    ret += "</tr>";
    ret += "</thead>";
    ret += "<tbody>";

    for(i = 0; i < num_row; i++) {
      tr_class = "";
      if(i == (num_row -1)){ tr_class += "last"; }
      if((i % 2) == 1) { tr_class += " odd"; }
      if(tr_class != "") { tr_class = " class=\"" + tr_class + "\" "; }
      ret += "<tr ref=\"" + data[i]["obj_id"] + ":" + data[i]["obj_rev"] + ":" + data[i]["obj_lang"] + ":" + i + ":" + data[i]["obj_state"] + "\" " + tr_class + " onmouseout=\"mouseOutLog(this)\" onmouseover=\"mouseOverLog(this)\" onclick=\"showChangeLogDetail(" + data[i]["log_id"] + ")\" >" + "\r\n";

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
    ret +=  "<div>No log was found</div>";
    ret += "</th>";

    ret += "<tr><td class=\"last bottom empty\" >";
    ret += "<strong>Sorry, No change log were found.</strong>";
    ret += "</td></tr>";
  }
  ret += "</tbody>";
  ret += "</table></div>";
  return(ret);
}

function mouseOverLog(obj){
  $(obj).addClass('over');
}

function mouseOutLog(obj){
  $(obj).removeClass('over');
}

function sortLogBy(obj, field){
  if($(obj).find("div").hasClass("click_asc")){ mode = "asc"; }
  else if($(obj).find("div").hasClass("click_desc")){ mode = "desc";}
  else { mode = ""; }
  _form.log.sf = field;
  _form.log.sm = mode;
  _form.log.p = 1;
  _form.log.loaded = false;
  refrechChangeLog(_form.log.target_objname, _form.log.id, _form.log.lang);
}

function goToLogPage(page){
  _form.log.p = page;
  _form.log.loaded = false;
  refrechChangeLog(_form.log.target_objname, _form.log.id, _form.log.lang);
}

function changeLanguage(obj) {
  var now_state = getFormData();
  var now_lang = $("#fwdparam_lang").val();
  var now_mode = $("#fwdparam_mode").val();
  var next_val = $(obj).val().split(":");
  var url = "form.php?m=" + next_val[0] + "&id=" + $("#fwdparam_item_id").val() + "&l=" + next_val[1];
  if(checkDiffEditState(now_state, _form.saveState)) {
    Dialog("Confirmation","<strong>Are you sure to switch content language and discard all changes?</strong><br/><br/>This item in current language has not been saved and contains some changes.",{"Discard Changes":function() { window.location.assign(url); }, "Cancel":function(){ destroyDialog(); $(obj).val(now_mode + ":" + now_lang);} });
  } else {
    window.location.assign(url);
  }
}