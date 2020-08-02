<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/materialize.min.js"></script>
<script src="../js/chart.min.js"></script>
<script src="../js/init.js"></script>
<script>
  $(document).ready(function() {
    $('.button-collapse').sideNav();
    var todaysDate = new Date();

      function convertDate(date) {
        var yyyy = date.getFullYear().toString();
        var mm = (date.getMonth()+1).toString();
        var dd  = date.getDate().toString();

        var mmChars = mm.split('');
        var ddChars = dd.split('');

        return yyyy + '-' + (mmChars[1]?mm:"0"+mmChars[0]) + '-' + (ddChars[1]?dd:"0"+ddChars[0]);
      }

      var dateToday = convertDate(todaysDate);
      // console.log(convertDate(todaysDate)); // Returns: 2015-08-25
      // Initialize modal
      $('.modal').modal();

    // Initialize select list
    $('select').material_select();

    
   $('.datepicker').pickadate({
	   
        format: 'yyyy-mm-dd',
        //min: dateToday // jika ingin di setting today ke atas
		    min:0
      });

    $('.tooltipped').tooltip();

    // Hide messagebox after 5 second
    setTimeout(function(){
      $('#msgBox').hide();
    }, 5000);

  });
</script>

<?php
  session_start();
  require 'session.php';
  include 'navbar.php';
  require '../model/db.php';
  include ("../model/fungsi_indotgl.php");
  include '../model/class_paging.php';
  $msg = $msgClass = '';

  // handle the get request base on user id
  if (isset($_REQUEST['id'])) {
    $id = mysqli_real_escape_string($conn, $_REQUEST['id']);
    $sql = "SELECT * FROM maintenance_form WHERE maintenance_id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
  }

  if (filter_has_var(INPUT_POST, 'submit')){
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	$locker_id = mysqli_real_escape_string($conn, $_POST['locker_id']);
    $permohonan = mysqli_real_escape_string($conn, $_POST['permohonan']);
	$alasan = mysqli_real_escape_string($conn, $_POST['alasan']);
    $tgl_pengajuan = mysqli_real_escape_string($conn, $_POST['tgl_pengajuan']);
	$nama = mysqli_real_escape_string($conn, $_POST['nama']);
	$nik = mysqli_real_escape_string($conn, $_POST['nik']);
	$bagian = mysqli_real_escape_string($conn, $_POST['bagian']);
	$jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
	
    $sql = "UPDATE maintenance_form SET locker_id=$locker_id, permohonan='$permohonan', tgl_pengajuan='$tgl_pengajuan',alasan='$alasan',nik='$nik',nama='$nama',bagian='$bagian',jabatan='$jabatan' WHERE maintenance_id='$id'";
    if (mysqli_query($conn, $sql)) { 
      $msg = "<a href='maintenance.php' class='white-text'><i class='fas fa-arrow-circle-left'></i></a> Update Successfull";
      $msgClass = "green";    
      
    } else {
      $msg = "Update error: " . $sql . "<br>" . mysqli_error($conn);
      $msgClass = "red";
    }
  }

?>


