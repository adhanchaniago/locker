
<?php
   session_start();
  require 'session.php';
  include 'navbar.php';
  require '../model/db.php';
	
	 $msg = $msgClass = '';

	 // Process booked locker and insert into database
  if (filter_has_var(INPUT_POST, 'book')) {
    $start = mysqli_real_escape_string($conn, $_POST['start']);
    //$end = mysqli_real_escape_string($conn, $_POST['end']);
    //$price = mysqli_real_escape_string($conn, $_POST['price']);
    $sid = mysqli_real_escape_string($conn, $_POST['studentid']);
    $lid = mysqli_real_escape_string($conn, $_POST['lockerid']);
    $keylocker = mysqli_real_escape_string($conn, $_POST['keylocker']);
	
  
          // check date if start date lower then end date output some error
          if ($start < 0) {
            $msg = "Please pick a correct date";
            $msgClass = "red";
          } else {
            
            $sql = "INSERT INTO record (record_start, student_id, locker_id,keylocker)
            VALUES ('$start', '$sid', '$lid','$keylocker')";

            $result = mysqli_multi_query($conn, $sql);
			
			$sql2 = "SELECT COUNT(locker_id) as total from record where locker_id='$lid' ";
              $result2 = mysqli_query($conn, $sql2);
              $row = mysqli_fetch_array($result2); 
              $total=$row['total'];
			  //echo $total;
			  //exit;
			  if ($total=2){
				  
			$sql3 = "UPDATE locker SET locker_status='Booked' WHERE locker_id='$lid' ";
			
			$result3 = mysqli_multi_query($conn, $sql3);
			  }
			//echo print_r($sql);
			//exit;
            if ($result) {
              do {
                // grab the result of the next query
                if (($result = mysqli_store_result($conn)) === false && mysqli_error($conn) !='') {
                  // echo "Query failed: " . mysqli_error($mysqli);
                }
              } while (mysqli_more_results($conn) && mysqli_next_result($conn));
              
              $msg = "<a href='index.php' class='white-text'><i class='fas fa-arrow-circle-left'></i></a> Booking success";
              $msgClass = "green";
            } else {
              // echo "First query failed..." . mysqli_error($conn);
            }
          }
      
     
    }
  
  
?>
</style>
<div class="wrapper">
  <section class="section">
    <div class="container2">
      <h3><i class="fas fa-tachometer-alt"></i> Locker Information</h3>	  
		
	 <div class="divider"></div><br>
		<?php if($msg != ''): ?>
      <div class="card-panel <?php echo $msgClass; ?>">
        <span class="white-text"><?php echo $msg; ?></span>
      </div>
    <?php endif ?>
    <form enctype="multipart/form-data" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="card-panel">
		<div class="row">
		<div class="input-field col s6 m6">
          <input type="text" id="studentid" name="studentid" >
          <label for="id">NIK</label>
        </div>
		<div class="input-field col s6 m6">
                <select name="code_bagian" id="code_bagian">
										<option>Pilih Bagian</option>
										<?php
									
										$qlocker=mysqli_query($conn,"SELECT * FROM bagian ");
										while($rlocker=mysqli_fetch_array($qlocker)){
																						
										?>
										<option value="<?php echo $rlocker['code_bagian']; ?>"><?php echo $rlocker['code_bagian']; ?></option>
										<?php
										}
										?>
									</select>
                <label>Bagian</label>
              </div>
		</div>	
		
	<div class="row">
	
	<div class="input-field col s6 m6">
			<label>Nomor Locker</label>
            <select name="lockerid" id="lockerid" required>
                <!-- Merk motor akan diload menggunakan ajax, dan ditampilkan disini -->
            </select>
			  
     </div>           
              
	</div>
	
      
      <div class="row">
        <div class="input-field col s6 m6">
          <input id="start" type="text" class="datepicker" name="start">
          <label for="start">Start date</label>
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
     
      <div class="center">
        <a href="index.php" class="btn btn-flat">Cancel</a>
        <button type="submit" name="book" class="btn green">Book now</button>
      </div>
    </form>
	
  </div>
</section>
      
<?php
 include 'cari/cari_bagian.php';
  //include 'footer.php' ;
?>
