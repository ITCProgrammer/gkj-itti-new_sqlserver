<?php
ini_set("error_reporting", 1);
session_start();
include "../koneksi.php";
$act  = isset($_GET['act']) ? $_GET['act'] : '';
$id  = $_GET['id'];
$sql = mysqli_query($con,"SELECT * FROM `tbl_bon_permintaan` WHERE id='$id'");
while($r = mysqli_fetch_array($sql)){
?>
         
<div class="modal-dialog modal-md">
          <div class="modal-content">
            <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="EditNoKK" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Update No KK</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id" value="<?php echo $r['id'];?>">
                    <input type="hidden" id="bon" name="bon" value="<?php echo $r['refno'];?>">
                        <div class="form-group">
                            <label for="ket" class="col-md-2 control-label">No KK</label>
                                <div class="col-md-8">
                                    <input name="nokk" type="text" class="form-control" id="nokk" value="<?php echo $r['nokk'];?>" placeholder="" required>
                                </div>
                        </div>
		        </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Save Changes</button>
                </div>
            </form>
        </div>
            <!-- /.modal-content -->
</div>
          <!-- /.modal-dialog -->
          <?php } ?>