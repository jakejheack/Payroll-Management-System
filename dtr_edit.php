<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_GET['dtr_ID'])){
    $dtr_ID = trim($_GET['dtr_ID']);

    $sql_dtr = "SELECT * FROM dtrs WHERE ID = '$dtr_ID'";
    $dan_dtr = $con->query($sql_dtr) or die ($con->error);
    $row_dtr = $dan_dtr->fetch_assoc();
    $count = mysqli_num_rows($dan_dtr);

    if($count > 0)
    {
        $emp_id = $row_dtr['emp_id'];
        $log_date = $row_dtr['log_date'];
        $d1 = $row_dtr['in1'];
        $d2 = $row_dtr['out1'];
        $d3 = $row_dtr['in2'];
        $d4 = $row_dtr['out2'];
        $d5 = $row_dtr['in3'];
        $d6 = $row_dtr['out3'];
        $d7 = $row_dtr['in4'];
        $d8 = $row_dtr['out4'];


            // employees info
            $sql_emp = "SELECT * FROM employees WHERE ID = '$emp_id'";
            $dan_emp = $con->query($sql_emp) or die ($con->error);
            $row_emp = $dan_emp->fetch_assoc();
            $count_emp = mysqli_num_rows($dan_emp);

            if($count_emp > 0){
                $emp_name = $row_emp['lname'].', '.$row_emp['fname'].' '.$row_emp['mname'].' '.$row_emp['extname'];
                $position = $row_emp['position'];
            }
            
    }
}

if(isset($_POST['changes']))
{

    $empID = trim($_POST['empIDS']);
    $dtrID = trim($_POST['dtrID']);
    $log_dates = date('l, F d, Y', strtotime($dtrID));

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
    $datee = date('d', strtotime($log_dates));
    $year = date('Y', strtotime($log_dates));
    $day = date('l', strtotime($log_dates));

    if(!empty($d1a))
    {
        $d1a = date('h:i A', strtotime($d1a));
    }else{
        $d1a = '00:00';
    }

    if(!empty($d2a) && $d1a != '00:00')
    {
        $d2a = date('h:i A', strtotime($d2a));
        $hr1 = abs($out1 - $in1)/3600;
    }elseif(!empty($d2a) && $d1a == '00:00'){
        $d2a = date('h:i A', strtotime($d2a));
        $hr1 = 0;
    }elseif(empty($d2a) && $d1a == '00:00'){
        $d2a = '00:00';
        $hr1 = 0;
    }

    if(!empty($d3a))
    {
        $d3a = date('h:i A', strtotime($d3a));
    }else{
        $d3a = '00:00';
    }

    if(!empty($d4a) && $d3a != '00:00')
    {
        $d4a = date('h:i A', strtotime($d4a));
        $hr2 = abs($out2 - $in2)/3600;
    }elseif(!empty($d4a) && $d3a == '00:00'){
        $d4a = date('h:i A', strtotime($d4a));
        $hr2 = 0;
    }elseif(empty($d4a) && $d3a == '00:00'){
        $d4a = '00:00';
        $hr2 = 0;
    }

    if(!empty($d5a))
    {
        $d5a = date('h:i A', strtotime($d5a));
    }else{
        $d5a = '00:00';
    }

    if(!empty($d6a) && $d5a != '00:00')
    {
        $d6a = date('h:i A', strtotime($d6a));
        $hr3 = abs($out3 - $in3)/3600;
    }elseif(!empty($d6a) && $d5a == '00:00'){
        $d6a = date('h:i A', strtotime($d6a));
        $hr3 = 0;
    }

    if(!empty($d7a))
    {
        $d7a = date('h:i A', strtotime($d7a));
    }else{
        $d7a = '00:00';
    }

    if(!empty($d8a) && $d7a != '00:00')
    {
        $d8a = date('h:i A', strtotime($d8a));
        $hr4 = abs($out4 - $in4)/3600;
    }elseif(!empty($d8a) && $d7a == '00:00'){
        $d8a = date('h:i A', strtotime($d8a));
        $hr4 = 0;
    }elseif(empty($d8a) && $d7a == '00:00'){
        $d8a = '00:00';
        $hr4 = 0;
    }

    $total_hrs = $hr1 + $hr2 + $hr3;
    $total_hrs = round($total_hrs,2);
    $total_ot = round($hr4,2);

    if($total_hrs > 8.08)
    {
        $total_hrs = '8.08';
    }

    function isValidDate($date, $format = 'm/d/Y') {
        $dateTime = DateTime::createFromFormat($format, $date);
        return $dateTime && $dateTime->format($format) === $date;
    }
     
    $date = $dtrID;
    $updated_by = $_SESSION['Usernames'].' - '.date('m/d/y h:ia');

    if (isValidDate($date)) {
        $sql_editTime = "INSERT INTO `dtrs` (`ID`, `emp_id`, `monthh`, `datee`, `yearr`, `dayy`, `log_date`, `log_no`,
        `in1`, `out1`, `in2`, `out2`, `in3`, `out3`, `in4`, `out4`, `remarks`, `total_hrs`, `total_ot`, `ot_approved`, `updated_by`)
        VALUES (NULL, '$empID', '$month', '$datee', '$year', '$day', '$log_dates', '8',
        '$d1a', '$d2a', '$d3a', '$d4a', '$d5a', '$d6a', '$d7a', '$d8a', '', '$total_hrs', '$total_ot', '', '$updated_by')";
    }else{
        $sql_editTime = "UPDATE `dtrs` SET `in1` = '$d1a', `out1` = '$d2a', `in2` = '$d3a', `out2` = '$d4a',
                    `in3` = '$d5a', `out3` = '$d6a', `in4` = '$d7a', `out4` = '$d8a', `total_hrs` = '$total_hrs', 
                    `total_ot` = '$total_ot', `ot_approved` = '', `updated_by` = '$updated_by'
                    WHERE `dtrs`.`ID` = '$dtrID'";
    }

        if($con->query($sql_editTime) or die ($con->error))
        {
            $_SESSION['CRUD'] = "Updated Successfully!";
            echo header("Location: dtr.php?ID=".$empID."&Department=".$_SESSION['departmentsss']."");
        }else{
            echo "Something went wrong";
        }  

    // echo $dtrID.'<br>'.$empID.'<br>'.$d1a.'<br>'.$d2a.'<br>'.$d3a.'<br>'.$d4a.'<br>'.$d5a.'<br>'.$d6a.'<br>'.$d7a.'<br>'.$d8a;
}

