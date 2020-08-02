<?php
  session_start();
  require 'session.php';
  include 'navbar.php';
  require '../model/db.php';

  $msg = $msgClass = '';

  // handle the get request base on user id
  if (isset($_REQUEST['id'])) {
    $id = mysqli_real_escape_string($conn, $_REQUEST['id']);
    $sql = "SELECT * FROM locker WHERE locker_id='$id'";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($result);
  }

  if (filter_has_var(INPUT_POST, 'submit')) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
	$slot1 = mysqli_real_escape_string($conn, $_POST['slot1']);
	$key1 = mysqli_real_escape_string($conn, $_POST['key1']);
	$slot2 = mysqli_real_escape_string($conn, $_POST['slot2']);
	$key2 = mysqli_real_escape_string($conn, $_POST['key2']);
	$code_bagian = mysqli_real_escape_string($conn, $_POST['code_bagian']);
	
	if ($status=='Damage'){
		 $sql6 = "UPDATE locker SET locker_id='$id', locker_status='Damage', locker_price='$price',slot1='$slot1',key1='$key1',slot2='$slot2',key2='$key2',code_bagian='$code_bagian' WHERE locker_id='$id'";
		$result6 = mysqli_query($conn, $sql6);
	}else{	
    $sql = "UPDATE locker SET locker_id='$id', locker_status='$status',locker_price='$price',slot1='$slot1',key1='$key1',slot2='$slot2',key2='$key2',code_bagian='$code_bagian' WHERE locker_id='$id'";
	}
	
   if (mysqli_query($conn, $sql)) {
	   
	   $sql5= "Select * from locker where locker_id='$id'";
					$result5 = mysqli_query($conn, $sql5);
					  $row = mysqli_fetch_array($result5); 
					  $slot1=$row['slot1'];
					   $slot2=$row['slot2'];
					   $key1=$row['key1'];
					   $key2=$row['key2'];
					   $status=$row['locker_status'];
					   
					   if ($slot1 <> '' and $slot2 <> '' and $status <> 'Damage'){
							$sql6 = "UPDATE locker SET locker_status='Booked' WHERE locker_id='$id'";
							$result6 = mysqli_query($conn, $sql6);	
								
					   }elseif(($slot1 <> '' or $slot2 <> '') and $status <> 'Damage'){
						   $sql6 = "UPDATE locker SET locker_status='Available' WHERE locker_id='$id'";
						   $result6 = mysqli_query($conn, $sql6);
						   
					   }
					   
      $msg = "<a href='locker.php' class='white-text'><i class='fas fa-arrow-circle-left'></i></a> Update Successfull ";
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
      <h5><i class="fas fa-edit"></i> Edit locker <?php echo $row['locker_id']; ?></h5>
      <div class="divider"></div><br><br>

      <!-- Locker edit form  -->
      <form class="col s12" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
        <div class="row">
          <div class="input-field col s6 m6">
            <input type="text" id="id" name="id" value="<?php echo $row['locker_id']; ?>">
            <label for="id">Locker id</label>
          </div>
		  
		  <div class="input-field col s6 m6">
            <select name="status">
										<?php
										$Available="";$Booked="";$Damage="";$kosong="";
										if($device==""){$Available="";$Booked="";$Damage="";$kosong='selected="selected"';}
										else if($device=="Available"){$Available='selected="selected"';$Booked="";$Damage="";$kosong="";}
										else if($device=="Booked"){$Available="";$Booked=='selected="selected"';$Damage="";$kosong="";}
										else if($device=="Damage"){$Available="";$Booked="";$Damage=='selected="selected"';$kosong="";}
										
										?>
										<option value="<?php echo $row['locker_status']; ?>" selected><?php echo $row['locker_status'];?></option> 
                                        <option value="Available" <?php echo $Available; ?> >Available</option>
										<option value="Booked" <?php echo $Booked; ?> >Booked</option>
										<option value="Damage" <?php echo $Damage; ?> >Damage</option>
			
              
            </select>
            <label>Status</label>
          </div>
                  
        </div>
        <div class="row">
		<div class="input-field col s6 m6">
			
                <select name="code_bagian"  id="code_bagian">
										<option>Pilih Bagian</option>
										<?php
										
										
										$qlocker=mysqli_query($conn,"SELECT * FROM bagian ");
										while($rlocker=mysqli_fetch_array($qlocker)){
																						
										?>
										<?php if($rlocker['code_bagian'] ==$row['code_bagian'] ):?>
								
								<option value="<?php echo $rlocker['code_bagian'] ?>" selected><?php echo $rlocker['nama_bagian'] ?></option>
								<?php else:?>
								
                                <option value="<?php echo $rlocker['code_bagian'] ?>"><?php echo $rlocker['nama_bagian'] ?></option>
								<?php endif;?>
                                <?php
                                    }
                                ?>
									</select>
                <label>Bagian</label>
              </div>
          <div class="input-field col s6 m6">
            <input id="price" type="text" name="price" value="<?php echo $row['locker_price']; ?>">
            <label for="price">Price</label>
          </div>
        </div>
			  
		 <div class="row">
		 
          <div class="input-field col s6 m6">
            <input id="slot1" type="text" name="slot1" value="<?php echo $row['slot1']; ?>">
            <label for="slot1">Slot 1</label>
          </div>
		  <div class="input-field col s6 m6">
            <select name="key1">
			<?php
										$Yes="";$No="";$kosong="";
										if($device==""){$Yes="";$No="";$kosong='selected="selected"';}
										else if($device=="Yes"){$Yes='selected="selected"';$No="";$kosong="";}
										else if($device=="No"){$Yes="";$No=='selected="selected"';$kosong="";}
										
										
										?>
										<option value="<?php echo $row['key1']; ?>" selected><?php echo $row['key1'];?></option> 
                                        <option value="" <?php echo $kosong; ?> ></option>
										<option value="Yes" <?php echo $Yes; ?> >Yes</option>
										<option value="No" <?php echo $No; ?> >No</option>
										
              
              
            </select>
            <label>Key Slot 1</label>
          </div>
                  
        </div>
		<div class="row">
          <div class="input-field col s6 m6">
            <input id="slot2" type="text" name="slot2" value="<?php echo $row['slot2']; ?>">
            <label for="slot2">Slot 2</label>
          </div>
		  <div class="input-field col s6 m6">
            <select name="key2">
              <?php
										$Yes="";$No="";$kosong="";
										if($device==""){$Yes="";$No="";$kosong='selected="selected"';}
										else if($device=="Yes"){$Yes='selected="selected"';$No="";$kosong="";}
										else if($device=="No"){$Yes="";$No=='selected="selected"';$kosong="";}
										
										
										?>
										<option value="<?php echo $row['key2']; ?>" selected><?php echo $row['key2'];?></option> 
                                        <option value="" <?php echo $kosong; ?> ></option>
										<option value="Yes" <?php echo $Yes; ?> >Yes</option>
										<option value="No" <?php echo $No; ?> >No</option>
              
            </select>
            <label>Key Slot 2</label>
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
  include 'cari/cari_locker.php';
  include 'footer.php';
?>
