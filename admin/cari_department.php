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
            <th>Code</th>
			<th>Nama Deparment</th>
            <th>Alokasi Locker</th>
			
            <!-- <th>Owner</th> -->
            <th colspan="3">Action</th>
          </tr>
					</thead>
					<tbody>
				
					<?php					
					$query=mysqli_query($conn,"SELECT * FROM bagian WHERE code_bagian LIKE '%".$kodepasien."%' or nama_bagian LIKE '%".$kodepasien."%'   ");
					$no=1;
					$num=mysqli_num_rows($query);
					if($num>=1){
					while($row=mysqli_fetch_array($query)){
					?>					
						<tr>
              <td><?php echo $no; ?></td>
              <td><?php echo $row['id_bagian']; ?></td>
              <td><?php echo $row['code_bagian']; ?></td>
              <td><?php echo $row['nama_bagian']; ?></td>
			  <td><?php echo $row['start']." sd ".$row['end']; ?></td>
			  
              <td>
                  <a href="bagian_edit.php?id=<?php echo $row['code_bagian']; ?>" class='blue-text tooltipped' data-position='right' data-tooltip='Edit'><i class='fas fa-pencil-alt'></i></a>
              </td>
			  <td>
                  <a href="bagian_alokasi.php?id=<?php echo $row['code_bagian']; ?>" class='green-text tooltipped' data-position='right' data-tooltip='Alokasi'><i class='fas fa-code'></i></a>
              </td>
              <td>
                <form method='POST' action='locker.php'>
                  <input type='hidden' name='id' value="<?php echo $row['code_bagian']; ?>">
                  <button type='submit' onclick='return confirm(`Delete this locker "<?php echo $row['code_bagian']; ?>" ?`);' name='delete' class='btn1 red-text tooltipped' data-position='top' data-tooltip='Delete'>
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