<?php
ini_set("error_reporting", 1);
session_start();
//include config
include("koneksi.php");
?>
<?php
//set base constant
if (!isset($_SESSION['userGKJ'])) { ?>
  <script>
    setTimeout("location.href='login'", 500);
  </script>
<?php die('');
} else if (!isset($_SESSION['passGKJ'])) { ?>
  <script>
    setTimeout("location.href='lockscreen'", 500);
  </script>
<?php die('');
}
?>
<?php
//request page
$page = isset($_GET['page']) ? $_GET['page'] : '';
$act  = isset($_GET['act']) ? $_GET['act'] : '';
$id   = isset($_GET['id']) ? $_GET['id'] : '';
$page = strtolower($page);
$Dept	    = $_SESSION['deptGKJ'];
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>GKJ-ITTI | <?php if ($_GET['page'] != "") {
                            echo ucwords($_GET['page']);
                          } else {
                            echo "Home";
                          } ?></title>

  <!-- Ionicons -->
  <link rel="stylesheet" href="plugins/Ionicons/css/ionicons.min.css">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Treegrid -->
  <link rel="stylesheet" href="plugins/treegrid/css/jquery.treegrid.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- Ekko Lightbox -->
  <link rel="stylesheet" href="plugins/ekko-lightbox/ekko-lightbox.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <style>
    body {
      /* "Calibri Light","serif" */
      font-family: Calibri, "sans-serif", "Source Sans Pro", "Courier New";
      font-style: normal;
    }

    .blink_me {
      animation: blinker 1s linear infinite;
    }

    @keyframes blinker {
      50% {
        opacity: 0;
      }
    }
  </style>
  <link rel="icon" type="image/png" href="dist/img/ITTI_Logo index.ico">
</head>

