<?php
// session_start();

// include_once("../connection/cons.php");
// $con = conns();

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
    // $dept = str_replace("'", "\'", $dept);

    $from = date('m/d/Y', strtotime($from));
    $to = date('m/d/Y', strtotime($to));

    $_SESSION['departmentsss'] = $_POST['dept'];
    $_SESSION['dtr_From'] = $from;
    $_SESSION['dtr_To'] = $to;
    $_SESSION['Payroll'] = $payroll;

}elseif(isset($_GET['Department']) && !isset($_POST['create'])){
    $dept = $_GET['Department'];
    $from = $_SESSION['dtr_From'];
    $to = $_SESSION['dtr_To'];
    $payroll = $_SESSION['Payroll'];
}elseif(!isset($_GET['Department']) && !isset($_POST['create']) && isset($_SESSION['departmentsss']) && isset($_SESSION['Payroll']) && isset($_SESSION['dtr_From']) && isset($_SESSION['dtr_To'])){
    $dept = $_SESSION['departmentsss'];
    $from = $_SESSION['dtr_From'];
    $to = $_SESSION['dtr_To'];
    $payroll = $_SESSION['Payroll'];
}

if(isset($_GET['page']))
{
    $page = $_GET['page'];
}else{
    $page = 1;
}

$limit = $page * 10;
$start = $limit - 10;


// employees info
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



