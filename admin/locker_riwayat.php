<?php
 session_start();
  require 'session.php';
  include 'navbar.php';
  require '../model/db.php';
  include ("../model/fungsi_indotgl.php");
  // handle the get request base on user id
  if (isset($_REQUEST['id'])) {
    $id = mysqli_real_escape_string($conn, $_REQUEST['id']);
    $sql = "SELECT * FROM locker WHERE locker_id='$id'";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($result);
  }
?>
<div class="wrapper">
  <section class="section">
    <div class="container2">
      <div class="row">
	  <div class="col s12 m3">
          <div class="card">
            <div class="row">
              <div class="col s6 m6 grey-text">
                <?php
                  $sql = "SELECT locker_id as locker from locker where locker_id='$id' ";
                  $result = mysqli_query($conn, $sql);
                  $row = mysqli_fetch_array($result);
                  echo "<h5>".$row['locker']."</h5>";
                ?>
                <h5>Nomor</h5>
              </div>
              <div class="col s6 m6 icon yellow-text text-darken-2">
                <i class="fas fa-box"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col s12 m3">
          <div class="card">
            <div class="row">
              <div class="col s6 m6 grey-text">
                <?php
                  $sql = "SELECT locker_status as locker from locker ";
                  $result = mysqli_query($conn, $sql);
                  $row = mysqli_fetch_array($result);
                  echo "<h5>".$row['locker']."</h5>";
                ?>
                <h5>Status</h5>
              </div>
              <div class="col s6 m6 icon green-text">
                <i class="fas fa-check"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col s12 m3">
          <div class="card">
            <div class="row">
              <div class="col s6 m6 grey-text">
                <?php
                  $sql = "SELECT COUNT(locker_id) as locker_id from tmp_record WHERE locker_id='$id' ";
                  $result = mysqli_query($conn, $sql);
                  $row = mysqli_fetch_array($result);
                  echo "<h5>".$row['locker_id']."</h5>";
                ?>
                <h5>Riwayat</h5>
              </div>
              <div class="col s6 m6 icon blue-text">
                <i class="fas fa-info-circle"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col s12 m3">
          <div class="card">
            <div class="row">
              <div class="col s6 m6 grey-text">
                 <?php
                  $sql = "SELECT COUNT(locker_id) as locker_id from maintenance_form WHERE locker_id='$id'";
                  $result = mysqli_query($conn, $sql);
                  $row = mysqli_fetch_array($result);
                  echo "<h5>".$row['locker_id']."</h5>";
                ?>
                <h5>Perbaikan</h5>
              </div>
              <div class="col s6 m6 icon red-text">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
            </div>
          </div>
        </div>
        
      </div>

      <!-- Details information -->
      <ul class="collapsible">
        <li>
          <div class="collapsible-header active blue darken-2 white-text">
            <i class="fas fa-info-circle"></i>&nbsp Locker Status 
          </div>
          <div class="collapsible-body">
            <table class="responsive-table highlight centered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Start</th>
                  <th>NIK</th>
                  <th>Locker id</th>
				          <th>Key</th>
                  <th>Status</th>
                  
				  
                </tr>
              </thead>
              <tbody>
                <?php
                  $i = 1;
                  $sql = "SELECT * FROM `record` WHERE locker_id='$id'";
                  $result = mysqli_query($conn, $sql);
                  while ($row = mysqli_fetch_array($result)):
                ?>
                <tr>
                  <td><?php echo $i; $i++; ?></td>
                  <td><?php echo tgl_indo($row['record_start']); ?></td>
                  <td><?php echo $row['student_id']; ?></td>                  
                  <td><?php echo $row['locker_id']; ?></td>
				          <td><?php echo $row['keylocker']; ?></td>
                  <td><?php echo $row['record_status']; ?></td>
                  
				  
                </tr>
              <?php endwhile ?>
              </tbody>
            </table>
          </div>
        </li>
		<li>
          <div class="collapsible-header active blue darken-2 white-text">
            <i class="fas fa-wrench"></i>&nbsp Riwayat Perbaikan 
          </div>
          <div class="collapsible-body">
            <table class="responsive-table highlight centered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>No Pengajuan</th>
                  <th>Jenis_Pengajuan</th>
                  <th>Tgl_Pengajuan</th>
                  <th>Alasan_Pengajuan</th>
                  <th>Tgl_Chek</th>
        				  <th>Chek_By</th>
        				  <th>Hasil_Chek</th>
        				  <th>Rekomendasi</th>
        				  <th>Tgl_Perbaikan</th>
        				  <th>Tgl_Selesai</th>
        				  <th>Perbaikan_By</th>
        				  <th>Tindakan</th>
        				  <th>Biaya</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $i = 1;
                  $sql = "SELECT * FROM maintenance_form WHERE locker_id='$id'";
                  $result = mysqli_query($conn, $sql);
                  while ($row = mysqli_fetch_array($result)):
                ?>
                <tr>
                  <td><?php echo $i; $i++; ?></td>
                  <td><?php echo $row['maintenance_id']; ?></td>
                  <td><?php echo $row['permohonan']; ?></td>                  
                  <td><?php echo tgl_indo($row['tgl_pengajuan']); ?></td>
                  <td><?php echo $row['alasan']; ?></td>
                  <td><?php echo tgl_indo($row['tgl_chek']); ?></td>
        				  <td><?php echo $row['user_chek']; ?></td>
        				  <td><?php echo $row['hasil_chek']; ?></td>
        				  <td><?php echo $row['rekomendasi']; ?></td>
        				  <td><?php echo tgl_indo($row['tgl_perbaikan']); ?></td>
        				  <td><?php echo tgl_indo($row['tgl_selesai_perbaikan']); ?></td>
        				  <td><?php echo $row['user_perbaikan']; ?></td>
        				  <td><?php echo $row['tindakan']; ?></td>
        				  <td><?php echo $row['biaya_perbaikan']; ?></td>
                </tr>
              <?php endwhile ?>
              </tbody>
            </table>
          </div>
        </li>
        <li>
          <div class="collapsible-header active blue darken-2 white-text">
            <i class="fas fa-book"></i>&nbsp Riwayat Pemakaian
          </div>
          <div class="collapsible-body">
            <table class="responsive-table highlight centered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>NIK</th>
                  <th>Start</th>
                  <th>End</th>
                  <th>Delete By</th>
                  
                </tr>
              </thead>
              <tbody>
                <?php
                  $i = 1;
                  $sql = "SELECT * FROM tmp_record WHERE locker_id='$id'";
                  $result = mysqli_query($conn, $sql);
                  while ($row = mysqli_fetch_array($result)):
                ?>
                <tr>
                  <td><?php echo $i; $i++; ?></td>
                  <td><?php echo $row['nik']; ?></td>
                  <td><?php echo tgl_indo($row['start']); ?></td>
                  <td><?php echo tgl_indo($row['delete_date']); ?></td>
                  <td><?php echo $row['delete_by']; ?></td>
                  
                </tr>
              <?php endwhile ?>
              </tbody>
            </table>
          </div>
        </li>
      </ul>
    </div>
  </section>
</div>
<?php
  mysqli_close($conn);
  include 'cari/cari_locker.php';
  include 'footer.php';
?>
