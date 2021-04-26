$(document).ready(function(){
	$("#btn_banner").on('click', function(){
		add_banner();
	});

	show_banner();

});

function show_banner(){
    $.ajax({
        type: "post",
        url: BASE_LANG + "service/banner.php",
        data: {
          "cmd": "show_banner",
        },
        dataType: "json",
        beforeSend: function(){
          // $('#sign_out').prop('disabled', true);
        },
        complete: function(){
          // $('#sign_out').prop('disabled', false);
        },
        success: function(res) {
            var status 	= res['status'];
            var data 	= res['data'];
            
            var bannerHTML = '';
            $.each(data, function( index, value ) {
            	var checked = '';
            	var ribbon  = '';
            	if (value.banner_active == '1'){
            		checked = 'checked';
	            	ribbon += '<div class="ribbon ribbon-top ribbon-bookmark bg-green">';
	            	ribbon += '<i class="fas fa-check" style="font-size: large;"></i>';
	            	ribbon += '</div>';
            	}

            	bannerHTML += '<div class="col-sm-6 col-lg-3">';
            	bannerHTML += '<div class="card card-sm">';
            	bannerHTML += ribbon;
            	bannerHTML += '<a target="_blank" href="' + BASE_URL + 'images/banner/' + value.banner_image + '" class="d-block"><img src="' + BASE_URL + 'images/banner/' + value.banner_image + '" class="card-img-top"></a>';
            	bannerHTML += '<div class="card-body">';
            	bannerHTML += '<div class="d-flex align-items-center">';
            	bannerHTML += '<span data-b-name="' + value.banner_name + '" data-b-id="' + value.banner_id + '" name="remove_banner" class="cursor-pointer avatar me-3 rounded text-danger"><i class="far fa-trash-alt"></i></span>';
            	bannerHTML += '<div>';
            	bannerHTML += '<div>' + value.banner_name + '</div>';
            	bannerHTML += '</div>';
            	bannerHTML += '<div class="ms-auto">';
            	bannerHTML += '<label class="form-check form-switch form-check-inline m-auto mt-1">';
            	bannerHTML += '<input data-active-id="' + value.banner_id + '" name="update_banner" class="cursor-pointer form-check-input" type="checkbox" ' + checked + '>';
            	bannerHTML += '</label>';
            	bannerHTML += '</div>';
            	bannerHTML += '</div>';
            	bannerHTML += '</div>';
            	bannerHTML += '</div>';
            	bannerHTML += '</div>';
            });

            $("#show_banner").html(bannerHTML).fadeIn(500);

            $('[name="remove_banner"]').on('click', function(){
            	var banner_id 	= $(this).attr('data-b-id');
            	var banner_name = $(this).attr('data-b-name');

		        // MODAL REMOVE BANNER
		        var remove_modalText  = 'Do you really want to remove banner name <br><b>' + banner_name + '</b> ?';
		        var modalID           = 'modal_removeBannerGEN';
		        var btn_remove_id     = 'submit_remove_banner';
		        modal_remove(btn_remove_id, modalID, remove_modalText, 'modal_removeBanner');

		        $('#' + btn_remove_id).on('click', function(){
		          $.ajax({
		              type: "post",
		              url: BASE_LANG + "service/banner.php",
		              data: {
		                  "cmd": "remove_banner",
		                  "banner_id": banner_id
		              },
		              dataType: "json",
		              beforeSend: function(){
		                $('[name="remove_banner"]').prop('disabled', true);
		              },
		              complete: function(){
		                $('[name="remove_banner"]').prop('disabled', false);
		              },
		              success: function(res) {
		                  var status = res['status'];
		                  var msg = res['msg'];
		                  if (status == true) {
		                      alert_center('Process remove banner', msg, "success")
		                      show_banner();
		                      $('#' + modalID).modal('hide');
		                  }else{
		                      alert_center('Process remove banner', msg, "error")
		                  }
		              }
		          });
		        });
            });

            $('[nane="update_banner"]').on('change', function(){
            	var active_banner_id 	= $(this).attr('data-active-id');
            	console.log(active_banner_id)
            	console.log($(this))
		          // $.ajax({
		          //     type: "post",
		          //     url: BASE_LANG + "service/banner.php",
		          //     data: {
		          //         "cmd": "update_banner",
		          //         "active_banner_id": active_banner_id
		          //     },
		          //     dataType: "json",
		          //     beforeSend: function(){
		          //       $('[name="remove_banner"]').prop('disabled', true);
		          //     },
		          //     complete: function(){
		          //       $('[name="remove_banner"]').prop('disabled', false);
		          //     },
		          //     success: function(res) {
		          //         var status = res['status'];
		          //         var msg = res['msg'];
		          //         if (status == true) {
		          //             alert_center('Process remove banner', msg, "success")
		          //             show_banner();
		          //             $('#' + modalID).modal('hide');
		          //         }else{
		          //             alert_center('Process remove banner', msg, "error")
		          //         }
		          //     }
		          // });
            });
        }
    });
}

