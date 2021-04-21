$(document).ready(function(){
    datatable_register();

    // COURSE SELECT
    var course_active   = '<span class="badge bg-success me-1"></span> Active';
    var course_inactive = '<span class="badge bg-danger me-1"></span> Inactive';
    var label_course = ($('#select_course option:selected').attr('data-active') == '1') ? course_active : course_inactive;
    $('#label_course').html(label_course)
    $('#select_course').on('change', function(){

      var label_course = ($(this).find('option:selected').attr('data-active') == '1') ? course_active : course_inactive;
      $('#label_course').html(label_course)
      datatable_register.ajax.reload();
    });

});

function datatable_register(){
    datatable_register = $("#datatable_register").DataTable({
        "scrollX": true,
        "pageLength": 10,
        "responsive": true,
        "paging": true,
        "processing": true,
        "order": [[ 18, "desc" ]],
        "ajax": {
            "url" : BASE_LANG + "service/course_register.php",
            "type": "POST",
            "data": function( d ){ 
                d.cmd     = "course_register";
                d.course  = $('#select_course').val();
            },
            "beforeSend": function(){
            },
            "complete": function(){
            }
        },
        "type":'JSON',
        "columns": [
            // { "data": "cus_id"},
            { "data": "status", render: status},
            { "data": "course_name"},
            { "data": "customer_fullname" },
            { "data": "customer_phone" },
            { "data": "customer_facebook" },
            { "data": "customer_line" },
            { "data": "customer_email" },
            { "data": "customer_company" },
            { "data": "customer_position" },
            { "data": "customer_idcard" },
            { "data": "customer_image", render: image},
            { "data": "course_name_studied" },
            { "data": "course_expectation" },
            { "data": "coordinator_name" },
            { "data": "coordinator_phone" },
            { "data": "coordinator_adviser" },
            { "data": "allergic_food" },
            { "data": "shirt_id", render: shirt},
            { "data": "createdatetime", render: datetime}
        ],
        "columnDefs": [
            { targets: [0, 1, 10], className: "text-center" },
        ],
        "initComplete": function( settings, start, end, max, total, pre ) {
        }
    });

    function status(data, type, row, meta){
        var status = '';
        if (data == 'Pending') {
          status = '<button class="btn btn-outline-warning w-100">' + data + '</button>';
        }else if (data == 'Approve'){
          status = '<button class="btn btn-outline-success w-100">' + data + '</button>';
        }else{
          status = '<button class="btn btn-outline-danger w-100">' + data + '</button>';
        }

        return '<span data-cusid="' + row['cus_id'] + '" data-status="' + data + '" name="change_status">' + status + '</span>';
    }

    function shirt(data, type, row, meta){
        return '<i class="fas fa-tshirt"></i> ' +
               '<button name="shirt" ' +
               'data-s-fullname="'  + row['customer_fullname'] + '" ' +
               'data-s-gender="'    + row['shirt_gender'] + '" ' +
               'data-s-size="'      + row['shirt_size'] + '" ' +
               'data-s-width="'     + row['shirt_width'] + '" ' +
               'data-s-height="'    + row['shirt_height'] + '" ' +
               'class="btn btn-outline-dark btn-sm w-50">' + row['shirt_size'] + '</button>';
    }

    function image(data, type, row, meta){
      var imagesUrl = BASE_URL + 'images/' + row['course_name'] + '/customer/' + data;
      var images = '';
      images += '<div class="col-auto">';
      images += ' <img name="cus_img" src="' + imagesUrl + '" ';
      images += ' data-img="'         + imagesUrl + '"';
      images += ' data-cusname="'     + row['customer_fullname'] + '"';
      images += ' data-cuscompany="'  + row['customer_company'] + '"';
      images += ' data-cusposition="' + row['customer_position'] + '"';
      images += ' class="rounded cursor-pointer" width="40" height="40">'
      images += '</div>';

      return images;
    }

    function datetime(data, type, row, meta){
        return moment(data).format('YYYY-MM-DD HH:mm:ss');
    }

    // CLICK IMG
    $('#datatable_register tbody').on( 'click', '[name="cus_img"]', function (e) {
        var row = $(this).closest("tr"); 
        var image_url = row.find('[name="cus_img"]').attr('data-img');
        var full_name = row.find('[name="cus_img"]').attr('data-cusname');
        var company   = row.find('[name="cus_img"]').attr('data-cuscompany');
        var position  = row.find('[name="cus_img"]').attr('data-cusposition');

        var modal_imgHTML = '';
        modal_imgHTML += '<div class="modal modal-blur fade" id="modal_imageGEN">';
        modal_imgHTML += '<div class="modal-dialog modal-lg" role="document">';
        modal_imgHTML += '<div class="modal-content">';
        modal_imgHTML += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        modal_imgHTML += '<div class="modal-status bg-success"></div>';
        modal_imgHTML += '<div class="modal-body text-center py-4">';
        modal_imgHTML += '<h3>' + full_name + '</h3>';
        modal_imgHTML += '<div class="text-muted mb-3">' + company + ' (' + position + ') </div>';
        modal_imgHTML += '<img src="' + image_url + '" style="max-width: 400px;">';
        modal_imgHTML += '</div>';
        modal_imgHTML += '<div class="modal-footer">';
        modal_imgHTML += '</div>';
        modal_imgHTML += '</div>';
        modal_imgHTML += '</div>';
        modal_imgHTML += '</div>';
        $('#modal_image').html(modal_imgHTML);
        $('#modal_imageGEN').modal('show');
    });

    // CLICK STATUS
    $('#datatable_register tbody').on( 'click', '[name="change_status"]', function (e) {
        var row = $(this).closest("tr"); 
        var customer_id = row.find('[name="change_status"]').attr('data-cusid');
        var c_status    = row.find('[name="change_status"]').attr('data-status');


        var btn_status = '';
        var curent_status = '';
        if (c_status == 'Pending') {
          btn_status += '<button class="confirm_status btn btn-outline-success w-100">Approve</button>';
          btn_status += '<button class="confirm_status btn btn-outline-danger w-100 mt-2">Disapproval</button>';
          curent_status = '<span class="badge bg-warning me-1"></span>' + c_status;
        }else if (c_status == 'Approve'){
          btn_status = '<button class="confirm_status btn btn-outline-danger w-100">Disapproval</button>';
          curent_status = '<span class="badge bg-success me-1"></span>' + c_status;
        }else{
          btn_status = '<button class="confirm_status btn btn-outline-success w-100">Approve</button>';
          curent_status = '<span class="badge bg-danger me-1"></span>' + c_status;
        }

        var modal_statusHTML = '';
        modal_statusHTML += '<div class="modal modal-blur fade" id="modal_statusGEN">';
        modal_statusHTML += '<div class="modal-dialog modal-sm" role="document">';
        modal_statusHTML += '<div class="modal-content">';
        modal_statusHTML += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        modal_statusHTML += '<div class="modal-status bg-success"></div>';
        modal_statusHTML += '<div class="modal-body text-center py-4">';
        modal_statusHTML += '<h3>Change status customer</h3>';
        modal_statusHTML += '<div class="text-muted mb-3">Curent status : ' + curent_status + '</div>';
        modal_statusHTML += btn_status;
        // modal_statusHTML += '<img src="' + image_url + '" style="max-width: 400px;">';
        modal_statusHTML += '</div>';
        modal_statusHTML += '<div class="modal-footer">';
        modal_statusHTML += '</div>';
        modal_statusHTML += '</div>';
        modal_statusHTML += '</div>';
        modal_statusHTML += '</div>';
        $('#modal_status').html(modal_statusHTML);
        $('#modal_statusGEN').modal('show');

        $('.confirm_status').on('click', function(){
          $.ajax({
              type: "post",
              url: BASE_LANG + "service/course_register.php",
              data: {
                  "cmd": "change_status",
                  "customer_id": customer_id,
                  "status": this.textContent
              },
              dataType: "json",
              beforeSend: function(){
                  $(':button.confirm_status').prop('disabled', true);
              },
              complete: function(){
                  $(':button.confirm_status').prop('disabled', false);
              },
              success: function(res) {
                  var status = res['status'];
                  var msg = res['msg'];
                  if (status == true) {
                      alert_center('Process change status', msg, "success")
                      datatable_register.ajax.reload();
                      $('#modal_statusGEN').modal('hide');
                  }else{
                      alert_center('Process change status', msg, "error")
                  }
              }
          });
        });
    });

    // CLICK IMG
    $('#datatable_register tbody').on( 'click', '[name="shirt"]', function (e) {
        var row = $(this).closest("tr"); 
        var shirt_id  = row.find('[name="shirt"]').attr('data-shirtid');
        var full_name = row.find('[name="shirt"]').attr('data-s-fullname');
        var gender    = row.find('[name="shirt"]').attr('data-s-gender');
        var size      = row.find('[name="shirt"]').attr('data-s-size');
        var width     = row.find('[name="shirt"]').attr('data-s-width');
        var height    = row.find('[name="shirt"]').attr('data-s-height');

        var modal_shirtHTML = '';
        modal_shirtHTML += '<div class="modal modal-blur fade" id="modal_shirtGEN">';
        modal_shirtHTML += '<div class="modal-dialog modal-lg" role="document">';
        modal_shirtHTML += '<div class="modal-content">';
        modal_shirtHTML += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        modal_shirtHTML += '<div class="modal-status bg-success"></div>';
        modal_shirtHTML += '<div class="modal-body text-center py-4">';
        modal_shirtHTML += '<h3>Shirt Size</h3>';
        modal_shirtHTML += '<div class="text-muted mb-3">' + full_name + '</div>';
        modal_shirtHTML += '<table class="table table-vcenter card-table">';
        modal_shirtHTML += '<thead><tr><th>Gender</th><th>Size</th><th>Width</th><th>Height</th></tr></thead>';
        modal_shirtHTML += '<tbody><tr><td>' + gender + '</td><td>' + size + '</td><td>' + width + '</td><td>' + height + '</td></tr></tbody>';
        modal_shirtHTML += '</table>';
        modal_shirtHTML += '</div>';
        modal_shirtHTML += '<div class="modal-footer">';
        modal_shirtHTML += '</div>';
        modal_shirtHTML += '</div>';
        modal_shirtHTML += '</div>';
        modal_shirtHTML += '</div>';
        $('#modal_shirt').html(modal_shirtHTML);
        $('#modal_shirtGEN').modal('show');
    });

}

