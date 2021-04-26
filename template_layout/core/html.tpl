<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$OMPage->getTitle("WLAP",$OMRoute->uri(),"")?></title>
	<base href="<?=WEB_META_BASE_URL?>" />
	<link rel="shortcut icon" href="<?=WEB_META_BASE_URL?>images/favicon.png" />
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
	<style type="text/css">
		body {
		    font-family: 'Kanit', sans-serif !important;
		    font-size: 16px !important;
		}
	</style>
	<link href="<?=WEB_META_BASE_URL?>css/comp.css<?=$OMPage->merge_media('css')?>" rel="stylesheet" type="text/css" />

	<!-- CSS Template -->
	<link rel="stylesheet" href="<?=WEB_META_BASE_URL?>css/template/css/tabler.min.css">
	<link rel="stylesheet" href="<?=WEB_META_BASE_URL?>css/template/css/tabler-flags.min.css">
	<link rel="stylesheet" href="<?=WEB_META_BASE_URL?>css/template/css/tabler-payments.min.css">
	<link rel="stylesheet" href="<?=WEB_META_BASE_URL?>css/template/css/tabler-vendors.min.css">
	<link rel="stylesheet" href="<?=WEB_META_BASE_URL?>css/template/css/demo.min.css">
	<link rel="stylesheet" href="<?=WEB_META_BASE_URL?>css/extensions/choices/choices.min.css">
	<link rel="stylesheet" href="<?=WEB_META_BASE_URL?>css/extensions/bootstrap-tagsinput/bootstrap-tagsinput.css">
	<link rel="stylesheet" href="<?=WEB_META_BASE_URL?>css/extensions/wysiwyg/quill-snow/quill.snow.css">

	<!-- BOOTSTRAP 5 -->
	<!-- <link rel="stylesheet" href="<?=WEB_META_BASE_URL?>css/extensions/bootstrap5/bootstrap.min.css"> -->

	<!-- DATATABLE -->
	<link rel="stylesheet" href="<?=WEB_META_BASE_URL?>css/extensions/datatable/dataTables.bootstrap5.min.css">
	<!-- <link rel="stylesheet" href="<?=WEB_META_BASE_URL?>css/extensions/datatable/responsive.bootstrap4.min.css"> -->
	<!-- Fonts -->
	<link rel="stylesheet" href="<?=WEB_META_BASE_URL?>fontawesome-free/css/all.min.css">
	<!-- Jquery -->
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/core/jquery-3.4.1.min.js"></script>

	<!-- SITE -->
	<link rel="stylesheet" href="<?=WEB_META_BASE_URL?>css/site.css">
</head>
<body>
	<div id="toast-container"></div>
	<?php include $theme_dir . "body.tpl"; 
		$data_lang = array();
		foreach (get_defined_vars() as $key => $value) {
			$pos = strpos($key, "str");
			if($pos !== FALSE) {
				$data_lang[$key] = $value;
			}
		}
	?>
	<script type="text/javascript">
		var LANG = '<?=LANG?>';
		var BASE_URL = '<?=WEB_META_BASE_URL?>';
		var BASE_URL_API = '<?=WEB_META_BASE_API?>';
		var BASE_LANG = '<?=WEB_META_BASE_LANG?>';
		var sData_lang = '<?=json_encode($data_lang)?>';
	</script>

	<!-- BOOTSTRAP 5 -->
	<!-- <script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/extensions/bootstrap5/bootstrap.min.js"></script> -->

	<!-- jquery validate :js -->
	<script src="<?=WEB_META_BASE_URL?>js/extensions/jquery-validation/jquery.validate.min.js"></script>
	<script src="<?=WEB_META_BASE_URL?>js/extensions/jquery-validation/additional-methods.min.js"></script>
	<!-- jquery select2 :js -->
	<!-- <script src="<?=WEB_META_BASE_URL?>js/extensions/select2/select2-4.0.5.full.min.js"></script> -->
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/core/moment.min.js"></script>

	<!-- DATATABLE -->
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/extensions/datatable/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/extensions/datatable/dataTables.bootstrap4.min.js"></script>
<!-- 	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/extensions/datatable/dataTables.responsive.min.js"></script>
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/extensions/datatable/responsive.bootstrap4.min.js"></script>
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/extensions/datatable/dataTables.buttons.js"></script>
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/extensions/datatable/dataTables.rowGroup.min.js"></script> -->
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>fontawesome-free/js/all.min.js"></script>

	<!-- template:js -->
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/core/moment.min.js"></script>
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/extensions/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/template/libs/choices.js/choices.js"></script>
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/template/libs/litepicker/litepicker.js"></script>
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/template/js/tabler.min.js"></script>
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/extensions/sweetalert1/sweetalert.min.js"></script>
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/extensions/wysiwyg/quill-snow/quill.js"></script>

	<!-- <script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/extensions/sweetalert2/sweetalert2.min.js"></script> -->
	<!-- template:js -->
	<script type="text/javascript" src="<?=WEB_META_BASE_URL?>js/comp.js<?=$OMPage->merge_media('js')?>"></script>
</body>

</html>
