$(function(){
    var dt_employee = $('#datatable_employee').DataTable({
        responsive: true,
        pageLength: 10,
        ajax: {
            "url" : BASE_LANG + "service/employee.php",
            "type": "POST",
            "data": function( d ){ 
                d.cmd = "employee";
            }
        },
        type: "JSON",
        columns: [
            { data: "username"},
            { data: "full_name"},
            { data: "telephone"},
            { data: "email"},
            { data: "position"},
            //{ data: "is_admin", render: employee_is_admin},
            { data: "emp_id", render: employee_tools}
        ],
        columnDefs: [
            { targets: "_all", defaultContent: "-"},
            { targets: 5, className: "text-center", width: "10%"}
        ]
    });
    
    $('#datatable_employee').on('click', '[name="edit_employee"]', function(e){
        var data = $(e.currentTarget).data();
        console.log(data);
        $('#edit_e_username').val(data.username);
        $('#edit_e_name').val(data.fullName);
        $('#edit_e_phone').val(data.telephone);
        $('#edit_e_email').val(data.email);
        $('#edit_e_pos').val(data.position);
        $("#edit_e_admin option").each(function () {
            if($(this).val() == data.edit_e_admin){
                $(this).attr("selected","selected");
            } else {
                $(this).removeAttr("selected");
            }
        });
        $('#edit_e_id').val(data.empId);
        $('#modal_edit').modal('show');
    });

    // $('#datatable_employee').on('click', '[name="upd_active"]', function(e){
    //     e.preventDefault();
    //     var data = $(e.currentTarget).data();
    //     $('.btn-confirm-upd').data('empId', data.empId);
    //     $('.btn-confirm-upd').data('active', data.active);
    //     $('#modal_active').modal('show');
    // });

    // $('#modal_active').on('click', '.btn-confirm-upd', function(){
    //     $.ajax({
    //         type: "post",
    //         url: BASE_LANG + "service/employee.php",
    //         data: {
    //             "cmd": "update_status",
    //             "emp_id": $(this).data('empId'),
    //             "emp_active": $(this).data('active')
    //         },
    //         dataType: "json",
    //         success: function (res) {
    //             var msg = res['msg'];
    //             if (res['status']) {
    //                 alert_center('Update employee status', msg, "success")
    //                 dt_employee.ajax.reload();
    //             }else{
    //                 alert_center('Update employee status', msg, "error")
    //             }
    //         }
    //     });
    // });

    $('#modal_remove').on('click', '.btn-confirm-del', function(){
        $.ajax({
            type: "post",
            url: BASE_LANG + "service/employee.php",
            data: {
                "cmd": "remove_employee",
                "emp_id": $(this).data('empId')
            },
            dataType: "json",
            success: function (res) {
                var msg = res['msg'];
                if (res['status']) {
                    alert_center('Remove employee', msg, "success")
                    dt_employee.ajax.reload();
                }else{
                    alert_center('Remove employee', msg, "error")
                }
            }
        });
    });
    
    $("#modal_add").on("hidden.bs.modal", function () {
        $('#frm_add_employee')[0].reset();
        $('#frm_add_employee').find('.is-invalid').removeClass("is-invalid");
        $('#frm_add_employee').find('.is-valid').removeClass("is-valid");
    });

    $("#modal_edit").on("hidden.bs.modal", function () {
        $('#frm_edit_employee')[0].reset();
        $('#frm_edit_employee').find('.is-invalid').removeClass("is-invalid");
        $('#frm_edit_employee').find('.is-valid').removeClass("is-valid");
    });

    $('#modal_remove').on('show.bs.modal', function(e) {
        var data = $(e.relatedTarget).data();
        $('.title', this).text(data.userName);
        $('.btn-confirm-del', this).data('empId', data.empId);
    });

    $('#frm_add_employee').validate({
        rules: {
            add_e_username: {
                required: true
            },
            add_e_password: {
                required: true
            },
            add_e_name: {
                required: true
            }
        },
        errorPlacement: function(error,element) {
            return true;
        },
        errorClass: "text-danger",
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error').removeClass('has-success');
            $(element).closest('.form-group').prevObject.addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            $(element).closest('.form-group').prevObject.removeClass('is-invalid').addClass('is-valid');
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            var data = new FormData($(form)[0]);
            data.append("cmd", "add_employee");
            $.ajax({
                type: "post",
                url: BASE_LANG + "service/employee.php",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(res){
                    var status = res['status'];
                    var msg = res['msg'];
                    if (status == true) {
                        $('#modal_add').modal('hide');
                        alert_center('Add employee', msg, "success")
                        dt_employee.ajax.reload();
                    } else {
                        alert_center('Add employee', msg, "error")
                    }
                }
            });
        }
    });

    $('#frm_edit_speaker').validate({
        rules: {
            add_e_username: {
                required: true
            },
            add_e_name: {
                required: true
            }
        },
        errorPlacement: function(error,element) {
            return true;
        },
        errorClass: "text-danger",
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error').removeClass('has-success');
            $(element).closest('.form-group').prevObject.addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            $(element).closest('.form-group').prevObject.removeClass('is-invalid').addClass('is-valid');
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            var data = new FormData($(form)[0]);
            data.append("cmd", "update_employee");
            $.ajax({
                type: "post",
                url: BASE_LANG + "service/employee.php",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(res){
                    var status = res['status'];
                    var msg = res['msg'];
                    if (status == true) {
                        $('#modal_edit').modal('hide');
                        alert_center('Update employee', msg, "success")
                        dt_employee.ajax.reload();
                    } else {
                        alert_center('Update employee', msg, "error")
                    }
                }
            });
        }
    });

    // function employee_is_admin(data, type, row) {
    //     if (data == 'Y')
    //         return '<label class="form-switch"> \
    //                     <input class="form-check-input" name="upd_active" type="checkbox" data-active="' + data + '" data-emp-id="' + row['emp_id'] +'" checked> \
    //                 </label>';
    //     else
    //         return '<label class="form-switch"> \
    //                     <input class="form-check-input" name="upd_active" type="checkbox" data-active="' + data + '" data-emp-id="' + row['emp_id'] +'"> \
    //                 </label>';
    // }

    function employee_tools(data, type, row) {
        var tools = '<button ';
            tools += ' data-emp-id = "' + data + '"';
            tools += ' data-username = "' + row['username'] + '"';
            tools += ' data-full-name = "'  + row['full_name'] + '"';
            tools += ' data-telephone = "' + row['telephone'] + '"';
            tools += ' data-email = "' + row['email'] + '"';
            tools += ' data-position = "' + row['position'] + '"';
            tools += ' name="edit_employee" class="btn btn-outline-warning mx-1"><i class="fas fa-edit"></i></button>';
            tools += '<button name="remove_employee" data-user-name = "' + row['username'];
            tools += '" data-emp-id="' + data + '" class="btn btn-outline-danger mx-1" data-bs-toggle="modal" data-bs-target="#modal_remove">'
            tools += '<i class="far fa-trash-alt"></i></button>';
        return tools
    }
})