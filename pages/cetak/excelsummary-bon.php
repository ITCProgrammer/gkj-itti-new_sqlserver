<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=summary-bon-permintaan-" . substr($_GET['awal'], 0, 10) . "_" . substr($_GET['akhir'], 0, 10) . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

ini_set("error_reporting", 1);
session_start();

include "../../koneksi.php";

$Awal   = $_GET['awal'] ?? '';
$Akhir  = $_GET['akhir'] ?? '';
$Status = $_GET['status'] ?? '';
?>

<body>
    <strong>Summary Bon Permintaan</strong><br>
    <strong>Periode: <?php echo htmlspecialchars($Awal); ?> s/d <?php echo htmlspecialchars($Akhir); ?></strong><br>

    <table width="100%" border="1">
        <tr>
            <th bgcolor="#12C9F0">NO.</th>
            <th bgcolor="#12C9F0">NO BON</th>
            <th bgcolor="#12C9F0">NO KK</th>
            <th bgcolor="#12C9F0">DEPT</th>
            <th bgcolor="#12C9F0">STATUS</th>
            <th bgcolor="#12C9F0">TGL UPDATE</th>
            <th bgcolor="#12C9F0">KETERANGAN</th>
        </tr>

        <?php
        $no = 1;

        $params = [];
        $where  = "WHERE t.refno IS NOT NULL
           AND CONVERT(date, t.tgl_buat) BETWEEN ? AND ?";
        $params[] = $Awal;
        $params[] = $Akhir;

        if ($Status !== '') {
            $where .= " AND t.[status] = ?";
            $params[] = $Status;
        }

        $sql = "
                SELECT
                    t.refno,
                    MAX(t.dept) AS dept,

                    COALESCE((
                        SELECT STRING_AGG(x.nokk, ', ')
                        FROM (
                            SELECT DISTINCT CAST(nokk AS varchar(max)) AS nokk
                            FROM db_qc.tbl_bon_permintaan
                            WHERE refno = t.refno
                        ) x
                    ), '') AS nokk,

                    COALESCE((
                        SELECT STRING_AGG(x.sts, ', ')
                        FROM (
                            SELECT DISTINCT CAST([status] AS varchar(max)) AS sts
                            FROM db_qc.tbl_bon_permintaan
                            WHERE refno = t.refno
                        ) x
                    ), '') AS [status],

                    CONVERT(varchar(19), MAX(t.tgl_update), 120) AS tgl_update,

                    COALESCE((
                        SELECT STRING_AGG(x.ket, ', ')
                        FROM (
                            SELECT DISTINCT CAST(jns_permintaan AS varchar(max)) AS ket
                            FROM db_qc.tbl_bon_permintaan
                            WHERE refno = t.refno
                        ) x
                    ), '') AS ket

                FROM db_qc.tbl_bon_permintaan t
                $where
                GROUP BY t.refno
                ORDER BY MAX(t.id) DESC
            ";

        $stmt = sqlsrv_query($con, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        ?>
            <tr>
                <td><?php echo $no; ?></td>
                <td>'<?php echo $r['refno']; ?></td>
                <td><?php echo $r['nokk']; ?></td>
                <td><?php echo $r['dept']; ?></td>
                <td><?php echo $r['status']; ?></td>
                <td><?php echo $r['tgl_update']; ?></td>
                <td><?php echo $r['ket']; ?></td>
            </tr>
        <?php
            $no++;
        }
        ?>
    </table>
</body>