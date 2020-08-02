
<script>
	 $(function () {
		var check_all = $('#toggle_all_checkboxes');
	  check_all.on('click', function () {
	  	var ul = $(this).parents('.row').find('ul'),
	    		li = ul.find('li:not(".disabled")');
	        
	    $(li).each(function () {
				$(this).trigger('click');
	    });
	  });

	  $('select').material_select();
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

  // Form handling
  if (filter_has_var(INPUT_POST, 'submit')) {
    // Get form data
    
    $jenis_maintenance = mysqli_real_escape_string($conn, $_POST['jenis_maintenance']);
	$remarks = mysqli_real_escape_string($conn, $_POST['remarks']);
    $tgl_maintenance = mysqli_real_escape_string($conn, $_POST['tgl_maintenance']);
	$user = mysqli_real_escape_string($conn, $_POST['user']);
	

    // Check if the input is empty
    if (!empty($jenis_maintenance) && !empty($tgl_maintenance)) {
      // pass
      $sql = "INSERT INTO maintenance_berkala (jenis_maintenance,tgl_maintenance,remarks,user,maintenance_type)
      VALUES ('$jenis_maintenance', '$tgl_maintenance','$remarks', '$user','Maintenance Berkala')";

      if (mysqli_query($conn, $sql)) {
        // Success
        $msg = "Maintenance berkala berhasil di simpan";
        $msgClass = "green";
      } else {
        $msg = "Maintenance berkala Gagal / Erorr: " . $sql . "<br>" . mysqli_error($conn);
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
    $sql = "DELETE FROM maintenance_berkala WHERE maintenance_berkala_id='$id'";

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
      <h5><i class="fas fa-cogs"></i> Maintenance Berkala</h5>
      <div class="divider"></div>
      <br>
      <div class="row">
        <div class="col s12 m6">
          <br><a href="#addPengajuan" class="btn green modal-trigger">Add Maintenance Berkala</a> <br/>	  
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
            <th>Maintenance ID</th>
            <th>Tgl_Maintenance</th>
			<th>Jenis_Maintenance</th>
			<th>Maintenance by</th>
			<th>Remarks</th>
			
            <th colspan="2">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $i = 1;
            $sql = "SELECT * FROM maintenance_berkala LIMIT $posisi,$batas";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)):
          ?>
            <tr>
              <td><?php echo $i; $i++ ?></td>
              <td><?php echo $row['maintenance_berkala_id']; ?></td>
              <td><?php echo tgl_indo($row['tgl_maintenance']); ?></td>
			  <td><?php echo $row['jenis_maintenance']; ?></td>
			  <td><?php echo $row['user']; ?></td>
			  <td><?php echo $row['remarks']; ?></td>
			  
              <td>
                  <a href="maintenance_berkala_edit.php?id=<?php echo $row['maintenance_berkala_id']; ?>" class='blue-text tooltipped' data-position='right' data-tooltip='Edit'><i class='fas fa-pencil-alt'></i>Edit</a>
				  <a href="#addLocker" class='green-text modal-trigger' data-position='right' data-tooltip='Locker List'><i class='fas fa-print'></i>Locker List</a>
					
			  </td>
              <td>
                <form method='POST' action='maintenance_berkala.php'>
                  <input type='hidden' name='id' value="<?php echo $row['maintenance_berkala_id']; ?>">
                  <button type='submit' onclick='return confirm(`Delete this locker "<?php echo $row['maintenance_berkala_id']; ?>" ?`);' name='delete' class='btn1 red-text tooltipped' data-position='top' data-tooltip='Delete'>
                    Delete <i class='far fa-trash-alt'></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endwhile ?>
		  <tr>
							<td colspan="5">
							<?php
							$jmldata=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM maintenance_berkala"));
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
          <h5>FORM MAINTENANCE BERKALA</h5>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
           
            <div class="row">
              <div class="input-field col s6 m6">
                <select name="jenis_maintenance">
                  <option value="" disabled selected>Jenis Maintenance</option>
                  <option value="Pembersihan">Pembersihan</option>
                  <option value="Pengecatan">Pengecatan</option>
                  <option value="Pergantian Suku Cadang">Pergantian Suku Cadang</option>
				  <option value="Lain-lain">Lain-lain</option>
                </select>
                <label>Jenis Maintenance</label>
              </div>

			  </div>
			<div class="row">  
			<div class="input-field col s6 m6">
				<input id="tgl_maintenance" type="text" class="datepicker" name="tgl_maintenance">
				<label for="tgl_maintenance">Tgl maintenance</label>
			</div>
			<div class="input-field col s6 m6">
					<input id="user" type="text" name="user">	
					<label for="user">Maintenance By</label>
			</div>
			
			</div>
			<div class="input-field col s6 m6">
				<input id="remarks" type="text" name="remarks">
				<label for="remarks">Remarks</label>
			</div>  
            
					
            <button type="submit" class="btn blue" name="submit">SAVE</button>
          </form>
        </div>
        <div class="modal-footer">
          <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
        </div>
      </div>
	  <!-- Modal Add Locker-->
      <!-- Add pengajuan perbaikan locker modal -->
      <div id="addLocker" class="modal">
        <div class="modal-content">
          <h5>List Locker</h5>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
           
           <div class="row">
		<div class="input-field col s12">
			<p>
				<input name="toggle_all_checkboxes" type="checkbox" id="toggle_all_checkboxes"  />
				<label for="toggle_all_checkboxes">Toogle all checkboxes</label>
			</p>
		</div>
        <div class="input-field col s12">
                <select multiple >
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
                
              </div>
		
      </div>  
            
					
            <button type="submit" class="btn blue" name="submit">SAVE</button>
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
  include 'cari/cari_maintenance_berkala.php';
  include 'footer.php';
 
?>
	