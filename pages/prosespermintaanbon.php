<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";
$ip_num = $_SERVER['REMOTE_ADDR'];
$os= $_SERVER['HTTP_USER_AGENT'];
$Usernm	    = $_SESSION['userGKJ'];
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
    <?php 
    $sqln = sqlsrv_query(
      $con,
      "SELECT COUNT(*) AS cnt FROM (
        SELECT refno
        FROM db_qc.tbl_bon_permintaan
        WHERE [status] IN ('Approve', 'Terima')
        GROUP BY refno
      ) x"
    );
    $rn = sqlsrv_fetch_array($sqln, SQLSRV_FETCH_ASSOC);
    ?>
    <?php if($rn['cnt']>0){ ?>
    <div class="alert alert-warning alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h4><i class="icon fa fa-info"></i> Informasi</h4>

				<p>Terdapat <strong><?php echo $rn['cnt'];?> Bon</strong> yang Siap Proses, Mohon Diperiksa.</p>
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
                    <th><div align="center">Tgl Proses GKJ</div></th>
                    <th><div align="center">Jenis Permintaan</div></th>
                    <th><div align="center">Keterangan</div></th>
                    <th><div align="center">Action</div></th>
                  </tr>
                </thead>
                <tbody> 
                <?php
                $sqlText = "WITH base AS (
                  SELECT *,
                  LTRIM(RTRIM(refno)) AS refno_trim 
                  FROM db_qc.tbl_bon_permintaan
                  WHERE refno IS NOT NULL AND [status] <> 'Selesai'
                )
                SELECT
                  MAX(id) AS id,
                  MAX(dept) AS dept,
                  b.refno_trim AS refno,
                  MAX(jns_permintaan) AS jns_permintaan,
                  COUNT(b.refno_trim) AS jmlkk,
                  STUFF((
                    SELECT DISTINCT ', ' + b2.nokk
                    FROM base b2
                    WHERE b2.refno_trim = b.refno_trim AND b2.nokk IS NOT NULL
                    FOR XML PATH(''), TYPE
                  ).value('.', 'varchar(max)'), 1, 2, '') AS nokk,
                  STUFF((
                    SELECT DISTINCT ', ' + b2.[status]
                    FROM base b2
                     WHERE b2.refno_trim = b.refno_trim AND b2.[status] IS NOT NULL
                    FOR XML PATH(''), TYPE
                  ).value('.', 'varchar(max)'), 1, 2, '') AS [status],
                  STUFF((
                    SELECT DISTINCT ', ' + b2.personil_buat
                    FROM base b2
                    WHERE b2.refno_trim = b.refno_trim AND b2.personil_buat IS NOT NULL
                    FOR XML PATH(''), TYPE
                  ).value('.', 'varchar(max)'), 1, 2, '') AS personil_buat,
                  STUFF((
                    SELECT DISTINCT ', ' + b2.personil_periksa
                    FROM base b2
                    WHERE b2.refno_trim = b.refno_trim AND b2.personil_periksa IS NOT NULL
                    FOR XML PATH(''), TYPE
                  ).value('.', 'varchar(max)'), 1, 2, '') AS personil_periksa,
                  CONVERT(varchar(10), MAX(tgl_periksa), 23) AS tgl_periksa,
                  STUFF((
                    SELECT DISTINCT ', ' + b2.personil_approve
                    FROM base b2
                    WHERE b2.refno_trim = b.refno_trim AND b2.personil_approve IS NOT NULL
                    FOR XML PATH(''), TYPE
                  ).value('.', 'varchar(max)'), 1, 2, '') AS personil_approve,
                  CONVERT(varchar(10), MAX(tgl_approve), 23) AS tgl_approve,
                  STUFF((
                    SELECT DISTINCT ', ' + b2.personil_terima
                    FROM base b2
                    WHERE b2.refno_trim = b.refno_trim AND b2.personil_terima IS NOT NULL
                    FOR XML PATH(''), TYPE
                  ).value('.', 'varchar(max)'), 1, 2, '') AS personil_terima,
                  CONVERT(varchar(10), MAX(tgl_terima), 23) AS tgl_terima,
                  STUFF((
                    SELECT DISTINCT ', ' + b2.personil_proses
                    FROM base b2
                    WHERE b2.refno_trim = b.refno_trim AND b2.personil_proses IS NOT NULL
                    FOR XML PATH(''), TYPE
                  ).value('.', 'varchar(max)'), 1, 2, '') AS personil_proses,
                  CONVERT(varchar(10), MAX(tgl_proses), 23) AS tgl_proses,
                  STUFF((
                    SELECT DISTINCT ', ' + b2.personil_selesai
                    FROM base b2
                    WHERE b2.refno_trim = b.refno_trim AND b2.personil_selesai IS NOT NULL
                    FOR XML PATH(''), TYPE
                  ).value('.', 'varchar(max)'), 1, 2, '') AS personil_selesai,
                  CONVERT(varchar(10), MAX(tgl_selesai), 23) AS tgl_selesai,
                  STUFF((
                    SELECT DISTINCT ', ' + b2.personil_cancel
                    FROM base b2
                    WHERE b2.refno_trim = b.refno_trim AND b2.personil_cancel IS NOT NULL
                    FOR XML PATH(''), TYPE
                  ).value('.', 'varchar(max)'), 1, 2, '') AS personil_cancel,
                  CONVERT(varchar(10), MAX(tgl_cancel), 23) AS tgl_cancel,
                  CONVERT(varchar(19), MAX(tgl_update), 120) AS tgl_update,
                  CONVERT(varchar(10), MIN(tgl_buat), 23) AS tgl_buat,
                  MAX(potong_null) AS potong_null
                FROM base b
                GROUP BY b.refno_trim
                ORDER BY MAX(id) DESC";
                $sql = sqlsrv_query($con, $sqlText);
                $n=1;
                while($row=sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
                ?>	  
                <tr>
                  <td align="center"><?php echo $n; ?></td>
                  <td align="center"><a href="Timeline-<?php echo $row['refno'];?>" target="_blank"><?php echo $row['refno'];?></a></td>
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
                  <td align="center"><a href="#" class="btn btn-warning btn-xs edit_tglproses <?php if($row['tgl_proses']!="" AND $_SESSION['lvlGKJ']=="admin" OR $_SESSION['lvlGKJ']=="superadmin"){}else{echo "disabled";} ?>" refno="<?php echo trim($row['refno']); ?>"><?php if($row['tgl_proses']!=""){echo $row['tgl_proses'];}else{echo "Belum Diproses";} ?></a></td>
                  <td align="center">
                  <?php
                    $sqlket = sqlsrv_query($con, "SELECT DISTINCT(jns_permintaan) AS jns_permintaan FROM db_qc.tbl_bon_permintaan WHERE refno = ?", array(trim($row['refno'])));
                    while($rket=sqlsrv_fetch_array($sqlket, SQLSRV_FETCH_ASSOC)){
                  ?>	  
                  <?php if($rket['jns_permintaan']=="Bongkaran"){?>
                  <a href="ProsesBongkaranBon-<?php echo trim($row['refno']); ?>-<?php echo $rket['jns_permintaan']; ?>-<?php echo $row['id']; ?>" class="btn btn-warning btn-xs <?php if($row['status']=="Baru" or $row['status']=="Approve" or $row['status']=="Selesai" or $row['status']=="Terima" or $row['status']=="Check" or $row['status']=="Cancel"){echo "disabled";} ?>" ><?php echo $rket['jns_permintaan']; ?></a>
                  <?php } ?>
                  <?php if($rket['jns_permintaan']=="Potong Sample"){?>
                  <a href="ProsesBon-<?php echo trim($row['refno']); ?>-<?php echo $rket['jns_permintaan']; ?>-<?php echo $row['id']; ?>" class="btn btn-success btn-xs <?php if($row['status']=="Baru" or $row['status']=="Approve" or $row['status']=="Selesai" or $row['status']=="Terima" or $row['status']=="Check" or $row['status']=="Cancel"){echo "disabled";} ?>" ><?php echo $rket['jns_permintaan']; ?></a>
                  <?php } ?>
                  <?php if($rket['jns_permintaan']=="Potong Pass Qty"){?>
                  <a href="ProsesBon-<?php echo trim($row['refno']); ?>-<?php echo $rket['jns_permintaan']; ?>-<?php echo $row['id']; ?>" class="btn btn-primary btn-xs <?php if($row['status']=="Baru" or $row['status']=="Approve" or $row['status']=="Selesai" or $row['status']=="Terima" or $row['status']=="Check" or $row['status']=="Cancel"){echo "disabled";} ?>" ><?php echo $rket['jns_permintaan']; ?></a>
                  <?php } ?>
                  <?php if($rket['jns_permintaan']=="Potong Sisa"){?>
                  <a href="ProsesPotongSisa-<?php echo trim($row['refno']); ?>-<?php echo $rket['jns_permintaan']; ?>-<?php echo $row['id']; ?>" class="btn btn-info btn-xs <?php if($row['status']=="Baru" or $row['status']=="Approve" or $row['status']=="Selesai" or $row['status']=="Terima" or $row['status']=="Check" or $row['status']=="Cancel"){echo "disabled";} ?>" ><?php echo $rket['jns_permintaan']; ?></a>
                  <?php } ?>
                  <?php if($rket['jns_permintaan']=="Ganti Grade"){?>
                  <a href="ProsesGantiGrade-<?php echo trim($row['refno']); ?>-<?php echo $rket['jns_permintaan']; ?>-<?php echo $row['id']; ?>" class="btn btn-secondary btn-xs <?php if($row['status']=="Baru" or $row['status']=="Approve" or $row['status']=="Selesai" or $row['status']=="Terima" or $row['status']=="Check" or $row['status']=="Cancel"){echo "disabled";} ?>" ><?php echo $rket['jns_permintaan']; ?></a>
                  <?php } ?>
                  <?php if($rket['jns_permintaan']=="Bon Sample"){?>
                    <span class='badge badge-danger'><?php echo $rket['jns_permintaan'];?></span>
                  <?php } ?>
                  <!--<?php if($row['jns_permintaan']=="Bongkaran"){echo "<span class='badge badge-warning'>".$row['jns_permintaan']."</span>";}else if($row['jns_permintaan']=="Potong Sample"){echo "<span class='badge badge-success'>".$row['jns_permintaan']."</span>";}else if($row['jns_permintaan']=="Potong Pass Qty"){echo "<span class='badge badge-primary'>".$row['jns_permintaan']."</span>";}?>-->
                  <?php } ?>
                  </td>
                  <td align="left">
                  <?php
                    $sqlbon = sqlsrv_query($con, "SELECT nokk, jns_permintaan, ket, potong_null FROM db_qc.tbl_bon_permintaan WHERE refno = ?", array($row['refno']));
                    while($rbon=sqlsrv_fetch_array($sqlbon, SQLSRV_FETCH_ASSOC)){
                  ?>
                  <?php echo $rbon['nokk'].", ".$rbon['jns_permintaan'].", ".$rbon['ket']; ?><br>
                  <?php } ?>
                  </td>
                  <td align="center"><div class="btn-group">
                  <!-- <a href="#" class="btn btn-danger btn-xs <?php if($row['status']=="Cancel" or $row['status']=="Selesai" or $row['status']=="Sedang Proses"){ echo "disabled"; } ?>" onclick="confirm_cancel('CancelBon-<?php echo $_SESSION['userGKJ']; ?>-<?php echo $row['refno']; ?>-<?php echo $row['tgl_buat']; ?>-<?php echo $_SESSION['jabatanGKJ']; ?>');"><i class="fa fa-times-circle" data-toggle="tooltip" data-placement="top" title="Cancel"></i> </a> -->
                  <a href="CancelDetailBon-<?php echo $row['refno']; ?>" class="btn btn-danger btn-xs <?php if($row['status']=="Cancel" or $row['status']=="Selesai" or $row['status']=="Sedang Proses"){ echo "disabled"; } ?>" ><i class="fa fa-times-circle" data-toggle="tooltip" data-placement="top" title="Cancel"></i> </a>
                  <a href="ViewDetailBon-<?php echo $row['refno']; ?>" class="btn btn-success btn-xs" ><i class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="View Detail"></i> </a>
                  <a href="TambahDetailBon-<?php echo $row['refno']; ?>" class="btn btn-info btn-xs <?php if($row['status']=="Approve" or $row['status']=="Check" or $row['status']=="Selesai" or $row['status']=="Baru" or $row['status']=="Cancel" or $row['status']=="Terima"){ echo "disabled"; } ?>"><i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="Tambah Detail"></i> </a>
                  <?php if($row['potong_null']=='1' AND $row['status']=="Approve"){?>
                  <a href="pages/cetak/bon-permintaan-ptgnull.php?bon=<?php echo $row['refno']; ?>&tgl=<?php echo substr($row['tgl_update'],0,10); ?>" class="btn btn-primary btn-xs  <?php if(($row['status']=="Approve" and $row['potong_null']=='0') or $row['status']=="Check" or $row['status']=="Baru" or $row['status']=="Cancel" or $row['status']=="Terima"){ echo "disabled"; } ?>" target="_blank"><i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="Cetak"></i> </a>
                  <?php }else{?>
                  <a href="pages/cetak/bon-permintaan.php?bon=<?php echo $row['refno']; ?>&tgl=<?php echo substr($row['tgl_update'],0,10); ?>" class="btn btn-primary btn-xs  <?php if($row['status']=="Approve" or $row['status']=="Check" or $row['status']=="Baru" or $row['status']=="Cancel" or $row['status']=="Terima"){ echo "disabled"; } ?>" target="_blank"><i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="Cetak"></i> </a>
                  <?php }?>
                  <a href="#" class="btn btn-success btn-xs <?php if($row['status']=="Sedang Proses" or $row['status']=="Terima" or $row['status']=="Baru" or $row['status']=="Selesai" or $row['status']=="Check" or $row['status']=="Cancel"){ echo "disabled"; } ?>" onclick="confirm_terima('TerimaBon-<?php echo $row['refno']; ?>-<?php echo $row['tgl_buat']; ?>-<?php echo $_SESSION['userGKJ']; ?>-<?php echo $_SESSION['jabatanGKJ']; ?>');"><i class="fa fa-check-circle" data-toggle="tooltip" data-placement="top" title="Terimaa"></i> </a>
                  <a href="#" class="btn btn-primary btn-xs <?php if($row['status']=="Baru" or $row['status']=="Selesai" or $row['status']=="Approve" or $row['status']=="Sedang Proses" or $row['status']=="Check" or $row['status']=="Cancel"){ echo "disabled"; } ?>" onclick="confirm_proses('SProsesBon-<?php echo $row['refno']; ?>-<?php echo $row['tgl_buat']; ?>-<?php echo $_SESSION['userGKJ']; ?>-<?php echo $_SESSION['jabatanGKJ']; ?>');"><i class="fa fa-spinner" data-toggle="tooltip" data-placement="top" title="Proses"></i> </a>
                  <a href="#" class="btn btn-warning btn-xs <?php if($row['status']=="Baru" or $row['status']=="Terima" or $row['status']=="Selesai" or $row['status']=="Approve" or $row['status']=="Check" or $row['status']=="Cancel"){ echo "disabled"; } ?>" onclick="confirm_selesai('SelesaiBon-<?php echo $row['refno']; ?>-<?php echo $row['tgl_buat']; ?>-<?php echo $_SESSION['userGKJ']; ?>-<?php echo $_SESSION['jabatanGKJ']; ?>');"><i class="fa fa-flag-checkered" data-toggle="tooltip" data-placement="top" title="Selesai"></i> </a></div></td>
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
<!-- /.content -->
<div id="TglProsesEdit" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- Modal Popup untuk terima bon-->
<div class="modal fade" id="terimaBon" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:100px;">
      <div class="modal-header">
				<h4 class="modal-title">INFORMATION</h4>  
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
				<h4 class="modal-title">INFORMATION</h4>  
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
<!-- Modal Popup untuk proses bon-->
<div class="modal fade" id="sprosesBon" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:100px;">
      <div class="modal-header">
        <h4 class="modal-title">INFORMATION</h4>  
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <h5 class="modal-title" style="text-align:center;">Bon Permintaan Akan <span class='badge badge-success'>Diproses</span>?</h5>
      </div>	
      <div class="modal-footer justify-content-between">
        <a href="#" class="btn btn-success" id="sproses_link">Yes</a>
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Popup untuk cancel bon-->
<div class="modal fade" id="cancelBon" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:100px;">
      <div class="modal-header">
				<h4 class="modal-title">INFORMATION</h4>  
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
			<div class="modal-body">
				<h5 class="modal-title" style="text-align:center;">Bon Permintaan Akan <span class='badge badge-danger'>di Cancel</span>?</h5>
			</div>	
      <div class="modal-footer justify-content-between">
        <a href="#" class="btn btn-success" id="cancel_link">Yes</a>
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
function confirm_proses(sproses_url) {
$('#sprosesBon').modal('show', {
backdrop: 'static'
});
document.getElementById('sproses_link').setAttribute('href', sproses_url);
}
function confirm_cancel(cancel_url) {
$('#cancelBon').modal('show', {
backdrop: 'static'
});
document.getElementById('cancel_link').setAttribute('href', cancel_url);
}
</script>
<script>
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});
</script>
