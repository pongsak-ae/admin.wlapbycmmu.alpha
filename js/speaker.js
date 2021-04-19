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
            { "data": "sort"},
            { "data": "name"},
            { "data": "title"},
            { "data": "status"},
            { "data": "id"}
        ],
        rowReorder: true,
        scrollY: 500,
        paging: false,
        responsive: true
    } );
})