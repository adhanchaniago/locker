<?php
  session_start();
  require 'session.php';
  include 'navbar.php';
  require '../model/db.php';
	 include '../model/class_paging.php';
  $msg = $msgClass = '';

  // Form handling
  if (filter_has_var(INPUT_POST, 'submit')) {
    // Get form data
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
	$code_bagian = mysqli_real_escape_string($conn, $_POST['code_bagian']);
    // Check if the input is empty
    if (!empty($id) && !empty($status) && !empty($price)) {
      // pass
      $sql = "INSERT INTO locker (locker_id, locker_status, locker_price, code_bagian)
      VALUES ('$id', '$status', '$price', '$code_bagian')";

      if (mysqli_query($conn, $sql)) {
        // Success
        $msg = "Locker added";
        $msgClass = "green";
      } else {
        $msg = "Fail to add locker error: " . $sql . "<br>" . mysqli_error($conn);
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
    $sql = "DELETE FROM locker WHERE locker_id='$id'";

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
      <h5><i class="fas fa-cogs"></i> Locker setting</h5>
      <div class="divider"></div>
      <br>
      <div class="row">
        <div class="col s12 m6">
          <a href="#addlocker" class="btn green modal-trigger">Add New locker</a>
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
            <th>No Locker</th>
            <th>Status</th>
			<th>Bagian</th>
            <th>No ID 1</th>
			<th>Key 1</th>
			<th>No ID 2</th>
			<th>Key 2</th>
            <!-- <th>Owner</th> -->
            <th colspan="3">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $i = 1;
            $sql = "SELECT * FROM locker LIMIT $posisi,$batas";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)):
          ?>
            <tr>
              <td><?php echo $i; $i++ ?></td>
              <td><?php echo $row['locker_id']; ?></td>
              <td><?php echo $row['locker_status']; ?></td>
              <td><?php echo $row['code_bagian']; ?></td>
			  <td><?php echo $row['slot1']; ?></td>
			  <td><?php echo $row['key1']; ?></td>
			  <td><?php echo $row['slot2']; ?></td>
			  <td><?php echo $row['key2']; ?></td>
              <td>
                  <a href="locker_edit.php?id=<?php echo $row['locker_id']; ?>" class='blue-text tooltipped' data-position='right' data-tooltip='Edit'><i class='fas fa-pencil-alt'></i></a>
              </td>
			  <td>
                  <a href="locker_riwayat.php?id=<?php echo $row['locker_id']; ?>" class='green-text tooltipped' data-position='right' data-tooltip='Riwayat'><i class='fas fa-book'></i></a>
              </td>
              <td>
                <form method='POST' action='locker.php'>
                  <input type='hidden' name='id' value="<?php echo $row['locker_id']; ?>">
                  <button type='submit' onclick='return confirm(`Delete this locker "<?php echo $row['locker_id']; ?>" ?`);' name='delete' class='btn1 red-text tooltipped' data-position='top' data-tooltip='Delete'>
                    <i class='far fa-trash-alt'></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endwhile ?>
		  <tr>
							<td colspan="5">
							<?php
							$jmldata=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM record"));
							$jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
							$linkHalaman = $p->navHalaman($_GET['halaman'], $jmlhalaman); 
							echo "$linkHalaman";
							?></td>
						<td colspan="2">Jumlah Record <?php echo $jmldata; ?></td>
						</tr>
        </tbody>
      </table>
	</div>
      <!-- Modal -->
      <!-- Add locker modal -->
      <div id="addlocker" class="modal">
        <div class="modal-content">
          <h5>Add Locker</h5>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="row">
              <div class="input-field col s12">
                <input id="id" type="text" name="id">
                <label for="id">NO Locker</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <select name="status">
                  <option value="" disabled selected>Choose your option</option>
                  <option value="Available">Available</option>
                  <option value="Booked">Booked</option>
                  <option value="Damage">Damage</option>
                </select>
                <label>Status</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <input id="price" type="text" name="price">
                <label for="price">Price</label>
              </div>
            </div>
			<div class="row">
			<div class="input-field col s6 m6">
                <select name="code_bagian">
										<option>Pilih Bagian</option>
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
			  </div>
            <button type="submit" class="btn blue" name="submit">Add</button>
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
   include 'cari/cari_locker.php';
  include 'footer.php';
 
?>
	