<?php
include '../model/db.php';
include ("../model/fungsi_indotgl.php");
$kodepasien=$_POST['q'];

?>
<div class="hasil_cari">
<h5>Hasil Pencarian:<b><?php echo $kodepasien; ?></b></h5>
<table class="table table-striped">
					<thead>
					 <tr class="myHead">
            <th>#</th>
            <th>Id</th>
            <th>Start Date</th>
            
            <!-- <th>Price</th>-->
            <th>Item</th>
            <th>NIK</th>
            <th>No Locker</th>
			<th>Key</th>
            <th class="center-align">Status</th>
            <th>Approved by</th>
            <th colspan="2" class="center">Actions</th>
          </tr>
					</thead>
					<tbody>
				
					<?php					
					$query=mysqli_query($conn,"SELECT * FROM record WHERE student_id='$kodepasien' or locker_id LIKE '%".$kodepasien."%' ");
					$no=1;
					$num=mysqli_num_rows($query);
					if($num>=1){
					while($row=mysqli_fetch_array($query)){
					?>					
						<tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $row['record_id']; ?></td>
            <td><?php echo tgl_indo($row['record_start']); ?></td>
            
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
			<td><?php echo $row['keylocker']; ?></td>
            <td><?php echo $row['record_status']; ?></td>
            <td><?php echo $row['record_approved_by']; ?></td>
            <td>
              <form method='POST' action='records.php'>
                <input type='hidden' name='id' value='<?php echo $row['record_id']; ?>'>
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
					
					<?php
					$no++;
					}
					}
					else{
					?>
						<tr>
							<td colspan="11"><div class="alert alert-error">Data tidak ditemukan</div></td>
						</tr>
						<?php
					}
					?>
					
					</tbody>
				</table>
</div>