<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_POST['update_time'])){
    $emp_id = $_POST['emp_id'];
    $mon_on = $_POST['mon_on'];
    $tue_on = $_POST['tue_on'];
    $wed_on = $_POST['wed_on'];
    $thu_on = $_POST['thu_on'];
    $fri_on = $_POST['fri_on'];
    $sat_on = $_POST['sat_on'];
    $sun_on = $_POST['sun_on'];
    $min_break = $_POST['min_break'];
    $sched_from = $_POST['sched_from'];
    $shed_to = $_POST['sched_to'];
    $page = $_POST['pages'];

    if($mon_on == "ON"){
        $mon_in1 = date('h:i A', strtotime($_POST['mon_in1']));
        $mon_out1 = date('h:i A', strtotime($_POST['mon_out1']));
        $mon_in2 = date('h:i A', strtotime($_POST['mon_in2']));
        $mon_out2 = date('h:i A', strtotime($_POST['mon_out2']));
    }else{
        $mon_in1 = '';
        $mon_out1 = '';
        $mon_in2 = '';
        $mon_out2 = '';
    }

    if($tue_on == "ON"){
        $tue_in1 = date('h:i A', strtotime($_POST['tue_in1']));
        $tue_out1 = date('h:i A', strtotime($_POST['tue_out1']));
        $tue_in2 = date('h:i A', strtotime($_POST['tue_in2']));
        $tue_out2 = date('h:i A', strtotime($_POST['tue_out2']));
    }else{
        $tue_in1 = '';
        $tue_out1 = '';
        $tue_in2 = '';
        $tue_out2 = '';
    }

    if($wed_on == "ON"){
        $wed_in1 = date('h:i A', strtotime($_POST['wed_in1']));
        $wed_out1 = date('h:i A', strtotime($_POST['wed_out1']));
        $wed_in2 = date('h:i A', strtotime($_POST['wed_in2']));
        $wed_out2 = date('h:i A', strtotime($_POST['wed_out2']));
    }else{
        $wed_in1 = '';
        $wed_out1 = '';
        $wed_in2 = '';
        $wed_out2 = '';
    }

    if($thu_on == "ON"){
        $thu_in1 = date('h:i A', strtotime($_POST['thu_in1']));
        $thu_out1 = date('h:i A', strtotime($_POST['thu_out1']));
        $thu_in2 = date('h:i A', strtotime($_POST['thu_in2']));
        $thu_out2 = date('h:i A', strtotime($_POST['thu_out2']));
    }else{
        $thu_in1 = '';
        $thu_out1 = '';
        $thu_in2 = '';
        $thu_out2 = '';
    }

    if($fri_on == "ON"){
        $fri_in1 = date('h:i A', strtotime($_POST['fri_in1']));
        $fri_out1 = date('h:i A', strtotime($_POST['fri_out1']));
        $fri_in2 = date('h:i A', strtotime($_POST['fri_in2']));
        $fri_out2 = date('h:i A', strtotime($_POST['fri_out2']));
    }else{
        $fri_in1 = '';
        $fri_out1 = '';
        $fri_in2 = '';
        $fri_out2 = '';
    }

    if($sat_on == "ON"){
        $sat_in1 = date('h:i A', strtotime($_POST['sat_in1']));
        $sat_out1 = date('h:i A', strtotime($_POST['sat_out1']));
        $sat_in2 = date('h:i A', strtotime($_POST['sat_in2']));
        $sat_out2 = date('h:i A', strtotime($_POST['sat_out2']));
    }else{
        $sat_in1 = '';
        $sat_out1 = '';
        $sat_in2 = '';
        $sat_out2 = '';
    }

    if($sun_on == "ON"){
        $sun_in1 = date('h:i A', strtotime($_POST['sun_in1']));
        $sun_out1 = date('h:i A', strtotime($_POST['sun_out1']));
        $sun_in2 = date('h:i A', strtotime($_POST['sun_in2']));
        $sun_out2 = date('h:i A', strtotime($_POST['sun_out2']));
    }else{
        $sun_in1 = '';
        $sun_out1 = '';
        $sun_in2 = '';
        $sun_out2 = '';
    }

    // schedules
    $sql_sched = "SELECT * FROM schedules WHERE emp_id = '$emp_id'";
    $dan_sched = $con->query($sql_sched) or die ($con->error);
    $row_sched = $dan_sched->fetch_assoc();
    $count_sched = mysqli_num_rows($dan_sched);

    if($count_sched > 0){
        $sql = "UPDATE schedules SET mon = '$mon_on', tue= '$tue_on', wed = '$wed_on', thu = '$thu_on', fri = '$fri_on', sat = '$sat_on', sun = '$sun_on',
        mon_in1 = '$mon_in1', mon_out1 = '$mon_out1', mon_in2 = '$mon_in2', mon_out2 = '$mon_out2',
        tue_in1 = '$tue_in1', tue_out1 = '$tue_out1', tue_in2 = '$tue_in2', tue_out2 = '$tue_out2',
        wed_in1 = '$wed_in1', wed_out1 = '$wed_out1', wed_in2 = '$wed_in2', wed_out2 = '$wed_out2',
        thu_in1 = '$thu_in1', thu_out1 = '$thu_out1', thu_in2 = '$thu_in2', thu_out2 = '$thu_out2',
        fri_in1 = '$fri_in1', fri_out1 = '$fri_out1', fri_in2 = '$fri_in2', fri_out2 = '$fri_out2',
        sat_in1 = '$sat_in1', sat_out1 = '$sat_out1', sat_in2 = '$sat_in2', sat_out2 = '$sat_out2',
        sun_in1 = '$sun_in1', sun_out1 = '$sun_out1', sun_in2 = '$sun_in2', sun_out2 = '$sun_out2',
        min_break = '$min_break'
         WHERE emp_id = '$emp_id'";
    }else{
        $sql = "INSERT INTO `schedules` (`ID`, `emp_id`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`, `sun`, 
        `mon_in1`, `mon_out1`, `mon_in2`, `mon_out2`, `tue_in1`, `tue_out1`, `tue_in2`, `tue_out2`, 
        `wed_in1`, `wed_out1`, `wed_in2`, `wed_out2`, `thu_in1`, `thu_out1`, `thu_in2`, `thu_out2`, 
        `fri_in1`, `fri_out1`, `fri_in2`, `fri_out2`, `sat_in1`, `sat_out1`, `sat_in2`, `sat_out2`, 
        `sun_in1`, `sun_out1`, `sun_in2`, `sun_out2`, `min_break`)
        VALUES (NULL, '$emp_id', '$mon_on',  '$tue_on', '$wed_on', '$thu_on', '$fri_on', '$sat_on', '$sun_on', 
        '$mon_in1', '$mon_out1', '$mon_in2', '$mon_out2', 
        '$tue_in1', '$tue_out1', '$tue_in2', '$tue_out2', 
        '$wed_in1', '$wed_out1', '$wed_in2', '$wed_out2', 
        '$thu_in1', '$thu_out1', '$thu_in2', '$thu_out2', 
        '$fri_in1', '$fri_out1', '$fri_in2', '$fri_out2', 
        '$sat_in1', '$sat_out1', '$sat_in2', '$sat_out2', 
        '$sun_in1', '$sun_out1', '$sun_in2', '$sun_out2',
        '$min_break')";
    }

    if($con->query($sql) or die ($con->error))
    {
    }else{
        echo "Something went wrong";
    }

    if($page == 'daily_time_record_employees'){
        echo header("Location: daily_time_record_employees.php?ID=".$emp_id."");
    }else{
        $_SESSION['status_sched'] = "Employee's Schedule updated successfully.";
        echo header("Location: employees_dtr.php");
    }
}
?>