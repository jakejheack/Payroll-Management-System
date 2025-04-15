<?php 
session_start();

include_once("connection/cons.php");
$con = conns();

$id = trim($_POST['id']);
//  $id = '09/18/2023';
 $ddd = $id;

function isValidDate($date, $format = 'm/d/Y') {
     $dateTime = DateTime::createFromFormat($format, $date);
     return $dateTime && $dateTime->format($format) === $date;
 }
  
 $date = $id;
 if (isValidDate($date)) {
     $id = 0;
 }

$sql = "SELECT * FROM dtrs WHERE ID='$id' LIMIT 1";
$query = mysqli_query($con,$sql);
$row = mysqli_fetch_assoc($query);
$count = mysqli_num_rows($query);

// echo json_encode($row);

if($count > 0){
        $id = $row['ID'];
        $emp_id = $row['emp_id'];
        $month = $row['monthh'];
        $date = $row['datee'];
        $year = $row['yearr'];
        $day = $row['dayy'];
        $log_date = $row['log_date'];
        $log_no = $row['log_no'];

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

        if(!empty($row['in3'])){
                $in3 = date('H:i', strtotime($row['in3']));
        }else{
                $in3 = "";
        }
        if(!empty($row['out3'])){
                $out3 = date('H:i', strtotime($row['out3']));
        }else{
                $out3 = "";
        }

        if(!empty($row['in4'])){
                $in4 = date('H:i', strtotime($row['in4']));
        }else{
                $in4 = "";
        }
        if(!empty($row['out4'])){
                $out4 = date('H:i', strtotime($row['out4']));
        }else{
                $out4 = "";
        }

        $remarks = $row['remarks'];
        $total_hrs = $row['total_hrs'];
        $total_ot = $row['total_ot'];
        $ot_approved = $row['ot_approved'];
        $updated_by = $row['approved_by'];
        $updated_time = $row['updated_by'];
        $off_status = $row['off_status'];
        $off_approved = $row['off_approved'];

        if(empty($ot_approved)){
                $ot_approved = "Pending";
        }

        if(empty($off_status)){
                $off_status = "Pending";
        }

}else{
        $id = "";
        $emp_id = "";
        $log_date = date('l, F d, Y', strtotime($ddd));
        $log_no = "";
        $in1 = "";
        $out1 = "";
        $in2 = "";
        $out2 = "";
        $in3 = "";
        $out3 = "";
        $in4 = "";
        $out4 = "";
        $total_hrs = "";
        $total_ot = "";
        $ot_approved = "Pending";
        $off_status = "";
        $off_approved = "";
        $updated_by = "";
        $updated_time = "";
}

$emp_IDS = $_SESSION['emp_id'];

$sql_sched_custom = "SELECT * FROM sched_custom WHERE dated = '$log_date' && emp_id = '$emp_IDS'";
$query_sched_custom = mysqli_query($con,$sql_sched_custom);
$row_sched_custom = mysqli_fetch_assoc($query_sched_custom);
$count_sched_custom = mysqli_num_rows($query_sched_custom);

if($count_sched_custom > 0){
        if(!empty($row_sched_custom['in1'])){
                $in1_sched = date('H:i', strtotime($row_sched_custom['in1']));
        }else{
                $in1_sched = "";
        }
        if(!empty($row_sched_custom['out1'])){
                $out1_sched = date('H:i', strtotime($row_sched_custom['out1']));
        }else{
                $out1_sched = "";
        }
    
        if(!empty($row_sched_custom['in2'])){
                $in2_sched = date('H:i', strtotime($row_sched_custom['in2']));
        }else{
                $in2_sched = "";
        }
        if(!empty($row_sched_custom['out2'])){
                $out2_sched = date('H:i', strtotime($row_sched_custom['out2']));
        }else{
                $out2_sched = "";
        }

        $updated_by_sched = $row_sched_custom['created_by'];
        $sched_remarks = 'Customized Schedule';
}else{
        $day_sched = strtolower(date('D', strtotime($log_date)));
        // regular schedule
        $sql_sched = "SELECT * FROM schedules WHERE emp_id = '$emp_IDS'";
        $dan_sched = $con->query($sql_sched) or die ($con->error);
        $row_sched = $dan_sched->fetch_assoc();
        $count_sched = mysqli_num_rows($dan_sched);

        if($count_sched > 0){
                do{
                        $work_stat = $row_sched[$day_sched];

                        if($work_stat == 'ON'){
                                $in1_sched = date('H:i', strtotime($row_sched[$day_sched.'_in1']));
                                $out1_sched = date('H:i', strtotime($row_sched[$day_sched.'_out1']));
                                $in2_sched = date('H:i', strtotime($row_sched[$day_sched.'_in2']));
                                $out2_sched = date('H:i', strtotime($row_sched[$day_sched.'_out2']));
                                $updated_by_sched = '';
                                $sched_remarks = 'Regular Schedule';
                        }else{
                                $in1_sched = "";
                                $out1_sched = "";
                                $in2_sched = "";
                                $out2_sched = "";
                                $updated_by_sched = '';
                                $sched_remarks = 'Day Off';
                        }
                }while($row_sched = $dan_sched->fetch_assoc());
        }else{
                $in1_sched = "";
                $out1_sched = "";
                $in2_sched = "";
                $out2_sched = "";
                $updated_by_sched = '';
                $sched_remarks = 'W/out Schedule';
        }
}
?>

{
    "ID":"<?= $id?>",
    "emp_id":"<?= $emp_id?>",
    "log_date":"<?= $log_date?>",
    "log_no":"<?= $log_no?>",
    "in1":"<?php if($in1 != '00:00')
            { echo $in1;  }?>",
    "out1":"<?php if($out1 != '00:00')
            { echo $out1; }?>",
    "in2":"<?php if($in2 != '00:00')
            { echo $in2; }?>",
    "out2":"<?php if($out2 != '00:00')
            { echo $out2; }?>",
    "in3":"<?php if($in3 != '00:00')
            { echo $in3; }?>",
    "out3":"<?php if($out3 != '00:00')
            { echo $out3; }?>",
    "in4":"<?php if($in4 != '00:00')
            { echo $in4; }?>",
    "out4":"<?php if($out4 != '00:00')
            { echo $out4; }?>",
    "total_hrs":"<?= $total_hrs?>",
    "total_ot":"<?= $total_ot?>",
    "ot_approved":"<?= $ot_approved?>",
    "updated_by":"<?= $updated_by?>",
    "updated_time":"<?= $updated_time?>",
    "in1_sched":"<?= $in1_sched?>",
    "out1_sched":"<?= $out1_sched?>",
    "in2_sched":"<?= $in2_sched?>",
    "out2_sched":"<?= $out2_sched?>",
    "created_by_sched":"<?= $updated_by_sched?>",
    "sched_remarks":"<?= $sched_remarks?>",
    "off_status":"<?= $off_status?>",
    "off_approved":"<?= $off_approved?>"
}