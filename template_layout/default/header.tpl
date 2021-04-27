<style type="text/css">
  .logo-main:hover {
      opacity: 0.5;
      color: unset;
      text-decoration: unset;
  }
</style>

<header class="navbar navbar-expand-md navbar-dark d-print-none">
  <div class="container-xl">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
      <a class='logo-main' href="<?=WEB_META_BASE_URL?>">
        IFIRSTFIX.COM<!-- <img src="<?=WEB_META_BASE_URL?>images/logo.png" width="110" height="32" alt="Tabler" class="navbar-brand-image">-->
      </a>
    </h1>
    <div class="navbar-nav flex-row order-md-last">
      <!--
      <div class="nav-item dropdown d-none d-md-flex me-3">
        <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"></path><path d="M9 17v1a3 3 0 0 0 6 0v-1"></path></svg>
          <span class="badge bg-red"></span>
        </a>
      
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-card">
          <div class="card">
            <div class="card-body">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus ad amet consectetur exercitationem fugiat in ipsa ipsum, natus odio quidem quod repudiandae sapiente. Amet debitis et magni maxime necessitatibus ullam.
            </div>
          </div>
        </div>
      </div>
      -->

      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
          <span class="avatar avatar-sm" style="background-image: url(<?=WEB_META_BASE_URL?>images/face28.jpg)"></span>
          <div class="d-none d-xl-block ps-2">
            <div><?php echo employee()[0]['full_name']; ?></div>
            <div class="mt-1 small text-muted"><?php echo employee()[0]['position']; ?></div>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
        <!--
          <a href="#" class="dropdown-item">Set status</a>
          <a href="#" class="dropdown-item">Profile &amp; account</a>
          <a href="#" class="dropdown-item">Feedback</a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">Settings</a>
        -->
          <a id="sign_out" class="dropdown-item cursor-pointer">Logout</a>
        </div>
      </div>
    </div>
  </div>
</header>