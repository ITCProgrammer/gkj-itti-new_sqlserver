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
  <title>GKJ-ITTI | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">  
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">	
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
	body{
		/* "Calibri Light","serif" */
		font-family: Calibri, "sans-serif", "Source Sans Pro", "Courier New";		
		font-style: normal;
	}	
  </style>
  <link rel="icon" type="image/png" href="dist/img/ITTI_Logo index.ico">	
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
	<a href="#"><b>GKJ</b> ITTI</a>  
	<img src="dist/img/ITTI_LogoShape.png" alt="Logo Indotaichen">    
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input name="username" type="username" class="form-control form-control-sm" id="username" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input name="password" type="password" class="form-control form-control-sm" id="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!--<div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>-->
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-sm btn-primary float-right">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>      
		<p class="mb-1">
        <!-- <a href="ForgotPass">I forgot my password</a> -->
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
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>	
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
<?php
$ip_num = $_SERVER['REMOTE_ADDR'];
$os= $_SERVER['HTTP_USER_AGENT'];
if($_POST){ //login user
  extract($_POST);
  $username = mysqli_real_escape_string($con,$_POST['username']);
  $password = mysqli_real_escape_string($con,$_POST['password']);
  $passwordmd5 = md5($password);
  $sql=mysqli_query($con,"SELECT * FROM `tbl_user_gkj` WHERE `username`='$username' AND `password`='$passwordmd5' LIMIT 1");
  if(mysqli_num_rows($sql)>0)
  {  
  $r = mysqli_fetch_array($sql);
  $_SESSION['idGKJ'] 			= $r['id'];
  $_SESSION['userGKJ']	  = $r['username'];
  $_SESSION['passGKJ']	  = $password;
  $_SESSION['fotoGKJ']	  = $r['foto'];
  $_SESSION['jabatanGKJ'] = $r['jabatan'];
  $_SESSION['stsGKJ']			= $r['status'];
  $_SESSION['lvlGKJ']     = $r['level'];
	$_SESSION['subGKJ']     = $r['sub_dept'];
	$_SESSION['deptGKJ']    = $r['dept'];
	$_SESSION['emailGKJ']		= $r['email'];	  
  //login_validate();
    echo "<script>window.location='Home';</script>";
	/*echo "<script>swal({
  title: 'Login Success!!',
  text: 'Click Ok to continue',
  type: 'success',
  }).then((result) => {
  if (result.value) {
    window.location='Home';
  }
});</script>";*/
}else{
  echo "<script>
      $(function() {
    Swal.fire({
          type: 'error',
          title: 'Login Failed!',
          text: 'Wrong Email or Password!!'
        });
    });
    
  </script>";
} 
}else
if( $_GET['act']=="logout" ){ //logout user
//echo "<script>window.location='login';</script>";
echo "<script>
  	$(function() {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 6000
    });
	Toast.fire({
        type: 'success',
        title: 'Log out successful'
      });
  });
  
</script>";
}
unset($_SESSION['email']);
?>
