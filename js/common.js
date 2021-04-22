var arrLang = JSON.parse(sData_lang);
$(this).on('show.bs.modal load', function() {

    // console.log('html'); แปลภาษา
    $(".lang").each(function(index, element) {
        // console.log('index',"index");
        var key = $(this).attr("key");
        $(this).text(arrLang[key]);
    });
})

$(document).ready(function(){

    $('#sign_out').on("click", function() {
        $.ajax({
            type: "post",
            url: BASE_LANG + "service/login.php",
            data: {
              "cmd": "check_logout",
            },
            dataType: "json",
            beforeSend: function(){
              $('#sign_out').prop('disabled', true);
            },
            complete: function(){
              $('#sign_out').prop('disabled', false);
            },
            success: function(res) {
                var status = res['status'];
                var msg = res['msg'];
                if (status == true) {
                  alert_center('Process logout', msg, "success")
                  setTimeout(function(){ window.location = BASE_LANG; }, 1500);
                }else{
                  alert_center('Process logout', msg, "danger")
                }
            }
        });
    });
    
    $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
});

$.validator.addMethod("numberOnly", function(value, element, param) {
  return this.optional(element) || /^[0-9]+$/i.test(value);
}, "Please enter only number !");

$.validator.addMethod("is_english", function(value, element, param) {
  return this.optional(element) || /^[a-zA-Z0-9]+$/i.test(value);
}, "Please enter only english or number !");

$.validator.addMethod("notEnough", function(value, element, param) {
  return this.optional(element) || parseInt(value) <= parseInt(param);
}, "Please enter value less than !");

$.validator.addMethod("notEqual", function(value, element, param) {
  return this.optional(element) || value > param;
}, "Please enter expiration date at least a day !");

$.validator.addMethod("equalDay", function(value, element, param) {
  return this.optional(element) || moment(value).format("YYYY-MM-DD HH:mm") >= moment().format("YYYY-MM-DD HH:mm");
}, "Please enter datetime greater than or equal to the current day !");

// form validate t
// เช็คขนาดไฟล์
$.validator.addMethod('filesize', function(value, element, param) {
    // alert(value + ' - '+ element.files[0].size + ' - '+ param + ' - ' +this.optional(element) );
   return this.optional(element) || (element.files[0].size <= param) 
});   
// เช็คนามสกุลไฟล์
$.validator.addMethod("accepta",function(t,e,i){
    var a,r,n="string"==typeof i?i.replace(/\s/g,"").replace(/,/g,"|"):"image/*",s=this.optional(e);
    if(s)return s;
    if("file"===$(e).attr("type")&&(n=n.replace(/\*/g,".*"),e.files&&e.files.length))
        for(a=0;e.files.length>a;a++)
            if(r=e.files[a],!r.type.match(RegExp(".?("+n+")$","i")))
                return!1;
            return!0
},"File must be JPEG or PNG!");

// เช็ค selectone กรณี ต้องเลือกอีนใดอันหนึ่ง
$.validator.addMethod('selectone', function(value,element) {
    var flag = false;
    $('.require-one :selected').each(function() {
        if($(this).val() !== "") {
            flag = true;
            return false;
        }
    });
    return flag;
}, 'required at least one dropdown is set to a certain value.');

$.validator.addMethod("laxEmail", function(value, element) {
  // allow any non-whitespace characters as the host part
  return this.optional( element ) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@(?:\S{1,63})$/.test( value );
}, 'Please enter a valid email address.');

$.validator.addMethod("selectNull", function(value, element) {
  // allow any non-whitespace characters as the host part
    if (value != "") {
        return true;
    }else{
        return false
    }

}, 'Please enter a valid select.');

