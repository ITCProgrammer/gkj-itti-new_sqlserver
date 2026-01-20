<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=laporan-tg-" . substr($_GET['awal'], 0, 10) . "_" . substr($_GET['akhir'], 0, 10) . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

$Awal  = $_GET['awal'] ?? '';
$Akhir = $_GET['akhir'] ?? '';
?>

<body>
    <strong>Laporan Tolakan Gudang Kain Jadi</strong><br>
    <strong>Periode: <?php echo $Awal; ?> s/d <?php echo $Akhir; ?></strong><br>

    <table width="100%" border="1">
        <tr>
            <th bgcolor="#12C9F0">TGL BON</th>
            <th bgcolor="#12C9F0">TGL MSK MUTASI</th>
            <th bgcolor="#12C9F0">NO BON</th>
            <th bgcolor="#12C9F0">LANGGANAN</th>
            <th bgcolor="#12C9F0">PO NO</th>
            <th bgcolor="#12C9F0">ORD NO</th>
            <th bgcolor="#12C9F0">JENIS KAIN</th>
            <th bgcolor="#12C9F0">NO KK</th>
            <th bgcolor="#12C9F0">WARNA</th>
            <th bgcolor="#12C9F0">ROLL</th>
            <th bgcolor="#12C9F0">BERAT NETTO</th>
            <th bgcolor="#12C9F0">BERAT TG</th>
            <th bgcolor="#12C9F0">NO LOT</th>
            <th bgcolor="#12C9F0">KET</th>
            <th bgcolor="#12C9F0">LOKASI</th>
        </tr>

        <?php
        $sql = "
                SELECT
                    a.id,
                    a.no_permintaan,
                    a.refno,
                    a.nokk,
                    a.langganan,
                    a.no_po,
                    a.no_order,
                    a.jenis_kain,
                    a.warna,
                    a.no_lot,
                    a.jns_permintaan,
                    a.ket,
                    a.tgl_buat,

                    COUNT(b.no_rol) AS rol,
                    SUM(b.berat) AS berat,
                    SUM(b.berat_potong) AS berat_potong,

                    COALESCE((
                    SELECT STRING_AGG(x.tgl_mutasi, ', ')
                    FROM (
                        SELECT DISTINCT CONVERT(varchar(10), tgl_mutasi, 23) AS tgl_mutasi
                        FROM db_qc.tbl_bon_permintaan_detail
                        WHERE nokk = a.nokk AND no_permintaan = a.no_permintaan
                    ) x
                    ), '') AS tgl_mutasi,

                    COALESCE((
                    SELECT STRING_AGG(x.tempat, ', ')
                    FROM (
                        SELECT DISTINCT CAST(tempat AS varchar(max)) AS tempat
                        FROM db_qc.tbl_bon_permintaan_detail
                        WHERE nokk = a.nokk AND no_permintaan = a.no_permintaan
                    ) x
                    ), '') AS tempat

                FROM db_qc.tbl_bon_permintaan a
                INNER JOIN db_qc.tbl_bon_permintaan_detail b
                    ON a.no_permintaan = b.no_permintaan
                AND a.nokk = b.nokk
                WHERE CONVERT(date, a.tgl_buat) BETWEEN ? AND ?
                GROUP BY
                    a.id,
                    a.no_permintaan,
                    a.refno,
                    a.nokk,
                    a.langganan,
                    a.no_po,
                    a.no_order,
                    a.jenis_kain,
                    a.warna,
                    a.no_lot,
                    a.jns_permintaan,
                    a.ket,
                    a.tgl_buat
                ORDER BY a.tgl_buat ASC, a.id ASC
                ";

        $stmt = sqlsrv_query($con, $sql, [$Awal, $Akhir]);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $sqlStock = "
                            SELECT
                            COUNT(d.no_roll) AS jml_roll,
                            SUM(d.weight) AS tberat
                            FROM db_qc.detail_pergerakan_stok d
                            INNER JOIN db_qc.pergerakan_stok p ON d.id_stok = p.id
                            WHERE d.nokk = ?
                            AND d.[status] = '1'
                            AND d.transtatus = '1'
                        ";
            $stmtStock = sqlsrv_query($con, $sqlStock, [$r['nokk']]);
            if ($stmtStock === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $row = sqlsrv_fetch_array($stmtStock, SQLSRV_FETCH_ASSOC);

            $sql2 = "
                        SELECT
                        COUNT(no_rol) AS no_rol_sblm,
                        SUM(berat) AS berat_sblm,
                        SUM(berat_potong) AS berat_ptg_sblm
                        FROM db_qc.tbl_bon_permintaan_detail
                        WHERE nokk = ? AND no_permintaan = ?
                    ";
            $stmt2 = sqlsrv_query($con, $sql2, [$r['nokk'], $r['no_permintaan']]);
            if ($stmt2 === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);

            $tglBuat = $r['tgl_buat'];
            $tglBuatStr = ($tglBuat instanceof DateTime) ? $tglBuat->format('d-M-y') : date('d-M-y', strtotime((string)$tglBuat));

            $tglMutStr = '';
            if (!empty($r['tgl_mutasi'])) {
                $first = substr($r['tgl_mutasi'], 0, 10);
                $tglMutStr = date('d-M-y', strtotime($first));
            }

            $jml_roll = (float)($row['jml_roll'] ?? 0);
            $tberat   = (float)($row['tberat'] ?? 0);
            $no_rol_sblm    = (float)($row2['no_rol_sblm'] ?? 0);
            $berat_sblm     = (float)($row2['berat_sblm'] ?? 0);
            $berat_ptg_sblm = (float)($row2['berat_ptg_sblm'] ?? 0);

            // ===== Output roll =====
            if ($r['jns_permintaan'] == "Potong Sample" || $r['jns_permintaan'] == "Potong Pass Qty") {
                $roll_out = $jml_roll;
            } elseif ($r['jns_permintaan'] == "Bongkaran" && $jml_roll == 0) {
                $roll_out = number_format($no_rol_sblm, 0);
            } elseif ($r['jns_permintaan'] == "Bongkaran" && $jml_roll >= 0) {
                $roll_out = number_format($jml_roll + $no_rol_sblm, 0);
            } elseif ($r['jns_permintaan'] == "Potong Sisa" && $jml_roll == 0) {
                $roll_out = number_format($no_rol_sblm, 0);
            } elseif ($r['jns_permintaan'] == "Potong Sisa" && $jml_roll >= 0) {
                $roll_out = number_format($jml_roll - $no_rol_sblm, 0);
            } else {
                $roll_out = $jml_roll;
            }

            // ===== Output berat =====
            if ($r['jns_permintaan'] == "Potong Sample" || $r['jns_permintaan'] == "Potong Pass Qty") {
                $berat_out = number_format($tberat + $berat_ptg_sblm, 2);
            } elseif ($r['jns_permintaan'] == "Bongkaran" && $jml_roll == 0) {
                $berat_out = number_format($berat_sblm, 2);
            } elseif ($r['jns_permintaan'] == "Bongkaran" && $jml_roll >= 0) {
                $berat_out = number_format($tberat + $berat_sblm, 2);
            } elseif ($r['jns_permintaan'] == "Potong Sisa" && $jml_roll == 0) {
                $berat_out = number_format($berat_sblm, 2);
            } elseif ($r['jns_permintaan'] == "Potong Sisa" && $jml_roll >= 0) {
                $berat_out = number_format($tberat, 2);
            } else {
                $berat_out = number_format($tberat, 2);
            }

            $bp = $r['berat_potong'];
            $bp_out = ($bp === null || $bp === '') ? "0" : $bp;
        ?>
            <tr>
                <td><?php echo $tglBuatStr; ?></td>
                <td><?php echo $tglMutStr; ?></td>
                <td><?php echo "'" . $r['refno']; ?></td>
                <td><?php echo $r['langganan']; ?></td>
                <td><?php echo $r['no_po']; ?></td>
                <td><?php echo $r['no_order']; ?></td>
                <td><?php echo $r['jenis_kain']; ?></td>
                <td><?php echo $r['nokk']; ?></td>
                <td><?php echo $r['warna']; ?></td>
                <td><?php echo $roll_out; ?></td>
                <td><?php echo $berat_out; ?></td>
                <td><?php echo $bp_out; ?></td>
                <td><?php echo $r['no_lot']; ?></td>
                <td><?php echo $r['jns_permintaan'] . ", " . $r['ket']; ?></td>
                <td><?php echo $r['tempat']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>