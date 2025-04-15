<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");
$y_now = date("Y");
$m_now = date("m");
$d_now = date("j");

if(isset($_GET['ID'])){
    $emp_ids = $_GET['ID'];
    $_SESSION['emp_id'] = $emp_ids;

    if(!isset($_GET['Department'])){
        $dept = '';
    }else{
        $dept = $_GET['Department'];
    }
}elseif(!isset($_GET['ID']) && isset($_SESSION['emp_id'])){
    $emp_ids = $_SESSION['emp_id'];
}else{
    echo header("Location: employees_dtr.php");
}

if(isset($_SESSION['employees_dtr_from']) && isset($_SESSION['employees_dtr_to'])){
    $from = $_SESSION['employees_dtr_to'];
    $to = $_SESSION['employees_dtr_from'];
}else{
    $from = date('Y-m-d');
    $to = date('Y-m-d');
}

// employees info
$sql_emp = "SELECT * FROM employees WHERE ID = '$emp_ids'";
$dan_emp = $con->query($sql_emp) or die ($con->error);
$row_emp = $dan_emp->fetch_assoc();
$count_emp = mysqli_num_rows($dan_emp);

if($count_emp > 0){
    $emp_name = $row_emp['lname'].', '.$row_emp['fname'].' '.$row_emp['mname'].' '.$row_emp['extname'];
    $position = $row_emp['position'];
}else{
    $emp_name = '-';
    $position = '-';    
}

// schedules
$sql_sched = "SELECT * FROM schedules WHERE emp_id = '$emp_ids'";
$dan_sched = $con->query($sql_sched) or die ($con->error);
$row_sched = $dan_sched->fetch_assoc();
$count_sched = mysqli_num_rows($dan_sched);

$breaks = 30;

if($count_sched > 0)
{
    $mons = $row_sched['mon'];
    $tues = $row_sched['tue'];
    $weds = $row_sched['wed'];
    $thus = $row_sched['thu'];
    $fris = $row_sched['fri'];
    $sats = $row_sched['sat'];
    $suns = $row_sched['sun'];

    if(!empty($row_sched['min_break'])){
        $breaks = $row_sched['min_break'];
    }

    if($mons == 'ON'){
        $mon_in1 = date('H:i', strtotime($row_sched['mon_in1']));
        $mon_out1 = date('H:i', strtotime($row_sched['mon_out1']));
        $mon_in2 = date('H:i', strtotime($row_sched['mon_in2']));
        $mon_out2 = date('H:i', strtotime($row_sched['mon_out2']));       
    }else{
        $mon_in1 = '';
        $mon_out1 = '';
        $mon_in2 = '';
        $mon_out2 = '';
    }

    if($tues == 'ON'){
        $tue_in1 = date('H:i', strtotime($row_sched['tue_in1']));
        $tue_out1 = date('H:i', strtotime($row_sched['tue_out1']));
        $tue_in2 = date('H:i', strtotime($row_sched['tue_in2']));
        $tue_out2 = date('H:i', strtotime($row_sched['tue_out2']));
    }else{
        $tue_in1 = '';
        $tue_out1 = '';
        $tue_in2 = '';
        $tue_out2 = '';
    }

    if($weds == 'ON'){
        $wed_in1 = date('H:i', strtotime($row_sched['wed_in1']));
        $wed_out1 = date('H:i', strtotime($row_sched['wed_out1']));
        $wed_in2 = date('H:i', strtotime($row_sched['wed_in2']));
        $wed_out2 = date('H:i', strtotime($row_sched['wed_out2']));
    }else{
        $wed_in1 = '';
        $wed_out1 = '';
        $wed_in2 = '';
        $wed_out2 = '';
    }

    if($thus == 'ON'){
        $thu_in1 = date('H:i', strtotime($row_sched['thu_in1']));
        $thu_out1 = date('H:i', strtotime($row_sched['thu_out1']));
        $thu_in2 = date('H:i', strtotime($row_sched['thu_in2']));
        $thu_out2 = date('H:i', strtotime($row_sched['thu_out2']));
    }else{
        $thu_in1 = '';
        $thu_out1 = '';
        $thu_in2 = '';
        $thu_out2 = '';
    }

    if($fris == 'ON'){
        $fri_in1 = date('H:i', strtotime($row_sched['fri_in1']));
        $fri_out1 = date('H:i', strtotime($row_sched['fri_out1']));
        $fri_in2 = date('H:i', strtotime($row_sched['fri_in2']));
        $fri_out2 = date('H:i', strtotime($row_sched['fri_out2']));
    }else{
        $fri_in1 = '';
        $fri_out1 = '';
        $fri_in2 = '';
        $fri_out2 = '';
    }

    if($sats == 'ON'){
        $sat_in1 = date('H:i', strtotime($row_sched['sat_in1']));
        $sat_out1 = date('H:i', strtotime($row_sched['sat_out1']));
        $sat_in2 = date('H:i', strtotime($row_sched['sat_in2']));
        $sat_out2 = date('H:i', strtotime($row_sched['sat_out2']));
    }else{
        $sat_in1 = '';
        $sat_out1 = '';
        $sat_in2 = '';
        $sat_out2 = '';
    }

    if($suns == 'ON'){
        $sun_in1 = date('H:i', strtotime($row_sched['sun_in1']));
        $sun_out1 = date('H:i', strtotime($row_sched['sun_out1']));
        $sun_in2 = date('H:i', strtotime($row_sched['sun_in2']));
        $sun_out2 = date('H:i', strtotime($row_sched['sun_out2']));
    }else{
        $sun_in1 = '';
        $sun_out1 = '';
        $sun_in2 = '';
        $sun_out2 = '';
    }

}else{
    $mons = 'OFF';
    $tues = 'OFF';
    $weds = 'OFF';
    $thus = 'OFF';
    $fris = 'OFF';
    $sats = 'OFF';
    $suns = 'OFF';

    $mon_in1 = '';
    $mon_out1 = '';
    $mon_in2 = '';
    $mon_out2 = '';

    $tue_in1 = '';
    $tue_out1 = '';
    $tue_in2 = '';
    $tue_out2 = '';

    $wed_in1 = '';
    $wed_out1 = '';
    $wed_in2 = '';
    $wed_out2 = '';

    $thu_in1 = '';
    $thu_out1 = '';
    $thu_in2 = '';
    $thu_out2 = '';

    $fri_in1 = '';
    $fri_out1 = '';
    $fri_in2 = '';
    $fri_out2 = '';

    $sat_in1 = '';
    $sat_out1 = '';
    $sat_in2 = '';
    $sat_out2 = '';

    $sun_in1 = '';
    $sun_out1 = '';
    $sun_in2 = '';
    $sun_out2 = '';

}


$_SESSION['Back_Location'] = 'daily_time_record_employees.php';

