$(function(){
    var dt_speaker = $('#datatable_employee').DataTable({
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
            { data: "is_admin", render: employee_is_admin},
            { data: "emp_id", render: employee_tools}
        ],
        columnDefs: [
            { targets: "_all", defaultContent: "-"}
        ]
    });
    
    $('#datatable_speaker').on('click', '[name="edit_speaker"]', function(e){
        var data = $(e.currentTarget).data();
        $('#edit_s_name').val(data.name);
        $('#edit_s_lname').val(data.surname);
        $('#edit_s_email').val(data.email);
        $('#edit_s_comp').val(data.company);
        $('#edit_s_pos').val(data.position);
        if(data.image !== null && data.image !== '' && data.image !== 'null') {
            $('#speaker_edit_image').attr('src', '../images/speaker/' + data.image);
        }
        $('#edit_s_id').val(data.id);
        $('#modal_edit').modal('show');
    });

    $('#datatable_speaker').on('click', '[name="upd_active"]', function(e){
        e.preventDefault();
        var data = $(e.currentTarget).data();
        $('.btn-confirm-upd').data('speakerId', data.speakerId);
        $('.btn-confirm-upd').data('active', data.active);
        $('#modal_active').modal('show');
    });

    $('#modal_active').on('click', '.btn-confirm-upd', function(){
        $.ajax({
            type: "post",
            url: BASE_LANG + "service/speaker.php",
            data: {
                "cmd": "update_active",
                "speaker_id": $(this).data('speakerId'),
                "speaker_active": $(this).data('active')
            },
            dataType: "json",
            success: function (res) {
                var msg = res['msg'];
                if (res['status']) {
                    alert_center('Update speaker active', msg, "success")
                    dt_speaker.ajax.reload();
                }else{
                    alert_center('Update speaker active', msg, "error")
                }
            }
        });
    });

    $('#modal_remove').on('click', '.btn-confirm-del', function(){
        $.ajax({
            type: "post",
            url: BASE_LANG + "service/speaker.php",
            data: {
                "cmd": "remove_speaker",
                "speaker_id": $(this).data('speakerId')
            },
            dataType: "json",
            success: function (res) {
                var msg = res['msg'];
                if (res['status']) {
                    alert_center('Remove speaker', msg, "success")
                    dt_speaker.ajax.reload();
                }else{
                    alert_center('Remove speaker', msg, "error")
                }
            }
        });
    });
    
    $("#modal_add").on("hidden.bs.modal", function () {
        $('#frm_add_speaker')[0].reset();
        $('#speaker_image').attr('src', '../images/no-image.jpg');
        $('#frm_add_speaker').find('.is-invalid').removeClass("is-invalid");
        $('#frm_add_speaker').find('.is-valid').removeClass("is-valid");
        $('#frm_add_speaker').find('label.text-danger').remove();
    });

    $("#modal_edit").on("hidden.bs.modal", function () {
        $('#frm_edit_speaker')[0].reset();
        $('#frm_edit_speaker').find('.is-invalid').removeClass("is-invalid");
        $('#frm_edit_speaker').find('.is-valid').removeClass("is-valid");
        $('#frm_edit_speaker').find('label.text-danger').remove();
    });

    $('#modal_remove').on('show.bs.modal', function(e) {
        var data = $(e.relatedTarget).data();
        $('.title', this).text(data.name + ' ' + data.surname);
        $('.btn-confirm-del', this).data('speakerId', data.speakerId);
    });

    var frm_add_validator = $('#frm_add_speaker').validate({
        rules: {
            add_s_name: {
                required: true
            },
            add_s_lname: {
                required: true
            },
            add_s_email: {
                required: true,
                email: true
            },
            add_s_comp: {
                required: true
            },
            add_s_pos: {
                required: true
            },
            add_s_img: {
                required: true,
                accept: "image/*",
                maxImageWH: 300
            }
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
            data.append("cmd", "add_speaker");
            $.ajax({
                type: "post",
                url: BASE_LANG + "service/speaker.php",
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
                        alert_center('Add speaker', msg, "success")
                        dt_speaker.ajax.reload();
                    } else {
                        alert_center('Add speaker', msg, "error")
                    }
                }
            });
        }
    });

    var frm_edit_validator = $('#frm_edit_speaker').validate({
        rules: {
            edit_s_name: {
                required: true
            },
            edit_s_lname: {
                required: true
            },
            edit_s_email: {
                required: true,
                email: true
            },
            edit_s_comp: {
                required: true
            },
            edit_s_pos: {
                required: true
            },
            edit_s_img: {
                accept: "image/*",
                maxImageWH: 315
            }
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
            data.append("cmd", "update_speaker");
            $.ajax({
                type: "post",
                url: BASE_LANG + "service/speaker.php",
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
                        alert_center('Update speaker', msg, "success")
                        dt_speaker.ajax.reload();
                    } else {
                        alert_center('Update speaker', msg, "error")
                    }
                }
            });
        }
    });

    function employee_is_admin(data, type, row) {
        if (data == 'Y')
            return '<label class="form-check form-check-inline form-switch"> \
                        <input class="form-check-input" name="upd_active" type="checkbox" data-active="' + data + '" data-speaker-id="' + row['speaker_id'] +'" checked> \
                        <span class="form-check-label">Active</span> \
                    </label>';
        else
            return '<label class="form-check form-check-inline form-switch"> \
                        <input class="form-check-input" name="upd_active" type="checkbox" data-active="' + data + '" data-speaker-id="' + row['speaker_id'] +'"> \
                        <span class="form-check-label">Inactive</span> \
                    </label>';
    }

    function employee_tools(data, type, row) {
        var tools = '<button ';
            tools += ' data-id = "' + data + '"';
            tools += ' data-name = "' + row['speaker_name'] + '"';
            tools += ' data-surname = "' + row['speaker_surname'] + '"';
            tools += ' data-position = "' + row['speaker_position'] + '"';
            tools += ' data-company = "'  + row['speaker_company'] + '"';
            tools += ' data-email = "' + row['speaker_email'] + '"';
            tools += ' data-image = "' + row['speaker_image'] + '"';
            tools += ' name="edit_speaker" class="btn btn-outline-warning mx-1"><i class="fas fa-edit"></i></button>';
            tools += '<button name="remove_speaker" data-name = "' + row['speaker_name'] + '" data-surname = "' + row['speaker_surname'];
            tools += '" data-speaker-id="' + data + '" class="btn btn-outline-danger mx-1" data-bs-toggle="modal" data-bs-target="#modal_remove">'
            tools += '<i class="far fa-trash-alt"></i></button>';
        return tools
    }
})