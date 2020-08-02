<?php
include '../model/db.php';

$kodepasien=$_POST['q'];

?>
<div class="hasil_cari">
<h5>Hasil Pencarian:<b><?php echo $kodepasien; ?></b></h5>
<table class="table table-striped">
					<thead>
					 <tr class="myHead">
            <th>#</th>
            <th>No Locker</th>
            <th>Status</th>
			<th>Bagian</th>
            <th>No ID 1</th>
			<th>Key 1</th>
			<th>No ID 2</th>
			<th>Key 2</th>
            <!-- <th>Owner</th> -->
            <th colspan="3">Action</th>
          </tr>
					</thead>
					<tbody>
				
					<?php					
					$query=mysqli_query($conn,"SELECT * FROM locker WHERE locker_id LIKE '%".$kodepasien."%' or locker_status LIKE '%".$kodepasien."%' or slot1 LIKE '%".$kodepasien."%' or slot2 LIKE '%".$kodepasien."%' or code_bagian LIKE '%".$kodepasien."%'   ");
					$no=1;
					$num=mysqli_num_rows($query);
					if($num>=1){
					while($row=mysqli_fetch_array($query)){
					?>					
						<tr>
              <td><?php echo $no; ?></td>
              <td><?php echo $row['locker_id']; ?></td>
              <td><?php echo $row['locker_status']; ?></td>
              <td><?php echo $row['code_bagian']; ?></td>
			  <td><?php echo $row['slot1']; ?></td>
			  <td><?php echo $row['key1']; ?></td>
			  <td><?php echo $row['slot2']; ?></td>
			  <td><?php echo $row['key2']; ?></td>
              <td>
                  <a href="locker_edit.php?id=<?php echo $row['locker_id']; ?>" class='blue-text tooltipped' data-position='right' data-tooltip='Edit'><i class='fas fa-pencil-alt'></i></a>
              </td>
			  <td>
                  <a href="locker_riwayat.php?id=<?php echo $row['locker_id']; ?>" class='green-text tooltipped' data-position='right' data-tooltip='Riwayat'><i class='fas fa-book'></i></a>
              </td>
              <td>
                <form method='POST' action='locker.php'>
                  <input type='hidden' name='id' value="<?php echo $row['locker_id']; ?>">
                  <button type='submit' onclick='return confirm(`Delete this locker "<?php echo $row['locker_id']; ?>" ?`);' name='delete' class='btn1 red-text tooltipped' data-position='top' data-tooltip='Delete'>
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