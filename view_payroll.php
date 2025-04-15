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
    header("Location: payroll.php");
}

// payroll
$sql = "SELECT * FROM net_pay WHERE ID = '$id'";
$dan = $con->query($sql) or die ($con->error);
$row = $dan->fetch_assoc();

$work = $row['work_days'];
$rate = $row['rate'];
    
$noh_ot = $row['ot_hrs'];
$per_hour = $row['ot_rate'];

$leave = $row['leaves'];

// basic salary
$basic = $work * $rate;
// additions
$ot = $noh_ot * $per_hour;
$holiday = $row['holiday'];
// total additions + salary
$additional = $basic + $ot + $holiday;
// deductions
$leaves = $leave * $rate;
$l = $rate / 8;
$late_hours = $row['late'];
$late_deduction = $late_hours * $l;

$ca = $row['ca'];
$sss = $row['sss'];
$pag_ibig = $row['pag_ibig'];
$philhealth = $row['philhealth'];
// total deductions
$deductions = $leaves + $late_deduction + $ca + $sss + $pag_ibig + $philhealth;
// net pay
$net_pay = $additional - $deductions;


// header
include("includes/header.php");
include("includes/menus.php");
?>

<div class="containers">
    <h3>Payroll</h3>
    <div class="contents">
        <div class="emp_payroll">
<table>
    <thead>
        <tr>
        <th colspan="3">Earnings Statement</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Period:</td>
            <td colspan="2"><?php echo $row['period'] ?></td>
        </tr>
        <tr>
            <td>Employee's ID no: </td>
            <td colspan="2"><?php echo $row['emp_id'] ?></td>
        </tr>
        <tr>
            <td>Name: </td>
            <td colspan="2">Name: <?php echo $row['emp_name']; ?></td>
        </tr>
        <tr>
            <td>Position:</td>
            <td colspan="2">Position: <?php echo $row['position']; ?></td>
        </tr>
        <tr>
            <td>Rate per Day: </td>
            <td colspan="2"><?php echo $row['rate']; ?></td>
        </tr>
        <tr>
            <td>-</td>
            <td>-</td>
            <td>-</td>
        </tr>
        <tr>
            <td></td>
            <td><h4>Basic Pay: </h4></td>
            <td><h4><?php echo $row['basic_pay']; ?></h4></td>
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
        </tr>
        <tr>
            <td>Holiday Pay:</td>
            <td><?php echo $holiday; ?></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td ><h5>Total Gross Pay:</h5></td>
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
            <td></td>
        </tr>
        <tr>
            <td>SSS:</td>
            <td><?php echo $sss ?></td>
            <td></td>
        </tr>
        <tr>
            <td>Pag-ibig:</td>
            <td><?php echo $pag_ibig ?></td>
            <td></td>
        </tr>
        <tr>
            <td>Philhealth:</td>
            <td><?php echo $philhealth ?></td>
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

<a href="print_employees_payroll.php?ID=<?php echo $id; ?>" id="Print_p" target="_blank">Print Record</a>
<a href="payroll.php">Back</a>

</div>
</div>
</div>