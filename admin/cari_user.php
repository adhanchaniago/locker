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
            <th>Username</th>
			<th>NIK</th>
            <th>Nama</th>
			<th>Bagian</th>
			<th>Email</th>
            <th>No_Tlp</th>
			
            <!-- <th>Owner</th> -->
            <th colspan="3">Action</th>
          </tr>
					</thead>
					<tbody>
				
					<?php					
					$query=mysqli_query($conn,"SELECT * FROM student WHERE student_id LIKE '%".$kodepasien."%' or student_username LIKE '%".$kodepasien."%' or student_email LIKE '%".$kodepasien."%'    ");
					$no=1;
					$num=mysqli_num_rows($query);
					if($num>=1){
					while($row=mysqli_fetch_array($query)){
					?>					
						<tr>
              <td><?php echo $no; ?></td>
              <td><?php echo $row['student_username']; ?></td>
              <td><?php echo $row['student_id']; ?></td>
              <td><?php echo $row['student_name']; ?></td>
			  <td><?php echo $row['student_department']; ?></td>
			  <td><?php echo $row['student_email']; ?></td>
			  <td><?php echo $row['student_phone']; ?></td>
              <td>
              <a href='users_edit.php?id=<?php echo $row['student_id']; ?>' class='btn-flat blue-text tooltip' data-position='right' data-tooltip='Edit'><i class='fas fa-pencil-alt'></i></a>
            </td>
            <td>
              <form method='POST' action='users.php'>
                <input type='hidden' name='id' value="<?php echo $row['student_id'];?>">
                <button type='submit' onclick='return confirm(`Delete this user <?php echo $row['student_id']; ?>?`);' name='delete' class='btn-flat red-text tooltipped' data-position='top' data-tooltip='Delete'>
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