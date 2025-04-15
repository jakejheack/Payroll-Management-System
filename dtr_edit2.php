<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");

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
    $d5a = $_POST['d5'];
    $d6a = $_POST['d6'];
    $d7a = $_POST['d7'];
    $d8a = $_POST['d8'];

    $in1 = strtotime($d1a);
    $out1 = strtotime($d2a);
    $in2 = strtotime($d3a);
    $out2 = strtotime($d4a);
    $in3 = strtotime($d5a);
    $out3 = strtotime($d6a);
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
        $d1a = '';
    }

    if(!empty($d2a))
    {
        $d2a = date('h:i A', strtotime($d2a));
    }else{
        $d2a = '';
    }

    if(!empty($d3a))
    {
        $d3a = date('h:i A', strtotime($d3a));
    }else{
        $d3a = '';
    }

    if(!empty($d4a))
    {
        $d4a = date('h:i A', strtotime($d4a));
    }else{
        $d4a = '';
    }

    if(!empty($d5a))
    {
        $d5a = date('h:i A', strtotime($d5a));
    }else{
        $d5a = '';
    }

    if(!empty($d6a))
    {
        $d6a = date('h:i A', strtotime($d6a));
    }else{
        $d6a = '';
    }

    if(!empty($d7a))
    {
        $d7a = date('h:i A', strtotime($d7a));
    }else{
        $d7a = '';
    }

    if(!empty($d8a))
    {
        $d8a = date('h:i A', strtotime($d8a));
    }else{
        $d8a = '';
    }

    function isValidDate($date, $format = 'm/d/Y') {
        $dateTime = DateTime::createFromFormat($format, $date);
        return $dateTime && $dateTime->format($format) === $date;
    }
     
    $date = $dtrID;
    $updated_by = $_SESSION['Usernames'].' - '.date('m/d/y h:ia');

    if (isValidDate($date)) {
        $sql_editTime = "INSERT INTO `dtrs` (`ID`, `emp_id`, `monthh`, `datee`, `yearr`, `dayy`, `log_date`, `log_no`,
        `in1`, `out1`, `in2`, `out2`, `in3`, `out3`, `in4`, `out4`, `updated_by`)
        VALUES (NULL, '$empID', '$month', '$datee', '$year', '$day', '$log_dates', '8',
        '$d1a', '$d2a', '$d3a', '$d4a', '$d5a', '$d6a', '$d7a', '$d8a', '$updated_by')";
    }else{
        $sql_editTime = "UPDATE `dtrs` SET `in1` = '$d1a', `out1` = '$d2a', `in2` = '$d3a', `out2` = '$d4a',
                    `in3` = '$d5a', `out3` = '$d6a', `in4` = '$d7a', `out4` = '$d8a', `updated_by` = '$updated_by'
                    WHERE `dtrs`.`ID` = '$dtrID'";
    }

    if($con->query($sql_editTime) or die ($con->error))
    {
        // $_SESSION['CRUD'] = "Updated Successfully!";
        // echo header("Location:".$loc."?ID=".$empID."&froms=".$_SESSION['dtr_From2']."&tos=".$_SESSION['dtr_To2']."");
    }else{
        echo "Something went wrong";
    }  

    if(isset($_POST['empIDS_b']) && isset($_POST['d1_b']) && isset($_POST['d2_b']) && isset($_POST['d3_b']) && isset($_POST['d4_b']) && isset($_POST['dated_b2'])){
        $emp_ids = $_POST['empIDS_b'];
        $in1s = trim($_POST['d1_b']);
        $out1s = trim($_POST['d2_b']);
        $in2s = trim($_POST['d3_b']);
        $out2s = trim($_POST['d4_b']);
        $dates = trim($_POST['dated_b2']);

        if(!empty($in1s)){
            $in1s = date('h:i A', strtotime($in1s));
        }
        if(!empty($out1s)){
            $out1s = date('h:i A', strtotime($out1s));
        }
        if(!empty($in2s)){
            $in2s = date('h:i A', strtotime($in2s));
        }
        if(!empty($out2s)){
            $out2s = date('h:i A', strtotime($out2s));
        }

        // check if already saved
        $check_id = "SELECT * FROM sched_custom WHERE dated = '$dates' && emp_id = '$emp_ids'";
        $query = mysqli_query($con,$check_id);
        $row = mysqli_fetch_assoc($query);
        $count = mysqli_num_rows($query);

        $updated_by = $_SESSION['Usernames'].' - '.date('m/d/y h:ia');

        if($count > 0){
            $id = $row['ID'];

            $sql = "UPDATE `sched_custom` SET `in1` = '$in1s', `out1` = '$out1s', `in2` = '$in2s', `out2` = '$out2s', `created_by` = '$updated_by' WHERE `ID` = '$id'";
        }else{

            $sql = "INSERT INTO `sched_custom` (`ID`, `emp_id`, `dated`, `in1`, `out1`, `in2`, `out2`, `created_by`) 
                    VALUES (NULL, '$emp_ids', '$dates', '$in1s', '$out1s', '$in2s', '$out2s', '$updated_by')";

        }

        $d = ($con->query($sql) or die ($con->error));
        
    }
    

        header("Location: " . $_SERVER["HTTP_REFERER"]);    
}