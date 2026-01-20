<?PHP
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
$ip_num = $_SERVER['REMOTE_ADDR'];
$os = $_SERVER['HTTP_USER_AGENT'];
$Dept        = $_SESSION['deptGKJ'];
$Awal     = isset($_POST['awal']) ? $_POST['awal'] : '';
$Akhir     = isset($_POST['akhir']) ? $_POST['akhir'] : '';
$Status     = isset($_POST['status']) ? $_POST['status'] : '';
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
                                            <input name="awal" type="date" class="form-control pull-right" required id="datepicker" placeholder="0000-00-00" value="<?php echo $Awal; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="awal" class="col-md-2">Tgl Akhir</label>
                                        <div class="col-sm-2">
                                            <input name="akhir" type="date" class="form-control pull-right" required id="datepicker1" placeholder="0000-00-00" value="<?php echo $Akhir; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="status" class="col-md-2">Status</label>
                                        <div class="col-md-2">
                                            <select class="form-control select2" name="status" id="status">
                                                <option value="">Pilih</option>
                                                <option value="Baru" <?php if ($Status == "Baru") {
                                                                            echo "SELECTED";
                                                                        } ?>>Baru</option>
                                                <option value="Check" <?php if ($Status == "Check") {
                                                                            echo "SELECTED";
                                                                        } ?>>Check</option>
                                                <option value="Approve" <?php if ($Status == "Approve") {
                                                                            echo "SELECTED";
                                                                        } ?>>Approve</option>
                                                <option value="Terima" <?php if ($Status == "Terima") {
                                                                            echo "SELECTED";
                                                                        } ?>>Terima</option>
                                                <option value="Sedang Proses" <?php if ($Status == "Sedang Proses") {
                                                                                    echo "SELECTED";
                                                                                } ?>>Sedang Proses</option>
                                                <option value="Selesai" <?php if ($Status == "Selesai") {
                                                                            echo "SELECTED";
                                                                        } ?>>Selesai</option>
                                                <option value="Cancel" <?php if ($Status == "Cancel") {
                                                                            echo "SELECTED";
                                                                        } ?>>Cancel</option>
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
                                <a href="pages/cetak/excelsummary-bon.php?awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>&status=<?php echo $Status; ?>" class="btn btn-primary <?php if ($Awal == "") {
                                                                                                                                                                                            echo "disabled";
                                                                                                                                                                                        } ?>" target="_blank"><i class="fa fa-file-excel" data-toggle="tooltip" data-placement="top" title="Cetak Excel"></i> Excel</a>
                                <a href="pages/cetak/lap-tg.php?awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>" class="btn btn-warning <?php if ($Awal == "") {
                                                                                                                                                    echo "disabled";
                                                                                                                                                } ?>" target="_blank"><i class="fa fa-file" data-toggle="tooltip" data-placement="top" title="Laporan TG"></i> Laporan TG</a>
                                <a href="pages/cetak/excellap-tg.php?awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>" class="btn btn-success <?php if ($Awal == "") {
                                                                                                                                                        echo "disabled";
                                                                                                                                                    } ?>" target="_blank"><i class="fa fa-file-excel" data-toggle="tooltip" data-placement="top" title="Laporan TG Excel"></i> Laporan TG</a>
                            </div>
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
                                            <div align="center">Tgl Update</div>
                                        </th>
                                        <th>
                                            <div align="center">Keterangan</div>
                                        </th>
                                        <!-- <th><div align="center">Action</div></th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $params = [];
                                    $where  = "WHERE t.refno IS NOT NULL
                                        AND CONVERT(date, t.tgl_buat) BETWEEN ? AND ?";
                                    $params[] = $Awal;
                                    $params[] = $Akhir;

                                    if ($Status != '') {
                                        $where .= " AND t.[status] = ?";
                                        $params[] = $Status;
                                    }

                                    $sql = "
                                    SELECT
                                        MAX(t.id) AS id,
                                        MAX(t.dept) AS dept,
                                        t.refno,
                                        COUNT(*) AS jmlkk,

                                        COALESCE((
                                            SELECT STRING_AGG(x.nokk, ', ')
                                            FROM (SELECT DISTINCT CAST(nokk AS varchar(max)) AS nokk
                                                FROM db_qc.tbl_bon_permintaan
                                                WHERE refno = t.refno) x
                                        ), '') AS nokk,

                                        COALESCE((
                                            SELECT STRING_AGG(x.sts, ', ')
                                            FROM (SELECT DISTINCT CAST([status] AS varchar(max)) AS sts
                                                FROM db_qc.tbl_bon_permintaan
                                                WHERE refno = t.refno) x
                                        ), '') AS [status],

                                        COALESCE((
                                            SELECT STRING_AGG(x.v, ', ')
                                            FROM (SELECT DISTINCT CAST(personil_buat AS varchar(max)) AS v
                                                FROM db_qc.tbl_bon_permintaan
                                                WHERE refno = t.refno) x
                                        ), '') AS personil_buat,

                                        COALESCE((
                                            SELECT STRING_AGG(x.v, ', ')
                                            FROM (SELECT DISTINCT CAST(personil_periksa AS varchar(max)) AS v
                                                FROM db_qc.tbl_bon_permintaan
                                                WHERE refno = t.refno) x
                                        ), '') AS personil_periksa,
                                        CONVERT(varchar(10), MAX(t.tgl_periksa), 23) AS tgl_periksa,

                                        COALESCE((
                                            SELECT STRING_AGG(x.v, ', ')
                                            FROM (SELECT DISTINCT CAST(personil_approve AS varchar(max)) AS v
                                                FROM db_qc.tbl_bon_permintaan
                                                WHERE refno = t.refno) x
                                        ), '') AS personil_approve,
                                        CONVERT(varchar(10), MAX(t.tgl_approve), 23) AS tgl_approve,

                                        COALESCE((
                                            SELECT STRING_AGG(x.v, ', ')
                                            FROM (SELECT DISTINCT CAST(personil_terima AS varchar(max)) AS v
                                                FROM db_qc.tbl_bon_permintaan
                                                WHERE refno = t.refno) x
                                        ), '') AS personil_terima,
                                        CONVERT(varchar(10), MAX(t.tgl_terima), 23) AS tgl_terima,

                                        COALESCE((
                                            SELECT STRING_AGG(x.v, ', ')
                                            FROM (SELECT DISTINCT CAST(personil_proses AS varchar(max)) AS v
                                                FROM db_qc.tbl_bon_permintaan
                                                WHERE refno = t.refno) x
                                        ), '') AS personil_proses,
                                        CONVERT(varchar(10), MAX(t.tgl_proses), 23) AS tgl_proses,

                                        COALESCE((
                                            SELECT STRING_AGG(x.v, ', ')
                                            FROM (SELECT DISTINCT CAST(personil_selesai AS varchar(max)) AS v
                                                FROM db_qc.tbl_bon_permintaan
                                                WHERE refno = t.refno) x
                                        ), '') AS personil_selesai,
                                        CONVERT(varchar(10), MAX(t.tgl_selesai), 23) AS tgl_selesai,

                                        COALESCE((
                                            SELECT STRING_AGG(x.v, ', ')
                                            FROM (SELECT DISTINCT CAST(personil_cancel AS varchar(max)) AS v
                                                FROM db_qc.tbl_bon_permintaan
                                                WHERE refno = t.refno) x
                                        ), '') AS personil_cancel,
                                        CONVERT(varchar(10), MAX(t.tgl_cancel), 23) AS tgl_cancel,

                                        CONVERT(varchar(19), MAX(t.tgl_update), 120) AS tgl_update,
                                        CONVERT(varchar(10), MAX(t.tgl_buat), 23) AS tgl_buat

                                    FROM db_qc.tbl_bon_permintaan t
                                    $where
                                    GROUP BY t.refno
                                    ORDER BY MAX(t.id) DESC
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
                                            <td align="center"><a href="Timeline-<?php echo $row['refno']; ?>" target="_blank"><?php echo $row['refno']; ?></a></td>
                                            <td align="center"><?php echo $row['dept']; ?></td>
                                            <td align="center"><?php if ($row['status'] == "Baru") {
                                                                    echo "<span class='badge badge-info'>" . $row['status'] . "</span><br>";
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
                                                                    echo "<span class='badge badge-success'>" . $row['status'] . "</span><br>";
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
                                                                } ?>
                                            </td>
                                            <td align="center"><?php echo $row['tgl_update']; ?></td>
                                            <td align="center">
                                                <?php
                                                $sqlket = sqlsrv_query(
                                                    $con,
                                                    "SELECT DISTINCT jns_permintaan FROM db_qc.tbl_bon_permintaan WHERE refno = ?",
                                                    [$row['refno']]
                                                );
                                                if ($sqlket === false) {
                                                    die(print_r(sqlsrv_errors(), true));
                                                }

                                                while ($rket = sqlsrv_fetch_array($sqlket, SQLSRV_FETCH_ASSOC)) {
                                                    if ($rket['jns_permintaan'] == "Bongkaran") {
                                                        echo "<span class='badge badge-warning'>{$rket['jns_permintaan']}</span> ";
                                                    } else if ($rket['jns_permintaan'] == "Potong Sample") {
                                                        echo "<span class='badge badge-success'>{$rket['jns_permintaan']}</span> ";
                                                    } else if ($rket['jns_permintaan'] == "Potong Pass Qty") {
                                                        echo "<span class='badge badge-primary'>{$rket['jns_permintaan']}</span> ";
                                                    }
                                                }
                                                ?>
                                            </td>
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