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
                <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-backdrop="static" data-bs-target="#modal_add">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Add Speaker
                </a>
                <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#modal_add" aria-label="Add Speaker">
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
                        <th>Name</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Tools</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal_remove" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
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
                            <button type="button" class="btn btn-danger w-100 btn-confirm-del" data-bs-dismiss="modal">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal_add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm_add_speaker">
                <div class="modal-header">
                    <h5 class="modal-title">New speaker</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-3 ">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">First name</label>
                                        <input type="text" id="add_s_name" name="add_s_name" class="form-control" placeholder="Enter first name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Last name</label>
                                        <input type="text" id="add_s_lname" name="add_s_lname" class="form-control" placeholder="Enter last name">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3 ">
                                <label class="form-label">Email address</label>
                                <input type="email" id="add_s_email" name="add_s_email" class="form-control" aria-describedby="emailHelp" placeholder="Enter email">
                            </div>
                            <div class="form-group mb-3 ">
                                <label class="form-label">Company</label>
                                <input type="text" id="add_s_comp" name="add_s_comp" class="form-control" placeholder="Enter company">
                            </div>
                            <div class="form-group mb-3 ">
                                <label class="form-label">Position</label>
                                <input type="text" id="add_s_pos" name="add_s_pos" class="form-control" placeholder="Enter position">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <img id="speaker_image" src="../images/no-image.jpg" class="card-img-top mb-3" style="height: 252px;object-fit: cover;">
                            <input type="file" id="add_s_img" name="add_s_img" class="form-control" accept="image/*"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Create new speaker
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal_edit" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm_edit_speaker">
                <div class="modal-header">
                    <h5 class="modal-title">Edit speaker</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-3 ">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">First name</label>
                                        <input type="text" id="edit_s_name" name="edit_s_name" class="form-control" placeholder="Enter first name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Last name</label>
                                        <input type="text" id="edit_s_lname" name="edit_s_lname" class="form-control" placeholder="Enter last name">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3 ">
                                <label class="form-label">Email address</label>
                                <input type="email" id="edit_s_email" name="edit_s_email" class="form-control" aria-describedby="emailHelp" placeholder="Enter email">
                            </div>
                            <div class="form-group mb-3 ">
                                <label class="form-label">Company</label>
                                <input type="text" id="edit_s_comp" name="edit_s_comp" class="form-control" placeholder="Enter company">
                            </div>
                            <div class="form-group mb-3 ">
                                <label class="form-label">Position</label>
                                <input type="text" id="edit_s_pos" name="edit_s_pos" class="form-control" placeholder="Enter position">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <img id="speaker_edit_image" src="../images/no-image.jpg" class="card-img-top mb-3" style="height: 252px;object-fit: cover;">
                            <input type="file" id="edit_s_img" name="edit_s_img" class="form-control" accept="image/*"/>
                        </div>
                    </div>
                    <input id="edit_s_id" name="edit_s_id" type="hidden">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary ms-auto">
                        Update speaker
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal_active" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-warning"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-warning icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 9v2m0 4v.01" />
                    <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                </svg>
                <h3>Are you sure?</h3>
                <div class="text-muted">Do you really want to change speaker status ?</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-white w-100" data-bs-dismiss="modal">Cancel</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-warning w-100 btn-confirm-upd" data-bs-dismiss="modal">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>