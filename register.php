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
  <title>GKJ-ITTI | Registration Page</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">	
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
	body{
		/* "Calibri Light","serif" */
		font-family: Calibri, "sans-serif", "Source Sans Pro", "Courier New";		
		font-style: normal;
	}	
  </style>
	<link rel="icon" type="image/png" href="dist/img/index.ico">	
	
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <img src="dist/img/index.ico" alt="Logo Indotaichen">  
    <a href="#"><b>Marketing</b> ITTI</a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Register a new membership</p>

      <form action="" method="post" enctype="multipart/form-data">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Full name" name="user">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Retype password" name="repassword">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
		<div class="input-group mb-3">
		  <select class="form-control select2" name="dept" id="dept" required>
			<option value=""></option>  
		    <option value="MKT">MKT</option>
		    <option value="PDC">PDC</option>
		    <option value="RMP">RMP</option>
		    <option value="KNT">KNT</option>
		    <option value="TAS">TAS</option>
		    <option value="QCF">QCF</option>
		    <option value="FIN">FIN</option>
		    <option value="BRS">BRS</option>
		    <option value="DYE">DYE</option>
		    <option value="LAB">LAB</option>
			<option value="YND">YND</option>
			<option value="GKG">GKG</option> 
			<option value="PRT">PRT</option>
			<option value="PPC">PPC</option>  
          </select>	
          
        </div>  
        <div class="row">
          <div class="col-8">
            <!--<!--<div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>-->
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      
      <a href="login" class="text-center">I already have a membership</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>	
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
	
<script>
$(function () { 	
$(".select2").select2({
      theme: 'bootstrap4',
	  placeholder: "Select",
      allowClear: true,	
    });
});
</script>	
</body>
</html>
<?php
if($_POST){ //login user
	extract($_POST);
	    $passNew = mysqli_real_escape_string($con,$_POST['password']);
		$passRe	  =	mysqli_real_escape_string($con,$_POST['repassword']);
		$user	  =	mysqli_real_escape_string($con,$_POST['user']);
		$email	  =	mysqli_real_escape_string($con,$_POST['email']);
		$dept	  =	mysqli_real_escape_string($con,$_POST['dept']);
	$sql=mysqli_query($con,"SELECT count(*) jml FROM user_login WHERE `email`='$email'  LIMIT 1");
	$dt=mysqli_fetch_array($sql);
	if($dt['jml']>0){
		echo " <script>alert('Email Already Registered');</script>";
	}else{
		if($passNew!=$passRe)
		{
			echo " <script>alert('Not Match Re-New Password!!');</script>";
			}else
			{
				$sqlinsert=mysqli_query($con,"INSERT INTO user_login SET 
				`user`='$user',
				`password`='$passNew',
				`email`='$email',
				`level`='ADMIN',
				`dept`='$dept',
				`akses`='admin',
				`status`='Aktif'
				");				
				echo " <script>alert('User Has Registered, Please Login');window.location='login';</script>";
				}
		
		}
	
}

?>
