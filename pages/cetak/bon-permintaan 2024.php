<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Bon Permintaan Potong Sample dan Bongkaran</title>
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
<table height="61"  style="width:7.8in;" width="100%" border="0" class="table-list1">
  <tbody>
    <tr>
      <td width="83" rowspan="3" align="center"><img src="../../dist/img/ITTI_Logo 2021.png" width="50" height="49" alt=""/></td>
      <td width="462" rowspan="3" align="center"><font size="+1">
        <strong>BON PERMINTAAN POTONG SAMPLE DAN BONGKARAN</strong>
      </font></td>
      <td width="64" height="16">No. Form</td>
      <td width="16" align="center" valign="middle">:</td>
      <td width="102">19-14</td>
    </tr>
    <tr>
      <td height="21"  >No. Revisi</td>
      <td align="center" valign="middle"  >:</td>
      <td >04</td>
    </tr>
    <tr>
      <td height="14">Tgl. Terbit</td>
      <td align="center" valign="middle" >:</td>
      <td >11 Oktober 2018</td>
    </tr>
  </tbody>
</table>
<table style="width:7.8in;">
  <tbody>
    <tr>
      <td width="9%">&nbsp;</td>
      <td width="39%" height="17" align="right"><font size="+1"><strong><?php echo $_GET['bon'];?></strong></font></td>
      <td width="26%" align="right">&nbsp;</td>
      <td width="26%" align="right"><font size="-1">Tanggal</font>: <font size="-1"><?php echo date('d M Y', strtotime($_GET['tgl']));?></font></td>
    </tr>
  </tbody>
</table>
	
