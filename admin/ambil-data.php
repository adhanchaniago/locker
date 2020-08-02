<?php
include '../model/db.php';
$bagian=$_POST['code_bagian'];

    $sql = "select * from locker where locker_status='Available' and code_bagian='$bagian'";
	
    $hasil = mysqli_query($conn, $sql);
    while ($data = mysqli_fetch_array($hasil)) {
        ?>
        <option value="<?php echo  $data['locker_id']; ?>"><?php echo $data['locker_id']; ?></option>
        <?php
    }

?>
