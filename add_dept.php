<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_POST['submit']))
{
    $department = trim($_POST['dept']);
    $department = str_replace("'", "\'", $department);
    $department = str_replace("&", "AND", $department);
    $department = strtoupper($department);

           // check if registered
           $check_id = "SELECT * FROM departments WHERE dept = '$department'";
           $result = $con->query($check_id) or die ($con->error);
           $count = mysqli_num_rows($result);

           if($count > 0){
               $_SESSION['check'] = "".$department." already Exists!";
               echo header("Location: departments.php");
           }else{
           
                   $sql = "INSERT INTO `departments` (`ID`, `dept`, `males`, `females`) VALUES (NULL, '$department', '0', '0')";
     
               if($con->query($sql) or die ($con->error))
                   {
                       $_SESSION['status'] = "".$department." added successfully!";
                       echo header("Location: departments.php");
                   }else{
                       echo "Something went wrong";
               }

           }
    
}