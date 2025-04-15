<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_POST['submit'])){
    $id = $_POST['rate_ID'];
    $rates = $_POST['per_hour'];
    if($rates <= 0){
        $rates = '';
    }

    $sql = "UPDATE `employees` SET `allowances` = '$rates' WHERE `ID` = '$id'";
    $rate_sql = ($con->query($sql) or die ($con->error));

    header("Location: " . $_SERVER["HTTP_REFERER"]);
}else{
    echo header("Location: logout.php");
}
?>