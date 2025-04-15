<?php 
session_start();

include_once("connection/cons.php");
$con = conns();

$id = trim($_POST['id']);

$emp_ID = $_SESSION['emp_id'];

$log_date = date('l, F d, Y', strtotime($id));
$day =  strtolower(date('D', strtotime($id)));

$sql = "SELECT * FROM sched_custom WHERE dated = '$log_date' && emp_id = '$emp_ID'";
$query = mysqli_query($con,$sql);
$row = mysqli_fetch_assoc($query);
$count = mysqli_num_rows($query);


if($count > 0){
    if(!empty($row['in1'])){
        $in1 = date('H:i', strtotime($row['in1']));
    }else{
            $in1 = "";
    }
    if(!empty($row['out1'])){
            $out1 = date('H:i', strtotime($row['out1']));
    }else{
            $out1 = "";
    }

    if(!empty($row['in2'])){
            $in2 = date('H:i', strtotime($row['in2']));
    }else{
            $in2 = "";
    }
    if(!empty($row['out2'])){
            $out2 = date('H:i', strtotime($row['out2']));
    }else{
            $out2 = "";
    }

    $updated_by = $row['created_by'];
}else{
    $in1 = "";
    $out1 = "";
    $in2 = "";
    $out2 = "";
    $updated_by = '';
}

// echo json_encode($row);
?>

{
    "log_date":"<?= $log_date ?>",
    "in1":"<?= $in1?>",
    "out1":"<?= $out1?>",
    "in2":"<?= $in2?>",
    "out2":"<?= $out2?>",
    "created_by":"<?= $updated_by?>"
}