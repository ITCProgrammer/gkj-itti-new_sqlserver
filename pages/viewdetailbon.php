<?PHP
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
$ip_num 	= $_SERVER['REMOTE_ADDR'];
$os			= $_SERVER['HTTP_USER_AGENT'];
$Usernm	    = $_SESSION['userGKJ'];
$Bon        = $_GET['bon'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="300" />
<title>Detail Bon</title>
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">	
</head>

<body>
<section class="content">	
  <form role="form" method="post" enctype="multipart/form-data" name="form1">  
	  <div class="row">		  
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Data Detail Bon Permintaan</h3>	<br>
            No Bon : <?php echo $_GET['bon'];?>			
		      </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive">		  				
            <table id="example5" width="100%" class="table table-sm table-bordered table-hover tree" style="font-size: 13px;">
              <thead class="btn-success">
                <tr>
                <th><div align="center">No </div></th>
                <th><div align="center">No KK</div></th>
                <th><div align="center">Langganan</div></th>
                <th><div align="center">No PO</div></th>
                <th><div align="center">No Order</div></th>
                <th><div align="center">Warna</div></th>
                <th><div align="center">Lot</div></th>
                <th><div align="center">Status</div></th>
                <th><div align="center">Tgl Buka Bon</div></th>
                <th><div align="center">Permintaan</div></th>
                <th><div align="center">Keterangan</div></th>
                <th><div align="center">Action</div></th>
                </tr>
              </thead>
              <tbody> 
              <?php
              $sqldata = mysqli_query($con,"SELECT 
              id,refno, nokk, langganan, no_po, no_order, warna, dept,
              no_lot, `status`, jns_permintaan,ket,
              `personil_buat`,
              `personil_periksa`,
              DATE_FORMAT(tgl_periksa,'%Y-%m-%d') as tgl_periksa,
              `personil_approve`,
              DATE_FORMAT(tgl_approve,'%Y-%m-%d') as tgl_approve,
              `personil_terima`,
              DATE_FORMAT(tgl_terima,'%Y-%m-%d') as tgl_terima,
              `personil_proses`,
              DATE_FORMAT(tgl_proses,'%Y-%m-%d') as tgl_proses,
              `personil_selesai`,
              DATE_FORMAT(tgl_selesai,'%Y-%m-%d') as tgl_selesai,
              `personil_cancel`,
              DATE_FORMAT(tgl_cancel,'%Y-%m-%d') as tgl_cancel, 
              DATE_FORMAT(tgl_buat,'%Y.%m.%d') as tgl_status, 
              DATE_FORMAT(tgl_buat,'%Y-%m-%d') as tgl_buat
              FROM tbl_bon_permintaan WHERE refno='$_GET[bon]'");
              $n=1;
              while($row = mysqli_fetch_array($sqldata)){
                $sqlcount = "SELECT BALANCE.* FROM BALANCE BALANCE WHERE BALANCE.LOTCODE ='0000986400901'";
              ?>	  
              <tr>
              <td align="center"><?php echo $n; ?></td>
              <td align="center"><?php echo $row['nokk']; ?></td>
              <td align="center"><?php echo $row['langganan']; ?></td>
              <td align="center"><?php echo $row['no_po']; ?></td>
              <td align="center"><?php echo $row['no_order']; ?></td>
              <td align="center"><?php echo $row['warna']; ?></td>
              <td align="center"><?php echo $row['no_lot']; ?></td>
              <td align="center"><?php if($row['status']=="Baru"){echo "<span class='badge badge-secondary'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_buat']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_status']."</span>";}
              else if($row['status']=="Terima"){echo "<span class='badge badge-primary'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_terima']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_terima']."</span>";}
              else if($row['status']=="Sedang Proses"){echo "<span class='badge badge-warning blink_me'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_proses']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_proses']."</span>";}
              else if($row['status']=="Selesai"){echo "<span class='badge badge-success'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_selesai']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_selesai']."</span>";}
              else if($row['status']=="Approve"){echo "<span class='badge badge-info'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_approve']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_approve']."</span>";}
              else if($row['status']=="Check"){echo "<span class='badge badge-primary'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_periksa']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_periksa']."</span>";}
              else if($row['status']=="Cancel"){echo "<span class='badge badge-danger'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_cancel']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_cancel']."</span>";} ?>
              </td>
              <td align="center"><?php echo $row['tgl_buat']; ?></td>
              <td align="center"><?php echo $row['jns_permintaan']; ?></td>
              <td align="center"><?php echo $row['ket']; ?></td>
              <td align="center"><div class="btn-group">
              <?php if($_SESSION['deptGKJ']=="GKJ" OR $_SESSION['deptGKJ']=="DIT"){?>
              <a href="#" class="btn btn-success btn-xs update_nokk <?php if($row['status']=="Selesai" or $_SESSION['deptGKJ']!="GKJ" or $row['status']=="Cancel"){echo "disabled";} ?>" id="<?php echo $row['id']; ?>"><i class="fa fa-pen-square" data-toggle="tooltip" data-placement="top" title="Edit No KK"></i> </a>
              <?php }?> <a href="#" class="btn btn-primary btn-xs update_ket <?php if($row['status']=="Selesai" or $row['status']=="Cancel"){echo "disabled";} ?>" id="<?php echo $row['id']; ?>"><i class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="Edit Keterangan"></i> </a>
              </div></td>
              </tr>
              <?php $n++;} ?>
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
<div id="UpdateKet" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div id="UpdateNoKK" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- /.content -->
<div class="modal fade" id="modal_del" tabindex="-1" >
    <div class="modal-dialog" >
        <div class="modal-content" style="margin-top:100px;">
            <div class="modal-header">
                <h4 class="modal-title">Apakah Anda Yakin Ingin Cancel KK Ini ?</h4>
                <button type="button" class="close"  data-dismiss="modal" aria-hidden="true">&times;</button>
                
            </div>
            <div class="modal-footer justify-content-between">
                <a href="#" class="btn btn-danger" id="delete_link">Ya</a>
                <button type="button" class="btn btn-success" data-dismiss="modal">Tidak</button>
            </div>
        </div>
    </div>
</div>
</html>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<script type="text/javascript">
    function confirm_delete(delete_url)
    {
      $('#modal_del').modal('show', {backdrop: 'static'});
      document.getElementById('delete_link').setAttribute('href' , delete_url);
    }
</script>
<script>
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});
</script>
