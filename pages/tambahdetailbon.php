<?PHP
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
$ip_num 	= $_SERVER['REMOTE_ADDR'];
$os			= $_SERVER['HTTP_USER_AGENT'];
// $Nokk		= isset($_POST['nokk']) ? $_POST['nokk'] : '';
$Nokk1		= isset($_GET['nokk']) ? $_GET['nokk'] : '';
// $Bon1		= isset($_POST['refno']) ? $_POST['refno'] : '';
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
$nopermintaan=no_urut();
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
				  <button type="button" class="btn btn-success float-left" onClick="window.location.href='TambahDetailBon-<?php echo $Bon; ?>'" name="new"><i class="fa fa-file"></i> New</button>
        </div>
        <!-- /.card-header -->
        <?php 
        $sql 	= "SELECT A.LOTCODE, B.CODE AS NO_ORDER,B.ORDERPARTNERBRANDCODE,B.LONGDESCRIPTION AS BUYER, B.LEGALNAME1 AS LANGGANAN, B.ITEMDESCRIPTION AS JENIS_KAIN,
              B.PO_HEADER AS PO_HEADER,B.PO_LINE AS PO_LINE, C.PRODUCTIONDEMANDCODE AS NO_LOT,TRIM(D.LONGDESCRIPTION) AS WARNA FROM BALANCE A 
          LEFT JOIN 
              (SELECT SALESORDER.CODE,SALESORDER.ORDERPARTNERBRANDCODE,ORDERPARTNERBRAND.LONGDESCRIPTION,BUSINESSPARTNER.LEGALNAME1,
              SALESORDER.EXTERNALREFERENCE AS PO_HEADER,SALESORDERLINE.EXTERNALREFERENCE AS PO_LINE, SALESORDERLINE.ITEMDESCRIPTION FROM SALESORDER SALESORDER
              LEFT JOIN SALESORDERLINE SALESORDERLINE ON SALESORDER.CODE = SALESORDERLINE.SALESORDERCODE
              LEFT JOIN ORDERPARTNERBRAND ORDERPARTNERBRAND ON SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNERBRAND.ORDPRNCUSTOMERSUPPLIERCODE AND SALESORDER.ORDERPARTNERBRANDCODE = ORDERPARTNERBRAND.CODE
              LEFT JOIN ORDERPARTNER ORDERPARTNER ON SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNER.CUSTOMERSUPPLIERCODE
              LEFT JOIN BUSINESSPARTNER BUSINESSPARTNER ON ORDERPARTNER.ORDERBUSINESSPARTNERNUMBERID = BUSINESSPARTNER.NUMBERID
              GROUP BY SALESORDER.CODE,SALESORDER.ORDERPARTNERBRANDCODE,ORDERPARTNERBRAND.LONGDESCRIPTION,
              SALESORDER.EXTERNALREFERENCE,SALESORDERLINE.EXTERNALREFERENCE,SALESORDERLINE.ITEMDESCRIPTION,BUSINESSPARTNER.LEGALNAME1) B
          ON A.PROJECTCODE = B.CODE
          LEFT JOIN 
          (SELECT PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE, PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE FROM 
              PRODUCTIONDEMANDSTEP PRODUCTIONDEMANDSTEP
              GROUP BY PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE,PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE) C
          ON A.LOTCODE=C.PRODUCTIONORDERCODE
          LEFT JOIN 
              (SELECT USERGENERICGROUP.CODE,USERGENERICGROUP.LONGDESCRIPTION FROM USERGENERICGROUP USERGENERICGROUP) D
          ON A.DECOSUBCODE05=D.CODE
          WHERE A.LOTCODE='$Nokk1' AND B.CODE IS NOT NULL
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
                  <input name="refno" type="text" class="form-control form-control-sm" id="refno" placeholder="" value="<?php echo $Bon;?>" readonly="readonly">
					      </div>	
              </div>	
              <div class="form-group row">
                <label for="nokk" class="col-md-3">Nokk</label>
                <div class="col-md-5"> 
					        <div class="input-group input-group">
                    <input name="user_buat" type="hidden" class="form-control form-control-sm" id="user_buat" placeholder="" value="<?php echo $_SESSION['userGKJ']; ?>">
                    <input name="jabatan_buat" type="hidden" class="form-control form-control-sm" id="jabatan_buat" placeholder="" value="<?php echo $_SESSION['jabatanGKJ']; ?>">	
                    <!-- <input name="nokk" type="text" class="form-control  form-control-sm" id="nokk" placeholder="" value="<?php if($Nokk!=""){ echo $Nokk;} ?>" maxlength="25" <?php if($Nokk!=""){ echo "ReadOnly";} ?> required> -->
                    <input name="nokk" onchange="window.location='index1.php?page=tambahdetailbon&amp;refno=<?php echo $_GET['refno'];?>&amp;nokk='+this.value" onBlur="window.location='index1.php?page=tambahdetailbon&amp;refno=<?php echo $_GET['refno'];?>&amp;nokk='+this.value" type="text" class="form-control form-control-sm" id="nokk" placeholder="" value="<?php if($Nokk1!=""){ echo $Nokk1;} ?>" maxlength="25" <?php if($Nokk1!=""){ echo "ReadOnly";} ?> required>	
					          
                    <!-- <span class="input-group-append">
               	      <button type="submit" class="btn btn-success btn-sm"  <?php if($Nokk!=""){ echo "Disabled";} ?>>  <i class="fa fa-search"></i></button>
               		  </span>	 -->
					        </div>	
					      </div>	
              </div>
				      <div class="form-group row">
                <label for="langganan" class="col-md-3">Langganan</label>
                <div class="col-md-6">  
                  <input name="langganan" type="text" class="form-control form-control-sm" id="langganan" placeholder="" value="<?php if($_GET['nokk']!=''){echo $rowdb2['LANGGANAN']."/".$rowdb2['BUYER'];}?>" readonly="readonly">
					      </div>
					      <div class="col-md-2">  
                  <input name="lot" type="text" class="form-control form-control-sm" id="lot" placeholder="No Lot" value="<?php if($_GET['nokk']!=''){echo $rowdb2['NO_LOT'];} ?>" readonly="readonly">
					      </div> 
              </div>
				      <div class="form-group row">
                <label for="no_po" class="col-md-3">No PO</label>
                <div class="col-md-4">  
                  <input name="no_po" type="text" class="form-control form-control-sm" id="no_po" placeholder="" value="<?php if($rowdb2['PO_HEADER']!=''){echo $rowdb2['PO_HEADER'];}else{echo $rowdb2['PO_LINE'];} ?>" readonly="readonly">
					      </div>
					      <div class="col-md-4">  
                  <input name="no_order" type="text" class="form-control form-control-sm" id="no_order" placeholder="No Order" value="<?php if($_GET['nokk']!=''){echo $rowdb2['NO_ORDER'];} ?>" readonly="readonly">
					      </div> 
              </div>
				      <div class="form-group row">
                <label for="jenis_kain" class="col-md-3">Jenis Kain</label>
                <div class="col-md-8">  
                  <input name="jenis_kain" type="text" class="form-control form-control-sm" id="jenis_kain" placeholder="" value="<?php if($_GET['nokk']!=''){echo $rowdb2['JENIS_KAIN'];}?>" readonly="readonly">
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
                  <input name="warna" type="text" class="form-control form-control-sm" id="warna" placeholder="Warna" value="<?php if($_GET['nokk']!=''){echo $rowdb2['WARNA'];} ?>" readonly="readonly">
					      </div> 
              </div>
				      <div class="form-group row">
                <label for="ket" class="col-md-3">Keterangan</label>
					      <div class="col-md-3">
					        <select name="kategori" class="form-control form-control-sm select2" id="kategori" <?php if($Nokk!=""){echo "required";}?>>
                    <option value="">Pilih</option>
                    <option value="Potong Sample">Potong Sample</option>
                    <option value="Bongkaran">Bongkaran</option>
                    <option value="Potong Pass Qty">Potong Pass Qty</option>
                    <option value="Potong Sisa">Potong Sisa</option>
					        </select>
					      </div>
                <div class="col-md-5">
					        <textarea name="ket" class="form-control form-control-sm" <?php if($Nokk!=""){echo "required";}?> placeholder="Note.." onKeyDown="textCounter(this.form.ket,this.form.countDisplay);" onKeyUp="textCounter(this.form.ket,this.form.countDisplay);"></textarea>
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
                <th width="23%"><div align="center">Berat</div></th>
                <th width="21%"><div align="center">Panjang</div></th>
                <th width="26%"><div align="center">SN</div></th>
                <th width="16%"><div align="center">Tempat</div></th>
              </tr>
            </thead>  
            <tbody>
            <?php
              $sqlBL 	= "SELECT A.*, B.CODE AS NO_ORDER,B.ORDERPARTNERBRANDCODE,B.LONGDESCRIPTION AS BUYER, B.LEGALNAME1 AS LANGGANAN,
              B.PO_HEADER AS PO_HEADER,B.PO_LINE AS PO_LINE, C.PRODUCTIONDEMANDCODE AS NO_LOT,TRIM(D.LONGDESCRIPTION) AS WARNA FROM BALANCE A 
              LEFT JOIN 
                  (SELECT SALESORDER.CODE,SALESORDER.ORDERPARTNERBRANDCODE,ORDERPARTNERBRAND.LONGDESCRIPTION,BUSINESSPARTNER.LEGALNAME1,
                  SALESORDER.EXTERNALREFERENCE AS PO_HEADER,SALESORDERLINE.EXTERNALREFERENCE AS PO_LINE FROM SALESORDER SALESORDER
                  LEFT JOIN SALESORDERLINE SALESORDERLINE ON SALESORDER.CODE = SALESORDERLINE.SALESORDERCODE
                  LEFT JOIN ORDERPARTNERBRAND ORDERPARTNERBRAND ON SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNERBRAND.ORDPRNCUSTOMERSUPPLIERCODE AND SALESORDER.ORDERPARTNERBRANDCODE = ORDERPARTNERBRAND.CODE
                  LEFT JOIN ORDERPARTNER ORDERPARTNER ON SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE = ORDERPARTNER.CUSTOMERSUPPLIERCODE
                  LEFT JOIN BUSINESSPARTNER BUSINESSPARTNER ON ORDERPARTNER.ORDERBUSINESSPARTNERNUMBERID = BUSINESSPARTNER.NUMBERID
                  GROUP BY SALESORDER.CODE,SALESORDER.ORDERPARTNERBRANDCODE,ORDERPARTNERBRAND.LONGDESCRIPTION,
                  SALESORDER.EXTERNALREFERENCE,SALESORDERLINE.EXTERNALREFERENCE,BUSINESSPARTNER.LEGALNAME1) B
              ON A.PROJECTCODE = B.CODE
              LEFT JOIN 
              (SELECT PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE, PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE FROM 
                  PRODUCTIONDEMANDSTEP PRODUCTIONDEMANDSTEP
                  GROUP BY PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE,PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE) C
              ON A.LOTCODE=C.PRODUCTIONORDERCODE
              LEFT JOIN 
                  (SELECT USERGENERICGROUP.CODE,USERGENERICGROUP.LONGDESCRIPTION FROM USERGENERICGROUP USERGENERICGROUP) D
              ON A.DECOSUBCODE05=D.CODE
              WHERE A.LOTCODE='$Nokk1' AND A.LOGICALWAREHOUSECODE ='M031' AND NOT (A.WHSLOCATIONWAREHOUSEZONECODE='B1' OR A.WHSLOCATIONWAREHOUSEZONECODE='TMP')
            ORDER BY A.ELEMENTSCODE ASC";
            $stmt=db2_exec($conn1,$sqlBL, array('cursor'=>DB2_SCROLLABLE));
            while($rowBL = db2_fetch_assoc($stmt)){
					  ?>	
              <tr align="center">
                <td align="center"><?php echo number_format($rowBL['BASEPRIMARYQUANTITYUNIT'],2);?></td>
                <td align="center"><?php echo number_format($rowBL['BASESECONDARYQUANTITYUNIT'],2);?></td>
                <td align="center"><?php echo $rowBL['ELEMENTSCODE'];?></td>
                <td><?php echo $rowBL['WHSLOCATIONWAREHOUSEZONECODE']."-".$rowBL['WAREHOUSELOCATIONCODE'];?></td>
              </tr>
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
          $sqlcount = mysqli_query($con,"SELECT *
          FROM
            tbl_bon_permintaan 
          WHERE
            ISNULL( refno ) AND dept='$Dept'
          GROUP BY
            id");
          $cek=mysqli_num_rows($sqlcount);
          ?>
          <input type="submit" value="Save" name="savebon" id="savebon" class="btn btn-primary float-right" <?php if($cek==0){echo "disabled";}?>/>	
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
                <td><div align="center" valign="middle">#<input type="hidden" name="allbox2" value="check" onclick="checkAll2(0);" /></div></td>
              </tr>
            </thead>  
            <tbody>
            <?php
            $sqldata = mysqli_query($con,"SELECT *
            FROM
              tbl_bon_permintaan 
            WHERE
              ISNULL( refno ) AND dept='$Dept'
            GROUP BY
              id");
            $n=1;
            while($row = mysqli_fetch_array($sqldata)){
					  ?>	
              <tr align="center">
                <td align="center"><?php echo date("Y-m-d",strtotime($row['tgl_buat']));?></td>
                <td align="center"><?php echo $row['langganan'];?></td>
                <td align="center"><?php echo $row['no_po'];?></td>
                <td><?php echo $row['no_order'];?></td>
                <td><?php echo $row['jenis_kain'];?></td>
                <td><?php echo $row['warna'];?></td>
                <td><?php echo $row['nokk'];?></td>
                <td><?php echo $row['no_lot'];?></td>
                <td align="left"><?php if($row['jns_permintaan']=="Bongkaran"){echo "<span class='badge badge-warning'>".$row['jns_permintaan']."</span>";}else if($row['jns_permintaan']=="Potong Sample"){echo "<span class='badge badge-success'>".$row['jns_permintaan']."</span>";}else if($row['jns_permintaan']=="Potong Pass Qty"){echo "<span class='badge badge-success'>".$row['jns_permintaan']."</span>";}else if($row['jns_permintaan']=="Potong Sisa"){echo "<span class='badge badge-info'>".$row['jns_permintaan']."</span>";}?><br><?php echo $row['ket'];?></td>
                <td><input type="checkbox" name="cek0[<?php echo $n; ?>]" value="<?php echo $row['id']; ?>"/></td>
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
    $no=1;
		$refno=$_POST['refno'];	
		$nokk=$_POST['nokk'];
		$langganan=str_replace("'","''",$_POST['langganan']);
		$nopo=$_POST['no_po'];
		$noorder=$_POST['no_order'];
		$jeniskain=str_replace("'","''",$_POST['jenis_kain']);
		$warna=str_replace("'","''",$_POST['warna']);
		$lot=$_POST['lot'];
		$dept=$_POST['dept'];
		$jnsP=$_POST['kategori'];
		$user_buat=$_POST['user_buat'];
		$jabatan_buat=$_POST['jabatan_buat'];
    $ket=str_replace("'","''",$_POST['ket']);
    $sqlcek = mysqli_query($con,"SELECT * FROM `tbl_bon_permintaan` WHERE refno='$refno' AND NOT ISNULL( refno )");
    $cek = mysqli_num_rows($sqlcek);
    if($cek>0){
      $sqldt = mysqli_query($con,"SELECT * FROM `tbl_bon_permintaan` WHERE refno='$refno' AND NOT ISNULL( refno ) LIMIT 1");
      $rowdt = mysqli_fetch_array($sqldt);
      $sqlInsert=mysqli_query($con,"INSERT INTO tbl_bon_permintaan SET
      no_permintaan='$nopermintaan',
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
      personil_buat='$rowdt[personil_buat]',
      jabatan_buat='$rowdt[jabatan_buat]',
      tgl_buat='$rowdt[tgl_buat]',
      personil_periksa='$rowdt[personil_periksa]',
      jabatan_periksa='$rowdt[jabatan_periksa]',
      tgl_periksa='$rowdt[tgl_periksa]',
      personil_approve='$rowdt[personil_approve]',
      jabatan_approve='$rowdt[jabatan_approve]',
      tgl_approve='$rowdt[tgl_approve]',
      tgl_update=now()");
    }else{
      $sqlInsert=mysqli_query($con,"INSERT INTO tbl_bon_permintaan SET
      no_permintaan='$nopermintaan',
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
    }
    if($sqlInsert){
      echo "<script>window.location='TambahDetailBon-$refno';</script>"; 
		}
  }

  if(isset($_POST['savebon'])){
		$no=1;
		$Dept	= $_SESSION['deptGKJ'];
		$refno=$_GET['refno'];
		$usernm=$_SESSION['userGKJ'];
		$ip= $_SERVER['REMOTE_ADDR'];
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
      refno='$refno', 
      `status`='Sedang Proses',
      status_tambah='1',
      personil_tambah='$usernm',
      tgl_tambah=now() 
      WHERE id='$idcek'");
      $sqlInsert=mysqli_query($con,"INSERT INTO tbl_log_bon_gkj SET
      proses='Tambah Detail Bon Permintaan',
      detail_proses='User Menambah Detail KK: $result[nokk] Pada Bon: $refno ',
      user='$usernm',
      waktu_proses=now(),
      ip='$ip'");
    }
		$no++;
		}

  if($sql){
    echo "<script>window.location='ProsesPermintaanBon';</script>";
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