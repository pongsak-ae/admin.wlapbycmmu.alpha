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
							Gallery
						</h2>
						<div class="text-muted mt-1">1-12 of 241 photos</div>
					</div>
					<div class="col-auto ms-auto d-print-none">
						<div class="d-flex">
							<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_banner">
								<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
								Add banner
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="row row-cards">
				<div class="col-sm-6 col-lg-3">
					<div class="card card-sm">
						<a href="#" class="d-block"><img src="./images/banner/1.jpg" class="card-img-top"></a>
						<div class="card-body">
							<div class="d-flex align-items-center">
								<!-- <span class="avatar me-3 rounded" style="background-image: url(./static/avatars/000m.jpg)"></span> -->
								<div>
									<div>Paweł Kuna</div>
									<!-- <div class="text-muted">3 days ago</div> -->
								</div>
								<div class="ms-auto">
									<label class="form-check form-switch form-check-inline m-auto mt-1">
		                            	<input class="cursor-pointer form-check-input" type="checkbox" checked="">
		                            	<!-- <span class="form-check-label">Option 1</span> -->
		                          	</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-3">
					<div class="card card-sm">
						<a href="#" class="d-block"><img src="./images/banner/2.jpg" class="card-img-top"></a>
						<div class="card-body">
							<div class="d-flex align-items-center">
								<span class="avatar me-3 rounded">JL</span>
								<div>
									<div>Jeffie Lewzey</div>
									<div class="text-muted">5 days ago</div>
								</div>
								<div class="ms-auto">
									<a href="#" class="text-muted">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
										335
									</a>
									<a href="#" class="ms-3 text-muted">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.5 13.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" /></svg>
										80
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-3">
					<div class="card card-sm">
						<a href="#" class="d-block"><img src="./images/banner/3.jpg" class="card-img-top"></a>
						<div class="card-body">
							<div class="d-flex align-items-center">
								<span class="avatar me-3 rounded">JL</span>
								<div>
									<div>Jeffie Lewzey</div>
									<div class="text-muted">5 days ago</div>
								</div>
								<div class="ms-auto">
									<a href="#" class="text-muted">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
										335
									</a>
									<a href="#" class="ms-3 text-muted">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.5 13.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" /></svg>
										80
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-3">
					<div class="card card-sm">
						<a href="#" class="d-block"><img src="./images/banner/4.jpg" class="card-img-top"></a>
						<div class="card-body">
							<div class="d-flex align-items-center">
								<span class="avatar me-3 rounded">JL</span>
								<div>
									<div>Jeffie Lewzey</div>
									<div class="text-muted">5 days ago</div>
								</div>
								<div class="ms-auto">
									<a href="#" class="text-muted">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
										335
									</a>
									<a href="#" class="ms-3 text-muted">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.5 13.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" /></svg>
										80
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-blur fade" id="add_banner" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New banner</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      	<div class="row">
      		<div class="col-md-4 col-sm-12">
		        <div class="mb-3">
					<div class="form-floating">
						<select class="form-select" id="banner_status">
							<option selected="" disabled="">Please select status</option>
							<option value="1">Active</option>
							<option value="0">Inactive</option>
						</select>
						<label for="banner_status">Select</label>
					</div>
				</div>
	        </div>
	        <div class="col-md-8 col-sm-12">
		        <div class="mb-3">
		        	<div class="form-floating mb-3">
		        		<input type="email" class="form-control" id="floating-input" placeholder="n" autocomplete="off">
		        		<label for="floating-input">Banner name</label>
		        	</div>
		        </div>
			</div>
			<div class="col-12">
				<div class="card card-sm">
					<a class="d-block" target="_blank">
						<img id="banner_img" src="<?=WEB_META_BASE_URL?>images/no-image.jpg" class="card-img-top" style="height: 25rem;object-fit: cover;">
					</a>
					<div class="card-body">
						<div class="d-flex align-items-center">
							<input id="add_banner_img" name="add_banner_img" type="file" class="form-control">
						</div>
					</div>
				</div>
			</div>	

		</div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary ms-auto" data-bs-dismiss="modal">Submit</button>
      </div>
    </div>
  </div>
</div>

