<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");
$y_now = date("Y");
$m_now = date("m");
$d_now = date("j");

$now = date("m/1/Y");
$now_update = date("m/d/y-h:i a");

$m_last = date('m', strtotime($now . "- 1 month"));

if(isset($_POST['create']) && !isset($_GET['Department']))
{
    $payroll = $_POST['cut_off'];
    $from = $_POST['froms'];
    $to = $_POST['tos'];
    $dept = $_POST['dept'];
    $dept = str_replace("'", "\'", $dept);
    $payroll_uniqid = $_POST['pay_uniqid'];

    $from = date('m/d/Y', strtotime($from));
    $to = date('m/d/Y', strtotime($to));

    $_SESSION['departmentsss'] = $_POST['dept'];
    $_SESSION['dtr_From'] = $from;
    $_SESSION['dtr_To'] = $to;
    $_SESSION['Payroll'] = $payroll;
    $_SESSION['Payroll_Uniqid'] = $payroll_uniqid;

}elseif(isset($_GET['Department']) && !isset($_POST['create'])){
    $dept = $_GET['Department'];
    $from = $_SESSION['dtr_From'];
    $to = $_SESSION['dtr_To'];
    $payroll = $_SESSION['Payroll'];
    $payroll_uniqid = $_SESSION['Payroll_Uniqid'];
}elseif(!isset($_GET['Department']) && !isset($_POST['create']) && isset($_SESSION['departmentsss']) && isset($_SESSION['Payroll']) && isset($_SESSION['dtr_From']) && isset($_SESSION['dtr_To'])){
    $dept = $_SESSION['departmentsss'];
    $from = $_SESSION['dtr_From'];
    $to = $_SESSION['dtr_To'];
    $payroll = $_SESSION['Payroll'];
    $payroll_uniqid = $_SESSION['Payroll_Uniqid'];
}

$depts = str_replace("'", "\'", $dept);
$dept = str_replace("'", "\'", $dept);

// employees info
$sql_emp = "SELECT * FROM employees WHERE dept = '$depts' order by lname,fname,mname ASC";
$dan_emp = $con->query($sql_emp) or die ($con->error);
$row_emp = $dan_emp->fetch_assoc();
$count_emp = mysqli_num_rows($dan_emp);

if(isset($_SESSION['dtr_From']) && isset($_SESSION['dtr_To']))
{
    $from = $_SESSION['dtr_From'];
    $to = $_SESSION['dtr_To'];
}

// departments
$sql_dept = "SELECT * FROM departments WHERE dept = '$dept'";
$dan_dept = $con->query($sql_dept) or die ($con->error);
$row_dept = $dan_dept->fetch_assoc();
$count_dept = mysqli_num_rows($dan_dept);

if($count_dept > 0){
    $dept_id = $row_dept['ID'];
    $dept_name = $row_dept['dept'];
    $_SESSION['departmentsss'] = $dept_name;
}


$period_from = date('mdY', strtotime($from));
$period_to = date('mdY', strtotime($to));

$period_covered = $period_from.'-'.$period_to;

$transaction_id = $period_from.'_'.$period_to;

// payroll_history
$sql_pay_history = "SELECT * FROM payroll_history WHERE department = '$dept' && cut_off = '$payroll' && transaction_id = '$transaction_id'";
$dan_pay_history = $con->query($sql_pay_history) or die ($con->error);
$row_pay_history = $dan_pay_history->fetch_assoc();
$count_pay_history = mysqli_num_rows($dan_pay_history);


