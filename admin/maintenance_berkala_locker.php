
<script>
$('select').material_select();

$('select.initialized').prev('ul[id^="select-options-"]').children('li:first').on('click', function() {
  var selector = 'li.active';
  if ($(this).hasClass('active')) {
    selector = 'li:not(".active")';
  }

  $(this).siblings(selector).each(function() {
    $(this).click();
  });

  $('span', this).contents().last()[0].textContent = $(this).text().trim() === 'Check All' ? 'Uncheck All' : 'Check All';
  // Check values of selected options
  console.log($(this).parent().next().val());
});
</script>

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

    // Check if the input is empty
    if (!empty($id) && !empty($status) && !empty($price)) {
      // pass
      $sql = "INSERT INTO locker (locker_id, locker_status, locker_price)
      VALUES ('$id', '$status', '$price')";

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


      <br>
      <div class="row">
		
        <div class="input-field col s12">
                <select multiple searchable="search here..">
    <option value="0">Check All</option>
    <option value="1">Yellow</option>
    <option value="2">Green</option>
    <option value="3">Pink</option>
    <option value="4">Orange</option>
  </select>
  <label>Materialize Multiple Select</label>
              </div>
		
      </div>
	 
	 

   

	
<?php
  mysqli_close($conn);
   include 'cari/cari_locker.php';
  include 'footer.php';
 
?>
	