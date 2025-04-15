<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_GET['ID']))
{
    $emp_ids = $_GET['ID'];
    $dates = $_GET['dates'];

    $log_date = date('l, F d, Y', strtotime($dates));

    // employees info
    $sql_emp = "SELECT * FROM employees WHERE ID = '$emp_ids'";
    $dan_emp = $con->query($sql_emp) or die ($con->error);
    $row_emp = $dan_emp->fetch_assoc();
    $count_emp = mysqli_num_rows($dan_emp);

    if($count_emp > 0){
        $emp_name = $row_emp['lname'].', '.$row_emp['fname'].' '.$row_emp['mname'].' '.$row_emp['extname'];
        $position = $row_emp['position'];
    }

}

if(isset($_POST['changes']))
{
    $empID = $_POST['empIDS'];
    $log_dates = $_POST['log_date'];
    $d1a = $_POST['d1'];
    $d2a = $_POST['d2'];
    $d3a = $_POST['d3'];
    $d4a = $_POST['d4'];
    // $d5a = $_POST['d5'];
    // $d6a = $_POST['d6'];
    $d7a = $_POST['d7'];
    $d8a = $_POST['d8'];

    $in1 = strtotime($d1a);
    $out1 = strtotime($d2a);
    $in2 = strtotime($d3a);
    $out2 = strtotime($d4a);
    // $in3 = strtotime($d5a);
    // $out3 = strtotime($d6a);
    $in4 = strtotime($d7a);
    $out4 = strtotime($d8a);

    $month =  date('F', strtotime($log_dates));
    $date = date('d', strtotime($log_dates));
    $year = date('Y', strtotime($log_dates));
    $day = date('l', strtotime($log_dates));

    if(!empty($d1a))
    {
        $d1a = date('h:i A', strtotime($d1a));
    }else{
        $d1a = '00:00';
    }

    if(!empty($d2a) && !empty($d1a))
    {
        $d2a = date('h:i A', strtotime($d2a));
        $hr1 = abs($out1 - $in1)/3600;
    }else{
        $d2a = '00:00';
        $hr1 = 0;
    }

    if(!empty($d3a))
    {
        $d3a = date('h:i A', strtotime($d3a));
    }else{
        $d3a = '00:00';
    }

    if(!empty($d4a) && !empty($d3a))
    {
        $d4a = date('h:i A', strtotime($d4a));
        $hr2 = abs($out2 - $in2)/3600;
    }else{
        $d4a = '00:00';
        $hr2 = 0;
    }

    if(!empty($d5a))
    {
        $d5a = date('h:i A', strtotime($d5a));
    }else{
        $d5a = '00:00';
    }

    if(!empty($d6a) && !empty($d5a))
    {
        $d6a = date('h:i A', strtotime($d6a));
        $hr3 = abs($out3 - $in3)/3600;
    }else{
        $d6a = '00:00';
        $hr3 = 0;
    }

    if(!empty($d7a))
    {
        $d7a = date('h:i A', strtotime($d7a));
    }else{
        $d7a = '00:00';
    }

    if(!empty($d8a) && !empty($d7a))
    {
        $d8a = date('h:i A', strtotime($d8a));
        $hr4 = abs($out4 - $in4)/3600;
    }else{
        $d8a = '00:00';
        $hr4 = 0;
    }

    $total_hrs = $hr1 + $hr2 + $hr3;
    $total_hrs = round($total_hrs,2);
    $total_ot = round($hr4,2);

    $sql_NewTime = "INSERT INTO `dtrs` (`ID`, `emp_id`, `monthh`, `datee`, `yearr`, `dayy`, `log_date`, `log_no`,
                    `in1`, `out1`, `in2`, `out2`, `in3`, `out3`, `in4`, `out4`, `remarks`, `total_hrs`, `total_ot`)
                    VALUES (NULL, '$empID', '$month', '$date', '$year', '$day', '$log_dates', '8',
                    '$d1a', '$d2a', '$d3a', '$d4a', '$d5a', '$d6a', '$d7a', '$d8a', '', '$total_hrs', '$total_ot')";
    
    if($con->query($sql_NewTime) or die ($con->error))
    {
        $_SESSION['CRUD'] = "Updated Successfully!";
        echo header("Location: dtr.php?ID=".$empID."&Department=".$_SESSION['departmentsss']."");
    }else{
        echo "Something went wrong";
    }  

}

// header
include("includes/header.php");
include("includes/menus.php");

?>

<div class="containers">
    <h3>Daily Time Record</h3>
        <div class="contents">
            <form action="" method="post">
                <h4>Employee's Information</h4>
                    <span>Name: <?php echo $emp_name?></span>
                    <br>
                    <span>Position: <?php echo $position?></span>
                    <br>
                    <h4>Date: <?php echo $log_date?></h4>

                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>In</th>
                                <th>Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>AM</td>
                                <td><input type="time" name="d1" id=""></td>
                                <td><input type="time" name="d2" id=""></td>
                            </tr>
                            <tr>
                                <td>PM</td>
                                <td><input type="time" name="d3" id=""></td>
                                <td><input type="time" name="d4" id=""></td>
                            </tr>
                            <tr>
                                <td>O.T.</td>
                                <td><input type="time" name="d7" id=""></td>
                                <td><input type="time" name="d8" id=""></td>
                            </tr>
                        </tbody>
                    </table>

                    <input type="hidden" name="log_date" value="<?php echo $log_date?>">
                    <input type="hidden" name="empIDS" value="<?php echo $emp_ids?>">
                    <input type="submit" value="Save Changes" name="changes">
                    <a href="dtr.php">Cancel</a>

            </form>
        </div>
</div>