// validator phoneTH
$.validator.addMethod("phoneTHcheck", function(value, element) {

    var sub_tel_2 = value.substring(2, 0);
    if (sub_tel_2 == "66") {
        return this.optional(element) || /^([6]{2})([8]|[6]|[9]{1})([0-9]{8})+$/i.test(value);
    }else{
        return this.optional(element) || /^([0]{1})([8]|[6]|[9]{1})([0-9]{8})+$/i.test(value);
    }

}, 'ข้อมูลตัวอย่าง เช่น 08xxxxxxxx หรือ 668xxxxxxxxx');
// validator phoneTH

$.validator.addMethod("notEqual", function(value, element, param) {
  return this.optional(element) || value <= $('#campaign_enddate').val();
}, "Please enter expiration date at least a day !");

$.validator.addMethod("campKeyword", function(value) { //add custom method
  console.log(value)
    //Tags input plugin converts input into div having id #YOURINPUTID_tagsinput
    //now you can count no of tags
    return ($("#tagsx_tagsinput").find(".tag").length > 0);
});

function fm_number(number){
    num = Number(number).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    return num;
}

function CheckNum(){
    if (event.keyCode != 44 && event.keyCode != 13 && event.keyCode < 48 || event.keyCode > 57){
      event.returnValue = false;
     }
}

// check_text_english
function check_text_english(key){
    // var ew = event.which;
    // if(ew == 32)
    //     return true;
    // if(48 <= ew && ew <= 57)
    //     return true;
    // if(65 <= ew && ew <= 90)
    //     return true;
    // if(97 <= ew && ew <= 122)
    //     return true;

    // return false;

    return /^[a-z0-9\-\" "\,\/\.\+\-\*\=\(\)\:\;\!\#\$\%\&\<\>\?\@\[\]\_\`\~\{\}\|]+$/i.test(key);
    // if (event.keyCode != 13 && (event.keyCode > 125 || event.keyCode == 34 || event.keyCode == 92 || event.keyCode == 96)){
    //   event.returnValue = false;
    //   return false;
    //  }
    // return true;
}

// Currency Formatting
function numberWithCommas(number) {
    var parts = number.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

function alert_top_center(type_alert, title, time){
  const Toast = Swal.mixin({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: time,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })
  Toast.fire({
    icon: type_alert,
    title: title //'Signed in successfully'
  })
}

function alert_rigth_url(type_alert, title, time, url){

    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: time,
      timerProgressBar: true,
      onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    })
    Toast.fire({
      icon: type_alert,
      title: title
    }).then((result) => {
        window.location.href = BASE_LANG + url;
    })
}

function alert_rigth_2(type_alert, title, time){
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: time,
      timerProgressBar: true,
      onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    })
    Toast.fire({
      icon: type_alert,
      title: title
    })
}

function alert_center_url(type_alert, title, text, url){
    Swal.fire({
      title: title,
      text: text,
      icon: type_alert,
      allowOutsideClick: false,
      showCancelButton: false,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'OK'
    })
    .then((result) => {
        window.location.href = BASE_LANG + url;
    })
}

function alert_center(title, text, icon){
    var btn = (icon == "success") ? false : true;
    var time = (icon == "success") ? 1500 : 0;
    swal({
        title: title,
        text: text,
        icon: icon,
        button: btn,
        showConfirmButton: false,
        timer: time,
    })
}

// compareArray
function getDistinctArray(arr) {
    var compareArray = new Array();
    if (arr.length > 1) {
        for (i = 0;i < arr.length;i++) {
            if (compareArray.indexOf(arr[i]) == -1) {
                compareArray.push(arr[i]);
            }
        }
    }
    return compareArray;
}


