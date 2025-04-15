<?php
if(!isset($_SESSION)){
    session_start();
}

date_default_timezone_set("Asia/Manila");
$y_now = date("Y");
$m_now = date("m");
$d_now = date("j");

$now = date("m/d/Y");

$m_last = date('m', strtotime($now . "- 1 month"));

if(isset($_GET['Payroll']))
{
    $payroll = $_GET['Payroll'];

    if($payroll == 'Start')
    {
        if($m_now == date('m', strtotime('01/01/2022')))
        {
            $y_last = date('Y', strtotime($now . "- 1 year"));
        }else{
            $y_last = $y_now;
        }

        $from = $m_last.'/28'.'/'.$y_last;
        $to = $m_now.'/12'.'/'.$y_now;
    }elseif($payroll == 'End')
    {
        $from = $m_now.'/13'.'/'.$y_now;
        $to = $m_now.'/27'.'/'.$y_now;
    }

    $from = date('m/d/Y', strtotime($from));
    $to = date('m/d/Y', strtotime($to));

    $_SESSION['dtr_From'] = $from;
    $_SESSION['dtr_To'] = $to;

}else{
    $payroll = 'Start';

    if($m_now == date('m', strtotime('01/01/2022')))
    {
        $y_last = date('Y', strtotime($now . "- 1 year"));
    }else{
        $y_last = $y_now;
    }

    $from = $m_last.'/28'.'/'.$y_last;
    $to = $m_now.'/12'.'/'.$y_now;
}

$_SESSION['Payroll'] = $payroll;


$deptss = $dept;
$deptss = str_replace("'", "\'", $dept);


// employees info
if(empty($deptss)){
    $sql_emp = "SELECT * FROM employees order by lname,fname,mname ASC";
}else{
    $sql_emp = "SELECT * FROM employees WHERE dept = '$deptss' order by lname,fname,mname ASC";
}
$dan_emp = $con->query($sql_emp) or die ($con->error);
$row_emp = $dan_emp->fetch_assoc();
$count_emp = mysqli_num_rows($dan_emp); 


if(isset($_SESSION['dtr_From']) && isset($_SESSION['dtr_To']))
{
    $from = $_SESSION['dtr_From'];
    $to = $_SESSION['dtr_To'];
}

