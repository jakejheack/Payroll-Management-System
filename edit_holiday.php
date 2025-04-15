<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_POST['changes'])){
    $id = trim($_POST['holidayID']);
    $date = $_POST['datee_field'];
    $name = trim($_POST['namee_field']);
    $name = str_replace("'", "\'", $name);
    $name = str_replace("&", "AND", $name);
    $name = strtoupper($name);

    $type = strtoupper($_POST['types_field']);
 
    $date = date('2023-m-d', strtotime($date));

    $sql = "UPDATE holidays SET datee = '$date', names = '$name', types = '$type' WHERE ID ='$id'";
 
    if($con->query($sql) or die ($con->error))
        {
            $_SESSION['status'] = "Updated Successfully!";
            echo header("Location: holidays.php");
        }else{
            echo "Something went wrong";
    }
}