<?php
$PAGE_VAR["js"][] = "employee";

if ($_SESSION['status'] != "Y" && $_SESSION['isAdmin'] != 'Y') {
    header("Location: " . WEB_META_BASE_LANG . "login/");
}
?>

<div class="container-xl">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Employee List</h3>
            <div class="col-auto ms-auto d-print-none">
                <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-backdrop="static" data-bs-target="#modal_add">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Add Employee
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
            <table id="datatable_employee" class="table table-vcenter text-nowrap table-striped w-100">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Telephone</th>
                        <th>E-mail</th>
                        <th>Position</th>
                        <!-- <th>Is Admin</th> -->
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
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm_add_employee">
                <div class="modal-header">
                    <h5 class="modal-title">New employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" id="add_e_username" name="add_e_username" class="form-control" placeholder="Enter user name">
                        <label for="add_e_username">User Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" id="add_e_password" name="add_e_password" class="form-control" placeholder="Password">
                        <label for="add_e_password">Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" id="add_e_name" name="add_e_name" class="form-control" placeholder="Enter full name">
                        <label for="add_e_name">Full Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="tel" id="add_e_phone" name="add_e_phone" class="form-control" placeholder="Enter telephone number" pattern="[0-9]{9}|[0-9]{10}" maxlength="10">
                        <label for="add_e_phone">Telephone</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" id="add_e_email" name="add_e_email" class="form-control" placeholder="Enter e-mail">
                        <label for="add_e_email">Email address</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" id="add_e_pos" name="add_e_pos" class="form-control" placeholder="Enter position">
                        <label for="add_e_pos">Position</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select id="add_e_admin" name="add_e_admin" class="form-select">
                            <option value="Y" selected>Yes</option>
                            <option value="N">No</option>
                        </select>
                        <label for="add_e_admin">Is Admin</label>
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
                        Create new employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal_edit" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm_edit_employee">
                <div class="modal-header">
                    <h5 class="modal-title">Edit employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" id="edit_e_username" name="edit_e_username" class="form-control" placeholder="Enter user name">
                        <label for="edit_e_username">User Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" id="edit_e_password" name="edit_e_password" class="form-control" placeholder="Password">
                        <label for="edit_e_password">New Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" id="edit_e_name" name="edit_e_name" class="form-control" placeholder="Enter full name">
                        <label for="edit_e_name">Full Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="tel" id="edit_e_phone" name="edit_e_phone" class="form-control" placeholder="Enter telephone number" pattern="[0-9]{9}|[0-9]{10}" maxlength="10">
                        <label for="edit_e_phone">Telephone</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" id="edit_e_email" name="edit_e_email" class="form-control" placeholder="Enter e-mail">
                        <label for="edit_e_email">Email address</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" id="edit_e_pos" name="edit_e_pos" class="form-control" placeholder="Enter position">
                        <label for="edit_e_pos">Position</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select id="edit_e_admin" name="edit_e_admin" class="form-select">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                        <label for="edit_e_admin">Is Admin</label>
                    </div>
                    <input id="edit_e_id" name="edit_e_id" type="hidden">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary ms-auto">
                        Update employee
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
                <div class="text-muted">Do you want to change employee status ?</div>
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