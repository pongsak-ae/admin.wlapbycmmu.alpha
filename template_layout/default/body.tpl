<div id="toast-container"></div>
<div class="page">
	<div class="sticky-top">
		<?php include $theme_dir . "header.tpl"; ?>    

		<div class="navbar-expand-md">
		  <div class="collapse navbar-collapse" id="navbar-menu">
		    <div class="navbar navbar-light">
		      <div class="container-xl">
		        <ul class="navbar-nav col-md-10 col-sm-12">
		        	<li class="nav-item">
		        		<a class="nav-link" href="<?=WEB_META_BASE_LANG ; ?>" >
		        			<i class="nav-link-icon d-md-none d-lg-inline-block fas fa-chalkboard-teacher" style="margin: 1px 5px 0px 0px;"></i>
		        			<span class="nav-link-title">Register report</span>
		        		</a>
		        	</li>
		        	<li class="nav-item">
		        		<a class="nav-link" href="<?=WEB_META_BASE_LANG ; ?>" >
		        			<i class="nav-link-icon d-md-none d-lg-inline-block fas fa-th-large" style="margin: 1px 5px 0px 0px;"></i>
		        			<span class="nav-link-title">Banner</span>
		        		</a>
		        	</li>
		        	<li class="nav-item">
		        		<a class="nav-link" href="<?=WEB_META_BASE_LANG ; ?>course" >
		        			<i class="nav-link-icon d-md-none d-lg-inline-block fab fa-leanpub" style="margin: 1px 5px 0px 0px;"></i>
		        			<span class="nav-link-title">Courses</span>
		        		</a>
		        	</li>
		        	<li class="nav-item">
		        		<a class="nav-link" href="<?=WEB_META_BASE_LANG ; ?>" >
		        			<i class="nav-link-icon d-md-none d-lg-inline-block fas fa-users-cog" style="margin: 1px 5px 0px 0px;"></i>
		        			<span class="nav-link-title">Employee</span>
		        		</a>
		        	</li>

		        </ul>
		        <div class="col-md-2 col-sm-12 my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
		          <form action="." method="get">
		            <div class="input-icon">
		              <span class="input-icon-addon">
		                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
		              </span>
		              <input type="text" class="form-control" placeholder="Search…" aria-label="Search in website">
		            </div>
		          </form>
		        </div>
		      </div>
		    </div>
		  </div>
		</div>
		
  	</div>

	<div class="content">
		<?=$HTML_CONTENT?>
		<?php include $theme_dir . "footer.tpl";?>
	</div>
</div>
