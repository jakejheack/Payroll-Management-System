<?php
session_start();

include_once("../connection/cons.php");
$con = conns();


if(isset($_SESSION['employees_dtr_from']) && isset($_SESSION['employees_dtr_to'])){
    $from = $_SESSION['employees_dtr_to'];
    $to = $_SESSION['employees_dtr_from'];
}else{
    $from = date('Y-m-d');
    $to = date('Y-m-d');
}

// employees info
$sql_emp = "SELECT * FROM employees";
$dan_emp = $con->query($sql_emp) or die ($con->error);
$row_emp = $dan_emp->fetch_assoc();
$count_emp = mysqli_num_rows($dan_emp);

$x = 0;
do{
    $emp_id = $row_emp['ID'];
    $emp_ids = $row_emp['ID'];

    $from = date('Y-m-d', strtotime("- 1 day"));
    $to = date('2023-07-28');
    
        // schedules
        $sql_sched = "SELECT * FROM schedules WHERE emp_id = '$emp_ids'";
        $dan_sched = $con->query($sql_sched) or die ($con->error);
        $row_sched = $dan_sched->fetch_assoc();
        $count_sched = mysqli_num_rows($dan_sched);

        $breaks = 30;

        if($count_sched > 0)
        {
            $mons = $row_sched['mon'];
            $tues = $row_sched['tue'];
            $weds = $row_sched['wed'];
            $thus = $row_sched['thu'];
            $fris = $row_sched['fri'];
            $sats = $row_sched['sat'];
            $suns = $row_sched['sun'];

            if(!empty($row_sched['min_break'])){
                $breaks = $row_sched['min_break'];
            }

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
                $mons = 'OFF';
                $tues = 'OFF';
                $weds = 'OFF';
                $thus = 'OFF';
                $fris = 'OFF';
                $sats = 'OFF';
                $suns = 'OFF';

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

        <table id="dtr_table" class="dataTable table table-striped table-bordered table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Date</th>
                                        <th rowspan="2">Day</th>
                                        <th rowspan="2">In-1</th>
                                        <th rowspan="2">Out-1</th>
                                        <th rowspan="2">In-2</th>
                                        <th rowspan="2">Out-2</th>
                                        <th rowspan="2">In-3</th>
                                        <th rowspan="2">Out-3</th>
                                        <th rowspan="2">In-4</th>
                                        <th rowspan="2">Out-4</th>
                                        <th rowspan="2">Work Hrs.</th>
                                        <th colspan="2" style="text-align: center;">Overtime</th>
                                        <th colspan="2" style="text-align: center;">BT-1</th>
                                        <th colspan="2" style="text-align: center;">BT-2</th>
                                        <th colspan="2" style="text-align: center;">BT-3</th>
                                        <th rowspan="2">Mins. Break</th>
                                        <th rowspan="2">Remarks</th>
                                    </tr>
                                    <tr>
                                        <th>Hrs</th>
                                        <th>Status</th>
                                        <th>IN</th>
                                        <th>OUT</th>
                                        <th>IN</th>
                                        <th>OUT</th>
                                        <th>IN</th>
                                        <th>OUT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php
                                            $total_hrs = 0;
                                            $total_ot = 0;
                                            $total_reg_late = 0;
                                            $bt_late = 0;
                                            do{

                                                ?>
                                                <tr>
                                                    <?php
                                                        $ddd = date('l, F d, Y', strtotime($to));
                                                        // dtr
                                                        $sql_dtr = "SELECT * FROM dtrs WHERE emp_id = '$emp_id' && log_date = '$ddd'";
                                                        $dan_dtr = $con->query($sql_dtr) or die ($con->error);
                                                        $row_dtr = $dan_dtr->fetch_assoc();
                                                        $count = mysqli_num_rows($dan_dtr);

                                                        // Breaktime
                                                        $sql_dtr_breaks = "SELECT * FROM dtrs_breaks WHERE emp_id = '$emp_id' && log_date = '$ddd'";
                                                        $dan_dtr_breaks = $con->query($sql_dtr_breaks) or die ($con->error);
                                                        $row_dtr_breaks = $dan_dtr_breaks->fetch_assoc();
                                                        $count_breaks = mysqli_num_rows($dan_dtr_breaks);

                                                        $dayy = date('l', strtotime($to));

                                                        $dayys = date('D', strtotime($to));
                                                        $dayys = strtolower($dayys);
            
                                                        // schedules    
                                                        $sql_sched = "SELECT * FROM schedules WHERE emp_id = '$emp_id'";
                                                        $dan_sched = $con->query($sql_sched) or die ($con->error);
                                                        $row_sched = $dan_sched->fetch_assoc();
                                                        $count_sched = mysqli_num_rows($dan_sched);

                                                        if($count_sched > 0)
                                                        {
                                                            $mon = $row_sched['mon'];
                                                            $tue = $row_sched['tue'];
                                                            $wed = $row_sched['wed'];
                                                            $thu = $row_sched['thu'];
                                                            $fri = $row_sched['fri'];
                                                            $sat = $row_sched['sat'];
                                                            $sun = $row_sched['sun'];
                                                        }else{
                                                            $mon = 'ON';
                                                            $tue = 'ON';
                                                            $wed = 'ON';
                                                            $thu = 'ON';
                                                            $fri = 'ON';
                                                            $sat = 'ON';
                                                            $sun = 'ON';
                                                        }

                                                        // holidays
                                                        $hol_date = date('2023-m-d', strtotime($to));

                                                        $sql_hol = "SELECT * FROM holidays WHERE datee = '$hol_date'";
                                                        $dan_hol = $con->query($sql_hol) or die ($con->error);
                                                        $row_hol = $dan_hol->fetch_assoc();
                                                        $count_hol = mysqli_num_rows($dan_hol);

                                                        // holiday with work
                                                        if($count_hol > 0){
                                                        $hol_types = $row_hol['types'];
                                                        }else{
                                                        $hol_types = "";
                                                        }

                                                        $remarks = "";

                                                            // day-off w/ Work
                                                            if($dayys == 'mon' && $mon == 'OFF' && empty($hol_types))
                                                            {
                                                                $remarks = "DAY-OFF";
                                                            }
                                                            elseif($dayys == 'tue' && $tue == 'OFF' && empty($hol_types))
                                                            {
                                                                $remarks = "DAY-OFF";
                                                            }
                                                            elseif($dayys == 'wed' && $wed == 'OFF' && empty($hol_types))
                                                            {
                                                                $remarks = "DAY-OFF";
                                                            }
                                                            elseif($dayys == 'thu' && $thu == 'OFF' && empty($hol_types))
                                                            {
                                                                $remarks = "DAY-OFF";
                                                            }
                                                            elseif($dayys == 'fri' && $fri == 'OFF' && empty($hol_types))
                                                            {
                                                                $remarks = "DAY-OFF";
                                                            }
                                                            elseif($dayys == 'sat' && $sat == 'OFF' && empty($hol_types))
                                                            {
                                                                $remarks = "DAY-OFF";
                                                            }
                                                            elseif($dayys == 'sun' && $sun == 'OFF' && empty($hol_types))
                                                            {
                                                                $remarks = "DAY-OFF";
                                                            }


                                                    if($count < 1)
                                                    {
                                                        ?>
                                                            <td>
                                                                <?php
                                                                    if(strtotime($to) >= strtotime('now')){
                                                                        echo '<a href="#" class="btn text-active btn-link btn-sm disabled" role="button" aria-disabled="true">'.date('m/d', strtotime($to)).'</a>';
                                                                    }else{
                                                                        echo '<a href=javascript:void();" data-id="'.$to.'" class="btn text-active btn-link btn-sm editbtn" >'.date('m/d', strtotime($to)).'</a>';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td><?= date('D', strtotime($to)) ?></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><?= $remarks." ".$hol_types?></td>

                                                        <?php                                                                                                        
                                                    }else{

                                                        // lates
                                                        $in_color1 = 'black';
                                                        $in_color2 = 'black';
                                                        $in_color3 = 'black';
                                                        $in_color4 = 'black';
                                                        $late1 = 0;
                                                        $late2 = 0;
                                                        $late3 = 0;
                                                        $late4 = 0;

                                                        $sched_in1 = $row_dtr['in1'];
                                                        $sched_out1 = $row_dtr['out1'];
                                                        $sched_in2 = $row_dtr['in2'];
                                                        $sched_out2 = $row_dtr['out2'];

                                                        $sched_work = 'OFF';

                                                        if($mons == 'ON' && $dayys == 'mon'){
                                                            $sched_in1 = $mon_in1;
                                                            $sched_in2 = $mon_in2;
                                                            $sched_out1 = $mon_out1;
                                                            $sched_out2 = $mon_out2;

                                                            $sched_work = 'ON';
                                                            
                                                        }

                                                        if($tues == 'ON' && $dayys == 'tue'){
                                                            $sched_in1 = $tue_in1;
                                                            $sched_in2 = $tue_in2;
                                                            $sched_out1 = $tue_out1;
                                                            $sched_out2 = $tue_out2;

                                                            $sched_work = 'ON';
                                                        }

                                                        if($weds == 'ON' && $dayys == 'wed'){
                                                            $sched_in1 = $wed_in1;
                                                            $sched_in2 = $wed_in2;
                                                            $sched_out1 = $wed_out1;
                                                            $sched_out2 = $wed_out2;

                                                            $sched_work = 'ON';
                                                        }

                                                        if($thus == 'ON' && $dayys == 'thu'){
                                                            $sched_in1 = $thu_in1;
                                                            $sched_in2 = $thu_in2;
                                                            $sched_out1 = $thu_out1;
                                                            $sched_out2 = $thu_out2;

                                                            $sched_work = 'ON';
                                                        }

                                                        if($fris == 'ON' && $dayys == 'fri'){
                                                            $sched_in1 = $fri_in1;
                                                            $sched_in2 = $fri_in2;
                                                            $sched_out1 = $fri_out1;
                                                            $sched_out2 = $fri_out2;

                                                            $sched_work = 'ON';
                                                        }

                                                        if($sats == 'ON' && $dayys == 'sat'){
                                                            $sched_in1 = $sat_in1;
                                                            $sched_in2 = $sat_in2;
                                                            $sched_out1 = $sat_out1;
                                                            $sched_out2 = $sat_out2;

                                                            $sched_work = 'ON';

                                                        }

                                                        if($suns == 'ON' && $dayys == 'sun'){
                                                            $sched_in1 = $sun_in1;
                                                            $sched_in2 = $sun_in2;
                                                            $sched_out1 = $sun_out1;
                                                            $sched_out2 = $sun_out2;

                                                            $sched_work = 'ON';

                                                        }

                                                        if($sched_work == 'OFF'){
                                                            $working_schedule_remarks = 'DAY-OFF';
                                                        }

                                                        // custom schedules
                                                        $sql_custom_sched = "SELECT * FROM sched_custom WHERE emp_id = '$emp_ids' && dated = '$ddd'";
                                                        $dan_custom_sched = $con->query($sql_custom_sched) or die ($con->error);
                                                        $row_custom_sched = $dan_custom_sched->fetch_assoc();
                                                        $count_custom_sched = mysqli_num_rows($dan_custom_sched);

                                                        if($count_custom_sched > 0){
                                                            $sched_in1 = $row_custom_sched['in1'];
                                                            $sched_out1 = $row_custom_sched['out1'];
                                                            $sched_in2 = $row_custom_sched['in2'];
                                                            $sched_out2 = $row_custom_sched['out2'];
                                                            $sched_work = "ON";

                                                            if(strtotime($row_dtr['in1']) > strtotime($sched_in1)){
                                                                $in_color1 = 'red';
                                                            }else{
                                                                $in_color1 = '';
                                                            }
                                                            if(strtotime($row_dtr['in2']) > strtotime($sched_in2)){
                                                                $in_color2 = 'red';
                                                            }else{
                                                                $in_color2 = '';
                                                            }

                                                            $custom_sched = 'YES';
                                                            $custom_sched_created = $row_custom_sched['created_by'];
                                                        }else{
                                                            $custom_sched = '';
                                                            $custom_sched_created = '';
                                                        }

                                                        $sched_in1 = date('h:i A', strtotime($sched_in1));
                                                        $sched_in2 = date('h:i A', strtotime($sched_in2));

                                                        $scheds_in1 = $sched_in1;
                                                        $scheds_in2 = $sched_in2;

                                                        // number of hours work
                                                        $hour1 = 0;
                                                        $hour2 = 0;
                                                        $hour3 = 0;
                                                        $hour4 = 0;
                                                        $ot_hour = 0;

                                                        $in1 = $row_dtr['in1'];
                                                        $out1 = $row_dtr['out1'];
                                                        $in2 = $row_dtr['in2'];
                                                        $out2 = $row_dtr['out2'];
                                                        $in3 = empty($row_dtr['in3']) && !empty($row_dtr['in4']) || $row_dtr['in3'] == '00:00' && !empty($row_dtr['in4']) ? $row_dtr['in4'] : $row_dtr['in3'];
                                                        $out3 = empty($row_dtr['out3']) && !empty($row_dtr['out4']) || $row_dtr['out3'] == '00:00' && !empty($row_dtr['out4']) ? $row_dtr['out4'] : $row_dtr['out3'];
                                                        $in4 = empty($row_dtr['in3']) && !empty($row_dtr['in4']) || $row_dtr['in3'] == '00:00' && !empty($row_dtr['in4']) ? '' : $row_dtr['in4'];
                                                        $out4 = empty($row_dtr['out3']) && !empty($row_dtr['out4']) || $row_dtr['out3'] == '00:00' && !empty($row_dtr['out4']) ? '' : $row_dtr['out4'];

                                                        $in1 == '00:00' ? $in1 = '' : $in1 = $in1;
                                                        $out1 == '00:00' ? $out1 = '' : $out1 = $out1;
                                                        $in2 == '00:00' ? $in2 = '' : $in2 = $in2;
                                                        $out2 == '00:00' ? $out2 = '' : $out2 = $out2;
                                                        $in3 == '00:00' ? $in3 = '' : $in3 = $in3;
                                                        $out3 == '00:00' ? $out3 = '' : $out3 = $out3;
                                                        $in4 == '00:00' ? $in4 = '' : $in4 = $in4;
                                                        $out4 == '00:00' ? $out4 = '' : $out4 = $out4;


                                                        $hrs_total = 0;
                                                        // with schedule
                                                        if($sched_work == 'ON'){
                                                                // in1 out1
                                                                if(!empty($out1) && !empty($in1)){
                                                                    // if(strtotime($in1) <= strtotime($sched_in1) && strtotime($in1) < strtotime($sched_out1) || strtotime($in1) >= strtotime($sched_in1) && strtotime($in1) < strtotime($sched_out1)){
                                                                        // early in
                                                                        $adv1 = strtotime($sched_in1);
                                                                        $adv1 = date('h:i A', strtotime("- 15 minutes", $adv1));

                                                                        // greater than 15 minutes early in schedule
                                                                        if(strtotime($in1) <= strtotime($adv1)){
                                                                            $sched_in1 = date('h:i A', strtotime($adv1));
                                                                        }else{
                                                                            $sched_in1 = date('h:i A', strtotime($in1));
                                                                        }

                                                                        // undertime in out1
                                                                        if(strtotime($out1) < strtotime($sched_out1)){
                                                                            $hour1 = (strtotime($out1) - strtotime($sched_in1)) / 3600;
                                                                        }elseif(strtotime($out1) >= strtotime($sched_out1) && strtotime($out1) <= strtotime($sched_in2)){ // on schedule
                                                                            $hour1 = (strtotime($sched_out1) - strtotime($sched_in1)) / 3600;
                                                                        }elseif(strtotime($out1) > strtotime($sched_in2) && strtotime($out1) < strtotime($sched_out2)){ // no lunch break but undertime in out2
                                                                            $hour1 = (strtotime($out1) - strtotime($sched_in1)) / 3600;
                                                                        }elseif(strtotime($out1) >= strtotime($sched_out2)){ // no lunch break and time-out greater than sched out2
                                                                            $con_ot = 0;
                                                                            $hour1 = (strtotime($out1) - strtotime($sched_in1)) / 3600;
                                                                            $con_ot = (strtotime($out1) - strtotime($sched_out2)) / 3600;
                                                                            // with ot
                                                                            if($con_ot >= 0.25){
                                                                                $ot_hour = round($con_ot,2);
                                                                                $hour1 = (strtotime($sched_out2) - strtotime($sched_in1)) / 3600;
                                                                            }
                                                                        }
                                                                        $hour1 = round($hour1,2);
                                                                    // }
                                                                }

                                                                // in2 out2
                                                                if(!empty($out2) && !empty($in2)){
                                                                    // if(strtotime($in2) <= strtotime($sched_in2) && strtotime($in2) < strtotime($sched_out2) || strtotime($in2) >= strtotime($sched_in2) && strtotime($in2) < strtotime($sched_out2)){
                                                                        // early in
                                                                        if(strtotime($in2) <= strtotime($sched_in2) && strtotime($in2) >= strtotime($sched_out1)){
                                                                            $sched_in2 = date('h:i A', strtotime($sched_in2));
                                                                        }else{
                                                                            $sched_in2 = date('h:i A', strtotime($in2));
                                                                        }

                                                                        // undertime in out1 and time-IN again in 2
                                                                        if(strtotime($out2) >= strtotime($sched_out1) && strtotime($in2) < strtotime($sched_out1) && strtotime($out2) <= strtotime($sched_in2)){
                                                                            $hour2 = (strtotime($sched_out1) - strtotime($in2)) / 3600;
                                                                        }elseif(strtotime($out2) < strtotime($sched_out1)){ // undertime at out1 but time-out in out2
                                                                            $hour2 = (strtotime($out2) - strtotime($in2)) / 3600;
                                                                        }elseif(strtotime($out2) > strtotime($sched_in2)){  // undertime at out1 and overtime or in schedule at out2
                                                                            $con_ot = 0;
                                                                            $hour2 = (strtotime($out2) - strtotime($sched_in2)) / 3600;
                                                                            $con_ot = (strtotime($out2) - strtotime($sched_out2)) / 3600;
                                                                            // with ot
                                                                            if($con_ot >= 0.25){
                                                                                $con_ot = round($con_ot,2);
                                                                                $ot_hour = $ot_hour + $con_ot;
                                                                                $hour2 = (strtotime($sched_out2) - strtotime($sched_in2)) / 3600;
                                                                            }elseif($con_ot < 0.25 && $con_ot > 0){
                                                                                $hour2 = (strtotime($sched_out2) - strtotime($sched_in2)) / 3600;
                                                                            }
                                                                        }
                                                                        $hour2 = round($hour2,2);
                                                                    // }
                                                                }


                                                                // in3 out3
                                                                if(!empty($out3) && !empty($in3)){
                                                                    $sched_in3 = '';
                                                                    $sched_out3 = '';
                                                                    // at 2nd OUT Sched
                                                                    if(strtotime($in3) < strtotime($sched_out2) && strtotime($in3) > strtotime($sched_out1)){
                                                                        // with ot at IN schedule
                                                                        if(strtotime($in3) <= strtotime($scheds_in2)){
                                                                            $con_ot = 0;
                                                                            $hour3 = (strtotime($out3) - strtotime($scheds_in2)) / 3600;
                                                                            $con_ot = (strtotime($out3) - strtotime($sched_out2)) / 3600;
                                                                            // with ot
                                                                            if($con_ot >= 0.25){
                                                                                $con_ot = round($con_ot,2);
                                                                                $ot_hour = $ot_hour + $con_ot;
                                                                                $hour3 = (strtotime($sched_out2) - strtotime($scheds_in2)) / 3600;
                                                                            }elseif($con_ot < 0.25 && $con_ot > 0){
                                                                                $hour3 = (strtotime($sched_out2) - strtotime($sched_in2)) / 3600;
                                                                            }
                                                                        }else{
                                                                            $con_ot = 0;
                                                                            $hour3 = (strtotime($out3) - strtotime($in3)) / 3600;
                                                                            $con_ot = (strtotime($out3) - strtotime($sched_out2)) / 3600;
                                                                            // with ot
                                                                            if($con_ot >= 0.25){
                                                                                $con_ot = round($con_ot,2);
                                                                                $ot_hour = $ot_hour + $con_ot;
                                                                                $hour3 = (strtotime($sched_out2) - strtotime($scheds_in2)) / 3600;
                                                                            }elseif($con_ot < 0.25 && $con_ot > 0){
                                                                                $hour3 = (strtotime($sched_out2) - strtotime($sched_in2)) / 3600;
                                                                            }
                                                                        }
                                                                    }else{
                                                                        // ot
                                                                        $hour3 = (strtotime($out3) - strtotime($in3)) / 3600;
                                                                        $ot_hour = $ot_hour + round($hour3,2);
                                                                        $hour3 = 0;
                                                                    }
                                                                }


                                                                // in4 out4
                                                                if(!empty($out4) && !empty($in4)){
                                                                    $sched_in4 = '';
                                                                    $sched_out4 = '';
                                                                    // at 2nd OUT Sched
                                                                    if(strtotime($in4) < strtotime($sched_out2) && strtotime($in4) > strtotime($sched_out1)){
                                                                        // with ot at IN schedule
                                                                        if(strtotime($in4) <= strtotime($scheds_in2)){
                                                                            $con_ot = 0;
                                                                            $hour4 = (strtotime($out4) - strtotime($scheds_in2)) / 3600;
                                                                            $con_ot = (strtotime($out4) - strtotime($sched_out2)) / 3600;
                                                                            // with ot
                                                                            if($con_ot >= 0.25){
                                                                                $con_ot = round($con_ot,2);
                                                                                $ot_hour = $ot_hour + $con_ot;
                                                                                $hour4 = (strtotime($sched_out2) - strtotime($scheds_in2)) / 3600;
                                                                            }elseif($con_ot < 0.25 && $con_ot > 0){
                                                                                $hour4 = (strtotime($sched_out2) - strtotime($sched_in2)) / 3600;
                                                                            }
                                                                        }else{
                                                                            $con_ot = 0;
                                                                            $hour4 = (strtotime($out4) - strtotime($in4)) / 3600;
                                                                            $con_ot = (strtotime($out4) - strtotime($sched_out2)) / 3600;
                                                                            // with ot
                                                                            if($con_ot >= 0.25){
                                                                                $con_ot = round($con_ot,2);
                                                                                $ot_hour = $ot_hour + $con_ot;
                                                                                $hour4 = (strtotime($sched_out2) - strtotime($scheds_in2)) / 3600;
                                                                            }elseif($con_ot < 0.25 && $con_ot > 0){
                                                                                $hour4 = (strtotime($sched_out2) - strtotime($sched_in2)) / 3600;
                                                                            }
                                                                        }
                                                                    }else{
                                                                        // ot
                                                                        $hour4 = (strtotime($out4) - strtotime($in4)) / 3600;
                                                                        $ot_hour = $ot_hour + round($hour4,2);
                                                                        $hour4 = 0;
                                                                    }
                                                                }

                                                                $hrs_total = $hour1 + $hour2 + $hour3 + $hour4;
                                                                if($hrs_total >= 8.25){
                                                                    $ot_hour_p = $hrs_total - 8.25; 
                                                                    $hrs_total = 8.25;
                                                                    $ot_hour = $ot_hour + $ot_hour_p;

                                                                    if($ot_hour >= 0.25){
                                                                        $ot_hour = $ot_hour;
                                                                    }else{
                                                                        $ot_hour = 0;
                                                                    }
                                                                }


                                                                // lates
                                                                // late 1
                                                                if(strtotime($in1) > strtotime($scheds_in1) && strtotime($in1) < strtotime($sched_out1)){
                                                                    $in_color1 = 'red';
                                                                    $late1 = (strtotime($in1) - strtotime($scheds_in1)) / 3600;
                                                                    $late1 = $late1 * 60;
                                                                    $late1 = round($late1);
                                                                }elseif(strtotime($in1) > strtotime($scheds_in2) && strtotime($in1) < strtotime($sched_out2)){
                                                                    $in_color1 = 'red';
                                                                    $late1 = (strtotime($in1) - strtotime($scheds_in2)) / 3600;
                                                                    $late1 = $late1 * 60;
                                                                    $late1 = round($late1);
                                                                }

                                                                // late 2
                                                                if(strtotime($in2) > strtotime($scheds_in2) && strtotime($in2) < strtotime($sched_out2)){
                                                                    $in_color2 = 'red';
                                                                    $late2 = (strtotime($in2) - strtotime($scheds_in2)) / 3600;
                                                                    $late2 = $late2 * 60;
                                                                    $late2 = round($late2);
                                                                }

                                                                // late 3
                                                                if(strtotime($in3) > strtotime($scheds_in2) && strtotime($in3) < strtotime($sched_out2)){
                                                                    $in_color3 = 'red';
                                                                    $late3 = (strtotime($in3) - strtotime($scheds_in2)) / 3600;
                                                                    $late3 = $late3 * 60;
                                                                    $late3 = round($late3);
                                                                }

                                                                // late 4
                                                                if(strtotime($in4) > strtotime($scheds_in2) && strtotime($in4) < strtotime($sched_out2)){
                                                                    $in_color4 = 'red';
                                                                    $late4 = (strtotime($in4) - strtotime($scheds_in2)) / 3600;
                                                                    $late4 = $late4 * 60;
                                                                    $late4 = round($late4);
                                                                }
                                                        }else{
                                                            if(!empty($in1) && !empty($out1)){
                                                                $hour1 = (strtotime($out1) - strtotime($in1)) / 3600;
                                                                $hour1 = round($hour1,2);
                                                            }
                                                            if(!empty($in2) && !empty($out2)){
                                                                $hour2 = (strtotime($out2) - strtotime($in2)) / 3600;
                                                                $hour2 = round($hour2,2);
                                                            }
                                                            if(!empty($in3) && !empty($out3)){
                                                                $hour3 = (strtotime($out3) - strtotime($in3)) / 3600;
                                                                $hour3 = round($hour3,2);
                                                            }
                                                            if(!empty($in4) && !empty($out4)){
                                                                $hour4 = (strtotime($out4) - strtotime($in4)) / 3600;
                                                                $hour4 = round($hour4,2);
                                                            }

                                                            $hrs_total = $hour1 + $hour2 + $hour3 + $hour4;

                                                            $hr_c = 0;
                                                            if($hrs_total > 8){
                                                                $hr_c = $hrs_total - 8;
                                                                $hrs_total = 8;
                                                                $ot_hour = $ot_hour + $hr_c;

                                                                if($ot_hour >= 0.25){
                                                                    $ot_hour = $ot_hour;
                                                                }else{
                                                                    $ot_hour = 0;
                                                                }
                                                            }
            
                                                        }


                                                        // <!-- breaktime -->
                                                        $bt_lates_d = 0;
                                                        if($count_breaks > 0){
                                                            $mins_break = 0;
                                                            $mins_break2 = 0;
                                                            $mins_break3 = 0;
                                                                if($row_dtr_breaks['in1'] != '00:00'){
                                                                    $b_in1 = $row_dtr_breaks['in1'];
                                                                }else{
                                                                    $b_in1 = '';
                                                                }
                                                                if($row_dtr_breaks['in2'] != '00:00'){
                                                                    $b_in2 = $row_dtr_breaks['in2'];
                                                                }else{
                                                                    $b_in2 = '';
                                                                }
                                                                if($row_dtr_breaks['in3'] != '00:00'){
                                                                    $b_in3 = $row_dtr_breaks['in3'];
                                                                }else{
                                                                    $b_in3 = '';
                                                                }
                                                                if($row_dtr_breaks['out1'] != '00:00'){
                                                                    $b_out1 = $row_dtr_breaks['out1'];
                                                                    $mins_break = abs(strtotime($b_out1) - strtotime($b_in1))/3600; 
                                                                    $mins_break = $mins_break * 60;
                                                                    $mins_break = round($mins_break);
                                                                }else{
                                                                    $b_out1 = '';
                                                                }
                                                                if($row_dtr_breaks['out2'] != '00:00'){
                                                                    $b_out2 = $row_dtr_breaks['out2'];
                                                                    $mins_break2 = abs(strtotime($b_out2) - strtotime($b_in2))/3600;
                                                                    $mins_break2 = $mins_break2 * 60;
                                                                    $mins_break2 = round($mins_break2);
                                                                }else{
                                                                    $b_out2 = '';
                                                                }
                                                                if($row_dtr_breaks['out3'] != '00:00'){
                                                                    $b_out3 = $row_dtr_breaks['out3'];
                                                                    $mins_break3 = abs(strtotime($b_out3) - strtotime($b_in3))/3600; 
                                                                    $mins_break3 = $mins_break3 * 60;
                                                                    $mins_break3 = round($mins_break3);
                                                                }else{
                                                                    $b_out3 = '';
                                                                }

                                                                // $bt_lates_d = floatval($row_dtr_breaks['lates']);

                                                                $mins_break = $mins_break + $mins_break2 + $mins_break3;

                                                                $bt_lates_d = $mins_break - $breaks;

                                                                if($bt_lates_d > 0){
                                                                    $bt_color = "red";
                                                                }else{
                                                                    $bt_color = "black";
                                                                    $bt_lates_d = 0;
                                                                }

                                                        }

                                                        // bt late deductions
                                                        $late_in_bt = $bt_lates_d / 60;
                                                        $hrs_total = $hrs_total - $late_in_bt;
                                                        $hrs_total = round($hrs_total,2);

                                                        // total hours
                                                        $total_hrs = $total_hrs + $hrs_total;
                                                        $total_ot = $total_ot + $ot_hour;
                                                        
                                                        if($row_dtr['ot_approved'] == 'Approved'){
                                                            $total_hrs = $total_hrs + $ot_hour;
                                                            $hrs_total = $hrs_total + $ot_hour;
                                                        }

                                                        $dtrID = $row_dtr['ID'];

                                                        $c_in1 = date('h:i A', strtotime($scheds_in1));
                                                        $c_in2 = date('h:i A', strtotime($scheds_in2));
                                                        $c_out1 = date('h:i A', strtotime($sched_out1));
                                                        $c_out2 = date('h:i A', strtotime($sched_out2));

                                                        $late_day = $late1 + $late2 + $late3 + $late4;

                                                        // $sql_update_hr =  "UPDATE `dtrs` SET `in3` = '$in3', `out3` = '$out3', `in4` = '$in4', `out4` = '$out4',
                                                        //                     `total_hrs` = '$hrs_total', `total_ot` = '$ot_hour', 
                                                        //                     `c_in1` = '$c_in1', `c_out1` = '$c_out1', `c_in2` = '$c_in2', `c_out2` = '$c_out2',
                                                        //                     `created_by` = '$custom_sched_created', `custom_sched` = '$custom_sched',
                                                        //                     `in_lates` = '$late_day', `late1` = '$late1', `late2` = '$late2', `late3` = '$late3', `late4` = '$late4'
                                                        //                      WHERE `dtrs`.`ID` = '$dtrID'";
                                                        // $update_hr = ($con->query($sql_update_hr) or die ($con->error));
                                                    ?>
                                                        <td>
                                                            <?php
                                                                if(strtotime($to) >= strtotime('now')){
                                                                    echo '<a href="#" class="btn text-active btn-link btn-sm disabled" role="button" aria-disabled="true">'.date('m/d', strtotime($to)).'</a>';
                                                                }else{
                                                                    echo '<a href=javascript:void(); data-id="'.$row_dtr['ID'].'" class="btn text-active btn-link btn-sm editbtn" >'.date('m/d', strtotime($to)).'</a>';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td><?= date('D', strtotime($to)) ?></td>
                                                        <td style="color: <?= $in_color1?>"><?= $in1?></td>
                                                        <td><?= $out1?></td>
                                                        <td style="color: <?= $in_color2?>"><?= $in2?></td>
                                                        <td><?= $out2?></td>
                                                        <td style="color: <?= $in_color3?>"><?= $in3 ?></td>
                                                        <td><?= $out3 ?></td>
                                                        <td style="color: <?= $in_color4?>"><?= $in4 ?></td>
                                                        <td><?= $out4 ?></td>
                                                        <td <?= $hrs_total <= 7 ? 'class="table-danger"' : '' ?>><b><?= $hrs_total?></b></td>
                                                        <td><b><?= $ot_hour > 0 ? $ot_hour : '' ?></b></td>
                                                        <td><?php
                                                                if(empty($row_dtr['ot_approved']) && $ot_hour <= 0 || $row_dtr['ot_approved'] == 'Cancelled' && $ot_hour <= 0){
                                                                echo "";
                                                                }elseif($row_dtr['ot_approved'] == 'Approved'){
                                                                    ?>
                                                                    <a href=javascript:void(); data-id="<?=$row_dtr['ID']?>" class="overtimebtn badge badge-success">&check; Approved</a>
                                                                    <?php
                                                                }elseif(empty($row_dtr['ot_approved']) && $ot_hour > 0 || $row_dtr['ot_approved'] == 'Pending'){
                                                                    ?>
                                                                    <a href=javascript:void(); data-id="<?=$row_dtr['ID']?>" class="overtimebtn badge badge-warning">Pending...</a>
                                                                    <?php
                                                                }elseif($row_dtr['ot_approved'] == 'Cancelled' && $ot_hour > 0){
                                                                    ?>
                                                                    <a href=javascript:void(); data-id="<?=$row_dtr['ID']?>" class="overtimebtn badge badge-danger">&times; Cancelled</a>
                                                                    <?php
                                                                }
                                                            ?></td>
                                                            <!-- breaktime -->
                                                            <?php
                                                            if($count_breaks > 0){
                                                            ?>
                                                                <td style="color: <?= $bt_color?>"><?= $b_in1?></td>
                                                                <td style="color: <?= $bt_color?>"><?= $b_out1?></td>
                                                                <td style="color: <?= $bt_color?>"><?= $b_in2?></td>
                                                                <td style="color: <?= $bt_color?>"><?= $b_out2?></td>
                                                                <td style="color: <?= $bt_color?>"><?= $b_in3?></td>
                                                                <td style="color: <?= $bt_color?>"><?= $b_out3?></td>
                                                                <td style="color: <?= $bt_color?>"><?php
                                                                    if($bt_lates_d > 0){
                                                                        echo '<b style="color: red">'.$mins_break.'</b>';
                                                                        $mins_late = $mins_break - $breaks;
                                                                        $bt_late = $bt_late + $bt_lates_d;
                                                                    }else{
                                                                        echo $mins_break;
                                                                    }
                                                                ?></td>
                                                            <?php
                                                        }else{
                                                            ?>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            <?php
                                                        }
                                                        ?>
                                                        <td><?= $remarks." ".$hol_types?></td>
                                                    <?php
                                                    }
                                                ?>
                                                </tr>
                                                <?php
                                                $to = date('m/d/Y', strtotime($to . " +1 day"));
                                            }while(strtotime($to) <= strtotime($from));
                                        ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="10" style="text-align: right;">Total Working Hours</th>
                                        <th style="text-align: center;"><?= round($total_hrs,2)?></th>
                                        <th style="text-align: center;"></th>
                                        <th colspan=""></th>
                                        <th colspan="6" style="text-align: right;">Total Minutes Late in BT</th>
                                        <th style="text-align: center; color:red;"><?= $bt_late?></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
        </table>

<?php
    $x++;
}while($row_emp = $dan_emp->fetch_assoc());

echo '<br>'.$x;
