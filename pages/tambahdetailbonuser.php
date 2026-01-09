<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";
$ip_num 	= $_SERVER['REMOTE_ADDR'];
$os			= $_SERVER['HTTP_USER_AGENT'];
$Nokk		= isset($_POST['nokk']) ? $_POST['nokk'] : '';
$Nokk1		= isset($_GET['nokk']) ? $_GET['nokk'] : '';
$Bon1		= isset($_POST['refno']) ? $_POST['refno'] : '';
$Bon		= isset($_GET['refno']) ? $_GET['refno'] : '';
$Dept	    = $_SESSION['deptGKJ'];

function no_urut(){
  date_default_timezone_set("Asia/Jakarta");
  include"koneksi.php";
  $format = date("y");
  $sql=mysqli_query($con,"SELECT no_permintaan FROM tbl_bon_permintaan WHERE substr(no_permintaan,1,2) like '".$format."%' ORDER BY no_permintaan DESC LIMIT 1") or die (mysqli_error());
  $d=mysqli_num_rows($sql);
  if($d>0){
    $r=mysqli_fetch_array($sql);
    $d=$r['no_permintaan'];
    $str=substr($d,2,5);
    $Urut = (int)$str;
  }else{
    $Urut = 0;
  }
  $Urut = $Urut + 1;
  $Nol="";
  $nilai=5-strlen($Urut);
  for ($i=1;$i<=$nilai;$i++){
    $Nol= $Nol."0";
  }
  $nipbr =$format.$Nol.$Urut;
  return $nipbr;
}
function no_bon($Dept){
  date_default_timezone_set("Asia/Jakarta");
  include"koneksi.php";
  if($Dept=="MKT"){ $kd="1";}
  else if($Dept=="QCF"){$kd="2";}
  else if($Dept=="PPC"){$kd="3";}
  else if($Dept=="DYE"){$kd="4";}
  else if($Dept=="FIN"){$kd="5";}
  else if($Dept=="BRS"){$kd="6";}
  else if($Dept=="PRT"){$kd="7";}
  else if($Dept=="TAS"){$kd="8";}
  else if($Dept=="GKJ"){$kd="9";}
  else if($Dept=="YND"){$kd="0";}
  $format = $kd.date("y");
  $sql=mysqli_query($con,"SELECT refno FROM	tbl_bon_permintaan WHERE substr(refno,1,3) like '".$format."%' ORDER BY refno DESC LIMIT 1") or die (mysqli_error());
  $d=mysqli_num_rows($sql);
  if($d>0){
    $r=mysqli_fetch_array($sql);
    $d=$r['refno'];
    $str=substr($d,3,5);
    $Urut = (int)$str;
  }else{
    $Urut = 0;
  }
  $Urut = $Urut + 1;
  $Nol="";
  $nilai=5-strlen($Urut);
  for ($i=1;$i<=$nilai;$i++){
    $Nol= $Nol."0";
  }
  $nipbr =$format.$Nol.$Urut;
  return $nipbr;
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tambah Detail Bon Permintaan</title>

<script language="javascript" type="text/javascript"> 
	var maxAmount = 15;
	function textCounter(textField, showCountField) {
		if (textField.value.length > maxAmount) {
			textField.value = textField.value.substring(0, maxAmount);
		} else { 
			showCountField.value = maxAmount - textField.value.length;
		}
	}
</script>
<style>
input.noborder {
  border-bottom: none;
  border-left:none;
  border-right:none;
  border-top:none;
 }
</style>
</head>
<body>	
<form role="form" name="form1" id="form1" action="" enctype="multipart/form-data" method="post">	
  <div class="row">
    <div class="col-lg-6">	
    <!-- general form elements -->
      <div class="card">
        <div class="card-header">
          <input type="submit" value="Save" name="save" id="save" class="btn btn-primary float-right"/>
				  <button type="button" class="btn btn-success float-left" onClick="window.location.href='TambahDetailBonUser-<?php echo $Bon; ?>'" name="new"><i class="fa fa-file"></i> New</button>
        </div>
        <!-- /.card-header -->
        <?php 
              $sql 	= "SELECT A.LOTCODE, B.CODE AS NO_ORDER,B.ORDERPARTNERBRANDCODE,B.LONGDESCRIPTION AS BUYER, B.LEGALNAME1 AS LANGGANAN, B.ITEMDESCRIPTION AS JENIS_KAIN,
              B.PO_HEADER AS PO_HEADER,B.PO_LINE AS PO_LINE, C.PRODUCTIONDEMANDCODE AS NO_LOT,TRIM(D.LONGDESCRIPTION) AS WARNA FROM BALANCE A 
          LEFT JOIN 
              (SELECT SALESORDER.CODE,SALESORDER.ORDERPARTNERBRANDCODE,ORDERPARTNERBRAND.LONGDESCRIPTION,BUSINESSPARTNER.LEGALNAME1,
              SALESORDER.EXTERNALREFERENCE AS PO_HEADER,SALESORDERLINE.EXTERNALREFERENCE AS PO_LINE, SALESORDERLINE.ITEMDESCRIPTION, 
              SALESORDERLINE.ITEMTYPEAFICODE, SALESORDERLINE.SUBCODE01, SALESORDERLINE.SUBCODE02, SALESORDERLINE.SUBCODE03,
              SALESORDERLINE.SUBCODE04, SALESORDERLINE.SUBCODE05, SALESORDERLINE.SUBCODE06, SALESORDERLINE.SUBCODE07,
              SALESORDERLINE.SUBCODE08, SALESORDERLINE.SUBCODE09, SALESORDERLINE.SUBCODE10
              FROM SALESORDER SALESORDER
              LEFT JOIN SALESORDERLINE SALESORDERLINE ON SALESORDER.CODE = SALESORDERLINE.SALESORDERCODE
              LEFT JOIN ORDERPARTNERBRAND ORDERPARTNERBRAND ON SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNERBRAND.ORDPRNCUSTOMERSUPPLIERCODE AND SALESORDER.ORDERPARTNERBRANDCODE = ORDERPARTNERBRAND.CODE
              LEFT JOIN ORDERPARTNER ORDERPARTNER ON SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNER.CUSTOMERSUPPLIERCODE
              LEFT JOIN BUSINESSPARTNER BUSINESSPARTNER ON ORDERPARTNER.ORDERBUSINESSPARTNERNUMBERID = BUSINESSPARTNER.NUMBERID
              GROUP BY SALESORDER.CODE,SALESORDER.ORDERPARTNERBRANDCODE,ORDERPARTNERBRAND.LONGDESCRIPTION,
              SALESORDER.EXTERNALREFERENCE,SALESORDERLINE.EXTERNALREFERENCE,SALESORDERLINE.ITEMDESCRIPTION,BUSINESSPARTNER.LEGALNAME1,
              SALESORDERLINE.ITEMTYPEAFICODE, SALESORDERLINE.SUBCODE01, SALESORDERLINE.SUBCODE02, SALESORDERLINE.SUBCODE03,
              SALESORDERLINE.SUBCODE04, SALESORDERLINE.SUBCODE05, SALESORDERLINE.SUBCODE06, SALESORDERLINE.SUBCODE07,
              SALESORDERLINE.SUBCODE08, SALESORDERLINE.SUBCODE09, SALESORDERLINE.SUBCODE10) B
          ON A.PROJECTCODE = B.CODE AND 
          A.ITEMTYPECODE = B.ITEMTYPEAFICODE AND 
          A.DECOSUBCODE01 = B.SUBCODE01 AND
          A.DECOSUBCODE02 = B.SUBCODE02 AND
          A.DECOSUBCODE03 = B.SUBCODE03 AND
          A.DECOSUBCODE04 = B.SUBCODE04 AND
          A.DECOSUBCODE05 = B.SUBCODE05 AND
          A.DECOSUBCODE06 = B.SUBCODE06 AND
          A.DECOSUBCODE07 = B.SUBCODE07 AND
          A.DECOSUBCODE08 = B.SUBCODE08 AND
          A.DECOSUBCODE09 = B.SUBCODE09 AND
          A.DECOSUBCODE10 = B.SUBCODE10
          LEFT JOIN 
          (SELECT PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE, PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE FROM 
              PRODUCTIONDEMANDSTEP PRODUCTIONDEMANDSTEP
              GROUP BY PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE,PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE) C
          ON A.LOTCODE=C.PRODUCTIONORDERCODE
          LEFT JOIN 
              (SELECT USERGENERICGROUP.CODE,USERGENERICGROUP.LONGDESCRIPTION FROM USERGENERICGROUP USERGENERICGROUP) D
          ON A.DECOSUBCODE05=D.CODE
          WHERE TRIM(A.LOTCODE)='$Nokk1'
          LIMIT 1";
          $stmt=db2_exec($conn1,$sql, array('cursor'=>DB2_SCROLLABLE));
          $rowdb2 = db2_fetch_assoc($stmt);
				?>
        <div class="card-body">
				  <div class="row">			
				    <div class="col-sm-12">
              <div class="form-group row">
                <label for="refno" class="col-md-3">No Bon</label>
                <div class="col-md-3">  
                  <input name="refno" type="text" class="form-control form-control-sm" id="refno" placeholder="" value="<?php if($Bon1!=""){echo $Bon1;}else{echo $Bon;}?>" readonly="readonly">
					      </div>	
              </div>	
              <div class="form-group row">
                <label for="nokk" class="col-md-3">Nokk</label>
                <div class="col-md-5"> 
					        <div class="input-group input-group">
                    <input name="user_buat" type="hidden" class="form-control form-control-sm" id="user_buat" placeholder="" value="<?php echo $_SESSION['userGKJ']; ?>">
                    <input name="jabatan_buat" type="hidden" class="form-control form-control-sm" id="jabatan_buat" placeholder="" value="<?php echo $_SESSION['jabatanGKJ']; ?>">	
                    <!-- <input name="nokk" type="text" class="form-control  form-control-sm" id="nokk" placeholder="" value="<?php if($Nokk!=""){ echo $Nokk;} ?>" maxlength="25" <?php if($Nokk!=""){ echo "ReadOnly";} ?> required> -->
                    <input name="nokk" onchange="window.location='index1.php?page=tambahdetailbonuser&amp;refno=<?php echo $_GET['refno'];?>&amp;nokk='+this.value" onblur="window.location='index1.php?page=tambahdetailbonuser&amp;refno=<?php echo $_GET['refno'];?>&amp;nokk='+this.value" type="text" class="form-control form-control-sm" id="nokk" placeholder="" value="<?php if($Nokk1!=""){ echo $Nokk1;} ?>" maxlength="25" <?php if($Nokk1!=""){ echo "ReadOnly";} ?> required>	
                    <!-- <span class="input-group-append">
               	      <button type="submit" class="btn btn-success btn-sm"  <?php if($Nokk!=""){ echo "Disabled";} ?>>  <i class="fa fa-search"></i></button>
               		  </span>	 -->
					        </div>	
					      </div>	
              </div>
				      <div class="form-group row">
                <label for="langganan" class="col-md-3">Langganan</label>
                <div class="col-md-6">  
                  <input name="langganan" type="text" class="form-control form-control-sm" id="langganan" placeholder="" value="<?php if(TRIM($rowdb2['LOTCODE'])!=''){echo $rowdb2['LANGGANAN']."/".$rowdb2['BUYER'];} ?>" readonly="readonly">
					      </div>
					      <div class="col-md-2">  
                  <input name="lot" type="text" class="form-control form-control-sm" id="lot" placeholder="No Lot" value="<?php if(TRIM($rowdb2['LOTCODE'])!=''){echo $rowdb2['NO_LOT'];} ?>" readonly="readonly">
					      </div> 
              </div>
				      <div class="form-group row">
                <label for="no_po" class="col-md-3">No PO</label>
                <div class="col-md-4">  
                  <input name="no_po" type="text" class="form-control form-control-sm" id="no_po" placeholder="" value="<?php if($rowdb2['PO_HEADER']!=''){echo $rowdb2['PO_HEADER'];}else{echo $rowdb2['PO_LINE'];} ?>" readonly="readonly">
					      </div>
					      <div class="col-md-4">  
                  <input name="no_order" type="text" class="form-control form-control-sm" id="no_order" placeholder="No Order" value="<?php if(TRIM($rowdb2['LOTCODE'])!=''){echo $rowdb2['NO_ORDER'];} ?>" readonly="readonly">
					      </div> 
              </div>
				      <div class="form-group row">
                <label for="jenis_kain" class="col-md-3">Jenis Kain</label>
                <div class="col-md-8">  
                  <input name="jenis_kain" type="text" class="form-control form-control-sm" id="jenis_kain" placeholder="" value="<?php if(TRIM($rowdb2['LOTCODE'])!=''){echo $rowdb2['JENIS_KAIN'];}?>" readonly="readonly">
					      </div>	
                  </div>	  
				      <div class="form-group row">
                <label for="dept" class="col-md-3">Dept</label>
                <div class="col-md-3">	
                  <input name="dept" type="hidden" class="form-control form-control-sm" id="dept" placeholder="" value="<?php echo $_SESSION['deptGKJ'];?>">				
                  <select class="form-control select2" name="dept1" id="dept1" <?php if($Nokk1!=""){ echo "required"; }?> <?php if($_SESSION['deptGKJ']!="DIT"){ echo "disabled";} ?>>
						        <option value=""></option>
                    <option value="BRS"<?php if($_SESSION['deptGKJ']=="BRS"){ echo "SELECTED";}?>>BRS</option>
                    <option value="DYE" <?php if($_SESSION['deptGKJ']=="DYE"){ echo "SELECTED";}?>>DYE</option>
                    <option value="FIN" <?php if($_SESSION['deptGKJ']=="FIN"){ echo "SELECTED";}?>>FIN</option>
                    <option value="GKG" <?php if($_SESSION['deptGKJ']=="GKG"){ echo "SELECTED";}?>>GKG</option>
                    <option value="GKJ" <?php if($_SESSION['deptGKJ']=="GKJ"){ echo "SELECTED";}?>>GKJ</option>
                    <option value="KNT" <?php if($_SESSION['deptGKJ']=="KNT"){ echo "SELECTED";}?>>KNT</option>
                    <option value="LAB" <?php if($_SESSION['deptGKJ']=="LAB"){ echo "SELECTED";}?>>LAB</option>
                    <option value="MKT" <?php if($_SESSION['deptGKJ']=="PDC"){ echo "SELECTED";}?>>MKT</option>
                    <option value="PDC" <?php if($_SESSION['deptGKJ']=="BRS"){ echo "SELECTED";}?>>PDC</option>
                    <option value="PPC" <?php if($_SESSION['deptGKJ']=="PPC"){ echo "SELECTED";}?>>PPC</option>
                    <option value="PRT" <?php if($_SESSION['deptGKJ']=="PRT"){ echo "SELECTED";}?>>PRT</option>
                    <option value="QCF" <?php if($_SESSION['deptGKJ']=="QCF"){ echo "SELECTED";}?>>QCF</option>
                    <option value="RMP" <?php if($_SESSION['deptGKJ']=="RMP"){ echo "SELECTED";}?>>RMP</option>
                    <option value="TAS" <?php if($_SESSION['deptGKJ']=="TAS"){ echo "SELECTED";}?>>TAS</option>
                  </select>	
					      </div>
					      <div class="col-md-5">  
                  <input name="warna" type="text" class="form-control form-control-sm" id="warna" placeholder="Warna" value="<?php if(TRIM($rowdb2['LOTCODE'])!=''){echo $rowdb2['WARNA'];} ?>" readonly="readonly">
					      </div> 
              </div>
				      <div class="form-group row">
                <label for="ket" class="col-md-3">Keterangan</label>
					      <div class="col-md-3">
					        <select name="kategori" class="form-control form-control-sm select2" id="kategori" <?php if($Nokk1!=""){echo "required";}?>>
                    <option value="">Pilih</option>
                    <option value="Potong Sample">Potong Sample</option>
                    <option value="Bongkaran">Bongkaran</option>
                    <option value="Potong Pass Qty">Potong Pass Qty</option>
                    <option value="Potong Sisa">Potong Sisa</option>
					        </select>
					      </div>
                <div class="col-md-5">
					        <textarea name="ket" class="form-control form-control-sm" <?php if($Nokk1!=""){echo "required";}?> placeholder="Note.." onKeyDown="textCounter(this.form.ket,this.form.countDisplay);" onKeyUp="textCounter(this.form.ket,this.form.countDisplay);"></textarea>
                  <input readonly class="noborder" type="text" name="countDisplay" size="2" maxlength="2" value="15"> Karakter Tersisa
					      </div>		
              </div>
					  </div>
					</div>	
        </div>
        <!-- /.card-body -->
      </div>			
      <!-- /.card -->
	  </div>
	  <div class="col-lg-6">
      <div class="card">
        <div class="card-body table-responsive">
          <table id="example5" width="100%" class="table table-sm table-bordered table-hover" style="font-size: 12px;">
            <thead class="btn-info">
              <tr>
                <th width="26%"><div align="center">SN</div></th>
                <th width="23%"><div align="center">Berat</div></th>
                <th width="21%"><div align="center">Panjang</div></th>
                <th width="16%"><div align="center">Tempat</div></th>
              </tr>
            </thead>  
            <tbody>
            <?php
              $sqlBL 	= "SELECT A.* FROM BALANCE A 
              WHERE A.LOTCODE='$Nokk1' AND A.LOGICALWAREHOUSECODE ='M031' AND NOT (A.WHSLOCATIONWAREHOUSEZONECODE='B1' OR A.WHSLOCATIONWAREHOUSEZONECODE='TMP')
              ORDER BY A.ELEMENTSCODE ASC";
              $stmt=db2_exec($conn1,$sqlBL, array('cursor'=>DB2_SCROLLABLE));
              while($rowBL = db2_fetch_assoc($stmt)){
					  ?>	
              <tr align="center">
              <td align="center"><?php echo $rowBL['ELEMENTSCODE'];?></td>
                <td align="center"><?php echo number_format($rowBL['BASEPRIMARYQUANTITYUNIT'],2);?></td>
                <td align="center"><?php echo number_format($rowBL['BASESECONDARYQUANTITYUNIT'],2);?></td>
                <td><?php echo $rowBL['WHSLOCATIONWAREHOUSEZONECODE']."-".$rowBL['WAREHOUSELOCATIONCODE'];?></td>
                <?php 
                $toyard=$toyard+$rowBL['BASESECONDARYQUANTITYUNIT'];
                $toqty=$toqty+$rowBL['BASEPRIMARYQUANTITYUNIT'];
                $troll +=1;
                $no++;} ?>	
            </tbody>
            <br /><b>Total Roll : <?php echo $troll; ?></b><br /> 
            <b>Total Panjang : <?php echo $toyard; ?></b><br />
            <b>Total Berat : <?php echo $toqty; ?></b>
          </table>
        </div>	
      </div>
    </div>
  </div>	
</form>
<form role="form" name="form2" id="form2" action="" enctype="multipart/form-data" method="post">
	<div class="row">
	  <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
			    <h3 class="card-title">Detail Data</h3>
          <?php 
           $sqlc=mysqli_query($con,"SELECT *
           FROM
             tbl_bon_permintaan 
           WHERE
             ISNULL( refno ) AND dept='$Dept'
           GROUP BY
             id");
           $cekdatabon=mysqli_num_rows($sqlc);
          ?>
          <input type="submit" value="Save" name="savebon" id="savebon" class="btn btn-primary float-right" <?php if($cekdatabon==0){echo "disabled";}?>/>	
          <input type="submit" value="Batal" name="batal" id="batal" class="btn btn-danger float-right" <?php if($cekdatabon==0){echo "disabled";}?>/>	
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive">
          <table id="example6" width="100%" class="table table-sm table-bordered table-hover" style="font-size: 12px;">
            <thead class="btn-danger">
              <tr>
                <th><div align="center" valign="middle">Tgl Buat Bon</div></th>
                <th><div align="center" valign="middle">Langganan</div></th>
                <th><div align="center" valign="middle">No PO</div></th>
                <th><div align="center" valign="middle">No Order</div></th>
                <th><div align="center" valign="middle">Jenis Kain</div></th>
                <th><div align="center" valign="middle">Warna</div></th>
                <th><div align="center" valign="middle">No KK</div></th>
                <th><div align="center" valign="middle">No Lot</div></th>
                <th><div align="center" valign="middle">Keterangan</div></th>
                <td><div align="center" valign="middle"><input type="checkbox" name="allbox2" value="check" onclick="checkAll2(0);" /></td>
              </tr>
            </thead>  
            <tbody>
            <?php
            $n=1;
            while($rowdt=mysqli_fetch_array($sqlc)){
					  ?>	
              <tr align="center">
                <td align="center"><?php echo date("Y-m-d",strtotime($rowdt['tgl_buat']));?></td>
                <td align="center"><?php echo $rowdt['langganan'];?></td>
                <td align="center"><?php echo $rowdt['no_po'];?></td>
                <td><?php echo $rowdt['no_order'];?></td>
                <td><?php echo $rowdt['jenis_kain'];?></td>
                <td><?php echo $rowdt['warna'];?></td>
                <td><?php echo $rowdt['nokk'];?></td>
                <td><?php echo $rowdt['no_lot'];?></td>
                <td align="left"><?php if($rowdt['jns_permintaan']=="Bongkaran"){echo "<span class='badge badge-warning'>".$rowdt['jns_permintaan']."</span>";}else if($rowdt['jns_permintaan']=="Potong Sample"){echo "<span class='badge badge-success'>".$rowdt['jns_permintaan']."</span>";}else if($rowdt['jns_permintaan']=="Potong Pass Qty"){echo "<span class='badge badge-success'>".$rowdt['jns_permintaan']."</span>";}else if($rowdt['jns_permintaan']=="Potong Sisa"){echo "<span class='badge badge-info'>".$rowdt['jns_permintaan']."</span>";}?><br><?php echo $rowdt['ket'];?></td>
                <td><input type="checkbox" name="cek0[<?php echo $n; ?>]" value="<?php echo $rowdt['id']; ?>"/></td>
              </tr>
				      <?php $n++;}?>	
            </tbody>
          </table>
        </div>				  
      </div>
    </div>	
	</div>
</form>
<?php 
  if(isset($_POST['save'])){
    $nokk=trim($_POST['nokk']);
    $langganan=str_replace("'","''",$_POST['langganan']);
		$nopo=str_replace("'","''",$_POST['no_po']);
		$noorder=str_replace("'","''",$_POST['no_order']);
		$jeniskain=str_replace("'","''",$_POST['jenis_kain']);
		$warna=str_replace("'","''",$_POST['warna']);
		$lot=$_POST['lot'];
		$dept=$_POST['dept'];
		$jnsP=$_POST['kategori'];
		$user_buat=$_POST['user_buat'];
		$jabatan_buat=$_POST['jabatan_buat'];
    $ket=str_replace("'","''",$_POST['ket']);
    $nou=no_urut();
    $sqlData=mysqli_query($con,"INSERT INTO tbl_bon_permintaan SET
    no_permintaan='$nou',
    nokk='$nokk',
    langganan='$langganan',
    no_po='$nopo',
    no_order='$noorder',
    jenis_kain='$jeniskain',
    warna='$warna',
    no_lot='$lot',
    dept='$dept',
    ket='$ket',
    jns_permintaan='$jnsP',
    personil_buat='$user_buat',
    jabatan_buat='$jabatan_buat',
    tgl_buat=now(),
    tgl_update=now()");
    if($sqlData){
			// echo "<script>alert('Data Tersimpan');</script>";
			// echo "<script>window.location.href='?p=Input-Data-KJ;</script>";
// 			echo "<script>swal({
//   title: 'Data Tersimpan',   
//   text: 'Klik Ok untuk input data kembali',
//   type: 'success',
//   }).then((result) => {
//   if (result.value) {
    
// 	 window.location.href='PotongBongkar'; 
//   }
// });</script>";
      echo "<script>window.location='TambahDetailBonUser-$Bon';</script>"; 
		}
  }
  if(isset($_POST['savebon'])){
		$no=1;
		$nobon=$_GET['refno'];
    $ip= $_SERVER['REMOTE_ADDR'];
    $Dept= $_SESSION['deptGKJ'];
    $usertambah=$_SESSION['userGKJ'];
    $query = "SELECT *
    FROM
      tbl_bon_permintaan 
    WHERE
      ISNULL( refno ) AND dept='$Dept'
    GROUP BY
      id";
    $results = mysqli_query($con,$query);
    foreach ($results as $result){	
		$idcek	= $_POST['cek0'][$no];
		if($idcek!=""){		
    $sql 	= mysqli_query($con,"UPDATE tbl_bon_permintaan SET 
    refno='$nobon', 
		`status`='Approve',
		status_tambah='1',
		personil_tambah='$usertambah',
		tgl_tambah=now() 
    WHERE id='$idcek'");
			 }
		$no++;
		}
    
    $sqlInsert=mysqli_query($con,"INSERT INTO tbl_log_bon_gkj SET
    proses='Bon Permintaan Baru',
    detail_proses='User Membuat Bon: $nobon ',
    user='$usertambah',
    waktu_proses=now(),
    ip='$ip'");

  if($sqlInsert){
    echo "<script>window.location='StatusPermintaan';</script>";
  }
}
  if(isset($_POST['batal'])){
		$no=1;
    $ip= $_SERVER['REMOTE_ADDR'];
    $Dept= $_SESSION['deptGKJ'];
		$userbatal=$_SESSION['userGKJ'];
		$query = "SELECT *
    FROM
      tbl_bon_permintaan 
    WHERE
      ISNULL( refno ) AND dept='$Dept'
    GROUP BY
      id";
    $results = mysqli_query($con,$query);
    foreach ($results as $result){		
			$idcek	= $_POST['cek0'][$no];
			if($idcek!=""){		
        $sqldel=mysqli_query($con,"DELETE FROM tbl_bon_permintaan WHERE ISNULL(refno) AND id='$idcek'");
        $sqlInsert=mysqli_query($con,"INSERT INTO tbl_log_bon_gkj SET
        proses='Batal Bon Permintaan',
        detail_proses='User Membatalkan No KK : $result[nokk] Sebelum di Save',
        user='$userbatal',
        waktu_proses=now(),
        ip='$ip'");
			}
			$no++;
		}
    if($sqlInsert){
      echo "<script>window.location='TambahDetailBonUser-$Bon';</script>";
    }
	}
?>
</body>
</html>
<script type="text/javascript">
function checkAll(form1){
    for (var i=0;i<document.forms['form1'].elements.length;i++)
    {
        var e=document.forms['form1'].elements[i];
        if ((e.name !='allbox') && (e.type=='checkbox'))
        {
            e.checked=document.forms['form1'].allbox.checked;
			
        }
    }
}
function checkAll2(form2){
    for (var i=0;i<document.forms['form2'].elements.length;i++)
    {
        var e=document.forms['form2'].elements[i];
        if ((e.name !='allbox2') && (e.type=='checkbox'))
        {
            e.checked=document.forms['form2'].allbox.checked;
			
        }
    }
}	
</script>
