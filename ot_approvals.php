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

        // if($ot_approved == 'No' && $ot_status == 'Yes'){
        //     $reg_hrs = floatval($reg_hrs) - floatval($ot_hrs);
        // }elseif($ot_approved == 'Yes' && $ot_status == 'Yes'){
        //     $reg_hrs = $reg_hrs;
        // }elseif($ot_approved == 'Yes' && $ot_status == ''){
        //     $reg_hrs = floatval($reg_hrs) + floatval($ot_hrs);
        // }elseif($ot_approved == 'No' && $ot_status == ''){
        //     $reg_hrs = $reg_hrs;
        // }

        $approved_by = $_SESSION['Usernames'].' - '.date('m/d/y h:ia');

        $sql_editTime = "UPDATE `dtrs` SET `ot_approved` = '$ot_approved', `approved_by` = '$approved_by'
                    WHERE `dtrs`.`ID` = '$dtrID'";

        if($con->query($sql_editTime) or die ($con->error))
        {
            $_SESSION['CRUD'] = "Updated Successfully!";
            echo header("Location: dtr.php?ID=".$empID."&Department=".$_SESSION['departmentsss']."");
        }else{
            echo "Something went wrong";
        }  

    }else{
        echo header("Location: dtr.php?ID=".$empID."&Department=".$_SESSION['departmentsss']."");
    }
    // echo $dtrID.'<br>'.$empID.'<br>'.$d1a.'<br>'.$d2a.'<br>'.$d3a.'<br>'.$d4a.'<br>'.$d5a.'<br>'.$d6a.'<br>'.$d7a.'<br>'.$d8a;
}

