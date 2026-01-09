<?PHP
include_once 'controllers/permintaanClass.php';
// instance objek
$permintaan = new Permintaan();
$ip_num = $_SERVER['REMOTE_ADDR'];
$os= $_SERVER['HTTP_USER_AGENT'];
$Dept	    = $_SESSION['deptGKJ'];
$Awal	= isset($_POST['awal']) ? $_POST['awal'] : '';
$Akhir	= isset($_POST['akhir']) ? $_POST['akhir'] : '';
$Buyer	= isset($_POST['buyer']) ? $_POST['buyer'] : '';
$Order	= isset($_POST['no_order']) ? $_POST['no_order'] : '';
$Item	= isset($_POST['no_item']) ? $_POST['no_item'] : '';
$Warna	= isset($_POST['no_warna']) ? $_POST['no_warna'] : '';
$Lokasi	= isset($_POST['lokasi']) ? $_POST['lokasi'] : '';
$BS	    = isset($_POST['bs']) ? $_POST['bs'] : '';
$Ket	= isset($_POST['ket']) ? $_POST['ket'] : '';
$Lebar	= isset($_POST['lbr']) ? $_POST['lbr'] : '';
$Gramasi= isset($_POST['grms']) ? $_POST['grms'] : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Persediaan Kain Jadi</title>
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">	
</head>

