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
    $sql = "SELECT * FROM maintenance_berkala WHERE maintenance_berkala_id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
  }

  if (filter_has_var(INPUT_POST, 'submit')){
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	$jenis_maintenance = mysqli_real_escape_string($conn, $_POST['jenis_maintenance']);
	$remarks = mysqli_real_escape_string($conn, $_POST['remarks']);
    $tgl_maintenance = mysqli_real_escape_string($conn, $_POST['tgl_maintenance']);
	$user = mysqli_real_escape_string($conn, $_POST['user']);

    $sql = "UPDATE maintenance_berkala SET jenis_maintenance='$jenis_maintenance', tgl_maintenance='$tgl_maintenance', user='$user',remarks='$remarks' WHERE maintenance_berkala_id='$id'";
    if (mysqli_query($conn, $sql)) { 
      $msg = "<a href='maintenance_berkala.php' class='white-text'><i class='fas fa-arrow-circle-left'></i></a> Update Successfull";
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
      <h5><i class="fas fa-edit"></i> EDIT MAINTENANCE BERKALA - NOMOR MAINTENANCE : &nbsp <?php echo $row['maintenance_berkala_id']; ?></h5>
      <div class="divider"></div><br><br>

      <!-- Locker edit form  -->
      <form class="col s12" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
      	<input type='hidden' name='id' value="<?php echo $row['maintenance_berkala_id']; ?>">
        <div class="row">
			 <div class="input-field col s6 m6">
            <select name="jenis_maintenance">
										<?php
										$Pembersihan="";$Pengecatan="";$SparePart="";$LainLain="";$Kosong="";
										if($device==""){$Pembersihan="";$Pengecatan="";$SparePart="";$LainLain="";$Kosong=='selected="selected"';}
										else if($device=="Pembersihan"){$Pembersihan='selected="selected"';$Pengecatan="";$SparePart="";$LainLain="";$Kosong="";}
										else if($device=="Pengecatan"){$Pembersihan="";$Pengecatan=='selected="selected"';$SparePart="";$LainLain="";$Kosong="";}
										else if($device=="Pergantian Suku Cadang"){$Pembersihan="";$Pengecatan="";$SparePart=='selected="selected"';$LainLain="";$Kosong="";}
										else if($device=="Lain-lain"){$Pembersihan="";$Pengecatan="";$SparePart="";$LainLain=='selected="selected"';$Kosong="";}
										?>
										<option value="<?php echo $row['jenis_maintenance']; ?>" selected><?php echo $row['jenis_maintenance'];?></option> 
                                        <option value="Pembersihan" <?php echo $Pembersihan; ?> >Pembersihan</option>										
										<option value="Pengecatan" <?php echo $Pengecatan; ?> >Pengecatan</option>
										<option value="Pergantian Suku Cadang" <?php echo $SparePart; ?> >Pergantian Suku Cadang</option>
										<option value="Lain-lain" <?php echo $LainLain; ?> >Lain-lain</option>
              
            </select>
            <label>Jenis Maintenance</label>
          </div>
		  
			
		</div>
		<div class="row">
			  
			<div class="input-field col s6 m6">
				<input id="tgl_maintenance" type="text" class="datepicker" name="tgl_maintenance" value="<?php echo $row['tgl_maintenance']; ?>">
				<label for="tgl_maintenance">Tgl maintenance</label>
			</div>
			<div class="input-field col s6 m6">
					<input id="user" type="text" name="user" value="<?php echo $row['user']; ?>">	
					<label for="user">Maintenance By</label>
			</div>
			
		</div>	
		<div class="row">
			<div class="input-field col s6 m6">
				<input id="remarks" type="text" name="remarks" value="<?php echo $row['remarks']; ?>">
				<label for="remarks">Remarks</label>
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
   include 'cari/cari_maintenance_berkala.php';
  include 'footer.php';
 
?>