// header
include("includes/header.php");
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h4">Employees DTR</h3>
      </div>

        <div class="container table-responsive">
            <div class="form-row">
                <div class="form-group col-md-">
                                <?php
                                $pictures = $row_emp['pictures'];
                                    if(empty($pictures))
                                    {
                                        ?>
                                            <img id="" src="img/blank.jpg" alt="Employees Picture"  width="100px" height="100px" class="rounded-circle position-relative"/>
                                        <?php
                                    }elseif(!empty($pictures))
                                    {
                                        ?>
                                            <img id="" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($pictures); ?>" alt="Employees Picture"  width="100px" height="100px" style="margin:0; padding:0" class="rounded-circle position-relative"/>
                                        <?php
                                    }
                                ?>
                </div>
                <div class="form-group col-md-7">
                    <h5><?= $row_emp['lname'].', '.$row_emp['fname'].' '.$row_emp['mname'].' '.$row_emp['extname']?></h5>
                    <!-- <span class="text-secondary">ID #: <?= $row_emp['emp_id']?></span> -->
                    <!-- <br> -->
                    <strong class="text-secondary">Position: <?= $row_emp['position']?></strong>
                    <br>
                    <strong class="text-secondary">Department: <?= $row_emp['dept']?></strong>
                    <br>
                    <strong class="text-secondary">Payroll Department: <?= empty($row_emp['payroll_dept']) && $row_emp['confi'] == 'Yes' ? 'CONFI MEMBER' : $row_emp['payroll_dept'] ?></strong>
                </div>
                <div class="form-group col-md text-right">
                    <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#schedules" class="btn btn-primary btn-sm">Regular Working Schedule</a>
                    <a href="pdf_daily_time_record.php" target="_blank" class="btn btn-success btn-sm">Print PDF</a>
                    <?php
                        if(empty($dept)){
                            echo '<a href="employees_dtr.php" class="btn btn-secondary btn-sm">Back</a>';
                        }else{
                            echo '<a href="daily_time_records.php?Department='.$dept.'" class="btn btn-secondary btn-sm">Back</a>';
                            $to = $_SESSION['dtr_From'];
                            $from = $_SESSION['dtr_To'];
                            $_SESSION['employees_dtr_to'] = $from;
                            $_SESSION['employees_dtr_from'] = $to;
                        }
                    ?>                
                </div>
           </div>
 
            <div class="modal fade bd-example-modal-lgs" id="schedules" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Employee's Regular Working Schedule</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="employees_new_schedule.php" method="post">
                                <div class="row">
                                    <div class="col">
                                        <span>Employee's Name: <b><?= $emp_name ?></b></span>
                                    </div>
                                </div>

                                    <table class="dataTable table table-striped table-bordered" style="width:100%; margin: 0">
                                        <thead style="font-size: 15px;">
                                            <tr>
                                                <th colspan="" style="text-align: right"><span>Applicable Date:</span></th>
                                                <th colspan="2">
                                                    <div class="input-group">
                                                        <span class="input-group-text">From:</span>
                                                        <input type="date" name="sched_from" id="sched_from" value="<?= date('Y-m-d', strtotime($from))?>" class="form-control" required>
                                                    </div>
                                                </th>
                                                <th colspan="2">
                                                    <div class="input-group">
                                                        <span class="input-group-text">To:</span>
                                                        <input type="date" name="sched_to" id="sched_to" value="<?= date('Y-m-d', strtotime($to))?>" class="form-control" required>
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Mins. Break:</span>
                                                        <input type="number" name="min_break" id="breaks"  class="form-control" placeholder="# of minutes" value="<?= $breaks ?>" style="width: 77px;" required></th>
                                                    </div>    
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;">Day</th>
                                                <th style="text-align: center;">IN-1</th>
                                                <th style="text-align: center;">OUT-1</th>
                                                <th style="text-align: center;">IN-2</th>
                                                <th style="text-align: center;">OUT-2</th>
                                                <th>Work Day Status</th>
                                            </tr>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th style="color: <?= $mons == 'ON' ? 'blue' : 'red' ?>;">Monday</th>
                                                <td><input type="time" name="mon_in1" id="mon_in1"  class="form-control" value="<?= $mon_in1?>"></td>
                                                <td><input type="time" name="mon_out1" id="mon_out1"  class="form-control" value="<?= $mon_out1?>"></td>
                                                <td><input type="time" name="mon_in2" id="mon_in2"  class="form-control" value="<?= $mon_in2?>"></td>
                                                <td><input type="time" name="mon_out2" id="mon_out2"  class="form-control" value="<?= $mon_out2?>"></td>
                                                <td><select name="mon_on" id="" class="custom-select" required>
                                                    <option value="" <?= $count_sched <= 0 ? 'selected' : '' ?> hidden></option>
                                                    <option value="ON" <?= $mons == 'ON' ? 'selected' : '' ?>>Active</option>
                                                    <option value="OFF"  <?= $mons == 'OFF' && $count_sched > 0 ? 'selected' : '' ?>>Day off</option>
                                                </select></td>
                                            </tr>
                                            <tr>
                                                <th style="color: <?= $tues == 'ON' ? 'blue' : 'red' ?>;">Tuesday</th>
                                                <td><input type="time" name="tue_in1" id="tue_in1"  class="form-control" value="<?= $tue_in1?>"></td>
                                                <td><input type="time" name="tue_out1" id="tue_out1"  class="form-control" value="<?= $tue_out1?>"></td>
                                                <td><input type="time" name="tue_in2" id="tue_in2"  class="form-control" value="<?= $tue_in2?>"></td>
                                                <td><input type="time" name="tue_out2" id="tue_out2"  class="form-control" value="<?= $tue_out2?>"></td>
                                                <td><select name="tue_on" id="" class="custom-select" required>
                                                    <option value="" <?= $count_sched <= 0 ? 'selected' : '' ?> hidden></option>
                                                    <option value="ON" <?= $tues == 'ON' ? 'selected' : '' ?>>Active</option>
                                                    <option value="OFF"  <?= $tues == 'OFF' && $count_sched > 0 ? 'selected' : '' ?>>Day off</option>
                                                </select></td>
                                            </tr>
                                            <tr>
                                                <th style="color: <?= $weds == 'ON' ? 'blue' : 'red' ?>;">Wednesday</th>
                                                <td><input type="time" name="wed_in1" id="wed_in1"  class="form-control" value="<?= $wed_in1?>"></td>
                                                <td><input type="time" name="wed_out1" id="wed_out1"  class="form-control" value="<?= $wed_out1?>"></td>
                                                <td><input type="time" name="wed_in2" id="wed_in2"  class="form-control" value="<?= $wed_in2?>"></td>
                                                <td><input type="time" name="wed_out2" id="wed_out2"  class="form-control" value="<?= $wed_out2?>"></td>
                                                <td><select name="wed_on" id="" class="custom-select" required>
                                                    <option value="" <?= $count_sched <= 0 ? 'selected' : '' ?> hidden></option>
                                                    <option value="ON" <?= $weds == 'ON' ? 'selected' : '' ?>>Active</option>
                                                    <option value="OFF"  <?= $weds == 'OFF' && $count_sched > 0 ? 'selected' : '' ?>>Day off</option>
                                                </select></td>
                                            </tr>
                                            <tr>
                                                <th style="color: <?= $thus == 'ON' ? 'blue' : 'red' ?>;">Thursday</th>
                                                <td><input type="time" name="thu_in1" id="thu_in1"  class="form-control" value="<?= $thu_in1?>"></td>
                                                <td><input type="time" name="thu_out1" id="thu_out1"  class="form-control" value="<?= $thu_out1?>"></td>
                                                <td><input type="time" name="thu_in2" id="thu_in2"  class="form-control" value="<?= $thu_in2?>"></td>
                                                <td><input type="time" name="thu_out2" id="thu_out2"  class="form-control" value="<?= $thu_out2?>"></td>
                                                <td><select name="thu_on" id="" class="custom-select" required>
                                                    <option value="" <?= $count_sched <= 0 ? 'selected' : '' ?> hidden></option>
                                                    <option value="ON" <?= $thus == 'ON' ? 'selected' : '' ?>>Active</option>
                                                    <option value="OFF"  <?= $thus == 'OFF' && $count_sched > 0 ? 'selected' : '' ?>>Day off</option>
                                                </select></td>
                                            </tr>
                                            <tr>
                                                <th style="color: <?= $fris == 'ON' ? 'blue' : 'red' ?>;">Friday</th>
                                                <td><input type="time" name="fri_in1" id="fri_in1"  class="form-control" value="<?= $fri_in1?>"></td>
                                                <td><input type="time" name="fri_out1" id="fri_out1"  class="form-control" value="<?= $fri_out1?>"></td>
                                                <td><input type="time" name="fri_in2" id="fri_in2"  class="form-control" value="<?= $fri_in2?>"></td>
                                                <td><input type="time" name="fri_out2" id="fri_out2"  class="form-control" value="<?= $fri_out2?>"></td>
                                                <td><select name="fri_on" id="" class="custom-select" required>
                                                    <option value="" <?= $count_sched <= 0 ? 'selected' : '' ?> hidden></option>
                                                    <option value="ON" <?= $fris == 'ON' ? 'selected' : '' ?>>Active</option>
                                                    <option value="OFF"  <?= $fris == 'OFF' && $count_sched > 0 ? 'selected' : '' ?>>Day off</option>
                                                </select></td>
                                            </tr>
                                            <tr>
                                                <th style="color: <?= $sats == 'ON' ? 'blue' : 'red' ?>;">Saturday</th>
                                                <td><input type="time" name="sat_in1" id="sat_in1"  class="form-control" value="<?= $sat_in1?>"></td>
                                                <td><input type="time" name="sat_out1" id="sat_out1"  class="form-control" value="<?= $sat_out1?>"></td>
                                                <td><input type="time" name="sat_in2" id="sat_in2"  class="form-control" value="<?= $sat_in2?>"></td>
                                                <td><input type="time" name="sat_out2" id="sat_out2"  class="form-control" value="<?= $sat_out2?>"></td>
                                                <td><select name="sat_on" id="" class="custom-select" required>
                                                    <option value="" <?= $count_sched <= 0 ? 'selected' : '' ?> hidden></option>
                                                    <option value="ON" <?= $sats == 'ON' ? 'selected' : '' ?>>Active</option>
                                                    <option value="OFF"  <?= $sats == 'OFF' && $count_sched > 0 ? 'selected' : '' ?>>Day off</option>
                                                </select></td>
                                            </tr>
                                            <tr>
                                                <th style="color: <?= $suns == 'ON' ? 'blue' : 'red' ?>;">Sunday</th>
                                                <td><input type="time" name="sun_in1" id="sun_in1"  class="form-control" value="<?= $sun_in1?>"></td>
                                                <td><input type="time" name="sun_out1" id="sun_out1"  class="form-control" value="<?= $sun_out1?>"></td>
                                                <td><input type="time" name="sun_in2" id="sun_in2"  class="form-control" value="<?= $sun_in2?>"></td>
                                                <td><input type="time" name="sun_out2" id="sun_out2"  class="form-control" value="<?= $sun_out2?>"></td>
                                                <td><select name="sun_on" id="" class="custom-select" required>
                                                    <option value="" <?= $count_sched <= 0 ? 'selected' : '' ?> hidden></option>
                                                    <option value="ON" <?= $suns == 'ON' ? 'selected' : '' ?>>Active</option>
                                                    <option value="OFF"  <?= $suns == 'OFF' && $count_sched > 0 ? 'selected' : '' ?>>Day off</option>
                                                </select></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="mb-3" style="text-align: right;">
                                        <input type="hidden" name="emp_id" value="<?= $emp_ids?>">
                                        <input type="hidden" name="pages" value="daily_time_record_employees">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-primary btn-sm" value="Save Changes" name="update_time">
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="date_covered">
                <?php 
                if(isset($_SESSION['emp_id']) && $count_emp > 0){
                    $emp_id = $_SESSION['emp_id'];
                    ?>
                    <table id="dtr_table" class="dataTable table table-striped table-bordered table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th rowspan="2">Date</th>
                                <th rowspan="2">Day</th>
                                <th rowspan="2">In-1</th>
                                <th rowspan="2">Out-1</th>
                                <th rowspan="2">In-2</th>
                                <th rowspan="2">Out-2</th>
                                <th rowspan="2">In-3</th>
                                <th rowspan="2">Out-3</th>
                                <th rowspan="2">In-4</th>
                                <th rowspan="2">Out-4</th>
                                <th rowspan="2">Work Hrs.</th>
                                <th rowspan="2">Off w/ work <br> Status</th>
                                <th colspan="2" style="text-align: center;">Overtime</th>
                                <th colspan="2" style="text-align: center;">BT-1</th>
                                <th colspan="2" style="text-align: center;">BT-2</th>
                                <!-- <th colspan="2" style="text-align: center;">BT-3</th> -->
                                <th rowspan="2">Mins. Break</th>
                                <th rowspan="2">Remarks</th>
                            </tr>
                            <tr>
                                <th>Hrs</th>
                                <th>Status</th>
                                <th>IN</th>
                                <th>OUT</th>
                                <th>IN</th>
                                <th>OUT</th>
                                <!-- <th>IN</th>
                                <th>OUT</th> -->
                            </tr>
                        </thead>
                        <tbody>
                                <?php
                                    $total_hrs = 0;
                                    $total_ot = 0;
                                    $total_reg_late = 0;
                                    $total_ut = 0;
                                    $bt_late = 0;
                                    do{

                                        ?>
                                        <tr>
                                            <?php
                                                $ddd = date('l, F d, Y', strtotime($to));
                                                // dtr
                                                $sql_dtr = "SELECT * FROM dtrs WHERE emp_id = '$emp_id' && log_date = '$ddd'";
                                                $dan_dtr = $con->query($sql_dtr) or die ($con->error);
                                                $row_dtr = $dan_dtr->fetch_assoc();
                                                $count = mysqli_num_rows($dan_dtr);

                                                // Breaktime
                                                $sql_dtr_breaks = "SELECT * FROM dtrs_breaks WHERE emp_id = '$emp_id' && log_date = '$ddd'";
                                                $dan_dtr_breaks = $con->query($sql_dtr_breaks) or die ($con->error);
                                                $row_dtr_breaks = $dan_dtr_breaks->fetch_assoc();
                                                $count_breaks = mysqli_num_rows($dan_dtr_breaks);

                                                $dayy = date('l', strtotime($to));

                                                $dayys = date('D', strtotime($to));
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
                                                $hol_date = date('2023-m-d', strtotime($to));

                                                $sql_hol = "SELECT * FROM holidays WHERE datee = '$hol_date'";
                                                $dan_hol = $con->query($sql_hol) or die ($con->error);
                                                $row_hol = $dan_hol->fetch_assoc();
                                                $count_hol = mysqli_num_rows($dan_hol);

                                                // holiday with work
                                                if($count_hol > 0){
                                                    $hol_types = trim($row_hol['types']);
                                                }else{
                                                    $hol_types = "";
                                                }

                                                $remarks = "";

                                                    // day-off w/ Work
                                                    if($dayys == 'mon' && $mon == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "DAY-OFF";
                                                    }
                                                    elseif($dayys == 'tue' && $tue == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "DAY-OFF";
                                                    }
                                                    elseif($dayys == 'wed' && $wed == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "DAY-OFF";
                                                    }
                                                    elseif($dayys == 'thu' && $thu == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "DAY-OFF";
                                                    }
                                                    elseif($dayys == 'fri' && $fri == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "DAY-OFF";
                                                    }
                                                    elseif($dayys == 'sat' && $sat == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "DAY-OFF";
                                                    }
                                                    elseif($dayys == 'sun' && $sun == 'OFF' && empty($hol_types))
                                                    {
                                                        $remarks = "DAY-OFF";
                                                    }

                                                    $nn = 0;
                                                    // payroll
                                                    $sql_payrolls = "SELECT * FROM payrolls WHERE emp_id = '$emp_id' && posting = 'POSTED'";
                                                    $dan_payrolls = $con->query($sql_payrolls) or die ($con->error);
                                                    $row_payrolls = $dan_payrolls->fetch_assoc();
                                                    $count_payrolls = mysqli_num_rows($dan_payrolls);

                                                    if($count_payrolls > 0){
                                                        do{
                                                            $payroll_id = $row_payrolls['payroll_id'];
                                                            
                                                            if(!empty($payroll_id)){
                                                                // payroll history
                                                                $sql_payroll_history = "SELECT * FROM payroll_history WHERE `payroll_id` = '$payroll_id' && `status` = 'POSTED'";
                                                                $dan_payroll_history = $con->query($sql_payroll_history) or die ($con->error);
                                                                $row_payroll_history = $dan_payroll_history->fetch_assoc();
                                                                $count_payroll_history = mysqli_num_rows($dan_payroll_history);

                                                                if($count_payroll_history > 0){
                                                                    $from_payroll = $row_payroll_history['froms'];
                                                                    $to_payroll = $row_payroll_history['tos'];

                                                                    if(strtotime($to) >= strtotime($from_payroll) && strtotime($to) <= strtotime($to_payroll)){
                                                                        $nn++;
                                                                    }
                                                                }
                                                            }
                                                        }while($row_payrolls = $dan_payrolls->fetch_assoc());
                                                    }

                                            if($count < 1)
                                            {
                                                if(trim($remarks) != "DAY-OFF"){
                                                    $remarks = 'ABSENT';
                                                }

                                                ?>
                                                    <td>
                                                        <?php
                                                            if(strtotime($to) >= strtotime('now')){
                                                                echo '<a href="#" class="btn text-active btn-link btn-sm disabled" role="button" aria-disabled="true">'.date('m/d', strtotime($to)).'</a>';
                                                            }else{
                                                                if($nn > 0){
                                                                    echo '<a href="#" class="btn text-active btn-link btn-sm disabled" role="button" aria-disabled="true">'.date('m/d', strtotime($to)).'</a>';
                                                                }else{
                                                                    echo '<a href=javascript:void(); data-id="'.date('m/d/Y', strtotime($to)).'" class="btn text-active btn-link btn-sm editbtn" >'.date('m/d', strtotime($to)).'</a>';
                                                                }
                                                            }
                                                        ?>
                                                    </td>
                                                    <td><?= date('D', strtotime($to)) ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <!-- <td></td>
                                                    <td></td> -->
                                                    <td></td>
                                                    <td></td>
                                                    <td <?= $remarks == 'ABSENT' ? 'style= "color:red"' : '' ?>><?= $remarks." ".$hol_types?></td>
                                                <?php                                                                                                        
                                            }else{

                                                // lates
                                                $in_color1 = 'black';
                                                $in_color2 = 'black';
                                                $in_color3 = 'black';
                                                $in_color4 = 'black';
                                                $late1 = 0;
                                                $late2 = 0;
                                                $late3 = 0;
                                                $late4 = 0;

                                                $sched_in1 = $row_dtr['in1'];
                                                $sched_out1 = $row_dtr['out1'];
                                                $sched_in2 = $row_dtr['in2'];
                                                $sched_out2 = $row_dtr['out2'];

                                                $sched_work = 'OFF';

                                                if($mons == 'ON' && $dayys == 'mon'){
                                                    $sched_in1 = $mon_in1;
                                                    $sched_in2 = $mon_in2;
                                                    $sched_out1 = $mon_out1;
                                                    $sched_out2 = $mon_out2;

                                                    $sched_work = 'ON';
                                                    
                                                }

                                                if($tues == 'ON' && $dayys == 'tue'){
                                                    $sched_in1 = $tue_in1;
                                                    $sched_in2 = $tue_in2;
                                                    $sched_out1 = $tue_out1;
                                                    $sched_out2 = $tue_out2;

                                                    $sched_work = 'ON';
                                                }

                                                if($weds == 'ON' && $dayys == 'wed'){
                                                    $sched_in1 = $wed_in1;
                                                    $sched_in2 = $wed_in2;
                                                    $sched_out1 = $wed_out1;
                                                    $sched_out2 = $wed_out2;

                                                    $sched_work = 'ON';
                                                }

                                                if($thus == 'ON' && $dayys == 'thu'){
                                                    $sched_in1 = $thu_in1;
                                                    $sched_in2 = $thu_in2;
                                                    $sched_out1 = $thu_out1;
                                                    $sched_out2 = $thu_out2;

                                                    $sched_work = 'ON';
                                                }

                                                if($fris == 'ON' && $dayys == 'fri'){
                                                    $sched_in1 = $fri_in1;
                                                    $sched_in2 = $fri_in2;
                                                    $sched_out1 = $fri_out1;
                                                    $sched_out2 = $fri_out2;

                                                    $sched_work = 'ON';
                                                }

                                                if($sats == 'ON' && $dayys == 'sat'){
                                                    $sched_in1 = $sat_in1;
                                                    $sched_in2 = $sat_in2;
                                                    $sched_out1 = $sat_out1;
                                                    $sched_out2 = $sat_out2;

                                                    $sched_work = 'ON';

                                                }

                                                if($suns == 'ON' && $dayys == 'sun'){
                                                    $sched_in1 = $sun_in1;
                                                    $sched_in2 = $sun_in2;
                                                    $sched_out1 = $sun_out1;
                                                    $sched_out2 = $sun_out2;

                                                    $sched_work = 'ON';

                                                }

                                                if($sched_work == 'OFF'){
                                                    $working_schedule_remarks = 'DAY-OFF';
                                                }

                                                // custom schedules
                                                $sql_custom_sched = "SELECT * FROM sched_custom WHERE emp_id = '$emp_ids' && dated = '$ddd'";
                                                $dan_custom_sched = $con->query($sql_custom_sched) or die ($con->error);
                                                $row_custom_sched = $dan_custom_sched->fetch_assoc();
                                                $count_custom_sched = mysqli_num_rows($dan_custom_sched);

                                                if($count_custom_sched > 0 && !empty($row_custom_sched['in1']) && !empty($row_custom_sched['out1']) && !empty($row_custom_sched['in2']) && !empty($row_custom_sched['out2'])){
                                                    $sched_in1 = $row_custom_sched['in1'];
                                                    $sched_out1 = $row_custom_sched['out1'];
                                                    $sched_in2 = $row_custom_sched['in2'];
                                                    $sched_out2 = $row_custom_sched['out2'];
                                                    $sched_work = "ON";

                                                    if(strtotime($row_dtr['in1']) > strtotime($sched_in1)){
                                                        $in_color1 = 'red';
                                                    }else{
                                                        $in_color1 = '';
                                                    }
                                                    if(strtotime($row_dtr['in2']) > strtotime($sched_in2)){
                                                        $in_color2 = 'red';
                                                    }else{
                                                        $in_color2 = '';
                                                    }

                                                    $custom_sched = 'YES';
                                                    $custom_sched_created = $row_custom_sched['created_by'];
                                                }else{
                                                    $custom_sched = '';
                                                    $custom_sched_created = '';
                                                }

                                                $sched_in1 = date('h:i A', strtotime($sched_in1));
                                                $sched_in2 = date('h:i A', strtotime($sched_in2));

                                                $scheds_in1 = $sched_in1;
                                                $scheds_in2 = $sched_in2;

                                                // number of hours work
                                                $hour1 = 0;
                                                $hour2 = 0;
                                                $hour3 = 0;
                                                $hour4 = 0;
                                                $ot_hour = 0;

                                                $in1 = $row_dtr['in1'];
                                                $out1 = $row_dtr['out1'];
                                                $in2 = $row_dtr['in2'];
                                                $out2 = $row_dtr['out2'];
                                                $in3 = empty($row_dtr['in3']) && !empty($row_dtr['in4']) || $row_dtr['in3'] == '00:00' && !empty($row_dtr['in4']) ? $row_dtr['in4'] : $row_dtr['in3'];
                                                $out3 = empty($row_dtr['out3']) && !empty($row_dtr['out4']) || $row_dtr['out3'] == '00:00' && !empty($row_dtr['out4']) ? $row_dtr['out4'] : $row_dtr['out3'];
                                                $in4 = empty($row_dtr['in3']) && !empty($row_dtr['in4']) || $row_dtr['in3'] == '00:00' && !empty($row_dtr['in4']) ? '' : $row_dtr['in4'];
                                                $out4 = empty($row_dtr['out3']) && !empty($row_dtr['out4']) || $row_dtr['out3'] == '00:00' && !empty($row_dtr['out4']) ? '' : $row_dtr['out4'];

                                                $in1 == '00:00' ? $in1 = '' : $in1 = $in1;
                                                $out1 == '00:00' ? $out1 = '' : $out1 = $out1;
                                                $in2 == '00:00' ? $in2 = '' : $in2 = $in2;
                                                $out2 == '00:00' ? $out2 = '' : $out2 = $out2;
                                                $in3 == '00:00' ? $in3 = '' : $in3 = $in3;
                                                $out3 == '00:00' ? $out3 = '' : $out3 = $out3;
                                                $in4 == '00:00' ? $in4 = '' : $in4 = $in4;
                                                $out4 == '00:00' ? $out4 = '' : $out4 = $out4;


                                                $hrs_total = 0;
                                                $ut1 = 0;
                                                $ut2 = 0;
                                                $ut3 = 0;
                                                $ut4 = 0;
                                                $ut_day = 0;
                                                // with schedule
                                                if($sched_work == 'ON'){

                                                    // lates
                                                    // late 1
                                                            if(strtotime($in1) > strtotime($scheds_in1) && strtotime($in1) < strtotime($sched_out1)){
                                                                $in_color1 = 'red';
                                                                $late1 = (strtotime($in1) - strtotime($scheds_in1)) / 3600;
                                                                $late1 = $late1 * 60;
                                                                $late1 = round($late1);
                                                            }elseif(strtotime($in1) > strtotime($scheds_in2) && strtotime($in1) < strtotime($sched_out2)){
                                                                $in_color1 = 'red';
                                                                $late1 = (strtotime($in1) - strtotime($scheds_in2)) / 3600;
                                                                $late1 = $late1 * 60;
                                                                $late1 = round($late1);
                                                            }

                                                            // late 2
                                                            if(strtotime($in2) > strtotime($scheds_in2) && strtotime($in2) < strtotime($sched_out2)){
                                                                $in_color2 = 'red';
                                                                $late2 = (strtotime($in2) - strtotime($scheds_in2)) / 3600;
                                                                $late2 = $late2 * 60;
                                                                $late2 = round($late2);
                                                            }

                                                            // late 3
                                                            if(strtotime($in3) > strtotime($scheds_in2) && strtotime($in3) < strtotime($sched_out2)){
                                                                $in_color3 = 'red';
                                                                $late3 = (strtotime($in3) - strtotime($scheds_in2)) / 3600;
                                                                $late3 = $late3 * 60;
                                                                $late3 = round($late3);
                                                            }

                                                            // late 4
                                                            if(strtotime($in4) > strtotime($scheds_in2) && strtotime($in4) < strtotime($sched_out2)){
                                                                $in_color4 = 'red';
                                                                $late4 = (strtotime($in4) - strtotime($scheds_in2)) / 3600;
                                                                $late4 = $late4 * 60;
                                                                $late4 = round($late4);
                                                            }

                                                        // in1 out1
                                                        if(!empty($out1) && !empty($in1)){
                                                            // if(strtotime($in1) <= strtotime($sched_in1) && strtotime($in1) < strtotime($sched_out1) || strtotime($in1) >= strtotime($sched_in1) && strtotime($in1) < strtotime($sched_out1)){
                                                                // early in
                                                                $adv1 = strtotime($sched_in1);
                                                                // $adv1 = date('h:i A', strtotime("- 15 minutes", $adv1));

                                                                // early in schedule
                                                                if(strtotime($in1) <= strtotime($sched_in1)){
                                                                    $sched_in1 = date('h:i A', strtotime($sched_in1));
                                                                }else{
                                                                    $sched_in1 = date('h:i A', strtotime($in1));
                                                                }

                                                                // undertime in out1
                                                                if(strtotime($out1) < strtotime($sched_out1)){
                                                                    $hour1 = (strtotime($out1) - strtotime($sched_in1)) / 3600;
                                                                    $ut1 = (strtotime($sched_out1) - strtotime($out1)) / 3600;
                                                                    $ut1 = round($ut1 * 60);
                                                                }elseif(strtotime($out1) >= strtotime($sched_out1) && strtotime($out1) <= strtotime($sched_in2)){ // on schedule
                                                                    $hour1 = (strtotime($sched_out1) - strtotime($sched_in1)) / 3600;
                                                                }elseif(strtotime($out1) > strtotime($sched_in2) && strtotime($out1) < strtotime($sched_out2)){ // no lunch break but undertime in out2
                                                                    $hour1 = (strtotime($out1) - strtotime($sched_in1)) / 3600;
                                                                    if(strtotime($out1) < strtotime($sched_out2) && strtotime($in2) >= strtotime($sched_out2) || strtotime($out1) < strtotime($sched_out2) && empty($in2)){ // undertime at 2nd out
                                                                        $ut1 = (strtotime($sched_out2) - strtotime($out1)) / 3600;
                                                                        $ut1 = round($ut1 * 60);
                                                                    }elseif(strtotime($out1) < strtotime($sched_out2) && strtotime($in2) < strtotime($sched_out2)){
                                                                        if(!empty($out2)){
                                                                            $ut1 = (strtotime($in2) - strtotime($out1)) / 3600;
                                                                            $ut1 = round($ut1 * 60);
                                                                        }else{
                                                                            $ut1 = (strtotime($sched_out2) - strtotime($out1)) / 3600;
                                                                            $ut1 = round($ut1 * 60);
                                                                        }
                                                                    }
                                                                }elseif(strtotime($out1) >= strtotime($sched_out2)){ // no lunch break and time-out greater than sched out2
                                                                    // early in
                                                                    if(strtotime($in1) <= strtotime($sched_in2) && strtotime($in1) >= strtotime($sched_out1)){
                                                                        $sched_in1 = date('h:i A', strtotime($sched_in2));
                                                                    }else{
                                                                        $sched_in1 = date('h:i A', strtotime($in1));
                                                                    }
                                                                    $con_ot = 0;
                                                                    $hour1 = (strtotime($sched_out2) - strtotime($sched_in1)) / 3600;
                                                                    $con_ot = (strtotime($out1) - strtotime($sched_out2)) / 3600;
                                                                    // with ot
                                                                    if($con_ot >= 0.25){
                                                                        $ot_hour = round($con_ot,2);
                                                                    }
                                                                }
                                                                $hour1 = round($hour1,2);
                                                            // }
                                                        }

                                                        // in2 out2
                                                        if(!empty($out2) && !empty($in2)){
                                                            // if(strtotime($in2) <= strtotime($sched_in2) && strtotime($in2) < strtotime($sched_out2) || strtotime($in2) >= strtotime($sched_in2) && strtotime($in2) < strtotime($sched_out2)){
                                                                // early in
                                                                if(strtotime($in2) <= strtotime($sched_in2) && strtotime($in2) >= strtotime($sched_out1)){
                                                                    $sched_in2 = date('h:i A', strtotime($sched_in2));
                                                                }else{
                                                                    $sched_in2 = date('h:i A', strtotime($in2));
                                                                }

                                                                // undertime at out1 and time-IN again in 2
                                                                if(strtotime($out2) >= strtotime($sched_out1) && strtotime($in2) < strtotime($sched_out1) && strtotime($out2) <= strtotime($sched_in2)){
                                                                    $hour2 = (strtotime($sched_out1) - strtotime($in2)) / 3600;
                                                                }elseif(strtotime($out2) < strtotime($sched_out1)){ // undertime at out1 but time-out in out2
                                                                    $hour2 = (strtotime($out2) - strtotime($in2)) / 3600;
                                                                }elseif(strtotime($out2) > strtotime($sched_in2)){  // undertime at out1 and overtime or in schedule at out2
                                                                    $con_ot = 0;
                                                                    $hour2 = (strtotime($out2) - strtotime($sched_in2)) / 3600;
                                                                    $con_ot = (strtotime($out2) - strtotime($sched_out2)) / 3600;
                                                                    // with ot
                                                                    if($con_ot >= 0.25){
                                                                        $con_ot = round($con_ot,2);
                                                                        $ot_hour = $ot_hour + $con_ot;
                                                                        $hour2 = (strtotime($sched_out2) - strtotime($sched_in2)) / 3600;
                                                                    }elseif($con_ot < 0.25 && $con_ot > 0){
                                                                        $hour2 = (strtotime($sched_out2) - strtotime($sched_in2)) / 3600;
                                                                    }
                                                                }
                                                                $hour2 = round($hour2,2);
                                                            // }

                                                            // undertime 
                                                            if(strtotime($in2) < strtotime($sched_out1) && strtotime($out1) < strtotime($sched_out1)){ // undertime at out1
                                                                $ut1 = 0;
                                                                $ut1 = (strtotime($in2) - strtotime($out1)) / 3600;
                                                                $ut1 = round($ut1 * 60);
                                                            }

                                                            // undertime at out2
                                                            if(strtotime($out2) < strtotime($sched_out2) && strtotime($out2) > strtotime($scheds_in2)){
                                                                $ut2 = (strtotime($sched_out2) - strtotime($out2)) / 3600;
                                                                $ut2 = round($ut2 * 60);
                                                            }
                                                        }


                                                        // in3 out3
                                                        if(!empty($out3) && !empty($in3)){
                                                            $sched_in3 = '';
                                                            $sched_out3 = '';
                                                            // at 2nd OUT Sched
                                                            if(strtotime($in3) < strtotime($sched_out2) && strtotime($in3) > strtotime($sched_out1)){
                                                                // with ot at IN schedule
                                                                if(strtotime($in3) <= strtotime($scheds_in2)){
                                                                    // early in
                                                                    if(strtotime($in3) <= strtotime($scheds_in2) && strtotime($in3) >= strtotime($sched_out1)){
                                                                        $sched_in2 = date('h:i A', strtotime($scheds_in2));
                                                                    }else{
                                                                        $sched_in2 = date('h:i A', strtotime($in2));
                                                                    }
                                                                    $con_ot = 0;
                                                                    $hour3 = (strtotime($out3) - strtotime($sched_in2)) / 3600;
                                                                    $con_ot = (strtotime($out3) - strtotime($sched_out2)) / 3600;
                                                                    // with ot
                                                                    if($con_ot >= 0.25){
                                                                        $con_ot = round($con_ot,2);
                                                                        $ot_hour = $ot_hour + $con_ot;
                                                                        $hour3 = (strtotime($sched_out2) - strtotime($sched_in2)) / 3600;
                                                                    }elseif($con_ot < 0.25 && $con_ot > 0){
                                                                        $hour3 = (strtotime($sched_out2) - strtotime($sched_in2)) / 3600;
                                                                    }
                                                                }else{
                                                                    if(strtotime($out2) < strtotime($sched_out2) && strtotime($out2) > strtotime($scheds_in2)){
                                                                        $late3 = 0;
                                                                        $con_ot = 0;
                                                                        $hour3 = (strtotime($out3) - strtotime($in3)) / 3600;
                                                                        $con_ot = (strtotime($out3) - strtotime($sched_out2)) / 3600;
                                                                        // with ot
                                                                        if($con_ot >= 0.25){
                                                                            $con_ot = round($con_ot,2);
                                                                            $ot_hour = $ot_hour + $con_ot;
                                                                            $hour3 = (strtotime($sched_out2) - strtotime($in3)) / 3600;
                                                                            // $hour3 = (strtotime($sched_out2) - strtotime($scheds_in2)) / 3600;
                                                                        }elseif($con_ot < 0.25 && $con_ot > 0){
                                                                            $hour3 = (strtotime($sched_out2) - strtotime($in3)) / 3600;
                                                                        }
                                                                    }else{
                                                                        // early in
                                                                        if(strtotime($in3) <= strtotime($scheds_in2) && strtotime($in3) >= strtotime($sched_out1)){
                                                                            $sched_in2 = date('h:i A', strtotime($scheds_in2));
                                                                        }else{
                                                                            $sched_in2 = date('h:i A', strtotime($in2));
                                                                        }

                                                                        $con_ot = 0;
                                                                        // $hour3 = (strtotime($out3) - strtotime($in3)) / 3600;
                                                                        $con_ot = (strtotime($out3) - strtotime($sched_out2)) / 3600;
                                                                        // with ot
                                                                        if($con_ot >= 0.25){
                                                                            $con_ot = round($con_ot,2);
                                                                            $ot_hour = $ot_hour + $con_ot;
                                                                            $hour3 = (strtotime($sched_out2) - strtotime($in3)) / 3600;
                                                                            // $hour3 = (strtotime($sched_out2) - strtotime($scheds_in2)) / 3600;
                                                                        }elseif($con_ot < 0.25 && $con_ot > 0){
                                                                            $hour3 = (strtotime($sched_out2) - strtotime($in3)) / 3600;
                                                                        }
                                                                    }
                                                                }
                                                            }else{
                                                                // ot
                                                                if(strtotime($out3) > strtotime($sched_out2)){
                                                                    $hour3 = 0;
                                                                    $con_ot = 0;
                                                                    $hour3 = (strtotime($out3) - strtotime($in3)) / 3600;
                                                                    $con_ot = (strtotime($out3) - strtotime($sched_out2)) / 3600;
                                                                    // with ot
                                                                    if($con_ot >= 0.25){
                                                                        $con_ot = (strtotime($out3) - strtotime($in3)) / 3600;
                                                                        $con_ot = round($con_ot,2);
                                                                        $ot_hour = $ot_hour + $con_ot;
                                                                        // $hour3 = (strtotime($out3) - strtotime($in3)) / 3600;
                                                                        $hour3 = 0;
                                                                    }elseif($con_ot < 0.25 && $con_ot > 0){
                                                                        $hour3 = 0;
                                                                        // $hour3 = (strtotime($sched_out2) - strtotime($in3)) / 3600;
                                                                    }
                                                                }else{
                                                                    $hour3 = (strtotime($out3) - strtotime($in3)) / 3600;
                                                                    $ot_hour = $ot_hour + round($hour3,2);
                                                                    $hour3 = 0;
                                                                }
                                                            }

                                                            $hour3 = round($hour3,2);

                                                           // undertime 
                                                           if(strtotime($in3) < strtotime($sched_out2) && strtotime($in2) > strtotime($sched_out1)){ // undertime at out2
                                                                $ut2 = 0;
                                                                $ut2 = (strtotime($in3) - strtotime($out2)) / 3600;
                                                                $ut2 = round($ut2 * 60);
                                                            }

                                                            // undertime at out3
                                                            if(strtotime($out3) < strtotime($sched_out2) && strtotime($out3) > strtotime($scheds_in2)){
                                                                $ut3 = (strtotime($sched_out2) - strtotime($out3)) / 3600;
                                                                $ut3 = round($ut3 * 60);
                                                            }
                                                        }


                                                        // in4 out4
                                                        if(!empty($out4) && !empty($in4)){
                                                            $sched_in4 = '';
                                                            $sched_out4 = '';
                                                            // at 2nd OUT Sched
                                                            if(strtotime($in4) < strtotime($sched_out2) && strtotime($in4) > strtotime($sched_out1)){
                                                                // with ot at IN schedule
                                                                if(strtotime($in4) <= strtotime($scheds_in2)){
                                                                    $con_ot = 0;
                                                                    $hour4 = (strtotime($out4) - strtotime($scheds_in2)) / 3600;
                                                                    $con_ot = (strtotime($out4) - strtotime($sched_out2)) / 3600;
                                                                    // with ot
                                                                    if($con_ot >= 0.25){
                                                                        $con_ot = round($con_ot,2);
                                                                        $ot_hour = $ot_hour + $con_ot;
                                                                        $hour4 = (strtotime($sched_out2) - strtotime($scheds_in2)) / 3600;
                                                                    }elseif($con_ot < 0.25 && $con_ot > 0){
                                                                        $hour4 = (strtotime($sched_out2) - strtotime($sched_in2)) / 3600;
                                                                    }
                                                                }else{
                                                                    $con_ot = 0;
                                                                    $hour4 = (strtotime($out4) - strtotime($in4)) / 3600;
                                                                    $con_ot = (strtotime($out4) - strtotime($sched_out2)) / 3600;
                                                                    // with ot
                                                                    if($con_ot >= 0.25){
                                                                        $con_ot = round($con_ot,2);
                                                                        $ot_hour = $ot_hour + $con_ot;
                                                                        $hour4 = (strtotime($sched_out2) - strtotime($scheds_in2)) / 3600;
                                                                    }elseif($con_ot < 0.25 && $con_ot > 0){
                                                                        $hour4 = (strtotime($sched_out2) - strtotime($sched_in2)) / 3600;
                                                                    }
                                                                }
                                                            }else{
                                                                // ot
                                                                $hour4 = (strtotime($out4) - strtotime($in4)) / 3600;
                                                                $ot_hour = $ot_hour + round($hour4,2);
                                                                $hour4 = 0;
                                                            }

                                                            $hour4 = round($hour4,2);
                                                        }

                                                        $hrs_total = $hour1 + $hour2 + $hour3 + $hour4;
                                                        if($hrs_total >= 8.25){
                                                            $ot_hour_p = $hrs_total - 8.25; 
                                                            $hrs_total = 8.25;
                                                            $ot_hour = $ot_hour + $ot_hour_p;

                                                            if($ot_hour >= 0.25){
                                                                $ot_hour = $ot_hour;
                                                            }else{
                                                                $ot_hour = 0;
                                                            }
                                                        }


                                                }else{
                                                        if(!empty($in1) && !empty($out1)){
                                                            $hour1 = (strtotime($out1) - strtotime($in1)) / 3600;
                                                            $hour1 = round($hour1,2);
                                                        }
                                                        if(!empty($in2) && !empty($out2)){
                                                            $hour2 = (strtotime($out2) - strtotime($in2)) / 3600;
                                                            $hour2 = round($hour2,2);
                                                        }
                                                        if(!empty($in3) && !empty($out3)){
                                                            $hour3 = (strtotime($out3) - strtotime($in3)) / 3600;
                                                            $hour3 = round($hour3,2);
                                                        }
                                                        if(!empty($in4) && !empty($out4)){
                                                            $hour4 = (strtotime($out4) - strtotime($in4)) / 3600;
                                                            $hour4 = round($hour4,2);
                                                        }

                                                        $hrs_total = $hour1 + $hour2 + $hour3 + $hour4;

                                                        $hr_c = 0;
                                                        if($hrs_total > 8){
                                                            $hr_c = $hrs_total - 8;
                                                            $hrs_total = 8;
                                                            $ot_hour = $ot_hour + $hr_c;

                                                            if($ot_hour >= 0.25){
                                                                $ot_hour = $ot_hour;
                                                            }else{
                                                                $ot_hour = 0;
                                                            }
                                                        }


    
                                                }


                                                // <!-- breaktime -->
                                                $bt_lates_d = 0;
                                                if($count_breaks > 0){
                                                    $mins_break = 0;
                                                    $mins_break2 = 0;
                                                    // $mins_break3 = 0;
                                                        if($row_dtr_breaks['in1'] != '00:00'){
                                                            $b_in1 = $row_dtr_breaks['in1'];
                                                        }else{
                                                            $b_in1 = '';
                                                        }
                                                        if($row_dtr_breaks['in2'] != '00:00'){
                                                            $b_in2 = $row_dtr_breaks['in2'];
                                                        }else{
                                                            $b_in2 = '';
                                                        }
                                                        // if($row_dtr_breaks['in3'] != '00:00'){
                                                        //     $b_in3 = $row_dtr_breaks['in3'];
                                                        // }else{
                                                        //     $b_in3 = '';
                                                        // }
                                                        if($row_dtr_breaks['out1'] != '00:00'){
                                                            $b_out1 = $row_dtr_breaks['out1'];
                                                            $mins_break = abs(strtotime($b_out1) - strtotime($b_in1))/3600; 
                                                            $mins_break = $mins_break * 60;
                                                            $mins_break = round($mins_break);
                                                        }else{
                                                            $b_out1 = '';
                                                        }
                                                        if($row_dtr_breaks['out2'] != '00:00'){
                                                            $b_out2 = $row_dtr_breaks['out2'];
                                                            $mins_break2 = abs(strtotime($b_out2) - strtotime($b_in2))/3600;
                                                            $mins_break2 = $mins_break2 * 60;
                                                            $mins_break2 = round($mins_break2);
                                                        }else{
                                                            $b_out2 = '';
                                                        }
                                                        // if($row_dtr_breaks['out3'] != '00:00'){
                                                        //     $b_out3 = $row_dtr_breaks['out3'];
                                                        //     $mins_break3 = abs(strtotime($b_out3) - strtotime($b_in3))/3600; 
                                                        //     $mins_break3 = $mins_break3 * 60;
                                                        //     $mins_break3 = round($mins_break3);
                                                        // }else{
                                                        //     $b_out3 = '';
                                                        // }

                                                        // $bt_lates_d = floatval($row_dtr_breaks['lates']);

                                                        $mins_break = $mins_break + $mins_break2;

                                                        $bt_lates_d = $mins_break - $breaks;

                                                        if($bt_lates_d > 0){
                                                            $bt_color = "red";
                                                        }else{
                                                            $bt_color = "black";
                                                            $bt_lates_d = 0;
                                                        }

                                                }

                                                // bt late deductions
                                                $late_in_bt = $bt_lates_d / 60;
                                                $hrs_total = $hrs_total - $late_in_bt;
                                                $hrs_total = round($hrs_total,2);

                                                // total hours
                                                $total_hrs = $total_hrs + $hrs_total;
                                                $total_ot = $total_ot + $ot_hour;
                                                
                                                if($row_dtr['ot_approved'] == 'Approved'){
                                                    $total_hrs = $total_hrs + $ot_hour;
                                                    $hrs_total = $hrs_total + $ot_hour;
                                                }

                                                $dtrID = $row_dtr['ID'];

                                                $c_in1 = date('h:i A', strtotime($scheds_in1));
                                                $c_in2 = date('h:i A', strtotime($scheds_in2));
                                                $c_out1 = date('h:i A', strtotime($sched_out1));
                                                $c_out2 = date('h:i A', strtotime($sched_out2));

                                                $late_day = $late1 + $late2 + $late3 + $late4;
                                                $ut_day = $ut1 + $ut2 + $ut3 + $ut4;
                                                // if($ut_day < 2){ // less than 15 minutes undertime
                                                //     $ut_day = 0;
                                                // }

                                                $total_reg_late = $total_reg_late + $late_day;
                                                $total_ut = $total_ut + $ut_day;

                                                if($row_dtr['posting'] != 'POSTED'){
                                                    $sql_update_hr =  "UPDATE `dtrs` SET `in3` = '$in3', `out3` = '$out3', `in4` = '$in4', `out4` = '$out4',
                                                                        `total_hrs` = '$hrs_total', `total_ot` = '$ot_hour', 
                                                                        `c_in1` = '$c_in1', `c_out1` = '$c_out1', `c_in2` = '$c_in2', `c_out2` = '$c_out2',
                                                                        `created_by` = '$custom_sched_created', `custom_sched` = '$custom_sched',
                                                                        `in_lates` = '$late_day', `late1` = '$late1', `late2` = '$late2', `late3` = '$late3', `late4` = '$late4',
                                                                        `ut_times` = '$ut_day', `ut1` = '$ut1', `ut2` = '$ut2', `ut3` = '$ut3', `ut4` = '$ut4'
                                                                        WHERE `dtrs`.`ID` = '$dtrID'";
                                                    $update_hr = ($con->query($sql_update_hr) or die ($con->error));
                                                }
                                            ?>
                                                <td>
                                                    <?php
                                                        if(strtotime($to) >= strtotime('now')){
                                                            echo '<a href="#" class="btn text-active btn-link btn-sm disabled" role="button" aria-disabled="true">'.date('m/d', strtotime($to)).'</a>';
                                                        }else{
                                                            if($row_dtr['posting'] == 'POSTED'){
                                                                echo '<a href="#" class="btn text-active btn-link btn-sm disabled" role="button" aria-disabled="true">'.date('m/d', strtotime($to)).'</a>';
                                                            }else{
                                                                echo '<a href=javascript:void(); data-id="'.$row_dtr['ID'].'" class="btn text-active btn-link btn-sm editbtn" >'.date('m/d', strtotime($to)).'</a>';
                                                            }
                                                        }
                                                    ?>
                                                </td>
                                                <td><?= date('D', strtotime($to)) ?></td>
                                                <td style="color: <?= $in_color1?>"><?= $in1?></td>
                                                <td <?= $ut1 > 0 ? 'class="table-danger"' : '' ?>><?= $out1 ?></td>
                                                <td style="color: <?= $in_color2?>"><?= $in2?></td>
                                                <td <?= $ut2 > 0 ? 'class="table-danger"' : '' ?>><?= $out2 ?></td>
                                                <td style="color: <?= $in_color3?>"><?= $in3 ?></td>
                                                <td <?= $ut3 > 0 ? 'class="table-danger"' : '' ?>><?= $out3 ?></td>
                                                <td style="color: <?= $in_color4?>"><?= $in4 ?></td>
                                                <td <?= $ut4 > 0 ? 'class="table-danger"' : '' ?>><?= $out4 ?></td>
                                                <td <?= $hrs_total <= 7.5 && $hrs_total > 0 ? 'class="table-danger"' : '' ?>><b><?= $hrs_total?></b></td>
                                                <td><?php
                                                    if($remarks == 'DAY-OFF'){
                                                        if(empty($row_dtr['off_status']) || $row_dtr['off_status'] == 'Pending'){
                                                            echo '<a href=javascript:void(); data-id="'.$row_dtr['ID'].'" class="off-work-btn badge badge-warning">Pending...</a>';
                                                        }elseif($row_dtr['off_status'] == 'Approved'){
                                                            echo '<a href=javascript:void(); data-id="'.$row_dtr['ID'].'" class="off-work-btn badge badge-success">&check; Approved</a>';
                                                        }elseif($row_dtr['off_status'] == 'Cancelled'){
                                                            echo '<a href=javascript:void(); data-id="'.$row_dtr['ID'].'" class="off-work-btn badge badge-danger">&times; Cancelled</a>';
                                                        }
                                                    }
                                                ?></td>
                                                <td><b><?= $ot_hour > 0 ? $ot_hour : '' ?></b></td>
                                                <td><?php
                                                    if($row_dtr['posting'] != 'POSTED'){
                                                        if(empty($row_dtr['ot_approved']) && $ot_hour <= 0 || $row_dtr['ot_approved'] == 'Cancelled' && $ot_hour <= 0){
                                                           echo "";
                                                        }elseif($row_dtr['ot_approved'] == 'Approved'){
                                                            ?>
                                                            <a href=javascript:void(); data-id="<?=$row_dtr['ID']?>" class="overtimebtn badge badge-success">&check; Approved</a>
                                                            <?php
                                                        }elseif(empty($row_dtr['ot_approved']) && $ot_hour > 0 || $row_dtr['ot_approved'] == 'Pending'){
                                                            ?>
                                                            <a href=javascript:void(); data-id="<?=$row_dtr['ID']?>" class="overtimebtn badge badge-warning">Pending...</a>
                                                            <?php
                                                        }elseif($row_dtr['ot_approved'] == 'Cancelled' && $ot_hour > 0){
                                                            ?>
                                                            <a href=javascript:void(); data-id="<?=$row_dtr['ID']?>" class="overtimebtn badge badge-danger">&times; Cancelled</a>
                                                            <?php
                                                        }
                                                    }
                                                    ?></td>
                                                    <!-- breaktime -->
                                                    <?php
                                                    if($count_breaks > 0){
                                                    ?>
                                                        <td style="color: <?= $bt_color?>"><?= $b_in1?></td>
                                                        <td style="color: <?= $bt_color?>"><?= $b_out1?></td>
                                                        <td style="color: <?= $bt_color?>"><?= $b_in2?></td>
                                                        <td style="color: <?= $bt_color?>"><?= $b_out2?></td>
                                                        <!-- <td style="color: <?= $bt_color?>"><?= $b_in3?></td>
                                                        <td style="color: <?= $bt_color?>"><?= $b_out3?></td> -->
                                                        <td style="color: <?= $bt_color?>"><?php
                                                            if($bt_lates_d > 0){
                                                                echo '<b style="color: red">'.$mins_break.'</b>';
                                                                $mins_late = $mins_break - $breaks;
                                                                $bt_late = $bt_late + $bt_lates_d;
                                                            }else{
                                                                echo $mins_break;
                                                            }
                                                        ?></td>
                                                    <?php
                                                }else{
                                                    ?>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <!-- <td></td>
                                                        <td></td> -->
                                                    <?php
                                                }
                                                ?>
                                                <td><?= $remarks." ".$hol_types?></td>
                                            <?php
                                            }
                                        ?>
                                        </tr>
                                        <?php
                                        $to = date('m/d/Y', strtotime($to . " +1 day"));
                                    }while(strtotime($to) <= strtotime($from));
                                ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="" style="text-align: right;">Worked Days</th>
                                <th><?= round(($total_hrs/8),2) ?></th>
                                <th colspan="2" style="text-align: right;">Total Working Hours</th>
                                <th style="text-align: center;"><?= round($total_hrs,2)?></th>
                                <th colspan="2" style="text-align: right;">Undertime</th>
                                <th style="text-align: center; color:red;"><?php 
                                    if($total_ut == 0){
                                        echo '-';
                                    }elseif(intdiv($total_ut, 60) > 0 && ($total_ut % 60) > 0){
                                        echo intdiv($total_ut, 60).' hr(s). & '. ($total_ut % 60).' min(s).'; 
                                    }elseif(intdiv($total_ut, 60) > 0 && ($total_ut % 60) == 0){
                                        echo intdiv($total_ut, 60).' hr(s).'; 
                                    }elseif(intdiv($total_ut, 60) == 0 && ($total_ut % 60) > 0){
                                        echo ($total_ut % 60).' min(s).';
                                    }
                                    ?>
                                </th>
                                <th colspan="3" style="text-align: right;">Total Mins. Late</th>
                                <th style="text-align: center; color:red;"><?= $total_reg_late + $bt_late?> min(s).</th>
                                <th colspan="2" style="text-align: right;">Late in Reg. Sched.</th>
                                <th style="color:red;"><?= $total_reg_late.' mins.'?></th>
                                <th colspan="1" style="text-align: right;">Mins. Late in BT</th>
                                <th style="text-align: center; color:red;"><?= $bt_late?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                <?php
                // unset($_SESSION['dtr_date']);
                } 
                ?>
            </div>
        </div>
