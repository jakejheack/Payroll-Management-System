<?php 
include_once("connection/cons.php");
$con = conns();

$id = trim($_POST['id']);

$sql = "SELECT * FROM departments WHERE ID='$id' LIMIT 1";
$query = mysqli_query($con,$sql);
$row = mysqli_fetch_assoc($query);
$count = mysqli_num_rows($query);

// echo json_encode($row);

?>
{
        "dept":"<?= $row['dept']?>"
}