<div class="wrapper">
  <section class="section">
    <div class="container2">
      <?php if($msg != ''): ?>
        <div id="msgBox" class="card-panel <?php echo $msgClass; ?>">
          <span class="white-text"><?php echo $msg; ?></span>
        </div>
      <?php endif ?>
      <h5><i class="fas fa-edit"></i> EDIT PERBAIKAN LOCKER - NOMOR PENGAJUAN : &nbsp <?php echo $row['maintenance_id']; ?></h5>
      <div class="divider"></div><br><br>

      <!-- Locker edit form  -->
      <form class="col s12" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
      	<input type='hidden' name='id' value="<?php echo $row['maintenance_id']; ?>">
        <div class="row">
			 <div class="input-field col s6 m6">
            <select name="permohonan">
										<?php
										$LockerRusak="";$KunciRusak="";$KunciHilang="";$LainLain="";$Kosong="";
										if($device==""){$LockerRusak="";$KunciRusak="";$KunciHilang="";$LainLain="";$Kosong=='selected="selected"';}
										else if($device=="Locker Rusak"){$LockerRusak='selected="selected"';$KunciRusak="";$KunciHilang="";$LainLain="";$Kosong="";}
										else if($device=="Kunci Rusak"){$LockerRusak="";$KunciRusak=='selected="selected"';$KunciHilang="";$LainLain="";$Kosong="";}
										else if($device=="Kunci Hilang"){$LockerRusak="";$KunciRusak="";$KunciHilang=='selected="selected"';$LainLain="";$Kosong="";}
										else if($device=="Lain-lain"){$LockerRusak="";$KunciRusak="";$KunciHilang="";$LainLain=='selected="selected"';$Kosong="";}
										?>
										<option value="<?php echo $row['permohonan']; ?>" selected><?php echo $row['permohonan'];?></option> 
                                        <option value="Locker Rusak" <?php echo $LockerRusak; ?> >Locker Rusak</option>										
										<option value="Kunci Rusak" <?php echo $KunciRusak; ?> >Kunci Rusak</option>
										<option value="Kunci Hilang" <?php echo $KunciHilang; ?> >Kunci Hilang</option>
										<option value="Lain-lain" <?php echo $LainLain; ?> >Lain-lain</option>
              
            </select>
            <label>Permohonan</label>
          </div>
		  
		  <div class="input-field col s6 m6">
			
                <select name="locker_id"  id="locker_id">
										<option>Pilih Locker</option>
										<?php
										
										
										$qlocker=mysqli_query($conn,"SELECT * FROM locker ");
										while($rlocker=mysqli_fetch_array($qlocker)){
																						
										?>
										<?php if($rlocker['locker_id'] ==$row['locker_id'] ):?>
								
								<option value="<?php echo $rlocker['locker_id'] ?>" selected><?php echo $rlocker['locker_id'] ?></option>
								<?php else:?>
								
                                <option value="<?php echo $rlocker['locker_id'] ?>"><?php echo $rlocker['locker_id'] ?></option>
								<?php endif;?>
                                <?php
                                    }
                                ?>
									</select>
                <label>Locker</label>
              </div>
			  
				
		</div>
		<div class="row">
			<div class="input-field col s6 m6">
				<input id="alasan" type="text" name="alasan" value="<?php echo $row['alasan']; ?>">
				
				<label for="alasan">Alasan Perbaikan</label>
			</div> 
		
            <div class="input-field col s6 m6">
				<input id="tgl_pengajuan" type="text" class="datepicker" name="tgl_pengajuan" value="<?php echo $row['tgl_pengajuan']; ?>">
				<label for="tgl_pengajuan">Tgl Pengajuan</label>
			</div>
		</div>	
			 <div class="row">
				<div class="input-field col s6 m6">
					<input id="nik" type="text" name="nik" value="<?php echo $row['nik']; ?>" >	
					<label for="nik">NIK</label>
				 </div>
				<div class="input-field col s6 m6">
					<input id="nama" type="text" name="nama" value="<?php echo $row['nama']; ?>" >	
					<label for="nama">NAMA</label>
				 </div>
				
			</div>
			 <div class="row">
				<div class="input-field col s6 m6">			
                <select name="bagian"  id="bagian">
										<option>Pilih Bagian</option>
										<?php																				
										$qBagian=mysqli_query($conn,"SELECT * FROM bagian ");
										while($rBagian=mysqli_fetch_array($qBagian)){																						
										?>
										<?php if($rBagian['code_bagian'] ==$row['bagian'] ):?>
								
								<option value="<?php echo $rBagian['code_bagian'] ?>" selected><?php echo $rBagian['nama_bagian'] ?></option>
								<?php else:?>
								
                                <option value="<?php echo $rBagian['code_bagian'] ?>"><?php echo $rBagian['nama_bagian'] ?></option>
								<?php endif;?>
                                <?php
                                    }
                                ?>
									</select>
                <label>Bagian</label>
              </div>	
				
				 <div class="input-field col s6 m6">
					<input id="jabatan" type="text" name="jabatan" value="<?php echo $row['jabatan']; ?>">	
					<label for="jabatan">JABATAN</label>
				 </div>
			</div>
					
        <div class="row">
          <div class="center">
		              
              <button type="submit" class="waves-effect waves-light btn blue" name="submit">Update</button>           	
          </div>
        </div>
      </form>
    </div>
  </section>
</div>
<?php
 mysqli_close($conn);
   include 'cari/cari_maintenance.php';
  include 'footer.php';
 
?>
