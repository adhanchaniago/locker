<?php
  session_start();
  require 'session.php';
  include "navbar.php";
  require_once "../model/db.php";
?>

<script type="text/javascript" src="../js/Chart.min.js"></script>
<div class="wrapper">
  <section class="section">
    <div class="container2">
      <h5><i class="fas fa-chart-line"></i> Report analysis</h5>
      <div class="divider"></div>
      <div class="row">
        <div class="col s12 m6">
          <h5>Subscription Status</h5>
          <div class="chart-container">
            <canvas id="chart1"></canvas>
          </div>
        </div>
        <div class="col s12 m6">
          <h5>Locker Status</h5>
          <div class="chart-container">
            <canvas id="chart2"></canvas>
          </div>
        </div>
      </div><br>

      <div class="divider"></div><br>
      <h5>Laporan Kerusakan</h5>
      <canvas id="chart3"></canvas><br>

      <div class="divider"></div><br>
      <h5>Total Pemegang Kunci</h5>
      <canvas id="chart4"></canvas><br>
    </div>
  </section>
</div>
<?php
  include "footer.php";
?>
<script>
  // Chart 1 
    let ctx = document.getElementById("chart1").getContext('2d');
    let chart1 = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ["Active", "Expired"],
        datasets: [{
          label: '# Status',
          data: [
            <?php
              $sql = "SELECT SUM(record_sub = 'active') AS active, SUM(record_sub = 'expired') AS expired FROM record";
              $result = mysqli_query($conn, $sql);              
              while ($row = mysqli_fetch_array($result)) {
                echo $row['active'].",".$row['expired'];
              }
            ?>
          ],
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)'
          ],
          borderColor: [
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)'
          ],
          borderWidth: 1
        }]
      }
    });
  </script>

<script>
    // Chart 2 
    let ctx2 = document.getElementById("chart2").getContext('2d');
    let chart2 = new Chart(ctx2, {
      type: 'pie',
      data: {
        labels: ["Available", "Booked", "Damage"],
        datasets: [{          
          data: [
            <?php
              $sql = "SELECT SUM(locker_status = 'available') AS available, SUM(locker_status = 'booked') AS booked, SUM(locker_status = 'damage') AS damage FROM locker";
              $result = mysqli_query($conn, $sql);
              while ($row = mysqli_fetch_array($result)) {
                echo $row['available'].",".$row['booked'].",".$row['damage'];
              }
            ?>
          ],
          backgroundColor: [
            'rgba(54, 162, 235, 0.3)',
            'rgba(153, 102, 255, 0.3)',
            'rgba(255, 99, 132, 0.3)'
          ],
          borderColor: [
            'rgba(54, 162, 235, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 99, 132, 1)',
          ],
          borderWidth: 1
        }]
      }
    });
  </script>
<script>
    // Chart 3 
    let ctx3 = document.getElementById("chart3").getContext('2d');
    let chart3 = new Chart(ctx3, {
      type: 'horizontalBar',
      data: {
          labels: ["Locker Rusak", "Kunci Rusak", "Kunci Hilang", "Lain-lain"],
          datasets: [{
              label: '# Laporan Kerusakan',
              data: [
                <?php
                  $sql = "SELECT SUM(permohonan = 'Locker Rusak') AS lr,
                  SUM(permohonan = 'Kunci Rusak') AS kr,
                  SUM(permohonan = 'Kunci Hilang') AS kh,
                  SUM(permohonan = 'Lain-lain') AS oth
                  FROM maintenance_form";
                  $result = mysqli_query($conn, $sql);
                  while ($row = mysqli_fetch_array($result)) {
                    echo $row['lr'].",".$row['kr'].",".$row['kh'].",".$row['oth'];
                  }
                ?>
              ],
              backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(0, 0, 0, 0.2)'
              ],
              borderColor: [
                  'rgba(255,99,132,1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)',
                  'rgba(0, 0, 0, 1)'
              ],
              borderWidth: 1
          }]
      },
      options: {
        elements: {
          point: {
            radius:0
          }
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero:true
            }
          }]
        }
      }
    });
  </script>
<script>
    // Chart 4 
    let ctx4 = document.getElementById("chart4").getContext('2d');
    let chart4 = new Chart(ctx4, {
      type: 'bar',
      data: {
          labels: ["Pegang Kunci", "Tidak Pegang Kunci"],
          datasets: [{
              label: '# Total Pemegang Kunci',
              data: [
                <?php
                  $sql = "SELECT SUM(keylocker = 'Yes') AS yes,
                  SUM(keylocker = 'No') AS no FROM record";
                  $result = mysqli_query($conn, $sql);
                  while ($row = mysqli_fetch_array($result)) {
                    echo $row['yes'].",".$row['no'];
                  }
                ?>
              ],
              backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)'
              ],
              borderColor: [
                  'rgba(255,99,132,1)',
                  'rgba(54, 162, 235, 1)',
              ],
              borderWidth: 1
          }]
      },
      options: {
        elements: {
          point: {
            radius:0
          }
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero:true
            }
          }]
        }
      }
    });
  </script>
