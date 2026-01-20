<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Laporan TG</title>
  <link href="./styles_cetak.css" rel="stylesheet" type="text/css">
  <style>
    du {
      text-decoration-line: underline;
      text-decoration-style: double;
    }

    input {
      text-align: center;
      border: hidden;
    }

    .table-list2 {
      clear: both;
      text-align: left;
      border-collapse: collapse;
      margin: 0px 0px 10px 0px;
      background: #fff;
    }

    .table-list2 td {
      color: #333;
      font-size: 7px;
      border-color: #fff;
      border-collapse: collapse;
      vertical-align: center;
      padding: 1px 3px;
      border: 1px #000000 solid;
    }

    .noborder {
      color: #333;
      font-size: 12px;
      border-color: #FFF;
      border-collapse: collapse;
      vertical-align: center;
      padding: 3px 5px;
    }

    #nocetak {
      display: none;
    }
  </style>
</head>

<body>
  <table width="100%">
    <thead>
      <tr>
        <td>
          <table width="100%" border="1" class="table-list1">
            <tr>
              <td align="center">
                <strong>
                  <font size="+1">LAPORAN TOLAKAN GUDANG KAIN JADI</font><br />
                  <font size="-1">PERIODE: <?php echo date("d F Y", strtotime($_GET['awal'])); ?></font>
                  <br />
                  <font size="-1">FW-19-GKJ-12/05</font>
                </strong>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </thead>

    <tr>
      <td>
        <table width="100%" border="1" class="table-list1">
          <thead>
            <tr align="center">
              <td>
                <font size="-2"><strong>TGL BON</strong></font>
              </td>
              <td>
                <font size="-2"><strong>TGL MSK MUTASI</strong></font>
              </td>
              <td>
                <font size="-2"><strong>NO BON</strong></font>
              </td>
              <td>
                <font size="-2"><strong>LANGGANAN</strong></font>
              </td>
              <td>
                <font size="-2"><strong>PO NO</strong></font>
              </td>
              <td>
                <font size="-2"><strong>ORD NO</strong></font>
              </td>
              <td>
                <font size="-2"><strong>JENIS KAIN</strong></font>
              </td>
              <td>
                <font size="-2"><strong>NO KK</strong></font>
              </td>
              <td>
                <font size="-2"><strong>WARNA</strong></font>
              </td>
              <td>
                <font size="-2"><strong>ROLL</strong></font>
              </td>
              <td>
                <font size="-2"><strong>BERAT NETTO</strong></font>
              </td>
              <td>
                <font size="-2"><strong>BERAT TG</strong></font>
              </td>
              <td>
                <font size="-2"><strong>NO LOT</strong></font>
              </td>
              <td>
                <font size="-2"><strong>KET</strong></font>
              </td>
              <td>
                <font size="-2"><strong>LOKASI</strong></font>
              </td>
            </tr>
          </thead>

          <tbody>
            <?php
            $awal  = $_GET['awal'] ?? '';
            $akhir = $_GET['akhir'] ?? '';

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
                  ";

            $stmt = sqlsrv_query($con, $sql, [$awal, $akhir]);
            if ($stmt === false) {
              die(print_r(sqlsrv_errors(), true));
            }

            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

              // ====== DB2 query ======
              $sqld = "SELECT COUNT(BALANCE.ELEMENTSCODE) AS JML_ROLL, SUM(BALANCE.BASEPRIMARYQUANTITYUNIT) AS TBERAT
                   FROM BALANCE BALANCE
                   WHERE BALANCE.LOTCODE ='$row[nokk]'
                     AND BALANCE.LOGICALWAREHOUSECODE ='M031'
                     AND NOT (BALANCE.WHSLOCATIONWAREHOUSEZONECODE='B1'
                              OR BALANCE.WHSLOCATIONWAREHOUSEZONECODE='TMP'
                              OR BALANCE.WHSLOCATIONWAREHOUSEZONECODE='DOK')";
              $stmt2 = db2_exec($conn1, $sqld, array('cursor' => DB2_SCROLLABLE));
              $rowd  = db2_fetch_assoc($stmt2);

              $sqldt = "
                        SELECT
                          COUNT(no_rol) AS no_rol_sblm,
                          SUM(berat) AS berat_sblm,
                          SUM(berat_potong) AS berat_ptg_sblm
                        FROM db_qc.tbl_bon_permintaan_detail
                        WHERE nokk = ? AND no_permintaan = ?
                      ";
              $stmtdt = sqlsrv_query($con, $sqldt, [$row['nokk'], $row['no_permintaan']]);
              if ($stmtdt === false) {
                die(print_r(sqlsrv_errors(), true));
              }
              $rdt = sqlsrv_fetch_array($stmtdt, SQLSRV_FETCH_ASSOC);
            ?>
              <tr>
                <td align="center" valign="top">
                  <?php
                      $tglBon = $row['tgl_buat'];
                      echo ($tglBon instanceof DateTime) ? $tglBon->format('d-M-y') : date('d-M-y', strtotime($tglBon));
                  ?>
                </td>

                <td align="center" valign="top">
                  <?php
                  $tglMut = $row['tgl_mutasi'];
                  if ($tglMut != '') {
                    $first = substr($tglMut, 0, 10);
                    echo date('d-M-y', strtotime($first));
                  }
                  ?>
                </td>

                <td align="center" valign="top"><?php echo $row['refno']; ?></td>
                <td align="left" valign="top"><?php echo $row['langganan']; ?></td>
                <td align="left" valign="top"><?php echo $row['no_po']; ?></td>
                <td align="left" valign="top"><?php echo $row['no_order']; ?></td>
                <td align="left" valign="top"><?php echo $row['jenis_kain']; ?></td>
                <td align="left" valign="top"><?php echo $row['nokk']; ?></td>
                <td align="left" valign="top"><?php echo $row['warna']; ?></td>

                <td align="center" valign="top">
                  <?php
                  $jml_roll = (float)($rowd['JML_ROLL'] ?? 0);
                  $no_rol_sblm = (float)($rdt['no_rol_sblm'] ?? 0);

                  if ($row['jns_permintaan'] == "Potong Sample" || $row['jns_permintaan'] == "Potong Pass Qty") {
                    echo $jml_roll;
                  } elseif ($row['jns_permintaan'] == "Bongkaran" && $jml_roll == 0) {
                    echo number_format($no_rol_sblm, 0);
                  } elseif ($row['jns_permintaan'] == "Bongkaran" && $jml_roll >= 0) {
                    echo number_format($jml_roll + $no_rol_sblm, 0);
                  } elseif ($row['jns_permintaan'] == "Potong Sisa" && $jml_roll == 0) {
                    echo number_format($no_rol_sblm, 0);
                  } elseif ($row['jns_permintaan'] == "Potong Sisa" && $jml_roll >= 0) {
                    echo number_format($jml_roll - $no_rol_sblm, 0);
                  }
                  ?>
                </td>

                <td align="center" valign="top">
                  <?php
                  $tberat = (float)($rowd['TBERAT'] ?? 0);
                  $berat_sblm = (float)($rdt['berat_sblm'] ?? 0);
                  $berat_ptg_sblm = (float)($rdt['berat_ptg_sblm'] ?? 0);

                  if ($row['jns_permintaan'] == "Potong Sample" || $row['jns_permintaan'] == "Potong Pass Qty") {
                    echo number_format($tberat + $berat_ptg_sblm, 2);
                  } elseif ($row['jns_permintaan'] == "Bongkaran" && $jml_roll == 0) {
                    echo number_format($berat_sblm, 2);
                  } elseif ($row['jns_permintaan'] == "Bongkaran" && $jml_roll >= 0) {
                    echo number_format($tberat + $berat_sblm, 2);
                  } elseif ($row['jns_permintaan'] == "Potong Sisa" && $jml_roll == 0) {
                    echo number_format($berat_sblm, 2);
                  } elseif ($row['jns_permintaan'] == "Potong Sisa" && $jml_roll >= 0) {
                    echo number_format($tberat, 2);
                  }
                  ?>
                </td>

                <td align="center" valign="top">
                  <?php
                  $bp = $row['berat_potong'];
                  if ($bp === null || $bp === '') echo "0";
                  else echo $bp;
                  ?>
                </td>

                <td align="center" valign="top"><?php echo $row['no_lot']; ?></td>
                <td align="left" valign="top"><?php echo $row['jns_permintaan'] . ", " . $row['ket']; ?></td>
                <td align="center" valign="top"><?php echo $row['tempat']; ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </td>
    </tr>

    <tr>
      <td>
        <table border="0" class="table-list1" width="100%">
          <tr align="center">
            <td width="14%">&nbsp;</td>
            <td width="17%">Dibuat Oleh :</td>
            <td width="14%">Diperiksa Oleh :</td>
            <td width="14%">Diketahui Oleh :</td>
          </tr>
          <tr>
            <td>Nama</td>
            <td align="center">Ridwan</td>
            <td align="center">Tardo</td>
            <td align="center">Gamayel Agung Wibowo</td>
          </tr>
          <tr>
            <td>Jabatan</td>
            <td align="center">Clerk</td>
            <td align="center">Asst. Supervisor</td>
            <td align="center">Supervisor</td>
          </tr>
          <tr>
            <td>Tanggal</td>
            <td align="center"><?php echo date("d-M-y"); ?></td>
            <td align="center"><?php echo date("d-M-y"); ?></td>
            <td align="center"><?php echo date("d-M-y"); ?></td>
          </tr>
          <tr>
            <td valign="top" style="height: 0.6in;">Tanda Tangan</td>
            <td align="center"><img src="../../dist/img/ttdridwangkj.png" width="100" height="49" alt="" /></td>
            <td align="center"><img src="../../dist/img/tardo.png" width="50" height="49" alt="" /></td>
            <td align="center"></td>
          </tr>
        </table>
      </td>
    </tr>

  </table>
</body>

</html>