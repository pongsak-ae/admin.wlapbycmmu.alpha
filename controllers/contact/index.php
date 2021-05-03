<?php

	$PAGE_VAR["js"][] = "contact";
	// $PAGE_VAR["js"][] = "template/libs/apexcharts/dist/apexcharts.min";

	if($_SESSION['status'] != "Y"){
	 header("Location: ".WEB_META_BASE_LANG."login/");
	}

?>


<div class="container-xl">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Contact</h3>


    </div>
    <div class="card-body">
    	<form id="update_contact">
	    	<div class="row">
	    		<div class="col-12">
					<div class="mb-3">
						<label class="form-label">Address</label>
						<textarea  class="form-control" id="contact_address" name="contact_address" rows="6" placeholder="Address.."><?=contact()['address'] ?></textarea>
					</div>
				</div>
				<div class="col-4">
					<div class="input-icon mb-3">
				      <span class="input-icon-addon">
				        <i class="fas fa-phone-alt"></i>
				      </span>
				      <input value="<?=contact()['telephone'] ?>" type="text" id="contact_phone" name="contact_phone" class="form-control" placeholder="Phone nummber...">
				    </div>
				</div>
				<div class="col-4">
					<div class="input-icon mb-3">
				      <span class="input-icon-addon">
				        <i class="far fa-envelope"></i>
				      </span>
				      <input value="<?=contact()['email'] ?>" type="text" class="form-control" id="contact_email" name="contact_email" placeholder="Email...">
				    </div>
				</div>
				<div class="col-4">
					<div class="input-icon mb-3">
				      <span class="input-icon-addon">
				        <i class="fas fa-fax"></i>
				      </span>
				      <input value="<?=contact()['fax'] ?>" type="text" class="form-control" id="contact_fax" name="contact_fax" placeholder="Fax...">
				    </div>
				</div>
				<div class="col-6">
					<div class="input-icon mb-3">
				      <span class="input-icon-addon">
				        <i class="fab fa-line"></i>
				      </span>
				      <input value="<?=contact()['line'] ?>" type="text" class="form-control" id="contact_line" name="contact_line" placeholder="Line...">
				    </div>
				</div>
				<div class="col-6">
					<div class="input-icon mb-3">
				      <span class="input-icon-addon">
				        <i class="fab fa-facebook-square"></i>
				      </span>
				      <input value="<?=contact()['facebook'] ?>" type="text" class="form-control" id="contact_facebook" name="contact_facebook" placeholder="Facebook...">
				    </div>
				</div>
				<div class="col-3">
					<button class="btn btn-primary w-100">Submit</button>
				</div>
			</div>
		</form>
    </div>
  </div>
</div>



