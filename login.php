<?php 
session_start();
$nosidebar=$nonavbar=''; //variable to not include the navbar or sidbar  if is exist and
$title='Login';
include 'init.php';
 ?>
<body class=" login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="index2.html"><b>Tidda</b><span>.com</span></a>
  </div>
  <!-- login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign In</p>

    <form class="loginForm" action="" method="POST">
      <div class="form-group  ">
        <input type="email" class="form-control"  name="email" placeholder="Email" required>
        <i class="fa fa-envelope form-control-feedback"></i>
      </div>
      <div class="form-group  ">
        <input type="password" class="form-control"  name="password" placeholder="Password" required>
        <i class="fa fa-lock form-control-feedback"></i>
      </div>
      <div class="row">
        <div class="in-row">
          <div class="checkbox">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="btn-row">
          <input  type="submit" class="sub-button" value="Sign In">         
        </div> 
        <!-- /.col -->
      </div>
          <div class="row">
            <div class="in-row">
                <span class="loginError  visible color-red">Invalid Email/Password!</span>
            </div>
          </div>
          <div class="row">
            <div class="">
              <?php if(isset($_SESSION['registered'])) { ?>
                <span  class="registered-confirm color-green">You Have Registered Successfully !</span>
              <?php unset($_SESSION['registered']); } ?>
            </div>
          </div>
        </form>
        <!-- /.social-auth-links -->

    <a href="#">I forgot my password</a><br><br>
    <a href="register.php" class="text-center">Create an account</a>

  </div>
  <!-- login-box-body -->
</div>
<!-- login-box -->
</body>
<?php

include $tpl.'footer_end.php';
?>
