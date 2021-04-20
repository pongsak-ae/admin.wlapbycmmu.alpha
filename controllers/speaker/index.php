<?php
$PAGE_VAR["css"][] = "extensions/datatable/rowReorder.dataTables.min";
$PAGE_VAR["js"][] = "extensions/datatable/dataTables.rowReorder.min";
$PAGE_VAR["js"][] = "speaker";

if ($_SESSION['status'] != "Y") {
    header("Location: " . WEB_META_BASE_LANG . "login/");
}
?>

<div class="container-xl">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Speaker List</h3>
            <div class="col-auto ms-auto d-print-none">
                <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-speaker">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Add Speaker
                </a>
                <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#modal-speaker" aria-label="Add Speaker">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                </a>
            </div>
        </div>
        <div class="table-responsive my-3">
            <table id="datatable_speaker" class="table table-vcenter text-nowrap table-striped w-100">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>