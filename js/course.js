$(document).ready(function(){
    datatable_course();

    // ADD COURSE
    $('.add-course').on('click', function(){
        add_course();
    });

});

function datatable_course(){
    datatable_course = $("#datatable_course").DataTable({
        "scrollX": true,
        "pageLength": 25,
        "responsive": true,
        "paging": true,
        "processing": true,
        "ordering": false,
        // "order": [[ 3, "desc" ]],
        "ajax": {
            "url" : BASE_LANG + "service/course.php",
            "type": "POST",
            "data": function( d ){ 
                d.cmd = "course";
            },
            "beforeSend": function(){
            },
            "complete": function(){
            }
        },
        "type":'JSON',
        "columns": [
            { "data": "course_active", render: course_active},
            { "data": "course_no"},
            { "data": "course_name"},
            { "data": "course_datetime"},
            { "data": "course_place"},
            { "data": "course_price"},
            { "data": "course_startdate", render: course_startdate},
            { "data": "course_enddate", render: course_enddate},
            { "data": "course_id", render: tools}
        ],
        "columnDefs": [
            { targets: [0, 1, 8], className: "text-center" },
        ],
        "initComplete": function( settings, start, end, max, total, pre ) {
        }
    });

    function course_active(data, type, row, meta){
        var active = (data == 1) ? '<button class="btn btn-outline-success btn-sm w-100">Active</button>' : '<button class="btn btn-outline-danger btn-sm w-100">Inactive</button>';
        return active;
    }


    function course_startdate(data, type, row, meta){
        return moment(data).format('YYYY-MM-DD');
    }

    function course_enddate(data, type, row, meta){
        return moment(data).format('YYYY-MM-DD');
    }

    function tools(data, type, row, meta){
        var tools = '<button ';
        tools    += ' data-eco-id="'    + data + '"';
        tools    += ' data-eco-active="'+ row['course_active'] + '"';
        tools    += ' data-eco-no="'    + row['course_no'] + '"';
        tools    += ' data-eco-name="'  + row['course_name'] + '"';
        tools    += ' data-eco-date="'  + row['course_datetime'] + '"';
        tools    += ' data-eco-place="' + row['course_place'] + '"';
        tools    += ' data-eco-price="' + row['course_price'] + '"';
        tools    += ' data-eco-start="' + row['course_startdate'] + '"';
        tools    += ' data-eco-end="'   + row['course_enddate'] + '"';
        tools    += 'name="edit_course" class="btn btn-outline-warning mx-1"><i class="fas fa-edit"></i></button>';
        tools    += '<button name="remove_course" data-coname="' + row['course_name'] + '" data-coid="' + data + '" class="btn btn-outline-danger mx-1"><i class="far fa-trash-alt"></i></button>';

        return tools;
    }

    $('#datatable_course tbody').on( 'click', '[name="remove_course"]', function (e) {
        var row = $(this).closest("tr"); 
        var course_id   = row.find('[name="remove_course"]').attr('data-coid');
        var course_name = row.find('[name="remove_course"]').attr('data-coname');

        // MODAL REMOVE COURSE
        var remove_modalText  = 'Do you really want to remove course name <br><b>' + course_name + '</b> ?';
        var modalID           = 'modal_removeCourseGEN';
        var btn_remove_id     = 'submit_remove_course';
        modal_remove(btn_remove_id, modalID, remove_modalText, 'modal_removeCourse');

        $('#' + btn_remove_id).on('click', function(){
          $.ajax({
              type: "post",
              url: BASE_LANG + "service/course.php",
              data: {
                  "cmd": "remove_course",
                  "course_id": course_id
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
                      alert_center('Process remove course', msg, "success")
                      datatable_course.ajax.reload();
                      $('#' + modalID).modal('hide');
                  }else{
                      alert_center('Process remove course', msg, "error")
                  }
              }
          });
        });

    });

    $('#datatable_course tbody').on( 'click', '[name="edit_course"]', function (e) {
        var row = $(this).closest("tr"); 
        var course_id    = row.find('[name="edit_course"]').attr('data-eco-id');        
        var course_active= row.find('[name="edit_course"]').attr('data-eco-active');
        var course_no    = row.find('[name="edit_course"]').attr('data-eco-no');
        var course_name  = row.find('[name="edit_course"]').attr('data-eco-name');
        var course_date  = row.find('[name="edit_course"]').attr('data-eco-date');
        var course_place = row.find('[name="edit_course"]').attr('data-eco-place');
        var course_price = row.find('[name="edit_course"]').attr('data-eco-price');
        var course_start = row.find('[name="edit_course"]').attr('data-eco-start');
        var course_end   = row.find('[name="edit_course"]').attr('data-eco-end');

        edit_course(course_active, course_id, course_no, course_name, course_date, course_place, course_price, course_start, course_end)
    });
      
}

