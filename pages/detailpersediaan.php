<?php
include_once '../../config/conn.php';
include_once '../../controllers/permintaanClass.php';
$permintaan = new Permintaan();
$id=$_GET['id'];
$ket=$_GET['ket'];
foreach($permintaan->tampildetailkain($id,$ket) as $row1){}
?>
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">  
            <div class="modal-header">
              <h5 class="modal-title">Detail Persediaan Kain</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <h5>Total Roll : <?php echo $row1["totrol"];?> <br> Berat : <?php echo number_format($row1["totba"],'2','.',',');?> <br>  Panjang:<?php echo number_format($row1["totya"],'2','.',',');?></h5>
                <table id="example" class="table table-bordered table-hover table-striped" border="0" width="100%">
                    <thead>
                        <tr>
                            <th align="center" width="5%">No</th>
                            <th align="center" width="13%">No Roll</th>
                            <th align="center" width="18%">Berat (KG)</th>
                            <th align="center" width="12%">Panjang</th>
                            <th align="center" width="11%">Satuan</th>
                            <th align="center" width="9%">Grade</th>
                            <th align="center" width="17%">SN</th>
                            <th align="center" width="6%">Ket</th>
                            <th align="center" width="9%">Ket (Grade C)</th>
                            <th align="center" width="9%">Status</th>
                            <th align="center" width="9%">Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if (is_array($permintaan->datapersediaankain($id,$ket)) || is_object($permintaan->datapersediaankain($id,$ket))){
                        $n=1;
                        foreach($permintaan->datapersediaankain($id,$ket) as $row){
                    ?>
                        <tr>
                            <td align="center"><?php echo $n;?></td>
                            <td align="center"><?php echo $row['no_roll'];?></td>
                            <td align="right"><?php echo number_format($row['weight'],'2','.',',');?></td>
                            <td align="right"><?php echo number_format($row['yard_'],'2','.',',');?></td>
                            <td align="center"><?php echo $row['satuan'];?></td>
                            <td align="center"><?php echo $row['grade'];?></td>
                            <td align="right"><?php echo $row['SN'];?></td>
                            <td align="center"><?php if($row['sisa']=="SISA" OR $row['sisa']=="FKSI"){$sisa= "SISA";}else{$sisa=$row['sisa'];}echo $sisa;?></td>
                            <td bgcolor="<?php echo $rn;?>"><?php echo $row['ket_c'];?></td>
                            <?php  if($row['transtatus']=='0'){$kt="Sudah Keluar"; $rn="RED";}else{$kt="Ada"; $rn="";}?>
                            <td bgcolor="<?php echo $rn;?>"> <?php echo $kt;?></td>
                            <td align="center"><?php echo $row['lokasi'];?></td>
                        </tr>
                    <?php $n++; } }?>
                    </tbody>
                </table>
				
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              <!-- <button type="submit" class="btn btn-primary btn-sm">Save changes</button>-->
            </div>
			</form>	
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
               
          
<script>
$(function () { 	
$(".select2").select2({
      theme: 'bootstrap4',
	  placeholder: "Select",
      allowClear: true,	
    });
$("#example").DataTable({
	});
});
</script>