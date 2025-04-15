<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");
$y_now = date("Y");
$m_now = date("F");
$d_now = date("j");

if(isset($_GET['ID'])){
    $id = $_GET['ID'];
}else{
    header("Location: employees.php");
}

// employees
$sql = "SELECT * FROM employees WHERE ID = '$id'";
$dan = $con->query($sql) or die ($con->error);
$row = $dan->fetch_assoc();

$emp_id = $row['emp_id'];
$emp_name = $row['lname'].', '.$row['fname'].' '.$row['mname'];
$poss = $row['position'];

// position
$pos = "SELECT * FROM positions WHERE pos = '$poss'";
$d_pos = $con->query($pos) or die ($con->error);
$row_pos = $d_pos->fetch_assoc();

$rate = $row_pos['rate'];

// overtime
$p_hour = $rate / 8;
$hour_rate = round($p_hour * 1.25);


if(isset($_POST['next']))
{
    $from_md = $_POST['month_from'].' '.$_POST['day_from'];
    $to_md = $_POST['month_to'].' '.$_POST['day_to'];
    $period = $from_md.' to '.$to_md.', '.$y_now;
    
    $work = $_POST['work_days'];
    
    $noh_ot = $_POST['noh'];
    $per_hour = $_POST['per_hour'];

    $leave = $_POST['leave'];

    // basic salary
    $basic = $work * $rate;
    // additions
    $ot = $noh_ot * $per_hour;
    $holiday = $_POST['holiday'];
    // total additions + salary
    $additional = $basic + $ot + $holiday;
    // deductions
    $l = $rate / 8;
    $leaves = $leave * $rate;
    $late_hours = $_POST['late'];
    $late_deduction = $late_hours * $l;
    $ca = $_POST['ca'];
    $sss = $_POST['sss'];
    $pag_ibig = $_POST['pag-ibig'];
    $philhealth = $_POST['philhealth'];
    // total deductions
    $deductions = $leaves + $late_deduction + $ca + $sss + $pag_ibig + $philhealth;
    // net pay
    $net_pay = $additional - $deductions;

    $_SESSION['days_work'] = "Dated";
}

// header
include("includes/header.php");
include("includes/menus.php");
?>

<div class="containers">
    <h3>Payroll</h3>
    <div class="contents">
        <div class="emp_payroll">
