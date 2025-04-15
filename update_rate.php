<?php
session_start();

include_once("connection/cons.php");
$con = conns();


if(isset($_POST['submit'])){
    $id = $_POST['rate_ID'];
    $rates = $_POST['per_hour'];
    $ot_add = $rates * 0.25;
    $ot_rate = $rates + $ot_add;

    $sql = "UPDATE `employees` SET `rates` = '$rates', `ot` = '$ot_rate' WHERE `ID` = '$id'";
    $rate_sql = ($con->query($sql) or die ($con->error));

    header("Location: " . $_SERVER["HTTP_REFERER"]);
}else{
    echo header("Location: logout.php");
}
?>