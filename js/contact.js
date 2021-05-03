$(document).ready(function(){
	update_contact_validate()

});


function update_contact_validate(){
  $("#update_contact").validate({
      rules: {
          contact_address: {
            required: true
          },
          contact_phone: {
            required: true
          },
          contact_email : {
          	required: true,
          	laxEmail: true
          },
          // contact_fax : {
          // 	required: true
          // },
          // contact_line : {
          // 	required: true
          // },
          // contact_facebook : {
          // 	required: true
          // }
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

          submit_update_contact(form, e);
      } 
  });
}

function submit_update_contact(form, e) {
    var inputs = $(form).find(':input');
    var form_data = new FormData();

    form_data.append("cmd"           		, 'update_contact');
    form_data.append("contact_address"   	, inputs.filter('#contact_address').val());
    form_data.append("contact_phone"  		, inputs.filter('#contact_phone').val());
    form_data.append("contact_email"  		, inputs.filter('#contact_email').val());
    form_data.append("contact_fax"  		, inputs.filter('#contact_fax').val());
    form_data.append("contact_line"  		, inputs.filter('#contact_line').val());
    form_data.append("contact_facebook"  	, inputs.filter('#contact_facebook').val());

    $.ajax({
        type: "post",
        url: BASE_LANG + "service/contact.php",
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
              alert_center('Process add banner', msg, "success")
          }else{
              alert_center('Process add banner', msg, "error")
          }
        }
    });
}