function add_course(){
  var modal_addCourseHTML = '';
  modal_addCourseHTML += '<div class="modal modal-blur fade" id="modal_addcourseGEN" data-bs-backdrop="static" data-bs-keyboard="false">';
  modal_addCourseHTML += '<div class="modal-dialog modal-lg" role="document">';
  modal_addCourseHTML += '<div class="modal-content">';
  modal_addCourseHTML += '<form id="frm_add_course">';
  modal_addCourseHTML += '<div class="modal-status bg-primary"></div>';
  modal_addCourseHTML += '<div class="modal-header">';
  modal_addCourseHTML += '<h5 class="modal-title">Create course</h5>';
  modal_addCourseHTML += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '<div class="modal-body py-3">';

  modal_addCourseHTML += '<div class="row">'; // ROW
  modal_addCourseHTML += '<div class="col-6 my-1">';
  modal_addCourseHTML += '<div class="form-floating">';
  modal_addCourseHTML += '<select id="add_c_active" name="add_c_active" class="form-select">';
  modal_addCourseHTML += '<option value="1">Active</option><option value="0">Inactive</option></select>';
  modal_addCourseHTML += '<label for="add_c_active">COURSE STATUS</label>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '<div class="col-6 my-1">';
  modal_addCourseHTML += '<div class="form-floating">';
  modal_addCourseHTML += '<input id="add_c_no" name="add_c_no" type="tel" class="form-control">';
  modal_addCourseHTML += '<label for="add_c_no">COURSE NO.</label>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '<div class="col-12 my-1">';
  modal_addCourseHTML += '<div class="form-floating">';
  modal_addCourseHTML += '<input id="add_c_name" name="add_c_name" type="text" class="form-control">';
  modal_addCourseHTML += '<label for="add_c_name">COURSE NAME</label>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '<div class="col-12 my-1">';
  modal_addCourseHTML += '<div class="form-floating">';
  modal_addCourseHTML += '<input id="add_c_datetime" name="add_c_datetime" type="text" class="form-control">';
  modal_addCourseHTML += '<label for="add_c_datetime">COURSE DATETIME</label>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '<div class="col-12 my-1">';
  modal_addCourseHTML += '<div class="form-floating">';
  modal_addCourseHTML += '<input id="add_c_place" name="add_c_place" type="text" class="form-control">';
  modal_addCourseHTML += '<label for="add_c_place">COURSE PLACE</label>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '<div class="col-12 my-1">';
  modal_addCourseHTML += '<div class="form-floating">';
  modal_addCourseHTML += '<input id="add_c_price" name="add_c_price" type="text" class="form-control">';
  modal_addCourseHTML += '<label for="add_c_price">COURSE PRICE</label>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '<div class="col-md-6 col-sm-12 my-1 text-center">';
  modal_addCourseHTML += '<label for="add_c_start">START</label>';
  modal_addCourseHTML += '<div class="datepicker-inline mt-2" id="add_c_start"></div>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '<div class="col-md-6 col-sm-12 my-1 text-center">';
  modal_addCourseHTML += '<label for="add_c_end">END</label>';
  modal_addCourseHTML += '<div class="datepicker-inline mt-2" id="add_c_end"></div>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '</div>'; // ROW

  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '<div class="modal-footer">';
  modal_addCourseHTML += '<button type="submit" class="btn btn-primary ms-auto">Submit</button>' 
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '</form>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '</div>';
  modal_addCourseHTML += '</div>';
  $('#modal_addcourse').html(modal_addCourseHTML);
  $('#modal_addcourseGEN').modal('show');

  var start_date = new Litepicker({ 
    element: document.getElementById('add_c_start'),
    buttonText: {
      previousMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="15 6 9 12 15 18" /></svg>',
      nextMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 6 15 12 9 18" /></svg>',
    },
    inlineMode: true,
    startDate: moment()
  });

  var end_date = new Litepicker({ 
    element: document.getElementById('add_c_end'),
    buttonText: {
      previousMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="15 6 9 12 15 18" /></svg>',
      nextMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 6 15 12 9 18" /></svg>',
    },
    inlineMode: true,
    startDate: moment()
  });

  $("#frm_add_course").validate({
      rules: {
          add_c_no: {
              required: true,
              number: true
          },
          add_c_name: {
              required: true
          },
          add_c_datetime: {
              required: true
          },
          add_c_place: {
              required: true
          },
          add_c_price: {
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

          // ADD COURSE
          submit_add_course(form, e, start_date, end_date);
      } 
  });
}

function submit_add_course(form, e, start_date, end_date) {
    var inputs = $(form).find(':input');
    var form_data = new FormData();

    form_data.append("cmd"            , 'add_course');
    form_data.append("add_c_active"   , inputs.filter('#add_c_active').val());
    form_data.append("add_c_no"       , inputs.filter('#add_c_no').val());
    form_data.append("add_c_name"     , inputs.filter('#add_c_name').val());
    form_data.append("add_c_datetime" , inputs.filter('#add_c_datetime').val());
    form_data.append("add_c_place"    , inputs.filter('#add_c_place').val());
    form_data.append("add_c_price"    , inputs.filter('#add_c_price').val());
    form_data.append("add_c_start"    , start_date.getDate().format('YYYY-MM-DD'));
    form_data.append("add_c_end"      , end_date.getDate().format('YYYY-MM-DD'));

    $.ajax({
        type: "post",
        url: BASE_LANG + "service/course.php",
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
              alert_center('Process add course', msg, "success")
              datatable_course.ajax.reload();
              $('#modal_addcourseGEN').modal('hide');
          }else{
              alert_center('Process add course', msg, "error")
          }
        }
    });
}