<body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-footer-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <?php if($_SESSION['stsGKJ']=="Aktif"){ ?>	
        <li class="nav-item d-none d-sm-inline-block">
          <a href="Home" class="nav-link">Home</a>		
        </li>
      <li class="float-right nav-item d-none d-sm-inline">
        <a href="#" class="nav-link"><?php echo strtoupper($_SESSION['lvlGKJ']);?></a>
        </li>
      <li class="float-right nav-item d-none d-sm-inline">
        <a href="#" class="nav-link"><?php echo strtoupper($_SESSION['deptGKJ']);?></a>
        </li>	
      <?php } ?>
      </ul>

      <!-- SEARCH FORM -->
      <!--<form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>-->

      <!-- Right navbar links -->
    <?php
      $sqlnotif=mysqli_query($con,"SELECT COUNT(x.jml) AS cnt FROM
      (SELECT COUNT(*) AS jml FROM tbl_bon_permintaan WHERE (`status`='Approve' OR `status`='Terima')
      GROUP BY refno) X");
      $rnotif=mysqli_fetch_array($sqlnotif);
    ?>
    <!-- Notifikasi Bon Approve/Terima Pada Halaman GKJ -->
    <?php if($_SESSION['deptGKJ']=="GKJ" OR $_SESSION['deptGKJ']=="DIT"){ ?>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a href="#" class="nav-link" data-toggle="dropdown">
          <i class="fa fa-bell"></i>
          <span class="badge badge-danger navbar-badge <?php if($rnotif['cnt']>0){echo "blink_me";}?>"><?php echo $rnotif['cnt'];?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left:inherit; right:0px;">
          <span class="dropdown-item dropdown-header"><?php echo $rnotif['cnt'];?> Bon Permintaan Siap Proses</span>
          <div class="dropdown-divider"></div>
          <?php
            $sqldt=mysqli_query($con,"SELECT refno, dept, tgl_buat, TIMESTAMPDIFF(HOUR, tgl_buat, NOW()) AS difference_time, DATEDIFF(NOW(), tgl_buat) AS difference_day
            FROM tbl_bon_permintaan WHERE (`status`='Approve' OR `status`='Terima')
            GROUP BY refno");
            while($rdt=mysqli_fetch_array($sqldt)){
          ?>
            <a class="dropdown-item" href="#">
            No Bon : <?php echo $rdt['refno'].", ".$rdt['dept'];?>
            <span class="float-right text-muted text-sm"><?php echo $rdt['difference_day'];?> Hari yang Lalu</span>
            </a>
            <div class="dropdown-divider"></div>
          <?php } ?>
            <a class="dropdown-item dropdown-footer" href="ProsesPermintaanBon">Tampil Semua</a>
        </div>
      </li>
       <!-- Notifikasi Bon Sedang Proses Pada Halaman GKJ -->
       <?php
        $sqlnp=mysqli_query($con,"SELECT COUNT(x.jml) AS cnt FROM
        (SELECT COUNT(*) AS jml FROM tbl_bon_permintaan WHERE `status`='Sedang Proses'
        GROUP BY refno) X");
        $rnp=mysqli_fetch_array($sqlnp);
      ?>
      <li class="nav-item dropdown">
        <a href="#" class="nav-link" data-toggle="dropdown">
          <i class="fa fa-spinner"></i>
          <span class="badge badge-warning navbar-badge"><?php echo $rnp['cnt'];?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left:inherit; right:0px;">
          <span class="dropdown-item dropdown-header"><?php echo $rnp['cnt'];?> Bon Permintaan Sedang Proses</span>
          <div class="dropdown-divider"></div>
          <?php
            $sqldtp=mysqli_query($con,"SELECT refno, dept, tgl_buat, TIMESTAMPDIFF(HOUR, tgl_buat, NOW()) AS difference_time, DATEDIFF(NOW(), tgl_buat) AS difference_day
            FROM tbl_bon_permintaan WHERE `status`='Sedang Proses'
            GROUP BY refno");
            while($rdtp=mysqli_fetch_array($sqldtp)){
          ?>
            <a class="dropdown-item" href="#">
            No Bon : <?php echo $rdtp['refno'].", ".$rdtp['dept'];?>
            <span class="float-right text-muted text-sm"><?php echo $rdtp['difference_day'];?> Hari yang Lalu</span>
            </a>
            <div class="dropdown-divider"></div>
          <?php } ?>
            <a class="dropdown-item dropdown-footer" href="ProsesPermintaanBon">Tampil Semua</a>
        </div>
      </li>
    </ul>
    <?php } ?>
    <?php
      $sqlnd=mysqli_query($con,"SELECT COUNT(x.jml) AS cnt FROM
      (SELECT COUNT(*) AS jml FROM tbl_bon_permintaan WHERE (`status`='Baru' OR `status`='Check') AND dept='$Dept' 
      GROUP BY refno) X");
      $rnd=mysqli_fetch_array($sqlnd);
    ?>
     <!-- Notifikasi Bon Baru/Check Pada Halaman Dept User -->
    <?php if($_SESSION['deptGKJ']!="GKJ"){ ?>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a href="#" class="nav-link" data-toggle="dropdown">
          <i class="fa fa-bell"></i>
          <span class="badge badge-danger navbar-badge"><?php echo $rnd['cnt'];?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left:inherit; right:0px;">
          <span class="dropdown-item dropdown-header"><?php echo $rnd['cnt'];?> Bon Permintaan Baru</span>
          <div class="dropdown-divider"></div>
          <?php
            $sqldtd=mysqli_query($con,"SELECT refno, dept, tgl_buat, TIMESTAMPDIFF(HOUR, tgl_buat, NOW()) AS difference_time, DATEDIFF(NOW(), tgl_buat) AS difference_day
            FROM tbl_bon_permintaan WHERE (`status`='Baru' OR `status`='Check') AND dept='$Dept'
            GROUP BY refno");
            while($rdtd=mysqli_fetch_array($sqldtd)){
          ?>
            <a class="dropdown-item" href="#">
            No Bon : <?php echo $rdtd['refno'];?>
            <span class="float-right text-muted text-sm"><?php echo $rdtd['difference_day'];?> Hari yang Lalu</span>
            </a>
            <div class="dropdown-divider"></div>
          <?php } ?>
            <a class="dropdown-item dropdown-footer" href="StatusPermintaan">Tampil Semua</a>
        </div>
      </li>
      <!-- Notifikasi Bon Sedang Proses Pada Halaman Dept User -->
      <?php
      $sqlndp=mysqli_query($con,"SELECT COUNT(x.jml) AS cnt FROM
      (SELECT COUNT(*) AS jml FROM tbl_bon_permintaan WHERE `status`='Sedang Proses' AND dept='$Dept' 
      GROUP BY refno) X");
      $rndp=mysqli_fetch_array($sqlndp);
      ?>
      <li class="nav-item dropdown">
        <a href="#" class="nav-link" data-toggle="dropdown">
          <i class="fa fa-spinner"></i>
          <span class="badge badge-warning navbar-badge"><?php echo $rndp['cnt'];?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left:inherit; right:0px;">
          <span class="dropdown-item dropdown-header"><?php echo $rndp['cnt'];?> Bon Permintaan Sedang Proses</span>
          <div class="dropdown-divider"></div>
          <?php
              $sqldtdp=mysqli_query($con,"SELECT refno, dept, tgl_buat, TIMESTAMPDIFF(HOUR, tgl_buat, NOW()) AS difference_time, DATEDIFF(NOW(), tgl_buat) AS difference_day
              FROM tbl_bon_permintaan WHERE `status`='Sedang Proses' AND dept='$Dept'
              GROUP BY refno");
              while($rdtdp=mysqli_fetch_array($sqldtdp)){
          ?>
            <a class="dropdown-item" href="#">
            No Bon : <?php echo $rdtdp['refno'];?>
            <span class="float-right text-muted text-sm"><?php echo $rdtdp['difference_day'];?> Hari yang Lalu</span>
            </a>
            <div class="dropdown-divider"></div>
          <?php } ?>
            <a class="dropdown-item dropdown-footer" href="StatusPermintaan">Tampil Semua</a>
        </div>
      </li>
    </ul>
    <?php } ?>
  </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="Home" class="brand-link">
        <img src="dist/img/ITTI_Logo 2021.png" alt="Indo Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">GKJ ITTI</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="dist/img/<?php echo $_SESSION['fotoGKJ']; ?>" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="Profile" class="d-block"><?php echo strtoupper($_SESSION['userGKJ']); ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <?php if($_SESSION['stsGKJ']=="Aktif"){ ?>
              <li class="nav-item has-treeview <?php if($_GET['page']=="PotongBongkar" or $_GET['page']=="StatusPermintaan" or $_GET['page']=="ProsesPermintaanBon" or $_GET['page']=="SummaryBonPermintaan" or $_GET['page']=="BonSelesai" or $_GET['page']=="Petunjuk_Penggunaan"){echo"menu-open";}?>">
            <a href="#" class="nav-link <?php if($_GET['page']=="PotongBongkar" or $_GET['page']=="StatusPermintaan" or $_GET['page']=="ProsesPermintaanBon" or $_GET['page']=="SummaryBonPermintaan" or $_GET['page']=="CancelDetailBon" or $_GET['page']=="ViewDetailBon" or $_GET['page']=="BonSelesai" or $_GET['page']=="Petunjuk_Penggunaan"){echo"active";}?>">
              <i class="nav-icon fas fa-database text-gray"></i>
              <p>
                Bon Permintaan
                <i class="right fas fa-angle-left"></i>  
              </p>
            </a>
            <ul class="nav nav-treeview">
			        <li class="nav-item">
                <a href="PotongBongkar" class="nav-link <?php if($_GET['page']=="PotongBongkar"){echo"active";}?>">
                  <i class="far nav-icon fa-file text-red"></i>
                  <p>Potong Sample&amp;Bongkaran</p>
                </a>
              </li>	
              <?php if($_SESSION['deptGKJ']=="GKJ" OR $_SESSION['deptGKJ']=="DIT"){ ?>
              <!-- <li class="nav-item">
                <a href="BonSample" class="nav-link <?php if($_GET['page']=="BonSample"){echo"active";}?>">
                  <i class="far nav-icon fa-file text-purple"></i>
                  <p>Bon Sample</p>
                </a>
              </li>	 -->
              <li class="nav-item">
                <a href="ProsesPermintaanBon" class="nav-link <?php if($_GET['page']=="ProsesPermintaanBon"){echo"active";}?>">
                  <i class="far nav-icon fa-file text-yellow"></i>
                  <p>Proses Permintaan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="SummaryBonPermintaan" class="nav-link <?php if($_GET['page']=="SummaryBonPermintaan"){echo"active";}?>">
                  <i class="far nav-icon fa-file text-blue"></i>
                  <p>Summary Bon Permintaan</p>
                </a>
              </li>
              <?php } ?>	
			        <li class="nav-item">
                <a href="StatusPermintaan" class="nav-link <?php if($_GET['page']=="StatusPermintaan" or $_GET['page']=="CancelDetailBon" or $_GET['page']=="ViewDetailBon"){echo"active";}?>">
                  <i class="far nav-icon fa-file text-green"></i>
                  <p>Status</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="BonSelesai" class="nav-link <?php if($_GET['page']=="BonSelesai"){echo"active";}?>">
                  <i class="far nav-icon fa-file text-white"></i>
                  <p>Bon Selesai</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="Petunjuk_Penggunaan" class="nav-link <?php if($_GET['page']=="Petunjuk_Penggunaan"){echo"active";}?>">
                  <i class="far nav-icon fa-question-circle text-blue"></i>
                  <p>Petunjuk Penggunaan</p>
                </a>
              </li>				
            </ul>
          </li>
          <?php if($_SESSION['lvlGKJ']=="superadmin" or $_SESSION['lvlGKJ']=="admin") { ?>	
		      <li class="nav-item has-treeview <?php if($_GET['page']=="User"){echo"menu-open";}?>">
            <a href="#" class="nav-link <?php if($_GET['page']=="User"){echo"active";}?>">
              <i class="nav-icon fas fa-database text-gray"></i>
              <p>
                Master
                <i class="right fas fa-angle-left"></i>  
              </p>
            </a>
            <ul class="nav nav-treeview">
			        <li class="nav-item">
                <a href="User" class="nav-link <?php if($_GET['page']=="User"){echo"active";}?>">
                  <i class="far nav-icon fa-user text-fuchsia"></i>
                  <p>User</p>
                </a>
              </li>	
            </ul>
          </li>
			    <?php } ?>	
          <?php } ?>

            <li class="nav-item">
              <a href="logout" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt text-blue"></i>
                <p>Log Out</p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">

      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <?php
          if (!empty($page) and !empty($act)) {
            $files = 'pages/' . $page . '.' . $act . '.php';
          } else
          if (!empty($page)) {
            $files = 'pages/' . $page . '.php';
          } else {
            $files = 'pages/home.php';
          }

          if (file_exists($files)) {
            include_once($files);
          } else {
            include_once("blank.php");
          }
          ?>

        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
      <div class="p-3">
        <h5>Title</h5>
        <p>Sidebar content</p>
      </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- To the right -->
      <div class="float-right d-none d-sm-inline">
        Indo Taichen Textile Industry
      </div>
      <!-- Default to the left -->
      <strong>Copyright &copy; <?php echo date("Y");?> <a href="#">DIT</a>.</strong> All rights reserved.
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="plugins/bootstrap4-editable/js/bootstrap-editable.js"></script>
  <!-- DataTables -->
  <script src="plugins/datatables/jquery.dataTables.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
  <script src="plugins/datatables-fixedheader/js/dataTables.fixedHeader.min.js"></script>
  <!-- Select2 -->
  <script src="plugins/select2/js/select2.js"></script>
  <!-- bootstrap datepicker -->
  <!--<script src="plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>-->
  <!-- jQuery -->
  <script src="plugins/treegrid/js/jquery.treegrid.js"></script>
  <script src="plugins/treegrid/js/jquery.treegrid.bootstrap3.js"></script>
  <!-- InputMask -->
  <script src="plugins/moment/moment.min.js"></script>
  <script src="plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
  <!-- Toastr -->
  <script src="plugins/toastr/toastr.min.js"></script>
  <!-- Ekko Lightbox -->
  <script src="plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
  <!-- bs-custom-file-input -->
  <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
</body>

</html>
<script type="text/javascript">
  $(document).ready(function() {
    bsCustomFileInput.init();
  });
</script>
<script>
  $(function () {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: true
      });
    });

    $('.filter-container').filterizr({gutterPixels: 3});
    $('.btn[data-filter]').on('click', function() {
      $('.btn[data-filter]').removeClass('active');
      $(this).addClass('active');
    });
  })
</script>
<script>
   $(function () { 
	   
	//Datepicker
    $('#datepicker').datetimepicker({
      format: 'YYYY-MM-DD'
    });
	$('#datepicker1').datetimepicker({
      format: 'YYYY-MM-DD'
    });
	$('#datepicker2').datetimepicker({
      format: 'YYYY-MM-DD'
    });   
	$("#lookup1").DataTable();
	$("#lookup2").DataTable();
	$("#lookup3").DataTable();
	$("#example1").DataTable({
		"ordering": true,
		"paging": false,
		"info": true,
		"fixedHeader": true,
		"language": {
			          "search": "Filter records:"
			        }
		
	}); 
    $("#example2").DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": true,
      "autoWidth": false,	
    });
	$("#example3").DataTable({
		"scrollX"  : true,
	    "scrollY"  : '300px',
		"ordering": true,
		"paging": false,
		"info": true,
		
	});
	$("#example4").DataTable({
		"scrollX"  : true,
	    "scrollY"  : '263px',
		"ordering": true,
		"paging": false,
		"info": true,
		"searching": true,
		
	});   
	$("#example5").DataTable({	  	
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,	
    });  
	$("#example6").DataTable({	  	
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,	
    }); 
	$("#example7").DataTable({
	  "searching": false,	
	  "ordering": false,
	  "lengthChange": false,	
	  "pageLength" : 5,
      "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]]	      	
    });
    $("#example8").DataTable({	  	
      "paging": false,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,	
    });  
	$("#example9").DataTable({	  	
      "paging": false,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,	
    });     
	$(".select2").select2({
      theme: 'bootstrap4',
	  placeholder: "Select",
      allowClear: true,	
    });
    //Initialize Select2 Elements
	  
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });  
	$('.select2gardu').select2({
    placeholder: "Select a gardu",
    allowClear: true,
	theme: 'bootstrap4'	
    }); 
	$('.select2trafo1').select2({
    placeholder: "Select a trafo",
    allowClear: true,
	theme: 'bootstrap4'	
});
	$('.select2panel').select2({
    placeholder: "Select a panel",
    allowClear: true,
	theme: 'bootstrap4'
}); 
	$('.select2trafo').select2({
    placeholder: "Select a trafo",
    allowClear: true,
	theme: 'bootstrap4'
});
	$('.select2sts').select2({
    placeholder: "Select a status",
    allowClear: true,
	theme: 'bootstrap4'
});
	   
  });
	$(document).ready(function() {
                $('.tree').treegrid();
                $('.tree-2').treegrid({					
                    expanderExpandedClass: 'fa fa-minus',
                    expanderCollapsedClass: 'fa fa-plus'
                });

            });
