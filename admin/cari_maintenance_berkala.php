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
            <th>ID</th>
            <th>Tgl_Maintenance</th>
            <th>Jenis_Maintenance</th>
            <th>Maintenance_By</th>
            <th>Remarks</th>       
            <th colspan="2" class="center">Actions</th>
          </tr>
					</thead>
					<tbody>
				
					<?php					
					$query=mysqli_query($conn,"SELECT * FROM maintenance_berkala WHERE maintenance_berkala_id='$kodepasien' or user LIKE '%".$kodepasien."%' or jenis_maintenance LIKE '%".$kodepasien."%' ");
					$no=1;
					$num=mysqli_num_rows($query);
					if($num>=1){
					while($row=mysqli_fetch_array($query)){
					?>					
						<tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $row['maintenance_berkala_id']; ?></td>
            <td><?php echo tgl_indo($row['tgl_maintenance']); ?></td>           
            <td><?php echo $row['jenis_maintenance']; ?></td>
			<td><?php echo $row['user']; ?></td>
            <td><?php echo $row['remarks']; ?></td>
            <td>
                  <a href="maintenance_berkala_edit.php?id=<?php echo $row['maintenance_berkala_id']; ?>" class='blue-text tooltipped' data-position='right' data-tooltip='Edit'><i class='fas fa-pencil-alt'></i>Edit</a>
				  <a href="#addLocker" class='green-text modal-trigger' data-position='right' data-tooltip='Locker List'><i class='fas fa-print'></i>Locker List</a>
					
			  </td>
              <td>
                <form method='POST' action='maintenance_berkala.php'>
                  <input type='hidden' name='id' value="<?php echo $row['maintenance_berkala_id']; ?>">
                  <button type='submit' onclick='return confirm(`Delete this locker "<?php echo $row['maintenance_berkala_id']; ?>" ?`);' name='delete' class='btn1 red-text tooltipped' data-position='top' data-tooltip='Delete'>
                    Delete <i class='far fa-trash-alt'></i>
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