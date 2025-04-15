<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");

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
    $sched_to = $_POST['sched_to'];

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

    do{
        $sched_from = date('l, F d, Y', strtotime($sched_from));
        $dayys = date('D', strtotime($sched_from));
        $dayys = strtolower($dayys);
        $sched_status = 'ON';

        if($dayys == 'mon'){
            if($mon_on == "ON"){
                $in1 = date('h:i A', strtotime($_POST['mon_in1']));
                $out1 = date('h:i A', strtotime($_POST['mon_out1']));
                $in2 = date('h:i A', strtotime($_POST['mon_in2']));
                $out2 = date('h:i A', strtotime($_POST['mon_out2']));
            }else{
                $in1 = '';
                $out1 = '';
                $in2 = '';
                $out2 = '';
                $sched_status = 'OFF';
            }             
        }elseif($dayys == 'tue'){
            if($tue_on == "ON"){
                $in1 = date('h:i A', strtotime($_POST['tue_in1']));
                $out1 = date('h:i A', strtotime($_POST['tue_out1']));
                $in2 = date('h:i A', strtotime($_POST['tue_in2']));
                $out2 = date('h:i A', strtotime($_POST['tue_out2']));
            }else{
                $in1 = '';
                $out1 = '';
                $in2 = '';
                $out2 = '';
                $sched_status = 'OFF';
            }                    
        }elseif($dayys == 'wed'){
            if($wed_on == "ON"){
                $in1 = date('h:i A', strtotime($_POST['wed_in1']));
                $out1 = date('h:i A', strtotime($_POST['wed_out1']));
                $in2 = date('h:i A', strtotime($_POST['wed_in2']));
                $out2 = date('h:i A', strtotime($_POST['wed_out2']));
            }else{
                $in1 = '';
                $out1 = '';
                $in2 = '';
                $out2 = '';
                $sched_status = 'OFF';
            }                    
        }elseif($dayys == 'thu'){
            if($thu_on == "ON"){
                $in1 = date('h:i A', strtotime($_POST['thu_in1']));
                $out1 = date('h:i A', strtotime($_POST['thu_out1']));
                $in2 = date('h:i A', strtotime($_POST['thu_in2']));
                $out2 = date('h:i A', strtotime($_POST['thu_out2']));
            }else{
                $in1 = '';
                $out1 = '';
                $in2 = '';
                $out2 = '';
                $sched_status = 'OFF';
            }                    
        }elseif($dayys == 'fri'){
            if($fri_on == "ON"){
                $in1 = date('h:i A', strtotime($_POST['fri_in1']));
                $out1 = date('h:i A', strtotime($_POST['fri_out1']));
                $in2 = date('h:i A', strtotime($_POST['fri_in2']));
                $out2 = date('h:i A', strtotime($_POST['fri_out2']));
            }else{
                $in1 = '';
                $out1 = '';
                $in2 = '';
                $out2 = '';
                $sched_status = 'OFF';
            }                    
        }elseif($dayys == 'sat'){
            if($sat_on == "ON"){
                $in1 = date('h:i A', strtotime($_POST['sat_in1']));
                $out1 = date('h:i A', strtotime($_POST['sat_out1']));
                $in2 = date('h:i A', strtotime($_POST['sat_in2']));
                $out2 = date('h:i A', strtotime($_POST['sat_out2']));
            }else{
                $in1 = '';
                $out1 = '';
                $in2 = '';
                $out2 = '';
                $sched_status = 'OFF';
            }                    
        }elseif($dayys == 'sun'){
            if($sun_on == "ON"){
                $in1 = date('h:i A', strtotime($_POST['sun_in1']));
                $out1 = date('h:i A', strtotime($_POST['sun_out1']));
                $in2 = date('h:i A', strtotime($_POST['sun_in2']));
                $out2 = date('h:i A', strtotime($_POST['sun_out2']));
            }else{
                $in1 = '';
                $out1 = '';
                $in2 = '';
                $out2 = '';
                $sched_status = 'OFF';
            }                    
        }

        $nn = 0;
        // payroll
        $sql_payrolls = "SELECT * FROM payrolls WHERE emp_id = '$emp_id' && posting = 'POSTED'";
        $dan_payrolls = $con->query($sql_payrolls) or die ($con->error);
        $row_payrolls = $dan_payrolls->fetch_assoc();
        $count_payrolls = mysqli_num_rows($dan_payrolls);

        if($count_payrolls > 0){
            do{
                $payroll_id = $row_payrolls['payroll_id'];
                
                if(!empty($payroll_id)){
                    // payroll history
                    $sql_payroll_history = "SELECT * FROM payroll_history WHERE `payroll_id` = '$payroll_id' && `status` = 'POSTED'";
                    $dan_payroll_history = $con->query($sql_payroll_history) or die ($con->error);
                    $row_payroll_history = $dan_payroll_history->fetch_assoc();
                    $count_payroll_history = mysqli_num_rows($dan_payroll_history);

                    if($count_payroll_history > 0){
                        $from_payroll = $row_payroll_history['froms'];
                        $to_payroll = $row_payroll_history['tos'];

                        if(strtotime($sched_from) >= strtotime($from_payroll) && strtotime($sched_from) <= strtotime($to_payroll)){
                            $nn++;
                        }
                    }
                }
            }while($row_payrolls = $dan_payrolls->fetch_assoc());
        }
        
        if($nn == 0){
            // check if already saved
            $check_id = "SELECT * FROM sched_custom WHERE dated = '$sched_from' && emp_id = '$emp_id'";
            $query = mysqli_query($con,$check_id);
            $row = mysqli_fetch_assoc($query);
            $count = mysqli_num_rows($query);

            $updated_by = $_SESSION['Usernames'].' - '.date('m/d/y h:ia');

            if($count > 0){
                $id = $row['ID'];
                $sql = "UPDATE `sched_custom` SET `in1` = '$in1', `out1` = '$out1', `in2` = '$in2', `out2` = '$out2', `created_by` = '$updated_by', `remarks` = '$sched_status' WHERE `ID` = '$id'";
            }else{
                $sql = "INSERT INTO `sched_custom` (`ID`, `emp_id`, `dated`, `in1`, `out1`, `in2`, `out2`, `created_by`, `remarks`) 
                        VALUES (NULL, '$emp_id', '$sched_from', '$in1', '$out1', '$in2', '$out2', '$updated_by', '$sched_status')";
            }

            if($con->query($sql) or die ($con->error))
            {
            }else{
                echo "Something went wrong";
            }
        }

        $sched_from = date('l, F d, Y', strtotime($sched_from . " +1 day"));
    }while(strtotime($sched_from) <= strtotime($sched_to));

        // schedules
        $sql_sched = "SELECT * FROM schedules WHERE emp_id = '$emp_id'";
        $dan_sched = $con->query($sql_sched) or die ($con->error);
        $row_sched = $dan_sched->fetch_assoc();
        $count_sched = mysqli_num_rows($dan_sched);
    
        if($count_sched > 0){
            $sql_s = "UPDATE schedules SET mon = '$mon_on', tue= '$tue_on', wed = '$wed_on', thu = '$thu_on', fri = '$fri_on', sat = '$sat_on', sun = '$sun_on',
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
            $sql_s = "INSERT INTO `schedules` (`ID`, `emp_id`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`, `sun`, 
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
    
        if($con->query($sql_s) or die ($con->error))
        {
        }else{
            echo "Something went wrong";
        }

    header("Location: employees.php");

}
?>