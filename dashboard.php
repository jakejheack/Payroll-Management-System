<?php
session_start();

include_once("connection/cons.php");
$con = conns();

$sql_male = "SELECT gender FROM employees where gender = 'Male'";
$dan_male = $con->query($sql_male) or die ($con->error);
$row_male = $dan_male->fetch_assoc();
$count_male = mysqli_num_rows($dan_male);

$sql_female = "SELECT gender FROM employees where gender = 'Female'";
$dan_female = $con->query($sql_female) or die ($con->error);
$row_female = $dan_female->fetch_assoc();
$count_female = mysqli_num_rows($dan_female);

$total_employees = $count_male + $count_female;

// departments
$sql_dept = "SELECT * FROM departments ORDER BY dept ASC";
$dan_dept = $con->query($sql_dept) or die ($con->error);
$row_dept = $dan_dept->fetch_assoc();
$count_dept = mysqli_num_rows($dan_dept);

// header
include("includes/header.php");
?>
<script
src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3 text-primary" style="font-size:20px">Dashboard</h1>
      </div>

<div class="container text-center table-responsive">

<div class="row">
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-4">
                <div class="">
                    <div class="p-3 border   shadow"  style="height: 150px;">
                        <img class="img-fluid" style="width:50px" src="img/group.png" alt="">
                        <h1 class="h3"><?= $total_employees?></h1>
                        <b>Total Employees</b>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="">
                    <div class="p-3 border shadow"  style="height: 150px;">
                        <img class="img-fluid" style="width:50px" src="img/man.png" alt="">
                        <h1 class="h3"><?= $count_male?></h1>
                        <b>Male</b>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="">
                    <div class="p-3 border shadow"  style="height: 150px;">
                        <img class="img-fluid" style="width:50px" src="img/girl.png" alt="">
                        <h1 class="h3"><?= $count_female?></h1>
                        <b>Female</b>
                    </div>                    
                </div>
            </div>
        </div>
        <p></p>
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <div class="p-3 border shadow" style="height: 350px;">
                      <div class="text-left"></div>
                        <canvas id="myChart2" style="width:100%; max-width:700px;" class="w-100 p-3 h-100 d-inline-block"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
            <div class="col-md-12">
                <div class="p-3 border shadow" style="height: 500px;">
                    <canvas id="myChart" style="width:100%;max-width:700px; height: 500px;"><span>Loading</span></canvas>
                </div>
            </div>
        </div>
</div>
<p></p>
  
  <?php
    $tr = ceil($count_dept / 3);
    $no = 0;
    $nn = 0;  
  ?>

<div class="row">
    <div class="col-md-8">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h4 class="h4 text-primary" style="font-size:17px">Departments</h4>
          </div>
        <div class="row row-cols-<?= $tr?> row-cols-lg-5 g-2 g-lg-3">
                <?php
                  do{
                      $depts = $row_dept['dept'];
                      $depts = str_replace("'", "\'", $depts);
                      $length = strlen($depts);

                      $count_m_dept = $row_dept['males'];
                      $count_f_dept = $row_dept['females'];

                      $total_emp_dept = $count_m_dept + $count_f_dept;

                      $nn++;
                      $no++;
                              ?>
                    <div class="col-md-4">
                        <div class="">
                            <div class="p-3 border shadow"  style="height: 137px;">
                                <b class="h4 text-danger" style="font-size:15px"><?= $total_emp_dept ?></b>
                                <br>
                                <b <?= $length > 20 ? 'style="font-size:9px"' : 'style="font-size:11px"'?>><?= $row_dept['dept'] ?></b>
                                <br>
                                <i style="font-size:10px"><?= 'Male:'.$count_m_dept.' Female:'.$count_f_dept ?></i>
                            </div>
                          </div>
                    </div>
                <?php
                    $no = 0;
                    }while($row_dept = $dan_dept->fetch_assoc());

                      // new employees
                      $sql_new = "SELECT * FROM employees ORDER BY ID DESC LIMIT 5";
                      $dan_new = $con->query($sql_new) or die ($con->error);
                      $row_new = $dan_new->fetch_assoc();
                      $count_new = mysqli_num_rows($dan_new);

                      // bday
                      $sql_bday = "SELECT * FROM employees ORDER BY MONTH(bdate), DAY(bdate)";
                      $dan_bday = $con->query($sql_bday) or die ($con->error);
                      $row_bday = $dan_bday->fetch_assoc();
                      $count_bday = mysqli_num_rows($dan_bday);
                ?>

        </div>

    </div>
    <div class="col-md-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h4 class="h4 text-primary" style="font-size:17px">New Added Employees</h4>
          </div>
            <?php
                do{
                  $pictures = $row_new['pictures'];
                  ?>
                    <div class="">
                      <div class="p-3 border shadow" style="height: 97px;">
                        <?php
                          echo '<div class="" style="width:"><img src="data:image/jpg;charset=utf8;base64,'.base64_encode($pictures).'" alt="" width="57px" height="57px" style="padding-right:5px" class="rounded-circle float-left"></div>
                          <div style="font-size:11px; padding-left:20px" class="text-left"><strong class="text-success" style="font-size:12px">'.$row_new['lname'].', '.$row_new['fname'].' '.substr($row_new['mname'],0,1).' '.$row_new['extname'].'</strong><br>
                          <strong class="text-secondary">'.$row_new['position'].'</strong><br>'.$row_new['dept'].'</div>';
                        ?>
                      </div>
                    </div>
                    <p></p>
                  <?php
                }while($row_new = $dan_new->fetch_assoc());
              ?>
    </div>
