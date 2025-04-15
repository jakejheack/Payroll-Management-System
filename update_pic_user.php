<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_POST['ID']) && !empty($_FILES["image"]["name"])){

    $id = $_POST['ID'];
    // Get file info 
    $fileName = basename($_FILES["image"]["name"]); 
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 

    // Allow certain file formats 
    $allowTypes = array('jpg','png','jpeg','gif'); 
    if(in_array($fileType, $allowTypes)){
        $image = $_FILES['image']['tmp_name'];
        $imgContent = addslashes(file_get_contents($image));
    }

    $sql = "UPDATE user SET pictures = '$imgContent' WHERE ID ='$id'";     

        if($con->query($sql) or die ($con->error))
            {
                header("Location: " . $_SERVER["HTTP_REFERER"]);    
            }else{
                echo "Something went wrong";
        }
}else{
    header("Location: " . $_SERVER["HTTP_REFERER"]);    
}