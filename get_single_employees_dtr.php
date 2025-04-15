<?php 
include_once("connection/cons.php");
$con = conns();

$id = trim($_POST['id']);

$sql = "SELECT * FROM employees WHERE ID='$id' LIMIT 1";
$query = mysqli_query($con,$sql);
$row = mysqli_fetch_assoc($query);
$count = mysqli_num_rows($query);

$fullname = $row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname'];
$fullnames = $row['lname'].', '.$row['fname'].' '.$row['mname'].' '.$row['extname'];
$rate = $row['rates'];
$allowance = $row['allowances'];

empty($rate) ? $rate = 0 : '';
empty($allowance) ? $allowance = 0 : '';

// schedules
$sql_sched = "SELECT * FROM schedules WHERE emp_id = '$id'";
$dan_sched = $con->query($sql_sched) or die ($con->error);
$row_sched = $dan_sched->fetch_assoc();
$count_sched = mysqli_num_rows($dan_sched);

if($count_sched > 0)
{
    $mons = $row_sched['mon'];
    $tues = $row_sched['tue'];
    $weds = $row_sched['wed'];
    $thus = $row_sched['thu'];
    $fris = $row_sched['fri'];
    $sats = $row_sched['sat'];
    $suns = $row_sched['sun'];
    $breaks = $row_sched['min_break'];

    if($mons == 'ON'){
        $mon_in1 = date('H:i', strtotime($row_sched['mon_in1']));
        $mon_out1 = date('H:i', strtotime($row_sched['mon_out1']));
        $mon_in2 = date('H:i', strtotime($row_sched['mon_in2']));
        $mon_out2 = date('H:i', strtotime($row_sched['mon_out2']));       
    }else{
        $mon_in1 = '';
        $mon_out1 = '';
        $mon_in2 = '';
        $mon_out2 = '';
    }

    if($tues == 'ON'){
        $tue_in1 = date('H:i', strtotime($row_sched['tue_in1']));
        $tue_out1 = date('H:i', strtotime($row_sched['tue_out1']));
        $tue_in2 = date('H:i', strtotime($row_sched['tue_in2']));
        $tue_out2 = date('H:i', strtotime($row_sched['tue_out2']));
    }else{
        $tue_in1 = '';
        $tue_out1 = '';
        $tue_in2 = '';
        $tue_out2 = '';
    }

    if($weds == 'ON'){
        $wed_in1 = date('H:i', strtotime($row_sched['wed_in1']));
        $wed_out1 = date('H:i', strtotime($row_sched['wed_out1']));
        $wed_in2 = date('H:i', strtotime($row_sched['wed_in2']));
        $wed_out2 = date('H:i', strtotime($row_sched['wed_out2']));
    }else{
        $wed_in1 = '';
        $wed_out1 = '';
        $wed_in2 = '';
        $wed_out2 = '';
    }

    if($thus == 'ON'){
        $thu_in1 = date('H:i', strtotime($row_sched['thu_in1']));
        $thu_out1 = date('H:i', strtotime($row_sched['thu_out1']));
        $thu_in2 = date('H:i', strtotime($row_sched['thu_in2']));
        $thu_out2 = date('H:i', strtotime($row_sched['thu_out2']));
    }else{
        $thu_in1 = '';
        $thu_out1 = '';
        $thu_in2 = '';
        $thu_out2 = '';
    }

    if($fris == 'ON'){
        $fri_in1 = date('H:i', strtotime($row_sched['fri_in1']));
        $fri_out1 = date('H:i', strtotime($row_sched['fri_out1']));
        $fri_in2 = date('H:i', strtotime($row_sched['fri_in2']));
        $fri_out2 = date('H:i', strtotime($row_sched['fri_out2']));
    }else{
        $fri_in1 = '';
        $fri_out1 = '';
        $fri_in2 = '';
        $fri_out2 = '';
    }

    if($sats == 'ON'){
        $sat_in1 = date('H:i', strtotime($row_sched['sat_in1']));
        $sat_out1 = date('H:i', strtotime($row_sched['sat_out1']));
        $sat_in2 = date('H:i', strtotime($row_sched['sat_in2']));
        $sat_out2 = date('H:i', strtotime($row_sched['sat_out2']));
    }else{
        $sat_in1 = '';
        $sat_out1 = '';
        $sat_in2 = '';
        $sat_out2 = '';
    }

    if($suns == 'ON'){
        $sun_in1 = date('H:i', strtotime($row_sched['sun_in1']));
        $sun_out1 = date('H:i', strtotime($row_sched['sun_out1']));
        $sun_in2 = date('H:i', strtotime($row_sched['sun_in2']));
        $sun_out2 = date('H:i', strtotime($row_sched['sun_out2']));
    }else{
        $sun_in1 = '';
        $sun_out1 = '';
        $sun_in2 = '';
        $sun_out2 = '';
    }

}else{
    $mons = '';
    $tues = '';
    $weds = '';
    $thus = '';
    $fris = '';
    $sats = '';
    $suns = '';
    $breaks = '';

    $mon_in1 = '';
    $mon_out1 = '';
    $mon_in2 = '';
    $mon_out2 = '';

    $tue_in1 = '';
    $tue_out1 = '';
    $tue_in2 = '';
    $tue_out2 = '';

    $wed_in1 = '';
    $wed_out1 = '';
    $wed_in2 = '';
    $wed_out2 = '';

    $thu_in1 = '';
    $thu_out1 = '';
    $thu_in2 = '';
    $thu_out2 = '';

    $fri_in1 = '';
    $fri_out1 = '';
    $fri_in2 = '';
    $fri_out2 = '';

    $sat_in1 = '';
    $sat_out1 = '';
    $sat_in2 = '';
    $sat_out2 = '';

    $sun_in1 = '';
    $sun_out1 = '';
    $sun_in2 = '';
    $sun_out2 = '';

}

