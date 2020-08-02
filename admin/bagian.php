<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/materialize.min.js"></script>
<script src="../js/chart.min.js"></script>
<script src="../js/init.js"></script>
<?php
  session_start();
  require 'session.php';
  include 'navbar.php';
  require '../model/db.php';

  $msg = $msgClass = '';

  // Form handling
  if (filter_has_var(INPUT_POST, 'submit')) {
    // Get form data
    
    $code_bagian = mysqli_real_escape_string($conn, $_POST['code_bagian']);
    $nama_bagian = mysqli_real_escape_string($conn, $_POST['nama_bagian']);

    // Check if the input is empty
    if (!empty($code_bagian) && !empty($nama_bagian)) {
      // pass
      $sql = "INSERT INTO bagian (code_bagian, nama_bagian)
      VALUES ('$code_bagian', '$nama_bagian')";

      if (mysqli_query($conn, $sql)) {
        // Success
        $msg = "Bagian added";
        $msgClass = "green";
      } else {
        $msg = "Fail to add bagian error: " . $sql . "<br>" . mysqli_error($conn);
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
    $code_bagian = mysqli_real_escape_string($conn, $_POST['code_bagian']);
    $sql = "DELETE FROM bagian WHERE code_bagian='$code_bagian'";

    if (mysqli_query($conn, $sql)) {
      $msg = "Delete Successfull";
      $msgClass = "green";
    } else {
      $msg = "Error deleting this locker";
      $msgClass = "red";
    }
  }
  
  // Alokasi form handling
  if (filter_has_var(INPUT_POST, 'alokasi')) {
    // Get form data
    
    $code_bagian = mysqli_real_escape_string($conn, $_POST['code_bagian']);
    $start = mysqli_real_escape_string($conn, $_POST['start']);
	$end = mysqli_real_escape_string($conn, $_POST['end']);

    // Check if the input is empty
    if (!empty($code_bagian) && !empty($start)&& !empty($end)) {
      // pass
	  
      $sql = ("UPDATE locker set code_bagian='$code_bagian' where locker_id between '$start' and '$end'");
     
      if (mysqli_query($conn, $sql)) {
        // Success
        $msg = "Alokasi berhasi ";
        $msgClass = "green";
      } else {
        $msg = "Fail to try alokasi error: " . $sql . "<br>" . mysqli_error($conn);
        $msgClass = "red";
      }
    } else {
      // failed
      $msg = "Please fill in all fields";
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
      <h5><i class="fas fa-cogs"></i> Bagian / Department</h5>
      <div class="divider"></div>
      <br>
      <div class="row">
        <div class="col s12 m6">
          <a href="#addBagian" class="btn green modal-trigger">Add New Bagian</a>
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
			<th>ID</th>
            <th>Code</th>
            <th>Bagian</th>
            <th>Alokasi Locker</th>
            <th colspan="3">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $i = 1;
            $sql = "SELECT * FROM bagian ";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)):
          ?>
            <tr>
              <td><?php echo $i; $i++ ?></td>
			  <td><?php echo $row['id_bagian']; ?></td>
              <td><?php echo $row['code_bagian']; ?></td>
              <td><?php echo $row['nama_bagian']; ?></td>
              <td><?php echo $row['start']." sd ".$row['end']; ?></td>
              <td>
                  <a href="bagian_edit.php?id=<?php echo $row['code_bagian']; ?>" class='blue-text tooltipped' data-position='right' data-tooltip='Edit'><i class='fas fa-pencil-alt'></i></a>
              </td>
			  <td>
                  <a href="bagian_alokasi.php?id=<?php echo $row['code_bagian']; ?>" class='green-text tooltipped' data-position='right' data-tooltip='Alokasi'><i class='fas fa-code'></i></a>
              </td>
              <td>
                <form method='POST' action='bagian.php'>
                  <input type='hidden' name='code_bagian' value="<?php echo $row['code_bagian']; ?>">
                  <button type='submit' onclick='return confirm(`Delete this locker "<?php echo $row['code_bagian']; ?>" ?`);' name='delete' class='btn1 red-text tooltipped' data-position='top' data-tooltip='Delete'>
                    <i class='far fa-trash-alt'></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endwhile ?>
		  
        </tbody>
      </table>
	</div>
      <!-- Modal -->
      <!-- Add bagian modal -->
      <div id="addBagian" class="modal">
        <div class="modal-content">
          <h5>Add Bagian</h5>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="row">
              <div class="input-field col s12">
                <input id="code_bagian" type="text" name="code_bagian">
                <label for="code_bagian">Code</label>
              </div>
			  <div class="input-field col s12">
                <input id="nama_bagian" type="text" name="nama_bagian">
                <label for="nama_bagian">Nama</label>
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
   include 'cari/cari_department.php';
  include 'footer.php';
 
?>
	