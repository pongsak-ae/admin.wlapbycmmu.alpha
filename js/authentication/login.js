$(document).ready(function(){

    fnc_change_iconPass();
    validator_login();

});

function validator_login(){
    var validator_login = $('#login').validate({
        rules: {
            login_username: {
                required: true
            },
            login_password: {
                required: true
            }
        },
        errorPlacement: function(error, element) {
        },
        errorClass: "help-inline text-danger",
         highlight: function(element) {
             $(element).closest('.form-group').addClass('has-error').removeClass('has-success');
             $(element).closest('.form-group').prevObject.addClass('is-invalid').removeClass('is-valid');
         },
         unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');//.addClass('has-success');
            $(element).closest('.form-group').prevObject.removeClass('is-invalid').addClass('is-valid');

         },
        submitHandler: function(form, e) {
            e.preventDefault();
            submit_login(form,e);
        } 
    });
}

function submit_login(form,e){
    
    var inputs = $(form).find(':input');
    var login_username = inputs.filter('#login_username').val();
    var login_password = inputs.filter('#login_password').val();

    // ###### call api btn_submit ###### \\
    $.ajax({
        type: "post",
        url: BASE_LANG + "service/login.php",
        data: {
            "cmd": "login",
            "login_username": login_username,
            "login_password": login_password
        },
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
                alert_center('Process login', msg, "success")
                setTimeout(function(){ window.location = BASE_LANG; }, 1500);
            }else{
                alert_center('Process login', msg, "error")
            }
        }
    });

}

function fnc_change_iconPass(){
    $("#toggle-password").click(function() {
        $(".toggle-password").toggleClass("fa-eye fa-eye-slash");
        var input = $('#login_password');
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
}