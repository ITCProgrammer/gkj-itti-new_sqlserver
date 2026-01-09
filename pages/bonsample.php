<script type="text/javascript">
    function proses_zone(){
        var zone = document.getElementById("zone").value;

        if (zone == "") {
            window.location.href='BonSample';
        }else{
            window.location.href='BonSample&'+zone;
        }
    }

    function proses_location() {
        var zone    = document.getElementById("zone").value;
        var location = document.getElementById("location").value;

        if (zone == "") {
            swal({
                title: 'Zone tidak boleh kosong',   
                text: 'Klik Ok untuk input data kembali',
                type: 'error'
                });
        }else if (location == ""){
            swal({
                title: 'Location tidak boleh kosong',   
                text: 'Klik Ok untuk input data kembali',
                type: 'error'
                });
        } else {
            window.location.href='BonSample&'+zone+'&'+location;
        }
    }
</script>
<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";
$ip_num = $_SERVER['REMOTE_ADDR'];
$os= $_SERVER['HTTP_USER_AGENT'];

$location		= isset($_GET['location']) ? $_GET['location'] : '';
$zone		    = isset($_GET['zone']) ? $_GET['zone'] : '';
$Dept	    = $_SESSION['deptGKJ'];
$User	    = $_SESSION['userGKJ'];

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
<title>Permintaan Bon Sample</title>

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
  <div class="alert alert-warning alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h4><i class="icon fa fa-info"></i> Informasi</h4>

				<p>Maksimal Dalam Satu Bon Terdapat <strong>6 Element</strong>. Hanya bisa <strong>No Element ERP</strong> Saja. <br> No Element Lama Tidak Bisa Dipakai Pada Program Ini.</p>
	</div>
  <div class="row">
    <div class="col-lg-6">	
    <!-- general form elements -->
      <div class="card">
        <div class="card-header">
				  <!-- <button type="submit" class="btn btn-primary float-right" name="save" value="Save"><i class="fa fa-save"></i> Save</button> -->
          <input type="submit" value="Save" name="save" id="save" <?php if($location==''){echo "disabled";}?> class="btn btn-primary float-right"/>
				  <button type="button" class="btn btn-success float-left" onClick="window.location.href='BonSample'" name="new"><i class="fa fa-file"></i> New</button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
				  <div class="row">			
				    <div class="col-sm-12">		
              <div class="form-group row">
                <label for="zone" class="col-md-3">Zone</label>
                <div class="col-md-5"> 
                  <input name="user_buat" type="hidden" class="form-control form-control-sm" id="user_buat" placeholder="" value="<?php echo $_SESSION['userGKJ']; ?>">
                  <input name="jabatan_buat" type="hidden" class="form-control form-control-sm" id="jabatan_buat" placeholder="" value="<?php echo $_SESSION['jabatanGKJ']; ?>">		
                    <select class="form-control select2" name="zone" id="zone" required onchange="proses_zone()">
                      <option value="">Pilih</option>
                      <?php 
                      $stmt1=db2_exec($conn1,"SELECT DISTINCT(TRIM(WAREHOUSELOCATION.WAREHOUSEZONECODE)) AS WAREHOUSEZONECODE
                      FROM WAREHOUSELOCATION WAREHOUSELOCATION
                      WHERE WAREHOUSELOCATION.WHSZONEPHYSICALWAREHOUSECODE ='M03'", array('cursor'=>DB2_SCROLLABLE));
                      while($rz=db2_fetch_assoc($stmt1)){
                      ?>
                      <option value="<?php echo $rz['WAREHOUSEZONECODE'];?>" <?php if($_GET['zone']==$rz['WAREHOUSEZONECODE']){echo "SELECTED";}?>><?php echo $rz['WAREHOUSEZONECODE'];?></option>	
                      <?php }?>
                    </select>	
					      </div>	
              </div>
				      <div class="form-group row">
                <label for="location" class="col-md-3">Location</label>
                <div class="col-md-5">  
                  <select class="form-control select2" name="location" id="location" required onchange="proses_location()">
                      <option value="">Pilih</option>
                      <?php 
                      $stmt2=db2_exec($conn1,"SELECT DISTINCT(TRIM(WAREHOUSELOCATION.CODE)) AS CODE
                      FROM WAREHOUSELOCATION WAREHOUSELOCATION
                      WHERE WAREHOUSELOCATION.WHSZONEPHYSICALWAREHOUSECODE ='M03' AND WAREHOUSELOCATION.WAREHOUSEZONECODE= '$_GET[zone]'", array('cursor'=>DB2_SCROLLABLE));
                      while($rl=db2_fetch_assoc($stmt2)){
                      ?>
                      <option value="<?php echo $rl['CODE'];?>" <?php if($_GET['location']==$rl['CODE']){echo "SELECTED";}?>><?php echo $rl['CODE'];?></option>	
                      <?php }?>
                    </select>	
					      </div> 
              </div>
              <div class="form-group row">
                <label for="dept" class="col-md-3">Dept</label>
                <div class="col-md-3">
                  <input name="dept" type="hidden" class="form-control form-control-sm" id="dept" placeholder="" value="<?php echo $_SESSION['deptGKJ'];?>">					
                    <select class="form-control select2" name="dept1" id="dept1" <?php if($Nokk!=""){ echo "required"; }?> <?php if($_SESSION['deptGKJ']!="DIT"){ echo "disabled";} ?>>
                      <option value=""></option>
                      <option value="BRS"<?php if($_SESSION['deptGKJ']=="BRS"){ echo "SELECTED";}?>>BRS</option>
                      <option value="DYE" <?php if($_SESSION['deptGKJ']=="DYE"){ echo "SELECTED";}?>>DYE</option>
                      <option value="FIN" <?php if($_SESSION['deptGKJ']=="FIN"){ echo "SELECTED";}?>>FIN</option>
                      <option value="GKG" <?php if($_SESSION['deptGKJ']=="GKG"){ echo "SELECTED";}?>>GKG</option>
                      <option value="GKJ" <?php if($_SESSION['deptGKJ']=="GKJ"){ echo "SELECTED";}?>>GKJ</option>
                      <option value="KNT" <?php if($_SESSION['deptGKJ']=="KNT"){ echo "SELECTED";}?>>KNT</option>
                      <option value="LAB" <?php if($_SESSION['deptGKJ']=="LAB"){ echo "SELECTED";}?>>LAB</option>
                      <option value="MKT" <?php if($_SESSION['deptGKJ']=="MKT"){ echo "SELECTED";}?>>MKT</option>
                      <option value="PDC" <?php if($_SESSION['deptGKJ']=="PDC"){ echo "SELECTED";}?>>PDC</option>
                      <option value="PPC" <?php if($_SESSION['deptGKJ']=="PPC"){ echo "SELECTED";}?>>PPC</option>
                      <option value="PRT" <?php if($_SESSION['deptGKJ']=="PRT"){ echo "SELECTED";}?>>PRT</option>
                      <option value="QCF" <?php if($_SESSION['deptGKJ']=="QCF"){ echo "SELECTED";}?>>QCF</option>
                      <option value="RMP" <?php if($_SESSION['deptGKJ']=="RMP"){ echo "SELECTED";}?>>RMP</option>
                      <option value="TAS" <?php if($_SESSION['deptGKJ']=="TAS"){ echo "SELECTED";}?>>TAS</option>
                      <option value="YND" <?php if($_SESSION['deptGKJ']=="YND"){ echo "SELECTED";}?>>YND</option>
                    </select>	
					      </div>
              </div>
              <div class="form-group row">
                <label for="ket" class="col-md-3">Keterangan</label>
                  <div class="col-md-5">
                    <textarea name="ket" class="form-control form-control-sm" <?php if($location!=""){echo "required";}?> placeholder="Note.." onKeyDown="textCounter(this.form.ket,this.form.countDisplay);" onKeyUp="textCounter(this.form.ket,this.form.countDisplay);"></textarea>
                    <input readonly class="noborder" type="text" name="countDisplay" size="2" maxlength="2" value="15"> Karakter Tersisa
                  </div>							
              </div>
					  </div>
					</div>	
        </div>
      </div>			
  	</div>
  </div>	
  <div class="row">
    <div class="col-lg-12">
      <div class="card">      		  
        <div class="card-body table-responsive">
          <table id="example9" width="100%" class="table table-sm table-bordered table-hover" style="font-size: 12px;">
            <thead class="btn-info">
              <tr>
                <th width="5%"><div align="center">No</div></th>
                <th width="10%"><div align="center">SN</div></th>
                <th width="10%"><div align="center">No Demand</div></th>
                <th width="10%"><div align="center">No Prod Order</div></th>
                <th width="10%"><div align="center">No Order</div></th>
                <th width="10%"><div align="center">No PO</div></th>
                <th width="10%"><div align="center">No Artikel</div></th>
                <th width="10%"><div align="center">Berat</div></th>
                <th width="10%"><div align="center">Panjang</div></th>
                <th width="20%"><div align="center">Langganan</div></th>
                <th width="16%"><div align="center">Warna</div></th>
                <th width="20%"><div align="center">Jenis Kain</div></th>
                <th width="7%"><div align="center">Tgl Mutasi</div></th>
                <td width="7%"><div align="center"><input type="checkbox" name="allbox" value="check" onclick="checkAll(0);" /></div></td>
              </tr>
            </thead>  
            <tbody>
            <?php
            if($location!=''){
              $sqlBL 	= "SELECT
              A.PROJECTCODE,
              A.LOTCODE,
              SUBSTR(TRIM(A.ELEMENTSCODE),1,8) AS DEMANDNO,
              D.ORIGDLVSALORDLINESALORDERCODE, 
              D.ORIGDLVSALORDERLINEORDERLINE,
              A.WAREHOUSELOCATIONCODE,
              A.WHSLOCATIONWAREHOUSEZONECODE,
              A.ELEMENTSCODE,
              A.BASEPRIMARYQUANTITYUNIT,
              A.BASEPRIMARYUNITCODE,
              A.BASESECONDARYQUANTITYUNIT,
              A.BASESECONDARYUNITCODE,
              B.BUYER,
              B.LANGGANAN,
              B.PO_NUMBER,
              B.ITEMDESCRIPTION,
              TRIM(A.DECOSUBCODE02) AS DECOSUBCODE02,
              TRIM(A.DECOSUBCODE03) AS DECOSUBCODE03,
              TRIM(A.DECOSUBCODE05) AS DECOSUBCODE05,
              C.WARNA
              FROM BALANCE A 
              LEFT JOIN PRODUCTIONDEMAND D 
              ON SUBSTR(TRIM(A.ELEMENTSCODE),1,8) = D.CODE
              LEFT JOIN 
                      (SELECT SALESORDER.CODE,SALESORDER.ORDERPARTNERBRANDCODE,ORDERPARTNERBRAND.LONGDESCRIPTION AS BUYER,BUSINESSPARTNER.LEGALNAME1 AS LANGGANAN,
                      CASE
                      WHEN SALESORDER.EXTERNALREFERENCE IS NULL THEN SALESORDERLINE.EXTERNALREFERENCE
                      ELSE SALESORDER.EXTERNALREFERENCE
                      END AS PO_NUMBER, SALESORDERLINE.ITEMDESCRIPTION, SALESORDERLINE.ORDERLINE,
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
                      SALESORDERLINE.ORDERLINE,SALESORDERLINE.ITEMTYPEAFICODE, SALESORDERLINE.SUBCODE01, SALESORDERLINE.SUBCODE02, SALESORDERLINE.SUBCODE03,
                      SALESORDERLINE.SUBCODE04, SALESORDERLINE.SUBCODE05, SALESORDERLINE.SUBCODE06, SALESORDERLINE.SUBCODE07,
                      SALESORDERLINE.SUBCODE08, SALESORDERLINE.SUBCODE09, SALESORDERLINE.SUBCODE10) B
                  ON D.ORIGDLVSALORDLINESALORDERCODE = B.CODE AND 
                D.ORIGDLVSALORDERLINEORDERLINE = B.ORDERLINE 
              LEFT JOIN (
                  SELECT ITXVIEWCOLOR.ITEMTYPECODE, ITXVIEWCOLOR.SUBCODE01, ITXVIEWCOLOR.SUBCODE02,
                  ITXVIEWCOLOR.SUBCODE03, ITXVIEWCOLOR.SUBCODE04, ITXVIEWCOLOR.SUBCODE05, ITXVIEWCOLOR.SUBCODE06, 
                  ITXVIEWCOLOR.SUBCODE07, ITXVIEWCOLOR.SUBCODE08, ITXVIEWCOLOR.SUBCODE09, ITXVIEWCOLOR.SUBCODE10, 
                  ITXVIEWCOLOR.WARNA FROM ITXVIEWCOLOR ITXVIEWCOLOR) C ON
                  A.ITEMTYPECODE = C.ITEMTYPECODE AND 
                  A.DECOSUBCODE01 = C.SUBCODE01 AND
                  A.DECOSUBCODE02 = C.SUBCODE02 AND
                  A.DECOSUBCODE03 = C.SUBCODE03 AND
                  A.DECOSUBCODE04 = C.SUBCODE04 AND
                  A.DECOSUBCODE05 = C.SUBCODE05 AND
                  A.DECOSUBCODE06 = C.SUBCODE06 AND
                  A.DECOSUBCODE07 = C.SUBCODE07 AND
                  A.DECOSUBCODE08 = C.SUBCODE08 AND
                  A.DECOSUBCODE09 = C.SUBCODE09 AND
                  A.DECOSUBCODE10 = C.SUBCODE10
              WHERE A.WAREHOUSELOCATIONCODE = '$location' AND A.LOGICALWAREHOUSECODE ='M031' AND A.WHSLOCATIONWAREHOUSEZONECODE ='$zone'
              ORDER BY A.ELEMENTSCODE ASC";
            $stmt3=db2_exec($conn1,$sqlBL, array('cursor'=>DB2_SCROLLABLE));
            $no=1;
            while($rowBL = db2_fetch_assoc($stmt3)){
            $sqltgl 	= "SELECT VIEWELEMENTSANALYSIS.TEMPLATECODE, VIEWELEMENTSANALYSIS.ELEMENTCODE, LEFT(VIEWELEMENTSANALYSIS.CREATIONDATETIME,10) AS tgl_mutasi FROM VIEWELEMENTSANALYSIS VIEWELEMENTSANALYSIS
              WHERE (VIEWELEMENTSANALYSIS.TEMPLATECODE ='304' OR VIEWELEMENTSANALYSIS.TEMPLATECODE ='342') AND VIEWELEMENTSANALYSIS.ELEMENTCODE = '$rowBL[ELEMENTSCODE]' ORDER BY LEFT(VIEWELEMENTSANALYSIS.CREATIONDATETIME,10) DESC LIMIT 1";
              $stmt4=db2_exec($conn1,$sqltgl, array('cursor'=>DB2_SCROLLABLE));
              $result1 = db2_fetch_assoc($stmt4);
					  ?>	
              <tr align="center">
                <td align="center"><?php echo $no;?></td>
                <td align="center"><?php echo $rowBL['ELEMENTSCODE'];?></td>
                <td align="center"><?php echo $rowBL['DEMANDNO'];?></td>
                <td align="center"><?php echo $rowBL['LOTCODE'];?></td>
                <td align="center"><?php echo $rowBL['PROJECTCODE'];?></td>
                <td align="center"><?php echo $rowBL['PO_NUMBER'];?></td>
                <td><?php if($rowBL['DECOSUBCODE03']!=''){echo $rowBL['DECOSUBCODE02'].$rowBL['DECOSUBCODE03'];}?></td>
                <td align="center"><?php echo number_format($rowBL['BASEPRIMARYQUANTITYUNIT'],2);?></td>
                <td align="center"><?php echo number_format($rowBL['BASESECONDARYQUANTITYUNIT'],2);?></td>
                <td><?php if($rowBL['LANGGANAN']!=''){echo $rowBL['LANGGANAN']."/".$rowBL['BUYER'];}?></td>
                <td><?php if($rowBL['WARNA']!=''){echo $rowBL['WARNA'];}?></td>
                <td><?php if($rowBL['ITEMDESCRIPTION']!=''){echo $rowBL['ITEMDESCRIPTION'];}?></td>
                <td align="center"><?php echo $result1['TGL_MUTASI'];?></td>
                <td><input type="checkbox" name="cek[<?php echo $no; ?>]" value="<?php echo $rowBL['ELEMENTSCODE']; ?>"/></td>
              </tr>
            <?php 
            $toyard=$toyard+$rowBL['BASESECONDARYQUANTITYUNIT'];
            $toqty=$toqty+$rowBL['BASEPRIMARYQUANTITYUNIT'];
            $troll +=1;
            $no++;} }?>	
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
<!-- <form role="form" name="form2" id="form2" action="" enctype="multipart/form-data" method="post">
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
</form> -->
<?php 
  if(isset($_POST['save'])){
    $sqlRow 	= "SELECT
              A.PROJECTCODE,
              A.LOTCODE,
              SUBSTR(TRIM(A.ELEMENTSCODE),1,8) AS DEMANDNO,
              D.ORIGDLVSALORDLINESALORDERCODE, 
              D.ORIGDLVSALORDERLINEORDERLINE,
              A.WAREHOUSELOCATIONCODE,
              A.WHSLOCATIONWAREHOUSEZONECODE,
              A.ELEMENTSCODE,
              A.BASEPRIMARYQUANTITYUNIT,
              A.BASEPRIMARYUNITCODE,
              A.BASESECONDARYQUANTITYUNIT,
              A.BASESECONDARYUNITCODE,
              B.BUYER,
              B.LANGGANAN,
              B.PO_NUMBER,
              B.ITEMDESCRIPTION,
              TRIM(A.DECOSUBCODE02) AS DECOSUBCODE02,
              TRIM(A.DECOSUBCODE03) AS DECOSUBCODE03,
              TRIM(A.DECOSUBCODE05) AS DECOSUBCODE05,
              C.WARNA
              FROM BALANCE A 
              LEFT JOIN PRODUCTIONDEMAND D 
              ON SUBSTR(TRIM(A.ELEMENTSCODE),1,8) = D.CODE
              LEFT JOIN 
                      (SELECT SALESORDER.CODE,SALESORDER.ORDERPARTNERBRANDCODE,ORDERPARTNERBRAND.LONGDESCRIPTION AS BUYER,BUSINESSPARTNER.LEGALNAME1 AS LANGGANAN,
                      CASE
                      WHEN SALESORDER.EXTERNALREFERENCE IS NULL THEN SALESORDERLINE.EXTERNALREFERENCE
                      ELSE SALESORDER.EXTERNALREFERENCE
                      END AS PO_NUMBER, SALESORDERLINE.ITEMDESCRIPTION, SALESORDERLINE.ORDERLINE,
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
                      SALESORDERLINE.ORDERLINE,SALESORDERLINE.ITEMTYPEAFICODE, SALESORDERLINE.SUBCODE01, SALESORDERLINE.SUBCODE02, SALESORDERLINE.SUBCODE03,
                      SALESORDERLINE.SUBCODE04, SALESORDERLINE.SUBCODE05, SALESORDERLINE.SUBCODE06, SALESORDERLINE.SUBCODE07,
                      SALESORDERLINE.SUBCODE08, SALESORDERLINE.SUBCODE09, SALESORDERLINE.SUBCODE10) B
                  ON D.ORIGDLVSALORDLINESALORDERCODE = B.CODE AND 
                D.ORIGDLVSALORDERLINEORDERLINE = B.ORDERLINE 
              LEFT JOIN (
                  SELECT ITXVIEWCOLOR.ITEMTYPECODE, ITXVIEWCOLOR.SUBCODE01, ITXVIEWCOLOR.SUBCODE02,
                  ITXVIEWCOLOR.SUBCODE03, ITXVIEWCOLOR.SUBCODE04, ITXVIEWCOLOR.SUBCODE05, ITXVIEWCOLOR.SUBCODE06, 
                  ITXVIEWCOLOR.SUBCODE07, ITXVIEWCOLOR.SUBCODE08, ITXVIEWCOLOR.SUBCODE09, ITXVIEWCOLOR.SUBCODE10, 
                  ITXVIEWCOLOR.WARNA FROM ITXVIEWCOLOR ITXVIEWCOLOR) C ON
                  A.ITEMTYPECODE = C.ITEMTYPECODE AND 
                  A.DECOSUBCODE01 = C.SUBCODE01 AND
                  A.DECOSUBCODE02 = C.SUBCODE02 AND
                  A.DECOSUBCODE03 = C.SUBCODE03 AND
                  A.DECOSUBCODE04 = C.SUBCODE04 AND
                  A.DECOSUBCODE05 = C.SUBCODE05 AND
                  A.DECOSUBCODE06 = C.SUBCODE06 AND
                  A.DECOSUBCODE07 = C.SUBCODE07 AND
                  A.DECOSUBCODE08 = C.SUBCODE08 AND
                  A.DECOSUBCODE09 = C.SUBCODE09 AND
                  A.DECOSUBCODE10 = C.SUBCODE10
              WHERE A.WAREHOUSELOCATIONCODE = '$location' AND A.LOGICALWAREHOUSECODE ='M031' AND A.WHSLOCATIONWAREHOUSEZONECODE ='$zone'
              ORDER BY A.ELEMENTSCODE ASC";
            $stmt5=db2_exec($conn1,$sqlBL, array('cursor'=>DB2_SCROLLABLE));
            $user_buat=$_POST['user_buat'];
            $jabatan_buat=$_POST['jabatan_buat'];
            $ket=str_replace("'","''",$_POST['ket']);
            $nou=no_urut();
            $nobon=no_bon($Dept);
            $rdt=db2_fetch_assoc($stmt5);
            $sqlData=mysqli_query($con,"INSERT INTO tbl_bon_permintaan SET
            no_permintaan='$nou',
            refno='$nobon',
            nokk='$rdt[LOTCODE]',
            langganan='$rdt[LANGGANAN]/$rdt[BUYER]',
            no_po='$rdt[PO_NUMBER]',
            no_order='$rdt[PROJECT]',
            jenis_kain='$rdt[ITEMDESCRIPTION]',
            warna='$rdt[WARNA]',
            no_lot='$rdt[DEMANDNO]',
            dept='$Dept',
            ket='$ket',
            `status`='Sedang Proses',
            jns_permintaan='Bon Sample',
            personil_buat='$user_buat',
            jabatan_buat='$jabatan_buat',
            tgl_buat=now(),
            tgl_update=now()");

          $sqlRD 	= "SELECT
              A.PROJECTCODE,
              A.LOTCODE,
              SUBSTR(TRIM(A.ELEMENTSCODE),1,8) AS DEMANDNO,
              D.ORIGDLVSALORDLINESALORDERCODE, 
              D.ORIGDLVSALORDERLINEORDERLINE,
              A.WAREHOUSELOCATIONCODE,
              A.WHSLOCATIONWAREHOUSEZONECODE,
              A.ELEMENTSCODE,
              A.BASEPRIMARYQUANTITYUNIT,
              A.BASEPRIMARYUNITCODE,
              A.BASESECONDARYQUANTITYUNIT,
              A.BASESECONDARYUNITCODE,
              B.BUYER,
              B.LANGGANAN,
              B.PO_NUMBER,
              B.ITEMDESCRIPTION,
              TRIM(A.DECOSUBCODE02) AS DECOSUBCODE02,
              TRIM(A.DECOSUBCODE03) AS DECOSUBCODE03,
              TRIM(A.DECOSUBCODE05) AS DECOSUBCODE05,
              C.WARNA
              FROM BALANCE A 
              LEFT JOIN PRODUCTIONDEMAND D 
              ON SUBSTR(TRIM(A.ELEMENTSCODE),1,8) = D.CODE
              LEFT JOIN 
                      (SELECT SALESORDER.CODE,SALESORDER.ORDERPARTNERBRANDCODE,ORDERPARTNERBRAND.LONGDESCRIPTION AS BUYER,BUSINESSPARTNER.LEGALNAME1 AS LANGGANAN,
                      CASE
                      WHEN SALESORDER.EXTERNALREFERENCE IS NULL THEN SALESORDERLINE.EXTERNALREFERENCE
                      ELSE SALESORDER.EXTERNALREFERENCE
                      END AS PO_NUMBER, SALESORDERLINE.ITEMDESCRIPTION, SALESORDERLINE.ORDERLINE,
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
                      SALESORDERLINE.ORDERLINE,SALESORDERLINE.ITEMTYPEAFICODE, SALESORDERLINE.SUBCODE01, SALESORDERLINE.SUBCODE02, SALESORDERLINE.SUBCODE03,
                      SALESORDERLINE.SUBCODE04, SALESORDERLINE.SUBCODE05, SALESORDERLINE.SUBCODE06, SALESORDERLINE.SUBCODE07,
                      SALESORDERLINE.SUBCODE08, SALESORDERLINE.SUBCODE09, SALESORDERLINE.SUBCODE10) B
                  ON D.ORIGDLVSALORDLINESALORDERCODE = B.CODE AND 
                D.ORIGDLVSALORDERLINEORDERLINE = B.ORDERLINE 
              LEFT JOIN (
                  SELECT ITXVIEWCOLOR.ITEMTYPECODE, ITXVIEWCOLOR.SUBCODE01, ITXVIEWCOLOR.SUBCODE02,
                  ITXVIEWCOLOR.SUBCODE03, ITXVIEWCOLOR.SUBCODE04, ITXVIEWCOLOR.SUBCODE05, ITXVIEWCOLOR.SUBCODE06, 
                  ITXVIEWCOLOR.SUBCODE07, ITXVIEWCOLOR.SUBCODE08, ITXVIEWCOLOR.SUBCODE09, ITXVIEWCOLOR.SUBCODE10, 
                  ITXVIEWCOLOR.WARNA FROM ITXVIEWCOLOR ITXVIEWCOLOR) C ON
                  A.ITEMTYPECODE = C.ITEMTYPECODE AND 
                  A.DECOSUBCODE01 = C.SUBCODE01 AND
                  A.DECOSUBCODE02 = C.SUBCODE02 AND
                  A.DECOSUBCODE03 = C.SUBCODE03 AND
                  A.DECOSUBCODE04 = C.SUBCODE04 AND
                  A.DECOSUBCODE05 = C.SUBCODE05 AND
                  A.DECOSUBCODE06 = C.SUBCODE06 AND
                  A.DECOSUBCODE07 = C.SUBCODE07 AND
                  A.DECOSUBCODE08 = C.SUBCODE08 AND
                  A.DECOSUBCODE09 = C.SUBCODE09 AND
                  A.DECOSUBCODE10 = C.SUBCODE10
              WHERE A.WAREHOUSELOCATIONCODE = '$location' AND A.LOGICALWAREHOUSECODE ='M031' AND A.WHSLOCATIONWAREHOUSEZONECODE ='$zone'
              ORDER BY A.ELEMENTSCODE ASC";
            $stmt7=db2_exec($conn1,$sqlRD, array('cursor'=>DB2_SCROLLABLE));
            $no=1;
            while($rowd = db2_fetch_assoc($stmt7)){
              $sqltglm 	= "SELECT VIEWELEMENTSANALYSIS.TEMPLATECODE, VIEWELEMENTSANALYSIS.ELEMENTCODE, LEFT(VIEWELEMENTSANALYSIS.CREATIONDATETIME,10) AS tgl_mutasi FROM VIEWELEMENTSANALYSIS VIEWELEMENTSANALYSIS
              WHERE (VIEWELEMENTSANALYSIS.TEMPLATECODE ='304' OR VIEWELEMENTSANALYSIS.TEMPLATECODE ='342') AND VIEWELEMENTSANALYSIS.ELEMENTCODE = '$rowd[ELEMENTSCODE]' ORDER BY LEFT(VIEWELEMENTSANALYSIS.CREATIONDATETIME,10) DESC LIMIT 1";
              $stmt6=db2_exec($conn1,$sqltglm, array('cursor'=>DB2_SCROLLABLE));
              $result2 = db2_fetch_assoc($stmt6);
            $idcek	= $_POST['cek'][$no];
            if($idcek!=""){		
              $sqlDetail=mysqli_query($con,"INSERT INTO tbl_bon_permintaan_detail SET
              no_permintaan='$nou',
              nokk='$rowd[LOTCODE]',
              berat='$rowd[BASEPRIMARYQUANTITYUNIT]',
              berat_potong='0.00',
              panjang='$rowd[BASESECONDARYQUANTITYUNIT]',
              tempat='$location',
              sn='$rowd[ELEMENTSCODE]',
              tgl_mutasi='$result2[TGL_MUTASI]'");
             }
          $no++;
          }
    if($sqlData){
      echo "<script>window.location='StatusPermintaan';</script>"; 
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
            e.checked=document.forms['form2'].allbox2.checked;
			
        }
    }
}	
</script>