?>
        <div id="payroll_excel" style="display: none;">
                <strong style="color: green; font-size:20px">COH ENTERPRISES, INC.</strong>
                <br>
                <strong style="color: blue; font-size:17px">Department: <?= $dept_name ?></strong>
                <br>
                <strong>Payroll for the Period of  
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
                </strong>
                <table id="" class="" style="width:100%">
                    <thead>
                        <tr>
                            <th style="border: 1px solid black" rowspan="2">No.</th>
                            <th style="border: 1px solid black" rowspan="2">Name of Employee</th>
                            <th style="border: 1px solid black" colspan="2">Regular Work Days</th>
                            <th style="border: 1px solid black" colspan="2">O.T., Holidays & Off w/ Work</th>
                            <th style="border: 1px solid black" rowspan="2">Total Gross</th>
                            <th style="border: 1px solid black" colspan="<?= $payroll == 'Start' ? '8' : '9' ?>">Deductions</th>
                            <th style="border: 1px solid black" rowspan="2">Net Pay</th>
                            <th style="border: 1px solid black; width:200px; text-align:center" rowspan="2">Signature</th>
                        </tr>
                        <tr>
                            <th style="border: 1px solid black" class="ths">Hrs.</th>
                            <th style="border: 1px solid black" class="ths">Pay</th>
                            <th style="border: 1px solid black" class="ths">Hrs.</th>
                            <th style="border: 1px solid black" class="ths">Pay</th>
                            <?php
                                if($payroll == 'Start'){
                            ?>
                                <th style="border: 1px solid black" class="ths">SSS</th>
                                <th style="border: 1px solid black" class="ths">HDMF</th>
                                <th style="border: 1px solid black" class="ths">Philhealth</th>
                            <?php
                                }else{
                            ?>
                                <th style="border: 1px solid black" class="ths">SSS L</th>
                                <th style="border: 1px solid black" class="ths">SSS C</th>
                                <th style="border: 1px solid black" class="ths">HDMF L</th>
                                <th style="border: 1px solid black" class="ths">HDMF C</th>
                            <?php
                                }
                            ?>
                                <th style="border: 1px solid black" class="ths">Salary Loan</th>
                                <th style="border: 1px solid black" class="ths">ESF</th>
                                <th style="border: 1px solid black" class="ths">A/R</th>
                                <th style="border: 1px solid black" class="ths">Shortages</th>
                                <th style="border: 1px solid black" class="ths">Total Ded.</th>
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

                            $sss_total = 0;
                            $pagibig_total = 0;
                            $philhealth_total = 0;
                            $sss_loan_total = 0;
                            $sss_calamity_total = 0;
                            $hdmf_loan_total = 0;
                            $hdmf_calamity_total = 0;
                            $salary_loan_total = 0;
                            $esf_total = 0;
                            $ar_total = 0;
                            $shortages_total = 0;

                            // Load Names of Employees
                            do{
                                $from = $_SESSION['dtr_From'];
                                $rate = 0;

                                $emp_name = $row_emp['lname'].', '.$row_emp['fname'].' '.substr($row_emp['mname'],0,1).' '.$row_emp['extname'];
                                $emp_name = ucwords($emp_name);
                                $position = $row_emp['position'];
                                $empID = $row_emp['ID'];
                                $rate = floatval($row_emp['rates']);
                                $ot_rate = floatval($row_emp['ot']);
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

                                                if($row_dtr['ot_approved'] == 'Approved'){
                                                    $ot = (double)$row_dtr['total_ot'];
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
                                                    if($dayy == 'mon' && $mon == 'OFF' && $hol_types == "-")
                                                    {
                                                        $off_hrs = $off_hrs + $hrs1;
                                                        $off_hrs2 = $off_hrs2 + $ot;
                                                    }
                                                    elseif($dayy == 'tue' && $tue == 'OFF' && $hol_types == "-")
                                                    {
                                                        $off_hrs = $off_hrs + $hrs1;
                                                        $off_hrs2 = $off_hrs2 + $ot;
                                                    }
                                                    elseif($dayy == 'wed' && $wed == 'OFF' && $hol_types == "-")
                                                    {
                                                        $off_hrs = $off_hrs + $hrs1;
                                                        $off_hrs2 = $off_hrs2 + $ot;
                                                    }
                                                    elseif($dayy == 'thu' && $thu == 'OFF' && $hol_types == "-")
                                                    {
                                                        $off_hrs = $off_hrs + $hrs1;
                                                        $off_hrs2 = $off_hrs2 + $ot;
                                                    }
                                                    elseif($dayy == 'fri' && $fri == 'OFF' && $hol_types == "-")
                                                    {
                                                        $off_hrs = $off_hrs + $hrs1;
                                                        $off_hrs2 = $off_hrs2 + $ot;
                                                    }
                                                    elseif($dayy == 'sat' && $sat == 'OFF' && $hol_types == "-")
                                                    {
                                                        $off_hrs = $off_hrs + $hrs1;
                                                        $off_hrs2 = $off_hrs2 + $ot;
                                                    }
                                                    elseif($dayy == 'sun' && $sun == 'OFF' && $hol_types == "-")
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
                                        $additional_hrs = $total_ot + $regular_holiday_hrs + $special_holiday_hrs + $day_off_hrs;
                                        $additional_pay = $ot_pay + $regular_holiday + $special_holiday + $day_off;

                                            ?>
                                            <!-- Load Table details -->
                                                <tr>
                                                    <td style="border: 1px solid black"><?= $no ?></td>
                                                    <td style="border: 1px solid black; text-align:left; padding-left:5px"><b> <?= $emp_name ?></b></td>
                                                    <td style="border: 1px solid black"><?= round($total_hrs,2)?></td>
                                                    <td style="border: 1px solid black"><?= number_format($basic_pay,2)?></td>
                                                    <td style="border: 1px solid black"><?= round($additional_hrs,2)?></td>
                                                    <td style="border: 1px solid black"><?= number_format($additional_pay,2)?></td>
                                                    <td style="border: 1px solid black"><b><?= number_format($gross,2)?></b></td>
                                                    <?php
                                                        if($payroll == 'Start'){
                                                    ?>
                                                        <td style="border: 1px solid black"><?= $sss ?></td>
                                                        <td style="border: 1px solid black"><?= $pagibig ?></td>
                                                        <td style="border: 1px solid black"><?= $philhealth ?></td>
                                                    <?php
                                                        $sss_total = $sss_total + floatval($sss);
                                                        $pagibig_total = $pagibig_total + floatval($pagibig);
                                                        $philhealth_total = $philhealth_total + floatval($philhealth);
                                                        }else{
                                                    ?>
                                                        <td style="border: 1px solid black"><?= $sss_loan ?></td>
                                                        <td style="border: 1px solid black"><?= $sss_calamity ?></td>
                                                        <td style="border: 1px solid black"><?= $hdmf_loan ?></td>
                                                        <td style="border: 1px solid black"><?= $hdmf_calamity ?></td>
                                                    <?php
                                                        $sss_loan_total = $sss_loan_total + floatval($sss_loan);
                                                        $sss_calamity_total = $sss_calamity_total + floatval($sss_calamity);
                                                        $hdmf_loan_total = $hdmf_loan_total + floatval($hdmf_loan);
                                                        $hdmf_calamity_total = $hdmf_calamity_total + floatval($hdmf_calamity);
                                                        }
                                                    ?>
                                                    <td style="border: 1px solid black"><?= $salary_loan ?></td>
                                                    <td style="border: 1px solid black"><?= $esf ?></td>
                                                    <td style="border: 1px solid black"><?= $ar ?></td>
                                                    <td style="border: 1px solid black"><?= $shortages ?></td>
                                                    <td style="border: 1px solid black"><b><?= number_format($deductions,2)?></b></td>
                                                    <td style="border: 1px solid black; color: red; font-size:15px"><b><?= number_format($net_pay,2)?></b></td>
                                                    <td style="border: 1px solid black"></td>
                                                </tr>
                                            <?php
                                $salary_loan_total = $salary_loan_total + floatval($salary_loan);
                                $esf_total = $esf_total + floatval($esf);
                                $ar_total = $ar_total + floatval($ar);
                                $shortages_total = $shortages_total + floatval($shortages);

                                $total_gross = $total_gross + $gross;
                                $total_deductions = $total_deductions + $deductions;
                                $total_netpay = $total_netpay + $net_pay;

                                $total_hrs = round($total_hrs,2);
                                $basic_pay = number_format($basic_pay,2);
                                $additional_hrs = round($additional_hrs,2);
                                $additional_pay = number_format($additional_pay,2);
                                $gross = number_format($gross,2);
                                $deductions = number_format($deductions,2);
                                $net_pay = number_format($net_pay,2);

                                $no++;
                            }while($row_emp = $dan_emp->fetch_assoc());
                        
                            $pp = ceil($no / 10);

                            $prev = $page - 1;
                            $next = $page + 1;


                        }else{
                            $pp = 0;
                            $prev = 0;
                            $next = 0;

                            ?>
                                <tbody>
                                    <tr>
                                        <!-- <td style="border: 1px solid black" colspan="20">No Records Found!</td> -->
                                    </tr>
                                </tbody>
                            <?php
                        }
                    ?>
                    <tfoot>
                        <tr>
                            <th style="border: 1px solid black"></th>
                            <th style="border: 1px solid black"></th>
                            <th style="border: 1px solid black; color: red; font-size:15px" colspan="4">Grandtotal</th>
                            <th style="border: 1px solid black; color: red; font-size:15px"><?php echo number_format($total_gross,2)?></th>
                            <?php
                                if($payroll == 'Start'){
                                    ?>
                                        <th style="border: 1px solid black;"><?= number_format($sss_total,2)?></th>
                                        <th style="border: 1px solid black;"><?= number_format($pagibig_total,2)?></th>
                                        <th style="border: 1px solid black;"><?= number_format($philhealth_total,2)?></th>
                                    <?php
                                }else{
                                    ?>
                                        <th style="border: 1px solid black;"><?= number_format($sss_loan_total,2)?></th>
                                        <th style="border: 1px solid black;"><?= number_format($sss_calamity_total,2)?></th>
                                        <th style="border: 1px solid black;"><?= number_format($hdmf_loan_total,2)?></th>
                                        <th style="border: 1px solid black;"><?= number_format($hdmf_calamity_total,2)?></th>
                                    <?php
                                }
                            ?>
                            <th style="border: 1px solid black;"><?= number_format($salary_loan_total,2)?></th>
                            <th style="border: 1px solid black;"><?= number_format($esf_total,2)?></th>
                            <th style="border: 1px solid black;"><?= number_format($ar_total,2)?></th>
                            <th style="border: 1px solid black;"><?= number_format($shortages_total,2)?></th>
                            <th style="border: 1px solid black; color: red; font-size:15px"><?php echo number_format($total_deductions,2)?></th>
                            <th style="border: 1px solid black; color: red; font-size:15px"><?php echo number_format($total_netpay,2)?></th>
                            <th style="border: 1px solid black"></th>
                        </tr>
                    </tfoot>
                    </table>

        </div>
