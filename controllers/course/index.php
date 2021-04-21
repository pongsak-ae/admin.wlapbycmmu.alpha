<?php

$PAGE_VAR["js"][] = "course";
// $PAGE_VAR["js"][] = "template/libs/apexcharts/dist/apexcharts.min";

if($_SESSION['status'] != "Y"){
 header("Location: ".WEB_META_BASE_LANG."login/");
}


?>

<div class="container-xl">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Courses list</h3>
      <!-- Page title actions -->
      <div class="col-auto ms-auto d-print-none">
        <a class="add-course btn btn-primary d-none d-sm-inline-block cursor-poiter" >
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
          Create course
        </a>
        <a class="add-course btn btn-primary d-sm-none btn-icon cursor-poiter">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
        </a>
      </div>

    </div>
    <div class="table-responsive my-3">
      <table id="datatable_course" class="table table-vcenter text-nowrap table-striped w-100">
        <thead>
          <tr>
            <th>Active</th>
            <th>Course No.</th>
            <th>Course Name</th>
            <th>Course Datetime</th>
            <th>Place</th>
            <th>Price</th>
            <th>Course Start</th>
            <th>Course End</th>
            <th>Tools</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<div id="modal_addcourse"></div>
<div id="modal_removeCourse"></div>
<div id="modal_editcourse"></div>
<div id="modal_commemtcourse"></div>