//----------------------------

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function insertAtCaret(areaId, text) {
    // var curPos = document.getElementById(areaId).selectionStart; 
    // var text_area = $("#" + areaId + "");
    // let x = text_area.val(); 
    // text_area.val(x.slice(0, curPos) + text + x.slice(curPos)); 
    // text_area.focus();

    var txtarea = document.getElementById(areaId);
    var scrollPos = txtarea.scrollTop;
    var caretPos = txtarea.selectionStart;

    var front = (txtarea.value).substring(0, caretPos);
    var back = (txtarea.value).substring(txtarea.selectionEnd, txtarea.value.length);
    txtarea.value = front + text + back;
    caretPos = caretPos + text.length;
    txtarea.selectionStart = caretPos;
    txtarea.selectionEnd = caretPos;
    txtarea.focus();
    txtarea.scrollTop = scrollPos;
}

function urldecode(str) {
   return decodeURIComponent((str+'').replace(/\+/g, '%20'));
}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

function encode_quote(text){
    // {$#A01$} = '
    // {$#A02$} = "
    var value = text.replaceAll("'", "{$#A01$}").replaceAll('"', "{$#A02$}").replaceAll(':', "{$#A03$}").replaceAll('.', "{$#A04$}");

    return btoa(value);
}

function decode_quote(text){
    // {$#A01$} = '
    // {$#A02$} = "
    var value = atob(text).replaceAll("{$#A01$}", "'").replaceAll('{$#A02$}', '"').replaceAll('{$#A03$}', ':').replaceAll('{$#A04$}', '.');

    return value;
}


function get_page_perm(page_url) {
    var s_path =  page_url.split("/").slice(2).join("/");

    if (localStorage.getItem('user_pos') === null)
        gen_perm();
    
    if (localStorage.getItem('user_pos') !== null) {
        objPerm = JSON.parse(localStorage.getItem('user_pos'))[s_path];
        if (objPerm)
            return objPerm
        else
            window.location.href = '/';
    } else {
        window.location.href = '/';
    }
}

function makeid(length = 32) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}


(function ($) {
    $.fn.inputFilter = function (inputFilter) {
        return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function () {
            if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
                this.value = "";
            }
        });
    };
})(jQuery);

function removeValueArray(original, remove) {
//   var array = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
//   var filtered = array.filter(function(value, index, arr){ 
//     return value != 5;
//   });

  return original.filter(value => !remove.includes(value));
}

