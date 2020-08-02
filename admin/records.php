<?php
  session_start();
  require 'session.php';
  include 'navbar.php';
  require '../model/db.php';
  include '../model/class_paging.php';
  $msg = $msgClass = '';

  // Approve Booking
  if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
	//echo $id;
	//exit;
	$lid = mysqli_real_escape_string($conn, $_POST['locker_id']);
	$sid = mysqli_real_escape_string($conn, $_POST['studentid']);
	$keylocker = mysqli_real_escape_string($conn, $_POST['keylocker']);
    $adminId = $_SESSION['admin_id'];
    $sql = "UPDATE `record` SET record_status='approved', record_sub='active', record_approved_by='$adminId' WHERE record_id='$id'";
	 $result = mysqli_multi_query($conn, $sql);
			
			$sql2 = "SELECT COUNT(locker_id) as total from record where locker_id='$lid' ";
              $result2 = mysqli_query($conn, $sql2);
              $row = mysqli_fetch_array($result2); 
              $total=$row['total'];
			  //echo $total;
			  //exit;
			  if ($total=2){
			
			  $sql3 = "SELECT * from locker where locker_id='$lid' ";
              $result3 = mysqli_query($conn, $sql3);
              $row = mysqli_fetch_array($result3); 
              $slot1=$row['slot1'];
			   $slot2=$row['slot2'];
			   $key1=$row['key1'];
			   $key2=$row['key2'];
			   if ($slot1==""){
			  
				$sql4 = "UPDATE locker SET slot1='$sid',key1='$keylocker' WHERE locker_id='$lid' ";
					
			   $result4 = mysqli_multi_query($conn, $sql4);}
			   else{
				  $sql4 = "UPDATE locker SET slot2='$sid',key2='$keylocker'  WHERE locker_id='$lid' ";
				 
			   $result4 = mysqli_multi_query($conn, $sql4); }
			  }
			
			 $sql5="Select * from locker where locker_id='$lid'";
					$result5 = mysqli_query($conn, $sql5);
					  $row = mysqli_fetch_array($result5); 
					  $slot1=$row['slot1'];
					   $slot2=$row['slot2'];
					   $key1=$row['key1'];
					   $key2=$row['key2'];
					   if ($slot1 <> '' and $slot2 <> ''){
							$sql6 = "UPDATE locker SET locker_status='Booked' WHERE locker_id='$lid'";
							$result6 = mysqli_query($conn, $sql6);				
					   }else{
						   $sql6 = "UPDATE locker SET locker_status='Available' WHERE locker_id='$lid'";
						   $result6 = mysqli_query($conn, $sql6);
					   }	
	
    if (mysqli_query($conn, $sql)) {
      $msg = "Update Successfull";
      $msgClass = "green";
    } else {
      $msg = "Error updating this recrod";
      $msgClass = "red";
    }
  }

  // Delete form handling
  if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
	$lid=mysqli_real_escape_string($conn, $_POST['locker_id']);
    $sid = mysqli_real_escape_string($conn, $_POST['studentid']);
	$adminId = $_SESSION['admin_id'];
	
	$sql3 = "SELECT * from record where record_id='$id' ";
              $result3 = mysqli_query($conn, $sql3);
              $row = mysqli_fetch_array($result3); 
					$nik=$row['student_id'];
					$locker_id=$row['locker_id'];
					$keylocker=$row['keylocker'];
					$start=$row['record_start'];
					$status=$row['record_status'];
					
	$sql4 = "INSERT INTO tmp_record (nik, start, locker_id,keylocker,delete_by,delete_date,status)
            VALUES ('$nik', '$start', '$locker_id','$keylocker','$adminId',curdate(),'$status')";
            $result = mysqli_multi_query($conn, $sql4);				
	
    $sql = "DELETE FROM record WHERE record_id='$id'";
	$result = mysqli_multi_query($conn, $sql);
	 if (mysqli_query($conn, $sql)) {
		$sql3 = "SELECT * from locker where locker_id='$lid' ";
              $result3 = mysqli_query($conn, $sql3);
              $row = mysqli_fetch_array($result3); 
              $slot1=$row['slot1'];
			   $slot2=$row['slot2'];
			   $key1=$row['key1'];
			   $key2=$row['key2'];
			   
			   if ($slot1==$sid){
			  
					$sql4 = "UPDATE locker SET slot1='',key1='', locker_status='Available' WHERE locker_id='$lid' ";
			
					$result4 = mysqli_multi_query($conn, $sql4);
					
			   }else{
				  $sql4 = "UPDATE locker SET slot2='',key2='',locker_status='Available' WHERE locker_id='$lid' ";
			
					$result4 = mysqli_multi_query($conn, $sql4); 
			   } 
      $msg = "Delete Successfull";
      $msgClass = "green";
    } else {
      $msg = "Error deleting this recrod";
      $msgClass = "red";
    }
  }