// header
include("includes/header.php");
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h4>Employees Allowance for the Period of 
                <?php
                    if(isset($_SESSION['dtr_From']) && isset($_SESSION['dtr_To']))
                    {
                        $from = $_SESSION['dtr_From'];
                        $to = $_SESSION['dtr_To'];
                        ?>
                        <?php echo date('M d, Y', strtotime($from)).' - '.date('M d, Y', strtotime($to))?>
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
                    <!-- <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="manage_deductions.php?Department=<?= $dept_name?>" class="btn btn-primary btn-sm dropdown-item">Manage Deductions & Loans</a></li>
                        <li><a href="daily_time_records.php?Department=<?= $dept_name?>" class="btn btn-info btn-sm dropdown-item">Daily Time Records</a></li>
                    </ul> -->
                    <button class="btn btn-success dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="payroll_allowances_pdf.php?payroll_id=<?= $payroll_uniqid?>&department=<?= $dept_name?>" class="dropdown-item btn-sm" target="_blank">Print Allowance</a></li>
                        <!-- <li>
                        <?php
                            if(isset($_SESSION['dtr_From']) && isset($_SESSION['dtr_To']))
                            {
                                $from = $_SESSION['dtr_From'];
                                $to = $_SESSION['dtr_To'];
                                ?>
                                <button onclick="exportTableToExcel('payroll_excel', '<?php echo $dept_name.'_'.date('m/d/Y', strtotime($from)).' - '.date('m/d/Y', strtotime($to))?>')" class="dropdown-item btn-sm" type="button">Excel File</button>
                                <?php
                            }else{
                                ?>
                                <button onclick="exportTableToExcel('payroll_excel', 'Payroll_list')" class="dropdown-item btn-sm" type="button">Excel File</button>
                                <?php
                            }
                            ?>
                        </li> -->
                    </ul>
                        <a href="payroll_create.php?Department=<?= $_SESSION['departmentsss'] ?>" class="btn btn-secondary btn-sm">Back</a>
                    </div>
                </div>

            <div id="payrolls" class="">
                <table id="payroll_table" class="dataTable table table-striped table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name of Employee</th>
                            <!-- <th>Position</th> -->
                            <th>Rate</th>
                            <th>Reg. Hrs.</th>
                            <th>Reg. Pay</th>
                            <th>OT Hrs.</th>
                            <th>OT Pay</th>
                            <th>Reg. Hol. Hrs.</th>
                            <th>Reg. Hol. Pay</th>
                            <th>Spe. Hol. Hrs.</th>
                            <th>Spe. Hol. Pay</th>
                            <th>Day-off Hrs.</th>
                            <th>Day-off Pay.</th>
                            <th>Total Hrs.</th>
                            <th>Net Pay</th>
                        </tr>
                    </thead>

                    <?php
                        $total_gross = 0;
                        $total_deductions = 0;
                        $total_netpay = 0;
                    
                        if(isset($_SESSION['dtr_From']) && isset($_SESSION['dtr_To']) && $count_emp > 0)
                        {
                            $to = $_SESSION['dtr_To'];
                            $no = 1;

                            // Load Names of Employees
                            do{
                                if(!empty($row_emp['allowances'])){
                                $from = $_SESSION['dtr_From'];
                                $rate = 0;

                                $emp_name = $row_emp['lname'].', '.$row_emp['fname'].' '.substr($row_emp['mname'],0,1).' '.$row_emp['extname'];
                                $emp_name = ucwords($emp_name);
                                $position = $row_emp['position'];
                                $empID = $row_emp['ID'];
                                $rate = floatval($row_emp['rates']);
                                $ot_rate =  floatval($row_emp['ot']);
                                $reg_holiday_rate = floatval(round($rate,2)) * 2;
                                $sp = floatval(round($rate,2)) * 0.3;
                                $special_holiday_rate = floatval(round($rate,2)) + $sp;
                                $day_off_rate = $special_holiday_rate;


                                // payroll
                                    $total_hrs = 0;
                                    $total_ot = 0;
                                    $hrs1 = 0;
                                    $hrs2 = 0;
                                    $off_hrs = 0;
                                    $off_hrs2 = 0;
                                    $basic_pay = 0;
                                    $ot_pay = 0;
                                    $off_pay = 0;
                                    $ver = "";
                                    $gross = 0;
                                    $reg_hol_hrs = 0;
                                    $spe_hol_hrs = 0;
                                    $reg_hol_hrs2 = 0;
                                    $spe_hol_hrs2 = 0;
                                    do{

                                        $ddd = date('l, F d, Y', strtotime($from));

                                        // dtr
                                            $sql_dtr = "SELECT * FROM dtrs WHERE emp_id = '$empID' && log_date = '$ddd'";
                                            $dan_dtr = $con->query($sql_dtr) or die ($con->error);
                                            $row_dtr = $dan_dtr->fetch_assoc();
                                            $count = mysqli_num_rows($dan_dtr);

                                            $dayy = date('D', strtotime($from));
                                            $dayy = strtolower($dayy);

                                            // schedules
                                            $sql_sched = "SELECT * FROM schedules WHERE emp_id = '$empID'";
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
                                            $hol_date = date('2023-m-d', strtotime($ddd));

                                            $sql_hol = "SELECT * FROM holidays WHERE datee = '$hol_date'";
                                            $dan_hol = $con->query($sql_hol) or die ($con->error);
                                            $row_hol = $dan_hol->fetch_assoc();
                                            $count_hol = mysqli_num_rows($dan_hol);

                                                    // holiday with work
                                                    if($count_hol > 0){
                                                        $hol_types = $row_hol['types'];
                                                    }else{
                                                        $hol_types = "-";
                                                    }

                                                    
                                            if($count > 0)
                                            {
                                                $hrs1 = (double)$row_dtr['total_hrs'];
                                                $off_status = $row_dtr['off_status'];

                                                if($row_dtr['ot_approved'] == 'Approved'){
                                                    $ot = (double)$row_dtr['total_ot'];
                                                    $hrs1 = $hrs1 - $ot;
                                                }else{
                                                    $ot = 0;
                                                }
                                    
                                                    // holiday with work
                                                    if($hol_types == 'REGULAR HOLIDAY')
                                                    {
                                                        $reg_hol_hrs = $reg_hol_hrs + $hrs1;
                                                        $reg_hol_hrs2 = $reg_hol_hrs2 + $ot;
                                                    }elseif($hol_types == 'SPECIAL NON-WORKING HOLIDAY')
                                                    {
                                                        $spe_hol_hrs = $spe_hol_hrs + $hrs1;
                                                        $spe_hol_hrs2 = $spe_hol_hrs2 + $ot;
                                                    }

                                                    // day-off w/ Work
                                                    if($dayy == 'mon' && $mon == 'OFF' && $hol_types == "-" && $off_status == 'Approved')
                                                    {
                                                        $off_hrs = $off_hrs + $hrs1;
                                                        $off_hrs2 = $off_hrs2 + $ot;
                                                    }
                                                    elseif($dayy == 'tue' && $tue == 'OFF' && $hol_types == "-" && $off_status == 'Approved')
                                                    {
                                                        $off_hrs = $off_hrs + $hrs1;
                                                        $off_hrs2 = $off_hrs2 + $ot;
                                                    }
                                                    elseif($dayy == 'wed' && $wed == 'OFF' && $hol_types == "-" && $off_status == 'Approved')
                                                    {
                                                        $off_hrs = $off_hrs + $hrs1;
                                                        $off_hrs2 = $off_hrs2 + $ot;
                                                    }
                                                    elseif($dayy == 'thu' && $thu == 'OFF' && $hol_types == "-" && $off_status == 'Approved')
                                                    {
                                                        $off_hrs = $off_hrs + $hrs1;
                                                        $off_hrs2 = $off_hrs2 + $ot;
                                                    }
                                                    elseif($dayy == 'fri' && $fri == 'OFF' && $hol_types == "-" && $off_status == 'Approved')
                                                    {
                                                        $off_hrs = $off_hrs + $hrs1;
                                                        $off_hrs2 = $off_hrs2 + $ot;
                                                    }
                                                    elseif($dayy == 'sat' && $sat == 'OFF' && $hol_types == "-" && $off_status == 'Approved')
                                                    {
                                                        $off_hrs = $off_hrs + $hrs1;
                                                        $off_hrs2 = $off_hrs2 + $ot;
                                                    }
                                                    elseif($dayy == 'sun' && $sun == 'OFF' && $hol_types == "-" && $off_status == 'Approved')
                                                    {
                                                        $off_hrs = $off_hrs + $hrs1;
                                                        $off_hrs2 = $off_hrs2 + $ot;
                                                    }
                                            
                                                $total_hrs = $total_hrs + $hrs1;
                                                $total_ot = $total_ot + $ot;
                                            }

                                                // to verify hrs
                                                    if($hrs1 >= 9 || $hrs1 < 7 || $ot > 0)
                                                    {
                                                        $ver = "To Check";
                                                    }

                                        $from = date('m/d/Y', strtotime($from . " +1 day"));
                                    }while(strtotime($from) <= strtotime($to));

                                        // deduct hrs from day-off, regular and special holidays
                                        $minus_hrs = round($off_hrs,2) + round($reg_hol_hrs,2) + round($spe_hol_hrs,2);
                                        $minus_hrs2 = round($off_hrs2,2) + round($reg_hol_hrs2,2) + round($spe_hol_hrs2,2);

                                        // regular working hours
                                        $total_hrs = round($total_hrs,2) - round($minus_hrs,2);
                                        $total_ot = round($total_ot,2) - round($minus_hrs2,2);

                                        $regular_holiday_hrs = $reg_hol_hrs + $reg_hol_hrs2;
                                        $special_holiday_hrs = $spe_hol_hrs + $spe_hol_hrs2;
                                        $day_off_hrs = $off_hrs + $off_hrs2;

                                        // ot, holidays & off w/ work pay
                                        $additional_hrs = round($total_ot,2) + round($regular_holiday_hrs,2) + round($special_holiday_hrs,2) + round($day_off_hrs,2);

                                        $total_hrs = round($total_hrs,2);
                                        $additional_hrs = round($additional_hrs,2);
        
                                        $total_ot = round($total_ot,2);
                                        $regular_holiday_hrs = round($regular_holiday_hrs,2);
                                        $special_holiday_hrs = round($special_holiday_hrs,2);
                                        $day_off_hrs = round($day_off_hrs,2);
        
                                        $regular_pay = $total_hrs * floatval($row_emp['allowances']);
                                        $ot_pay = $total_ot * floatval($row_emp['allowances']);
                                        $reg_holiday_pay = $regular_holiday_hrs * (floatval($row_emp['allowances']) * 2);
                                        $spe_holiday_pay = floatval($row_emp['allowances']) * 0.3;
                                        $spe_holiday_pay = $special_holiday_hrs * (floatval($row_emp['allowances']) + $spe_holiday_pay);
                                        $day_off_pay = floatval($row_emp['allowances']) * 0.3;
                                        $day_off_pay = $day_off_hrs * (floatval($row_emp['allowances']) + $day_off_pay);

                                        $additional_pay = round($ot_pay,2) + round($reg_holiday_pay,2) + round($spe_holiday_pay,2) + round($day_off_pay,2);

                                            ?>
                                            <!-- Load Table details -->
                                                <tr>
                                                    <td><?= $no ?></td>
                                                    <td class="tdl" style="text-align: left;"><b> <?= $emp_name ?></b></td>
                                                    <!-- <td><?= $row_emp['position']?></td> -->
                                                    <td><?= '<a href=javascript:void(); data-id="'.$empID.'"'.(empty($row_emp['allowances']) || $row_emp['allowances'] == 0 ? ' class="btn text-danger btn-link ratebtn" >0' : ' class="btn btn-link ratebtn" >'.$row_emp['allowances']).'</a>'?>
                                                    </td>
                                                    <td><?= $total_hrs?></td>
                                                    <td><?= $regular_pay?></td>
                                                    <td><?= $total_ot ?></td>
                                                    <td><?= $ot_pay?></td>
                                                    <td><?= $regular_holiday_hrs?></td>
                                                    <td><?= $reg_holiday_pay?></td>
                                                    <td><?= $special_holiday_hrs?></td>
                                                    <td><?= $spe_holiday_pay?></td>
                                                    <td><?= $day_off_hrs?></td>
                                                    <td><?= $day_off_pay?></td>
                                                    <th><?= $total_hrs + $additional_hrs ?></th>
                                                    <th><?= $regular_pay + $additional_pay ?></th>
                                                </tr>
                                            <?php
                                $no++;

                                    $net_allowance = $regular_pay + $additional_pay;
                                    $dept = $row_emp['dept'];
                                    $dept = str_replace("'", "\'", $dept);

                                    // save to allowances
                                    $allowances_sql = "SELECT * FROM payroll_allowance WHERE emp_id = '$empID' && payroll_id = '$payroll_uniqid' && posting = 'ACTIVE'";
                                    $dan_allowance = $con->query($allowances_sql) or die ($con->error);
                                    $row_allowance = $dan_allowance->fetch_assoc();
                                    $count_allowance = mysqli_num_rows($dan_allowance);

                                    $from = $_SESSION['dtr_From'];
                                    $to = $_SESSION['dtr_To'];
                                    $pay_month = date('F', strtotime($to));

                                    if($count_allowance > 0){
                                        $id_allowance = $row_allowance['ID'];
                                        // update allowance
                                        $sql_update_allowance = "UPDATE `payroll_allowance` SET `reg_hrs`='$total_hrs',`reg_pay`='$regular_pay',`add_hrs`='$additional_hrs',`add_pay`='$additional_pay',
                                        `net_pay`='$net_allowance',`dept`='$dept',`cut_off`='$payroll',`pay_month`='$pay_month',`posting`='ACTIVE',`payroll_id`='$payroll_uniqid',
                                        `ot_hrs`='$total_ot',`ot_pay`='$ot_pay',`reg_hol_hrs`='$regular_holiday_hrs',`reg_hol_pay`='$reg_holiday_pay',`spe_hol_hrs`='$special_holiday_hrs',
                                        `spe_hol_pay`='$spe_holiday_pay',`off_hrs`='$day_off_hrs',`off_pay`='$off_pay', `froms`='$from', `tos`='$to' WHERE `ID` = '$id_allowance'";
                                        $sql_ua = ($con->query($sql_update_allowance) or die ($con->error));
                                    }else{
                                        // add new
                                        $sql_update_allowance = "INSERT INTO `payroll_allowance`(`ID`, `emp_id`, `emp_name`, `reg_hrs`, `reg_pay`, `add_hrs`, `add_pay`, 
                                        `net_pay`, `dept`, `cut_off`, `pay_month`, `posting`, `payroll_id`, `ot_hrs`, `ot_pay`, `reg_hol_hrs`, `reg_hol_pay`, 
                                        `spe_hol_hrs`, `spe_hol_pay`, `off_hrs`, `off_pay`, `froms`, `tos`) VALUES 
                                        (NULL,'$empID','$emp_name','$total_hrs','$regular_pay','$additional_hrs','$additional_pay','$net_allowance',
                                        '$dept','$payroll','$pay_month','ACTIVE','$payroll_uniqid','$total_ot','$ot_pay','$regular_holiday_hrs','$reg_holiday_pay',
                                        '$special_holiday_hrs','$spe_holiday_pay','$day_off_hrs','$day_off_pay','$from','$to')";
                                        $sql_ua = ($con->query($sql_update_allowance) or die ($con->error));
                                    }
                                }
                            }while($row_emp = $dan_emp->fetch_assoc());

                        }else{
                            ?>
                                <tbody>
                                    <tr>
                                    </tr>
                                </tbody>
                            <?php
                        }
                    ?>
                </table>
            </div>
        </div>
