<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=summary-bon-permintaan-".substr($_GET['awal'],0,10)."_".substr($_GET['akhir'],0,10).".xls");//ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
?>
<?php
$con=mysql_connect("10.0.0.10","dit","4dm1n"); 
//$con=mysql_connect("10.0.5.178","root","");
//$con=mysql_connect("localhost","root","");
$db=mysql_select_db("db_qc",$con)or die("Gagal Koneksi");
//--
$Awal=$_GET['awal'];
$Akhir=$_GET['akhir'];
$Status=$_GET['status'];
?>
<body>
<strong>Summary Bon Permintaan</strong><br>
<strong>Periode: <?php echo $Awal; ?> s/d <?php echo $Akhir; ?></strong><br>
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
    $no=1;
    if($Status!=""){ $sts=" AND `status`='$Status' ";}else{$sts=" ";}
    $query=mysql_query("SELECT
	id,	
	dept,
	refno,
	jns_permintaan,
	count( refno ) AS jmlkk,
	GROUP_CONCAT( DISTINCT nokk SEPARATOR ', ' ) AS nokk,
	GROUP_CONCAT( DISTINCT `status` SEPARATOR ', ') AS `status`,
	GROUP_CONCAT( DISTINCT `personil_buat` SEPARATOR ', ') AS `personil_buat`,
	GROUP_CONCAT( DISTINCT `personil_periksa` SEPARATOR ', ') AS `personil_periksa`,
	DATE_FORMAT(tgl_periksa,'%Y-%m-%d') as tgl_periksa,
	GROUP_CONCAT( DISTINCT `personil_approve` SEPARATOR ', ') AS `personil_approve`,
	DATE_FORMAT(tgl_approve,'%Y-%m-%d') as tgl_approve,
	GROUP_CONCAT( DISTINCT `personil_terima` SEPARATOR ', ') AS `personil_terima`,
	DATE_FORMAT(tgl_terima,'%Y-%m-%d') as tgl_terima,
	GROUP_CONCAT( DISTINCT `personil_proses` SEPARATOR ', ') AS `personil_proses`,
	DATE_FORMAT(tgl_proses,'%Y-%m-%d') as tgl_proses,
	GROUP_CONCAT( DISTINCT `personil_selesai` SEPARATOR ', ') AS `personil_selesai`,
	DATE_FORMAT(tgl_selesai,'%Y-%m-%d') as tgl_selesai,
	GROUP_CONCAT( DISTINCT `personil_cancel` SEPARATOR ', ') AS `personil_cancel`,
	DATE_FORMAT(tgl_cancel,'%Y-%m-%d') as tgl_cancel,
	tgl_update,
	DATE_FORMAT(tgl_buat,'%Y.%m.%d') as tgl_buat
    FROM
	tbl_bon_permintaan
    WHERE
	not ISNULL( refno ) and DATE_FORMAT(tgl_buat,'%Y-%m-%d') BETWEEN '$Awal' AND '$Akhir' $sts
    GROUP BY refno");
	while($r=mysql_fetch_array($query)){
        $sql=mysql_query("SELECT GROUP_CONCAT( DISTINCT jns_permintaan SEPARATOR ', ' ) AS ket FROM tbl_bon_permintaan WHERE refno='$r[refno]'");
        $row=mysql_fetch_array($sql);
	?>
    <tr>
      <td><?php echo $no;?></td>
      <td>'<?php echo $r['refno'];?></td>
      <td><?php echo $r['nokk'];?></td>
      <td><?php echo $r['dept'];?></td>
      <td><?php echo $r['status'];?></td>
      <td><?php echo $r['tgl_update'];?></td>
      <td><?php echo $row['ket'];?></td>
  </tr>
    <?php $no++;} ?>
</table>
</body>