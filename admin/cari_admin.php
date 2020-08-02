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
            <th>ID</th>
            <th>Nama</th>
			<th>Email</th>
            <th>No_Tlp</th>
			
            <!-- <th>Owner</th> -->
            <th colspan="3">Action</th>
          </tr>
					</thead>
					<tbody>
				
					<?php					
					$query=mysqli_query($conn,"SELECT * FROM admin WHERE admin_id LIKE '%".$kodepasien."%' or admin_username LIKE '%".$kodepasien."%' or admin_email LIKE '%".$kodepasien."%'    ");
					$no=1;
					$num=mysqli_num_rows($query);
					if($num>=1){
					while($row=mysqli_fetch_array($query)){
					?>					
						<tr>
              <td><?php echo $no; ?></td>
              <td><?php echo $row['admin_id']; ?></td>
              <td><?php echo $row['admin_username']; ?></td>
              <td><?php echo $row['admin_email']; ?></td>
			  <td><?php echo $row['admin_phone']; ?></td>
			  
              <td>
                  <a href='admin_edit.php?id=<?php echo $row['admin_id']; ?>' class='btn1 blue-text tooltipped' data-position='right' data-tooltip='Edit'><i class='fas fa-pencil-alt'></i></a>
              </td>
			  
              <td>
                <form method='POST' action='admin.php'>
                  <input type='hidden' name='id' value="<?php echo $row['admin_id'];?>">
                  <button type='submit' onclick='return confirm(`Delete this admin <?php echo $row['admin_id']; ?>?`);' name='delete' class='btn1 red-text tooltipped' data-position='top' data-tooltip='Delete'>
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