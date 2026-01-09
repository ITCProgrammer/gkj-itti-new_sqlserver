<?PHP
include_once 'controllers/usersClass.php';
// instance objek
$user     = new User();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Profile</title>
</head>
<?php
$Avatar	= isset($_POST['avatar']) ? $_POST['avatar'] : '';
	
?>	
<body>
	<section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="dist/img/<?php $user->ambilFoto($_SESSION[idGKJ]);?>"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center"><?php echo strtoupper($_SESSION['userGKJ']);?></h3>

                <p class="text-muted text-center"><?php echo $_SESSION['emailGKJ'];?></p>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">About Me</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> App</strong>

                <p class="text-muted">	
				<?php echo $_SERVER['HTTP_USER_AGENT'] ?>
                </p>

                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                <p class="text-muted"><?php
function getClientIP() {
 
    if (isset($_SERVER)) {
 
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
 
        if (isset($_SERVER["HTTP_CLIENT_IP"]))
            return $_SERVER["HTTP_CLIENT_IP"];
 
        return $_SERVER["REMOTE_ADDR"];
    }
 
    if (getenv('HTTP_X_FORWARDED_FOR'))
        return getenv('HTTP_X_FORWARDED_FOR');
 
    if (getenv('HTTP_CLIENT_IP'))
        return getenv('HTTP_CLIENT_IP');
 
    return getenv('REMOTE_ADDR');
}
echo "Your IP Address ".getClientIP()."";
?></p>

                <hr>

                </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
				  <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>	
                  <li class="nav-item"><a class="nav-link" href="#activity1" data-toggle="tab">Activity</a></li> 
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <!--<div class="tab-pane" id="activity">
                    <div class="timeline timeline-inverse">
			<?php 
			  $qry=mysql_query("SELECT tgl_update FROM tbl_timeline WHERE userid='$_SESSION[usid]' GROUP BY tgl_update ORDER BY id DESC");
			  while($r1=mysql_fetch_array($qry)){ 
						$bgcolor = ($col++ & 1) ? 'bg-purple' : 'bg-yellow';
						?>
						
                      
						<div class="time-label">
                        <span class="<?php echo $bgcolor;?>">
                          <?php echo date('d M Y', strtotime($r1[tgl_update]));?>
                        </span>
                      </div>
						<?php $qry1=mysql_query("SELECT * FROM tbl_timeline WHERE userid='$_SESSION[usid]' AND tgl_update='$r1[tgl_update]' ORDER BY id DESC");
			  while($r=mysql_fetch_array($qry1)){
						
				  if($r[aksi]=="tambah"){$icon="fa-database"; $warna="bg-green";}else if($r[aksi]=="ubah"){$icon="fa-edit"; $warna="bg-blue";}else if($r[aksi]=="hapus"){$icon="fa-trash"; $warna="bg-red";}else if($r[aksi]=="submit"){$icon="fa-user"; $warna="bg-info";}else if($r[aksi]=="login"){$icon="fa-user"; $warna="bg-success";}else if($r[aksi]=="logout"){$icon="fa-user"; $warna="bg-danger";}
						?>
                     
                      <div>
                        <i class="fa <?php echo $icon; ?> <?php echo $warna; ?>"></i>
 						
                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> <?php echo $r[time_update];?></span>

                          <h3 class="timeline-header"><span class="<?php if($r[aksi]=="login"){ echo "badge badge-info";}else if($r[aksi]=="logout"){ echo "badge badge-danger";} ?>"><?php echo $r[form_aksi];?></span></h3>

                          <div class="timeline-body ">
                          <?php echo $r[rinci_aksi];?>
						  <?php if($r[aksi]="login" or $r[aksi]=="logout"){
				  echo"<br>IP ADDRESS: <span class='badge badge-danger'>".$r[ipaddr]."</span><br>OS : <i>".$r[os]."</i>";
			  }?>	  
                          </div>
                          <div class="timeline-footer">
                            <i class="fa fa-user text-blue"> </i> <?php echo $r[userid];?>
                          </div>
                        </div>	
                      </div>
						<?php } ?>
                      
                      <?php } ?>
                      <div>
                        <i class="far fa-clock bg-gray"></i>
                      </div>
                    </div>
                  </div>-->
                  <!-- /.tab-pane -->               

                  <div class="tab-pane active" id="settings">
                    <form class="form-horizontal" action="UpdateProfile/" method="post" name="form1" enctype="multipart/form-data">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          	<input type="hidden" class="form-control" id="id" name="id" placeholder="" value="<?php echo $_SESSION['idGKJ'];?>">
							<input type="text" class="form-control" id="inputName" placeholder="Name" value="<?php echo $_SESSION['userGKJ'];?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" id="inputEmail" placeholder="Email" value="<?php echo $_SESSION['emailGKJ'];?>" name="email">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                          <input type="password" class="form-control" id="password" name="password">
                        </div>
                      </div>
					  <div class="form-group row">
                        <label for="re-password" class="col-sm-2 col-form-label">Re-Password</label>
                        <div class="col-sm-10">
                          <input type="password" class="form-control" id="re-password" name="re-password">
                        </div>
                      </div>
                      <div class="form-group row">
						  <label for="avatar" class="col-sm-2 col-form-label">Avatar</label>
                        <div class="col-sm-10">
						<div class="input-group">
						<input	class="form-control" name="avatar" id="avatar" value="<?php $user->ambilFoto($_SESSION[idGKJ]);?>" data-toggle="modal" data-target="#DataAvatar" readonly>
						<!--<select class="form-control select2" name="avatar" id="avatar">
						<option value=""></option>	
						<option value="avatar.png" <?php if($Avatar=="avatar.png"){echo "SELECTED"; } ?>>avatar</option>
						<option value="avatar2.png" <?php if($Avatar=="avatar2.png"){echo "SELECTED"; } ?>>avatar2</option>
						<option value="avatar3.png" <?php if($Avatar=="avatar3.png"){echo "SELECTED"; } ?>>avatar3</option>
						<option value="avatar04.png" <?php if($Avatar=="avatar04.png"){echo "SELECTED"; } ?>>avatar04</option>
						<option value="avatar5.png" <?php if($Avatar=="avatar5.png"){echo "SELECTED"; } ?>>avatar5</option>
						<option value="avatar6.png" <?php if($Avatar=="avatar6.png"){echo "SELECTED"; } ?>>avatar6</option>
						<option value="avatar7.png" <?php if($Avatar=="avatar7.png"){echo "SELECTED"; } ?>>avatar7</option>	
                  		</select> -->                       
                  		
               		  <span class="input-group-append">
                   	  <button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#DataAvatar">  <i class="fa fa-search"></i></button>
               		  </span>
                	</div>
                        </div>
                      </div> 
						<div class="form-group row">
						  <label for="avatar" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
						<div class="text-left">
                  <!--<img class="profile-user-img img-fluid img-circle"
                       src="dist/img/<?php if($Avatar==""){ $user->ambilFoto($_SESSION[idGKJ]); }else{echo $Avatar; } ?>"
                       alt="User profile picture">-->
                </div>
							</div>
						</div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
						  <!--<input type="submit" name="save" value="Submit" class="btn btn-danger float-right">-->
						  <button type="submit" name="cek1" value="Cek1" class="btn btn-danger float-right"><i class="fa fa-save"> </i> Submit </button>	
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
	<div class="modal fade" id="DataAvatar">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Data Avatar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                <span aria-hidden="true">&times;</span>
              </div>
              <div class="modal-body table-responsive">
               <table id="example7" class="table table-sm table-bordered table-hover table-striped" width="100%" style="font-size: 12px;">
						<thead>
							<tr>
								<th>Avatar</th>
								<th>Keterangan</th>
							</tr>
						</thead>
						<tbody>
							<tr class='pilih-no' data-id='avatar.png'>
								<td align="center"><img
                       src="dist/img/avatar.png"
                       alt="User profile picture" width="40" height="40"></td>
								<td align="center">avatar.png</td>
						  </tr>
							<tr class='pilih-no' data-id='avatar2.png'>
								<td align="center"><img width="40" height="40"
                       src="dist/img/avatar2.png"
                       alt="User profile picture"></td>
								<td align="center">avatar2.png</td>
						  </tr>
						  <tr class='pilih-no' data-id='avatar3.png'>
								<td align="center"><img width="40" height="40"
                       src="dist/img/avatar3.png"
                       alt="User profile picture"></td>
								<td align="center">avatar3.png</td>
						  </tr>
						  <tr class='pilih-no' data-id='avatar04.png'>
						    <td align="center"><img width="40" height="40"
                       src="dist/img/avatar04.png"
                       alt="User profile picture"></td>
						    <td align="center">avatar04.png</td>
					      </tr>
						  <tr class='pilih-no' data-id='avatar5.png'>
						    <td align="center"><img width="40" height="40"
                       src="dist/img/avatar5.png"
                       alt="User profile picture"></td>
						    <td align="center">avatar5.png</td>
					      </tr>
						  <tr class='pilih-no' data-id='avatar6.png'>
						    <td align="center"><img width="40" height="40"
                       src="dist/img/avatar6.png"
                       alt="User profile picture"></td>
						    <td align="center">avatar6.png</td>
					      </tr>
						  <tr class='pilih-no' data-id='avatar7.png'>
						    <td align="center"><img width="40" height="40"
                       src="dist/img/avatar7.png"
                       alt="User profile picture"></td>
						    <td align="center">avatar7.png</td>
					      </tr>
						  <tr class='pilih-no' data-id='avatar8.png'>
						    <td align="center"><img width="40" height="40"
                       src="dist/img/avatar8.png"
                       alt="User profile picture"></td>
						    <td align="center">avatar8.png</td>
					      </tr>
						  <tr class='pilih-no' data-id='avatar9.png'>
						    <td align="center"><img width="40" height="40"
                       src="dist/img/avatar9.png"
                       alt="User profile picture"></td>
						    <td align="center">avatar9.png</td>
					      </tr>
						  <tr class='pilih-no' data-id='avatar10.png'>
								<td align="center"><img width="40" height="40"
                       src="dist/img/avatar10.png"
                       alt="User profile picture"></td>
								<td align="center">avatar10.png</td>
						  </tr>	
						</tbody>
					</table>   		    
              </div>
              <div class="modal-footer justify-content-between">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  </div>	  
            </div>
            </div>
            <!-- /.modal-content -->
	</div>
</body>
</html>

