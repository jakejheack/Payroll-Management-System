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


$dept = str_replace("'", "\'", $dept);

// employees info
if(empty($dept)){
    $sql_emp = "SELECT * FROM employees order by lname,fname,mname ASC";
}else{
    $sql_emp = "SELECT * FROM employees WHERE `payroll_dept` = '$dept' order by lname,fname,mname ASC";
}
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


// $payroll = $_SESSION['Payroll'];

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
        <h4>Payroll for the Period of 
            <?php
                if(isset($_SESSION['dtr_From']) && isset($_SESSION['dtr_To']))
                {
                    $from = $_SESSION['dtr_From'];
                    $to = $_SESSION['dtr_To'];
                    ?>
                    <?php echo date('M. d, Y', strtotime($from)).' - '.date('M. d, Y', strtotime($to))?>
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
                        <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="manage_deductions.php?Department=<?= $dept_name?>" class="btn-sm dropdown-item">Manage Deductions & Loans</a></li>
                            <li><a href="daily_time_records.php?Department=<?= $dept_name?>" class="btn-sm dropdown-item">Daily Time Records</a></li>
                            <li><a href="employees_allowance.php?ID=<?= $payroll_uniqid?>&from=<?= $from?>&to=<?=$to?>&Department=<?= $dept_name?>" class="btn-sm dropdown-item">Employee's Allowance</a></li>
                        </ul>
                        <button class="btn btn-success dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="payroll_payslip_pdf.php?payroll_id=<?= $payroll_uniqid?>&department=<?= $dept_id?>" class="dropdown-item btn-sm" target="_blank">Print Payslip</a></li>
                            <li>
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
                            </li>
                        </ul>
                        <a href="payroll_summary_create.php?payroll_uniqid=<?= $payroll_uniqid?>" class="btn btn-secondary btn-sm">Back</a>
                    </div>
            </div>

            <div id="payrolls" class="">
                <table id="payroll_table" class="dataTable table table-striped table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Name of Employee</th>
                            <th rowspan="2">Rate/hr</th>
                            <th colspan="2">Regular Work Days</th>
                            <th colspan="2">O.T., Holidays & Off w/ Work</th>
                            <th rowspan="2">Total Gross</th>
                            <th colspan="<?= $payroll == 'Start' ? '8' : '9' ?>">Deductions</th>
                            <th rowspan="2">Net Pay</th>
                        </tr>
                        <tr>
                            <th class="ths">Hrs.</th>
                            <th class="ths">Pay</th>
                            <th class="ths">Hrs.</th>
                            <th class="ths">Pay</th>
                            <?php
                                if($payroll == 'Start'){
                            ?>
                                <th class="ths">SSS</th>
                                <th class="ths">HDMF</th>
                                <th class="ths">Philhealth</th>
                            <?php
                                }else{
                            ?>
                                <th class="ths">SSS L</th>
                                <th class="ths">SSS C</th>
                                <th class="ths">HDMF L</th>
                                <th class="ths">HDMF C</th>
                            <?php
                                }
                            ?>
                                <th class="ths">Salary Loan</th>
                                <th class="ths">ESF</th>
                                <th class="ths">A/R</th>
                                <th class="ths">Shortages</th>
                                <th class="ths">Total Ded.</th>
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
                                        $basic_pay = round($total_hrs,2) * floatval(round($rate,2));

                                        $ot_pay = round($total_ot,2) * floatval($ot_rate);

                                        // holidays and day-off with work
                                        $reg_hol_pay = round($reg_hol_hrs,2) * round($reg_holiday_rate,2);
                                        $spe_hol_pay = round($spe_hol_hrs,2) * round($special_holiday_rate,2);
                                        $off_pay = round($off_hrs,2) * round($day_off_rate,2);
                                        // off w/ ot
                                        $off_ot_rate = round($day_off_rate,2) * 0.25;
                                        $off_ot_rate = $off_ot_rate + $day_off_rate;
                                        $off_ot_pay = round($off_hrs2,2) * round($off_ot_rate,2);
                                        // regular holiday w/ ot
                                        $reg_hol_ot_rate = round($reg_holiday_rate,2) * 0.25;
                                        $reg_hol_ot_rate = $reg_holiday_rate + $reg_hol_ot_rate;
                                        $reg_hol_ot_pay = round($reg_hol_hrs2,2) * round($reg_hol_ot_rate,2);
                                        // special holiday w/ ot
                                        $spe_hol_ot_rate = round($special_holiday_rate,2) * 0.25;
                                        $spe_hol_ot_rate = $special_holiday_rate + $spe_hol_ot_rate;
                                        $spe_hol_ot_pay = round($spe_hol_hrs2,2) * round($spe_hol_ot_rate,2);

                                        // total gross
                                        $holidays_dayoff_pay = $reg_hol_pay + $spe_hol_pay + $off_pay + $reg_hol_ot_pay + $spe_hol_ot_pay + $off_ot_pay;
                                        $gross = $basic_pay + $ot_pay + $holidays_dayoff_pay;

                                        // deductions
                                        $sql_deductions = "SELECT * FROM deductions WHERE emp_id = '$empID'";
                                        $dan_deductions = $con->query($sql_deductions) or die ($con->error);
                                        $row_deductions = $dan_deductions->fetch_assoc();
                                        $count_deductions = mysqli_num_rows($dan_deductions);

                                        if($count_deductions > 0 && $payroll == 'Start')
                                        {
                                            if($row_deductions['ar_c'] == 'Both' || $row_deductions['ar_c'] == 'Starts'){
                                                $ar = $row_deductions['ar'];
                                            }else{
                                                $ar = 0;
                                            }

                                            if($row_deductions['sss_c'] == 'Both' || $row_deductions['sss_c'] == 'Starts'){
                                                $sss = $row_deductions['sss'];
                                            }else{
                                                $sss = 0;
                                            }

                                            if($row_deductions['pagibig_c'] == 'Both' || $row_deductions['pagibig_c'] == 'Starts'){
                                                $pagibig = $row_deductions['pagibig'];
                                            }else{
                                                $pagibig = 0;
                                            }

                                            if($row_deductions['philhealth_c'] == 'Both' || $row_deductions['philhealth_c'] == 'Starts'){
                                                $philhealth = $row_deductions['philhealth'];
                                            }else{
                                                $philhealth = 0;
                                            }

                                            if($row_deductions['esf_c'] == 'Both' || $row_deductions['esf_c'] == 'Starts'){
                                                $esf = $row_deductions['esf'];
                                            }else{
                                                $esf = 0;
                                            }

                                            if($row_deductions['salary_loan_c'] == 'Both' || $row_deductions['salary_loan_c'] == 'Starts'){
                                                $salary_loan = $row_deductions['salary_loan'];
                                            }else{
                                                $salary_loan = 0;
                                            }

                                            if($row_deductions['sss_loan_c'] == 'Both' || $row_deductions['sss_loan_c'] == 'Starts'){
                                                $sss_loan = $row_deductions['sss_loan'];
                                            }else{
                                                $sss_loan = 0;
                                            }

                                            if($row_deductions['sss_calamity_c'] == 'Both' || $row_deductions['sss_calamity_c'] == 'Starts'){
                                                $sss_calamity = $row_deductions['sss_calamity'];
                                            }else{
                                                $sss_calamity = 0;
                                            }

                                            if($row_deductions['hdmf_loan_c'] == 'Both' || $row_deductions['hdmf_loan_c'] == 'Starts'){
                                                $hdmf_loan = $row_deductions['hdmf_loan'];
                                            }else{
                                                $hdmf_loan = 0;
                                            }

                                            if($row_deductions['hdmf_calamity_c'] == 'Both' || $row_deductions['hdmf_calamity_c'] == 'Starts'){
                                                $hdmf_calamity = $row_deductions['hdmf_calamity'];
                                            }else{
                                                $hdmf_calamity = 0;
                                            }

                                            if($row_deductions['shortages_c'] == 'Both' || $row_deductions['shortages_c'] == 'Starts'){
                                                $shortages = $row_deductions['shortages'];
                                            }else{
                                                $shortages = 0;
                                            }

                                        }elseif($count_deductions > 0 && $payroll == 'End')
                                        {
                                            if($row_deductions['ar_c'] == 'Both' || $row_deductions['ar_c'] == 'Ends'){
                                                $ar = $row_deductions['ar'];
                                            }else{
                                                $ar = 0;
                                            }

                                            if($row_deductions['sss_c'] == 'Both' || $row_deductions['sss_c'] == 'Ends'){
                                                $sss = $row_deductions['sss'];
                                            }else{
                                                $sss = 0;
                                            }

                                            if($row_deductions['pagibig_c'] == 'Both' || $row_deductions['pagibig_c'] == 'Ends'){
                                                $pagibig = $row_deductions['pagibig'];
                                            }else{
                                                $pagibig = 0;
                                            }

                                            if($row_deductions['philhealth_c'] == 'Both' || $row_deductions['philhealth_c'] == 'Ends'){
                                                $philhealth = $row_deductions['philhealth'];
                                            }else{
                                                $philhealth = 0;
                                            }

                                            if($row_deductions['esf_c'] == 'Both' || $row_deductions['esf_c'] == 'Ends'){
                                                $esf = $row_deductions['esf'];
                                            }else{
                                                $esf = 0;
                                            }

                                            if($row_deductions['salary_loan_c'] == 'Both' || $row_deductions['salary_loan_c'] == 'Ends'){
                                                $salary_loan = $row_deductions['salary_loan'];
                                            }else{
                                                $salary_loan = 0;
                                            }

                                            if($row_deductions['sss_loan_c'] == 'Both' || $row_deductions['sss_loan_c'] == 'Ends'){
                                                $sss_loan = $row_deductions['sss_loan'];
                                            }else{
                                                $sss_loan = 0;
                                            }

                                            if($row_deductions['sss_calamity_c'] == 'Both' || $row_deductions['sss_calamity_c'] == 'Ends'){
                                                $sss_calamity = $row_deductions['sss_calamity'];
                                            }else{
                                                $sss_calamity = 0;
                                            }

                                            if($row_deductions['hdmf_loan_c'] == 'Both' || $row_deductions['hdmf_loan_c'] == 'Ends'){
                                                $hdmf_loan = $row_deductions['hdmf_loan'];
                                            }else{
                                                $hdmf_loan = 0;
                                            }

                                            if($row_deductions['hdmf_calamity_c'] == 'Both' || $row_deductions['hdmf_calamity_c'] == 'Ends'){
                                                $hdmf_calamity = $row_deductions['hdmf_calamity'];
                                            }else{
                                                $hdmf_calamity = 0;
                                            }

                                            if($row_deductions['shortages_c'] == 'Both' || $row_deductions['shortages_c'] == 'Ends'){
                                                $shortages = $row_deductions['shortages'];
                                            }else{
                                                $shortages = 0;
                                            }

                                        }elseif($count_deductions <= 0){
                                            $ar = 0;
                                            $sss = 0;
                                            $pagibig = 0;
                                            $philhealth = 0;
                                            $esf = 0;
                                            $salary_loan = 0;
                                            $sss_loan = 0;
                                            $sss_calamity = 0;
                                            $hdmf_loan = 0;
                                            $hdmf_calamity = 0;
                                            $shortages = 0;
                                        }

                                        $ar = floatval($ar);
                                        $sss = floatval($sss);
                                        $pagibig = floatval($pagibig);
                                        $philhealth = floatval($philhealth);
                                        $esf = floatval($esf);
                                        $salary_loan = floatval($salary_loan);
                                        $sss_loan = floatval($sss_loan);
                                        $sss_calamity = floatval($sss_calamity);
                                        $hdmf_loan = floatval($hdmf_loan);
                                        $hdmf_calamity = floatval($hdmf_calamity);
                                        $shortages = floatval($shortages);

                                        $deductions = $sss + $pagibig + $philhealth + $sss_loan + $sss_calamity + 
                                                    $hdmf_loan + $hdmf_calamity + $salary_loan + $esf + $ar + $shortages;

                                        $net_pay = $gross - $deductions;

                                        $regular_holiday = $reg_hol_pay + $reg_hol_ot_pay;
                                        $special_holiday = $spe_hol_pay + $spe_hol_ot_pay;
                                        $day_off = $off_pay + $off_ot_pay;

                                        $regular_holiday_hrs = $reg_hol_hrs + $reg_hol_hrs2;
                                        $special_holiday_hrs = $spe_hol_hrs + $spe_hol_hrs2;
                                        $day_off_hrs = $off_hrs + $off_hrs2;

                                        // ot, holidays & off w/ work pay
                                        $additional_hrs = round($total_ot,2) + round($regular_holiday_hrs,2) + round($special_holiday_hrs,2) + round($day_off_hrs,2);
                                        $additional_pay = round($ot_pay,2) + round($regular_holiday,2) + round($special_holiday,2) + round($day_off,2);

                                            ?>
                                            <!-- Load Table details -->
                                                <tr>
                                                    <td><?= $no ?></td>
                                                    <td class="tdl" style="text-align: left;"><b> <?= $emp_name ?></b></td>
                                                    <td <?= (empty($rate) ? 'class="text-danger"' : '')?>>
                                                        <?= '<a href=javascript:void(); data-id="'.$empID.'"'.(empty($rate) || $rate == 0 ? ' class="btn text-danger btn-link ratebtn" >0' : ' class="btn btn-link ratebtn" >'.$rate).'</a>'?>
                                                    </td>
                                                    <td><?= round($total_hrs,2)?></td>
                                                    <td><?= number_format($basic_pay,2)?></td>
                                                    <td><?= round($additional_hrs,2)?></td>
                                                    <td><?= number_format($additional_pay,2)?></td>
                                                    <td><b><?= number_format($gross,2)?></b></td>
                                                    <?php
                                                        if($payroll == 'Start'){
                                                    ?>
                                                        <td><?= $sss ?></td>
                                                        <td><?= $pagibig ?></td>
                                                        <td><?= $philhealth ?></td>
                                                    <?php
                                                        }else{
                                                    ?>
                                                        <td><?= $sss_loan ?></td>
                                                        <td><?= $sss_calamity ?></td>
                                                        <td><?= $hdmf_loan ?></td>
                                                        <td><?= $hdmf_calamity ?></td>
                                                    <?php
                                                        }
                                                    ?>
                                                    <td><?= $salary_loan ?></td>
                                                    <td><?= $esf ?></td>
                                                    <td><?= $ar ?></td>
                                                    <td><?= $shortages ?></td>
                                                    <td><b><?= number_format($deductions,2)?></b></td>
                                                    <td><b><?= number_format($net_pay,2)?></b></td>
                                                </tr>
                                            <?php
                                $total_gross = $total_gross + $gross;
                                $total_deductions = $total_deductions + $deductions;
                                $total_netpay = $total_netpay + $net_pay;

                               
                                $total_hrs = round($total_hrs,2);
                                $basic_pay = number_format($basic_pay,2);
                                $additional_hrs = round($additional_hrs,2);
                                $additional_pay = number_format($additional_pay,2);

                                $total_ot = round($total_ot,2);
                                $ot_pay = number_format($ot_pay,2);
                                $regular_holiday_hrs = round($regular_holiday_hrs,2);
                                $regular_holiday = number_format($regular_holiday,2);
                                $special_holiday_hrs = round($special_holiday_hrs,2);
                                $special_holiday = number_format($special_holiday,2);
                                $day_off_hrs = round($day_off_hrs,2);
                                $day_off = number_format($day_off,2);
                                
                                $gross = number_format($gross,2);
                                $deductions = number_format($deductions,2);
                                $net_pay = number_format($net_pay,2);


                                $payroll_h = "SELECT * FROM payrolls WHERE emp_id = '$empID' && transaction_id = '$transaction_id' && dept = '$dept_id'";
                                $dan_payroll_h = $con->query($payroll_h) or die ($con->error);
                                $row_payroll_h = $dan_payroll_h->fetch_assoc();
                                $count_payroll_h = mysqli_num_rows($dan_payroll_h);

                                $pay_month = date('F', strtotime($to));
                                if($count_payroll_h > 0)
                                {
                                    $pay_hID = $row_payroll_h['ID'];

                                    $sql_add_payroll = "UPDATE `payrolls` SET `emp_id`='$empID',`emp_name`='$emp_name',`reg_hrs`='$total_hrs',
                                            `reg_pay`='$basic_pay',`add_hrs`='$additional_hrs',`add_pay`='$additional_pay',`gross`='$gross',`sss`='$sss',
                                            `pagibig`='$pagibig',`philhealth`='$philhealth',`sss_loan`='$sss_loan',`sss_calamity`='$sss_calamity',
                                            `hdmf_loan`='$hdmf_loan',`hdmf_calamity`='$hdmf_calamity',`salary_loan`='$salary_loan',`esf`='$esf',
                                            `ar`='$ar',`shortages`='$shortages',`deductions`='$deductions',`net_pay`='$net_pay',
                                            `transaction_id`='$transaction_id',`dept`='$dept_id',`nos`='$no', `cut_off`='$payroll', `pay_month`='$pay_month',
                                            `payroll_id` = '$payroll_uniqid', `ot_hrs`='$total_ot', `ot_pay`='$ot_pay', `reg_hol_hrs`='$regular_holiday_hrs',
                                            `reg_hol_pay`='$regular_holiday', `spe_hol_hrs`='$special_holiday_hrs', `spe_hol_pay`='$special_holiday',
                                            `off_hrs`='$day_off_hrs', `off_pay`='$day_off' WHERE `ID` = '$pay_hID'";

                                }else{

                                    $sql_add_payroll = "INSERT INTO `payrolls` (`ID`, `emp_id`, `emp_name`, `reg_hrs`, `reg_pay`, 
                                            `add_hrs`, `add_pay`, `gross`, `sss`, `pagibig`, `philhealth`, `sss_loan`, `sss_calamity`, 
                                            `hdmf_loan`, `hdmf_calamity`, `salary_loan`, `esf`, `ar`, `shortages`, `deductions`, `net_pay`, 
                                            `transaction_id`, `dept`, `nos`, `cut_off`, `pay_month`, `payroll_id`, `ot_hrs`, `ot_pay`, 
                                            `reg_hol_hrs`, `reg_hol_pay`, `spe_hol_hrs`, `spe_hol_pay`, `off_hrs`, `off_pay`) 
                                            VALUES (NULL, '$empID', '$emp_name', '$total_hrs', '$basic_pay', '$additional_hrs', 
                                            '$additional_pay', '$gross', '$sss', '$pagibig', '$philhealth', '$sss_loan', '$sss_calamity', 
                                            '$hdmf_loan', '$hdmf_calamity', '$salary_loan', '$esf', '$ar', '$shortages', '$deductions', '$net_pay', 
                                            '$transaction_id', '$dept_id', '$no', '$payroll', '$pay_month', '$payroll_uniqid', '$total_ot', '$ot_pay', 
                                            '$regular_holiday_hrs', '$regular_holiday', '$special_holiday_hrs', '$special_holiday', 
                                            '$day_off_hrs', '$day_off')";

                                }

                                $s_pay = ($con->query($sql_add_payroll) or die ($con->error));

                                $no++;
                            }while($row_emp = $dan_emp->fetch_assoc());
                        

                        }else{
                            ?>
                                <tbody>
                                    <tr>
                                        <!-- <td colspan="20">No Records Found!</td> -->
                                    </tr>
                                </tbody>
                            <?php
                        }
                    ?>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th colspan="2"></th>
                            <th colspan="2">Grandtotal</th>
                            <th><?php echo number_format($total_gross,2)?></th>
                            <th colspan="<?= $payroll == 'Start' ? '7' : '8'?>"></th>
                            <th><?php echo number_format($total_deductions,2)?></th>
                            <th><?php echo number_format($total_netpay,2)?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

                    <?php


                    $total_gross = number_format($total_gross,2);
                    $total_deductions = number_format($total_deductions,2);
                    $total_netpay = number_format($total_netpay,2);

                    if($count_pay_history > 0)
                    {
                        $pay_ID = $row_pay_history['ID'];
                        $pay_gross = $row_pay_history['gross'];
                        $pay_deductions = $row_pay_history['deductions'];
                        $pay_netpay = $row_pay_history['netpay'];

                        // if($pay_gross != $total_gross || $pay_deductions != $total_deductions || $pay_netpay != $total_netpay){

                            $sql_add_history = "UPDATE `payroll_history` SET `gross`='$total_gross',
                            `deductions` = '$total_deductions', `netpay` = '$total_netpay', 
                            `updated_by`='$username',`date_updated`='$now_update', `payroll_id`='$payroll_uniqid'
                             WHERE `ID` = '$pay_ID'";

                            $hh_pay = ($con->query($sql_add_history) or die ($con->error));
                        // }

                    }else{

                        $from = $_SESSION['dtr_From'];
                        $to = $_SESSION['dtr_To'];
                        $dept_name = str_replace("'", "\'", $dept_name);

                        $sql_add_history = "INSERT INTO `payroll_history`(`ID`, `transaction_id`, `gross`, 
                        `deductions`, `netpay`, `cut_off`, `department`, `froms`, `tos`, `created_by`, `date_created`, 
                        `updated_by`, `date_updated`, `status`, `dept_id`, `payroll_id`) 
                        VALUES (NULL,'$transaction_id','$total_gross','$total_deductions','$total_netpay','$payroll','$dept_name',
                        '$from','$to','$username','$now_update','','','ACTIVE', '$dept_id', '$payroll_uniqid')";

                        $hh_pay = ($con->query($sql_add_history) or die ($con->error));
                    }

                    include("prints/payroll_list2.php");
            ?>

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
        $('#per_hour').val(json.rates);
        $('#rate_ID').val(id);
        //   $('#trid').val(trid);
        }
    });
    });
</script>

<!-- rate per hour -->
<div class="modal fade" id="ratebtn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title fs-5" id="exampleModalLabel">Employee's Rate per hour</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="update_rate.php" method="post">
            <div class="form-group row">
                <label for="dated" class="col-sm col-form-label">Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="names" id="fullname" disabled>
                    </div>
            </div>
            <div class="form-group row">
                <label for="dated" class="col-sm col-form-label">Rate per hour</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="per_hour" id="per_hour" step=".0001" required>
                    </div>
            </div>
                <div class="mb-3" style="text-align: center;">
                    <input type="submit" class="btn btn-primary btn-sm" value="Submit" name="submit">
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