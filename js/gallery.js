$(document).ready(function(){
    datatable_gallery();

    // COURSE SELECT
    var course_active   = '<span class="badge bg-success me-1"></span> Active';
    var course_inactive = '<span class="badge bg-danger me-1"></span> Inactive';
    var label_course = ($('#gallery_course option:selected').attr('data-active') == '1') ? course_active : course_inactive;
    $('#g_label_course').html(label_course)
    $('#gallery-course-name').val($('#gallery_course option:selected').attr('data-course-name'));

    $('#gallery_course').on('change', function(){
      var label_course = ($(this).find('option:selected').attr('data-active') == '1') ? course_active : course_inactive;
      $('#g_label_course').html(label_course);
      $('#gallery-course-name').val($(this).find('option:selected').attr('data-course-name'));
      datatable_gallery.ajax.reload();
    });

    $('#add_gallery').on('click', function(){
        add_gallery()
    });

});


function datatable_gallery(){
    datatable_gallery = $("#dtb_gallery").DataTable({
        "scrollX": true,
        "pageLength": 10,
        "responsive": true,
        "paging": true,
        "processing": true,
        "order": [[ 4, "desc" ]],
        "ajax": {
            "url" : BASE_LANG + "service/gallery.php",
            "type": "POST",
            "data": function( d ){ 
                d.cmd     = "gallery";
                d.course  = $('#gallery_course').val();
            },
            "beforeSend": function(){
            },
            "complete": function(){
            }
        },
        "type":'JSON',
        "columns": [
            // { "data": "cus_id"},
            { "data": "gallery_img", render: image},
            { "data": "gallery_name"},
            { "data": "gallery_alt" },
            { "data": "gallery_active", render : status},
            { "data": "createdatetime", render: datetime},
            { "data": "gallery_id", render: tools}
        ],
        "columnDefs": [
          { targets: [0], className: "text-center", width: "10%" },
          { targets: [3, 4, 5], className: "text-center", width: "10%" },
          { targets: [1, 2], width: "30%" }
        ],
        "initComplete": function( settings, start, end, max, total, pre ) {
        }
    });

    function status(data, type, row, meta){
        var checked = (data == '1') ? 'checked' : '';
        var active_gHTML = '';
        active_gHTML += '<div class="ms-auto">';
        active_gHTML += '<label class="form-check form-switch form-check-inline m-auto mt-1">';
        active_gHTML += '<input data-ac-id="' + row['gallery_id'] + '" name="active_gallery" class="cursor-pointer form-check-input" type="checkbox" ' + checked + '>';
        active_gHTML += '</label>';
        active_gHTML += '</div>';
        return active_gHTML;
    }

    function image(data, type, row, meta){
      var imagesUrl = BASE_URL + 'images/gallery/' + data;
      var images = '';
      images += '<div class="col-auto">';
      images += ' <img name="gallery_img" src="' + imagesUrl + '" ';
      images += ' data-img="'  + imagesUrl + '"';
      images += ' data-name="' + row['gallery_name'] + '"';
      images += ' data-alt="'  + row['gallery_alt'] + '"';
      images += ' class="rounded cursor-pointer" width="100%" >'
      images += '</div>';

      return images;
    }

    function datetime(data, type, row, meta){
        return moment(data).format('YYYY-MM-DD HH:mm:ss');
    }

    function tools(data, type, row) {
        var tools = '<button ';
            tools += ' data-e-id = "'     + data + '"';
            tools += ' data-e-active = "' + row['gallery_active'] + '"';
            tools += ' data-e-image = "'  + row['gallery_img'] + '"';
            tools += ' data-e-name = "'   + row['gallery_name'] + '"';
            tools += ' data-e-alt = "'    + row['gallery_alt'] + '"';
            tools += ' name="edit_gallery" class="btn btn-outline-warning mx-1"><i class="fas fa-edit"></i></button>';
            tools += '<button name="remove_gallery" data-rm-name = "' + row['gallery_name'] ;
            tools += '" data-rm-id="' + data + '" class="btn btn-outline-danger mx-1">'
            tools += '<i class="far fa-trash-alt"></i></button>';
        return tools;
    }

    $('#dtb_gallery tbody').on( 'click', '[name="gallery_img"]', function (e) {
        var row = $(this).closest("tr"); 
        var image_url = row.find('[name="gallery_img"]').attr('data-img');
        var gallery_name = row.find('[name="gallery_img"]').attr('data-name');
        var gallery_alt  = row.find('[name="gallery_img"]').attr('data-alt');

        var modal_imgHTML = '';
        modal_imgHTML += '<div class="modal modal-blur fade" id="modal_gallery_imageGEN">';
        modal_imgHTML += '<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">';
        modal_imgHTML += '<div class="modal-content">';
        modal_imgHTML += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        modal_imgHTML += '<div class="modal-status bg-success"></div>';
        modal_imgHTML += '<div class="modal-body text-center py-4">';
        modal_imgHTML += '<h3>' + gallery_name + '</h3>';
        modal_imgHTML += '<div class="text-muted mb-3">' + gallery_alt + ' </div>';
        modal_imgHTML += '<img src="' + image_url + '" style="width: 100%;">';
        modal_imgHTML += '</div>';
        modal_imgHTML += '<div class="modal-footer">';
        modal_imgHTML += '</div>';
        modal_imgHTML += '</div>';
        modal_imgHTML += '</div>';
        modal_imgHTML += '</div>';
        $('#modal_gallery_image').html(modal_imgHTML);
        $('#modal_gallery_imageGEN').modal('show');
    });

    $('#dtb_gallery tbody').on( 'click', '[name="remove_gallery"]', function (e) {
        var row = $(this).closest("tr"); 
        var gallery_id   = row.find('[name="remove_gallery"]').attr('data-rm-id');
        var gallery_name = row.find('[name="remove_gallery"]').attr('data-rm-name');

        // MODAL REMOVE COURSE
        var remove_modalText  = 'Do you really want to remove gallery name <br><b>' + gallery_name + '</b> ?';
        var modalID           = 'modal_removeGalleryGEN';
        var btn_remove_id     = 'submit_remove_gallery';
        modal_remove(btn_remove_id, modalID, remove_modalText, 'modal_removeGallery');

        $('#' + btn_remove_id).on('click', function(){
          $.ajax({
              type: "post",
              url: BASE_LANG + "service/gallery.php",
              data: {
                  "cmd": "remove_gallery",
                  "gallery_id": gallery_id
              },
              dataType: "json",
              beforeSend: function(){
                $(':button[name="remove_course"]').prop('disabled', true);
              },
              complete: function(){
                $(':button[name="remove_course"]').prop('disabled', false);
              },
              success: function(res) {
                  var status = res['status'];
                  var msg = res['msg'];
                  if (status == true) {
                      alert_center('Process remove gallery', msg, "success")
                      datatable_gallery.ajax.reload();
                      $('#' + modalID).modal('hide');
                  }else{
                      alert_center('Process remove gallery', msg, "error")
                  }
              }
          });
        });
    });

    $('#dtb_gallery tbody').on( 'click', '[name="active_gallery"]', function (e) {
        var row = $(this).closest("tr"); 
        var gallery_ac_id   = row.find('[name="active_gallery"]').attr('data-ac-id');
        var gallery_active  = (this.checked == true) ? '1' : '0';

        $.ajax({
            type: "post",
            url: BASE_LANG + "service/gallery.php",
            data: {
                "cmd": "active_gallery",
                "gallery_ac_id": gallery_ac_id,
                "gallery_active": gallery_active
            },
            dataType: "json",
            beforeSend: function(){
              $(':button[name="active_gallery"]').prop('disabled', true);
            },
            complete: function(){
              $(':button[name="active_gallery"]').prop('disabled', false);
            },
            success: function(res) {
                var status = res['status'];
                var msg = res['msg'];
                if (status == true) {
                    alert_center('Process update gallery', msg, "success")
                    datatable_gallery.ajax.reload();
                    $('#' + modalID).modal('hide');
                }else{
                    alert_center('Process update gallery', msg, "error")
                }
            }
        });
    });

    $('#dtb_gallery tbody').on( 'click', '[name="edit_gallery"]', function (e) {
        var row = $(this).closest("tr"); 
        var gallery_e_id      = row.find('[name="edit_gallery"]').attr('data-e-id');
        var gallery_e_active  = row.find('[name="edit_gallery"]').attr('data-e-active');
        var gallery_e_image   = row.find('[name="edit_gallery"]').attr('data-e-image');
        var gallery_e_name    = row.find('[name="edit_gallery"]').attr('data-e-name');
        var gallery_e_alt     = row.find('[name="edit_gallery"]').attr('data-e-alt');

        edit_gallery(gallery_e_id, gallery_e_active, gallery_e_image, gallery_e_name, gallery_e_alt);
    });
}

