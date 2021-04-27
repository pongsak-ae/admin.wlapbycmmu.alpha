<?php

$PAGE_VAR["js"][] = "gallery";
// $PAGE_VAR["js"][] = "template/libs/apexcharts/dist/apexcharts.min";

if($_SESSION['status'] != "Y"){
 header("Location: ".WEB_META_BASE_LANG."login/");
}

// // COURSE SELECT
$course_option = '';
foreach(course() as $k => $v) {
  $course_active = ($v['course_active'] == 1) ? 'selected' : '';
  $course_option .= '<option data-course-name="' . $v['course_name'] . '" data-active="'. $v['course_active'] .'" '. $course_active .' value="' . $v['course_id'] . '">' . $v['course_name'] . ' (' . $v['course_no'] . ')</option>';
}
// // COURSE SELECT

?>

<div class="container-xl">

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Course gallery</h3>
      
      <!-- Page title actions -->
      <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
          <div class="form-floating">
            <select  class="form-select" id="gallery_course" style="padding-right: 5rem;">
              <?=$course_option?>
            </select>
            <label id="g_label_course" for="gallery_course"></label>
            <input type="text" id="gallery-course-name" hidden="">
          </div>
          <span class="d-none d-sm-inline">
            <button id="add_gallery" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
              Create image
            </button>
          </span>
        </div>
      </div>

    </div>
    <div class="table-responsive my-3">
      <table id="dtb_gallery" class="table table-vcenter text-nowrap table-striped w-100">
        <thead>
          <tr>
            <th>Image</th>
            <th>Image name</th>
            <th>Image alt</th>
            <th>Image active</th>
            <th>Createdatetime</th>
            <th>Tools</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>


<div id="modal_gallery_image"></div>
<div id="modal_removeGallery"></div>
<div id="modal_add_gallery"></div>
<div id="modal_edit_gallery"></div>