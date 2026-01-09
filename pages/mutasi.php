<?PHP
include_once 'controllers/mutasiClass.php';
// instance objek
$mutasi     = new Mutasi();
// ip clients
$ip_num 	= $_SERVER['REMOTE_ADDR'];
$os			= $_SERVER['HTTP_USER_AGENT'];
$nomutasiP	= isset($_POST['no_mutasi']) ? $_POST['no_mutasi'] : '';
$nomutasiG	= isset($_GET['no_mutasi']) ? $_GET['no_mutasi'] : '';
$act 		= isset($_GET['act'])?$_GET['act']:'';
if($nomutasiP!=""){ 
$nomutasi=trim($nomutasiP);
}else{
$nomutasi=trim($nomutasiG);	
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Input Mutasi</title>

</head>
<body>	
<form role="form" method="post" enctype="multipart/form-data" name="form2" action="Mutasi">			
	<div class="row">					  
                    <div class="col-sm-2">
			  <div class="form-group">
				  <div class="col-md-12">
                  <input class="form-control" name="no_mutasi" value="<?php echo trim($nomutasi); ?>">								  	  
				  </div>				  			  	  
                </div>
					</div>
					  
				<div class="col-sm-2">
				<div class="form-group">
				  <div class="col-md-12">	  
			  	<button class="btn btn-success" type="submit"><i class="fa fa-search"></i> Search</button>				  
				  </div>
				  </div>
						
		    </div>
					  
		</div></form>	
<form role="form" method="post" enctype="multipart/form-data" name="form1" id="form1" action="MutasiSimpan/">
<input type="hidden" value="<?php echo trim($nomutasi); ?>" name="no_mutasi1">
<input type="hidden" value="<?php echo $_SESSION['userGKJ']; ?>" name="user_buat">	
	
<div class="row"> 
     <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
			<h3 class="card-title">Input Data Mutasi</h3>
			<div class="form-group">
				     <button class="btn btn-primary float-right" type="submit" name="save" value="Save"><i class="fa fa-save"></i> Save </button>					 
				</div>	
			</div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
<table id="example1" width="100%" class="table table-sm table-striped table-bordered" style="font-size: 14px;">
 <thead class="btn-danger">
  <tr>
    <th width="2%"><div align="center">No.</div></th>
    <th width="5%"><div align="center">Nokk</div></th>
    <th width="17%"><div align="center">Langganan</div></th>
    <th width="13%"><div align="center">No Order</div></th>
    <th width="13%"><div align="center">PO</div></th>
    <th width="7%"><div align="center">Lot</div></th>
    <th width="5%"><div align="center">Rol</div></th>
    <th width="9%"><div align="center">Berat</div></th>
    <th width="10%"><div align="center">Panjang</div></th>
    <th width="7%"><div align="center">Action</div></th>
    <th width="7%"><div align="center"><input type="checkbox" name="allbox" value="check" onClick="checkAll(0);" /></div></th>
    </tr>
  </thead>
  <tbody>
  <?php 
$no=1;  
if (is_array($mutasi->tampil_mutasi($nomutasi)))
{ 	 
if($nomutasi!=""){		
foreach($mutasi->tampil_mutasi($nomutasi) as $rowd){	
	$idstk=$mutasi->cek_idstok($rowd['id'],$nomutasi);
	$cekserah=$mutasi->cek_serah($rowd['id'],$nomutasi);
	$idserah=$mutasi->id_serah($rowd['id'],$nomutasi);
	?>
  <tr align="center">
    <td align="center"><?php echo $no;?></td>
    <td align="center"><?php echo $rowd['nokk'];?></td>
    <td align="left"><?php echo $rowd['pelanggan'];?></td>
    <td align="center"><?php echo $rowd['no_order'];?></td>
    <td align="center"><?php echo $rowd['no_po'];?></td>
    <td align="center"><?php echo $rowd['no_lot'];?></td>
    <td align="center"><?php echo $rowd['tot_rol'];?></td>
    <td align="right"><?php echo $rowd['tot_qty'];?></td>
    <td align="right"><?php echo $rowd['tot_yard']." ".$rowd['satuan'];?></td>
    <td align="center"><a href="#" class="btn btn-xs btn-danger <?php if($cekserah!="" or $idserah==""){ echo "disabled";} ?>" onclick="confirm_delete('DelMutasi-<?php echo $idserah; ?>-<?php echo $nomutasi; ?>');"><i class="fa fa-trash"></i> </a></td>
    <td align="center"><input type="checkbox" name="cek[<?php echo $no; ?>]" value="<?php echo $rowd['id']; ?>" <?php if($idstk==$rowd['id']){ echo "disabled";} ?>/></td>
    </tr>
	  <?php $no++; } }}else{
	echo "<script>window.alert('data tidak ditemukan');</script>";
}?>
  </tbody>
</table>
			  </div>	
				
</div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
</div>	
</form>
<!-- Modal Popup untuk Edit--> 
<div id="StsUsrEdit" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<div id="LvlUsrEdit" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>	
<!-- Modal Popup untuk delete-->
            <div class="modal fade" id="delMutasi" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content" style="margin-top:100px;">
                  <div class="modal-header">
					<h4 class="modal-title">INFOMATION</h4>  
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    
                  </div>
					<div class="modal-body">
						<h5 class="modal-title" style="text-align:center;">Are you sure to delete this information ?</h5>
					</div>	
                  <div class="modal-footer justify-content-between">
                    <a href="#" class="btn btn-danger" id="delete_link">Delete</a>
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                  </div>
                </div>
              </div>
            </div>	
</body>
</html>


<script type="text/javascript">
              function confirm_delete(delete_url) {
                $('#delMutasi').modal('show', {
                  backdrop: 'static'
                });
                document.getElementById('delete_link').setAttribute('href', delete_url);
              }
</script>
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
</script>