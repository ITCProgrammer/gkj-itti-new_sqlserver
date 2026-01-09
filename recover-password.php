<?php
ini_set("error_reporting", 1);
session_start();
//require_once "waktu.php";
include"koneksi.php";
?>
<?php
//set base constant
if( !isset($_SESSION['email'])) { ?>
<script>setTimeout("location.href='login'",500);</script>
<?php die( '' ); } ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MKT-ITTI | Recover Password</title>
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
      <p class="login-box-msg">You are only one step a way from your new password, recover your password now. <?php echo $_SESSION['email']; ?></p>
	  	
      <form action="" method="post" enctype="multipart/form-data">
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="passNew" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Confirm Password" name="passRe">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Change password</button>
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
	    $passNew = mysqli_real_escape_string($con,$_POST['passNew']);
		$passRe	  =	mysqli_real_escape_string($con,$_POST['passRe']);
	$sql=mysqli_query($con,"SELECT count(*) jml FROM user_login WHERE `email`='$_SESSION[email]'  LIMIT 1");
	$dt=mysqli_fetch_array($sql);
	if($dt['jml']>0){
		if($passNew!=$passRe)
		{
			echo " <script>alert('Not Match Re-New Password!!');</script>";
			}else
			{
				$sqlupdate=mysqli_query($con,"UPDATE user_login SET `password`='$passNew' WHERE `email`='$_SESSION[email]' LIMIT 1");				
				unset($_SESSION['email']);
			    echo " <script>alert('Password has been Changed!!');window.location='login';</script>";
				}
		
		}
	
}

?>