</script>
<script>
$(document).on('click', '.edit_lvlusr', function (e) {
  var m = $(this).attr("id");
    $.ajax({
       url: "pages/level_edit.php",
       type: "GET",
       data : {id: m,},
       success: function (ajaxData){
         $("#LvlUsrEdit").html(ajaxData);
         $("#LvlUsrEdit").modal('show',{backdrop: 'true'});
       }
     });
      });
$(document).on('click', '.edit_stsusr', function (e) {
  var m = $(this).attr("id");
    $.ajax({
       url: "pages/status_edit.php",
       type: "GET",
       data : {id: m,},
       success: function (ajaxData){
         $("#StsUsrEdit").html(ajaxData);
         $("#StsUsrEdit").modal('show',{backdrop: 'true'});
       }
     });
      });	
$(document).on('click', '.pilih-no', function(e) {
        document.getElementById("avatar").value = $(this).attr('data-id');
        document.getElementById("avatar").focus();
        $('#DataAvatar').modal('hide');
      });
$(document).on('click', '.detailpersediaan', function (e) {
  var m = $(this).attr("id");
  var k = $(this).attr("ket");
    $.ajax({
       url: "pages/detailpersediaan.php",
       type: "GET",
       data : {id: m, ket: k,},
       success: function (ajaxData){
         $("#DetailPersediaan").html(ajaxData);
         $("#DetailPersediaan").modal('show',{backdrop: 'true'});
       }
     });
      });
