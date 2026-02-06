<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";
$ip_num = $_SERVER['REMOTE_ADDR'];
$os= $_SERVER['HTTP_USER_AGENT'];
$Bon	    = isset($_GET['bon']) ? $_GET['bon'] : '';
$Jenis	    = isset($_GET['jns']) ? $_GET['jns'] : '';
$Nokk	    = isset($_GET['nokk']) ? $_GET['nokk'] : '';
$id	        = isset($_GET['id']) ? $_GET['id'] : '';
$tglTransaksi 	= isset($_GET['tgl']) ? $_GET['tgl'] :'' ;
$DataKet	= isset($_GET['ket']) ? $_GET['ket'] : '';
$SN	        = isset($_GET['barcode']) ? $_GET['barcode'] : '';
$Shift	    = isset($_GET['shift']) ? $_GET['shift'] : '';
$Usernm	    = $_SESSION['userGKJ'];
$barcode 	= $_POST['barcode'];
$tgl 		= $_POST['awal'];
$ket1 		= $_POST['ket'];
$shift1 	= $_POST['shift'];
$id1 		= $_POST['id'];
$nobon		= $_POST['nobon'];
$kkno		= $_POST['nokk'];

if($Nokk!=""){
	$Action="PermintaanSimpan/";
}else{
	$Action="";
}

