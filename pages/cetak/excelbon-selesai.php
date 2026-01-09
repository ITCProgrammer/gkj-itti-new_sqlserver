<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=bon-selesai-".substr($_GET['awal'],0,10)."_".substr($_GET['akhir'],0,10).".xls");//ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
?>
<?php 
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Laporan BON Selesai</title>	

</head>

<body>
<table width="100%">
    <thead>
      <tr>
        <td><table width="100%" border="1" class="table-list1"> 
          <tr>
			<td width="83" align="center"><img src="../../dist/img/ITTI_Logo 2021.png" width="50" height="49" alt=""/></td>  
            <td align="center"><strong><font size="+1">BON PERMINTAAN POTONG SAMPLE DAN BONGKARAN</font><br />
              <font size="-1">PERIODE: <?php echo date("d F Y", strtotime($_GET['awal']));?> - <?php echo date("d F Y", strtotime($_GET['akhir']));?></font>
              </strong></td>
          </tr>
        </table></td>
      </tr>
	  </thead>
    <tr>
      <td><table width="100%" border="1" class="table-list1">
        <thead>
          <tr align="center">
            <td><font size="-2"><strong>TGL BON</strong></font></td>
            <td><font size="-2"><strong>NO BON</strong></font></td>
            <td><font size="-2"><strong>NO PERMINTAAN</strong></font></td>
            <td><font size="-2"><strong>TGL MSK MUTASI</strong></font></td>
            <td><font size="-2"><strong>LANGGANAN</strong></font></td>
            <td><font size="-2"><strong>PO NO</strong></font></td>
            <td><font size="-2"><strong>NO ORDER</strong></font></td>
            <td><font size="-2"><strong>JENIS KAIN</strong></font></td>
            <td><font size="-2"><strong>WARNA</strong></font></td>
            <td><font size="-2"><strong>ROLL</strong></font></td>
            <td><font size="-2"><strong>BERAT</strong></font></td>
            <td><font size="-2"><strong>NO LOT</strong></font></td>
            <td><font size="-2"><strong>TEMPAT</strong></font></td>
            <td><font size="-2"><strong>KETERANGAN</strong></font></td>
            <td><font size="-2"><strong>MASALAH</strong></font></td>
            <td><font size="-2"><strong>PENYEBAB</strong></font></td>
          </tr>
        </thead>
        <tbody>
        <?php
        $sqldata 	= mysqli_query($con,"SELECT
        a.*,
		count( b.sn ) AS rol,
		sum( b.berat ) AS berat,
		GROUP_CONCAT( DISTINCT b.tgl_mutasi SEPARATOR ', ' ) AS tgl_mutasi,
		SUBSTRING(GROUP_CONCAT( DISTINCT b.tempat SEPARATOR ', ' ),1,55) AS tempat
      FROM
        tbl_bon_permintaan a
		INNER JOIN tbl_bon_permintaan_detail b ON a.nokk = b.nokk AND a.`no_permintaan` = b.`no_permintaan`
      WHERE
        not ISNULL( a.refno ) AND a.dept='QCF' AND DATE_FORMAT(a.tgl_buat,'%Y-%m-%d') BETWEEN '$_GET[awal]' AND '$_GET[akhir]' AND a.`status`='Selesai'
      GROUP BY
        a.id");
       while ($row=mysqli_fetch_array($sqldata)) {
      $sqld = "SELECT COUNT(BALANCE.ELEMENTSCODE) AS JML_ROLL, SUM(BALANCE.BASEPRIMARYQUANTITYUNIT) AS TBERAT FROM BALANCE BALANCE WHERE BALANCE.LOTCODE ='$row[nokk]' AND BALANCE.LOGICALWAREHOUSECODE ='M031' AND NOT (BALANCE.WHSLOCATIONWAREHOUSEZONECODE='B1' OR BALANCE.WHSLOCATIONWAREHOUSEZONECODE='TMP' OR BALANCE.WHSLOCATIONWAREHOUSEZONECODE='DOK')";
      $stmt=db2_exec($conn1,$sqld, array('cursor'=>DB2_SCROLLABLE));
      $rowd = db2_fetch_assoc($stmt);
      $sqldt = mysqli_query($con,"SELECT COUNT(sn) AS no_rol_sblm, SUM(berat) AS berat_sblm, SUM(berat_potong) AS berat_ptg_sblm 
      FROM tbl_bon_permintaan_detail WHERE nokk='$row[nokk]' AND no_permintaan='$row[no_permintaan]'");
      $rdt = mysqli_fetch_array($sqldt);
      $sqlb = mysqli_query($con,"SELECT COUNT(sn) AS jml_rol, SUM(berat) AS berat FROM tbl_bon_permintaan_detail WHERE nokk='$row[nokk]' AND no_permintaan='$row[no_permintaan]'");
      $rowb = mysqli_fetch_array($sqlb);
        ?>
          <tr>
            <td align="center" valign="top"><?php echo date("d-M-y", strtotime($row['tgl_buat']));?></td>
            <td align="center" valign="top"><?php echo $row['refno'];?></td>
            <td align="center" valign="top"><?php echo $row['no_permintaan'];?></td>
            <td align="center" valign="top"><?php echo date("d-M-y", strtotime($row['tgl_mutasi']));?></td>
            <td align="left" valign="top"><?php echo $row['langganan'];?></td>
            <td align="left" valign="top"><?php echo $row['no_po'];?></td>
            <td align="left" valign="top"><?php echo $row['no_order'];?></td>
            <td align="left" valign="top"><?php echo $row['jenis_kain'];?></td>
            <td align="left" valign="top"><?php echo $row['warna'];?></td>
            <td align="center" valign="top"><?php if($row['jns_permintaan']=="Potong Sample" OR $row['jns_permintaan']=="Potong Pass Qty"){echo $rowd['jml_roll'];}elseif($row['jns_permintaan']=="Bongkaran" AND $rowd['jml_roll']==0){echo number_format($rdt['no_rol_sblm'],0);}elseif($row['jns_permintaan']=="Bongkaran" AND $rowd['jml_roll']>=0){echo number_format($rowd['jml_roll']+$rdt['no_rol_sblm'],0);}else if($row['jns_permintaan']=="Potong Sisa" AND $rowd['jml_roll']==0){echo number_format($rdt['no_rol_sblm'],0);}else if($row['jns_permintaan']=="Potong Sisa" AND $rowd['jml_roll']>=0){echo number_format($rowd['jml_roll']-$rdt['no_rol_sblm'],0);}?></td>
            <td align="center" valign="top"><?php if($row['jns_permintaan']=="Potong Sample" OR $row['jns_permintaan']=="Potong Pass Qty"){echo number_format($rowd['tberat']+$rdt['berat_ptg_sblm'],2);}elseif($row['jns_permintaan']=="Bongkaran" AND $rowd['jml_roll']=="0"){echo number_format($rdt['berat_sblm'],2);}else if($row['jns_permintaan']=="Bongkaran" AND $rowd['jml_roll']>=0){echo number_format($rowd['tberat']+$rdt['berat_sblm'],2);}else if($row['jns_permintaan']=="Potong Sisa" AND $rowd['jml_roll']==0){echo number_format($rdt['berat_sblm'],2);}else if($row['jns_permintaan']=="Potong Sisa" AND $rowd['jml_roll']>=0){echo number_format($rowd['tberat'],2);}?></td>
            <td align="center" valign="top"><?php echo $row['no_lot'];?></td>
            <td align="center" valign="top"><?php echo $row['tempat'];?></td>
            <td align="left" valign="top"><?php echo $row['jns_permintaan'].", ".$row['ket'];?></td>
            <td align="center" valign="top"><?php echo $row['masalah'];?></td>
            <td align="center" valign="top"><?php  if($row['penyebab_qc']=="1"){echo "QCF"; }else{ echo "x";} ?></td>
          </tr>
        <?php }?> 
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
</table>
</body>
</html>
