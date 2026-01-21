<?PHP
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";
// ip clients
$ip_num = $_SERVER['REMOTE_ADDR'];
$os= $_SERVER['HTTP_USER_AGENT'];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Users</title>
</head>
<body>	
	
<div class="row"> 
     <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
			<h3 class="card-title">Detail Data</h3>
			</div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
<table id="example4" width="100%" class="table table-sm table-striped table-bordered" style="font-size: 14px;">
 <thead class="btn-info">
  <tr>
    <th width="4%"><div align="center">No.</div></th>
    <th width="14%"><div align="center">Username</div></th>
    <th width="32%"><div align="center">Email</div></th>
    <th width="17%"><div align="center">Jabatan</div></th>
    <th width="17%"><div align="center">Level</div></th>
    <th width="8%"><div align="center">Status</div></th>
    <th width="8%"><div align="center">Action</div></th>
    </tr>
  </thead>
  <tbody>
  <?php
  $sql = sqlsrv_query($con,"SELECT * FROM db_qc.tbl_user_gkj ORDER BY username ASC");
  if ($sql === false) {
    die(print_r(sqlsrv_errors(), true));
  }
  
  $no=1;
  while($rowd = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
	?>
  <tr align="center">
    <td align="center"><?php echo $no;?></td>
    <td align="center"><?php echo $rowd['username'];?></td>
    <td align="center"><?php echo $rowd['email'];?></td>
    <td align="center"><?php echo $rowd['jabatan'];?></td>
    <td align="center"><a href="#" class="edit_lvlusr" id="<?php echo $rowd['id']; ?>" ><?php if($rowd['level']=="superadmin"){echo  "<span class='badge badge-info'>".$rowd['level']."</span>";}else if($rowd['level']=="admin"){echo  "<span class='badge badge-warning'>".$rowd['level']."</span>";}else if($rowd['level']=="biasa"){echo  "<span class='badge badge-success'>".$rowd['level']."</span>";}else if($rowd['level']=="umum"){echo  "<span class='badge badge-primary'>".$rowd['level']."</span>";}?></a></td>
    <td align="center"><a href="#" class="edit_stsusr" id="<?php echo $rowd['id']; ?>" ><?php if($rowd['status']=="Aktif"){echo "<span class='badge badge-success'>".$rowd['status']."</span>";}else{echo "<span class='badge badge-warning'>".$rowd['status']."</span>";}?></a></td>
    <td align="center"><a href="#" class="btn btn-xs btn-danger" onclick="confirm_delete('DelUser-<?php echo $rowd['id'] ?>');"><i class="fa fa-trash"></i> </a></td>
    </tr>
	  <?php $no++; } ?>
  </tbody>
</table>
			  </div>	
				
</div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
</div>	
<!-- Modal Popup untuk Edit--> 
<div id="StsUsrEdit" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<div id="LvlUsrEdit" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>	
<!-- Modal Popup untuk delete-->
            <div class="modal fade" id="delUsr" tabindex="-1">
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
                $('#delUsr').modal('show', {
                  backdrop: 'static'
                });
                document.getElementById('delete_link').setAttribute('href', delete_url);
              }
</script>
