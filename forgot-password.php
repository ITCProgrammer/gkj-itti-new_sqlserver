<?php
ini_set("error_reporting", 1);
session_start();
//require_once "waktu.php";
include"koneksi.php";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MKT-ITTI | Forgot Password</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
	body{
		font-family: Calibri, "sans-serif", "Courier New";  /* "Calibri Light","serif" */
		font-style: normal;
	}	
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <img src="dist/img/index.ico" alt="Logo Indotaichen">  
    <a href="#"><b>Marketing</b> ITTI</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>

      <form action="" method="post" enctype="multipart/form-data">
        <div class="input-group mb-3">
          <input name="email" type="email" class="form-control" id="email" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Request new password</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mt-3 mb-1">
        <a href="login">Login</a>
      </p>
      
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
<?php
if($_POST){ //login user
  extract($_POST);
  $email = mysqli_real_escape_string($con,$_POST['email']);
  $sql=mysqli_query($con,"SELECT * FROM `user_login` WHERE `email`='$email' LIMIT 1");
  if(mysqli_num_rows($sql)>0)
  {  
  //login_validate();
	$_SESSION['email']=$email;  
    echo "<script>window.location='RecoverPass';</script>";
	/*echo "<script>swal({
  title: 'Login Success!!',
  text: 'Click Ok to continue',
  type: 'success',
  }).then((result) => {
  if (result.value) {
    window.location='Home';
  }
});</script>";*/

  }else {
	   echo "<script>alert('$email not registered');window.location='login';</script>";
  	 /*echo "<script> swal({
            title: 'Login Gagal!!',
            text: ' Klik Ok untuk Login kembali',
            type: 'warning'
        }, function(){
            window.location='login';
        });</script>";*/
  }

}
?>
