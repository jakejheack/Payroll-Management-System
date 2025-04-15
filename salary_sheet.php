<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_GET['ID']))
{
    $emp_id = $_GET['ID'];
    $_SESSION['emp_id'] = $emp_id;
}

if(isset($_SESSION['emp_id']) && isset($_SESSION['dtr_From']) && isset($_SESSION['dtr_To']) && isset($_SESSION['Payroll']))
{
    $emp_id = $_SESSION['emp_id'];
    $from = $_SESSION['dtr_From'];
    $froms = $_SESSION['dtr_From'];
    $to = $_SESSION['dtr_To'];
    $payroll = $_SESSION['Payroll'];
}else{
    echo "Something went wrong.";
}


// update deductions
if(isset($_POST['submit']))
{
    $emp_IDs = $_POST['emp_id'];
    $ars = $_POST['ar'];
    $ssss = $_POST['sss'];
    $pagibigs = $_POST['pagibig'];
    $philhealths = $_POST['philhealth'];
    $esfs = $_POST['esf'];
    $sls = $_POST['sl'];

    if(empty($ars))
    {
        $ars = 0;
    }

    if(empty($ssss))
    {
        $ssss = 0;
    }

    if(empty($pagibigs))
    {
        $pagibigs = 0;
    }

    if(empty($philhealths))
    {
        $philhealths = 0;
    }

    if(empty($esfs))
    {
        $esfs = 0;
    }

    if(empty($sls))
    {
        $sls = 0;
    }

    // check if have deductions
    $sql_deductions = "SELECT * FROM deductions WHERE emp_id = '$emp_id'";
    $dan_deductions = $con->query($sql_deductions) or die ($con->error);
    $row_deductions = $dan_deductions->fetch_assoc();
    $count_deductions = mysqli_num_rows($dan_deductions);

    if($count_deductions > 0){
        $sql_update = "UPDATE deductions SET ar = '$ars', sss = '$ssss', pagibig = '$pagibigs',
                        philhealth = '$philhealths', esf = '$esfs', salary_loan = $sls
                        WHERE emp_id = '$emp_IDs'";

        if($con->query($sql_update) or die ($con->error))
        {
        }else{
        echo "Something went wrong";
        }
    }else{
        $sql_new = "INSERT INTO `deductions` (`ID`, `emp_id`, `ar`, `sss`, `pagibig`, `philhealth`,
                    `esf`, `salary_loan`) VALUES (NULL, '$emp_IDs', '$ars', '$ssss', '$pagibigs', '$philhealths',
                    '$esfs', '$sls')";

        if($con->query($sql_new) or die ($con->error))
        {
        }else{
        echo "Something went wrong";
        }

    }

}

$y = date('Y', strtotime($from));
$dd1 = date('md', strtotime($from));
$dd2 = date('md', strtotime($to));

$trans = $y.$dd1.$dd2.$emp_id;

// employees info
$sql_emp = "SELECT * FROM employees WHERE ID = '$emp_id'";
$dan_emp = $con->query($sql_emp) or die ($con->error);
$row_emp = $dan_emp->fetch_assoc();
$count_emp = mysqli_num_rows($dan_emp);

if($count_emp > 0){
    $emp_name = $row_emp['lname'].', '.$row_emp['fname'].' '.$row_emp['mname'].' '.$row_emp['extname'];
    $position = $row_emp['position'];
    $empID = $row_emp['emp_id'];
    $rate = $row_emp['rates'];
    $ot_rate = $row_emp['ot'];

    $reg_holiday_rate = $rate * 2;
    $sp = $rate * 0.3;
    $special_holiday_rate = $rate + $sp;
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

}else{
    $emp_name = '-';
    $position = '-';
    $empID = '-';
}