$(document).on('click', '.update_ket', function(e) {
        var m = $(this).attr("id");
        var a = $(this).attr("refno");
        $.ajax({
          url: "pages/update_ket.php",
          type: "GET",
          data: {
            id: m,
            refno: a
          },
          success: function(ajaxData) {
            $("#UpdateKet").html(ajaxData);
            $("#UpdateKet").modal('show', {
              backdrop: 'true'
            });
          }
        });
      });
$(document).on('click', '.update_nokk', function(e) {
        var m = $(this).attr("id");
        $.ajax({
          url: "pages/update_nokk.php",
          type: "GET",
          data: {
            id: m
          },
          success: function(ajaxData) {
            $("#UpdateNoKK").html(ajaxData);
            $("#UpdateNoKK").modal('show', {
              backdrop: 'true'
            });
          }
        });
      });
$(document).on('click', '.edit_tglproses', function (e) {
  var m = $(this).attr("refno");
    $.ajax({
       url: "pages/tglproses_edit.php",
       type: "GET",
       data : {refno: m,},
       success: function (ajaxData){
         $("#TglProsesEdit").html(ajaxData);
         $("#TglProsesEdit").modal('show',{backdrop: 'true'});
       }
     });
      });
</script>
<?php
if( $act=="login" and $_SESSION['stsGKJ']=="Aktif"){
echo "<script>
  	$(function() {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 6000
    });
	Toast.fire({
        type: 'success',
        title: 'Log In successful'
      });
  });
  