function add_banner(){

	var modal_addBannerHTML = '';
	modal_addBannerHTML += '<div class="modal modal-blur fade" id="modal_add_bannerGEN" data-bs-backdrop="static" data-bs-keyboard="false">';
	modal_addBannerHTML += '<div class="modal-dialog modal-lg" role="document">';
	modal_addBannerHTML += '<div class="modal-content">';
	modal_addBannerHTML += '<div class="modal-header">';
	modal_addBannerHTML += '<h5 class="modal-title">New banner</h5>';
	modal_addBannerHTML += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '<form id="add_banner">';
	modal_addBannerHTML += '<div class="modal-body">';
	modal_addBannerHTML += '<div class="row">';
	modal_addBannerHTML += '<div class="col-md-4 col-sm-12">';
	modal_addBannerHTML += '<div class="mb-3">';
	modal_addBannerHTML += '<div class="form-floating">';
	modal_addBannerHTML += '<select class="form-select" id="add_banner_status" name="add_banner_status">';
	modal_addBannerHTML += '<option value="1">Active</option><option value="0">Inactive</option>';
	modal_addBannerHTML += '</select>';
	modal_addBannerHTML += '<label for="add_banner_status">Select</label>';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '<div class="col-md-8 col-sm-12">';
	modal_addBannerHTML += '<div class="mb-3">';
	modal_addBannerHTML += '<div class="form-floating mb-3">';
	modal_addBannerHTML += '<input id="add_banner_name" name="add_banner_name"  type="text" class="form-control" placeholder="n">';
	modal_addBannerHTML += '<label for="add_banner_name">Banner name</label>';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '<div class="col-12">';
	modal_addBannerHTML += '<div class="card card-sm">';
	modal_addBannerHTML += '<a class="d-block" target="_blank">';
	modal_addBannerHTML += '<img id="banner_img" src="' + BASE_URL + 'images/no-image.jpg" class="card-img-top" style="height: 25rem;object-fit: cover;">';
	modal_addBannerHTML += '</a>';
	modal_addBannerHTML += '<div class="card-body">';
	modal_addBannerHTML += '<div class="d-flex align-items-center">';
	modal_addBannerHTML += '<input id="add_banner_img" name="add_banner_img" type="file" class="form-control">';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '<div class="modal-footer">';
	modal_addBannerHTML += '<button type="submit" class="btn btn-primary ms-auto" >Submit</button>';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '</form>';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '</div>';
	modal_addBannerHTML += '</div>';

	$('#modal_add_banner').html(modal_addBannerHTML);
  	$('#modal_add_bannerGEN').modal('show');

	$("#add_banner_img").on('change', function(){
	  var file = this.files[0];
	  var reader = new FileReader();
	  reader.onloadend = function () {
	      $('img#banner_img').attr('src', reader.result);
	  }
	  if (file) {
	      reader.readAsDataURL(file);
	  } else {
	      return false;
	  }
	});

  	add_banner_validate();
}

function add_banner_validate(){
  $("#add_banner").validate({
      rules: {
          banner_status: {
            required: true,
            number: true
          },
          add_banner_name: {
            required: true
          },
          add_banner_img : {
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

          // ADD BANNER
          submit_add_banner(form, e);
      } 
  });
}

function submit_add_banner(form, e) {
    var inputs = $(form).find(':input');
    var form_data = new FormData();

    form_data.append("cmd"           		, 'add_banner');
    form_data.append("add_banner_status"   	, inputs.filter('#add_banner_status').val());
    form_data.append("add_banner_name"  	, inputs.filter('#add_banner_name').val());
    form_data.append("add_banner_img" 		, inputs.filter('#add_banner_img').prop("files")[0]);

    $.ajax({
        type: "post",
        url: BASE_LANG + "service/banner.php",
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
              // datatable_course.ajax.reload();
              $('#modal_add_bannerGEN').modal('hide');
              show_banner();
              $("#show_banner").html(bannerHTML).fadeIn(500);
          }else{
              alert_center('Process add banner', msg, "error")
          }
        }
    });
}