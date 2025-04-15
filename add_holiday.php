<?php
session_start();

include_once("connection/cons.php");
$con = conns();


if(isset($_POST['submit']))
{
    $date = $_POST['datee'];
    $name = trim($_POST['namee']);
    $name = str_replace("'", "\'", $name);
    $name = str_replace("&", "AND", $name);
    $name = strtoupper($name);

    $type = strtoupper($_POST['types']);
 
    $date = date('2023-m-d', strtotime($date));

    $sql = "INSERT INTO `holidays` (`ID`, `datee`, `names`, `types`)
            VALUES (NULL, '$date', '$name', '$type')";

    if($con->query($sql) or die ($con->error))
    {
        $_SESSION['status'] = "Successfully Saved!";
        echo header ("Location: holidays.php");
    }
}

?>