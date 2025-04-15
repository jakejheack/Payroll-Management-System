<?php

if(!isset($_SESSION))
{
    session_start();
}

include_once("connection/cons.php");
$con = conns();

    $sql = "DELETE FROM employees WHERE ID = '".$_GET['del_id']."'";
        if($dan = $con->query($sql) or die ($con->error))
        {
            $_SESSION['del'] = "Information Deleted";
            echo header("Location: employees.php");
        }else{
            echo "Something went wrong";
        }