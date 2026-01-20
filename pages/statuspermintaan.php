<?PHP
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
$ip_num = $_SERVER['REMOTE_ADDR'];
$os = $_SERVER['HTTP_USER_AGENT'];
$Dept      = $_SESSION['deptGKJ'];
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
      <?php if ($_SESSION['deptGKJ'] == "GKJ" or $_SESSION['deptGKJ'] == "DIT") {
      } else { ?>
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
                    <th>
                      <div align="center">No </div>
                    </th>
                    <th>
                      <div align="center">No Bon</div>
                    </th>
                    <th>
                      <div align="center">Dept</div>
                    </th>
                    <th>
                      <div align="center">Status</div>
                    </th>
                    <th>
                      <div align="center">Tgl Buka Bon</div>
                    </th>
                    <th>
                      <div align="center">Jenis Permintaan</div>
                    </th>
                    <th>
                      <div align="center">Keterangan</div>
                    </th>
                    <th>
                      <div align="center">Action</div>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $whereDept = "";
                  $params    = [];

                  if ($Dept == 'GKJ' || $Dept == 'DIT') {
                    // no filter
                  } elseif ($Dept == 'PPC' || $Dept == 'MKT') {
                    $whereDept = " AND (t.dept = ? OR t.dept = ?) ";
                    $params[] = 'PPC';
                    $params[] = 'MKT';
                  } else {
                    $whereDept = " AND t.dept = ? ";
                    $params[] = $Dept;
                  }

                  $sql = "
                    SELECT
                      MAX(t.id) AS id,
                      t.dept,
                      t.refno,
                      COUNT(*) AS jmlkk,

                      COALESCE((
                        SELECT STRING_AGG(d.nokk, ', ')
                        FROM (
                          SELECT DISTINCT CAST(nokk AS varchar(max)) AS nokk
                          FROM db_qc.tbl_bon_permintaan
                          WHERE refno = t.refno
                        ) d
                      ), '') AS nokk,

                      COALESCE((
                        SELECT STRING_AGG(d.[status], ', ')
                        FROM (
                          SELECT DISTINCT CAST([status] AS varchar(max)) AS [status]
                          FROM db_qc.tbl_bon_permintaan
                          WHERE refno = t.refno
                        ) d
                      ), '') AS [status],

                      COALESCE((
                        SELECT STRING_AGG(d.personil_buat, ', ')
                        FROM (
                          SELECT DISTINCT CAST(personil_buat AS varchar(max)) AS personil_buat
                          FROM db_qc.tbl_bon_permintaan
                          WHERE refno = t.refno
                        ) d
                      ), '') AS personil_buat,

                      COALESCE((
                        SELECT STRING_AGG(d.personil_periksa, ', ')
                        FROM (
                          SELECT DISTINCT CAST(personil_periksa AS varchar(max)) AS personil_periksa
                          FROM db_qc.tbl_bon_permintaan
                          WHERE refno = t.refno
                        ) d
                      ), '') AS personil_periksa,
                      CONVERT(varchar(10), MAX(t.tgl_periksa), 23) AS tgl_periksa,

                      COALESCE((
                        SELECT STRING_AGG(d.personil_approve, ', ')
                        FROM (
                          SELECT DISTINCT CAST(personil_approve AS varchar(max)) AS personil_approve
                          FROM db_qc.tbl_bon_permintaan
                          WHERE refno = t.refno
                        ) d
                      ), '') AS personil_approve,
                      CONVERT(varchar(10), MAX(t.tgl_approve), 23) AS tgl_approve,

                      COALESCE((
                        SELECT STRING_AGG(d.personil_terima, ', ')
                        FROM (
                          SELECT DISTINCT CAST(personil_terima AS varchar(max)) AS personil_terima
                          FROM db_qc.tbl_bon_permintaan
                          WHERE refno = t.refno
                        ) d
                      ), '') AS personil_terima,
                      CONVERT(varchar(10), MAX(t.tgl_terima), 23) AS tgl_terima,

                      COALESCE((
                        SELECT STRING_AGG(d.personil_proses, ', ')
                        FROM (
                          SELECT DISTINCT CAST(personil_proses AS varchar(max)) AS personil_proses
                          FROM db_qc.tbl_bon_permintaan
                          WHERE refno = t.refno
                        ) d
                      ), '') AS personil_proses,
                      CONVERT(varchar(10), MAX(t.tgl_proses), 23) AS tgl_proses,

                      COALESCE((
                        SELECT STRING_AGG(d.personil_selesai, ', ')
                        FROM (
                          SELECT DISTINCT CAST(personil_selesai AS varchar(max)) AS personil_selesai
                          FROM db_qc.tbl_bon_permintaan
                          WHERE refno = t.refno
                        ) d
                      ), '') AS personil_selesai,
                      CONVERT(varchar(10), MAX(t.tgl_selesai), 23) AS tgl_selesai,

                      COALESCE((
                        SELECT STRING_AGG(d.personil_cancel, ', ')
                        FROM (
                          SELECT DISTINCT CAST(personil_cancel AS varchar(max)) AS personil_cancel
                          FROM db_qc.tbl_bon_permintaan
                          WHERE refno = t.refno
                        ) d
                      ), '') AS personil_cancel,
                      CONVERT(varchar(10), MAX(t.tgl_cancel), 23) AS tgl_cancel,

                      CONVERT(varchar(19), MAX(t.tgl_update), 120) AS tgl_update,
                      CONVERT(varchar(10), MAX(t.tgl_buat), 102) AS tgl_buat

                    FROM db_qc.tbl_bon_permintaan t
                    WHERE t.refno IS NOT NULL
                      AND t.[status] <> 'Selesai'
                      $whereDept
                    GROUP BY t.refno, t.dept
                    ORDER BY MAX(t.id) DESC
                  ";

                  $stmt = sqlsrv_query($con, $sql, $params);
                  if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                  }
                  $n = 1;
                  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $refno = $row['refno'];

                    $rowStatus = $row['status'];
                    $rowDept   = $row['dept'];
                    $rowBuat   = $row['personil_buat'];

                    // ===== Jenis permintaan (distinct) =====
                    $sqlKet = "SELECT DISTINCT jns_permintaan FROM db_qc.tbl_bon_permintaan WHERE refno = ?";
                    $stmtKet = sqlsrv_query($con, $sqlKet, [$refno]);
                    if ($stmtKet === false) die(print_r(sqlsrv_errors(), true));

                    // ===== Keterangan detail (list nokk, jns, ket) =====
                    $sqlBon = "SELECT nokk, jns_permintaan, ket FROM db_qc.tbl_bon_permintaan WHERE refno = ?";
                    $stmtBon = sqlsrv_query($con, $sqlBon, [$refno]);
                    if ($stmtBon === false) die(print_r(sqlsrv_errors(), true));
                  ?>
                    <tr>
                      <td align="center"><?php echo $n; ?></td>
                      <td align="center"><a href="TimelineUser-<?php echo $row['refno']; ?>" target="_blank"><?php echo $refno; ?></a></td>
                      <td align="center"><?php echo $rowDept; ?></td>
                      <td align="center">
                        <?php
                        if ($rowStatus == "Baru") {
                          echo "<span class='badge badge-secondary'>{$rowStatus}</span><br>";
                          echo "<span class='badge badge-danger'>{$row['personil_buat']}</span><br>";
                          echo "<span class='badge badge-primary'>{$row['tgl_buat']}</span>";
                        } else if ($rowStatus == "Terima") {
                          echo "<span class='badge badge-primary'>{$rowStatus}</span><br>";
                          echo "<span class='badge badge-danger'>{$row['personil_terima']}</span><br>";
                          echo "<span class='badge badge-primary'>{$row['tgl_terima']}</span>";
                        } else if ($rowStatus == "Sedang Proses") {
                          echo "<span class='badge badge-warning blink_me'>{$rowStatus}</span><br>";
                          echo "<span class='badge badge-danger'>{$row['personil_proses']}</span><br>";
                          echo "<span class='badge badge-primary'>{$row['tgl_proses']}</span>";
                        } else if ($rowStatus == "Selesai") {
                          echo "<span class='badge badge-success'>{$rowStatus}</span><br>";
                          echo "<span class='badge badge-danger'>{$row['personil_selesai']}</span><br>";
                          echo "<span class='badge badge-primary'>{$row['tgl_selesai']}</span>";
                        } else if ($rowStatus == "Approve") {
                          echo "<span class='badge badge-info'>{$rowStatus}</span><br>";
                          echo "<span class='badge badge-danger'>{$row['personil_approve']}</span><br>";
                          echo "<span class='badge badge-primary'>{$row['tgl_approve']}</span>";
                        } else if ($rowStatus == "Check") {
                          echo "<span class='badge badge-primary'>{$rowStatus}</span><br>";
                          echo "<span class='badge badge-danger'>{$row['personil_periksa']}</span><br>";
                          echo "<span class='badge badge-primary'>{$row['tgl_periksa']}</span>";
                        } else if ($rowStatus == "Cancel") {
                          echo "<span class='badge badge-danger'>{$rowStatus}</span><br>";
                          echo "<span class='badge badge-danger'>{$row['personil_cancel']}</span><br>";
                          echo "<span class='badge badge-primary'>{$row['tgl_cancel']}</span>";
                        }
                        ?>
                      </td>
                      <td align="center"><?php echo $row['tgl_update']; ?></td>
                      <td align="center">
                        <?php
                        while ($rket = sqlsrv_fetch_array($stmtKet, SQLSRV_FETCH_ASSOC)) {
                          $jp = $rket['jns_permintaan'];
                          if ($jp == "Bongkaran")             echo "<span class='badge badge-warning'>{$jp}</span> ";
                          else if ($jp == "Potong Sample")    echo "<span class='badge badge-success'>{$jp}</span> ";
                          else if ($jp == "Potong Pass Qty")  echo "<span class='badge badge-primary'>{$jp}</span> ";
                          else if ($jp == "Potong Sisa")      echo "<span class='badge badge-info'>{$jp}</span> ";
                          else if ($jp == "Ganti Grade")      echo "<span class='badge badge-secondary'>{$jp}</span> ";
                          else if ($jp == "Bon Sample")       echo "<span class='badge badge-danger'>{$jp}</span> ";
                          else                                echo "<span class='badge badge-light'>{$jp}</span> ";
                        }
                        ?>
                      </td>

                      <td align="left">
                        <?php
                        while ($rbon = sqlsrv_fetch_array($stmtBon, SQLSRV_FETCH_ASSOC)) {
                          echo $rbon['nokk'] . ", " . $rbon['jns_permintaan'] . ", " . $rbon['ket'] . "<br>";
                        }
                        ?>
                      </td>
                      <td align="center">
                        <div class="btn-group">
                          <a href="pages/cetak/bon-permintaan.php?bon=<?php echo $refno; ?>&tgl=<?php echo substr($row['tgl_update'], 0, 10); ?>"
                            class="btn btn-primary btn-xs <?php
                                                          if ($rowStatus == "Approve" || $rowStatus == "Check" || $rowStatus == "Selesai" || $rowStatus == "Baru" || $rowStatus == "Cancel" || $rowStatus == "Terima") {
                                                            echo "disabled";
                                                          }
                                                          ?>"
                            target="_blank">
                            <i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="Cetak"></i>
                          </a>

                          <a href="CancelDetailBon-<?php echo $refno; ?>"
                            class="btn btn-danger btn-xs <?php
                                                          if ($rowStatus == "Cancel" || $rowStatus == "Selesai" || $rowStatus == "Sedang Proses" || ($_SESSION['deptGKJ'] ?? '') != $rowDept) {
                                                            echo "disabled";
                                                          }
                                                          ?>">
                            <i class="fa fa-times-circle" data-toggle="tooltip" data-placement="top" title="Cancel"></i>
                          </a>

                          <a href="TambahDetailBonUser-<?php echo $refno; ?>"
                            class="btn btn-info btn-xs <?php
                                                        if (
                                                          $rowStatus == "Check" || $rowStatus == "Selesai" || $rowStatus == "Baru" || $rowStatus == "Cancel" || $rowStatus == "Terima" || $rowStatus == "Sedang Proses"
                                                          || ($_SESSION['deptGKJ'] ?? '') != $rowDept
                                                          || ($_SESSION['userGKJ'] ?? '') != $rowBuat
                                                        ) {
                                                          echo "disabled";
                                                        }
                                                        ?>">
                            <i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="Tambah Detail"></i>
                          </a>

                          <a href="ViewDetailBon-<?php echo $refno; ?>" class="btn btn-success btn-xs">
                            <i class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="View Detail"></i>
                          </a>
                          <!--<a href="#" class="btn btn-success btn-xs <?php if ($row['status'] == "Sedang Proses" or $row['status'] == "Terima" or $row['status'] == "Baru" or $row['status'] == "Selesai") {
                                                                          echo "disabled";
                                                                        } ?>" onclick="confirm_terima('TerimaBon-<?php echo $row['refno']; ?>-<?php echo $row['tgl_buat']; ?>');"><i class="fa fa-check-circle" data-toggle="tooltip" data-placement="top" title="Terima"></i> </a>-->
                          <!-- <a href="#" class="btn btn-info btn-xs <?php if ($_SESSION['jabatanGKJ'] == "Staff" or $_SESSION['deptGKJ'] != "$row[dept]" or $row['status'] == "Terima" or $row['status'] == "Sedang Proses" or $row['status'] == "Approve" or $row['status'] == "Selesai" or $row['status'] == "Check" or $row['status'] == "Cancel") {
                                                                        echo "disabled";
                                                                      } ?>" onclick="confirm_check('CheckBon-<?php echo $row['refno']; ?>-<?php echo $row['tgl_buat']; ?>-<?php echo $_SESSION['userGKJ']; ?>-<?php echo $_SESSION['jabatanGKJ']; ?>');"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Check"></i> </a> -->
                          <!-- <a href="#" class="btn btn-success btn-xs <?php if ($_SESSION['jabatanGKJ'] == "Staff" or $_SESSION['deptGKJ'] != "$row[dept]" or $row['status'] == "Terima" or $row['status'] == "Sedang Proses" or $row['status'] == "Baru" or $row['status'] == "Selesai" or $row['status'] == "Approve" or $row['status'] == "Cancel") {
                                                                            echo "disabled";
                                                                          } ?>" onclick="confirm_approve('ApproveBon-<?php echo $row['refno']; ?>-<?php echo $row['tgl_buat']; ?>-<?php echo $_SESSION['userGKJ']; ?>-<?php echo $_SESSION['jabatanGKJ']; ?>');"><i class="fa fa-check" data-toggle="tooltip" data-placement="top" title="Approve"></i> </a> -->
                          <!-- <a href="#" class="btn btn-warning btn-xs <?php if ($_SESSION['jabatanGKJ'] == "Staff" or $_SESSION['deptGKJ'] != "$row[dept]" or $row['status'] == "Baru" or $row['status'] == "Terima" or $row['status'] == "Approve" or $row['status'] == "Selesai" or $row['status'] == "Check" or $row['status'] == "Cancel") {
                                                                            echo "disabled";
                                                                          } ?>" onclick="confirm_selesai('SelesaiBon-<?php echo $row['refno']; ?>-<?php echo $row['tgl_buat']; ?>-<?php echo $_SESSION['userGKJ']; ?>-<?php echo $_SESSION['jabatanGKJ']; ?>');"><i class="fa fa-flag-checkered" data-toggle="tooltip" data-placement="top" title="Selesai"></i> </a></div></td> -->
                    </tr>
                  <?php $n++;
                  } ?>
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