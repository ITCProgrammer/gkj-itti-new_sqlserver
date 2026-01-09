<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";
$ip_num = $_SERVER['REMOTE_ADDR'];
$os= $_SERVER['HTTP_USER_AGENT'];
$Dept	    = $_SESSION['deptGKJ'];
$Awal 	= isset($_POST['awal']) ? $_POST['awal'] :'' ;
$Akhir 	= isset($_POST['akhir']) ? $_POST['akhir'] :'' ;
$Status 	= isset($_POST['status']) ? $_POST['status'] :'' ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="300" />
<title>Summary Bon Permintaan</title>
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
                    <h3 class="card-title">Filter Summary Bon Permintaan</h3>				
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">			
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="awal" class="col-md-2">Tgl Awal</label>
                                    <div class="col-sm-2">
                                        <input name="awal" type="date" class="form-control pull-right" required id="datepicker" placeholder="0000-00-00" value="<?php echo $Awal;?>"/>	
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="awal" class="col-md-2">Tgl Akhir</label>
                                    <div class="col-sm-2">
                                        <input name="akhir" type="date" class="form-control pull-right" required id="datepicker1" placeholder="0000-00-00" value="<?php echo $Akhir;?>"/>	
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="status" class="col-md-2">Status</label>
                                    <div class="col-md-2">					
                                        <select class="form-control select2" name="status" id="status" >
                                            <option value="">Pilih</option>
                                            <option value="Baru" <?php if($Status=="Baru"){echo "SELECTED";}?>>Baru</option>
                                            <option value="Check" <?php if($Status=="Check"){echo "SELECTED";}?>>Check</option>
                                            <option value="Approve" <?php if($Status=="Approve"){echo "SELECTED";}?>>Approve</option>
                                            <option value="Terima" <?php if($Status=="Terima"){echo "SELECTED";}?>>Terima</option>
                                            <option value="Sedang Proses" <?php if($Status=="Sedang Proses"){echo "SELECTED";}?>>Sedang Proses</option>
                                            <option value="Selesai" <?php if($Status=="Selesai"){echo "SELECTED";}?>>Selesai</option>
                                            <option value="Cancel" <?php if($Status=="Cancel"){echo "SELECTED";}?>>Cancel</option>
                                        </select>	
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right"><i class="fa fa-search"></i> Cari Data</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">		  
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                    <h3 class="card-title">Summary Bon Permintaan</h3>		
                        <div align="right">
                            <a href="pages/cetak/excelsummary-bon.php?awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>&status=<?php echo $Status; ?>" class="btn btn-primary <?php if($Awal==""){echo "disabled";}?>" target="_blank"><i class="fa fa-file-excel" data-toggle="tooltip" data-placement="top" title="Cetak Excel"></i> Excel</a>
                            <a href="pages/cetak/lap-tg.php?awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>" class="btn btn-warning <?php if($Awal==""){echo "disabled";}?>" target="_blank"><i class="fa fa-file" data-toggle="tooltip" data-placement="top" title="Laporan TG"></i> Laporan TG</a>
                            <a href="pages/cetak/excellap-tg.php?awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>" class="btn btn-success <?php if($Awal==""){echo "disabled";}?>" target="_blank"><i class="fa fa-file-excel" data-toggle="tooltip" data-placement="top" title="Laporan TG Excel"></i> Laporan TG</a>				
                        </div>
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
                                <th><div align="center">Tgl Update</div></th>
                                <th><div align="center">Keterangan</div></th>
                                <!-- <th><div align="center">Action</div></th> -->
                                </tr>
                            </thead>
                            <tbody> 
                            <?php
                            if($Status!=""){
                                $sts=" AND `status`='$Status' ";
                            }else{ 
                                $sts=" ";
                            }
                            $sql 	= "SELECT
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
                                not ISNULL( refno ) and DATE_FORMAT(tgl_buat,'%Y-%m-%d') BETWEEN '$Awal' AND '$Akhir' $sts
                            GROUP BY
                                refno";
                            $sqlr = mysqli_query($con,$sql);
                            $n=1;
                            while($row = mysqli_fetch_array($sqlr)){
                            ?>	  
                            <tr>
                                <td align="center"><?php echo $n; ?></td>
                                <td align="center"><a href="Timeline-<?php echo $row['refno'];?>" target="_blank"><?php echo $row['refno'];?></a></td>
                                <td align="center"><?php echo $row['dept']; ?></td>
                                <td align="center"><?php if($row['status']=="Baru"){echo "<span class='badge badge-info'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_buat']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_buat']."</span>";}
                                else if($row['status']=="Terima"){echo "<span class='badge badge-primary'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_terima']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_terima']."</span>";}
                                else if($row['status']=="Sedang Proses"){echo "<span class='badge badge-warning blink_me'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_proses']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_proses']."</span>";}
                                else if($row['status']=="Selesai"){echo "<span class='badge badge-success'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_selesai']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_selesai']."</span>";}
                                else if($row['status']=="Approve"){echo "<span class='badge badge-success'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_approve']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_approve']."</span>";}
                                else if($row['status']=="Check"){echo "<span class='badge badge-primary'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_periksa']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_periksa']."</span>";}
                                else if($row['status']=="Cancel"){echo "<span class='badge badge-danger'>".$row['status']."</span><br>"; echo "<span class='badge badge-danger'>".$row['personil_cancel']."</span><br>"; echo "<span class='badge badge-primary'>".$row['tgl_cancel']."</span>";} ?>
                                </td>
                                <td align="center"><?php echo $row['tgl_update']; ?></td>
                                <td align="center">
                                <?php
                                    $sqlket = mysqli_query($con,"SELECT DISTINCT(jns_permintaan) FROM tbl_bon_permintaan WHERE refno='$row[refno]'");
                                    while($rket = mysqli_fetch_array($sqlket)){
                                ?>
                                <?php if($rket['jns_permintaan']=="Bongkaran"){echo "<span class='badge badge-warning'>".$rket['jns_permintaan']."</span>";}
                                else if($rket['jns_permintaan']=="Potong Sample"){echo "<span class='badge badge-success'>".$rket['jns_permintaan']."</span>";}
                                else if($rket['jns_permintaan']=="Potong Pass Qty"){echo "<span class='badge badge-primary'>".$rket['jns_permintaan']."</span>";}
                                    }?>
                                </td>
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
	</form>
    <!-- /.row -->
</section>
<!-- /.content -->
</body>
</html>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<script>
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();
	});
</script>
