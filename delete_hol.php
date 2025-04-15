<?php

if(!isset($_SESSION))
{
    session_start();
}

include_once("connection/cons.php");
$con = conns();

    $sql = "DELETE FROM holidays WHERE ID = '".$_GET['del_id']."'";
        if($dan = $con->query($sql) or die ($con->error))
        {
            $_SESSION['Delete_Holiday'] = "Information Deleted";
            echo header("Location: holidays.php");
        }else{
            echo "Something went wrong";
        }