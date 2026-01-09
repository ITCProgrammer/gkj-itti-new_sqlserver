<?php
include_once '../../../config/conn.php';
include_once '../../../controllers/permintaanClass.php';
// instance objek
$permintaan = new Permintaan();
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
      <td width="83" rowspan="3" align="center"><img src="../../../dist/img/ITTI_Logo 2021.png" width="50" height="49" alt=""/></td>
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
      <td width="35">No. lot</td>
      <td width="51">Tempat</td>
      <td width="83">Keterangan</td>
    </tr>
	 <?php
    if (is_array($permintaan->tampildataboncetak($_GET['bon'])) || is_object($permintaan->tampildataboncetak($_GET['bon']))){
 	 $n=1;
		foreach($permintaan->tampildataboncetak($_GET['bon']) as $row){
      foreach($permintaan->tampilnorolcetak($row['refno'],$row['nokk']) as $rowrol){}
      foreach($permintaan->tampilboncetak($row['nokk']) as $rowd){}
      foreach($permintaan->tampilhitungsebelum($row['nokk']) as $rdt){}
      foreach($permintaan->tampilketbongkaran($row['nokk']) as $rowb){}
					?>	 
    <tr>
      <td rowspan="2" align="center" valign="top"><font style="font-size: 9px;"><?php echo date('d M Y', strtotime($row['tgl_mutasi']));?></font></td>
      <td rowspan="2" align="left" valign="top"><font style="font-size: 7px;"><?php echo $row['langganan'];?></font></td>
	  <td align="center" valign="top"><font style="font-size: 8px;"><?php echo $row['no_po'];?></font></td>
      <td rowspan="2" valign="top"><font style="font-size: 7px;"><b title="<?php echo htmlentities($row['jenis_kain'],ENT_QUOTES);?>"><?php echo htmlentities(substr($row['jenis_kain'],0,10)."...",ENT_QUOTES);?></b></font></td>
      <td rowspan="2" valign="top"><font style="font-size: 7px;"><b title="<?php echo htmlentities($row['warna'],ENT_QUOTES);?>"><?php echo htmlentities(substr($row['warna'],0,10)."...",ENT_QUOTES);?></b></font></td>
      <td rowspan="2" align="center" valign="top"><font style="font-size: 8px;"><?php echo substr($row['nokk'],0,8)."<br>".substr($row['nokk'],8,10);?></font></td>
      <td rowspan="2" align="center" valign="top"><?php echo number_format($rowd['jml_roll'],0);?></td>
      <td rowspan="2" align="center" valign="top"><?php echo number_format($rowd['tberat']+$rdt['berat_sblm'],2);?></td>
      <td rowspan="2" align="center" valign="top"><?php echo $row['no_lot'];?></td>
      <td rowspan="2" align="center" valign="top"><font style="font-size: 8px;"><?php echo $row['tempat'];?></font></td>
      <td rowspan="2" align="left" valign="top"><font style="font-size: 7px;"><?php echo $row['jns_permintaan'];?><br><?php if($row['jns_permintaan']=="Potong Sample" OR $row['jns_permintaan']=="Potong Pass Qty"){echo $row['ket'].", no rol:".$rowrol['no_rol'].", brt ptg: ".$rowrol['berat_potong'];}else{echo $row['ket'].", jml roll: ".$rowb['jml_rol'].", jml brt: ".$rowb['berat'];}?></font></td>
    </tr>
    <tr >
      <td align="center" valign="top"><font style="font-size: 8px;"><?php echo $row['no_order'];?></font></td>
    </tr>
	<?php $n++;} }?>  
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
      if (is_array($permintaan->tampildatattd($_GET['bon'],$_GET['tgl'])) || is_object($permintaan->tampildatattd($_GET['bon'],$_GET['tgl']))){
		  foreach($permintaan->tampildatattd($_GET['bon'],$_GET['tgl']) as $rowdt){}}
		?>
    <tr>
      <td valign="top">Nama</td>
      <td align="center"><?php echo $rowdt['personil_buat'];?></td>
      <td align="center"><?php echo $rowdt['personil_periksa'];?></td>
      <td align="center"><?php echo $rowdt['personil_approve'];?></td>
      <td align="center"><?php echo $rowdt['personil_proses'];?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">Jabatan</td>
      <td align="center"><?php echo $rowdt['jabatan_buat'];?></td>
      <td align="center"><?php echo $rowdt['jabatan_periksa'];?></td>
      <td align="center"><?php echo $rowdt['jabatan_approve'];?></td>
      <td align="center"><?php echo $rowdt['jabatan_proses'];?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">Tanggal</td>
      <td align="center"><?php echo date("d M Y",strtotime($rowdt['tgl_buat']));?></td>
      <td align="center"><?php echo date("d M Y",strtotime($rowdt['tgl_periksa']));?></td>
      <td align="center"><?php echo date("d M Y",strtotime($rowdt['tgl_approve']));?></td>
      <td align="center"><?php echo date("d M Y",strtotime($rowdt['tgl_proses']));?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr style="height:0.7in;">
      <td valign="top">Tanda Tangan</td>
      <td align="center"><img src="../../../dist/img/<?php echo $rowdt['personil_buat'];?>.png" height="49" alt=""/></td>
      <td align="center"><img src="../../../dist/img/<?php echo $rowdt['personil_periksa'];?>.png" height="49" alt=""/></td>
      <td align="center"><img src="../../../dist/img/<?php echo $rowdt['personil_approve'];?>.png" height="49" alt=""/></td>
      <td align="center"><img src="../../../dist/img/<?php echo $rowdt['personil_proses'];?>.png" height="49" alt=""/></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
</body>
</html>