function add_gallery(){

  var modal_addGalleryHTML = '';
  modal_addGalleryHTML += '<div class="modal modal-blur fade" id="modal_add_galleryGEN" data-bs-backdrop="static" data-bs-keyboard="false">';
  modal_addGalleryHTML += '<div class="modal-dialog modal-lg" role="document">';
  modal_addGalleryHTML += '<div class="modal-content">';
  modal_addGalleryHTML += '<div class="modal-header">';
  modal_addGalleryHTML += '<h5 class="modal-title">New image in course <b>' + $('#gallery-course-name').val() + '</b></h5>';
  modal_addGalleryHTML += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '<form id="from_add_gallery">';
  modal_addGalleryHTML += '<div class="modal-body">';
  modal_addGalleryHTML += '<div class="row">';
  modal_addGalleryHTML += '<div class="col-md-4 col-sm-12">';
  modal_addGalleryHTML += '<div class="mb-3">';
  modal_addGalleryHTML += '<div class="form-floating">';
  modal_addGalleryHTML += '<select class="form-select" id="add_gallery_status" name="add_gallery_status">';
  modal_addGalleryHTML += '<option value="1">Active</option><option value="0">Inactive</option>';
  modal_addGalleryHTML += '</select>';
  modal_addGalleryHTML += '<label for="add_gallery_status">Select</label>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '<div class="col-md-4 col-sm-12">';
  modal_addGalleryHTML += '<div class="mb-3">';
  modal_addGalleryHTML += '<div class="form-floating mb-3">';
  modal_addGalleryHTML += '<input id="add_gallery_name" name="add_gallery_name"  type="text" class="form-control" placeholder="n">';
  modal_addGalleryHTML += '<label for="add_gallery_name">Image name</label>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '<div class="col-md-4 col-sm-12">';
  modal_addGalleryHTML += '<div class="mb-3">';
  modal_addGalleryHTML += '<div class="form-floating mb-3">';
  modal_addGalleryHTML += '<input id="add_gallery_alt" name="add_gallery_alt"  type="text" class="form-control" placeholder="n">';
  modal_addGalleryHTML += '<label for="add_gallery_alt">Image alt</label>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '<div class="col-12">';
  modal_addGalleryHTML += '<div class="card card-sm">';
  modal_addGalleryHTML += '<a class="d-block" target="_blank">';
  modal_addGalleryHTML += '<img id="show_gallery_img" src="' + BASE_URL + 'images/no-image.jpg" class="card-img-top" style="height: 25rem;object-fit: cover;">';
  modal_addGalleryHTML += '</a>';
  modal_addGalleryHTML += '<div class="card-body">';
  modal_addGalleryHTML += '<div class="d-flex align-items-center">';
  modal_addGalleryHTML += '<input id="add_gallery_img" name="add_gallery_img" type="file" class="form-control">';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '<div class="modal-footer">';
  modal_addGalleryHTML += '<button type="submit" class="btn btn-primary ms-auto" >Submit</button>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '</form>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '</div>';
  modal_addGalleryHTML += '</div>';

  $('#modal_add_gallery').html(modal_addGalleryHTML);
  $('#modal_add_galleryGEN').modal('show');

  $("#add_gallery_img").on('change', function(){
    var file = this.files[0];
    var reader = new FileReader();
    reader.onloadend = function () {
        $('img#show_gallery_img').attr('src', reader.result);
    }
    if (file) {
        reader.readAsDataURL(file);
    } else {
        return false;
    }
  });

    add_gallery_validate();
}