// update kunci	Yes
if (isset($_POST['KeyYes'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
	
    $sql = "UPDATE record set keylocker='Yes' WHERE record_id='$id'";

    if (mysqli_query($conn, $sql)) {
      $msg = "Update Successfull";
	  
      $msgClass = "green";
    } else {
      $msg = "Error Update this record";
      $msgClass = "red";
    }
  }	
	// update kunci	No
if (isset($_POST['KeyNo'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
	
    $sql = "UPDATE record set keylocker='No' WHERE record_id='$id'";

    if (mysqli_query($conn, $sql)) {
      $msg = "Update Successfull";
	  
      $msgClass = "green";
    } else {
      $msg = "Error Update this record";
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
      <h5><i class="fas fa-book"></i> Records List</h5>
      <div class="divider"></div>
      <br>
	<div class="row">
        <div class="col s12 m6">
		
          <a href="booking.php" class="btn green modal-trigger">Add New record</a>
        </div>
      <!-- Search field -->
      <div class="row">
        <div class="col s12 m6"></div>
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
            <th>Id</th>
            <th>Start Date</th>
        <!-- <th>Start Date</th>
             <th>Price</th>-->
            <th>Item</th>
            <th>NIK</th>
            <th>Locker Id</th>
			<th>Key</th>
            <th class="center-align">Status</th>
            <th>Approved by</th>
            <th colspan="2" class="center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $i = 1;
            $sql = "SELECT * FROM record   ORDER BY record_status DESC LIMIT $posisi,$batas";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_array($result)):
          ?>
          <tr>
            <td><?php echo $i; $i++; ?></td>
            <td><?php echo $row['record_id']; ?></td>
            <td><?php echo $row['record_start']; ?></td>
            
            <!-- <td><?php echo "IDR"." ".$row['record_price']; ?></td>-->
            <td>
              <!-- Modal Structure -->
              <div id="<?php echo $row['record_id']; ?>" class="modal">
                <div class="modal-content">
                  <img class="responsive-img" src="<?php echo '../'.$row['record_item']; ?>" alt="test">
                </div>
              </div>
              <a class="modal-trigger"  href="<?php echo '#'.$row['record_id']; ?>">View</a>
            </td>
            <td><?php echo $row['student_id']; ?></td>
            <td><?php echo $row['locker_id']; ?></td>
			<td>
				
				<form method='POST' action='records.php'>
				<input type="hidden" name="id" value="<?php echo $row['record_id'];?>">
				<?php if ($row['keylocker'] == ''): ?>
				<button type='submit' name='KeyYes' class='green-text btn1 tooltipped' data-position='right' data-tooltip='Yes'>
                    <i class="fas fa-key"></i>
				<button type='submit' name='KeyNo' class='red-text btn1 tooltipped' data-position='right' data-tooltip='No'>
                <i class="fas fa-key"></i>	
				
                <?php else: ?>
                  <?php echo $row['keylocker']; ?>
                <?php endif ?>
				</form>
			</td>
            <td><?php echo $row['record_status']; ?></td>
            <td><?php echo $row['record_approved_by']; ?></td>
            <td>
              <form method='POST' action='records.php'>
			  <input type='hidden' name='locker_id' value="<?php echo $row['locker_id']; ?>">
                <input type='hidden' name='id' value='<?php echo $row['record_id']; ?>'>
				<input type='hidden' name='studentid' value='<?php echo $row['student_id']; ?>'>
				<input type='hidden' name='keylocker' value='<?php echo $row['keylocker']; ?>'>
                <?php if ($row['record_status'] == 'pending'): ?>
                  <button type='submit' name='update' class='green-text btn1 tooltipped' data-position='right' data-tooltip='Approve'>
                    <i class="fas fa-check"></i>
                  </button>
                <?php else: ?>
                  <button type='submit' name='update' class='blue-text btn1 tooltipped' data-position='right' data-tooltip='Approve' disabled>
                    <i class="fas fa-check"></i>
                  </button>
                <?php endif ?>
              </form>
            </td>
            <td>
              <form method='POST' action='records.php'>
                <input type='hidden' name='id' value='<?php echo $row['record_id']; ?>'>
				<input type='hidden' name='locker_id' value='<?php echo $row['locker_id']; ?>'>
				<input type='hidden' name='studentid' value='<?php echo $row['student_id']; ?>'>
                <button type='submit' onclick='return confirm(`Delete this record <?php echo $row['record_id']; ?> - ID Locker: <?php echo $row['locker_id']; ?> ?`);' name='delete' class='red-text btn1 tooltipped' data-position='top' data-tooltip='Delete'>
                  <i class='far fa-trash-alt'></i>
                </button>
              </form>
            </td>
			
          </tr>
          <?php endwhile ?>
		  <tr>
							<td colspan="9">
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
    </div>
  </section>
</div>
<!-- Modal -->
      
	  <!-- Add Keylocker Modal -->
	  <div id="addKey<?php echo $row['record_id'];?>" class="modal">
        <div class="modal-content">
          <h5>Status Pemegang Kuunci</h5>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
				<div class="row">
					<div class="input-field col s6 m6">
                <input id="id" type="text" name="id" value="<?php echo $id; ?>">
				
                <label for="id">Masukkan NIK</label>
              </div>
					<div class="input-field col s6 m6">
					<select name="keylocker">
					<option value="" disabled selected>Choose your option</option>
					<option value="Yes">Yes</option>
					<option value="No">No</option>
					</select>
                <label>Key</label>
              </div>
				</div>
				
            <button type="submit" class="btn blue" name="updateKey">Add</button>
          </form>
        </div>
        <div class="modal-footer">
          <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
        </div>
      </div>
	  <!-- End locker modal -->
	  
    </div>
<?php
  mysqli_close($conn);
  include 'cari/cari_record.php';
  include 'footer.php';
?>