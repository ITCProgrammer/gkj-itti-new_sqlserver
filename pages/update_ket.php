<?php
ini_set("error_reporting", 1);
session_start();
include "../koneksi.php";

$act = isset($_GET['act']) ? $_GET['act'] : '';
$id  = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = sqlsrv_query(
    $con,
    "SELECT TOP 1 *
   FROM db_qc.tbl_bon_permintaan
   WHERE id = ?",
    [$id]
);

if ($sql === false) {
    die(print_r(sqlsrv_errors(), true));
}

while ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
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
            border-left: none;
            border-right: none;
            border-top: none;
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
                    <input type="hidden" id="id" name="id" value="<?php echo $r['id']; ?>">
                    <input type="hidden" id="bon" name="bon" value="<?php echo $r['refno']; ?>">
                    <input type="hidden" id="nokk" name="nokk" value="<?php echo $r['nokk']; ?>">

                    <div class="form-group">
                        <label for="ket" class="col-md-2 control-label">Keterangan</label>
                        <div class="col-md-12">
                            <textarea name="ket"
                                class="form-control form-control-sm"
                                required
                                placeholder="Note.."
                                onKeyDown="textCounter(this.form.ket,this.form.countDisplay);"
                                onKeyUp="textCounter(this.form.ket,this.form.countDisplay);"><?php echo $r['ket']; ?></textarea>

                            <input readonly class="noborder" type="text" name="countDisplay" size="2" maxlength="2" value="15"> Karakter Tersisa
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
<?php } ?>