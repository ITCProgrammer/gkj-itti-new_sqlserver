<?php
ini_set("error_reporting", 1);
session_start();
include "../koneksi.php";
$id=$_GET['id'];
$sql = mysqli_query($con,"SELECT * FROM tbl_user_gkj WHERE id='$id'");
while($r = mysqli_fetch_array($sql)){
?>
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">  
            <div class="modal-header">
              <h5 class="modal-title">Update Status</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
			  <input type="hidden" id="id" name="id" value="<?php echo $r['id'];?>">	
              <div class="form-group">
				<select class="form-control select2" name="sts" id="sts" required>	
					<option value=""></option>  
					<option value="Non-Aktif" <?php if($r['status']=="Non-Aktif"){ echo "SELECTED";} ?>>Non-Aktif</option>
					<option value="Aktif" <?php if($r['status']=="Aktif"){ echo "SELECTED";} ?>>Aktif</option>
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