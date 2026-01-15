<?php
include("../koneksi.php");
session_start();

$ip       = $_SERVER['REMOTE_ADDR'];
$useredit = $_SESSION['userGKJ'];

$nokk = isset($_POST['nokk']) ? $_POST['nokk'] : '';
$id   = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$bon  = isset($_POST['bon']) ? $_POST['bon'] : '';
$ket  = isset($_POST['ket']) ? $_POST['ket'] : '';

// UPDATE ket (SQL Server)
$sqlupdate = "
  UPDATE db_qc.tbl_bon_permintaan
  SET ket = ?
  WHERE id = ?
";
$okUpd = sqlsrv_query($con, $sqlupdate, [$ket, $id]);
if ($okUpd === false) {
	die(print_r(sqlsrv_errors(), true));
}

// INSERT log (SQL Server)
$sqlInsert = "
  INSERT INTO db_qc.tbl_log_bon_gkj
    (proses, detail_proses, [user], waktu_proses, ip)
  VALUES (?, ?, ?, GETDATE(), ?)
";
$okLog = sqlsrv_query($con, $sqlInsert, [
	'Edit Keterangan',
	"Edit Ket dengan No KK:$nokk ",
	$useredit,
	$ip
]);
if ($okLog === false) {
	die(print_r(sqlsrv_errors(), true));
}

// Redirect
echo "<script>window.location='ViewDetailBon-$bon';</script>";
exit;
?>