<!--  -->
</div>
    </div>
</main>

<?php
include("includes/footer.php");
?>
<!-- <script src="js/datatables/jquery.dataTables.min.js"></script> -->
<!-- <script src="js/datatables/dataTables.bootstrap4.min.js"></script> -->

<!-- <script src="js/datatables/dataTables.fixedColumns.min.js"></script> -->
<!-- modal -->
<!-- <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script> -->

<!-- <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/r-2.2.3/datatables.min.js"></script> -->

<script>

// Display Edit Modal
$('#dtr_table').on('click', '.editbtn', function(event) {
      var table = $('#dtr_table').DataTable();
      var trid = $(this).closest('tr').attr('id');
        //console.log(selectedRow);
      var id = $(this).data('id');
      $('#editModal').modal('show');

      $.ajax({
        url: "get_single_data_dtr.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#d1').val(json.in1);
          $('#d2').val(json.out1);
          $('#d3').val(json.in2);
          $('#d4').val(json.out2);
          $('#d5').val(json.in3);
          $('#d6').val(json.out3);
          $('#d7').val(json.in4);
          $('#d8').val(json.out4);
          $('#dated').val(json.log_date);
            $('#d1_b').val(json.in1_sched);
            $('#d2_b').val(json.out1_sched);
            $('#d3_b').val(json.in2_sched);
            $('#d4_b').val(json.out2_sched);
            $('#id_b').val(id);
            $('#dated_b2').val(json.log_date);
            $('#created_by').val(json.created_by_sched);
            $('#sched_remarks').val(json.sched_remarks);
         $('#updated_time').val(json.updated_time);
          $('#id').val(id);
        //   $('#trid').val(trid);
        }
      });
});