function add_gallery_validate(){
  $("#from_add_gallery").validate({
      rules: {
          add_gallery_status: {
            required: true
          },
          add_gallery_name: {
            required: true
          },
          add_gallery_alt : {
            required: true
          },
          add_gallery_img : {
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
          submit_add_gallery(form, e);
      } 
  });
}

function submit_add_gallery(form, e) {
    var inputs = $(form).find(':input');
    var form_data = new FormData();

    form_data.append("cmd"                , 'add_gallery');
    form_data.append("add_gallery_cid"    , $('#gallery_course').val());
    form_data.append("add_gallery_status" , inputs.filter('#add_gallery_status').val());
    form_data.append("add_gallery_name"   , inputs.filter('#add_gallery_name').val());
    form_data.append("add_gallery_alt"    , inputs.filter('#add_gallery_alt').val());
    form_data.append("add_gallery_img"    , inputs.filter('#add_gallery_img').prop("files")[0]);

    $.ajax({
        type: "post",
        url: BASE_LANG + "service/gallery.php",
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
              alert_center('Process add image', msg, "success")
              datatable_gallery.ajax.reload();
              $('#modal_add_galleryGEN').modal('hide');
          }else{
              alert_center('Process add image', msg, "error")
          }
        }
    });
}

function edit_gallery(gallery_e_id, gallery_e_active, gallery_e_image, gallery_e_name, gallery_e_alt){
  var url_gallery_e_image = BASE_URL + 'images/gallery/' + gallery_e_image;

  var modal_editGalleryHTML = '';
  modal_editGalleryHTML += '<div class="modal modal-blur fade" id="modal_edit_galleryGEN" data-bs-backdrop="static" data-bs-keyboard="false">';
  modal_editGalleryHTML += '<div class="modal-dialog modal-lg" role="document">';
  modal_editGalleryHTML += '<div class="modal-content">';
  modal_editGalleryHTML += '<div class="modal-header">';
  modal_editGalleryHTML += '<h5 class="modal-title">Edit image in course <b>' + $('#gallery-course-name').val() + '</b></h5>';
  modal_editGalleryHTML += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '<form id="from_edit_gallery">';
  modal_editGalleryHTML += '<div class="modal-body">';
  modal_editGalleryHTML += '<div class="row">';
  modal_editGalleryHTML += '<div class="col-md-4 col-sm-12">';
  modal_editGalleryHTML += '<div class="mb-3">';
  modal_editGalleryHTML += '<div class="form-floating">';
  modal_editGalleryHTML += '<select class="form-select" id="edit_gallery_status" name="edit_gallery_status">';
  modal_editGalleryHTML += '<option value="1">Active</option><option value="0">Inactive</option>';
  modal_editGalleryHTML += '</select>';
  modal_editGalleryHTML += '<label for="edit_gallery_status">Select</label>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '<div class="col-md-4 col-sm-12">';
  modal_editGalleryHTML += '<div class="mb-3">';
  modal_editGalleryHTML += '<div class="form-floating mb-3">';
  modal_editGalleryHTML += '<input id="edit_gallery_name" name="edit_gallery_name" value="' + gallery_e_name + '" type="text" class="form-control" placeholder="n">';
  modal_editGalleryHTML += '<label for="edit_gallery_name">Image name</label>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '<div class="col-md-4 col-sm-12">';
  modal_editGalleryHTML += '<div class="mb-3">';
  modal_editGalleryHTML += '<div class="form-floating mb-3">';
  modal_editGalleryHTML += '<input id="edit_gallery_alt" name="edit_gallery_alt" value="' + gallery_e_alt + '" type="text" class="form-control" placeholder="n">';
  modal_editGalleryHTML += '<label for="edit_gallery_alt">Image alt</label>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '<div class="col-12">';
  modal_editGalleryHTML += '<div class="card card-sm">';
  modal_editGalleryHTML += '<a href="' + url_gallery_e_image + '" class="d-block" target="_blank">';
  modal_editGalleryHTML += '<img id="show_edit_gallery_img" src="' + url_gallery_e_image + '" class="card-img-top" style="height: 25rem;object-fit: cover;">';
  modal_editGalleryHTML += '</a>';
  modal_editGalleryHTML += '<div class="card-body">';
  modal_editGalleryHTML += '<div class="d-flex align-items-center">';
  modal_editGalleryHTML += '<input id="edit_gallery_img" name="edit_gallery_img" type="file" class="form-control">';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '<div class="modal-footer">';
  modal_editGalleryHTML += '<button type="submit" class="btn btn-primary ms-auto" >Submit</button>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '</form>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '</div>';
  modal_editGalleryHTML += '</div>';

  $('#modal_edit_gallery').html(modal_editGalleryHTML);
  $('#modal_edit_galleryGEN').modal('show');

  $('#edit_gallery_status').val(gallery_e_active).trigger('change');

  $("#edit_gallery_img").on('change', function(){
    var file = this.files[0];
    var reader = new FileReader();
    reader.onloadend = function () {
        $('img#show_edit_gallery_img').attr('src', reader.result);
    }
    if (file) {
        reader.readAsDataURL(file);
    } else {
        return false;
    }
  });

  $("#from_edit_gallery").validate({
      rules: {
          edit_gallery_status: {
              required: true
          },
          edit_gallery_name: {
              required: true
          },
          edit_gallery_alt: {
              required: true
          },
          edit_gallery_img: {
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

          submit_edit_gallery(form, e, gallery_e_id);
      } 
  });
}

function submit_edit_gallery(form, e, gallery_e_id) {
    var inputs = $(form).find(':input');
    var form_data = new FormData();

    form_data.append("cmd"                  , 'edit_gallery');
    form_data.append("gallery_e_id"         , gallery_e_id);
    form_data.append("edit_gallery_status"  , inputs.filter('#edit_gallery_status').val());
    form_data.append("edit_gallery_name"    , inputs.filter('#edit_gallery_name').val());
    form_data.append("edit_gallery_alt"     , inputs.filter('#edit_gallery_alt').val());
    form_data.append("edit_gallery_img"     , inputs.filter('#edit_gallery_img').prop("files")[0]);

    $.ajax({
        type: "post",
        url: BASE_LANG + "service/gallery.php",
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
              alert_center('Process edit image', msg, "success")
              datatable_gallery.ajax.reload();
              $('#modal_edit_galleryGEN').modal('hide');
          }else{
              alert_center('Process edit image', msg, "error")
          }
        }
    });
}