function edit_course(course_active, course_id, course_no, course_name, course_date, course_place, course_price, course_start, course_end){
  var modal_editCourseHTML = '';
  modal_editCourseHTML += '<div class="modal modal-blur fade" id="modal_editcourseGEN" data-bs-backdrop="static" data-bs-keyboard="false">';
  modal_editCourseHTML += '<div class="modal-dialog modal-lg" role="document">';
  modal_editCourseHTML += '<div class="modal-content">';
  modal_editCourseHTML += '<form id="frm_edit_course">';
  modal_editCourseHTML += '<div class="modal-status bg-warning"></div>';
  modal_editCourseHTML += '<div class="modal-header">';
  modal_editCourseHTML += '<h5 class="modal-title">Edit course</h5>';
  modal_editCourseHTML += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '<div class="modal-body py-3">';

  modal_editCourseHTML += '<div class="row">'; // ROW
  modal_editCourseHTML += '<div class="col-6 my-1">';
  modal_editCourseHTML += '<div class="form-floating">';
  modal_editCourseHTML += '<select id="edit_c_active" name="edit_c_active" class="form-select">';
  modal_editCourseHTML += '<option value="1">Active</option><option value="0">Inactive</option></select>';
  modal_editCourseHTML += '<label for="edit_c_active">COURSE STATUS</label>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '<div class="col-6 my-1">';
  modal_editCourseHTML += '<div class="form-floating">';
  modal_editCourseHTML += '<input id="edit_c_no" name="edit_c_no" disabled value="' + course_no + '" type="tel" class="form-control">';
  modal_editCourseHTML += '<label for="edit_c_no">COURSE NO.</label>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '<div class="col-12 my-1">';
  modal_editCourseHTML += '<div class="form-floating">';
  modal_editCourseHTML += '<input id="edit_c_name" name="edit_c_name" value="' + course_name + '" type="text" class="form-control">';
  modal_editCourseHTML += '<label for="edit_c_name">COURSE NAME</label>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '<div class="col-12 my-1">';
  modal_editCourseHTML += '<div class="form-floating">';
  modal_editCourseHTML += '<input id="edit_c_datetime" name="edit_c_datetime" value="' + course_date + '" type="text" class="form-control">';
  modal_editCourseHTML += '<label for="edit_c_datetime">COURSE DATETIME</label>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '<div class="col-12 my-1">';
  modal_editCourseHTML += '<div class="form-floating">';
  modal_editCourseHTML += '<input id="edit_c_place" name="edit_c_place" value="' + course_place + '" type="text" class="form-control">';
  modal_editCourseHTML += '<label for="edit_c_place">COURSE PLACE</label>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '<div class="col-12 my-1">';
  modal_editCourseHTML += '<div class="form-floating">';
  modal_editCourseHTML += '<input id="edit_c_price" name="edit_c_price" value="' + course_price + '" type="text" class="form-control">';
  modal_editCourseHTML += '<label for="edit_c_price">COURSE PRICE</label>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '<div class="col-md-6 col-sm-12 my-1 text-center">';
  modal_editCourseHTML += '<label for="edit_c_start">START</label>';
  modal_editCourseHTML += '<div class="datepicker-inline mt-2" id="edit_c_start"></div>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '<div class="col-md-6 col-sm-12 my-1 text-center">';
  modal_editCourseHTML += '<label for="edit_c_end">END</label>';
  modal_editCourseHTML += '<div class="datepicker-inline mt-2" id="edit_c_end"></div>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '</div>'; // ROW

  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '<div class="modal-footer">';
  modal_editCourseHTML += '<button type="submit" class="btn btn-primary ms-auto">Submit</button>' 
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '</form>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '</div>';
  modal_editCourseHTML += '</div>';
  $('#modal_editcourse').html(modal_editCourseHTML);
  $('#modal_editcourseGEN').modal('show');

  $('#edit_c_active').val(course_active).trigger('change');

  var edit_start_date = new Litepicker({ 
    element: document.getElementById('edit_c_start'),
    buttonText: {
      previousMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="15 6 9 12 15 18" /></svg>',
      nextMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 6 15 12 9 18" /></svg>',
    },
    inlineMode: true,
    startDate: course_start
  });

  var edit_end_date = new Litepicker({ 
    element: document.getElementById('edit_c_end'),
    buttonText: {
      previousMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="15 6 9 12 15 18" /></svg>',
      nextMonth: '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 6 15 12 9 18" /></svg>',
    },
    inlineMode: true,
    startDate: course_end
  });

  $("#frm_edit_course").validate({
      rules: {
          edit_c_no: {
              required: true,
              number: true
          },
          edit_c_name: {
              required: true
          },
          edit_c_datetime: {
              required: true
          },
          edit_c_place: {
              required: true
          },
          edit_c_price: {
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

          // Edit COURSE
          submit_edit_course(form, e, course_id, edit_start_date, edit_end_date);
      } 
  });
}

function submit_edit_course(form, e, course_id, edit_start_date, edit_end_date) {
    var inputs = $(form).find(':input');
    var form_data = new FormData();

    form_data.append("cmd"             , 'edit_course');
    form_data.append("edit_c_course_id", course_id);
    form_data.append("edit_c_active"   , inputs.filter('#edit_c_active').val());
    // form_data.append("edit_c_no"       , inputs.filter('#edit_c_no').val());
    form_data.append("edit_c_name"     , inputs.filter('#edit_c_name').val());
    form_data.append("edit_c_datetime" , inputs.filter('#edit_c_datetime').val());
    form_data.append("edit_c_place"    , inputs.filter('#edit_c_place').val());
    form_data.append("edit_c_price"    , inputs.filter('#edit_c_price').val());
    form_data.append("edit_c_start"    , edit_start_date.getDate().format('YYYY-MM-DD'));
    form_data.append("edit_c_end"      , edit_end_date.getDate().format('YYYY-MM-DD'));

    $.ajax({
        type: "post",
        url: BASE_LANG + "service/course.php",
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
              alert_center('Process edit course', msg, "success")
              datatable_course.ajax.reload();
              $('#modal_editcourseGEN').modal('hide');
          }else{
              alert_center('Process edit course', msg, "error")
          }
        }
    });
}