// Display Overtime Modal
$('#dtr_table').on('click', '.overtimebtn', function(event) {
      var table = $('#dtr_table').DataTable();
      var trid = $(this).closest('tr').attr('id');
        //console.log(selectedRow);
      var id = $(this).data('id');
      $('#overtimeModal').modal('show');

      $.ajax({
        url: "get_single_data_dtr.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#d7_a').val(json.in4);
          $('#d8_a').val(json.out4);
          $('#dated_a').val(json.log_date);
          $('#otHrs').val(json.total_ot);
          $('#ot_approved').val(json.ot_approved);
          $('#updated_by').val(json.updated_by);
          $('#id_a').val(id);
        //   $('#trid').val(trid);
        }
      });
    });


// Display Off w/ work Modal
$('#dtr_table').on('click', '.off-work-btn', function(event) {
      var table = $('#dtr_table').DataTable();
      var trid = $(this).closest('tr').attr('id');
        //console.log(selectedRow);
      var id = $(this).data('id');
      $('#off-work-Modal').modal('show');

      $.ajax({
        url: "get_single_data_dtr.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
        //   $('#d7_a').val(json.in4);
        //   $('#d8_a').val(json.out4);
          $('#dated_b').val(json.log_date);
          $('#off_hrs').val(json.total_hrs);
          $('#off_status').val(json.off_status);
          $('#off_updated_by').val(json.off_approved);
          $('#id_off').val(id);
        //   $('#trid').val(trid);
        }
      });
    });

