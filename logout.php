<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_SESSION['Login'])){
$id_user_login_active_time = $_SESSION['Login'];
// user
$sql_user_login_active_time = "UPDATE `user` SET `active_status` = 'OFFLINE' WHERE `ID` = '$id_user_login_active_time'";
$dan_user_login_active_time = $con->query($sql_user_login_active_time) or die ($con->error);
}

unset($_SESSION['Login']);

session_unset();

session_destroy();

echo header("Location:index.php");
exit();