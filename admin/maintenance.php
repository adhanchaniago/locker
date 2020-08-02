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
	$('.collapsible').collapsible();
	
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
  include ("../model/class_paging.php");
  $msg = $msgClass = '';

  // Form handling
  if (filter_has_var(INPUT_POST, 'submit')) {
    // Get form data
    $locker_id = mysqli_real_escape_string($conn, $_POST['locker_id']);
    $permohonan = mysqli_real_escape_string($conn, $_POST['permohonan']);
	$alasan = mysqli_real_escape_string($conn, $_POST['alasan']);
    $tgl_pengajuan = mysqli_real_escape_string($conn, $_POST['tgl_pengajuan']);
	$nama = mysqli_real_escape_string($conn, $_POST['nama']);
	$nik = mysqli_real_escape_string($conn, $_POST['nik']);
	$bagian = mysqli_real_escape_string($conn, $_POST['bagian']);
	$jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);

    // Check if the input is empty
    if (!empty($locker_id) && !empty($nik) && !empty($nama)) {
      // pass
      $sql = "INSERT INTO maintenance_form (locker_id,permohonan,tgl_pengajuan,alasan,nik,nama,bagian,jabatan,maintenance_type)
      VALUES ('$locker_id', '$permohonan', '$tgl_pengajuan','$alasan', '$nik', '$nama', '$bagian', '$jabatan','Pengajuan Perbaikan')";

      if (mysqli_query($conn, $sql)) {
        // Success
        $msg = "Pengajuan perbaikan locker berhasil";
        $msgClass = "green";
      } else {
        $msg = "Pengajuan perbaikan locker Gagal / Erorr: " . $sql . "<br>" . mysqli_error($conn);
        $msgClass = "red";
      }
    } else {
      // failed
      $msg = "Please fill in all fields";
      $msgClass = "red";
    }
  }

  // Delete form handling
  if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = "DELETE FROM maintenance_form WHERE maintenance_id='$id'";

    if (mysqli_query($conn, $sql)) {
      $msg = "Delete Successfull";
      $msgClass = "green";
    } else {
      $msg = "Error deleting this locker";
      $msgClass = "red";
    }
  }
?>
<?php
	$p      = new Paging;
    $batas  = 20;
    $posisi = $p->cariPosisi($batas);
?>
	