// Display Custom Sched Modal
$('#dtr_table').on('click', '.schedbtn', function(event) {
      var table = $('#dtr_table').DataTable();
      var trid = $(this).closest('tr').attr('id');
        //console.log(selectedRow);
      var id = $(this).data('id');
      
      $('#schedModal').modal('show');

      $.ajax({
        url: "get_employee_schedule.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
            $('#d1_b').val(json.in1);
            $('#d2_b').val(json.out1);
            $('#d3_b').val(json.in2);
            $('#d4_b').val(json.out2);
            $('#dated_b').val(json.log_date);
            $('#dated_b2').val(json.log_date);
            $('#created_by').val(json.created_by);
            $('#id_b').val(id);
            //   $('#trid').val(trid);
        }
      });
    });



    let table = new DataTable('#dtr_table',{
        fixedColumns: {
        left: 2,
        right: 1
        },
        // fixedColumns: true,
        paging: false,
        pageLength : 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
        scrollCollapse: true,
        scrollX: true,
        scrollY: 400,
    });


    $('#mon_in1').change(function() {
        $('#tue_in1').val($('#mon_in1').val())
        $('#wed_in1').val($('#mon_in1').val())
        $('#thu_in1').val($('#mon_in1').val())
        $('#fri_in1').val($('#mon_in1').val())
        $('#sat_in1').val($('#mon_in1').val())
        $('#sun_in1').val($('#mon_in1').val())
    })

    $('#mon_out1').change(function() {
        $('#tue_out1').val($('#mon_out1').val())
        $('#wed_out1').val($('#mon_out1').val())
        $('#thu_out1').val($('#mon_out1').val())
        $('#fri_out1').val($('#mon_out1').val())
        $('#sat_out1').val($('#mon_out1').val())
        $('#sun_out1').val($('#mon_out1').val())
    })

    $('#mon_in2').change(function() {
        $('#tue_in2').val($('#mon_in2').val())
        $('#wed_in2').val($('#mon_in2').val())
        $('#thu_in2').val($('#mon_in2').val())
        $('#fri_in2').val($('#mon_in2').val())
        $('#sat_in2').val($('#mon_in2').val())
        $('#sun_in2').val($('#mon_in2').val())
    })

    $('#mon_out2').change(function() {
        $('#tue_out2').val($('#mon_out2').val())
        $('#wed_out2').val($('#mon_out2').val())
        $('#thu_out2').val($('#mon_out2').val())
        $('#fri_out2').val($('#mon_out2').val())
        $('#sat_out2').val($('#mon_out2').val())
        $('#sun_out2').val($('#mon_out2').val())
    })
