<?php 
session_start();
$nosidebar=$nonavbar=''; //variable to not include the navbar or sidbar  if is exist and
$title='Register';
include 'init.php';

 ?>

 <body class="register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="index2.html"><b>Tidda </b><span>.com</span></a>
  </div>

  <div class="register-box-body">
    <p class="login-box-msg">Create an account</p>

    <form class="registerForm" action="" method="POST" >
      <div class="form-group ">
        <input type="text" name="name"  class="name form-control" placeholder="Full name..."  >
        <i class="fa fa-user form-control-feedback"></i>
        <span   class="nameError color-red visible ">Name Error</span>
      </div>
      <div class="form-group ">
        <input type="email"  name="email"  class="email form-control"  id="emailCheck" placeholder="Email..."  >
        <i class="fa fa-envelope form-control-feedback"></i>
        <span  class="emailError color-red visible">Email Error</span>
        <span  class="emailExistsError color-red visible ">Email Error</span>
      </div>
      <div class="form-group ">
        <input type="password"  name="password"  class="password form-control" placeholder="Password..."  >
        <i class="fa fa-lock form-control-feedback"></i>
        <span  class="passwordError color-red visible ">Password Error</span>
      </div>
      <div class="form-group ">
        <input type="password" name="cpassword"  class="cpassword  form-control" placeholder="Retype password..." >
        <i class="fa fa-lock form-control-feedback"></i>
        <span  class="cpasswordError color-red visible ">Confirm Password Error</span>
      </div>
      <div class="row">
        <div class="in-row">
          <div class="checkbox">
            <label>
              <input type="checkbox" required> I agree to the <a href="#">terms</a>
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="btn-row">
          <input type="submit"  value="register" class="sub-button" name="submiting">
        </div>
        <!-- /.col -->
      </div>
    </form>

    <a href="login.php" class=" text-center" style=" display: inline-block;">I already have an account</a>
  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->


</body>
<?php

include $tpl.'footer_end.php';
?>