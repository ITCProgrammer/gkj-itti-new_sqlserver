<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";
$ip_num 	= $_SERVER['REMOTE_ADDR'];
$os			= $_SERVER['HTTP_USER_AGENT'];
$Usernm	    = $_SESSION['userGKJ'];
$Bon        = $_GET['bon'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="300" />
<title>Cancel Detail Bon</title>
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
                <th><div align="center">Action</div></th>
                </tr>
              </thead>
              <tbody> 
              <?php
              $sql = sqlsrv_query($con, "
                                          SELECT
                                              id, refno, nokk, langganan, no_po, no_order, warna, dept,
                                              no_lot, [status], jns_permintaan, ket,
                                              personil_buat,
                                              personil_periksa,
                                              CONVERT(varchar(10), tgl_periksa, 23)  AS tgl_periksa,
                                              personil_approve,
                                              CONVERT(varchar(10), tgl_approve, 23)  AS tgl_approve,
                                              personil_terima,
                                              CONVERT(varchar(10), tgl_terima, 23)   AS tgl_terima,
                                              personil_proses,
                                              CONVERT(varchar(10), tgl_proses, 23)   AS tgl_proses,
                                              personil_selesai,
                                              CONVERT(varchar(10), tgl_selesai, 23)  AS tgl_selesai,
                                              personil_cancel,
                                              CONVERT(varchar(10), tgl_cancel, 23)   AS tgl_cancel,
                                              CONVERT(varchar(10), tgl_buat, 102)    AS tgl_status,  -- yyyy.mm.dd (seperti MySQL %Y.%m.%d)
                                              CONVERT(varchar(10), tgl_buat, 23)     AS tgl_buat     -- yyyy-mm-dd (seperti MySQL %Y-%m-%d)
                                          FROM db_qc.tbl_bon_permintaan
                                          WHERE refno = ?
                                      ", [$Bon]);
              if ($sql === false) {
                die(print_r(sqlsrv_errors(), true));
              }

              $n=1;
              while($row =  sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
              ?>	  
              <tr>
              <td align="center"><?php echo $n; ?></td>
              <td align="center"><?php echo $row['nokk']; ?></td>
              <td align="center"><?php echo $row['langganan']; ?></td>
              <td align="center"><?php echo $row['no_po']; ?></td>
              <td align="center"><?php echo $row['no_order']; ?></td>
              <td align="center"><?php echo $row['warna']; ?></td>
              <td align="center"><?php echo $row['no_lot']; ?></td>
              <td align="center"><?php if($row['status']=="Baru"){echo "<span class='badge badge-info'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_buat']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_status']."</span>";}
              else if($row['status']=="Terima"){echo "<span class='badge badge-primary'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_terima']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_terima']."</span>";}
              else if($row['status']=="Sedang Proses"){echo "<span class='badge badge-warning blink_me'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_proses']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_proses']."</span>";}
              else if($row['status']=="Selesai"){echo "<span class='badge badge-success'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_selesai']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_selesai']."</span>";}
              else if($row['status']=="Approve"){echo "<span class='badge badge-success'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_approve']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_approve']."</span>";}
              else if($row['status']=="Check"){echo "<span class='badge badge-primary'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_periksa']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_periksa']."</span>";}
              else if($row['status']=="Cancel"){echo "<span class='badge badge-danger'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_cancel']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_cancel']."</span>";} ?>
              </td>
              <td align="center"><?php echo $row['tgl_buat']; ?></td>
              <td align="center"><?php echo $row['jns_permintaan']; ?></td>
              <td align="center"><div class="btn-group">
              <a href="#" class="btn btn-danger btn-xs" onclick="confirm_delete('./HapusDataDetailBon-<?php echo $row['id'] ?>-<?php echo trim($row['refno']) ?>-<?php echo $row['nokk'] ?>');"><i class="fa fa-times-circle" data-toggle="tooltip" data-placement="top" title="Cancel"></i> </a>
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
