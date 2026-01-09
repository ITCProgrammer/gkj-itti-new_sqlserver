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
du
{
    text-decoration-line: underline;
    text-decoration-style: double;
}

input{
text-align:center;
border:hidden;
}
.table-list2 {
	clear: both;
	text-align: left;
	border-collapse: collapse;
	margin: 0px 0px 10px 0px;
	background:#fff;	
}
.table-list2 td {
	color: #333;
	font-size:7px;
	border-color: #fff;
	border-collapse: collapse;
	vertical-align: center;
	padding: 1px 3px;
	border-bottom:1px #000000 solid;
	border-top:1px #000000 solid;
	border-left:1px #000000 solid;
	border-right:1px #000000 solid;

	
}

.noborder{
	color: #333;
	font-size:12px;
	border-color: #FFF;
	border-collapse: collapse;
	vertical-align: center;
	padding: 3px 5px;
	
	}
#nocetak {
	display:none;
	}

</style>
</head>

<body>
<table width="100%">
    <thead>
      <tr>
        <td><table width="100%" border="1" class="table-list1"> 
          <tr>
            <td align="center"><strong><font size="+1">LAPORAN TOLAKAN GUDANG KAIN JADI</font><br />
              <font size="-1">PERIODE: <?php echo date("d F Y", strtotime($_GET['awal']));?></font>
              <br />
            <font size="-1">FW-19-GKJ-12/05</font></strong></td>
          </tr>
        </table></td>
      </tr>
	  </thead>
    <tr>
      <td><table width="100%" border="1" class="table-list1">
        <thead>
          <tr align="center">
            <td><font size="-2"><strong>TGL BON</strong></font></td>
            <td><font size="-2"><strong>TGL MSK MUTASI</strong></font></td>
            <td><font size="-2"><strong>NO BON</strong></font></td>
            <td><font size="-2"><strong>LANGGANAN</strong></font></td>
            <td><font size="-2"><strong>PO NO</strong></font></td>
            <td><font size="-2"><strong>ORD NO</strong></font></td>
            <td><font size="-2"><strong>JENIS KAIN</strong></font></td>
            <td><font size="-2"><strong>NO KK</strong></font></td>
            <td><font size="-2"><strong>WARNA</strong></font></td>
            <td><font size="-2"><strong>ROLL</strong></font></td>
            <td><font size="-2"><strong>BERAT NETTO</strong></font></td>
            <td><font size="-2"><strong>BERAT TG</strong></font></td>
            <td><font size="-2"><strong>NO LOT</strong></font></td>
            <td><font size="-2"><strong>KET</strong></font></td>
            <td><font size="-2"><strong>LOKASI</strong></font></td>
          </tr>
        </thead>
        <tbody>
        <?php
        $sqldata 	= mysqli_query($con,"SELECT
        a.*,
        count( b.no_rol ) AS rol,
        sum( b.berat ) AS berat,
        SUM( b.berat_potong ) AS berat_potong,
        GROUP_CONCAT( DISTINCT b.tgl_mutasi SEPARATOR ', ' ) AS tgl_mutasi,
        GROUP_CONCAT( DISTINCT b.tempat SEPARATOR ', ' ) AS tempat
      FROM
        tbl_bon_permintaan a
        INNER JOIN tbl_bon_permintaan_detail b ON a.no_permintaan = b.no_permintaan 
        AND a.nokk = b.nokk 
      WHERE
        DATE_FORMAT(a.tgl_buat,'%Y-%m-%d') BETWEEN '$_GET[awal]' AND '$_GET[akhir]' 
      GROUP BY
        a.id");
       while ($row=mysqli_fetch_array($sqldata)) {
        $sqld = "SELECT COUNT(BALANCE.ELEMENTSCODE) AS JML_ROLL, SUM(BALANCE.BASEPRIMARYQUANTITYUNIT) AS TBERAT FROM BALANCE BALANCE WHERE BALANCE.LOTCODE ='$row[nokk]' AND BALANCE.LOGICALWAREHOUSECODE ='M031' AND NOT (BALANCE.WHSLOCATIONWAREHOUSEZONECODE='B1' OR BALANCE.WHSLOCATIONWAREHOUSEZONECODE='TMP' OR BALANCE.WHSLOCATIONWAREHOUSEZONECODE='DOK')";
        $stmt=db2_exec($conn1,$sqld, array('cursor'=>DB2_SCROLLABLE));
        $rowd = db2_fetch_assoc($stmt);

        $sqldt= mysqli_query($con,"SELECT COUNT(no_rol) AS no_rol_sblm, SUM(berat) AS berat_sblm, SUM(berat_potong) AS berat_ptg_sblm 
		    FROM tbl_bon_permintaan_detail WHERE nokk='$row[nokk]' AND no_permintaan='$row[no_permintaan]'");
        $rdt=mysqli_fetch_array($sqldt);
        $sqlb = mysqli_query($con,"SELECT COUNT(sn) AS jml_rol, SUM(berat) AS berat FROM tbl_bon_permintaan_detail WHERE nokk='$row[nokk]' AND no_permintaan='$row[no_permintaan]'");
        $rowb = mysqli_fetch_array($sqlb);
        ?>
          <tr>
            <td align="center" valign="top"><?php echo date("d-M-y", strtotime($row['tgl_buat']));?></td>
            <td align="center" valign="top"><?php echo date("d-M-y", strtotime($row['tgl_mutasi']));?></td>
            <td align="center" valign="top"><?php echo $row['refno'];?></td>
            <td align="left" valign="top"><?php echo $row['langganan'];?></td>
            <td align="left" valign="top"><?php echo $row['no_po'];?></td>
            <td align="left" valign="top"><?php echo $row['no_order'];?></td>
            <td align="left" valign="top"><?php echo $row['jenis_kain'];?></td>
            <td align="left" valign="top"><?php echo $row['nokk'];?></td>
            <td align="left" valign="top"><?php echo $row['warna'];?></td>
            <td align="center" valign="top"><?php if($row['jns_permintaan']=="Potong Sample" OR $row['jns_permintaan']=="Potong Pass Qty"){echo $rowd['jml_roll'];}elseif($row['jns_permintaan']=="Bongkaran" AND $rowd['jml_roll']==0){echo number_format($rdt['no_rol_sblm'],0);}elseif($row['jns_permintaan']=="Bongkaran" AND $rowd['jml_roll']>=0){echo number_format($rowd['jml_roll']+$rdt['no_rol_sblm'],0);}else if($row['jns_permintaan']=="Potong Sisa" AND $rowd['jml_roll']==0){echo number_format($rdt['no_rol_sblm'],0);}else if($row['jns_permintaan']=="Potong Sisa" AND $rowd['jml_roll']>=0){echo number_format($rowd['jml_roll']-$rdt['no_rol_sblm'],0);}?></td>
            <td align="center" valign="top"><?php if($row['jns_permintaan']=="Potong Sample" OR $row['jns_permintaan']=="Potong Pass Qty"){echo number_format($rowd['tberat']+$rdt['berat_ptg_sblm'],2);}elseif($row['jns_permintaan']=="Bongkaran" AND $rowd['jml_roll']=="0"){echo number_format($rdt['berat_sblm'],2);}else if($row['jns_permintaan']=="Bongkaran" AND $rowd['jml_roll']>=0){echo number_format($rowd['tberat']+$rdt['berat_sblm'],2);}else if($row['jns_permintaan']=="Potong Sisa" AND $rowd['jml_roll']==0){echo number_format($rdt['berat_sblm'],2);}else if($row['jns_permintaan']=="Potong Sisa" AND $rowd['jml_roll']>=0){echo number_format($rowd['tberat'],2);}?></td>
            <td align="center" valign="top"><?php if($row['berat_potong']!=NULL or $row['berat_potong']!=""){echo $row['berat_potong'];}else{echo "0";}?></td>
            <td align="center" valign="top"><?php echo $row['no_lot'];?></td>
            <td align="left" valign="top"><?php echo $row['jns_permintaan'].", ".$row['ket'];?></td>
            <td align="center" valign="top"><?php echo $row['tempat'];?></td>
          </tr>
        <?php }?> 
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td><table border="0" class="table-list1" width="100%">
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
          <td align="center"><?php echo date("d-M-y");?></td>
          <td align="center"><?php echo date("d-M-y");?></td>
          <td align="center"><?php echo date("d-M-y");?></td>
        </tr>
        <tr>
          <td valign="top" style="height: 0.6in;" >Tanda Tangan</td>
          <td align="center"><img src="../../dist/img/ttdridwangkj.png" width="100" height="49" alt=""/></td>
          <td align="center"><img src="../../dist/img/tardo.png" width="50" height="49" alt=""/></td>
          <td align="center"></td>
        </tr>
      </table></td>
    </tr>
</table>
</body>
</html>
