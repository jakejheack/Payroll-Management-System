<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");
$y_now = date("Y");
$m_now = date("m");
$d_now = date("j");


if(isset($_GET['Department'])){
    $dept = $_GET['Department'];
    $_SESSION['department'] = $_GET['Department'];
    $dept = str_replace("'", "\'", $dept);
}elseif(isset($_SESSION['department']))
{
    $dept = $_SESSION['department'];
    $dept = str_replace("'", "\'", $dept);
}else{
    $dept = "";
}

// employees info
if(empty($dept)){
    $sql_emp = "SELECT * FROM employees order by lname,fname,mname ASC";
}else{
    $sql_emp = "SELECT * FROM employees WHERE payroll_dept = '$dept' order by lname,fname,mname ASC";
}
$dan_emp = $con->query($sql_emp) or die ($con->error);
$row_emp = $dan_emp->fetch_assoc();
$count = mysqli_num_rows($dan_emp); 

// departments
$sql_dept = "SELECT * FROM departments WHERE dept = '$dept'";
$dan_dept = $con->query($sql_dept) or die ($con->error);
$row_dept = $dan_dept->fetch_assoc();

$dept_name = $row_dept['dept'];

// header
include("includes/header.php");
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4>DTR for the Period of 
            <?php
                if(isset($_SESSION['dtr_From']) && isset($_SESSION['dtr_To']))
                {
                    $from = $_SESSION['dtr_From'];
                    $to = $_SESSION['dtr_To'];
                    ?>
                    <?= date('M. d, Y', strtotime($from)).' - '.date('M. d, Y', strtotime($to))?>
                    <?php
                }
            ?>
        </h4>
      </div>

        <div class="container table-responsive">
            <div class="form-row">
                    <div class="form-group col-md-4">
                        <span>Department: <b><?= $dept_name ?></b></span> 
                    </div>
                    <div class="form-group col-md-8 text-right">
                        <a href="payroll_create.php?Department=<?= $dept_name ?>" class="btn btn-secondary btn-sm">Back</a>
                    </div>
            </div>

                <table id="dtrTable" class="dataTable display table table-striped table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name of Employee</th>
                            <?php
                                $ddd = date('l, F d, Y', strtotime($from));
                                $th = 0;

                                do{
                                    ?>
                                        <th colspan=""><?= date('m/d', strtotime($ddd)) ?></th>
                                        <!-- <th></th> -->
                                    <?php
                                    $ddd = date('m/d/Y', strtotime($ddd . " +1 day"));
                                    $th++;
                                }while(strtotime($ddd) <= strtotime($to));
                            ?>
                            <th colspan="">Total Hrs.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($count < 1){
                                $thh = ($th2 * 2) + 3;
                                ?>
                                    <tr>
                                    </tr>
                                <?php
                            }else{
                                $no = 0;
                                do{
                                    $from = $_SESSION['dtr_From'];
                                    $to = $_SESSION['dtr_To'];

                                    $emp_id = $row_emp['ID'];
                                    $no++;
                                    ?>
                                        <tr>
                                            <td><?= $no?></td>
                                            <td style="text-align: left;">
                                                <a href="daily_time_record_employees.php?
                                                ID=<?= $emp_id?>
                                                &Department=<?= $dept_name?>" class="btn text-active btn-link">
                                                <b><?= $row_emp['lname'].', '.$row_emp['fname'].' '.substr($row_emp['mname'],0,1).' '.$row_emp['extname']; ?></b>
                                                </a>
                                            </td>
                                            <?php
                                                $total_hrs = 0;
                                                $total_ot = 0;
                                                do{
                                                    $ddd2 = date('l, F d, Y', strtotime($from));
                                                    // dtr
                                                    $sql_dtr = "SELECT * FROM dtrs WHERE emp_id = '$emp_id' && log_date = '$ddd2'";
                                                    $dan_dtr = $con->query($sql_dtr) or die ($con->error);
                                                    $row_dtr = $dan_dtr->fetch_assoc();
                                                    $count_dtr = mysqli_num_rows($dan_dtr);

                                                    $dayy = date('l', strtotime($from));

                                                    $dayys = date('D', strtotime($from));
                                                    $dayys = strtolower($dayys);
        
                                                    // schedules    
                                                    $sql_sched = "SELECT * FROM schedules WHERE emp_id = '$emp_id'";
                                                    $dan_sched = $con->query($sql_sched) or die ($con->error);
                                                    $row_sched = $dan_sched->fetch_assoc();
                                                    $count_sched = mysqli_num_rows($dan_sched);

                                                    if($count_sched > 0)
                                                    {
                                                        $mon = $row_sched['mon'];
                                                        $tue = $row_sched['tue'];
                                                        $wed = $row_sched['wed'];
                                                        $thu = $row_sched['thu'];
                                                        $fri = $row_sched['fri'];
                                                        $sat = $row_sched['sat'];
                                                        $sun = $row_sched['sun'];
                                                    }else{
                                                        $mon = 'ON';
                                                        $tue = 'ON';
                                                        $wed = 'ON';
                                                        $thu = 'ON';
                                                        $fri = 'ON';
                                                        $sat = 'ON';
                                                        $sun = 'ON';
                                                    }

                                                    // holidays
                                                    $hol_date = date('2023-m-d', strtotime($from));

                                                    $sql_hol = "SELECT * FROM holidays WHERE datee = '$hol_date'";
                                                    $dan_hol = $con->query($sql_hol) or die ($con->error);
                                                    $row_hol = $dan_hol->fetch_assoc();
                                                    $count_hol = mysqli_num_rows($dan_hol);

                                                    // holiday with work
                                                    if($count_hol > 0){
                                                    $hol_types = $row_hol['types'];
                                                    }else{
                                                    $hol_types = "";
                                                    }

                                                    $remarks = "";

                                                    // day-off w/ Work
                                                    if($dayys == 'mon' && $mon == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "Day-Off";
                                                    }
                                                    elseif($dayys == 'tue' && $tue == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "Day-Off";
                                                    }
                                                    elseif($dayys == 'wed' && $wed == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "Day-Off";
                                                    }
                                                    elseif($dayys == 'thu' && $thu == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "Day-Off";
                                                    }
                                                    elseif($dayys == 'fri' && $fri == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "Day-Off";
                                                    }
                                                    elseif($dayys == 'sat' && $sat == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "Day-Off";
                                                    }
                                                    elseif($dayys == 'sun' && $sun == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "Day-Off";
                                                    }

                                                    if($count_dtr < 1)
                                                    {
                                                        ?>
                                                            <td>-</td>
                                                        <?php
                                                    }else{
                                                        $hrs1 = (double)$row_dtr['total_hrs'];

                                                        $total_hrs = $total_hrs + $hrs1;

                                                        ?>
                                                            <td <?= $hrs1 < 7.5 ? 'class="table-danger"' : ''?>><?= $hrs1?></td>
                                                        <?php
                                                        }
                                                    ?>
                                                    <?php
                                                    $from = date('m/d/Y', strtotime($from . " +1 day"));
                                                }while(strtotime($from) <= strtotime($to));
                                                        ?>
                                                            <td><b><?= $total_hrs ?></b></td>
                                        </tr>
                                    <?php
                                }while($row_emp = $dan_emp->fetch_assoc());
                            }
                        ?>
                    </tbody>
                </table>
            </div>
<!--  -->
</div>
    </div>
</main>

<?php
include("includes/footer.php");
?>

<script>
// let table = new DataTable('#dtrTable');
// $('#dtrTable').DataTable();

let table = new DataTable('#dtrTable',{
        fixedColumns: {
        left: 2,
        right: 1
        },
        // scrollCollapse: true,
        scrollX: true
        // scrollY: 500
    });
</script>