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
                        <th>Tools</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-remove" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 9v2m0 4v.01" />
                    <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                </svg>
                <h3>Are you sure?</h3>
                <div class="text-muted">Do you really want to remove <b class="title"></b>? What you've done cannot be undone.</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-white w-100" data-bs-dismiss="modal">Cancel</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-danger w-100 btn-confirm-del">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>