function no_urut(){
    date_default_timezone_set("Asia/Jakarta");
    include"koneksi.php";
    $format = date("y");
    $sql = sqlsrv_query(
        $con,
        "SELECT TOP 1 no_permintaan
         FROM db_qc.tbl_bon_permintaan
         WHERE LEFT(no_permintaan, 2) LIKE ?
         ORDER BY no_permintaan DESC",
        array($format . "%")
    );
    $r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC);
    if($r){
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
function no_doc(){
    date_default_timezone_set("Asia/Jakarta");
    include"koneksi.php";   
    $format = date("ymd")."4";
    $sql = sqlsrv_query(
        $con,
        "SELECT TOP 1 documentno
         FROM db_qc.pergerakan_stok
         WHERE LEFT(documentno, 7) = ?
         ORDER BY documentno DESC",
        array($format)
    );
    $r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC);
    if($r){
        $d=$r['documentno'];
        $str=substr($d,7,3);
        $Urut = (int)$str;
    }else{
        $Urut = 0;
    }
    $Urut = $Urut + 1;
    $Nol="";
    $nilai=3-strlen($Urut);
    for ($i=1;$i<=$nilai;$i++){
            $Nol= $Nol."0";
        }
    $nipbr =$format.$Nol.$Urut;
    return $nipbr;
}
function sn(){
    include"koneksi.php";
    $format = date("y")."2";
    $sql = sqlsrv_query(
        $con,
        "SELECT TOP 1 SN
         FROM db_qc.detail_pergerakan_stok
         WHERE LEFT(SN, 3) = ?
         ORDER BY SN DESC",
        array($format)
    );
    $r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC);
    if($r){
        $d=$r['SN'];
        $str=substr($d,3,10);
        $Urut = (int)$str;
    }else{
        $Urut = 0;
    }
    $Urut = $Urut + 3;
    $Nol="";
    $nilai=10-strlen($Urut);
    for ($i=1;$i<=$nilai;$i++){
    $Nol= $Nol."0";	
    }
    $snbr =$format.$Nol.$Urut;
    return $snbr;
}
$nopermintaan=no_urut();
$nodoc=no_doc();
$snkain=sn();

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Proses Bon</title>
<!-- Font Awesome -->
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">  
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">	
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body>	
<form role="form" name="form1" id="form1" action="" enctype="multipart/form-data" method="post">	
    <div class="row">
        <div class="col-lg-6">	
            <div class="card">
                <!--<div class="card-header">
                </div>-->
                <!-- /.card-header -->
                <!-- form start -->
                <div class="card-body">
                    <div class="row">			
                        <div class="col-sm-12">		
                            <div class="form-group row">
                                <label for="no_doc" class="col-md-3">No Dokumen</label>
                                <div class="col-md-5"> 
                                    <input name="no_doc" type="text" class="form-control  form-control-sm" id="no_doc" placeholder="" value="<?php echo $nodoc; ?>" readonly="readonly" >
                                    <input name="id" type="hidden" class="form-control  form-control-sm" id="id" placeholder="" value="<?php echo $id; ?>" >
                                    <input name="nobon" type="hidden" class="form-control  form-control-sm" id="nobon" placeholder="" value="<?php echo $Bon; ?>" >
                                    <input name="userid" type="hidden" class="form-control  form-control-sm" id="userid" placeholder="" value="<?php echo $_SESSION['userGKJ']; ?>">
                                </div>	
                            </div>
                            <div class="form-group row">
                                <label for="jenis" class="col-md-3">Jenis Bongkaran</label>
                                    <div class="col-md-4">  
                                        <input name="jenis" type="text" class="form-control form-control-sm" id="jenis" placeholder="" value="<?php echo $Jenis; ?>" readonly="readonly">
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label for="nokk" class="col-md-3">No KK</label>
                                    <div class="col-md-4">  
                                        <select class="form-control select2" name="nokk" id="nokk" onchange="window.location='index1.php?page=proses_bon&amp;bon=<?php echo $_GET['bon'];?>&amp;jns=<?php echo $_GET['jns'];?>&amp;id=<?php echo $_GET['id'];?>&amp;nokk='+this.value">
                                            <option value=""></option>
                                            <?php 
                                            $sqld = sqlsrv_query(
                                                $con,
                                                "SELECT nokk
                                                 FROM db_qc.tbl_bon_permintaan
                                                 WHERE refno = ? AND jns_permintaan = ?
                                                 ORDER BY id ASC",
                                                array($Bon, $Jenis)
                                            );
                                            while($rkk = sqlsrv_fetch_array($sqld, SQLSRV_FETCH_ASSOC)){
                                            ?>
                                            <option value="<?php echo $rkk['nokk'];?>" <?php if($_GET['nokk']==$rkk['nokk']){echo "SELECTED";}?>><?php echo $rkk['nokk'];?></option> 
                                            <?php } ?>
                                        </select>
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_order" class="col-md-3">No Order</label>
                                    <div class="col-md-4">
                                        <?php 
                                            $sqldo = sqlsrv_query(
                                                $con,
                                                "SELECT no_order
                                                 FROM db_qc.tbl_bon_permintaan
                                                 WHERE refno = ? AND nokk = ?
                                                 ORDER BY id ASC",
                                                array($Bon, $Nokk)
                                            );
                                            $rorder = sqlsrv_fetch_array($sqldo, SQLSRV_FETCH_ASSOC);
                                        ?>  
                                        <input name="no_order" type="text" class="form-control form-control-sm" id="no_order" placeholder="" value="<?php echo $rorder['no_order'];?>" readonly="readonly">
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label for="awal" class="col-md-3">Tgl Keluar</label>
                                <div class="col-sm-4">
                                    <input name="awal" type="date" class="form-control pull-right" required id="datepicker" placeholder="0000-00-00" value="<?php echo $tglTransaksi;?>"/>	
                                </div>
                            </div>	  
                            <div class="form-group row">
                                <label for="shift" class="col-md-3">Shift</label>
                                    <div class="col-md-3">					
                                        <select class="form-control select2" name="shift" id="shift" >
                                            <option value="">Pilih</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="<?php echo $Shift;?>" <?php if($Shift!=""){echo "SELECTED";}?>><?php echo $Shift;?></option>
                                        </select>	
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label for="ket" class="col-md-3">Keterangan</label>
                                <div class="col-md-5">
                                    <textarea name="ket" class="form-control form-control-sm" id="ket" placeholder="Note.."><?php echo $DataKet; ?></textarea>
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
                            <th width="14%"><div align="center">No</div></th>
                            <th width="14%"><div align="center">SN/Barcode</div></th>
                            <th width="23%"><div align="center">Qty (Kg)</div></th>
                            <th width="21%"><div align="center">Yard</div></th>
                            <th width="26%"><div align="center">Grade</div></th>
                            <th width="16%"><div align="center">Tempat</div></th>
                            <th width="16%"><div align="center">Ket</div></th>
                            <!-- <td width="7%"><div align="center"><input type="checkbox" name="allbox" value="check" onclick="checkAll(0);" /></div></td> -->
                        </tr>
                        </thead>  
                        <tbody>
                        <?php
                            $sqlBL 	= "SELECT A.*, E.QUALITYREASONCODE, E.LONGDESCRIPTION AS KET FROM BALANCE A 
                            LEFT JOIN 
                            (SELECT ELEMENTSINSPECTION.ELEMENTCODE,ELEMENTSINSPECTION.QUALITYREASONCODE, QUALITYREASON.LONGDESCRIPTION FROM ELEMENTSINSPECTION ELEMENTSINSPECTION
                            LEFT JOIN QUALITYREASON QUALITYREASON ON ELEMENTSINSPECTION.QUALITYREASONCODE = QUALITYREASON.CODE
                            WHERE QUALITYREASON.LONGDESCRIPTION NOT LIKE '%Tahanan%') E
                            ON A.ELEMENTSCODE = E.ELEMENTCODE
                            WHERE A.LOTCODE='$Nokk' AND A.LOGICALWAREHOUSECODE ='M031' AND NOT (A.WHSLOCATIONWAREHOUSEZONECODE='B1' OR A.WHSLOCATIONWAREHOUSEZONECODE='TMP')
                            ORDER BY A.ELEMENTSCODE ASC";
                            $stmt=db2_exec($conn1,$sqlBL, array('cursor'=>DB2_SCROLLABLE));
                            $no=1;
                            while($rowBL = db2_fetch_assoc($stmt)){
                            ?>	
                        <tr align="center">
                            <td align="center"><?php echo $no;?></td>
                            <td align="center"><?php echo $rowBL['ELEMENTSCODE'];?></td>
                            <td align="center" ><?php echo number_format($rowBL['BASEPRIMARYQUANTITYUNIT'],2);?></td>
                            <td align="center" ><?php echo number_format($rowBL['BASESECONDARYQUANTITYUNIT'],2);?></td>
                            <td align="center" ><?php if($rowBL['QUALITYLEVELCODE']=='1'){echo 'A';}else if($rowBL['QUALITYLEVELCODE']=='2'){echo 'B';}else if($rowBL['QUALITYLEVELCODE']=='3'){echo 'C';}else if($rowBL['QUALITYLEVELCODE']=='4'){echo 'D';}  ?></td>
                            <td align="center"><?php echo $rowBL['WAREHOUSELOCATIONCODE'];?></td>
                            <td align="center" ><?php echo $rowBL['KET']; ?></td>
                            <!-- <td><input type="checkbox" name="cek[<?php echo $no; ?>]" value="<?php echo $rowd['id']; ?>"/></td> -->
                        </tr>
                        <?php 
                        $toyard=$toyard+$rowBL['BASESECONDARYQUANTITYUNIT'];
                        $toqty=$toqty+$rowBL['BASEPRIMARYQUANTITYUNIT'];
                        $no++;}?>	
                        </tbody>
                        <br />  <b>Total Yard : <?php echo $toyard; ?></b><br />
                        <b>Total Qty : <?php echo $toqty; ?></b>
                    </table>
                </div>	
            </div>
        </div>
    </div>	
    <div class="row">
	    <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                <h3 class="card-title">Data Kain</h3>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label for="barcode" class="col-md-2">Barcode</label>
                            <div class="col-sm-3">  
                                <input name="barcode" type="text" class="form-control form-control-sm" id="barcode" placeholder="" value="<?php echo $SN; ?>" required>
                                <input name="userid" type="hidden" class="form-control  form-control-sm" id="userid" placeholder="" value="<?php echo $_SESSION['userGKJ']; ?>">
                            </div>
                    </div>
                    <div class="card-footer">
                        <input type="submit" value="Save" name="save" id="save" class="btn btn-success float-right"/>
                        <input type="submit" value="Batal" name="batal" id="batal" class="btn btn-danger float-right"/>
                        <input type="submit" value="Tambah" name="tambah" id="tambah" class="btn btn-primary float-left"/>	
                        <!-- <button type="submit" class="btn btn-success float-right" name="save" value="SaveDetail"><i class="fa fa-save"></i> Save</button> -->
                        <!-- <button type="submit" class="btn btn-danger float-right" name="batal" value="Batal"><i class="fa fa-times"></i> Batal</button>		 -->
                        <!-- <button type="submit" class="btn btn-primary float-left" name="tambah" value="TambahDetail"><i class="fa fa-plus"></i> Tambah</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- <form role="form" name="form3" id="form3" action="" enctype="multipart/form-data" method="post"> -->
	<div class="row">
        <div class="col-lg-12">
            <div class="card">
                    <div class="card-header">
                    <h3 class="card-title">Detail Data</h3>
                        <!-- <button type="submit" class="btn btn-success float-right" name="save" value="SaveDetail"><i class="fa fa-save"></i> Save</button> -->
                        <!-- <button type="submit" class="btn btn-danger float-right" name="batal" value="Batal"><i class="fa fa-times"></i> Batal</button> -->
                        <!-- <button type="submit" class="btn btn-primary float-left" name="tambah" value="TambahDetail"><i class="fa fa-plus"></i> Tambah</button> -->
                    </div>
                    <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table id="example6" width="100%" class="table table-sm table-bordered table-hover" style="font-size: 12px;">
                        <thead class="btn-danger">
                        <tr>
                            <th><div align="center" valign="middle">No</div></th>
                            <th><div align="center" valign="middle">No KK</div></th>
                            <th><div align="center" valign="middle">No PO</div></th>
                            <th><div align="center" valign="middle">No Order</div></th>
                            <th><div align="center" valign="middle">Warna</div></th>
                            <th><div align="center" valign="middle">Qty (Kg)</div></th>
                            <th><div align="center" valign="middle">Qty Potong (Kg)</div></th>
                            <th><div align="center" valign="middle">Yard</div></th>
                            <th><div align="center" valign="middle">Grade</div></th>
                            <th><div align="center" valign="middle">Barcode</div></th>
                            <th><div align="center" valign="middle">Keterangan</div></th>
                            <!-- <td><div align="center" valign="middle">#<input type="hidden" name="allbox2" value="check" onclick="checkAll2(0);" /></div></td> -->
                        </tr>
                        </thead>  
                        <tbody>
                        <?php
                        $sqlc = sqlsrv_query(
                            $con,
                            "SELECT * FROM db_qc.tmp_detail_pergerakan_stok a WHERE a.transtatus = ? AND a.userid = ? ORDER BY a.SN ASC",
                            array('2', $Usernm)
                        );
                        $n=1;
                        while($row = sqlsrv_fetch_array($sqlc, SQLSRV_FETCH_ASSOC)){
                            $sqld="SELECT A.*, B.CODE AS NO_ORDER,
                            B.PO_HEADER AS PO_HEADER,B.PO_LINE AS PO_LINE, TRIM(D.LONGDESCRIPTION) AS WARNA FROM BALANCE A 
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
                            (SELECT USERGENERICGROUP.CODE,USERGENERICGROUP.LONGDESCRIPTION FROM USERGENERICGROUP USERGENERICGROUP) D
                            ON A.DECOSUBCODE05=D.CODE
                            WHERE A.ELEMENTSCODE ='".$row['barcode']."'";
                            $stmt=db2_exec($conn1,$sqld, array('cursor'=>DB2_SCROLLABLE));
                            $rowd = db2_fetch_assoc($stmt);
                        ?>	
                        <tr align="center">
                            <td align="center" ><?php echo $n; ?></td>
                            <td align="center"><?php echo $row['nokk'];?></td>
                            <td align="center"><?php if($rowd['PO_HEADER']!=''){echo $rowd['PO_HEADER'];}else{echo $rowd['PO_LINE'];}?></td>
                            <td align="center"><?php echo $rowd['NO_ORDER'];?></td>
                            <td><?php echo $rowd['WARNA'];?></td>
                            <td align="center" ><?php echo number_format($row['weight'],'2','.',','); ?></td>
                            <td align="center" ><?php if($_GET['jns']=="Potong Sample" or $_GET['jns']=="Potong Kain" or $_GET['jns']=="Potong Pass Qty"){echo "<input type='text' value='0' name='qty_p' size='10'>";} ?></td>
                            <td align="center" ><?php echo number_format($row['yard_'],'2','.',','); ?></td>
                            <td align="center" ><?php if($row['grade']='1'){echo 'A';}else if($row['grade']=='2'){echo 'B';}else if($row['grade']=='3'){echo 'C';} ?></td>
                            <td align="center" ><?php echo $row['barcode']; ?></td>
                            <td align="center" ><?php echo $row['sisa']; ?></td>
                            <!-- <td><input type="checkbox" name="cek0[<?php echo $n; ?>]" value="<?php echo $row['id']; ?>"/></td> -->
                        </tr>
                        <?php 
                        $totalyard=$totalyard+$row['yard_'];
                        $totalqty=$totalqty+$row['weight'];
                        $n++;}?>	
                        </tbody>
                        <br /> <b>Total Yard : <?php echo $totalyard; ?></b><br />
                        <b>Total Qty : <?php echo $totalqty; ?></b>
                    </table>
                </div>
                <!-- /.card-body -->	
            </div>
        </div>
	</div>
