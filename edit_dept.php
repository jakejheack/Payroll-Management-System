<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_POST['changes']))
{
    $dept = trim($_POST['dept_name']);
    $dept = str_replace("'", "\'", $dept);
    $dept = str_replace("&", "AND", $dept);
    $dept = strtoupper($dept);

    $id = $_POST['deptID'];

    // check if existed
    $sql_dept = "SELECT * FROM departments WHERE dept = '$dept'";
    $d_dept = $con->query($sql_dept) or die ($con->error);
    $row_dept = $d_dept->fetch_assoc();
    $count = mysqli_num_rows($d_dept);

    $id_dept = $row_dept['ID'];

    $sql_d_name = "SELECT * FROM departments WHERE ID = '$id'";
    $d_d_name = $con->query($sql_d_name) or die ($con->error);
    $row_d_name = $d_d_name->fetch_assoc();
    $count_d = mysqli_num_rows($d_d_name);

    $ex_name = $row_d_name['dept'];
    $ex_name = str_replace("'", "\'", $ex_name);

    if($count > 0 && $id != $id_dept){
            $_SESSION['check'] = $dept.' is already Exists!';
            echo header("Location: departments.php");
    }else{
        $sql = "UPDATE departments SET dept = '$dept' WHERE ID ='$id'";
 
        $sql_dept_update = "UPDATE `employees` SET `dept` = '$dept' WHERE `dept` = '$ex_name'";
        $query= mysqli_query($con,$sql_dept_update);

        if($con->query($sql) or die ($con->error))
            {
                $_SESSION['status'] = "Updated Successfully!";
                echo header("Location: departments.php");
            }else{
                echo "Something went wrong";
        } 
    } 

}
