$(document).ready(function(){


    // CHANGE ICON PASSWORD
    fnc_change_iconPass();

    validator_register();

});

function validator_register(){
    var validator_register = $('#register').validate({
        rules: {
            username: {
                required: true
            },
            // name: {
            //     required: true
            // },
            password: {
                required: true,
                minlength: 6
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            },
            email: {
                required: true,
                laxEmail: true
            }
        },
        errorPlacement: function(error, element) {
        },
        errorClass: "help-inline text-danger",
         highlight: function(element) {
             $(element).closest('.form-group').addClass('has-error').removeClass('has-success');
             $(element).closest('.form-group').prevObject.addClass('is-invalid').removeClass('is-valid');
             // console.log($(element).closest('.form-group').prevObject)
         },
         unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');//.addClass('has-success');
            $(element).closest('.form-group').prevObject.removeClass('is-invalid').addClass('is-valid');
         },
        submitHandler: function(form, e) {
            e.preventDefault();

            submit_register(form,e);
        }
    });
}

function submit_register(form,e) {
    var inputs = $(form).find(':input');
    var form_data = new FormData();

    form_data.append("cmd", 'register');
    form_data.append("username"             , inputs.filter('#username').val());
    form_data.append("password"             , inputs.filter('#password').val());
    form_data.append("confirm_password"     , inputs.filter('#confirm_password').val());
    form_data.append("email"                , inputs.filter('#email').val());

    $.ajax({
        type: "post",
        url: BASE_LANG + "service/register.php",
        contentType: false,
        cache: false,
        processData: false,
        data: form_data,
        dataType: "json",
        beforeSend: function(){
            $(':button[type="submit"]').prop('disabled', true);
        },
        complete: function(){
            $(':button[type="submit"]').prop('disabled', false);
        },
        success: function(res) {
            var status = res['status'];
            var msg = res['msg'];
            if (status == true) {
                // alert_top_center("success", arrLang[msg], 1500);
                alert_Toast("success", msg, msg, 1500)
                setTimeout(function(){ window.location = BASE_LANG; }, 1500);
            }else{
                alert_Toast("danger", "Unsuccess", msg, 3000)
                // alert_center("warning", "Unsuccess", arrLang[msg])
            }
        }
    });
}

function fnc_change_iconPass(){
    $("#toggle-password").click(function() {
        $(".toggle-password").toggleClass("fa-eye fa-eye-slash");
        var input = $('#password');
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    $("#toggle-password-span").click(function() {
        $(".toggle-password-span").toggleClass("fa-eye fa-eye-slash");
        var input_confirm = $('#confirm_password');
        if (input_confirm.attr("type") == "password") {
            input_confirm.attr("type", "text");
        } else {
            input_confirm.attr("type", "password");
        }
    });

}