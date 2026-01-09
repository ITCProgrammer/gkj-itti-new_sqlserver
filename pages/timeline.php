<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
$ip_num = $_SERVER['REMOTE_ADDR'];
$os= $_SERVER['HTTP_USER_AGENT'];
$Bon	    = isset($_GET['refno']) ? $_GET['refno'] : '';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Profile</title>
</head>
<?php 
$sqltl = mysqli_query($con,"SELECT *, 
    DATE_FORMAT(tgl_buat,'%Y.%m.%d') as tgl_buat1,
    GROUP_CONCAT( DISTINCT nokk SEPARATOR ', ' ) AS nokk_all,
    GROUP_CONCAT(`ket` SEPARATOR ', ') AS `ket_all`,
    GROUP_CONCAT(`warna` SEPARATOR ', ') AS `warna_all`,
    GROUP_CONCAT(`no_lot` SEPARATOR ', ') AS `lot_all`
    FROM `tbl_bon_permintaan` WHERE refno='$Bon' ");
$r1 = mysqli_fetch_array($sqltl);
?>
<body>
	<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
			        <form method="post" enctype="multipart/form-data" name="form1">  
                        <div class="card card-primary">
                            <div class="card-body">
                                <h3>Description</h3>
                                <hr>  
                                <strong><i class="fas fa-book mr-1"></i> Nomor Bon</strong>
                                
                                <p class="text-muted">	
                                <?php echo $r1['refno']; ?>
                                </p>

                                <hr>
                                <strong><i class="fas fa-book mr-1"></i> No KK</strong>
                                
                                <p class="text-muted">	
                                <?php echo $r1['nokk_all']; ?>
                                </p>

                                <hr>  
                                <strong><i class="fas fa-book mr-1"></i> Keterangan</strong>
                                
                                <p class="text-muted">	
                                <?php echo $r1['ket_all']; ?>
                                </p>

                                <hr>  
                                <strong><i class="fas fa-book mr-1"></i> Warna</strong>
                                
                                <p class="text-muted">	
                                <?php echo $r1['warna_all']; ?>
                                </p>

                                <hr>  
                                <strong><i class="fas fa-book mr-1"></i> Lot</strong>
                                
                                <p class="text-muted">	
                                <?php echo $r1['lot_all']; ?>
                                </p>

                                <hr>   
                                <strong><i class="fas fa-book mr-1"></i> Langganan</strong>

                                <p class="text-muted">	
                                <?php echo $r1['langganan']; ?>
                                </p>
                                
                                <hr>             

                                <strong><i class="fas fa-book mr-1"></i> Jenis Kain</strong>

                                <p class="text-muted"><?php echo $r1['jenis_kain']; ?></p>

                                <hr>
                                <strong><i class="fas fa-book mr-1"></i> Jenis Permintaan</strong>
                                
                                <p class="text-muted">	
                                <?php 
                               $sqlket= mysqli_query($con,"SELECT DISTINCT(jns_permintaan) FROM tbl_bon_permintaan WHERE refno='$Bon'");
                               while($rket=mysqli_fetch_array($sqlket)){
                                ?>
                                <?php if($rket['jns_permintaan']=="Bongkaran"){?>
                                    <?php echo $rket['jns_permintaan']."<br>"; ?>
                                <?php } ?>
                                <?php if($rket['jns_permintaan']=="Potong Sample"){?>
                                    <?php echo $rket['jns_permintaan']."<br>"; ?>
                                <?php } ?>
                                <?php if($rket['jns_permintaan']=="Potong Pass Qty"){?>
                                    <?php echo $rket['jns_permintaan']; ?>
                                <?php } ?>
                                <?php if($rket['jns_permintaan']=="Potong Sisa"){?>
                                    <?php echo $rket['jns_permintaan']; ?>
                                <?php } ?>
                                <?php if($rket['jns_permintaan']=="Ganti Grade"){?>
                                    <?php echo $rket['jns_permintaan']; ?>
                                <?php } }?>
                                </p>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
		            </form>		
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="activity">
                                    <div class="timeline timeline-inverse">
                                        <?php 
                                        $sql = mysqli_query($con,"SELECT *, 
                                            DATE_FORMAT(tgl_buat,'%Y.%m.%d') as tgl_buat1,
                                            GROUP_CONCAT( DISTINCT nokk SEPARATOR ', ' ) AS nokk_all,
                                            GROUP_CONCAT(`ket` SEPARATOR ', ') AS `ket_all`,
                                            GROUP_CONCAT(`warna` SEPARATOR ', ') AS `warna_all`,
                                            GROUP_CONCAT(`no_lot` SEPARATOR ', ') AS `lot_all`
                                            FROM `tbl_bon_permintaan` WHERE refno='$Bon' ");		
                                        $r2 = mysqli_fetch_array($sql);			  
                                        ?> 	
                                        <!-- timeline time label -->
                                        <div class="time-label">
                                            <span class="<?php echo "bg-orange";?>">
                                                <?php echo date('d M Y', strtotime($r2['tgl_buat']));?>
                                            </span>
                                        </div>
                                        <!-- /.timeline-label -->
                                        <!-- timeline item -->
                                        <div>
                                            <i class="fa fa-edit bg-green"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> <?php echo date('H:i:s', strtotime($r2['tgl_buat']));?></span>
                                                <h3 class="timeline-header border-0">Bon Dibuat Oleh <a href="#"><?php echo $r2['personil_buat']; ?></a> Dari Dept. <?php echo $r2['dept']; ?>
                                                </h3>
                                            </div>	
                                        </div>		
                                        <!-- END timeline item -->
                                        <!-- timeline item -->
                                        <?php if($r2['tgl_periksa']!= NULL){?>
                                        <!-- timeline time label -->
                                        <div class="time-label">
                                            <span class="<?php echo "bg-orange";?>">
                                                <?php echo date('d M Y', strtotime($r2['tgl_periksa']));?>
                                            </span>
                                        </div>
                                        <!-- /.timeline-label -->
                                        <!-- timeline item -->
                                        <div>
                                            <i class="fa fa-file bg-primary"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> <?php echo date('H:i:s', strtotime($r2['tgl_periksa']));?></span>
                                                <h3 class="timeline-header border-0">Bon Diperiksa Oleh <a href="#"><?php echo $r2['personil_periksa']; ?></a>
                                                </h3>
                                            </div>	
                                        </div>		
                                        <!-- END timeline item -->
                                        <!-- timeline item -->
                                        <?php }?>
                                        <?php if($r2['tgl_approve']!= NULL){?>
                                        <!-- timeline time label -->
                                        <div class="time-label">
                                            <span class="<?php echo "bg-orange";?>">
                                                <?php echo date('d M Y', strtotime($r2['tgl_approve']));?>
                                            </span>
                                        </div>
                                        <!-- /.timeline-label -->
                                        <!-- timeline item -->
                                        <div>
                                            <i class="fa fa-check bg-green"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> <?php echo date('H:i:s', strtotime($r2['tgl_approve']));?></span>
                                                <h3 class="timeline-header border-0">Bon Diapprove Oleh <a href="#"><?php echo $r2['personil_approve']; ?></a>
                                                </h3>
                                            </div>	
                                        </div>		
                                        <!-- END timeline item -->
                                        <!-- timeline item -->
                                        <?php }?>
                                        <?php if($r2['tgl_approve']!= NULL AND $r2['status_tambah']=='1'){?>
                                        <!-- timeline time label -->
                                        <div class="time-label">
                                            <span class="<?php echo "bg-orange";?>">
                                                <?php echo date('d M Y', strtotime($r2['tgl_tambah']));?>
                                            </span>
                                        </div>
                                        <!-- /.timeline-label -->
                                        <!-- timeline item -->
                                        <div>
                                            <i class="fa fa-check bg-green"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> <?php echo date('H:i:s', strtotime($r2['tgl_tambah']));?></span>
                                                <h3 class="timeline-header border-0">Penambahan Bon Oleh User <a href="#"><?php echo $r2['personil_tambah']; ?></a>&nbsp; No KK : <?php echo $r2['nokk']; ?>,&nbsp;<?php echo $r2['jns_permintaan']; ?>
                                                </h3>
                                            </div>	
                                        </div>		
                                        <!-- END timeline item -->
                                        <!-- timeline item -->
                                        <?php }?>
                                        <?php if($r2['tgl_terima']!= NULL){?>
                                        <!-- timeline time label -->
                                        <div class="time-label">
                                            <span class="<?php echo "bg-orange";?>">
                                                <?php echo date('d M Y', strtotime($r2['tgl_terima']));?>
                                            </span>
                                        </div>
                                        <!-- /.timeline-label -->
                                        <!-- timeline item -->
                                        <div>
                                            <i class="fa fa-file bg-purple"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> <?php echo date('H:i:s', strtotime($r2['tgl_terima']));?></span>
                                                <h3 class="timeline-header border-0">Bon Diterima Oleh <a href="#"><?php echo $r2['personil_terima']; ?></a> Dari Dept. GKJ
                                                </h3>
                                            </div>	
                                        </div>		
                                        <!-- END timeline item -->
                                        <!-- timeline item -->
                                        <?php }?>
                                        <?php if($r2['tgl_proses']!= NULL){?>
                                        <!-- timeline time label -->
                                        <div class="time-label">
                                            <span class="<?php echo "bg-orange";?>">
                                                <?php echo date('d M Y', strtotime($r2['tgl_proses']));?>
                                            </span>
                                        </div>
                                        <!-- /.timeline-label -->
                                        <!-- timeline item -->
                                        <div>
                                            <i class="fa fa-spinner bg-info"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> <?php echo date('H:i:s', strtotime($r2['tgl_proses']));?></span>
                                                <h3 class="timeline-header border-0">Bon Telah Diproses Oleh <a href="#"><?php echo $r2['personil_proses']; ?> </a>
                                                </h3>
                                            </div>	
                                        </div>		
                                        <!-- END timeline item -->
                                        <!-- timeline item -->
                                        <?php }?>
                                        <?php 
                                        $sqltbh = mysqli_query($con,"SELECT * FROM `tbl_bon_permintaan` WHERE refno='$Bon' AND status_tambah='1'");
                                        while($r3 = mysqli_fetch_array($sqltbh)){					  
                                        ?>
                                        <?php if($r3['tgl_approve']==NULL AND $r3['status_tambah']=='1'){?>
                                        <!-- timeline time label -->
                                        <div class="time-label">
                                            <span class="<?php echo "bg-orange";?>">
                                                <?php echo date('d M Y', strtotime($r3['tgl_tambah']));?>
                                            </span>
                                        </div>
                                        <!-- /.timeline-label -->
                                        <!-- timeline item -->
                                        <div>
                                            <i class="fa fa-plus bg-green"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> <?php echo date('H:i:s', strtotime($r3['tgl_tambah']));?></span>
                                                <h3 class="timeline-header border-0">Penambahan Bon Oleh GKJ&nbsp;<a href="#"><?php echo $r3['personil_tambah']; ?></a>&nbsp; No KK : <?php echo $r3['nokk']; ?>, &nbsp;<?php echo $r3['jns_permintaan']; ?>
                                                </h3>
                                            </div>	
                                        </div>		
                                        <!-- END timeline item -->
                                        <!-- timeline item -->
                                        <?php   } 
                                            } ?> 
                                        <?php if($r2['tgl_selesai']!= NULL){?>
                                        <!-- timeline time label -->
                                        <div class="time-label">
                                            <span class="<?php echo "bg-orange";?>">
                                                <?php echo date('d M Y', strtotime($r2['tgl_selesai']));?>
                                            </span>
                                        </div>
                                        <!-- /.timeline-label -->
                                        <!-- timeline item -->
                                        <div>
                                            <i class="fa fa-flag-checkered bg-yellow"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> <?php echo date('H:i:s', strtotime($r2['tgl_selesai']));?></span>
                                                <h3 class="timeline-header border-0">Bon Telah Diselesaikan Oleh <a href="#"><?php echo $r2['personil_selesai']; ?> </a>
                                                </h3>
                                            </div>	
                                        </div>		
                                        <!-- END timeline item -->
                                        <!-- timeline item -->
                                        <?php }?>
                                        <?php if($r2['tgl_cancel']!= NULL){?>
                                        <!-- timeline time label -->
                                        <div class="time-label">
                                            <span class="<?php echo "bg-orange";?>">
                                                <?php echo date('d M Y', strtotime($r2['tgl_cancel']));?>
                                            </span>
                                        </div>
                                        <!-- /.timeline-label -->
                                        <!-- timeline item -->
                                        <div>
                                            <i class="fa fa-times bg-red"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> <?php echo date('H:i:s', strtotime($r2['tgl_cancel']));?></span>
                                                <h3 class="timeline-header border-0">Bon Telah Dicancel Oleh <a href="#"><?php echo $r2['personil_cancel']; ?> </a>
                                                </h3>
                                            </div>	
                                        </div>		
                                        <!-- END timeline item -->
                                        <!-- timeline item -->
                                        <?php }?>
                                        <div>
                                            <i class="far fa-calendar-alt bg-gray"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.tab-pane -->               
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
<div id="ApprovedEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>	
<div id="AppAfterEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div id="JenisKainEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
</body>
</html>
