$(function(){
    var dt_speaker = $('#datatable_speaker').DataTable( {
        ajax: {
            "url" : BASE_LANG + "service/speaker.php",
            "type": "POST",
            "data": function( d ){ 
                d.cmd = "speaker";
            }
        },
        type: "JSON",
        columns: [
            { data: "speaker_sort"},
            { data: "speaker_name", render: speaker_name},
            { data: "speaker_position", render: speaker_position},
            { data: "speaker_id", render: speaker_tools}
        ],
        columnDefs: [
            { targets: [0, 3], className: "text-center", width: "10%" },
            { targets: [1, 2], width: "35%" }
        ],
        rowReorder: true,
        scrollY: 500,
        paging: false,
        responsive: true
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

    $('#modal_remove').on('show.bs.modal', function(e) {
        var data = $(e.relatedTarget).data();
        $('.title', this).text(data.name + ' ' + data.surname);
        $('.btn-confirm-del', this).data('speakerId', data.speakerId);
    });

    $('#add_s_img').on('change', function () {
        var file = this.files[0];
        var reader = new FileReader();
        reader.onloadend = function () {
            $('#speaker_image').attr('src', reader.result);
        }
        if (file) {
            reader.readAsDataURL(file);
        } else {
            return false;
        }
    });

    $('#frm_add_speaker').validate({
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
                required: true
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

    $('#frm_edit_speaker').validate({
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

    function speaker_name(data, type, row) {
        var speaker_img;
        if (row["speaker_image"])
            speaker_img = '<span class="avatar me-2" style="background-image: url(./images/speaker/' + row["speaker_image"] + ')"></span>';
        else
            speaker_img = '<span class="avatar me-2">' + data.charAt(0).toUpperCase() + row["speaker_surname"].charAt(0).toUpperCase() + '</span>';

        var nameHtml = '<div class="d-flex py-1 align-items-center">';
            nameHtml += speaker_img;
            nameHtml += '<div class="flex-fill">';
            nameHtml += '   <div class="font-weight-medium">' + data + ' ' + row["speaker_surname"] + '</div>';
            nameHtml += '   <div class="text-muted"><a href="#" class="text-reset">' + row["speaker_email"] + '</a></div>';
            nameHtml += '</div>';
            nameHtml += '</div>';
        return nameHtml
    }

    function speaker_position(data, type, row) {
        return '<div>' + row["speaker_company"] + '</div> \
                <div class="text-muted">' + data +'</div>';
    }

    function speaker_tools(data, type, row) {
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