<body>
<section class="content">	
    <form role="form" method="post" enctype="multipart/form-data" name="form1">
        <div class="row">		  
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                    <h3 class="card-title">Filter Persediaan Kain Jadi</h3>				
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">			
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="awal" class="col-md-2">Tgl Awal</label>
                                    <div class="col-sm-2">
                                        <input name="awal" type="date" class="form-control pull-right" id="datepicker" placeholder="0000-00-00" value="<?php echo $Awal;?>"/>	
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="akhir" class="col-md-2">Tgl Akhir</label>
                                    <div class="col-sm-2">
                                        <input name="akhir" type="date" class="form-control pull-right" id="datepicker1" placeholder="0000-00-00" value="<?php echo $Akhir;?>"/>	
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="buyer" class="col-md-2">Buyer/Order/Item/No Warna</label>
                                    <div class="col-sm-2">
                                        <input name="buyer" type="text" class="form-control pull-right" id="buyer" placeholder="Buyer" value="<?php echo $Buyer;  ?>" autocomplete="off"/>
                                    </div>
                                    <div class="col-sm-2">
                                        <input name="no_order" type="text" class="form-control pull-right" id="no_order" placeholder="No Order" value="<?php echo $Order;  ?>" autocomplete="off"/>
                                    </div>
                                    <div class="col-sm-2">
                                        <input name="no_item" type="text" class="form-control pull-right" id="no_item" placeholder="No Item" value="<?php echo $Item;  ?>" autocomplete="off"/>
                                    </div>
                                    <div class="col-sm-2">
                                        <input name="no_warna" type="text" class="form-control pull-right" id="no_warna" placeholder="No Warna" value="<?php echo $Warna;  ?>" autocomplete="off"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="lebar" class="col-md-2">Lebar/Gramasi</label>
                                    <div class="col-sm-1">
                                        <input name="lbr" type="text" class="form-control pull-right" id="lbr" placeholder="Lebar" value="<?php echo $Lebar;  ?>" autocomplete="off"/>
                                    </div>
                                    <div class="col-sm-1">
                                        <input name="grms" type="text" class="form-control pull-right" id="grms" placeholder="Gramasi" value="<?php echo $Gramasi;  ?>" autocomplete="off"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="lokasi" class="col-md-2">Lokasi</label>
                                    <div class="col-sm-2">
                                        <select class="form-control select2" name="lokasi" id="lokasi">
                                            <option value="">Pilih</option>
                                            <?php
                                            if (is_array($permintaan->tampillokasi()) || is_object($permintaan->tampillokasi())){
                                            foreach($permintaan->tampillokasi() as $row){
                                            ?>
                                            <option value="<?php echo $row[lokasi];?>" <?php if($Lokasi==$row[lokasi]){echo "SELECTED";}?>><?php echo $row[lokasi];?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="sisa" class="col-md-2">&nbsp;</label>
                                    <div class="col-sm-2">
                                        <select class="form-control select2" name="ket" id="ket">
                                            <option value="">Pilih</option>
                                            <option value="SISA" <?php if($Ket=="SISA"){echo "SELECTED";}?>>SISA</option>
                                        </select>
                                    </div>	
                                </div>
                                <div class="form-group row">
                                    <label for="bs" class="col-md-2">&nbsp;</label>		  
                                    <div class="col-sm-2">
                                        <input type="checkbox" name="bs" id="bs" value="1" <?php  if($BS=="1"){ echo "checked";} ?>>  
                                        <label> BS</label>
                                    </div>		  	
                                </div>	
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right"><i class="fa fa-search"></i> Cari Data</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">		  
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                    <h3 class="card-title">Persediaan Kain Jadi</h3>		
                        <!--<div align="right">
                            <a href="views/pages/cetak/excelsummary-bon.php?awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>&status=<?php echo $Status; ?>" class="btn btn-primary <?php if($Awal==""){echo "disabled";}?>" target="_blank"><i class="fa fa-file-excel" data-toggle="tooltip" data-placement="top" title="Cetak Excel"></i> Excel</a>
                            <a href="views/pages/cetak/lap-tg.php?awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>" class="btn btn-warning <?php if($Awal==""){echo "disabled";}?>" target="_blank"><i class="fa fa-file" data-toggle="tooltip" data-placement="top" title="Laporan TG"></i> Laporan TG</a>
                            <a href="views/pages/cetak/excellap-tg.php?awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>" class="btn btn-success <?php if($Awal==""){echo "disabled";}?>" target="_blank"><i class="fa fa-file-excel" data-toggle="tooltip" data-placement="top" title="Laporan TG Excel"></i> Laporan TG</a>				
                        </div>-->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">		  				
                        <table id="example5" width="100%" class="table table-sm table-bordered table-hover tree" style="font-size: 13px;">
                            <thead class="btn-success">
                                <tr>
                                    <th rowspan="3"><div align="center">No</div></th>
                                    <th rowspan="3"><div align="center">Tgl</div></th>
                                    <th rowspan="3"><div align="center">No Item</div></th>
                                    <th rowspan="3"><div align="center">Langganan</div></th>
                                    <th rowspan="3"><div align="center">PO</div></th>
                                    <th rowspan="3"><div align="center">Order</div></th>
                                    <th rowspan="3"><div align="center">Jenis Kain</div></th>
                                    <th rowspan="3"><div align="center">No Warna</div></th>
                                    <th rowspan="3"><div align="center">Warna</div></th>
                                    <th rowspan="3"><div align="center">No Card</div></th>
                                    <th rowspan="3"><div align="center">Lot</div></th>
                                    <th rowspan="3"><div align="center">Roll</div></th>
                                    <th colspan="7"><div align="center">Netto (Kg)</div></th>
                                    <th rowspan="3"><div align="center">Yard/Meter</div></th>
                                    <th rowspan="3"><div align="center">Unit</div></th>
                                    <th rowspan="3"><div align="center">Lokasi</div></th>
                                    <th rowspan="3"><div align="center">FOC</div></th>
                                    <th rowspan="3"><div align="center">LBR</div></th>
                                    <th rowspan="3"><div align="center">X</div></th>
                                    <th rowspan="3"><div align="center">GRMS</div></th>
                                    <th rowspan="3"><div align="center">OL</div></th>
                                    <th rowspan="3"><div align="center">Status</div></th>
                                    <th rowspan="3"><div align="center">Keterangan</div></th>
                                    <th rowspan="3"><div align="center">Status Warna</div></th>
                                </tr>
                                <tr>
                                    <th colspan="2"><div align="center">Grade A</div></th>
                                    <th colspan="2"><div align="center">Grade B</div></th>
                                    <th colspan="2"><div align="center">Grade C</div></th>
                                    <th rowspan="2"><div align="center">Keterangan</div></th>
                                </tr>
                                <tr>
                                    <th><div align="center">Roll</div></th>
                                    <th><div align="center">KG</div></th>
                                    <th><div align="center">Roll</div></th>
                                    <th><div align="center">KG</div></th>
                                    <th><div align="center">Roll</div></th>
                                    <th><div align="center">KG</div></th>
                                </tr>
                            </thead>
                            <tbody> 
                            <?php
                            if($Awal!="" OR $Buyer!="" OR $Order!="" OR $Item!="" OR $Warna!="" OR $Lokasi!="" OR $BS!="" OR $Ket!="" OR $Lebar!="" OR $Gramasi!=""){
                            if (is_array($permintaan->tampilpersediaankain($Awal,$Akhir,$Buyer,$Order,$Item,$Warna,$Lokasi,$BS,$Ket,$Lebar,$Gramasi)) || is_object($permintaan->tampilpersediaankain($Awal,$Akhir,$Buyer,$Order,$Item,$Warna,$Lokasi,$BS,$Ket,$Lebar,$Gramasi))){
                            $n=1;
                            $i = 1;
                            $cBooking = "";
                            $p0 = "";
                            foreach($permintaan->tampilpersediaankain($Awal,$Akhir,$Buyer,$Order,$Item,$Warna,$Lokasi,$BS,$Ket,$Lebar,$Gramasi) as $row){
                                foreach($permintaan->tampiltempat($row[nokk],$row[sisa]) as $myBlk){}
                                foreach($permintaan->tampilcatatan($row[nokk],$row[sisa]) as $myBlkC){}
                                foreach($permintaan->tampilcatatan1($row[id_stok],$row[nokk],$row[sisa]) as $myBlkC1){}
                                $catat="";
                                if($myBlkC1['catatan']!=""){
                                $catat=$myBlkC1['catatan'].$myBlkC1['sisa'];}
                                else{
                                    if($permintaan->cekcatatan($row[id_stok],$row[nokk],$row[sisa])>0){}else{
                                        $catat=$myBlkC['catatan'];}
                                    }
                                foreach($permintaan->tampilsum($row[nokk],$row[sisa],$row[id_stok],$row[ket_stok]) as $myro){}
                                foreach($permintaan->tampildatakite($row[nokk]) as $myBlk1){}
                                foreach($permintaan->tampildtkain($row[nokk]) as $myBlk2){}
                                if($row['sisa']=="SISA" OR $row['sisa']=="FKSI"){
                                    $brt_sisa=$myro['grd_a']+$myro['grd_b']+$myro['grd_c'];
                                    if($brt_sisa>10 and substr($row[tgl_update],0,10)>="2019-01-01"){$sts_sisa="Sisa Produksi";}
                                    else if($brt_sisa<=10 and substr($row[tgl_update],0,10)>="2019-01-01"){$sts_sisa="Sisa Toleransi";}
                                }else{$sts_sisa="";}	
                                $brt_sisa1=$myro['grd_a']+$myro['grd_b']+$myro['grd_c'];
                                if($myBlk1['no_po']!=""){$p0=$myBlk1['no_po'];}else{$p0=$myBlk2['no_po'];}
                                $strp0=strtoupper($p0);
                                $strp1=strtoupper($p0);
                                $cBooking=strpos($strp0,"BOOKING");
                                $cMiniBulk=strpos($strp0,"MINI BULK");
                            ?>	  
                            <tr>
                                <td><?php echo $n;?></td>
                                <td><?php echo date("d-M-Y", strtotime($row['tgl_update']));?></td>
                                <td><b title="<?php echo $myBlk1['no_item'];?>"><?php echo substr($myBlk1['no_item'],0,8)."...";?></b></td>
                                <td><b title="<?php echo $myBlk1['pelanggan'];?>"><?php echo substr($myBlk1['pelanggan'],0,7)."...";?></b></td>
                                <td><b title="<?php if($myBlk1['no_po']!=""){echo $myBlk1['no_po'];}else{echo $myBlk2['no_po'];}?>"><?php if($myBlk1['no_po']!=""){echo substr($myBlk1['no_po'],0,7)."...";}else{echo substr($myBlk2['no_po'],0,7)."...";}?></b></td>
                                <td><?php if($myBlk1['no_order']!=""){echo $myBlk1['no_order'];}else{echo $myBlk2['no_order'];}?></td>
                                <td><b title="<?php echo htmlentities($myBlk1['jenis_kain'],ENT_QUOTES);?>"><?php echo htmlentities(substr($myBlk1['jenis_kain'],0,7)."...",ENT_QUOTES);?></b></td>
                                <td><b title="<?php echo $myBlk1['no_warna'];?>"><?php echo substr($myBlk1['no_warna'],0,7)."...";?></b></td>
                                <td><b title="<?php echo $myBlk1['warna'];?>"><?php echo substr($myBlk1['warna'],0,7)."...";?></b></td>
                                <td><a href="#" class="detailpersediaan" id="<?php echo $row['nokk']; ?>" ket="<?php echo $row['sisa']; ?>"><?php echo $row['nokk']; ?></a></td>
                                <td><?php echo trim($myBlk1['no_lot']);?></td>
                                <td align="right"><?php echo $myro['tot_rol'];?></td>
                                <td align="right"><?php echo $myro['rol_a'];?></td>
                                <td align="right"><?php echo number_format($myro['grd_a'],'2','.',',');?></td>
                                <td align="right"><?php echo $myro['rol_b'];?></td>
                                <td align="right"><?php echo number_format($myro['grd_b'],'2','.',',');?></td>
                                <td align="right"><?php echo $myro['rol_c'];?></td>
                                <td align="right"><?php echo number_format($myro['grd_c'],'2','.',',');?></td>
                                <td><?php if($row['sisa']=="SISA" OR $row['sisa']=="FKSI"){echo "SISA";}?></td>
                                <td align="right"><?php
                                if($myro['satuan']=="PCS"){echo number_format($myro['netto'])." ".$myro['satuan'];}else{
                                echo number_format($myro['tot_yard'],'2','.',',')." ".$myro['satuan'];} ?></td>
                                <td><?php if($myBlk['tempat']!=""){echo $myBlk['tempat'];}else if($row['blok']!=""){echo $row['blok'];}else{echo "N/A";}?></td>
                                <td><?php if($row['lokasi']!=""){echo $row['lokasi'];}else{echo "N/A";}?></td>
                                <td><?php if($myro['sisa']=="FOC"){echo "FOC";}?></td>
                                <td><?php echo $myBlk1['lebar'];?></td>
                                <td>X</td>
                                <td><?PHP echo $myBlk1['berat']; ?></td>
                                <td><?php if($row['sisa']=="KITE" OR $row['sisa']=="FKSI"){echo "Fasilitas KITE";}?></td>
                                <td align="center">
                                <?php if($row['ket_stok']!=""){
                                    echo trim($row['ket_stok']);
                                    }else if($cBooking>-1 or $cMiniBulk > -1){
                                        echo "Booking";
                                        }else if(($row['sisa']=="FKSI" or $row['sisa']=="SISA")){
                                            echo trim($sts_sisa);
                                            }else{echo trim($row['sts_stok']);}?>
                                </td>
                                <td align="center"><?php if($catat!=""){echo $catat;}?></td>
                                <td align="center">
                                <?php 
                                if (is_array($permintaan->tampilstswarna($row[id_stok],$row[id_detail],$row[nokk])) || is_object($permintaan->tampilstswarna($row[id_stok],$row[id_detail],$row[nokk]))){
                                    foreach($permintaan->tampilstswarna($row[id_stok],$row[id_detail],$row[nokk]) as $dataSttsClr){}}
                                    if ($dataSttsClr['note'] != '') {
                                        echo $dataSttsClr['note'];
                                        } else {
                                        echo "Empty";
                                        }
                                ?>
                                </td>
                                <?php $i++; ?>
                            </tr>
                            <?php 
                                    if($myro['sisa']=="SISA" OR $myro['sisa']=="FKSI" OR $myro['sisa']=="FOC"){$brtoo=0;}else{$brtoo=number_format($row['bruto'],'2','.',',');}
                                    $totbruto=$totbruto+$brtoo;
                                    $totyard=$totyard+$myro['tot_yard'];
                                    $totrol=$totrol+$myro['tot_rol'];
                                    $totrola=$totrola+$myro['rol_a'];
                                    $totrolb=$totrolb+$myro['rol_b'];
                                    $totrolc=$totrolc+$myro['rol_c'];
                                    $totab=$totab+$myro['grd_ab'];
                                    $tota=$tota+$myro['grd_a'];
                                    $totb=$totb+$myro['grd_b'];
                                    $totc=$totc+$myro['grd_c'];
                                    $totpcs=$totpcs +$myro['netto'];
                                    $rolab=$rolab + $myro['jml_ab'];
                                    $rolac=$rolac + $myro['jml_grd_c'];
                                if($myro['satuan']=='Meter')
                                {$kartot=$kartot + $myro['tot_yard']; $totrolm = $totrolm + $myro['tot_rol'];}
                                if($myro['satuan']=='Yard')
                                {$pltot=$pltot + $myro['tot_yard'];   $totroly = $totroly + $myro['tot_rol'];}
                                if($myro['satuan']=='PCS')
                                {$totrolp = $totrolp + $myro['tot_rol'];}
                            $n++;} } }?>
                            </tbody>
                            <tfoot>
                                <tr bgcolor="#99FFFF">
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99"></td>
                                    <td bgcolor="#CCFF99"></td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99"></td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                </tr>
                                <tr bgcolor="#99FFFF">
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99"></td>
                                    <td bgcolor="#CCFF99"></td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">PCS</td>
                                    <td align="right" bgcolor="#CCFF99"><?php echo number_format($totrolp); ?></td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99"></td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                </tr>
                                <tr bgcolor="#99FFFF">
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">Meter</td>
                                    <td align="right" bgcolor="#CCFF99"><?php echo number_format($totrolm); ?></td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">Meter</td>
                                    <td align="right" bgcolor="#CCFF99"><?php echo number_format($kartot,'2','.',','); ?></td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td align="right" bgcolor="#CCFF99">&nbsp;</td>
                                    <td align="right" bgcolor="#CCFF99"><?php echo number_format($totpcs); ?></td>
                                    <td bgcolor="#CCFF99">PCS</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                </tr>
                                <tr bgcolor="#99FFFF">
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">Yard</td>
                                    <td align="right" bgcolor="#CCFF99"><?php echo  number_format($totroly);?></td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">Yard</td>
                                    <td align="right" bgcolor="#CCFF99"><?php echo  number_format($pltot,'2','.',',');?></td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                </tr>
                                <tr bgcolor="#99FFFF">
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99"><b>Total</b></td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td align="right" bgcolor="#CCFF99"><b><?php echo $totrol;?></b></td>
                                    <td align="right" bgcolor="#CCFF99"><b><?php echo $totrola;?></b></td>
                                    <td align="right" bgcolor="#CCFF99"><b><?php echo number_format($tota,'2','.',',');?></b></td>
                                    <td align="right" bgcolor="#CCFF99"><b><?php echo $totrolb;?></b></td>
                                    <td align="right" bgcolor="#CCFF99"><b><?php echo number_format($totb,'2','.',',');?></b></td>
                                    <td align="right" bgcolor="#CCFF99"><b><?php echo $totrolc;?></b></td>
                                    <td align="right" bgcolor="#CCFF99"><b><?php echo number_format($totc,'2','.',',');?></b></td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td align="right" bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                    <td bgcolor="#CCFF99">&nbsp;</td>
                                </tr>
                                <tr bgcolor="#99FFFF">
                                    <td colspan="30" bgcolor="#CCFF99"><b>
                                    ( Roll : <?php echo  number_format($totrol);  ?> )
                                    <font color="Blue">(GRADE A: <?php echo  number_format($tota,'2','.',',');  ?> Kg, Roll: <?php echo  number_format($totrola);  ?>)</font>
                                    <font color="Green">(GRADE B: <?php echo  number_format($totb,'2','.',',');  ?> Kg, Roll: <?php echo  number_format($totrolb);  ?>)</font>
                                    <font color="Red">(GRADE C: <?php echo  number_format($totc,'2','.',',');  ?> Kg, Roll: <?php echo  number_format($totrolc);  ?>)</font>
                                    (TOTAL : <?php echo  number_format($tota+$totb+$totc,'2','.',',');  ?> Kg) </b></td>
                                </tr>
                                <b>
                                ( Roll : <?php echo  number_format($totrol);  ?> )
                                <font color="Blue">(GRADE A: <?php echo  number_format($tota,'2','.',',');  ?> Kg, Roll: <?php echo  number_format($totrola);  ?>)</font>
                                <font color="Green">(GRADE B: <?php echo  number_format($totb,'2','.',',');  ?> Kg, Roll: <?php echo  number_format($totrolb);  ?>)</font>
                                <font color="Red">(GRADE C: <?php echo  number_format($totc,'2','.',',');  ?> Kg, Roll: <?php echo  number_format($totrolc);  ?>)</font>
                                (TOTAL : <?php echo  number_format($tota+$totb+$totc,'2','.',',');  ?> Kg)</b>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->	
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
	</form>
    <!-- /.row -->
</section>
<!-- /.content -->
<div id="DetailPersediaan" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
</body>
</html>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<script>
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();
	});
</script>
