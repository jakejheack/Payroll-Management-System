<?php
session_start();

include_once("connection/cons.php");
$con = conns();

// verify
if(isset($_POST['login']))
{
    $c_user = $_POST['user'];
    $c_pw = $_POST['pw'];

    // check if valid
    $check_id = "SELECT * FROM user WHERE BINARY users = '$c_user' && BINARY pw = '$c_pw'";
    $result = $con->query($check_id) or die ($con->error);
    $row = $result->fetch_assoc();
    $count = mysqli_num_rows($result);

    if($count > 0){
        $_SESSION['Login'] = $row['ID'];
        $_SESSION['Usernames'] = $row['names'];
        $_SESSION['Access'] = $row['access'];
        $_SESSION['Store'] = $row['store'];

        echo header("Location: dashboard.php");
    }else{
        $_SESSION['pw'] = "Invalid Username or Password";
        echo header("Location: index.php");
    }
    
}
