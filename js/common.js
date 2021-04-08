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
                  setTimeout(function(){ window.location = BASE_LANG; }, 1000);
                }else{
                  alert_center('Process logout', msg, "danger")
                }
            }
        });
    });
    
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
    var time = (icon == "success") ? 1000 : 0;
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
    var value = text.replaceAll("'", "{$#A01$}").replaceAll('"', "{$#A02$}");

    return btoa(value);
}

function decode_quote(text){
    // {$#A01$} = '
    // {$#A02$} = "
    var value = atob(text).replaceAll("{$#A01$}", "'").replaceAll('{$#A02$}', '"');

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