<!--  -->
</div>
    </div>
</main>

<?php
include("includes/footer.php");
?>


<script>
    let table = new DataTable('#payroll_table',{
        fixedColumns: {
        left: 3,
        right: 2
        },
        scrollCollapse: true,
        scrollX: true,
        scrollY: 500
    });

    // Display DTR Modal
    $('#payroll_table').on('click', '.ratebtn', function(event) {
    var table = $('#payroll_table').DataTable();
    var trid = $(this).closest('tr').attr('id');
        //console.log(selectedRow);
    var id = $(this).data('id');
    $('#ratebtn').modal('show');

    $.ajax({
        url: "get_single_employees_dtr.php",
        data: {
        id: id
        },
        type: 'post',
        success: function(data) {
        var json = JSON.parse(data);
        $('#fullname').val(json.fullname);
        $('#per_hour').val(json.allowance);
        $('#rate_ID').val(id);
        //   $('#trid').val(trid);
        }
    });
    });
</script>


<div class="modal fade" id="ratebtn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title fs-5" id="exampleModalLabel">Employee's Rate Allowance per hour</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="update_rate_allowance.php" method="post">
            <div class="form-group row">
                <label for="dated" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="names" id="fullname" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated" class="col-sm-2 col-form-label">Rate</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="per_hour" id="per_hour" step=".0001" required>
                    </div>
            </div>
                <div class="mb-3" style="text-align: center;">
                    <input type="submit" class="btn btn-primary btn-sm" value="Save Changes" name="submit">
                </div>
                    <input type="hidden" name="rate_ID" id="rate_ID" value="" required>
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>