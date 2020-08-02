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
    $sql = "SELECT * FROM bagian WHERE code_bagian = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
  }

  if (filter_has_var(INPUT_POST, 'submit')){
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	$code_bagian = mysqli_real_escape_string($conn, $_POST['code_bagian']);
    $nama_bagian = mysqli_real_escape_string($conn, $_POST['nama_bagian']);

    $sql = "UPDATE bagian SET code_bagian='$code_bagian', nama_bagian='$nama_bagian' WHERE code_bagian='$id'";
    if (mysqli_query($conn, $sql)) { 
      $msg = "<a href='bagian_edit.php' class='white-text'><i class='fas fa-arrow-circle-left'></i></a> Update Successfull";
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
      <h5><i class="fas fa-edit"></i> EDIT Bagian - Code : &nbsp <?php echo $row['code_bagian']; ?></h5>
      <div class="divider"></div><br><br>

      <!-- Locker edit form  -->
      <form class="col s12" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
      	<input type='hidden' name='id' value="<?php echo $row['code_bagian']; ?>">
        <div class="row">			 
		  
              <div class="input-field col s12">
                <input id="code_bagian" type="text" name="code_bagian"value="<?php echo $row['code_bagian']; ?>">
                <label for="code_bagian">Code</label>
              </div>
			  <div class="input-field col s12">
                <input id="nama_bagian" type="text" name="nama_bagian" value="<?php echo $row['nama_bagian']; ?>">
                <label for="nama_bagian">Nama</label>
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
