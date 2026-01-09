<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
	<?php 

//$koneksi = mysqli_connect("localhost","root","","db_market");

$koneksi=mysqli_connect("10.0.1.91","dit","4dm1n","db_mkt");

// Check connection
if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}
 ?>
<table width="100%" border="1">
  <tbody>
    <tr>
	  <td>No</td>
	  <td>Item</td>
	  <td>Buyer</td>	
	  <?php // jalankan query
$result = mysqli_query($koneksi, "SELECT seasons FROM tbl_forecast GROUP BY seasons");
 
// tampilkan query
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC))
{ ?>		
      <td><?php echo $row['seasons']; ?></td>
	<?php } ?>	
	  <td>Total</td>
	  <td>Tgl</td>	
    </tr>
<?php 
$no=1;	  
// jalankan query
$result1 = mysqli_query($koneksi, "SELECT * FROM tbl_forecast GROUP BY tgl");
 
// tampilkan query
while ($row1=mysqli_fetch_array($result1,MYSQLI_ASSOC))
{ ?>	  
    <tr>
	  <td align="center"><?php echo $no;?></td>
	  <td align="center"><?php echo $row1['no_item'];?></td>
	  <td align="center"><?php echo $row1['buyer'];?></td>	
		<?php
     // tampilkan query
 $subtot=0;
$result = mysqli_query($koneksi, "SELECT seasons FROM tbl_forecast GROUP BY seasons");		
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC))
{ 
$result2 = mysqli_query($koneksi, "SELECT total FROM tbl_forecast WHERE seasons='$row[seasons]' and no_item='$row1[no_item]' and buyer='$row1[buyer]' and tgl='$row1[tgl]'");	
	$row2=mysqli_fetch_array($result2,MYSQLI_ASSOC);	
		?>		
      <td align="right"><?php echo $row2['total'];?></td>
	<?php $subtot+=$row2['total'];} ?>
	  <td align="right"><?php echo $subtot;?></td>
	  <td align="center"><?php echo $row1['tgl'];?></td>	
    </tr>
	 <?php 
 $no++;
$total=$total+$subtot;
} ?> 
	<tr>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>	
	  <?php // jalankan query
$result3 = mysqli_query($koneksi, "SELECT seasons FROM tbl_forecast GROUP BY seasons");
 
// tampilkan query
while ($row3=mysqli_fetch_array($result3,MYSQLI_ASSOC))
{ ?>		
      <td>&nbsp;</td>
	<?php } ?>	
	  <td align="right"><?php echo $total;?></td>
	  <td>&nbsp;</td>	
    </tr>  
  </tbody>
</table>

</body>
</html>