<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_POST['submit']))
{
    $position = trim($_POST['position']);
    $position = str_replace("'", "\'", $position);
    $position = str_replace("&", "AND", $position);
    $position = strtoupper($position);

           // check if registered
           $check_id = "SELECT * FROM positions WHERE pos = '$position'";
           $result = $con->query($check_id) or die ($con->error);
           $count = mysqli_num_rows($result);

           if($count > 0){
               $_SESSION['check'] = "".$position." POSITION already Exists!";
               echo header("Location: positions.php");
           }else{
           
                   $sql = "INSERT INTO `positions` (`ID`, `pos`) VALUES (NULL, '$position')";
     
               if($con->query($sql) or die ($con->error))
                   {
                       $_SESSION['status'] = "".$position." POSITION added successfully!";
                       echo header("Location: positions.php");
                   }else{
                       echo "Something went wrong";
               }

           }
    
}