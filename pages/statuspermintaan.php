<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";
$ip_num = $_SERVER['REMOTE_ADDR'];
$os= $_SERVER['HTTP_USER_AGENT'];
$Dept	    = $_SESSION['deptGKJ'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="300" />
<title>Status Permintaan</title>
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">	
</head>

<body>
<section class="content">	
  <form role="form" method="post" enctype="multipart/form-data" name="form1">
  <?php if($_SESSION['deptGKJ']=="GKJ" OR $_SESSION['deptGKJ']=="DIT"){}else{ ?>
  <div class="alert alert-warning alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h4><i class="icon fa fa-info"></i> Informasi</h4>

				<p>Jika Bon Sudah Berstatus Approve, Harap Kartu kerja Diantar ke Dept. GKJ Agar Segera Diproses.</p>
	</div>
  <?php } ?>  
	  <div class="row">		  
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Data Bon Permintaan</h3>				
		      </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive">		  				
            <table id="example5" width="100%" class="table table-sm table-bordered table-hover tree" style="font-size: 13px;">
              <thead class="btn-success">
                <tr>
                  <th><div align="center">No </div></th>
                  <th><div align="center">No Bon</div></th>
                  <th><div align="center">Dept</div></th>
                  <th><div align="center">Status</div></th>
                  <th><div align="center">Tgl Buka Bon</div></th>
                  <th><div align="center">Jenis Permintaan</div></th>
                  <th><div align="center">Keterangan</div></th>
                  <th><div align="center">Action</div></th>
                </tr>
              </thead>
              <tbody> 
              <?php
                if($Dept=='GKJ' OR $Dept=='DIT'){
                  $dept=" ";
                }elseif($Dept=='PPC' OR $Dept=='MKT'){
                  $dept=" AND (dept='PPC' OR dept='MKT') ";
                }else{ 
                  $dept=" AND dept='$Dept' ";
                }
                $sql 	= mysqli_query($con,"SELECT
                  id,	
                  dept,
                  refno,
                  jns_permintaan,
                  count( refno ) AS jmlkk,
                  GROUP_CONCAT( DISTINCT nokk SEPARATOR ', ' ) AS nokk,
                  GROUP_CONCAT( DISTINCT `status` SEPARATOR ', ') AS `status`,
                  GROUP_CONCAT( DISTINCT `personil_buat` SEPARATOR ', ') AS `personil_buat`,
                  GROUP_CONCAT( DISTINCT `personil_periksa` SEPARATOR ', ') AS `personil_periksa`,
                  DATE_FORMAT(tgl_periksa,'%Y-%m-%d') as tgl_periksa,
                  GROUP_CONCAT( DISTINCT `personil_approve` SEPARATOR ', ') AS `personil_approve`,
                  DATE_FORMAT(tgl_approve,'%Y-%m-%d') as tgl_approve,
                  GROUP_CONCAT( DISTINCT `personil_terima` SEPARATOR ', ') AS `personil_terima`,
                  DATE_FORMAT(tgl_terima,'%Y-%m-%d') as tgl_terima,
                  GROUP_CONCAT( DISTINCT `personil_proses` SEPARATOR ', ') AS `personil_proses`,
                  DATE_FORMAT(tgl_proses,'%Y-%m-%d') as tgl_proses,
                  GROUP_CONCAT( DISTINCT `personil_selesai` SEPARATOR ', ') AS `personil_selesai`,
                  DATE_FORMAT(tgl_selesai,'%Y-%m-%d') as tgl_selesai,
                  GROUP_CONCAT( DISTINCT `personil_cancel` SEPARATOR ', ') AS `personil_cancel`,
                  DATE_FORMAT(tgl_cancel,'%Y-%m-%d') as tgl_cancel,
                  tgl_update,
                  DATE_FORMAT(tgl_buat,'%Y.%m.%d') as tgl_buat
                FROM
                  tbl_bon_permintaan
                WHERE
                  not ISNULL( refno ) $dept AND `status`!='Selesai'
                GROUP BY
                  refno
                ORDER BY
                  id DESC");
              $n=1;
              while($row=mysqli_fetch_array($sql)){
					    ?>	  
              <tr>
              <td align="center"><?php echo $n; ?></td>
              <td align="center"><a href="TimelineUser-<?php echo $row['refno'];?>" target="_blank"><?php echo $row['refno'];?></a></td>
              <td align="center"><?php echo $row['dept']; ?></td>
              <td align="center"><?php if($row['status']=="Baru"){echo "<span class='badge badge-secondary'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_buat']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_buat']."</span>";}
              else if($row['status']=="Terima"){echo "<span class='badge badge-primary'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_terima']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_terima']."</span>";}
              else if($row['status']=="Sedang Proses"){echo "<span class='badge badge-warning blink_me'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_proses']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_proses']."</span>";}
              else if($row['status']=="Selesai"){echo "<span class='badge badge-success'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_selesai']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_selesai']."</span>";}
              else if($row['status']=="Approve"){echo "<span class='badge badge-info'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_approve']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_approve']."</span>";}
              else if($row['status']=="Check"){echo "<span class='badge badge-primary'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_periksa']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_periksa']."</span>";}
              else if($row['status']=="Cancel"){echo "<span class='badge badge-danger'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_cancel']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_cancel']."</span>";} ?>
              </td>
              <td align="center"><?php echo $row['tgl_update']; ?></td>
              <td align="center">
              <?php
                $sqlket= mysqli_query($con,"SELECT DISTINCT(jns_permintaan) FROM tbl_bon_permintaan WHERE refno='$row[refno]'");
                while($rket=mysqli_fetch_array($sqlket)){
              ?>
              <?php if($rket['jns_permintaan']=="Bongkaran"){echo "<span class='badge badge-warning'>".$rket['jns_permintaan']."</span>";}
              else if($rket['jns_permintaan']=="Potong Sample"){echo "<span class='badge badge-success'>".$rket['jns_permintaan']."</span>";}
              else if($rket['jns_permintaan']=="Potong Pass Qty"){echo "<span class='badge badge-primary'>".$rket['jns_permintaan']."</span>";}
              else if($rket['jns_permintaan']=="Potong Sisa"){echo "<span class='badge badge-info'>".$rket['jns_permintaan']."</span>";}
              else if($rket['jns_permintaan']=="Ganti Grade"){echo "<span class='badge badge-secondary'>".$rket['jns_permintaan']."</span>";}
              else if($rket['jns_permintaan']=="Bon Sample"){echo "<span class='badge badge-danger'>".$rket['jns_permintaan']."</span>";}
                  } ?>
              </td>
              <td align="left">
                  <?php

                    $sqlbon=mysqli_query($con,"SELECT nokk, jns_permintaan, ket FROM tbl_bon_permintaan WHERE refno='$row[refno]'");
                    while($rbon=mysqli_fetch_array($sqlbon)){
                  ?>
                  <?php echo $rbon['nokk'].", ".$rbon['jns_permintaan'].", ".$rbon['ket']; ?><br>
                  <?php }?>
                  </td>
              <td align="center"><div class="btn-group">
              <a href="pages/cetak/bon-permintaan.php?bon=<?php echo $row['refno']; ?>&tgl=<?php echo substr($row['tgl_update'],0,10); ?>" class="btn btn-primary btn-xs <?php if($row['status']=="Approve" or $row['status']=="Check" or $row['status']=="Selesai" or $row['status']=="Baru" or $row['status']=="Cancel" or $row['status']=="Terima"){ echo "disabled"; } ?>" target="_blank"><i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="Cetak"></i> </a>
              <a href="CancelDetailBon-<?php echo $row['refno']; ?>" class="btn btn-danger btn-xs <?php if($row['status']=="Cancel" or $row['status']=="Selesai" or $row['status']=="Sedang Proses" or $_SESSION['deptGKJ']!="$row[dept]"){ echo "disabled"; } ?>" ><i class="fa fa-times-circle" data-toggle="tooltip" data-placement="top" title="Cancel"></i> </a>
              <a href="TambahDetailBonUser-<?php echo $row['refno']; ?>" class="btn btn-info btn-xs <?php if($row['status']=="Check" or $row['status']=="Selesai" or $row['status']=="Baru" or $row['status']=="Cancel" or $row['status']=="Terima" or $row['status']=="Sedang Proses" or $_SESSION['deptGKJ']!="$row[dept]" or $_SESSION['userGKJ']!="$row[personil_buat]"){ echo "disabled"; } ?>"><i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="Tambah Detail"></i> </a>
              <a href="ViewDetailBon-<?php echo $row['refno']; ?>" class="btn btn-success btn-xs" ><i class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="View Detail"></i> </a>
              <!--<a href="#" class="btn btn-success btn-xs <?php if($row['status']=="Sedang Proses" or $row['status']=="Terima" or $row['status']=="Baru" or $row['status']=="Selesai"){ echo "disabled"; } ?>" onclick="confirm_terima('TerimaBon-<?php echo $row['refno']; ?>-<?php echo $row['tgl_buat']; ?>');"><i class="fa fa-check-circle" data-toggle="tooltip" data-placement="top" title="Terima"></i> </a>-->
              <!-- <a href="#" class="btn btn-info btn-xs <?php if($_SESSION['jabatanGKJ']=="Staff" or $_SESSION['deptGKJ']!="$row[dept]" or $row['status']=="Terima" or $row['status']=="Sedang Proses" or $row['status']=="Approve" or $row['status']=="Selesai" or $row['status']=="Check" or $row['status']=="Cancel"){ echo "disabled"; } ?>" onclick="confirm_check('CheckBon-<?php echo $row['refno']; ?>-<?php echo $row['tgl_buat']; ?>-<?php echo $_SESSION['userGKJ']; ?>-<?php echo $_SESSION['jabatanGKJ']; ?>');"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Check"></i> </a> -->
              <!-- <a href="#" class="btn btn-success btn-xs <?php if($_SESSION['jabatanGKJ']=="Staff" or $_SESSION['deptGKJ']!="$row[dept]" or $row['status']=="Terima" or $row['status']=="Sedang Proses" or $row['status']=="Baru" or $row['status']=="Selesai" or $row['status']=="Approve" or $row['status']=="Cancel"){ echo "disabled"; } ?>" onclick="confirm_approve('ApproveBon-<?php echo $row['refno']; ?>-<?php echo $row['tgl_buat']; ?>-<?php echo $_SESSION['userGKJ']; ?>-<?php echo $_SESSION['jabatanGKJ']; ?>');"><i class="fa fa-check" data-toggle="tooltip" data-placement="top" title="Approve"></i> </a> -->
              <!-- <a href="#" class="btn btn-warning btn-xs <?php if($_SESSION['jabatanGKJ']=="Staff" or $_SESSION['deptGKJ']!="$row[dept]" or $row['status']=="Baru" or $row['status']=="Terima" or $row['status']=="Approve" or $row['status']=="Selesai" or $row['status']=="Check" or $row['status']=="Cancel"){ echo "disabled"; } ?>" onclick="confirm_selesai('SelesaiBon-<?php echo $row['refno']; ?>-<?php echo $row['tgl_buat']; ?>-<?php echo $_SESSION['userGKJ']; ?>-<?php echo $_SESSION['jabatanGKJ']; ?>');"><i class="fa fa-flag-checkered" data-toggle="tooltip" data-placement="top" title="Selesai"></i> </a></div></td> -->
              </tr>
              <?php $n++;}?>
              </tbody>
            </table>
				  </div>	
          <!-- /.card-body -->
        </div>
        <!-- /.card -->   
      </div>
      <!-- /.col --> 
    </div>
    <!-- /.row -->
	</form>
</section>
<!-- /.content -->
<!-- Modal Popup untuk terima bon-->
<div class="modal fade" id="terimaBon" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:100px;">
      <div class="modal-header">
				<h4 class="modal-title">INFOMATION</h4>  
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
			<div class="modal-body">
				<h5 class="modal-title" style="text-align:center;"><span class='badge badge-primary'>Terima</span> Bon Permintaan ?</h5>
			</div>	
      <div class="modal-footer justify-content-between">
        <a href="#" class="btn btn-success" id="terima_link">Yes</a>
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>	
<!-- Modal Popup untuk selesai bon-->
<div class="modal fade" id="selesaiBon" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:100px;">
      <div class="modal-header">
				<h4 class="modal-title">INFOMATION</h4>  
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
			<div class="modal-body">
				<h5 class="modal-title" style="text-align:center;">Bon Permintaan Telah <span class='badge badge-success'>Selesai</span>?</h5>
			</div>	
      <div class="modal-footer justify-content-between">
        <a href="#" class="btn btn-success" id="selesai_link">Yes</a>
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Popup untuk approve bon-->
<div class="modal fade" id="approveBon" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:100px;">
      <div class="modal-header">
				<h4 class="modal-title">INFOMATION</h4>  
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>            
      </div>
			<div class="modal-body">
				<h5 class="modal-title" style="text-align:center;">Bon Permintaan Akan <span class='badge badge-success'>Diapprove</span>?</h5>
			</div>	
      <div class="modal-footer justify-content-between">
        <a href="#" class="btn btn-success" id="approve_link">Yes</a>
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Popup untuk check bon-->
<div class="modal fade" id="checkBon" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:100px;">
      <div class="modal-header">
				<h4 class="modal-title">INFOMATION</h4>  
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                    
      </div>
			<div class="modal-body">
				<h5 class="modal-title" style="text-align:center;">Bon Permintaan Telah <span class='badge badge-success'>Dicheck</span>?</h5>
			</div>	
      <div class="modal-footer justify-content-between">
        <a href="#" class="btn btn-success" id="check_link">Yes</a>
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>		
</body>
</html>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<script type="text/javascript">
function confirm_terima(terima_url) {
$('#terimaBon').modal('show', {
backdrop: 'static'
});
document.getElementById('terima_link').setAttribute('href', terima_url);
}
function confirm_selesai(selesai_url) {
$('#selesaiBon').modal('show', {
backdrop: 'static'
});
document.getElementById('selesai_link').setAttribute('href', selesai_url);
}
function confirm_approve(approve_url) {
$('#approveBon').modal('show', {
backdrop: 'static'
});
document.getElementById('approve_link').setAttribute('href', approve_url);
}
function confirm_check(check_url) {
$('#checkBon').modal('show', {
backdrop: 'static'
});
document.getElementById('check_link').setAttribute('href', check_url);
}
</script>
<script>
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});
</script>