</script>

<!-- Edit Work Hours -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-5" id="exampleModalLabel">Daily Time Record</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form action="dtr_edit2.php" method="post">
                <div class="form-group row">
                    <label for="dated" class="col-sm-2 col-form-label">Date</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="dated" placeholder="Date" disabled>
                        </div>
                </div>
                            <div class="mb-3">
                                <table width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="text-align: center;">IN</th>
                                            <th style="text-align: center;">OUT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th><label for="dated" class="col-sm-2 col-form-label">1<label></th>
                                            <td><input type="time"  class="form-control" name="d1" id="d1"></td>
                                            <td><input type="time"  class="form-control" name="d2" id="d2"></td>
                                        </tr>
                                        <tr>
                                            <th><label for="dated" class="col-sm-2 col-form-label">2<label></th>
                                            <td><input type="time"  class="form-control" name="d3" id="d3"></td>
                                            <td><input type="time"  class="form-control" name="d4" id="d4"></td>
                                        </tr>
                                        <tr>
                                            <th><label for="dated" class="col-sm-2 col-form-label">3<label></th>
                                            <td><input type="time"  class="form-control" name="d5" id="d5"></td>
                                            <td><input type="time"  class="form-control" name="d6" id="d6"></td>
                                        </tr>
                                        <tr>
                                            <th><label for="dated" class="col-sm-2 col-form-label">4<label></th>
                                            <td><input type="time"  class="form-control" name="d7" id="d7"></td>
                                            <td><input type="time"  class="form-control" name="d8" id="d8"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="dtrID" id="id" value="">
                            <input type="hidden" name="empIDS" value="<?= trim($emp_ids)?>">
                    <hr>

                    <div class="modal-header">
                        <h4 class="modal-title fs-5" id="exampleModalLabel">Working Schedule</h4>
                    </div>
                    <p></p>
                            <!-- schedule -->
                            <div class="mb-3 form-group">
                                <table width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>IN</th>
                                            <th>OUT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th><label for="" class="col-sm-2 col-form-label">AM<label></th>
                                            <td><input type="time" class="form-control" name="d1_b" id="d1_b"></td>
                                            <td><input type="time" class="form-control" name="d2_b" id="d2_b"></td>
                                        </tr>
                                        <tr>
                                            <th><label for="" class="col-sm-2 col-form-label">PM<label></th>
                                            <td><input type="time" class="form-control" name="d3_b" id="d3_b"></td>
                                            <td><input type="time" class="form-control" name="d4_b" id="d4_b"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group row">
                                <label for="dated_a" class="col-sm-2 col-form-label">Updated by: </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="created_by" placeholder="" disabled>
                                    </div>
                            </div>
                                <input type="hidden" id="dated_b2" name="dated_b2">
                                <input type="hidden" name="dtrID_b" id="id_b" value="">
                                <input type="hidden" name="empIDS_b" value="<?= trim($emp_ids)?>">
                            <div class="mb-3" style="text-align: right;">
                                <input type="submit" class="btn btn-primary btn-sm" value="Save Changes" name="changes">
                            </div>
            </form>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- OT Approval -->
