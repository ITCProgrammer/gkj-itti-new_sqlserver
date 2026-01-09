<?php
ini_set("error_reporting", 1);
session_start();
include "../koneksi.php";
$act  = isset($_GET['act']) ? $_GET['act'] : '';
$id=$_GET['id'];
$sql = mysqli_query($con,"SELECT * FROM tbl_user_gkj WHERE id='$id'");
while($r = mysqli_fetch_array($sql)){
?>
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">  
            <div class="modal-header">
              <h5 class="modal-title">Update Level</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
			  <input type="hidden" id="id" name="id" value="<?php echo $r['id'];?>">	
              <div class="form-group">
				<select class="form-control select2" name="lvl" id="lvl" required>	
					<option value=""></option>  
					<option value="superadmin" <?php if($r['level']=="superadmin"){ echo "SELECTED";} ?>>superadmin</option>
					<option value="admin" <?php if($r['level']=="admin"){ echo "SELECTED";} ?>>admin</option>
					<option value="biasa" <?php if($r['level']=="biasa"){ echo "SELECTED";} ?>>biasa</option>
					<option value="umum" <?php if($r['level']=="umum"){ echo "SELECTED";} ?>>umum</option>
          		</select>	  
              </div>
				
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary btn-sm">Save changes</button>			  	
            </div>
			</form>	
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
               
          <?php } ?>
<script>
$(function () { 	
$(".select2").select2({
      theme: 'bootstrap4',
	  placeholder: "Select",
      allowClear: true,	
    });
});
</script>