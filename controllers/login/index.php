<?php
header('Content-Type: text/html; charset=utf-8');
$theme = "login";
$PAGE_VAR["js"][] = "authentication/login";

if(isset($_SESSION['status'])){
  header("Location: ".WEB_META_BASE_LANG);
} 

?>

<form id="login" class="card card-md">
  <div class="card-body">
    <h2 class="card-title text-center mb-4">Login to your account</h2>
    <div class="mb-3">
      <label class="form-label">Username</label>
      <input id="login_username" name="login_username" type="text" class="form-control" placeholder="Enter username">
    </div>
    <div class="mb-2">
      <label class="form-label">Password</label>
      <div class="input-group input-group-flat">
        <input id="login_password" name="login_password" type="password" class="form-control"  placeholder="Password">
        <span id="toggle-password" class="input-group-text">
          <span toggle="#password-field" class="far fa-eye field-icon mr-2 cursor-pointer toggle-password"></span>
        </span>
      </div>
    </div>
    <div class="form-footer">
      <button type="submit" class="btn btn-primary w-100">Sign in</button>
    </div>
  </div>
</form>