<?php 
    if(isset($_SESSION['days_work']))
    {
        ?>
            <form action="print_employees_payroll.php" method="post" target="_blank" >
                <table>
                    <thead>
                        <tr>
                        <th colspan="3">Earnings Statement</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Period:</td>
                            <td colspan="2"><?php echo $from_md.' to '.$to_md.', '.$y_now; ?></td>
                            <input type="hidden" name="period" value="<?php echo $period;?>">
                        </tr>
                        <tr>
                            <td>Employee's ID no:  </td>
                            <td colspan="2"><?php echo $row['emp_id'] ?></td>
                            <input type="hidden" name="emp_id" value="<?php echo $row['emp_id'];?>">
                        </tr>
                        <tr>
                            <td>Name:</td>
                            <td colspan="2"><?php echo $row['lname'].', '.$row['fname'].' '.$row['mname']; ?></td>
                            <input type="hidden" name="name" value="<?php echo $row['lname'].', '.$row['fname'].' '.$row['mname']; ?>">
                        </tr>
                        <tr>
                            <td>Position:</td>
                            <td colspan="2"><?php echo $row['position']; ?></td>
                            <input type="hidden" name="position" value="<?php echo $row['position'];?>">
                        </tr>
                        <tr>
                            <td>Rate per Day: </td>
                            <td colspan="2" ><?php echo $rate; ?></td>
                            <input type="hidden" name="rate" value="<?php echo $rate;?>">
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><h4>Basic Pay: </h4></td>
                            <td><h4><?php echo $basic; ?></h4></td>
                            <input type="hidden" name="work" value="<?php echo $work;?>">
                            <input type="hidden" name="basic" value="<?php echo $basic;?>">
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td colspan="3"><h5>Additionals;</h5></td>
                        </tr>
                        <tr>
                            <td>Overtime: (<?php echo $noh_ot; ?> Hr/s.)</td>
                            <td><?php echo $ot; ?></td>
                            <td></td>
                            <input type="hidden" name="noh_ot" value="<?php echo $noh_ot;?>">
                            <input type="hidden" name="per_hour" value="<?php echo $per_hour;?>">
                        </tr>
                        <tr>
                            <td>Holiday Pay:</td>
                            <td><?php echo $holiday; ?></td>
                            <input type="hidden" name="holiday" value="<?php echo $holiday;?>">
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><h5>Total Gross Pay:</h5></td>
                            <td><h5><?php echo $additional; ?></h5></td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td colspan="3"><h5>Deductions;</h5></td>
                        </tr>
                        <tr>
                            <td>Leave w/ out Pay (<?php echo $leave; ?> Day/s):</td>
                            <td><?php echo $leaves; ?></td>
                            <input type="hidden" name="leave" value="<?php echo $leave;?>">
                            <td></td>
                        </tr>
                        <tr>
                            <td>Late (<?php echo $late_hours.' Hours'?>)</td>
                            <td><?php echo $late_deduction ?></td>
                            <input type="hidden" name="late" value="<?php echo $late_hours?>">
                            <td></td>
                        </tr>
                        <tr>
                            <td>Cash Advance:</td>
                            <td><?php echo $ca ?></td>
                            <input type="hidden" name="ca" value="<?php echo $ca;?>">
                            <td></td>
                        </tr>
                        <tr>
                            <td>SSS:</td>
                            <td><?php echo $sss ?></td>
                            <input type="hidden" name="sss" value="<?php echo $sss;?>">
                            <td></td>
                        </tr>
                        <tr>
                            <td>Pag-ibig:</td>
                            <td><?php echo $pag_ibig ?></td>
                            <input type="hidden" name="pag_ibig" value="<?php echo $pag_ibig;?>">
                            <td></td>
                        </tr>
                        <tr>
                            <td>Philhealth:</td>
                            <td><?php echo $philhealth ?></td>
                            <input type="hidden" name="philhealth" value="<?php echo $philhealth;?>">
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><h5>Total Deductions:</h5></td>
                            <td><h5><?php echo $deductions; ?></h5></td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><h4>Net Pay:</h4></td>
                            <td><h4>P <?php echo $net_pay; ?></h4></td>
                        </tr>
                    </tbody>
                </table>
            <input type="submit" name="submit" value="Save & Print">
            <a href="employees.php">Done</a>
            </form>
        <?php
        unset($_SESSION['days_work']);
    }else{
?>
<form action="" method="post">
    <div class="det">
        <h4>Employee's Information;</h4>
        <span>Employee's ID no.: <?php echo $row['emp_id'] ?></span>
        <br>
        <span>Employee's Name: <?php echo $row['lname'].', '.$row['fname'].' '.$row['mname']; ?></span>
        <br>
        <span>Position: <?php echo $row['position']; ?></span>
        <br>
        <span>Rate per Day: <?php echo $rate; ?></span>
    </div>
    <h4>Work Days;</h4>
    <span>No. of Work Days:</span>
    <input type="number" name="work_days" id="" value="7" required>
    <br>
    <span>From:</span>
    <select name="month_from" id="month" required>
                            <option value="" disabled selected>Month</option>
                            <option value="January" <?php echo($m_now == "January")? 'selected' : '' ?>>January</option>
                            <option value="February" <?php echo($m_now == "February")? 'selected' : '' ?>>February</option>
                            <option value="March" <?php echo($m_now == "March")? 'selected' : '' ?>>March</option>
                            <option value="April" <?php echo($m_now == "April")? 'selected' : '' ?>>April</option>
                            <option value="May" <?php echo($m_now == "May")? 'selected' : '' ?>>May</option>
                            <option value="June" <?php echo($m_now == "June")? 'selected' : '' ?>>June</option>
                            <option value="July" <?php echo($m_now == "July")? 'selected' : '' ?>>July</option>
                            <option value="August" <?php echo($m_now == "August")? 'selected' : '' ?>>August</option>
                            <option value="September" <?php echo($m_now == "September")? 'selected' : '' ?>>September</option>
                            <option value="October" <?php echo($m_now == "October")? 'selected' : '' ?>>October</option>
                            <option value="November" <?php echo($m_now == "November")? 'selected' : '' ?>>November</option>
                            <option value="December" <?php echo($m_now == "December")? 'selected' : '' ?>>December</option>
                        </select>

                        <select name="day_from" id="days" required>
                            <option value="" disabled selected>Day</option>
                            <?php
                                $days = 1;
                                    do{
                                        ?>
                                            <option value="<?php echo $days; ?>"><?php echo $days; ?></option>
                                        <?php
                                        $days++;
                                    }while($days < 32)
                            ?>
                        </select>
                                    <br>
                <span>To:</span>
                            <select name="month_to" id="month" required>
                            <option value="" disabled selected>Month</option>
                            <option value="January" <?php echo($m_now == "January")? 'selected' : '' ?>>January</option>
                            <option value="February" <?php echo($m_now == "February")? 'selected' : '' ?>>February</option>
                            <option value="March" <?php echo($m_now == "March")? 'selected' : '' ?>>March</option>
                            <option value="April" <?php echo($m_now == "April")? 'selected' : '' ?>>April</option>
                            <option value="May" <?php echo($m_now == "May")? 'selected' : '' ?>>May</option>
                            <option value="June" <?php echo($m_now == "June")? 'selected' : '' ?>>June</option>
                            <option value="July" <?php echo($m_now == "July")? 'selected' : '' ?>>July</option>
                            <option value="August" <?php echo($m_now == "August")? 'selected' : '' ?>>August</option>
                            <option value="September" <?php echo($m_now == "September")? 'selected' : '' ?>>September</option>
                            <option value="October" <?php echo($m_now == "October")? 'selected' : '' ?>>October</option>
                            <option value="November" <?php echo($m_now == "November")? 'selected' : '' ?>>November</option>
                            <option value="December" <?php echo($m_now == "December")? 'selected' : '' ?>>December</option>
                        </select>

                        <select name="day_to" id="days" required>
                            <option value="" disabled selected>Day</option>
                            <?php
                                $days = 1;
                                    do{
                                        ?>
                                            <option value="<?php echo $days; ?>"><?php echo $days; ?></option>
                                        <?php
                                        $days++;
                                    }while($days < 32)
                            ?>
                        </select>
                        <br>
                <h4>Additional Pays';</h4>
                <span>Overtime;</span>
                <br>
                <span>No. of Hours (O.T.):</span>
                <input type="number" name="noh" id="" value="0" required>
                <span>( P <?php echo $hour_rate?> per hour O.T.)</span>
                <input type="hidden" name="per_hour" id="" value="<?php echo $hour_rate?>" required>
                <br>
                <span>Holiday Pay</span>
                <input type="number" name="holiday" id="" value="0" required>
                <br>

                <h4>Deductions;</h4>
                <span>Leave w/ out Pay (Days):</span>
                <input type="number" name="leave" id="" value="0" required>
                <br>
                <span>No. of Hours Late:</span>
                <input type="number" name="late" id="" value="0" required>
                <br>
                <span>Cash Advance:</span>
                <input type="number" name="ca" id="" value="0" required>
                <br>
                <span>SSS:</span>
                <input type="number" name="sss" id="" value="33.75" required>
                <br>
                <span>Pag-ibig:</span>
                <input type="number" name="pag-ibig" id="" value="15" required>
                <br>
                <span>Philhealth:</span>
                <input type="number" name="philhealth" id="" value="87.5" required>
                <br>

<input type="submit" name="next" value="Next">
</form>

<?php
    }
?>
        </div>
    </div>
</div>