?>

{
        "ID":"<?= $row['ID'] ?>",
        "emp_id":"<?= $row['emp_id']?>",
        "fullname":"<?= $fullname?>",
        "fullnames":"<?= $fullnames?>",
        "position":"<?= $row['position']?>",
        "dept":"<?= $row['dept']?>",
        "payroll_dept":"<?= $row['confi'] == 'Yes' ? 'CONFI MEMBER' : $row['payroll_dept']?>",
        "job_status":"<?= $row['job_status']?>",
        "bdate":"<?= $row['bdate']?>",
        "gender":"<?= $row['gender']?>",
        "civil_status":"<?= $row['civil_status']?>",
        "address":"<?= $row['address']?>",
        "spouse":"<?= $row['spouse']?>",
        "no_of_child":"<?= $row['no_of_child']?>",
        "child":"<?= $row['child']?>",
        "educ_background":"<?= $row['educ_background']?>",
        "beneficiary":"<?= $row['beneficiary']?>",
        "dept":"<?= $row['dept']?>",
        "contact_no":"<?= $row['contact_no']?>",
        "gsis":"<?= $row['gsis']?>",
        "pagibig":"<?= $row['pagibig']?>",
        "philhealth":"<?= $row['philhealth']?>",
        "sss":"<?= $row['sss']?>",
        "tin":"<?= $row['tin']?>",
        "date_hired":"<?= $row['date_hired']?>",
        "emergency_name":"<?= $row['emergency_name']?>",
        "emergency_address":"<?= $row['emergency_address']?>",
        "emergency_contact":"<?= $row['emergency_contact']?>",
        "rates":"<?= $rate?>",
        "allowance":"<?= $allowance?>",
        "mon_on":"<?= $mons?>",
        "mon_in1":"<?= $mon_in1?>",
        "mon_out1":"<?= $mon_out1?>",
        "mon_in2":"<?= $mon_in2?>",
        "mon_out2":"<?= $mon_out2?>",
        "tue_on":"<?= $tues?>",
        "tue_in1":"<?= $tue_in1?>",
        "tue_out1":"<?= $tue_out1?>",
        "tue_in2":"<?= $tue_in2?>",
        "tue_out2":"<?= $tue_out2?>",
        "wed_on":"<?= $weds?>",
        "wed_in1":"<?= $wed_in1?>",
        "wed_out1":"<?= $wed_out1?>",
        "wed_in2":"<?= $wed_in2?>",
        "wed_out2":"<?= $wed_out2?>",
        "thu_on":"<?= $thus?>",
        "thu_in1":"<?= $thu_in1?>",
        "thu_out1":"<?= $thu_out1?>",
        "thu_in2":"<?= $thu_in2?>",
        "thu_out2":"<?= $thu_out2?>",
        "fri_on":"<?= $fris?>",
        "fri_in1":"<?= $fri_in1?>",
        "fri_out1":"<?= $fri_out1?>",
        "fri_in2":"<?= $fri_in2?>",
        "fri_out2":"<?= $fri_out2?>",
        "sat_on":"<?= $sats?>",
        "sat_in1":"<?= $sat_in1?>",
        "sat_out1":"<?= $sat_out1?>",
        "sat_in2":"<?= $sat_in2?>",
        "sat_out2":"<?= $sat_out2?>",
        "sun_on":"<?= $suns?>",
        "sun_in1":"<?= $sun_in1?>",
        "sun_out1":"<?= $sun_out1?>",
        "sun_in2":"<?= $sun_in2?>",
        "sun_out2":"<?= $sun_out2?>",
        "breaks":"<?= $breaks?>"
}