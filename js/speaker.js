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

    $('#datatable_speaker').on('click', '[name="remove_speaker"]', function(){
        $.ajax({
            type: "post",
            url: BASE_LANG + "service/form.php",
            data: {
                "cmd": "del",
                "id": $(this).attr("data-id")
            },
            dataType: "json",
            success: function (data) {
                Toastify({
                    text: "Delete successful!",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();
                tblFormView.ajax.reload();
            }
        });
    });

    $('#modal-remove').on('click', '.btn-confirm-del', function(){
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
    
    $('#modal-remove').on('show.bs.modal', function(e) {
        var data = $(e.relatedTarget).data();
        $('.title', this).text(data.name + ' ' + data.surname);
        $('.btn-confirm-del', this).data('speakerId', data.speakerId);
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
        errorClass: "help-inline text-danger",
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
                        $('#frm_add_speaker')[0].reset();
                        alert_center('Add speaker', msg, "success")
                        dt_speaker.ajax.reload();
                    } else {
                        alert_center('Add speaker', msg, "error")
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
            tools += ' name="edit_speaker" class="btn btn-outline-warning mx-1"><i class="fas fa-edit"></i></button>';
            tools += '<button name="remove_speaker" data-name = "' + row['speaker_name'] + '" data-surname = "' + row['speaker_surname'];
            tools += '" data-speaker-id="' + data + '" class="btn btn-outline-danger mx-1" data-bs-toggle="modal" data-bs-target="#modal-remove">'
            tools += '<i class="far fa-trash-alt"></i></button>';
        return tools
    }
})