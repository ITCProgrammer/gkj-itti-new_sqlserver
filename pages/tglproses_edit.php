<?php
ini_set("error_reporting", 1);
session_start();
include "../koneksi.php";
$refno=$_GET['refno'];
$sql = mysqli_query($con,"SELECT * FROM tbl_bon_permintaan WHERE refno='$refno' ORDER BY id LIMIT 1");
while($r = mysqli_fetch_array($sql)){
?>
        <div class="modal-dialog">
          <div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="EditTglProses/" enctype="multipart/form-data">  
            <div class="modal-header">
              <h5 class="modal-title">Edit Tgl Proses</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
			  <input type="hidden" id="refno" name="refno" value="<?php echo $r['refno'];?>">	
             <div class="form-group">
				 <label for="tgl_proses" class="col-md-4 control-label">Tgl Proses</label>
                    <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                        <input type="text" name="tgl_proses" value="<?php echo $r['tgl_proses'];?>" class="form-control datetimepicker-input" data-target="#reservationdatetime"/>
                        <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
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
               
          <?php }?>
<script>
$(function () { 	
//Date and time picker
    $('#reservationdatetime').datetimepicker({
		format: 'YYYY-MM-DD HH:mm:ss',
		icons: { time: 'far fa-clock' } 
	});
});
</script>