<?php
ini_set("error_reporting", 1);
session_start();
include "../koneksi.php";
$act  = isset($_GET['act']) ? $_GET['act'] : '';
$id        = $_GET['id'];
$sql = mysqli_query($con,"SELECT * FROM `tbl_bon_permintaan` WHERE id='$id'");
while($r = mysqli_fetch_array($sql)){
?>
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
<div class="modal-dialog modal-md">
          <div class="modal-content">
            <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="EditKet" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Update Keterangan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id" value="<?php echo $r['id'];?>">
                    <input type="hidden" id="bon" name="bon" value="<?php echo $r['refno'];?>">
                    <input type="hidden" id="nokk" name="nokk" value="<?php echo $r['nokk'];?>">
                        <div class="form-group">
                            <label for="ket" class="col-md-2 control-label">Keterangan</label>
                                <div class="col-md-12">
                                <textarea name="ket" class="form-control form-control-sm" required placeholder="Note.." onKeyDown="textCounter(this.form.ket,this.form.countDisplay);" onKeyUp="textCounter(this.form.ket,this.form.countDisplay);"><?php echo $r['ket'];?></textarea>
                                <input readonly class="noborder" type="text" name="countDisplay" size="2" maxlength="2" value="15"> Karakter Tersisa
                                    <!-- <input name="ket" type="text" style="text-transform:uppercase" class="form-control" id="ket" value="<?php echo $r['ket'];?>" placeholder="" required> -->
                                </div>
                        </div>
		        </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Save Changes</button>
                    <!--<?php if($_SESSION['lvl_id']!="ADMIN"){echo "disabled"; } ?>-->
                </div>
            </form>
        </div>
            <!-- /.modal-content -->
</div>
          <!-- /.modal-dialog -->
          <?php }?>