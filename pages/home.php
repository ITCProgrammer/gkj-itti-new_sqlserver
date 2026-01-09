<?php 
session_start();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Home</title>
</head>

<body>
<div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>Buka Bon</h3>

                <p>Buka Bon Permintaan</p>
              </div>
              <div class="icon">
                <i class="ion ion-edit"></i>
              </div>
              <a href="<?php if($_SESSION['stsGKJ']=="Non-Aktif"){echo "#";}else{echo "PotongBongkar";}?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>Status Permintaan</h3>

                <p>Data Status Permintaan</p>
              </div>
              <div class="icon">
                <i class="ion ion-social-buffer"></i>
              </div>
              <a href="<?php if($_SESSION['stsGKJ']=="Non-Aktif"){echo "#";}else{echo "StatusPermintaan";}?>" class="small-box-footer" >More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>Persediaan Kain</h3>

                <p>Data Persediaan Kain</p>
              </div>
              <div class="icon">
                <i class="ion ion-monitor"></i>
              </div>
              <a href="<?php if($_SESSION['stsGKJ']=="Non-Aktif"){echo "#";}else{echo "PersediaanKainJadi";}?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>Bon Selesai</h3>

                <p>Bon Selesai</p>
              </div>
              <div class="icon">
                <i class="ion ion-checkmark"></i>
              </div>
              <a href="<?php if($_SESSION['stsGKJ']=="Non-Aktif"){echo "#";}else{echo "BonSelesai";}?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>	
	
</body>
</html>