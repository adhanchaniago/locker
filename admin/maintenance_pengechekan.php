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
    $tgl_chek = mysqli_real_escape_string($conn, $_POST['tgl_chek']);	
    $user_chek = mysqli_real_escape_string($conn, $_POST['user_chek']);
    $hasil_chek = mysqli_real_escape_string($conn, $_POST['hasil_chek']);
	$rekomendasi = mysqli_real_escape_string($conn, $_POST['rekomendasi']);
	
    $sql = "UPDATE maintenance_form SET tgl_chek='$tgl_chek', user_chek='$user_chek', hasil_chek='$hasil_chek',rekomendasi='$rekomendasi' WHERE maintenance_id='$id'";
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
      <h5><i class="fas fa-edit"></i> FORM PENGECHEKAN PERBAIKAN LOCKER NOMOR PENGAJUAN : &nbsp <?php echo $row['maintenance_id']; ?></h5>
      <div class="divider"></div><br><br>

      <!-- Locker edit form  -->
      <form class="col s12" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
      	<input type='hidden' name='id' value="<?php echo $row['maintenance_id']; ?>">
        <div class="row">
              <div class="input-field col s6 m6">
                <input id="permohonan" type="text" name="permohonan" value="<?php echo $row['permohonan']; ?>" disabled>
				<label for="permohonan">Permohonan Perbaikan</label>
              </div>
            
				<div class="input-field col s6 m6">
                <input id="locker_id" type="text" name="locker_id" value="<?php echo $row['locker_id']; ?>" disabled>
				<label for="locker_id">No Locker</label>
              </div>
		</div>
		<div class="row">
			<div class="input-field col s6 m6">
				<input id="alasan" type="text" name="alasan" value="<?php echo $row['alasan']; ?>" disabled>
				
				<label for="alasan">Alasan Perbaikan</label>
			</div> 
		
            <div class="input-field col s6 m6">
				<input id="tgl_pengajuan" type="text" class="datepicker" name="tgl_pengajuan" value="<?php echo tgl_indo($row['tgl_pengajuan']); ?>" disabled>
				<label for="tgl_pengajuan">Tgl Pengajuan</label>
			</div>
		</div>	
			 <div class="row">
				<div class="input-field col s6 m6">
					<input id="nik" type="text" name="nik" value="<?php echo $row['nik']; ?>" disabled>	
					<label for="nik">NIK</label>
				 </div>
				<div class="input-field col s6 m6">
					<input id="nama" type="text" name="nama" value="<?php echo $row['nama']; ?>" disabled>	
					<label for="nama">NAMA</label>
				 </div>
				
			</div>
			 <div class="row">	
				<div class="input-field col s6 m6">
					<input id="bagian" type="text" name="bagian" value="<?php echo $row['bagian']; ?>" disabled>	
					<label for="bagian">BAGIAN</label>
				 </div><div class="input-field col s6 m6">
					<input id="jabatan" type="text" name="jabatan" value="<?php echo $row['jabatan']; ?>" disabled>	
					<label for="jabatan">JABATAN</label>
				 </div>
			</div>
			<h4>Details Pengechekan :</h4>

			 <div class="row">
				<div class="input-field col s6 m6">
					<input id="tgl_chek" type="text" class="datepicker" name="tgl_chek" value="<?php echo $row['tgl_chek']; ?>">
					<label for="tgl_chek">Tgl Pengechekan</label>
					
				</div>  
				<div class="input-field col s6 m6">
					<input id="user_chek" type="text" name="user_chek" value="<?php echo $row['user_chek']; ?>">
					<label for="user_chek">Chek By</label>
				</div>			
            </div>
			<div class="row">
				<div class="input-field col s6 m6">
					<input id="hasil_chek" type="text" name="hasil_chek" value="<?php echo $row['hasil_chek']; ?>">
					<label for="hasil_chek">Hasil Pengechekan</label>
					
				</div>  
				<div class="input-field col s6 m6">
					<input id="rekomendasi" type="text" name="rekomendasi" value="<?php echo $row['rekomendasi']; ?>">
					<label for="rekomendasi">Rekomendasi</label>
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
