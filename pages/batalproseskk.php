<?PHP
include_once 'controllers/permintaanClass.php';
// instance objek
$permintaan = new Permintaan();
$ip_num 	= $_SERVER['REMOTE_ADDR'];
$os			= $_SERVER['HTTP_USER_AGENT'];
$Usernm	    = $_SESSION['userGKJ'];
$Nokk        = $_GET['nokk'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="300" />
<title>Detail KK</title>
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
            <h3 class="card-title">Data Detail KK</h3>	<br>
            No KK : <?php echo $_GET['nokk'];?>			
		      </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive">		  				
            <table id="example5" width="100%" class="table table-sm table-bordered table-hover tree" style="font-size: 13px;">
              <thead class="btn-success">
                <tr>
                <th><div align="center">No </div></th>
                <th><div align="center">SN</div></th>
                <th><div align="center">No Roll</div></th>
                <th><div align="center">Satuan</div></th>
                <th><div align="center">KG</div></th>
                <th><div align="center">Keterangan</div></th>
                <th><div align="center">Action</div></th>
                </tr>
              </thead>
              <tbody> 
              <?php
              if (is_array($permintaan->tampildatakirim($_GET['nokk'])) || is_object($permintaan->tampildatakirim($_GET['nokk']))){
              $n=1;
              foreach($permintaan->tampildatakirim($_GET['nokk']) as $row){
                foreach($permintaan->tampildetaildatakirim($_GET['nokk'], $row['SN']) as $r){}
              ?>	  
              <tr>
              <td align="center"><?php echo $n; ?></td>
              <td align="center"><?php echo $row['SN']; ?></td>
              <td align="center"><?php echo $row['no_roll']; ?></td>
              <td align="center"><?php echo $row['satuan']; ?></td>
              <td align="center"><?php echo number_format($row['weight'],'2'); ?></td>
              <td align="center"><?php if($r['id']!=""){echo "OK";}else{echo "NULL";}?></td>
              <td align="center">
              <a href="#" class="btn btn-danger btn-xs <?php if($row['status']=="Selesai"){ echo "disabled"; } ?>" onclick="confirm_batal('BatalKK-<?php echo $row['id']; ?>-<?php echo $row['nokk']; ?>-<?php echo $row['SN']; ?>-<?php echo $_SESSION['userGKJ']; ?>-<?php echo $r['id']; ?>');"><i class="fa fa-times-circle" data-toggle="tooltip" data-placement="top" title="Batal KK"></i> </a>
              </td>
              </tr>
              <?php $n++;} }?>
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
<!-- Modal Popup untuk terima bon-->
<div class="modal fade" id="BatalKK" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:100px;">
      <div class="modal-header">
				<h4 class="modal-title">INFORMATION</h4>  
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
			<div class="modal-body">
				<h5 class="modal-title" style="text-align:center;"><span class='badge badge-danger'>Batal Proses</span> KK ?</h5>
			</div>	
      <div class="modal-footer justify-content-between">
        <a href="#" class="btn btn-danger" id="batalkk_link">Yes</a>
        <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
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
    function confirm_batal(batalkk_url) {
        $('#BatalKK').modal('show', {
        backdrop: 'static'
        });
        document.getElementById('batalkk_link').setAttribute('href', batalkk_url);
    }
</script>
<script>
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});
</script>
