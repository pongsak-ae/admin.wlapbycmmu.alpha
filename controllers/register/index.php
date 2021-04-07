<?php
$theme = "login";
$PAGE_VAR["css"][] = "site";
$PAGE_VAR["js"][] = "authentication/register";

?>

<form id="register" class="card card-md">
  <div class="card-body">
    <h2 class="card-title text-center mb-4">Create new account</h2>

    <div class="mb-3">
      <label class="form-label">Username</label>
      <input id="username" name="username" type="text" class="form-control" placeholder="Enter username">
    </div>

    <div class="mb-3">
      <label class="form-label">Email address</label>
      <input id="email" name="email" type="email" class="form-control" placeholder="Enter email">
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <div class="input-group input-group-flat">
        <input id="password" name="password" type="password" class="form-control"  placeholder="Password">
        <span id="toggle-password" class="input-group-text">
          <span toggle="#password-field" class="far fa-eye field-icon mr-2 cursor-pointer toggle-password"></span>
        </span>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Confirm Password</label>
      <div class="input-group input-group-flat">
        <input id="confirm_password" name="confirm_password" type="password" class="form-control"  placeholder="Confirm Password">
        <span id="toggle-password-span" class="input-group-text">
          <span toggle="#password-field" class="far fa-eye field-icon mr-2 cursor-pointer toggle-password-span"></span>
        </span>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-check">
        <input type="checkbox" class="form-check-input"/>
        <span class="form-check-label">Agree the <a href="./terms-of-service.html" tabindex="-1">terms and policy</a>.</span>
      </label>
    </div>

    <div class="form-footer">
      <button type="submit" class="btn btn-primary w-100">Create new account</button>
    </div>
  </div>
</form>

<div class="text-center text-muted mt-3">
  Already have account? <a href="<?=WEB_META_BASE_LANG."login" ; ?>" tabindex="-1">Sign in</a>
</div>