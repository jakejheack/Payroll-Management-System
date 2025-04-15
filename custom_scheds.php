<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");

if(isset($_POST['changes'])){
    $emp_id = $_POST['empIDS_b'];
    $in1 = trim($_POST['d1_b']);
    $out1 = trim($_POST['d2_b']);
    $in2 = trim($_POST['d3_b']);
    $out2 = trim($_POST['d4_b']);
    $date = trim($_POST['dated_b2']);

    $in1 = date('h:i A', strtotime($in1));
    $out1 = date('h:i A', strtotime($out1));
    $in2 = date('h:i A', strtotime($in2));
    $out2 = date('h:i A', strtotime($out2));

    // check if already saved
    $check_id = "SELECT * FROM sched_custom WHERE dated = '$date' && emp_id = '$emp_id'";
    $query = mysqli_query($con,$check_id);
    $row = mysqli_fetch_assoc($query);
    $count = mysqli_num_rows($query);

    $updated_by = $_SESSION['Usernames'].' - '.date('m/d/y h:ia');

    if($count > 0){
        $id = $row['ID'];

        $sql = "UPDATE `sched_custom` SET `in1` = '$in1', `out1` = '$out1', `in2` = '$in2', `out2` = '$out2', `created_by` = '$updated_by' WHERE `ID` = '$id'";
    }else{

        $sql = "INSERT INTO `sched_custom` (`ID`, `emp_id`, `dated`, `in1`, `out1`, `in2`, `out2`, `created_by`) 
                VALUES (NULL, '$emp_id', '$date', '$in1', '$out1', '$in2', '$out2', '$updated_by')";

    }

    if($con->query($sql) or die ($con->error))
    {
        // $_SESSION['CRUD'] = "Updated Successfully!";
        echo header("Location: dtr.php?ID=".$emp_id."&Department=".$_SESSION['departmentsss']."");
    }else{
        echo "Something went wrong";
    }
        
}
?>