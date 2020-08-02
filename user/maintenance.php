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
        min: dateToday
      });

    $('.tooltipped').tooltip();

    // Hide messagebox after 5 second
    setTimeout(function(){
      $('#msgBox').hide();
    }, 5000);

    // Search
    $('#search').on('keyup', function() {
        var value = $(this).val();
        var patt = new RegExp(value, "i");
          $('#myTable').find('tr').each(function() {
            if (!($(this).find('td').text().search(patt) >= 0)) {
              $(this).not('.myHead').hide();
            }
            if (($(this).find('td').text().search(patt) >= 0)) {
              $(this).show();
            }
          });
        });
		
		

  });
</script>
<?php
  require 'session.php';
  include 'navbar.php';
  require '../model/db.php';
	 
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
      $sql = "INSERT INTO maintenance_form (locker_id,permohonan,tgl_pengajuan,alasan,nik,nama,bagian,jabatan)
      VALUES ('$locker_id', '$permohonan', '$tgl_pengajuan','$alasan', '$nik', '$nama', '$bagian', '$jabatan')";

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

<div class="wrapper">
  <section class="section">
    <div class="container2">
      <?php if($msg != ''): ?>
        <div id="msgBox" class="card-panel <?php echo $msgClass; ?>">
          <span class="white-text"><?php echo $msg; ?></span>
        </div>
      <?php endif ?>
      <h5><i class="fas fa-edit"></i> FORM PENGAJUAN PERBAIKAN LOCKER </h5>
      <div class="divider"></div><br><br>

      <!-- Locker edit form  -->
      <form class="col s12" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
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
			<?php
					$id=$_SESSION['s_id'];
					$sql = "SELECT * FROM `student` WHERE `student_id`='$id'";
					$result = mysqli_query($conn, $sql);

					$row = mysqli_fetch_array($result);
			?>
			 <div class="row">
				<div class="input-field col s6 m6">
					<input id="nik" type="text" name="nik" value="<?php echo $_SESSION['s_id']; ?>">	
					<label for="nik">NIK</label>
				 </div>
				<div class="input-field col s6 m6">
					<input id="nama" type="text" name="nama" value="<?php echo $_SESSION['s_name']; ?>">	
					<label for="nama">NAMA</label>
				 </div>
				
			</div>
			 <div class="row">	
				<div class="input-field col s6 m6">
					<input id="bagian" type="text" name="bagian" value="<?php echo $row['student_department']; ?>">	
					<label for="bagian">BAGIAN</label>
				 </div><div class="input-field col s6 m6">
					<input id="jabatan" type="text" name="jabatan">	
					<label for="jabatan">JABATAN</label>
				 </div>
			</div>
			<button type="submit" class="btn blue" name="submit">AJUKAN</button>
      </form>
    </div>
  </section>
</div>
	
<?php
  mysqli_close($conn);
 
  include 'footer.php';
 
?>
	