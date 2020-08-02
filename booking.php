<?php
  require 'session.php';
  include 'navbar.php';
  require_once 'model/db.php';

  $msg = $msgClass = '';
  
  // handle the get request base on user id
  if (isset($_REQUEST['id'])) {
    $id = mysqli_real_escape_string($conn, $_REQUEST['id']);
    $sql = "SELECT * FROM locker WHERE locker_id='$id'";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($result);

    $_SESSION['locker_id'] = $row['locker_id'];
	
  }

  // Process booked locker and insert into database
  if (filter_has_var(INPUT_POST, 'book')) {
    $start = mysqli_real_escape_string($conn, $_POST['start']);
    //$end = mysqli_real_escape_string($conn, $_POST['end']);
    
    $sid = mysqli_real_escape_string($conn, $_POST['studentid']);
    $lid = mysqli_real_escape_string($conn, $_POST['lockerid']);
    $code_bagian = mysqli_real_escape_string($conn, $_POST['code_bagian']);

    
          // check date if start date lower then end date output some error
          if ($start < 0) {
            $msg = "Please pick a correct date";
            $msgClass = "red";
          } else {
            $sql = "INSERT INTO record (record_start, student_id, locker_id)
            VALUES ('$start', '$sid', '$lid')";

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
  mysqli_close($conn);

?>
<section class="section">
  <div class="container">
    <h5><i class="fab fa-wpforms"></i> Booking information</h5>
    <div class="divider"></div><br>
    <?php if($msg != ''): ?>
      <div class="card-panel <?php echo $msgClass; ?>">
        <span class="white-text"><?php echo $msg; ?></span>
      </div>
    <?php endif ?>
    <form enctype="multipart/form-data" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="card-panel">
      <div class="row">
        <div class="input-field col s6 m6">
          <input readonly type="text" id="lockerid" name="lockerid" value="<?php echo $_SESSION['locker_id']; ?>" >
          <label for="id">Locker id</label>
        </div>
        
      </div>
	  <div class="row">
	  <div class="input-field col s6 m6">
          <input readonly type="text" id="studentid" name="studentid" value="<?php echo $_SESSION['s_id']; ?>">
          <label for="id">NIK</label>
        </div>
		
		<div class="input-field col s6 m6">
          <input readonly type="text" id="code_bagian" name="code_bagian" value="<?php echo $_SESSION['s_bagian']; ?>" >
          <label for="id">Bagian</label>
        </div>
	  </div>
      <div class="row">
        <div class="input-field col s6 m6">
          <input id="start" type="text" class="datepicker" name="start">
          <label for="start">Start date</label>
        </div>
        
      </div>
      
      <div class="center">
        <a href="index.php" class="btn btn-flat">Cancel</a>
        <button type="submit" name="book" class="btn green">Book Now</button>
      </div>
    </form>
  </div>
</section>
<?php
  include 'footer.php';
?>