</form>
<?php 
  if(isset($_POST['tambah'])){
    $sqlData = "SELECT BALANCE.*, LEFT(VIEWELEMENTSANALYSIS.CREATIONDATETIME,10) AS tgl_mutasi FROM BALANCE BALANCE 
    LEFT JOIN VIEWELEMENTSANALYSIS VIEWELEMENTSANALYSIS ON BALANCE.ELEMENTSCODE = VIEWELEMENTSANALYSIS.ELEMENTCODE 
    WHERE BALANCE.ELEMENTSCODE ='$barcode' AND (VIEWELEMENTSANALYSIS.TEMPLATECODE ='322' OR VIEWELEMENTSANALYSIS.TEMPLATECODE ='304' OR VIEWELEMENTSANALYSIS.TEMPLATECODE ='342' OR VIEWELEMENTSANALYSIS.TEMPLATECODE ='302')";
    $stmt=db2_exec($conn1,$sqlData, array('cursor'=>DB2_SCROLLABLE));
    $rowData = db2_fetch_assoc($stmt);
	$weight		= $rowData['BASEPRIMARYQUANTITYUNIT'];
	$yard		= $rowData['BASESECONDARYQUANTITYUNIT'];
	$grade		= $rowData['QUALITYLEVELCODE'];
	$satuan		= $rowData['BASESECONDARYUNITCODE'];
	$SN			= $rowData['ELEMENTSCODE'];
	$no_kk		= $rowData['LOTCODE'];
    $lokasi		= $rowData['WAREHOUSELOCATIONCODE'];
    $tgl_mutasi		= $rowData['TGL_MUTASI'];
    $sqlInsert = sqlsrv_query(
        $con,
        "INSERT INTO db_qc.tmp_detail_pergerakan_stok
         (weight, yard_, satuan, grade, barcode, nokk, transtatus, userid, lokasi, tgl_mutasi)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        array($weight, $yard, $satuan, $grade, $SN, $no_kk, '2', $Usernm, $lokasi, $tgl_mutasi)
    );
    if($sqlInsert){
      echo "<script>window.location='index1.php?page=proses_bon&bon=$Bon&jns=$Jenis&id=$id1&nokk=$Nokk&barcode=$barcode&tgl=$tgl&ket=$ket1&shift=$shift1';</script>"; 
		}
  }
  if(isset($_POST['batal'])){
    $sqldelete = sqlsrv_query(
        $con,
        "DELETE FROM db_qc.tmp_detail_pergerakan_stok WHERE transtatus = ? AND userid = ?",
        array('2', $Usernm)
    );
    if($sqldelete){
        echo "<script>window.location='index1.php?page=proses_bon&bon=$Bon&jns=$Jenis&id=$id1&nokk=$Nokk&barcode=$barcode&tgl=$tgl&ket=$ket1&shift=$shift1';</script>"; 
          }
  }
  if(isset($_POST['save'])){
    $txtKeterangan	= $_POST['ket'];
    if($_POST['jenis']=="Bongkaran"){$ket = 'Tolakan';}else if($_POST['jenis']=="Potong Pass Qty"){$ket = 'Potong Sample, Potong Pass Qty';}else if($_POST['jenis']=="Potong Sisa"){$ket= 'Revisi Stiker';}
	else{$ket = $_POST['jenis'];}
    $cmbTanggal 	= $_POST['awal'];
    $txtDok			= $_POST['no_dok'];
    $tgl_update		= $_POST['awal'];
    $documentno		= $_POST['no_doc'];
    $tgl_sj			= $_POST['awal'];
    $shift			= $_POST['shift'];
    $sqlInsert = sqlsrv_query(
        $con,
        "INSERT INTO db_qc.pergerakan_stok
         (tgl_update, documentno, tgl_sj, shift, ket, typestatus, typetrans, fromtoid, no_sj, userid)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        array($tgl_update, $documentno, $tgl_sj, $shift, $ket . "," . $txtKeterangan, '3', '2', 'OUT', $txtDok, $Usernm)
    );

    $sqlpmt = sqlsrv_query(
        $con,
        "SELECT TOP 1 no_permintaan
         FROM db_qc.tbl_bon_permintaan
         WHERE refno = ? AND nokk = ?
         ORDER BY id ASC",
        array($nobon, $kkno)
    );
    $rowp = sqlsrv_fetch_array($sqlpmt, SQLSRV_FETCH_ASSOC);
    $sqlData = sqlsrv_query(
        $con,
        "SELECT * FROM db_qc.tmp_detail_pergerakan_stok WHERE userid = ? AND transtatus = ?",
        array($Usernm, '2')
    );
    $tmpData = sqlsrv_fetch_array($sqlData, SQLSRV_FETCH_ASSOC);

    $dataBerat 			= $tmpData['weight'];
    $dataYard 			= $tmpData['yard_'];
	$dataSatuan			= $tmpData['satuan'];
	$dataGrade			= $tmpData['grade'];
	$dataSN				= $tmpData['barcode'];
	$dataKK				= $tmpData['nokk'];
	$dataLokasi 		= $tmpData['lokasi'];
	$tglmutasi			= $tmpData['tgl_mutasi'];
    $no_urut			= $rowp['no_permintaan'];
    $weight		        = $_POST['qty_p'];
    
    $sqlInsert1 = sqlsrv_query(
        $con,
        "INSERT INTO db_qc.tbl_bon_permintaan_detail
         (no_permintaan, nokk, berat, berat_potong, panjang, tempat, sn, tgl_mutasi)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
        array($no_urut, $dataKK, $dataBerat, $weight, $dataYard, $dataLokasi, $dataSN, $tglmutasi)
    );
    $sqldelete = sqlsrv_query(
        $con,
        "DELETE FROM db_qc.tmp_detail_pergerakan_stok WHERE transtatus = ? AND userid = ?",
        array('2', $Usernm)
    );
    if($sqlInsert1){
        echo "<script>window.location='index1.php?page=proses_bon&bon=$Bon&jns=$Jenis&id=$id1&nokk=$Nokk';</script>"; 
          }
  }
?>

</body>
</html>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>	
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
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