<div class="modal fade" id="overtimeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-5" id="exampleModalLabel">Overtime Approval</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="ot_approval.php" method="post">
        <div class="form-group row">
            <label for="dated_a" class="col-sm-2 col-form-label">Date</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="dated_a" placeholder="Date" disabled>
                </div>
        </div>
        <div class="form-group row">
            <label for="ot_approved" class="col-sm-2 col-form-label">Status</label>
            <div class="col-sm-10">
                <select id="ot_approved" name="ot_approved" class="form-control" required>
                    <option value="Approved">Approved</option>
                    <option value="Cancelled">Cancelled</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="dated_a" class="col-sm-2 col-form-label">Updated by: </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="updated_by" placeholder="" disabled>
                </div>
        </div>
        <div class="form-group row">
            <label for="dated_a" class="col-sm-2 col-form-label"># of Hours: </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="otHrs" id="otHrs" placeholder="" disabled>
                </div>
        </div>
                <div class="mb-3" style="text-align: right;">
                    <input type="submit" class="btn btn-primary btn-sm" value="Save Changes" name="changes">
                </div>
                    <input type="hidden" name="dtrID_a" id="id_a" value="">
                    <input type="hidden" name="empIDS_a" value="<?= trim($emp_ids)?>">
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Off w/ work Approval -->
<div class="modal fade" id="off-work-Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title fs-5" id="exampleModalLabel">Day-Off w/ work Approval</h2>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="off_approval.php" method="post">
        <div class="form-group row">
            <label for="dated_a" class="col-sm-2 col-form-label">Date</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="dated_b" placeholder="Date" disabled>
                </div>
        </div>
        <div class="form-group row">
            <label for="ot_approved" class="col-sm-2 col-form-label">Status</label>
            <div class="col-sm-10">
                <select id="off_status" name="off_status" class="form-control" required>
                    <option value="Approved">Approved</option>
                    <option value="Cancelled">Cancelled</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="dated_a" class="col-sm-2 col-form-label">Updated by: </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="off_updated_by" placeholder="" disabled>
                </div>
        </div>
        <div class="form-group row">
            <label for="dated_a" class="col-sm-2 col-form-label"># of Hours: </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="off_hrs" id="off_hrs" placeholder="" disabled>
                </div>
        </div>
                <div class="mb-3" style="text-align: right;">
                    <input type="submit" class="btn btn-primary btn-sm" value="Save Changes" name="changes">
                </div>
                    <input type="hidden" name="dtrID_b" id="id_off" value="">
                    <input type="hidden" name="empIDS_b" value="<?= trim($emp_ids)?>">
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


