<?php
   session_start();
  require 'session.php';
  include 'navbar.php';
  require '../model/db.php';
	
	 $msg = $msgClass = '';
	
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
          <input readonly type="text" id="lockerid" name="lockerid" value="-">
          <label for="id">Locker id</label>
        </div>
        <div class="input-field col s6 m6">
          <input readonly type="text" id="studentid" name="studentid" value="<?php echo $_POST['nik']; ?>">
          <label for="id">NIK</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6 m6">
          <input id="start" type="text" class="datepicker" name="start">
          <label for="start">Start date</label>
        </div>
        <div class="input-field col s6 m6">
          <input id="end" type="text" class="datepicker" name="end">
          <label for="end">End date</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6 m6">
          <input readonly type="text" id="price" name="price" >
          <label for="price">Locker Price (IDR) / day</label>
        </div>
        <div class="input-field file-field col s6 m6">
          <div class="btn blue">
            <span>Picture</span>
            <input type="file" name="item">
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text">
          </div>
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
  include 'footer.php';
?>
