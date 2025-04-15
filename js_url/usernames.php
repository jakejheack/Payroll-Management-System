<?php
session_start();

include_once("../connection/cons.php");
$con = conns();

if(isset($_POST['idu']))
{
    $username = trim($_POST['idu']);
    $username = str_replace("'", "\'", $username);
}

$check_ids = "SELECT * FROM user WHERE users = '$username'";
$results = $con->query($check_ids) or die ($con->error);
$row = $results->fetch_assoc();
$counts = mysqli_num_rows($results);

if($counts > 0){
    ?>
        <label id="invalid" class="text-danger">Username: <b>"<?= trim($_POST['idu']);?>"</b> is not Available...</label>
    <?php
}elseif(strlen($username) < 1){
    ?>
        <label id="invalid" class="text-danger">Username is Required...</label>
    <?php
}