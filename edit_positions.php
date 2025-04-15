<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_POST['changes']))
{
    $position = trim($_POST['position_name']);
    $position = str_replace("'", "\'", $position);
    $position = str_replace("&", "AND", $position);
    $position = strtoupper($position);

    $id = $_POST['pos_ID'];
    
    // positions
    $pos_c = "SELECT * FROM positions WHERE pos = '$position'";
    $d_pos_c = $con->query($pos_c) or die ($con->error);
    $row_pos_c = $d_pos_c->fetch_assoc();
    $count = mysqli_num_rows($d_pos_c);

    $id_pos = $row_pos_c['ID'];

    $sql_p_name = "SELECT * FROM positions WHERE ID = '$id'";
    $d_p_name = $con->query($sql_p_name) or die ($con->error);
    $row_p_name = $d_p_name->fetch_assoc();
    $count_p = mysqli_num_rows($d_p_name);

    $ex_name = $row_p_name['pos'];
    $ex_name = str_replace("'", "\'", $ex_name);

    if($count > 0 && $id != $id_pos){
            $_SESSION['check'] = $position.' POSITION is already Exists!';
            echo header("Location: positions.php");
    }else{
        $sql = "UPDATE positions SET pos = '$position' WHERE ID ='$id'";
 
        $sql_position_update = "UPDATE `employees` SET `position` = '$position' WHERE `position` = '$ex_name'";
        $query= mysqli_query($con,$sql_position_update);

        if($con->query($sql) or die ($con->error))
            {
                $_SESSION['status'] = "Updated Successfully!";
                echo header("Location: positions.php");
            }else{
                echo "Something went wrong";
        } 
    } 

}