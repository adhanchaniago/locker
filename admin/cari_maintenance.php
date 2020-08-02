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
            <th>No_Pengajuan</th>
			<th>Jenis_Pengajuan</th>
            <th>No_Locker</th>
            <th>User</th>
			<th>Tgl_Pengajuan</th>
			<th>Tgl_Pengechekan</th>
			<th>Tgl_Perbaikan</th>	
            <th colspan="2" class="center">Actions</th>
          </tr>
					</thead>
					<tbody>
				
					<?php					
					$query=mysqli_query($conn,"SELECT * FROM maintenance_form WHERE maintenance_id='$kodepasien' or locker_id LIKE '%".$kodepasien."%' or nik LIKE '%".$kodepasien."%' or nama LIKE '%".$kodepasien."%'  ");
					$no=1;
					$num=mysqli_num_rows($query);
					if($num>=1){
					while($row=mysqli_fetch_array($query)){
					?>					
						<tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $row['maintenance_id']; ?></td>                      
            <td><?php echo $row['permohonan']; ?></td>
			<td><?php echo $row['locker_id']; ?></td>
            <td><?php echo $row['nik']; ?> - <?php echo $row['nama']; ?></td>
			<td><?php echo tgl_indo($row['tgl_pengajuan']); ?></td>
			<td><?php echo tgl_indo($row['tgl_chek']); ?></td> 
			<td><?php echo tgl_indo($row['tgl_perbaikan']); ?></td> 			
            <td>
                  <a href="maintenance_edit.php?id=<?php echo $row['maintenance_id']; ?>" class='black-text tooltipped' data-position='right' data-tooltip='Edit'><i class='fas fa-code'></i>Edit</a>
				  <a href="maintenance_pengechekan.php?id=<?php echo $row['maintenance_id']; ?>" class='blue-text tooltipped' data-position='right' data-tooltip='Pengechekan'><i class='fas fa-pencil-alt'></i>Check</a>              
                  <a href="maintenance_perbaikan.php?id=<?php echo $row['maintenance_id']; ?>" class='green-text tooltipped' data-position='right' data-tooltip='Perbaikan'><i class='fas fa-arrows-alt'></i>Finish</a>
              </td>
            <td>
                <form method='POST' action='maintenance.php'>
                  <input type='hidden' name='id' value="<?php echo $row['maintenance_id']; ?>">
                  <button type='submit' onclick='return confirm(`Delete this locker "<?php echo $row['maintenance_id']; ?>" ?`);' name='delete' class='btn1 red-text tooltipped' data-position='top' data-tooltip='Delete'>
                    Del<i class='far fa-trash-alt'></i>
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