if($count_emp > 0)
{
?>

<div id="payroll_list_print">
    
    <table style="border: 1px solid black; display: none">
                    <thead>
                        <tr>
                            <th style="border: 1px solid black" rowspan="2">No.</th>
                            <th style="border: 1px solid black" rowspan="2">Name of Employee</th>
                            <th style="border: 1px solid black" colspan="2">Regular Work Days</th>
                            <th style="border: 1px solid black" colspan="2">O.T., Holidays & Off w/ Work</th>
                            <th style="border: 1px solid black" rowspan="2">Total Gross</th>
                            <th style="border: 1px solid black" colspan="12">Deductions</th>
                            <th style="border: 1px solid black" rowspan="2">Net Pay</th>
                        </tr>
                        <tr>
                            <th style="border: 1px solid black">Hrs.</th>
                            <th style="border: 1px solid black">Pay</th>
                            <th style="border: 1px solid black">Hrs.</th>
                            <th style="border: 1px solid black">Pay</th>
                            <th style="border: 1px solid black">SSS</th>
                            <th style="border: 1px solid black">HDMF</th>
                            <th style="border: 1px solid black">Philhealth</th>
                            <th style="border: 1px solid black">SSS L</th>
                            <th style="border: 1px solid black">SSS C</th>
                            <th style="border: 1px solid black">HDMF L</th>
                            <th style="border: 1px solid black">HDMF C</th>
                            <th style="border: 1px solid black">Salary Loan</th>
                            <th style="border: 1px solid black">ESF</th>
                            <th style="border: 1px solid black">A/R</th>
                            <th style="border: 1px solid black">Shortages</th>
                            <th style="border: 1px solid black">Total Ded.</th>
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
                                $rate = $row_emp['rates'];
                                $ot_rate = $row_emp['ot'];
                                $reg_holiday_rate = floatval($rate) * 2;
                                $sp = floatval($rate) * 0.3;
                                $special_holiday_rate = floatval($rate) + $sp;
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
                                                $ot = (double)$row_dtr['total_ot'];
                                    
                                                    // holiday with work
                                                    if($hol_types == 'Regular Holiday')
                                                    {
                                                        $reg_hol_hrs = $reg_hol_hrs + $hrs1;
                                                        $reg_hol_hrs2 = $reg_hol_hrs2 + $ot;
                                                    }elseif($hol_types == 'Special Non-Working Holiday')
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
                                        $minus_hrs = $off_hrs + $reg_hol_hrs + $spe_hol_hrs;
                                        $minus_hrs2 = $off_hrs2 + $reg_hol_hrs2 + $spe_hol_hrs2;

                                        // regular working hours
                                        $total_hrs = $total_hrs - $minus_hrs;
                                        $total_ot = $total_ot - $minus_hrs2;
                                        $basic_pay = round($total_hrs,2) * floatval($rate);
                                        $ot_pay = round($total_ot,2) * floatval($ot_rate);

                                        // holidays and day-off with work
                                        $reg_hol_pay = round($reg_hol_hrs,2) * $reg_holiday_rate;
                                        $spe_hol_pay = round($spe_hol_hrs,2) * $special_holiday_rate;
                                        $off_pay = round($off_hrs,2) * $day_off_rate;
                                        // off w/ ot
                                        $off_ot_rate = $day_off_rate * 0.25;
                                        $off_ot_rate = $off_ot_rate + $day_off_rate;
                                        $off_ot_pay = round($off_hrs2,2) * $off_ot_rate;
                                        // regular holiday w/ ot
                                        $reg_hol_ot_rate = $reg_holiday_rate * 0.25;
                                        $reg_hol_ot_rate = $reg_holiday_rate + $reg_hol_ot_rate;
                                        $reg_hol_ot_pay = round($reg_hol_hrs2,2) * $reg_hol_ot_rate;
                                        // special holiday w/ ot
                                        $spe_hol_ot_rate = $special_holiday_rate * 0.25;
                                        $spe_hol_ot_rate = $special_holiday_rate + $spe_hol_ot_rate;
                                        $spe_hol_ot_pay = round($spe_hol_hrs2,2) * $spe_hol_ot_rate;

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
                                                    <td style="border: 1px solid black"><?php echo $no ?></td>
                                                    <td style="border: 1px solid black" class="tdl"><?php echo $emp_name ?></td>
                                                    <td style="border: 1px solid black"><?php echo round($total_hrs,2)?></td>
                                                    <td style="border: 1px solid black"><?php echo number_format($basic_pay,2)?></td>
                                                    <td style="border: 1px solid black"><?php echo round($additional_hrs,2)?></td>
                                                    <td style="border: 1px solid black"><?php echo number_format($additional_pay,2)?></td>
                                                    <td style="border: 1px solid black"><b><?php echo number_format($gross,2)?></b></td>
                                                    <td style="border: 1px solid black"><?php echo $sss ?></td>
                                                    <td style="border: 1px solid black"><?php echo $pagibig ?></td>
                                                    <td style="border: 1px solid black"><?php echo $philhealth ?></td>
                                                    <td style="border: 1px solid black"><?php echo $sss_loan ?></td>
                                                    <td style="border: 1px solid black"><?php echo $sss_calamity ?></td>
                                                    <td style="border: 1px solid black"><?php echo $hdmf_loan ?></td>
                                                    <td style="border: 1px solid black"><?php echo $hdmf_calamity ?></td>
                                                    <td style="border: 1px solid black"><?php echo $salary_loan ?></td>
                                                    <td style="border: 1px solid black"><?php echo $esf ?></td>
                                                    <td style="border: 1px solid black"><?php echo $ar ?></td>
                                                    <td style="border: 1px solid black"><?php echo $shortages ?></td>
                                                    <td style="border: 1px solid black"><b><?php echo number_format($deductions,2)?></b></td>
                                                    <td style="border: 1px solid black"><b><?php echo number_format($net_pay,2)?></b></td>
                                                </tr>
                                            <?php
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

                                $payroll_h = "SELECT * FROM payrolls WHERE emp_id = '$empID' && transaction_id = '$transaction_id' && dept = '$dept_id'";
                                $dan_payroll_h = $con->query($payroll_h) or die ($con->error);
                                $row_payroll_h = $dan_payroll_h->fetch_assoc();
                                $count_payroll_h = mysqli_num_rows($dan_payroll_h);

                                if($count_payroll_h > 0)
                                {
                                    $pay_hID = $row_payroll_h['ID'];

                                    $sql_add_payroll = "UPDATE `payrolls` SET `emp_id`='$empID',`emp_name`='$emp_name',`reg_hrs`='$total_hrs',
                                            `reg_pay`='$basic_pay',`add_hrs`='$additional_hrs',`add_pay`='$additional_pay',`gross`='$gross',`sss`='$sss',
                                            `pagibig`='$pagibig',`philhealth`='$philhealth',`sss_loan`='$sss_loan',`sss_calamity`='$sss_calamity',
                                            `hdmf_loan`='$hdmf_loan',`hdmf_calamity`='$hdmf_calamity',`salary_loan`='$salary_loan',`esf`='$esf',
                                            `ar`='$ar',`shortages`='$shortages',`deductions`='$deductions',`net_pay`='$net_pay',
                                            `transaction_id`='$transaction_id',`dept`='$dept_id',`nos`='$no' WHERE `ID` = '$pay_hID'";

                                }else{

                                    $sql_add_payroll = "INSERT INTO `payrolls` (`ID`, `emp_id`, `emp_name`, `reg_hrs`, `reg_pay`, 
                                            `add_hrs`, `add_pay`, `gross`, `sss`, `pagibig`, `philhealth`, `sss_loan`, `sss_calamity`, 
                                            `hdmf_loan`, `hdmf_calamity`, `salary_loan`, `esf`, `ar`, `shortages`, `deductions`, `net_pay`, 
                                            `transaction_id`, `dept`, `nos`) 
                                            VALUES (NULL, '$empID', '$emp_name', '$total_hrs', '$basic_pay', '$additional_hrs', 
                                            '$additional_pay', '$gross', '$sss', '$pagibig', '$philhealth', '$sss_loan', '$sss_calamity', 
                                            '$hdmf_loan', '$hdmf_calamity', '$salary_loan', '$esf', '$ar', '$shortages', '$deductions', '$net_pay', 
                                            '$transaction_id', '$dept_id', '$no')";

                                }

                                $s_pay = ($con->query($sql_add_payroll) or die ($con->error));

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
                                        <td colspan="20">No Records Found!</td>
                                    </tr>
                                </tbody>
                            <?php
                        }
                    ?>
                    <tfoot>
                        <tr>
                            <th style="border: 1px solid black" colspan="6">Grandtotal</th>
                            <th style="border: 1px solid black"><?php echo number_format($total_gross,2)?></th>
                            <th style="border: 1px solid black" colspan="11"></th>
                            <th style="border: 1px solid black"><?php echo number_format($total_deductions,2)?></th>
                            <th style="border: 1px solid black"><?php echo number_format($total_netpay,2)?></th>
                        </tr>
                    </tfoot>
    </table>
