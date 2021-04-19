$(document).ready(function(){
    datatable_course();

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
        var tools = '<button class="btn btn-outline-warning mx-1"><i class="fas fa-edit"></i></button>';
        tools    += '<button class="btn btn-outline-danger mx-1"><i class="far fa-trash-alt"></i></button>';

        return tools;
    }

    // CLICK STATUS
    // $('#datatable_register tbody').on( 'click', '[name="change_status"]', function (e) {
    //     var row = $(this).closest("tr"); 
    //     var customer_id = row.find('[name="change_status"]').attr('data-cusid');
    //     var c_status    = row.find('[name="change_status"]').attr('data-status');


    //     var btn_status = '';
    //     var curent_status = '';
    //     if (c_status == 'Pending') {
    //       btn_status += '<button class="confirm_status btn btn-outline-success w-100">Approve</button>';
    //       btn_status += '<button class="confirm_status btn btn-outline-danger w-100 mt-2">Disapproval</button>';
    //       curent_status = '<span class="badge bg-warning me-1"></span>' + c_status;
    //     }else if (c_status == 'Approve'){
    //       btn_status = '<button class="confirm_status btn btn-outline-danger w-100">Disapproval</button>';
    //       curent_status = '<span class="badge bg-success me-1"></span>' + c_status;
    //     }else{
    //       btn_status = '<button class="confirm_status btn btn-outline-success w-100">Approve</button>';
    //       curent_status = '<span class="badge bg-danger me-1"></span>' + c_status;
    //     }

    //     var modal_statusHTML = '';
    //     modal_statusHTML += '<div class="modal modal-blur fade" id="modal_statusGEN">';
    //     modal_statusHTML += '<div class="modal-dialog modal-sm" role="document">';
    //     modal_statusHTML += '<div class="modal-content">';
    //     modal_statusHTML += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    //     modal_statusHTML += '<div class="modal-status bg-success"></div>';
    //     modal_statusHTML += '<div class="modal-body text-center py-4">';
    //     modal_statusHTML += '<h3>Change status customer</h3>';
    //     modal_statusHTML += '<div class="text-muted mb-3">Curent status : ' + curent_status + '</div>';
    //     modal_statusHTML += btn_status;
    //     // modal_statusHTML += '<img src="' + image_url + '" style="max-width: 400px;">';
    //     modal_statusHTML += '</div>';
    //     modal_statusHTML += '<div class="modal-footer">';
    //     modal_statusHTML += '</div>';
    //     modal_statusHTML += '</div>';
    //     modal_statusHTML += '</div>';
    //     modal_statusHTML += '</div>';
    //     $('#modal_status').html(modal_statusHTML);
    //     $('#modal_statusGEN').modal('show');

    //     $('.confirm_status').on('click', function(){
    //       $.ajax({
    //           type: "post",
    //           url: BASE_LANG + "service/course_register.php",
    //           data: {
    //               "cmd": "change_status",
    //               "customer_id": customer_id,
    //               "status": this.textContent
    //           },
    //           dataType: "json",
    //           beforeSend: function(){
    //               $(':button.confirm_status').prop('disabled', true);
    //           },
    //           complete: function(){
    //               $(':button.confirm_status').prop('disabled', false);
    //           },
    //           success: function(res) {
    //               var status = res['status'];
    //               var msg = res['msg'];
    //               if (status == true) {
    //                   alert_center('Process change status', msg, "success")
    //                   datatable_register.ajax.reload();
    //                   $('#modal_statusGEN').modal('hide');
    //               }else{
    //                   alert_center('Process change status', msg, "error")
    //               }
    //           }
    //       });
    //     });
    // });

}