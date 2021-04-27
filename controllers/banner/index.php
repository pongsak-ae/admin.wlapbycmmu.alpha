<?php

	$PAGE_VAR["js"][] = "banner";
	// $PAGE_VAR["js"][] = "template/libs/apexcharts/dist/apexcharts.min";

	if($_SESSION['status'] != "Y"){
	 header("Location: ".WEB_META_BASE_LANG."login/");
	}

?>

<div class="container-xl">
	<div class="page">
		<div class="content">
			<div class="page-header d-print-none">
				<div class="row align-items-center">
					<div class="col">
						<h2 class="page-title">
							Banner
						</h2>
						<div class="text-muted mt-1">1-12 of 241 photos</div>
					</div>
					<div class="col-auto ms-auto d-print-none">
						<div class="d-flex">
							<button id="btn_banner" class="btn btn-primary">
								<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
								Add banner
							</button>
						</div>
					</div>
				</div>
			</div>
			<div id="show_banner" class="row row-cards" style="max-height: 53rem;overflow-y: auto;">
			</div>
		</div>
	</div>
</div>

<div id="modal_add_banner"></div>
<div id="modal_removeBanner"></div>

