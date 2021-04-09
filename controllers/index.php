<?php

$PAGE_VAR["js"][] = "register-report";
$PAGE_VAR["js"][] = "template/libs/apexcharts/dist/apexcharts.min";

if($_SESSION['status'] != "Y"){
 header("Location: ".WEB_META_BASE_LANG."login/");
}

// COURSE SELECT
$course_option = '';
foreach(course() as $k => $v) {
  $course_active = ($v['course_active'] == 1) ? 'selected' : '';
  $course_option .= '<option '. $course_active .' value="' . $v['course_id'] . '">' . $v['course_name'] . '</option>';
}
// COURSE SELECT

?>

<div class="container-xl">
<!--   <div class="page-header d-print-none">
    <div class="row align-items-center">
      <div class="col">
        <div class="page-pretitle">
          Report
        </div>
        <h2 class="page-title">
          Customer register
        </h2>
      </div>
    </div>
  </div> -->

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Customer register</h3>
      <!-- Page title actions -->
      <div class="col-auto ms-auto d-print-none">
        <div class="form-floating">
          <select class="form-select" id="select_course" style="padding-right: 5rem;">
            <?=$course_option?>
          </select>
          <label id="label_course" for="select_course"></label>
        </div>
      </div>

    </div>
    <div class="table-responsive my-3">
      <table id="datatable_register" class="table table-vcenter text-nowrap table-striped">
        <thead>
          <tr>
            <!-- <th>Tools</th> -->
            <th>Status</th>
            <th>Course Name</th>
            <th>Fullname</th>
            <th>Telephone</th>
            <th>Facebook</th>
            <th>Line</th>
            <th>Email</th>
            <th>Company</th>
            <th>Position</th>
            <th>IDCARD</th>
            <th>Image</th>
            <th>Course Studied</th>
            <th>Course Expectation</th>
            <th>Coordinator Name</th>
            <th>Coordinator Phone</th>
            <th>Coordinator Adviser</th>
            <th>Allergic Food</th>
            <th>Shirt Size</th>
            <th>Createdate</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>


<div id="modal_image"></div>
<div id="modal_status"></div>
<div id="modal_shirt"></div>