</div>
<p></p>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h4 class="h4 text-primary" style="font-size:17px"><svg class="bi"><use xlink:href="#birthday-icon"/></svg>  <?= date('F Y')?> Celebrants</h4>
    </div>
  <div class="row">
          <div class="row row-cols-3 row-cols-lg-5 g-2 g-lg-3">
          <?php
                  do{
                    $bday = date('F', strtotime($row_bday['bdate']));
                    $dayb = date('d', strtotime($row_bday['bdate']));

                    if($bday == date('F')){
                      $pictures = $row_bday['pictures'];
                      ?>
                        <div class="col-md-4">
                          <div class="">
                            <div class="p-3 border shadow mb-2 <?= date('F d') == date('F d', strtotime($row_bday['bdate'])) ? 'bg-primary text-white' : 'text-success' ?> " style="height: 97px;">
                                <div class="">
                                  <img src="data:image/jpg;charset=utf8;base64,<?= base64_encode($pictures)?>" alt="" width="57px" height="57px" style="padding-right:5px" class="rounded-circle float-left">
                                  <?php
                                  if(date('F d') == date('F d', strtotime($row_bday['bdate']))){
                                  ?>
                                  <span class="top-0 start-100 translate-middle p-2 badge bg-warning rounded-circle float-right">
                                      <span class="visually-hidden">New alerts</span>
                                    </span>
                                  <?php } ?>
                                </div>
                                <div style="font-size:10px; padding-left:20px" class="text-left">
                                  <strong class="" style="font-size:12px">
                                    <?= $row_bday['fname'].' '.$row_bday['lname'].' '.$row_bday['extname'] ?>
                                  </strong>
                                  <br>
                                  <strong <?= date('F d') == date('F d', strtotime($row_bday['bdate'])) ? '' : 'class="text-danger"' ?>>
                                    <?= date('M d', strtotime($row_bday['bdate'])) ?>
                                </strong>
                                </div>
                            </div>
                          </div>
                        </div>
                      <?php
                    }
                  }while($row_bday = $dan_bday->fetch_assoc());
          ?>
      </div>
    </div>
</div>
<p></p>
<!--  -->
      </div>
    </div>
</main>

<script>
const xValues1 = ["Jul-15","Jul-30","Aug-15","Aug-28","Sep-15","Sep-30","Oct-15","Oct-30","Nov-15","Nov-30","Dec-15","Dec-30"];

new Chart("myChart2", {
  type: "line",
  data: {
    labels: xValues1,
    datasets: [{
      label: "Gross Amount",
      data: [100800,100900,101000,102000,103000,104000,105000,106000,107000,108600],
      borderColor: "blue",
      fill: false
    },{
      label: "Deductions",
      data: [80600,81400,82300,83200,84100,85600,86000,88400,88200,89000,100000],
      borderColor: "red",
      fill: false
    },{
      label: "Net Pay",
      data: [21300,22300,23300,24300,25300,26300,27300,28300,29300,29300],
      borderColor: "green",
      fill: false
    }]
  },
  options: {
    legend: {
      display: true,
      text: "Peso"
    },
    title: {
      display: true,
      text: "Salary Statistics Performance"
    }
  }
});


const xValues = ["BRANCH 1", "BRANCH 2", "BRANCH 3", "BRANCH 4", "BRANCH 5", "BRANCH 6", "BRANCH 7", "BRANCH 8"];
const yValues = [82, 32, 21, 26, 13, 35, 34, 23];
const barColors = [
  "#b91d47",
  "#00aba9",
  "#2b5797",
  "#e8c3b9",
  "#1e7145",
  "#E88682",
  "#67C86B",
  "#67AAE2"
];

new Chart("myChart", {
  type: "doughnut",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
    title: {
      display: true,
      text: "Employees per Department"
    }
  }
});
</script>

<?php
include('includes/footer.php');
?>
