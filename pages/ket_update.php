<?php
include("../koneksi.php");
session_start();
$ip= $_SERVER['REMOTE_ADDR'];
$useredit=$_SESSION['userGKJ'];
$nokk=$_POST['nokk'];
$sqlupdate 	= mysqli_query($con,"UPDATE tbl_bon_permintaan SET ket='$_POST[ket]' WHERE id='$_POST[id]'");
  
$sqlInsert=mysqli_query($con,"INSERT INTO tbl_log_bon_gkj SET
proses='Edit Keterangan',
detail_proses='Edit Ket dengan No KK:$nokk ',
user='$useredit',
waktu_proses=now(),
ip='$ip'");

	if($sqlupdate){
  		echo "<script>window.location='ViewDetailBon-$_POST[bon]';</script>";
	}else{
		echo "Update Data Gagal";
	}
?>