do{

    $ddd = date('l, F d, Y', strtotime($from));

    // dtr
        $sql_dtr = "SELECT * FROM dtrs WHERE emp_id = '$emp_id' && log_date = '$ddd'";
        $dan_dtr = $con->query($sql_dtr) or die ($con->error);
        $row_dtr = $dan_dtr->fetch_assoc();
        $count = mysqli_num_rows($dan_dtr);

        $dayy = date('D', strtotime($from));
        $dayy = strtolower($dayy);

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

            // $d1 = strtotime($row_dtr['in1']);
            // $d2 = strtotime($row_dtr['out1']);
            // $d3 = strtotime($row_dtr['in2']);
            // $d4 = strtotime($row_dtr['out2']);
            // $d5 = strtotime($row_dtr['in3']);
            // $d6 = strtotime($row_dtr['out3']);
            // $d7 = strtotime($row_dtr['in4']);
            // $d8 = strtotime($row_dtr['out4']);

            // $am = abs($d2 - $d1)/3600;
            // $pm = abs($d4 - $d3)/3600;
            // $hrs1 = $am + $pm;

            // $ot1 = abs($d6 - $d5)/3600;
            // $ot2 = abs($d8 - $d7)/3600;
            // $hrs2 = $ot1 + $ot2;

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
                if($hrs1 > 9 || $hrs1 < 7 || $ot > 0)
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
    $basic_pay = round($total_hrs,2) * $rate;
    $ot_pay = round($total_ot,2) * $ot_rate;

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
    $sql_deductions = "SELECT * FROM deductions WHERE emp_id = '$emp_id'";
    $dan_deductions = $con->query($sql_deductions) or die ($con->error);
    $row_deductions = $dan_deductions->fetch_assoc();
    $count_deductions = mysqli_num_rows($dan_deductions);

    if($count_deductions > 0 && $payroll == 'Start')
    {
        $ar = $row_deductions['ar'];
        $sss = $row_deductions['sss'];
        $pagibig = $row_deductions['pagibig'];
        $philhealth = $row_deductions['philhealth'];
        $esf = $row_deductions['esf'];
        $salary_loan = $row_deductions['salary_loan'];
    }elseif($count_deductions > 0 && $payroll == 'End')
    {
        $ar = $row_deductions['ar'];
        $sss = 0;
        $pagibig = 0;
        $philhealth = 0;
        $esf = 0;
        $salary_loan = $row_deductions['salary_loan'];
    }elseif($count_deductions <= 0){
        $ar = 0;
        $sss = 0;
        $pagibig = 0;
        $philhealth = 0;
        $esf = 0;
        $salary_loan = 0;
    }

    $deductions = $ar + $sss + $pagibig + $philhealth + $esf + $salary_loan;

    $net_pay = $gross - $deductions;

    $regular_holiday = $reg_hol_pay + $reg_hol_ot_pay;
    $special_holiday = $spe_hol_pay + $spe_hol_ot_pay;
    $day_off = $off_pay + $off_ot_pay;

    $regular_holiday_hrs = $reg_hol_hrs + $reg_hol_hrs2;
    $special_holiday_hrs = $spe_hol_hrs + $spe_hol_hrs2;
    $day_off_hrs = $off_hrs + $off_hrs2;


// header
include("includes/header.php");
include("includes/menus.php");
 

?>

<div class="containers">
    <h3>Salary Sheet for the Period of  
        <?php echo date('M d, Y', strtotime($froms)).' - '.date('M d, Y', strtotime($to))?>
    </h3>
        <div class="contents">

            <!-- <span>Date Covered: <?php echo date('M d, Y', strtotime($froms)).' - '.date('M d, Y', strtotime($to))?></span> -->
                <!-- <span>Transaction No. <?php echo $trans?></span>
                <br> -->
                <div class="pay_dtr">
                    <a href="payroll.php" class="pay-list">Payroll List</a>
                    <a href="dtr.php" class="dtr-s">DTR</a>
                </div>

                <h4>Employee Information;</h4>
                <span>Employee ID: <?php echo $empID?></span>
                <br>
                <span>Employee Name: <?php echo $emp_name ?></span>
                <br>
                <span>Designation: <?php echo $position ?></span>
                <br>
                <span>Rate per Hour: <?php echo $rate?></span>

                <form action="" method="post" class="salary_sheet">
                    <table class="earnings">
                        <tbody>
                            <tr>
                                <th colspan="3">Earnings</th>
                            </tr>
                            <tr>
                                <th>Particulars</th>
                                <th>Hours</th>
                                <th>Pay</th>
                            </tr>
                            <tr>
                                <td>Regular Working Days</td>
                                <td><?php echo round($total_hrs,2)?></td>
                                <td><?php echo number_format($basic_pay,2)?></td>
                            </tr>
                            <tr>
                                <td>Overtime</td>
                                <td><?php echo round($total_ot,2)?></td>
                                <td><?php echo number_format($ot_pay,2)?></td>
                            </tr>
                            <tr>
                                <td>Regular Holidays</td>
                                <td><?php echo round($reg_hol_hrs,2)?></td>
                                <td><?php echo number_format($reg_hol_pay,2)?></td>
                            </tr>
                            <tr>
                                <td>R.H. Overtime</td>
                                <td><?php echo round($reg_hol_hrs2,2)?></td>
                                <td><?php echo number_format($reg_hol_ot_pay,2)?></td>
                            </tr>
                            <tr>
                                <td>Special Non-Working Holiday</td>
                                <td><?php echo round($spe_hol_hrs,2)?></td>
                                <td><?php echo number_format($spe_hol_pay,2)?></td>
                            </tr>
                            <tr>
                                <td>S.H. Overtime</td>
                                <td><?php echo round($spe_hol_hrs2,2)?></td>
                                <td><?php echo number_format($spe_hol_ot_pay,2)?></td>
                            </tr>
                            <tr>
                                <td>Day-off w/ Work</td>
                                <td><?php echo round($off_hrs,2)?></td>
                                <td><?php echo number_format($off_pay,2)?></td>
                            </tr>
                            <tr>
                                <td>D.W. Overtime</td>
                                <td><?php echo round($off_hrs2,2)?></td>
                                <td><?php echo number_format($off_ot_pay,2)?></td>
                            </tr>
                            <tr>
                                <th colspan="2">Total Gross</th>
                                <th><?php echo number_format($gross,2)?></th>
                            </tr>
                        </tbody>
                    </table>

                    <table class="deductions">
                        <tbody>
                            <tr>
                                <th colspan="2">Deductions</th>
                            </tr>
                            <tr>
                                <th>Particulars</th>
                                <th>Amount</th>
                            </tr>
                            <tr>
                                <td>A/R</td>
                                <td><input type="number" name="ar" id="" value="<?php echo $ar?>"></td>
                            </tr>
                            <tr>
                                <td>SSS Contributions</td>
                                <td><input type="number" name="sss" id="" value="<?php echo $sss?>"></td>
                            </tr>
                            <tr>
                                <td>PAG-IBIG Contributions</td>
                                <td><input type="number" name="pagibig" id="" value="<?php echo $pagibig?>"></td>
                            </tr>
                            <tr>
                                <td>Philhealth Contributions</td>
                                <td><input type="number" name="philhealth" id="" value="<?php echo $philhealth?>"></td>
                            </tr>
                            <tr>
                                <td>ESF</td>
                                <td><input type="number" name="esf" id="" value="<?php echo $esf?>"></td>
                            </tr>
                            <tr>
                                <td>Salary Loan</td>
                                <td><input type="number" name="sl" id="" value="<?php echo $salary_loan?>"></td>
                           </tr>
                           <tr>
                                <th>Total Deductions</th>
                                <th><?php echo number_format($deductions,2)?></th>
                           </tr>
                           <tr>
                                <th>Net Pay</th>
                                <th><?php echo number_format($net_pay,2)?></th>
                           </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="emp_id" value="<?php echo $emp_id?>">
                    <input type="submit" value="Save Changes" name="submit">
            </form>
        </div>
</div>
<script>
// document.getElementById("Additionals").style.display = "none";
// document.getElementById("Deductions").style.display = "none";
// document.getElementById("Totals").style.display = "none";

function openNext(evt, nextName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(nextName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>