<table width="100%" border="0" class="table-list1" style="width:7.8in;">
  <tbody>
    <tr align="center" valign="middle">
      <td width="73">Tanggal Masuk Mutasi</td>
      <td width="86">Langganan</td>
      <td width="66">No. PO &amp; No. Order</td>
      <td width="96">Jenis Kain</td>
      <td width="78">Warna</td>
      <td width="45">No. Kartu Kerja</td>
      <td width="42">Roll</td>
      <td width="48">Berat</td>
      <td width="35">No. Demand</td>
      <td width="51">Tempat</td>
      <td width="83">Keterangan</td>
    </tr>
	 <?php
    $sqlc = mysqli_query($con,"SELECT
		a.*,
		count( b.sn ) AS rol,
		sum( b.berat ) AS berat,
		GROUP_CONCAT( DISTINCT b.tgl_mutasi SEPARATOR ', ' ) AS tgl_mutasi,
		SUBSTRING(GROUP_CONCAT( DISTINCT b.tempat SEPARATOR ', ' ),1,55) AS tempat
	FROM
		tbl_bon_permintaan a
		INNER JOIN tbl_bon_permintaan_detail b ON a.nokk = b.nokk AND a.`no_permintaan` = b.`no_permintaan`
	WHERE
		a.refno='$_GET[bon]' 
	GROUP BY
    a.id");
    $n=1;
    while($row = mysqli_fetch_array($sqlc)){
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
    <!-- <?php echo date('d M Y', strtotime(substr($row['tgl_mutasi'],0,10)));?> -->
      <td rowspan="2" align="center" valign="top"><input style="font-size: 9px;" name="tgl_mutasi" type="text" placeholder="" size="10" value="<?php echo date('d M Y', strtotime(substr($row['tgl_mutasi'],0,10)));?>"/></td>
      <td rowspan="2" align="left" valign="top"><font style="font-size: 7px;"><b title="<?php echo htmlentities($row['langganan'],ENT_QUOTES);?>"><?php echo htmlentities(substr($row['langganan'],0,35),ENT_QUOTES);?></b></font></td>
	    <td align="center" valign="top"><font style="font-size: 7px;"><b title="<?php echo htmlentities($row['no_po'],ENT_QUOTES);?>"><?php echo htmlentities(substr($row['no_po'],0,15),ENT_QUOTES);?></b></font></td>
      <td rowspan="2" valign="top"><font style="font-size: 7px;"><b title="<?php echo htmlentities($row['jenis_kain'],ENT_QUOTES);?>"><?php echo htmlentities(substr($row['jenis_kain'],0,40),ENT_QUOTES);?></b></font></td>
      <td rowspan="2" valign="top"><font style="font-size: 7px;"><b title="<?php echo htmlentities($row['warna'],ENT_QUOTES);?>"><?php echo htmlentities(substr($row['warna'],0,10)."...",ENT_QUOTES);?></b></font></td>
      <td rowspan="2" align="center" valign="top"><font style="font-size: 8px;"><?php echo substr($row['nokk'],0,8)."<br>".substr($row['nokk'],8,10);?></font></td>
      <td rowspan="2" align="center" valign="top"><?php if($row['jns_permintaan']=="Potong Sample" OR $row['jns_permintaan']=="Potong Pass Qty" OR $row['jns_permintaan']=="Bon Sample"){echo $rowd['JML_ROLL'];}else if($row['jns_permintaan']=="Bongkaran" AND $rowd['JML_ROLL']==0){echo number_format($rdt['no_rol_sblm'],0);}else if($row['jns_permintaan']=="Bongkaran" AND $rowd['JML_ROLL']>=0){echo number_format($rowd['JML_ROLL']+$rdt['no_rol_sblm'],0);}else if($row['jns_permintaan']=="Potong Sisa" AND $rowd['JML_ROLL']==0){echo number_format($rdt['no_rol_sblm'],0);}else if($row['jns_permintaan']=="Potong Sisa" AND $rowd['JML_ROLL']>=0){echo number_format($rowd['JML_ROLL'],0);}else if($row['jns_permintaan']=="Ganti Grade" AND $rowd['JML_ROLL']==0){echo number_format($rdt['no_rol_sblm'],0);}else if($row['jns_permintaan']=="Ganti Grade" AND $rowd['JML_ROLL']>=0){echo number_format($rowd['JML_ROLL']+$rdt['no_rol_sblm'],0);}?></td>
      <td rowspan="2" align="center" valign="top"><?php if($row['jns_permintaan']=="Potong Sample" OR $row['jns_permintaan']=="Potong Pass Qty" OR $row['jns_permintaan']=="Bon Sample"){echo number_format($rowd['TBERAT']+$rdt['berat_ptg_sblm'],2);}else if($row['jns_permintaan']=="Bongkaran" AND $rowd['JML_ROLL']=="0"){echo number_format($rdt['berat_sblm'],2);}else if($row['jns_permintaan']=="Bongkaran" AND $rowd['JML_ROLL']>=0){echo number_format($rowd['TBERAT']+$rdt['berat_sblm'],2);}else if($row['jns_permintaan']=="Potong Sisa" AND $rowd['JML_ROLL']==0){echo number_format($rdt['berat_sblm'],2);}else if($row['jns_permintaan']=="Potong Sisa" AND $rowd['JML_ROLL']>=0){echo number_format($rowd['TBERAT'],2);}else if($row['jns_permintaan']=="Ganti Grade" AND $rowd['JML_ROLL']=="0"){echo number_format($rdt['berat_sblm'],2);}else if($row['jns_permintaan']=="Ganti Grade" AND $rowd['JML_ROLL']>=0){echo number_format($rowd['TBERAT']+$rdt['berat_sblm'],2);}?></td>
      <td rowspan="2" align="center" valign="top"><?php echo $row['no_lot'];?></td>
      <td rowspan="2" align="center" valign="top"><font style="font-size: 7px;"><strong><?php echo $row['tempat'];?></strong></font></td>
      <td rowspan="2" align="left" valign="top"><font style="font-size: 7px;"><?php echo $row['jns_permintaan'];?><br><?php if($row['jns_permintaan']=="Potong Sample" OR $row['jns_permintaan']=="Potong Pass Qty" OR $row['jns_permintaan']=="Bon Sample"){echo $row['ket'].", brt ptg: ".$rdt['berat_ptg_sblm'];}else if($row['jns_permintaan']=="Potong Sisa"){echo "Pot. U/ Sisa";}else if($row['jns_permintaan']=="Ganti Grade"){echo "Ganti Grade";}else{echo $row['ket'].", jml roll: ".$rowb['jml_rol'].", jml brt: ".$rowb['berat'];}?></font></td>
    </tr>
    <tr >
      <td align="center" valign="top"><font style="font-size: 8px;"><?php echo $row['no_order'];?></font></td>
    </tr>
	<?php $n++;}?>  
    <?php 
	  if($n>1){$jml=$n-1;}else{$jml=0;}
	  for($i=1;$i<=8-$jml;$i++)
	  {?>
    <tr>
      <td rowspan="2" align="center">&nbsp;</td>
      <td rowspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td rowspan="2">&nbsp;</td>
      <td rowspan="2">&nbsp;</td>
      <td rowspan="2">&nbsp;</td>
      <td rowspan="2">&nbsp;</td>
      <td rowspan="2">&nbsp;</td>
      <td rowspan="2">&nbsp;</td>
      <td rowspan="2">&nbsp;</td>
      <td rowspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
	  <?php } ?>  
  </tbody>
</table>
<table width="100%" border="0" class="table-list1" style="width:7.8in;">
  <tbody>
    <tr align="center">
      <td width="13%">&nbsp;</td>
      <td colspan="3">Departemen User</td>
      <td colspan="3">Departemen Gudang kain jadi</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td width="12%" align="center">Diisi Oleh:</td>
      <td width="13%" align="center">Diperiksa Oleh:</td>
      <td width="12%" align="center">Diketahui Oleh:</td>
      <td width="12%" align="center">Diisi Oleh:</td>
      <td width="12%" align="center">Diperiksa Oleh:</td>
      <td width="12%" align="center">Diketahui Oleh:</td>
    </tr>
    <?php
      $sqlttd = mysqli_query($con,"SELECT
      a.*,
      count( b.sn ) AS rol,
      sum( b.berat ) AS berat,
      GROUP_CONCAT( DISTINCT b.tgl_mutasi SEPARATOR ', ' ) AS tgl_mutasi,
      GROUP_CONCAT( DISTINCT b.tempat SEPARATOR ', ' ) AS tempat,
      DATE_FORMAT(tgl_approve,'%H') AS jam
    FROM
      tbl_bon_permintaan a
      INNER JOIN tbl_bon_permintaan_detail b ON a.no_permintaan = b.no_permintaan 
      AND a.nokk = b.nokk 
    WHERE
      a.refno='$_GET[bon]' and DATE_FORMAT(a.tgl_update,'%Y-%m-%d')='$_GET[tgl]' 
    GROUP BY
      a.id
    ORDER BY 
      a.id ASC LIMIT 1");
      $rowdttd = mysqli_fetch_array($sqlttd);
		?>
    <tr>
      <td valign="top">Nama</td>
      <td align="center"><?php if(strlen($rowdttd['personil_buat'])>15){echo substr($rowdttd['personil_buat'],0,15);}else{echo $rowdttd['personil_buat'];}?></td>
      <td align="center"><?php if(strlen($rowdttd['personil_periksa'])>15){echo substr($rowdttd['personil_periksa'],0,15);}else{echo $rowdttd['personil_periksa'];}?></td>
      <td align="center"><?php if(strlen($rowdttd['personil_approve'])>15){echo substr($rowdttd['personil_approve'],0,15);}else{echo $rowdttd['personil_approve'];}?></td>
      <td align="center"><?php if(strlen($rowdttd['personil_proses'])>15){echo substr($rowdttd['personil_proses'],0,15);}else{echo $rowdttd['personil_proses'];}?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">Jabatan</td>
      <td align="center"><?php echo $rowdttd['jabatan_buat'];?></td>
      <td align="center"><?php echo $rowdttd['jabatan_periksa'];?></td>
      <td align="center"><?php echo $rowdttd['jabatan_approve'];?></td>
      <td align="center"><?php echo $rowdttd['jabatan_proses'];?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">Tanggal</td>
      <td align="center"><?php echo date("d M Y",strtotime($rowdttd['tgl_buat']));?></td>
      <td align="center"><?php echo date("d M Y",strtotime($rowdttd['tgl_periksa']));?></td>
      <td align="center"><?php echo date("d M Y",strtotime($rowdttd['tgl_approve']));?></td>
      <td align="center"><?php echo date("d M Y",strtotime($rowdttd['tgl_proses']));?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr style="height:0.7in;">
      <td valign="top">Tanda Tangan</td>
      <td align="center"><img src="../../dist/img/<?php echo $rowdttd['personil_buat'];?>.png" height="49" alt=""/></td>
      <td align="center"><img src="../../dist/img/<?php echo $rowdttd['personil_periksa'];?>.png" height="49" alt=""/></td>
      <td align="center"><img src="../../dist/img/<?php echo $rowdttd['personil_approve'];?>.png" height="49" alt=""/></td>
      <td align="center"><img src="../../dist/img/<?php echo $rowdttd['personil_proses'];?>.png" height="49" alt=""/></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
<table width="100%" border="0" style="width:7.8in;">
    <tr align="right">
      <td style="font-size: 10px;"><span style="border-top:0px #000000 solid; 
        border-bottom:0px #000000 solid;
        border-left:0px #000000 solid; 
        border-right:0px #000000 solid;"><?php if(($rowdttd['jam']>7 AND $rowdttd['jam']<15) OR $rowdttd['jabatan_approve']=="Assistant Manager"){}else{echo $rowdttd['tgl_approve'];}?></td>
    </tr>
</table>
</body>
</html>