<div class="wrapper">
  <section class="section">
    <div class="container2">
      <?php if($msg != ''): ?>
        <div id="msgBox" class="card-panel <?php echo $msgClass; ?>">
          <span class="white-text"><?php echo $msg; ?></span>
        </div>
      <?php endif ?>
      <h5><i class="fas fa-cogs"></i> Maintenance</h5>
      <div class="divider"></div>
      <br>
      <div class="row">
        <div class="col s12 m6">
          <br><a href="#addPengajuan" class="btn green modal-trigger">Pengajuan Perbaikan Locker</a> <br/>
		  <br><a href="maintenance_berkala.php?module=&halaman=1" class="btn blue modal-trigger">Maintenance Berkala</a> <br/>
		  
		  <!--  <br><a href="#addPerbaikan" class="btn yellow modal-trigger">Perbaikan Locker</a> <br/> -->
        </div>
		 		
        <div class="col s12 m6">
          <div class="input-field">
            <i class="material-icons prefix">search</i>
            <input type="text" id="txtcari">
            <label for="search">Search</label>
          </div>
        </div>
      </div>
	  
	  <div id="hasil"></div>
      <!-- Locker table list -->
	  <div id="tabel_awal">
      <table id="myTable" class="responsive-table highlight centered">
        <thead class="blue darken-2 white-text">
          <tr class="myHead">
            <th>#</th>
            <th>No Pengajuan</th>
            <th>Tgl_Pengajuan</th>
			      <th>Jenis_Pengajuan</th>
            <th>Locker ID</th>
      			<th>User_Pengajuan</th>
      			<th>Tgl_Pengechekan</th>
      			<th>Pengechekan by</th>
      			<th>Hasil_Pengechekan</th>
      			<th>Rekomendasi_team_pengechekan</th>
      			<th>Tgl_Perbaikan</th>
      			<th>Tgl_Selesai Perbaikan</th>
      			<th>Perbaikan by</th>
      			<th>Tindakan_penyelesaian</th>
            <!-- <th>Owner</th> -->
            <th colspan="2">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $i = 1;
            $sql = "SELECT * FROM maintenance_form LIMIT $posisi,$batas";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)):
          ?>
            <tr>
              <td><?php echo $i; $i++ ?></td>
              <td><?php echo $row['maintenance_id']; ?></td>              
              <td><?php echo tgl_indo($row['tgl_pengajuan']); ?></td>
      			  <td><?php echo $row['permohonan']; ?></td>
      			  <td><?php echo $row['locker_id']; ?></td>
      			  <td><?php echo $row['nik']; ?> - <?php echo $row['nama']; ?></td>
      			  <td><?php echo tgl_indo($row['tgl_chek']); ?></td>
      			  <td><?php echo $row['user_chek']; ?></td>
      			  <td><?php echo $row['hasil_chek']; ?></td>
      			  <td><?php echo $row['rekomendasi']; ?></td>
      			  <td><?php echo tgl_indo($row['tgl_perbaikan']); ?></td>
      			  <td><?php echo tgl_indo($row['tgl_selesai_perbaikan']); ?></td>
      			  <td><?php echo $row['user_perbaikan']; ?></td>
      			  <td><?php echo $row['tindakan']; ?></td>
              <td>
                  <a href="maintenance_edit.php?id=<?php echo $row['maintenance_id']; ?>" class='black-text tooltipped' data-position='right' data-tooltip='Edit'><i class='fas fa-code'></i>Edit</a> 
				  <a href="maintenance_pengechekan.php?id=<?php echo $row['maintenance_id']; ?>" class='blue-text tooltipped' data-position='right' data-tooltip='Pengechekan'><i class='fas fa-pencil-alt'></i>Check</a>
       
                  <a href="maintenance_perbaikan.php?id=<?php echo $row['maintenance_id']; ?>" class='green-text tooltipped' data-position='right' data-tooltip='Perbaikan'><i class='fas fa-arrows-alt'></i>Finish</a>
              </td>
              <td>
                <form method='POST' action='maintenance.php'>
                  <input type='hidden' name='id' value="<?php echo $row['maintenance_id']; ?>">
                  <button type='submit' onclick='return confirm(`Delete this locker "<?php echo $row['maintenance_id']; ?>" ?`);' name='delete' class='btn1 red-text tooltipped' data-position='top' data-tooltip='Delete'>
                    Del<i class='far fa-trash-alt'></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endwhile ?>
		  <tr>
							<td colspan="13">
							<?php
							$jmldata=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM maintenance_form"));
							$jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
							$linkHalaman = $p->navHalaman($_GET['halaman'], $jmlhalaman); 
							echo "$linkHalaman";
							?></td>
						<td colspan="3">Jumlah Record <?php echo $jmldata; ?></td>
						</tr>
        </tbody>
      </table>
	</div>
      <!-- Modal -->
      <!-- Add pengajuan perbaikan locker modal -->
      <div id="addPengajuan" class="modal">
        <div class="modal-content">
          <h5>FORM PENGAJUAN PERBAIKAN LOCKER</h5>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
           
            <div class="row">
              <div class="input-field col s6 m6">
                <select name="permohonan">
                  <option value="" disabled selected>Permohonan pengajuan</option>
                  <option value="Locker Rusak">Locker Rusak</option>
                  <option value="Kunci Hilang">Kunci Hilang</option>
                  <option value="Kunci Rusak">Kunci Rusak</option>
				  <option value="Lain-lain">Lain-lain</option>
                </select>
                <label>Permohonan pengajuan</label>
              </div>
            
			<div class="input-field col s6 m6">
                <select name="locker_id">
										<option value="" disabled selected>Pilih Locker</option>
										<?php
										$qlocker=mysqli_query($conn,"SELECT * FROM locker ");
										while($rlocker=mysqli_fetch_array($qlocker)){										
										?>
										<option value="<?php echo $rlocker['locker_id']; ?>"><?php echo $rlocker['locker_id']; ?></option>
										<?php
										}
										?>
									</select>
                <label>No Locker</label>
              </div>
			  </div>
			<div class="input-field col s6 m6">
				<input id="alasan" type="text" name="alasan">
				<label for="alasan">Alasan Perbaikan</label>
			</div>  
      <div class="input-field col s6 m6">
				<input id="tgl_pengajuan" type="text" class="datepicker" name="tgl_pengajuan">
				<label for="tgl_pengajuan">Tgl Pengajuan</label>
			</div>
			
			 <div class="row">
				<div class="input-field col s6 m6">
					<input id="nik" type="text" name="nik">	
					<label for="nik">NIK</label>
				 </div>
				<div class="input-field col s6 m6">
					<input id="nama" type="text" name="nama">	
					<label for="nama">NAMA</label>
				 </div>
				
			</div>
			 <div class="row">
				<div class="input-field col s6 m6">
                <select name="bagian">
										<option value="" disabled selected>Pilih Bagian</option>
										<?php
										$qlocker=mysqli_query($conn,"SELECT * FROM bagian ");
										while($rlocker=mysqli_fetch_array($qlocker)){										
										?>
										<option value="<?php echo $rlocker['code_bagian']; ?>"><?php echo $rlocker['nama_bagian']; ?></option>
										<?php
										}
										?>
									</select>
                <label>Bagian</label>
              </div>
			  
				 <div class="input-field col s6 m6">
					<input id="jabatan" type="text" name="jabatan">	
					<label for="jabatan">JABATAN</label>
				 </div>
			</div>
					
            <button type="submit" class="btn blue" name="submit">AJUKAN</button>
          </form>
        </div>
        <div class="modal-footer">
          <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
        </div>
      </div>
	  
    </div>
  </section>
</div>
	
<?php
  mysqli_close($conn);
   include 'cari/cari_maintenance.php';
  include 'footer.php';
?>
	