function removeArray(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function uniqId() {
  return Math.round(new Date().getTime() + (Math.random() * 26));
}

function alert_Toast(type_alert, title, text, time_show) {

    var date = new Date().getTime();
    var bg_alert = "";
    if (type_alert == "success"){
      bg_alert = "bg-success"
    }else if (type_alert == "warning") {
      bg_alert = "bg-warning"
    }else{
      bg_alert = "bg-danger"
    }

    var toast = '';
    toast    += '<div class="toast m-3" aria-live="assertive" aria-atomic="true" style="position: absolute; top: 0; right: 0;">';
    toast    += '<div class="toast-header text-white ' + bg_alert + '">';
    toast    += '<strong class="mr-auto"><i class="fas fa-check"></i> ' + title + '</strong>';
    // toast    += '<small>11 mins ago</small>';
    toast    += '<a class="ml-2 close cursor-pointer text-white" data-dismiss="toast" aria-label="Close">';
    toast    += '<i class="fas fa-times"></i>';
    toast    += '</a>';
    toast    += '</div>';
    toast    += '<div class="toast-body">';
    toast    += '<div>' + text + '</div>';
    toast    += '</div>';
    toast    += '</div>';
    
    $('#toast-container').html(toast);

    $(".toast").toast({ delay: time_show });
    $(".toast").toast('show');
}

$('body').on('click','.close',function(){
      $(this).closest('.toast').toast('hide')
})


function modal_remove(btn_remove_id, modalID, text, divID){
    var modal_removeHTML = '';
    modal_removeHTML += '<div class="modal modal-blur fade" id="' + modalID + '">';
    modal_removeHTML += '<div class="modal-dialog modal-sm" role="document">';
    modal_removeHTML += '<div class="modal-content">';
    modal_removeHTML += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    modal_removeHTML += '<div class="modal-status bg-danger"></div>';
    modal_removeHTML += '<div class="modal-body text-center py-4">';
    modal_removeHTML += '<svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>';
    modal_removeHTML += '<h3>Are you sure?</h3>';
    modal_removeHTML += '<div class="text-muted">' + text + '</div>';
    modal_removeHTML += '</div>';
    modal_removeHTML += '<div class="modal-footer">';
    modal_removeHTML += '<div class="w-100">';
    modal_removeHTML += '<div class="row">';
    modal_removeHTML += '<div class="col"><button class="btn btn-white w-100" data-bs-dismiss="modal">Cancel</button></div>';
    modal_removeHTML += '<div class="col"><button id="' + btn_remove_id + '" class="btn btn-danger w-100" data-bs-dismiss="modal">Remove</button></div>';
    modal_removeHTML += '</div>';
    modal_removeHTML += '</div>';
    modal_removeHTML += '</div>';
    modal_removeHTML += '</div>';
    modal_removeHTML += '</div>';
    modal_removeHTML += '</div>';
    $('#' + divID).html(modal_removeHTML);
    $('#' + modalID).modal('show');
}

function Course_speeker(course_id){
  $.ajax({
      type: "post",
      url: BASE_LANG + "service/course.php",
      data: {
        "cmd": "add_course_speeker",
      },
      dataType: "json",
      beforeSend: function(){
        // $('#course_speeker_list').html(speeker_skelton);
      },
      complete: function(){
        // $('#sign_out').prop('disabled', false);
      },
      success: function(res) {
        var status = res['status'];
        var data   = res['data'];

        var append_data = '';
        $.each(data, function( index, value ) {
            var modal_addCourseHTML = '';
            modal_addCourseHTML += '<div class="col-12 my-1">';
            modal_addCourseHTML += '<div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">';
            modal_addCourseHTML += '<label class="form-selectgroup-item flex-fill">';
            modal_addCourseHTML += '<input type="checkbox" value="' + value.speaker_id + '" name="add-course-speeker" class="form-selectgroup-input">';
            modal_addCourseHTML += '<div class="form-selectgroup-label d-flex align-items-center p-2">';
            modal_addCourseHTML += '<div class="me-3"><span class="form-selectgroup-check"></span></div>';
            modal_addCourseHTML += '<div class="form-selectgroup-label-content d-flex align-items-center">';
            modal_addCourseHTML += '<span class="avatar me-3" style="background-image: url(' + BASE_URL + 'images/speeker/' + value.speaker_image + ')"></span>';
            modal_addCourseHTML += '<div><div class="font-weight-medium">' + value.speaker_name + ' ' + value.speaker_surname + '</div><div class="text-muted">' + value.speaker_company + ' (' + value.speaker_position + ')</div></div>';
            modal_addCourseHTML += '</div>';
            modal_addCourseHTML += '</div>';
            modal_addCourseHTML += '</label>';
            modal_addCourseHTML += '</div>';
            modal_addCourseHTML += '</div>';

            append_data += modal_addCourseHTML;
        });

        $('#add_course_speeker').html(append_data);

      }
  });
}

function editCourse_speeker(course_id){
  $.ajax({
      type: "post",
      url: BASE_LANG + "service/course.php",
      data: {
        "cmd": "add_course_speeker",
      },
      dataType: "json",
      beforeSend: function(){
        // $('#course_speeker_list').html(speeker_skelton);
      },
      complete: function(){
        // $('#sign_out').prop('disabled', false);
      },
      success: function(res) {
        var status = res['status'];
        var data   = res['data'];

        var append_data = '';
        $.each(data, function( index, value ) {
            var modal_addCourseHTML = '';
            modal_addCourseHTML += '<div class="col-12 my-1">';
            modal_addCourseHTML += '<div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">';
            modal_addCourseHTML += '<label class="form-selectgroup-item flex-fill">';
            modal_addCourseHTML += '<input type="checkbox" value="' + value.speaker_id + '" name="add-course-speeker" class="form-selectgroup-input">';
            modal_addCourseHTML += '<div class="form-selectgroup-label d-flex align-items-center p-2">';
            modal_addCourseHTML += '<div class="me-3"><span class="form-selectgroup-check"></span></div>';
            modal_addCourseHTML += '<div class="form-selectgroup-label-content d-flex align-items-center">';
            modal_addCourseHTML += '<span class="avatar me-3" style="background-image: url(' + BASE_URL + 'images/speeker/' + value.speaker_image + ')"></span>';
            modal_addCourseHTML += '<div><div class="font-weight-medium">' + value.speaker_name + ' ' + value.speaker_surname + '</div><div class="text-muted">' + value.speaker_company + ' (' + value.speaker_position + ')</div></div>';
            modal_addCourseHTML += '</div>';
            modal_addCourseHTML += '</div>';
            modal_addCourseHTML += '</label>';
            modal_addCourseHTML += '</div>';
            modal_addCourseHTML += '</div>';

            append_data += modal_addCourseHTML;
        });

        $('#edit_course_speeker').html(append_data);
        editSelectCourse_speeker(course_id);
      }
  });
}

function editSelectCourse_speeker(course_id){
  $.ajax({
      type: "post",
      url: BASE_LANG + "service/course.php",
      data: {
        "cmd": "edit_course_speeker",
        "course_id": course_id
      },
      dataType: "json",
      beforeSend: function(){
        // $('#course_speeker_list').html(speeker_skelton);
      },
      complete: function(){
        // $('#sign_out').prop('disabled', false);
      },
      success: function(res) {
        var status = res['status'];
        var data   = res['data'];
        $.each(data, function( index, value ) {
          $('input:checkbox[value="' + value.speaker_id + '"]').prop('checked', true);
        });
      }
  });
}

function customer(course_id){
  
  $.ajax({
      type: "post",
      url: BASE_LANG + "service/course.php",
      data: {
        "cmd": "customer",
        "course_id": course_id
      },
      dataType: "json",
      beforeSend: function(){
        // $('#course_speeker_list').html(speeker_skelton);
      },
      complete: function(){
        // $('#sign_out').prop('disabled', false);
      },
      success: function(res) {
        var status = res['status'];
        var data   = res['data'];
        var append_data = '';

        append_data += '<select type="text" class="form-select validate[required] w-100" id="select_customer" name="select_customer">' ;
        append_data += '<option selected disabled value="" data-custom-properties="&lt;span class=&quot;avatar avatar-xs&quot; style=&quot;background-image: url(./images/face28.jpg)&quot;&gt;&lt;/span&gt;">Select customer</option>' ;

        $.each(data, function( index, value ) {
          append_data += '<option value="' + value.cus_id + '" data-custom-properties="&lt;span class=&quot;avatar avatar-xs&quot; style=&quot;background-image: url(' + BASE_URL + 'images/customer/' + value.customer_image + ')&quot;&gt;&lt;/span&gt;">' + value.customer_fullname + '</option>' ;
        });

        append_data += '</select>' ;

        $('#span_customer').html(append_data);

        var el;
        window.Choices && (new Choices(el = document.getElementById('select_customer'), {
          classNames: {
            containerInner: el.className,
            input: 'form-control',
            inputCloned: 'form-control-sm',
            listDropdown: 'dropdown-menu',
            itemChoice: 'dropdown-item',
            activeState: 'show',
            selectedState: 'active',
          },
          shouldSort: false,
          searchEnabled: true,
          callbackOnCreateTemplates: function(template) {
            var classNames = this.config.className,
                itemSelectText = this.config.itemSelectText;
            return {
              item: function(classNames, data) {
                return template('<div class="' + String(classNames.item) + ' ' + String( data.highlighted ? classNames.highlightedState : classNames.itemSelectable ) + '" data-item data-id="' + String(data.id) + '" data-value="' + String(data.value) + '"' + String(data.active ? 'aria-selected="true"' : '') + '' + String(data.disabled ? 'aria-disabled="true"' : '') + '><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + String(data.label) + '</div>');
              },
              choice: function(classNames, data) {
                return template('<div class="' + String(classNames.item) + ' ' + String(classNames.itemChoice) + ' ' + String( data.disabled ? classNames.itemDisabled : classNames.itemSelectable ) + '" data-select-text="' + String(itemSelectText) + '" data-choice  ' + String( data.disabled ? 'data-choice-disabled aria-disabled="true"' : 'data-choice-selectable' ) + ' data-id="' + String(data.id) + '" data-value="' + String(data.value) + '" ' + String( data.groupId > 0 ? 'role="treeitem"' : 'role="option"' ) + ' ><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + String(data.label) + '</div>');
              },
            };
          },
        }));

      }
  });
}

function comment(course_id){
  
  $.ajax({
      type: "post",
      url: BASE_LANG + "service/course.php",
      data: {
        "cmd": "comment",
        "course_id": course_id
      },
      dataType: "json",
      beforeSend: function(){
        // $('#course_speeker_list').html(speeker_skelton);
      },
      complete: function(){
        // $('#sign_out').prop('disabled', false);
      },
      success: function(res) {
        var status = res['status'];
        var data   = res['data'];
        var append_data = '';

        if (data.length != 0) {
          $.each(data, function( index, value ) {

            var commentHTML = '';
            commentHTML += '<div id="divComment_' + value.commenter_id + '" class="my-2">';
            commentHTML += '<span style="float: right;">';
            commentHTML += '<button type="button" data-comid="' + value.commenter_id + '" class="remove_comment btn btn-outline-danger btn-sm mx-2"><i class="far fa-trash-alt"></i></button>';
            commentHTML += '</span>';
            commentHTML += '<blockquote>';
            commentHTML += '<b>' + value.commenter_title + '</b>';
            commentHTML += '<p><em>' + value.commenter_detail + '</em></p>';
            commentHTML += '</blockquote>';
            commentHTML += '<div class="row">';
            commentHTML += '<div class="col-auto">';
            commentHTML += '<span class="avatar" style="background-image: url(' + BASE_URL + 'images/customer/' + value.customer_image + ')"></span>';
            commentHTML += '</div>';
            commentHTML += '<div class="col">';
            commentHTML += '<div class="text-truncate">';
            commentHTML += '<b>' + value.customer_fullname + '</b> commented ' + moment(value.commentdate, "YYYYMMDD hh:mm:").fromNow(); + ' post.';
            commentHTML += '</div>';
            commentHTML += '<div class="text-muted">' + value.customer_company + ' (' + value.customer_position + ')</div>';
            commentHTML += '</div>';
            commentHTML += '</div>';
            commentHTML += '</div>';

            append_data += commentHTML;

          });

        }else{
          append_data = '<blockquote>No comment..</blockquote>'
        }

        $('#divComment').html(append_data);

        // REMOVE COMMENT
        $(".remove_comment").on('click', function(){
          var rm_comment_id = $(this).attr('data-comid');
          $.ajax({
              type: "post",
              url: BASE_LANG + "service/course.php",
              data: {
                "cmd": "remove_comment",
                "comment_id": rm_comment_id
              },
              dataType: "json",
              beforeSend: function(){
                $(':button[type="button"]').prop('disabled', true);
              },
              complete: function(){
                setTimeout(function() {$(':button[type="button"]').prop('disabled', false)}, 500);
              },
              success: function(res) {
                var status = res['status'];
                var msg = res['msg'];
                if (status == true) {
                  $("#divComment_" + rm_comment_id).fadeOut(500, function() { $(this).remove(); });

                }else{
                    alert_center('Process remove comment', msg, "error")
                }

              }
          });
        });

      }
  });
}