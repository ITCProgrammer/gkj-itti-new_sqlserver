<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=laporan-tg-".substr($_GET['awal'],0,10)."_".substr($_GET['akhir'],0,10).".xls");//ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
?>
<?php 
//$con=mysql_connect("10.0.10.178","root","");
$con=mysql_connect("10.0.0.10","dit","4dm1n");
//$con=mysql_connect("localhost","root","");
$db=mysql_select_db("db_qc",$con)or die("Gagal Koneksi");
//--
$Awal=$_GET['awal'];
$Akhir=$_GET['akhir'];
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
    $query=mysql_query("SELECT
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
	while($r=mysql_fetch_array($query)){
        $sql=mysql_query("SELECT
		COUNT(no_roll) AS jml_roll, SUM(weight) AS tberat
	    FROM
		detail_pergerakan_stok a
		INNER JOIN pergerakan_stok b ON a.id_stok=b.id
	    WHERE
		a.nokk = '$r[nokk]' 
		AND a.`status` = '1' 
		AND a.transtatus = '1' 
	    ORDER BY
		a.id ASC");
        $row=mysql_fetch_array($sql);

        $sql2=mysql_query("SELECT COUNT(no_rol) AS no_rol_sblm, SUM(berat) AS berat_sblm, SUM(berat_potong) AS berat_ptg_sblm 
        FROM tbl_bon_permintaan_detail WHERE nokk='$r[nokk]' AND no_permintaan='$r[no_permintaan]'");
        $row2=mysql_fetch_array($sql2);
	?>
    <tr>
      <td><?php echo date("d-M-y", strtotime($r['tgl_buat']));?></td>
      <td><?php echo date("d-M-y", strtotime($r['tgl_mutasi']));?></td>
      <td>'<?php echo $r['refno'];?></td>
      <td><?php echo $r['langganan'];?></td>
      <td><?php echo $r['no_po'];?></td>
      <td><?php echo $r['no_order'];?></td>
      <td><?php echo $r['jenis_kain'];?></td>
      <td><?php echo $r['nokk'];?></td>
      <td><?php echo $r['warna'];?></td>
      <td><?php if($r['jns_permintaan']=="Potong Sample" OR $r['jns_permintaan']=="Potong Pass Qty"){echo $row['jml_roll'];}elseif($r['jns_permintaan']=="Bongkaran" AND $row['jml_roll']==0){echo number_format($row2['no_rol_sblm'],0);}elseif($r['jns_permintaan']=="Bongkaran" AND $row['jml_roll']>=0){echo number_format($row['jml_roll']+$row2['no_rol_sblm'],0);}else if($r['jns_permintaan']=="Potong Sisa" AND $row['jml_roll']==0){echo number_format($row2['no_rol_sblm'],0);}else if($r['jns_permintaan']=="Potong Sisa" AND $row['jml_roll']>=0){echo number_format($row['jml_roll']-$row2['no_rol_sblm'],0);}?></td>
      <td><?php if($r['jns_permintaan']=="Potong Sample" OR $r['jns_permintaan']=="Potong Pass Qty"){echo number_format($row['tberat']+$row2['berat_ptg_sblm'],2);}elseif($r['jns_permintaan']=="Bongkaran" AND $row['jml_roll']=="0"){echo number_format($row2['berat_sblm'],2);}else if($r['jns_permintaan']=="Bongkaran" AND $row['jml_roll']>=0){echo number_format($row['tberat']+$row2['berat_sblm'],2);}else if($r['jns_permintaan']=="Potong Sisa" AND $row['jml_roll']==0){echo number_format($row2['berat_sblm'],2);}else if($r['jns_permintaan']=="Potong Sisa" AND $row['jml_roll']>=0){echo number_format($row['tberat'],2);}?></td>
      <td><?php if($r['berat_potong']!=NULL or $r['berat_potong']!=""){echo $r['berat_potong'];}else{echo "0";}?></td>
      <td><?php echo $r['no_lot'];?></td>
      <td><?php echo $r['jns_permintaan'].", ".$r['ket'];?></td>
      <td><?php echo $r['tempat'];?></td>
  </tr>
    <?php } ?>
</table>
</body>