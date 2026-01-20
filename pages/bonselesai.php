<?PHP
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";

$ip_num = $_SERVER['REMOTE_ADDR'];
$os     = $_SERVER['HTTP_USER_AGENT'];
$Dept   = $_SESSION['deptGKJ'];

$Awal  = isset($_POST['awal']) ? $_POST['awal'] : '';
$Akhir = isset($_POST['akhir']) ? $_POST['akhir'] : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="refresh" content="300" />
  <title>Bon Selesai</title>
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
</head>

<body>
  <section class="content">
    <form role="form" method="post" enctype="multipart/form-data" name="form1">

      <?php if ($Dept == "QCF") { ?>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Filter Bon Selesai</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-12">

                    <div class="form-group row">
                      <label for="awal" class="col-md-2">Tgl Awal</label>
                      <div class="col-sm-2">
                        <input name="awal" type="date" class="form-control pull-right" required id="datepicker" placeholder="0000-00-00" value="<?php echo $Awal; ?>" />
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="akhir" class="col-md-2">Tgl Akhir</label>
                      <div class="col-sm-2">
                        <input name="akhir" type="date" class="form-control pull-right" required id="datepicker1" placeholder="0000-00-00" value="<?php echo $Akhir; ?>" />
                      </div>
                    </div>

                  </div>
                </div>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Cari Data</button>
                <a href="pages/cetak/excelbon-selesai.php?awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>"
                  class="btn btn-primary float-right <?php if ($Awal == "") {
                                                        echo "disabled";
                                                      } ?>"
                  target="_blank">
                  <i class="fa fa-file-excel" data-toggle="tooltip" data-placement="top" title="Cetak Excel"></i> Cetak Excel
                </a>
              </div>

            </div>
          </div>
        </div>
      <?php } ?>

      <div class="row">
        <div class="col-12">
          <div class="card">

            <div class="card-header">
              <h3 class="card-title">Data Bon Permintaan Selesai</h3>
            </div>

            <div class="card-body table-responsive">
              <table id="example5" width="100%" class="table table-sm table-bordered table-hover tree" style="font-size: 13px;">
                <thead class="btn-success">
                  <tr>
                    <th>
                      <div align="center">No</div>
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
                  // ===== Dept filter (SQL Server) =====
                  $where = " WHERE refno IS NOT NULL AND [status] = 'Selesai' ";
                  $params = [];

                  if ($Dept == 'GKJ' || $Dept == 'DIT') {
                    // no filter
                  } elseif ($Dept == 'PPC' || $Dept == 'MKT') {
                    $where .= " AND dept IN ('PPC','MKT') ";
                  } else {
                    $where .= " AND dept = ? ";
                    $params[] = $Dept;
                  }

                  // ===== Date filter khusus QCF =====
                  if ($Dept == "QCF" && $Awal != "" && $Akhir != "") {
                    $where .= " AND CONVERT(date, tgl_buat) BETWEEN ? AND ? ";
                    $params[] = $Awal;
                    $params[] = $Akhir;
                  }

                  $sql = "
                          ;WITH R AS (
                            SELECT DISTINCT refno
                            FROM db_qc.tbl_bon_permintaan
                            $where
                          )
                          SELECT
                            (SELECT MAX(id) FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno) AS id,
                            r.refno,

                            (SELECT MAX(dept) FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno) AS dept,

                            (SELECT MAX([status]) FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno) AS [status],

                            (SELECT COALESCE(STRING_AGG(CAST(z.personil_buat AS varchar(max)), ', '), '')
                            FROM (SELECT DISTINCT personil_buat FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno AND personil_buat IS NOT NULL) z
                            ) AS personil_buat,

                            (SELECT COALESCE(STRING_AGG(CAST(z.personil_periksa AS varchar(max)), ', '), '')
                            FROM (SELECT DISTINCT personil_periksa FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno AND personil_periksa IS NOT NULL) z
                            ) AS personil_periksa,

                            (SELECT COALESCE(STRING_AGG(CAST(z.personil_approve AS varchar(max)), ', '), '')
                            FROM (SELECT DISTINCT personil_approve FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno AND personil_approve IS NOT NULL) z
                            ) AS personil_approve,

                            (SELECT COALESCE(STRING_AGG(CAST(z.personil_terima AS varchar(max)), ', '), '')
                            FROM (SELECT DISTINCT personil_terima FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno AND personil_terima IS NOT NULL) z
                            ) AS personil_terima,

                            (SELECT COALESCE(STRING_AGG(CAST(z.personil_proses AS varchar(max)), ', '), '')
                            FROM (SELECT DISTINCT personil_proses FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno AND personil_proses IS NOT NULL) z
                            ) AS personil_proses,

                            (SELECT COALESCE(STRING_AGG(CAST(z.personil_selesai AS varchar(max)), ', '), '')
                            FROM (SELECT DISTINCT personil_selesai FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno AND personil_selesai IS NOT NULL) z
                            ) AS personil_selesai,

                            (SELECT COALESCE(STRING_AGG(CAST(z.personil_cancel AS varchar(max)), ', '), '')
                            FROM (SELECT DISTINCT personil_cancel FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno AND personil_cancel IS NOT NULL) z
                            ) AS personil_cancel,

                            (SELECT MAX(CONVERT(varchar(10), tgl_periksa, 23)) FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno) AS tgl_periksa,
                            (SELECT MAX(CONVERT(varchar(10), tgl_approve, 23)) FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno) AS tgl_approve,
                            (SELECT MAX(CONVERT(varchar(10), tgl_terima, 23))  FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno) AS tgl_terima,
                            (SELECT MAX(CONVERT(varchar(10), tgl_proses, 23))  FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno) AS tgl_proses,
                            (SELECT MAX(CONVERT(varchar(10), tgl_selesai, 23)) FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno) AS tgl_selesai,
                            (SELECT MAX(CONVERT(varchar(10), tgl_cancel, 23))  FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno) AS tgl_cancel,

                            (SELECT MAX(CONVERT(varchar(19), tgl_update, 120)) FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno) AS tgl_update,

                            (SELECT MAX(CONVERT(varchar(10), tgl_buat, 102)) FROM db_qc.tbl_bon_permintaan x WHERE x.refno = r.refno) AS tgl_buat

                          FROM R r
                          ORDER BY id DESC
                        ";

                  $stmt = sqlsrv_query($con, $sql, $params);
                  if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                  }

                  $n = 1;
                  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                  ?>
                    <tr>
                      <td align="center"><?php echo $n; ?></td>

                      <td align="center">
                        <a href="TimelineUser-<?php echo $row['refno']; ?>" target="_blank"><?php echo $row['refno']; ?></a>
                      </td>

                      <td align="center"><?php echo $row['dept']; ?></td>

                      <td align="center">
                        <?php
                        if ($row['status'] == "Baru") {
                          echo "<span class='badge badge-secondary'>" . $row['status'] . "</span><br>";
                          echo "<span class='badge badge-danger'>" . $row['personil_buat'] . "</span><br>";
                          echo "<span class='badge badge-primary'>" . $row['tgl_buat'] . "</span>";
                        } else if ($row['status'] == "Terima") {
                          echo "<span class='badge badge-primary'>" . $row['status'] . "</span><br>";
                          echo "<span class='badge badge-danger'>" . $row['personil_terima'] . "</span><br>";
                          echo "<span class='badge badge-primary'>" . $row['tgl_terima'] . "</span>";
                        } else if ($row['status'] == "Sedang Proses") {
                          echo "<span class='badge badge-warning blink_me'>" . $row['status'] . "</span><br>";
                          echo "<span class='badge badge-danger'>" . $row['personil_proses'] . "</span><br>";
                          echo "<span class='badge badge-primary'>" . $row['tgl_proses'] . "</span>";
                        } else if ($row['status'] == "Selesai") {
                          echo "<span class='badge badge-success'>" . $row['status'] . "</span><br>";
                          echo "<span class='badge badge-danger'>" . $row['personil_selesai'] . "</span><br>";
                          echo "<span class='badge badge-primary'>" . $row['tgl_selesai'] . "</span>";
                        } else if ($row['status'] == "Approve") {
                          echo "<span class='badge badge-info'>" . $row['status'] . "</span><br>";
                          echo "<span class='badge badge-danger'>" . $row['personil_approve'] . "</span><br>";
                          echo "<span class='badge badge-primary'>" . $row['tgl_approve'] . "</span>";
                        } else if ($row['status'] == "Check") {
                          echo "<span class='badge badge-primary'>" . $row['status'] . "</span><br>";
                          echo "<span class='badge badge-danger'>" . $row['personil_periksa'] . "</span><br>";
                          echo "<span class='badge badge-primary'>" . $row['tgl_periksa'] . "</span>";
                        } else if ($row['status'] == "Cancel") {
                          echo "<span class='badge badge-danger'>" . $row['status'] . "</span><br>";
                          echo "<span class='badge badge-danger'>" . $row['personil_cancel'] . "</span><br>";
                          echo "<span class='badge badge-primary'>" . $row['tgl_cancel'] . "</span>";
                        }
                        ?>
                      </td>

                      <!-- sudah aman karena string -->
                      <td align="center"><?php echo $row['tgl_update']; ?></td>

                      <td align="center">
                        <?php
                        $sqlket = "SELECT DISTINCT jns_permintaan FROM db_qc.tbl_bon_permintaan WHERE refno = ?";
                        $stmtKet = sqlsrv_query($con, $sqlket, [$row['refno']]);
                        if ($stmtKet === false) {
                          die(print_r(sqlsrv_errors(), true));
                        }

                        while ($rket = sqlsrv_fetch_array($stmtKet, SQLSRV_FETCH_ASSOC)) {
                          if ($rket['jns_permintaan'] == "Bongkaran") {
                            echo "<span class='badge badge-warning'>" . $rket['jns_permintaan'] . "</span>";
                          } else if ($rket['jns_permintaan'] == "Potong Sample") {
                            echo "<span class='badge badge-success'>" . $rket['jns_permintaan'] . "</span>";
                          } else if ($rket['jns_permintaan'] == "Potong Pass Qty") {
                            echo "<span class='badge badge-primary'>" . $rket['jns_permintaan'] . "</span>";
                          } else if ($rket['jns_permintaan'] == "Potong Sisa") {
                            echo "<span class='badge badge-info'>" . $rket['jns_permintaan'] . "</span>";
                          }
                        }
                        ?>
                      </td>

                      <td align="left">
                        <?php
                        $sqlbon = "SELECT nokk, jns_permintaan, ket FROM db_qc.tbl_bon_permintaan WHERE refno = ?";
                        $stmtBon = sqlsrv_query($con, $sqlbon, [$row['refno']]);
                        if ($stmtBon === false) {
                          die(print_r(sqlsrv_errors(), true));
                        }

                        while ($rbon = sqlsrv_fetch_array($stmtBon, SQLSRV_FETCH_ASSOC)) {
                          echo $rbon['nokk'] . ", " . $rbon['jns_permintaan'] . ", " . $rbon['ket'] . "<br>";
                        }
                        ?>
                      </td>

                      <td align="center">
                        <div class="btn-group">
                          <?php
                          $tglParam = substr($row['tgl_update'], 0, 10);
                          ?>
                          <a href="pages/cetak/bon-permintaan.php?bon=<?php echo trim($row['refno']); ?>&tgl=<?php echo $tglParam; ?>"
                            class="btn btn-primary btn-xs <?php if ($row['status'] == "Approve" or $row['status'] == "Check" or $row['status'] == "Baru" or $row['status'] == "Cancel" or $row['status'] == "Terima") {
                                                            echo "disabled";
                                                          } ?>"
                            target="_blank">
                            <i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="Cetak"></i>
                          </a>

                          <a href="ViewDetailBon-<?php echo $row['refno']; ?>" class="btn btn-success btn-xs">
                            <i class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="View Detail"></i>
                          </a>
                        </div>
                      </td>

                    </tr>
                  <?php
                    $n++;
                  }
                  ?>
                </tbody>

              </table>
            </div>

          </div>
        </div>
      </div>

    </form>
  </section>

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
          <a href="#" class="btn btn-danger" id="terima_link">Yes</a>
          <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
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
          <a href="#" class="btn btn-danger" id="selesai_link">Yes</a>
          <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
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
          <a href="#" class="btn btn-danger" id="approve_link">Yes</a>
          <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
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
          <a href="#" class="btn btn-danger" id="check_link">Yes</a>
          <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
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