</script>";
}
if( $_SESSION['stsGKJ']=="Non-Aktif" ){
echo "<script>
  	$(function() {
	toastr.error('Account has not been activated please contact DIT')
  });
  
</script>";
}
// ## UBAH STATUS JADI TERIMA
if($page == "statusterima"){
  date_default_timezone_set("Asia/Jakarta");
	$user=$_GET['user'];
	$refno=$_GET['refno'];
	$tgl=$_GET['tgl'];
	$jabatan=$_GET['jabatan'];
	$ip= $_SERVER['REMOTE_ADDR'];
   $sql 	= mysqli_query($con,"UPDATE tbl_bon_permintaan SET
		`status`='Terima',
		`personil_terima`='$user',
		`jabatan_terima`='$jabatan',
		`tgl_terima`= now()
    WHERE refno='$refno' and DATE_FORMAT(tgl_buat,'%Y.%m.%d')='$tgl'");

    $sqlInsert=mysqli_query($con,"INSERT INTO tbl_log_bon_gkj SET
    proses='Terima Bon Permintaan',
    detail_proses='GKJ Melakukan Proses Terima Bon: $refno ',
    user='$user',
    waktu_proses=now(),
    ip='$ip'");

  if($sqlInsert){
    echo "<script>window.location='ProsesPermintaanBon';</script>";
  }
		
}
// ## UBAH STATUS JADI CHECK
if($page == "statuscheck"){
  date_default_timezone_set("Asia/Jakarta");
	$user=$_GET['user'];
	$refno=$_GET['refno'];
	$tgl=$_GET['tgl'];
	$jabatan=$_GET['jabatan'];
	$ip= $_SERVER['REMOTE_ADDR'];

  $sql 	= mysqli_query($con,"UPDATE tbl_bon_permintaan SET
		`status`='Check',
		`personil_periksa`='$user',
		`jabatan_periksa`='$jabatan',
		`tgl_periksa`= now()
    WHERE refno='$refno' and DATE_FORMAT(tgl_buat,'%Y.%m.%d')='$tgl'");
  
  $sqlInsert=mysqli_query($con,"INSERT INTO tbl_log_bon_gkj SET
    proses='Check Bon Permintaan',
    detail_proses='User Melakukan Proses Check Bon: $refno ',
    user='$user',
    waktu_proses=now(),
    ip='$ip'");
  if($sqlInsert){
    echo "<script>window.location='TimelineUser-$refno';</script>";
  }
		 
 }
 
// ## UBAH STATUS JADI APPROVE
if($page == "statusapprove"){
  date_default_timezone_set("Asia/Jakarta");
	$user=$_GET['user'];
	$refno=$_GET['refno'];
	$tgl=$_GET['tgl'];
	$jabatan=$_GET['jabatan'];
	$ip= $_SERVER['REMOTE_ADDR'];
  
  $sql 	= mysqli_query($con,"UPDATE tbl_bon_permintaan SET
		`status`='Approve',
		`personil_approve`='$user',
		`jabatan_approve`='$jabatan',
		`tgl_approve`= now()
    WHERE refno='$refno' and DATE_FORMAT(tgl_buat,'%Y.%m.%d')='$tgl'");
    
	$sqlInsert=mysqli_query($con,"INSERT INTO tbl_log_bon_gkj SET
  proses='Approve Bon Permintaan',
  detail_proses='User Melakukan Proses Approve Bon: $refno ',
  user='$user',
  waktu_proses=now(),
  ip='$ip'");

if($sqlInsert){
  echo "<script>window.location='TimelineUser-$refno';</script>";
  }
		 
}

// ## UBAH STATUS JADI SEDANG PROSES
if($page == "statussproses"){
  date_default_timezone_set("Asia/Jakarta");
	$user=$_GET['user'];
	$refno=$_GET['refno'];
	$tgl=$_GET['tgl'];
	$jabatan=$_GET['jabatan'];
  
  $sql 	= mysqli_query($con,"UPDATE tbl_bon_permintaan SET
		`status`='Sedang Proses',
		`personil_proses`='$user',
		`jabatan_proses`='$jabatan',
		`tgl_proses`= now()
    WHERE refno='$refno' and DATE_FORMAT(tgl_buat,'%Y.%m.%d')='$tgl'");
  
  echo "<script>window.location='ProsesPermintaanBon';</script>";
}
 // ## UBAH STATUS JADI CANCEL
if($page == "statuscancel"){
  date_default_timezone_set("Asia/Jakarta");
	$usernm=$_GET['usernm'];
	$refno=$_GET['refno'];
	$tgl=$_GET['tgl'];
	$jabatan=$_GET['jabatan'];
  
  $sql 	= mysqli_query($con,"UPDATE tbl_bon_permintaan SET
		`status`='Cancel',
		`personil_cancel`='$usernm',
		`jabatan_cancel`='$jabatan',
		`tgl_cancel`= now()
    WHERE refno='$refno' and DATE_FORMAT(tgl_buat,'%Y.%m.%d')='$tgl'");
  echo "<script>window.location='ProsesPermintaanBon';</script>";
}

 // ## UBAH STATUS JADI SELESAI
if($page == "statusselesai"){
  date_default_timezone_set("Asia/Jakarta");
	$user=$_GET['user'];
	$refno=$_GET['refno'];
	$tgl=$_GET['tgl'];
	$jabatan=$_GET['jabatan'];
  $ip= $_SERVER['REMOTE_ADDR'];
  $sql 	= mysqli_query($con,"UPDATE tbl_bon_permintaan SET
		`status`='Selesai',
		`personil_selesai`='$user',
		`jabatan_selesai`='$jabatan',
		`tgl_selesai`= now()
		WHERE refno='$refno' and DATE_FORMAT(tgl_buat,'%Y.%m.%d')='$tgl'");
  
  $sqlInsert=mysqli_query($con,"INSERT INTO tbl_log_bon_gkj SET
   proses='Bon Permintaan Selesai',
   detail_proses='GKJ Telah Menyelesaikan Proses Permintaan Bon: $refno ',
   user='$user',
   waktu_proses=now(),
   ip='$ip'");
 
 if($sqlInsert){
   echo "<script>window.location='ProsesPermintaanBon';</script>";
   }
		
}

 // ## CANCEL KK
 if($page == "hapusdetailbon"){
  date_default_timezone_set("Asia/Jakarta");
	$id=$_GET['id'];
	$bon=$_GET['bon'];
	$nokk=$_GET['nokk'];
  $ip= $_SERVER['REMOTE_ADDR'];
  $usercancel	= $_SESSION['userGKJ'];
  $sql 	= mysqli_query($con,"DELETE FROM tbl_bon_permintaan WHERE id='$id'");
  
  $sqlInsert=mysqli_query($con,"INSERT INTO tbl_log_bon_gkj SET
   proses='Cancel KK',
   detail_proses='Cancel KK dengan ID:$id,No Bon:$bon dan No KK:$nokk ',
   user='$usercancel',
   waktu_proses=now(),
   ip='$ip'");
 
 if($sqlInsert){
   echo "<script>window.location='CancelDetailBon-$bon';</script>";
   }
		
}

if($page == "ubahlvl"){
  $sqlupdate 	= mysqli_query($con,"UPDATE tbl_user_gkj SET `level`='$_GET[lvl]' WHERE id='$_GET[id]'");
  if($sqlupdate){
    echo "<script>window.location='User';</script>";
  }
}
?>