</div>

<?php
}else{
    ?>
    <div id="payroll_list_print">
    <table style="border: 1px solid black; display: none">
        <thead>
            <tr>
                <th style="border: 1px solid black" rowspan="2">No.</th>
                <th style="border: 1px solid black" rowspan="2">Name of Employee</th>
                <th style="border: 1px solid black" colspan="2">Regular Work Days</th>
                <th style="border: 1px solid black" colspan="2">O.T., Holidays & Off w/ Work</th>
                <th style="border: 1px solid black" rowspan="2">Total Gross</th>
                <th style="border: 1px solid black" colspan="12">Deductions</th>
                <th style="border: 1px solid black" rowspan="2">Net Pay</th>
            </tr>
            <tr>
                <th style="border: 1px solid black">Hrs.</th>
                <th style="border: 1px solid black">Pay</th>
                <th style="border: 1px solid black">Hrs.</th>
                <th style="border: 1px solid black">Pay</th>
                <th style="border: 1px solid black">SSS</th>
                <th style="border: 1px solid black">HDMF</th>
                <th style="border: 1px solid black">Philhealth</th>
                <th style="border: 1px solid black">SSS L</th>
                <th style="border: 1px solid black">SSS C</th>
                <th style="border: 1px solid black">HDMF L</th>
                <th style="border: 1px solid black">HDMF C</th>
                <th style="border: 1px solid black">Salary Loan</th>
                <th style="border: 1px solid black">ESF</th>
                <th style="border: 1px solid black">A/R</th>
                <th style="border: 1px solid black">Shortages</th>
                <th style="border: 1px solid black">Total Ded.</th>
            </tr>
        </thead>
    <tbody>
            <!-- Load Table details -->
            <tr>
            <td style="border: 1px solid black" colspan="20">No Records Found!</td>
            </tr>
    </tbody>
    <tfoot>
        <tr>
            <th style="border: 1px solid black" colspan="6">Grandtotal</th>
            <th style="border: 1px solid black">0</th>
            <th style="border: 1px solid black" colspan="11"></th>
            <th style="border: 1px solid black">0</th>
            <th style="border: 1px solid black">0</th>
        </tr>
    </tfoot>
</table>
</div>

    
    <?php
}
?>