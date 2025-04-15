<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");

if(isset($_POST['changes']))
{

    $empID = trim($_POST['empIDS_a']);
    $dtrID = trim($_POST['dtrID_a']);
    $ot_approved = trim($_POST['ot_approved']);

    $sql = "SELECT * FROM dtrs WHERE ID='$dtrID'";
    $query = mysqli_query($con,$sql);
    $row = mysqli_fetch_assoc($query);
    $count = mysqli_num_rows($query);

    if($count > 0){

        $ot_status = $row['ot_approved'];
        $reg_hrs = $row['total_hrs'];
        $ot_hrs = $row['total_ot'];

        $approved_by = $_SESSION['Usernames'].' - '.date('m/d/y h:ia');

        $sql_editTime = "UPDATE `dtrs` SET `ot_approved` = '$ot_approved', `approved_by` = '$approved_by'
                    WHERE `dtrs`.`ID` = '$dtrID'";

    if(isset($_SESSION['Back_Location'])){
        $loc = $_SESSION['Back_Location'];
    }else{
        $loc = 'daily_time_record_employees.php';
    }

        if($con->query($sql_editTime) or die ($con->error))
        {
            $_SESSION['CRUD'] = "Updated Successfully!";
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }else{
            echo "Something went wrong";
        }  

    }else{
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
}

