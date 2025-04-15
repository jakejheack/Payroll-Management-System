<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");


$emp_ids = $_SESSION['emp_id'];

if(isset($_SESSION['employees_dtr_from']) && isset($_SESSION['employees_dtr_to'])){
    $from = $_SESSION['employees_dtr_to'];
    $to = $_SESSION['employees_dtr_from'];
}else{
    $from = date('Y-m-d');
    $to = date('Y-m-d');
}

// employees info
$sql_emp = "SELECT * FROM employees WHERE ID = '$emp_ids'";
$dan_emp = $con->query($sql_emp) or die ($con->error);
$row_emp = $dan_emp->fetch_assoc();
$count_emp = mysqli_num_rows($dan_emp);

if($count_emp > 0){
    $emp_name = $row_emp['lname'].', '.$row_emp['fname'].' '.$row_emp['mname'].' '.$row_emp['extname'];
    $position = $row_emp['position'];
}else{
    $emp_name = '-';
    $position = '-';    
}

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

// $img = '<img src="' . $path . '">';
// if(empty($pix))
// {
//     $emp_pic = '<img src="img/blank.jpg" alt="" width="33px" height="33px">';
// }elseif(!empty($pix) && $pix != '---')
// {
//     $emp_pic = '<img src="data:image/jpg;charset=utf8;base64,'.base64_encode($pix).'" width="33px" height="33px"/>';
// }

$html = '';
$html .= '<body style="font-family: Calibri; font-size: 10pt;">
        <div style="text-align: center">
            <img src="img/COH.jpg" style="width: 120px"/>
            <br>
            <b style="color: #096799; font-size: 12pt">DAILY TIME RECORD</b>
            <br>
            <b>'.date('M d, Y', strtotime($to)).' to '.date('M d, Y', strtotime($from)).'</b>
        </div>
        <br>
        <table>
            <tbody>
                <tr>
                    <td width="50px">Name:</td>
                    <td width="330px"><b> '.$emp_name.'</b></td>
                    <td>Employee ID #:</td>
                    <td><b> '.$row_emp['emp_id'].'</b></td>
                </tr>
                <tr>
                    <td>Position:</td>
                    <td><b>'.$position.'</b></td>
                    <td>Department:</td>
                    <td><b> '.$row_emp['dept'].'</b></td>
                </tr>
            </tbody>
        </table>';
$html .= '<table border="1" cellspacing="0" cellspadding="0" width="100%" autosize="2.4" style="font-size: 9pt">
<thead>
<tr>
    <th rowspan="2">Date</th>
    <th rowspan="2">Day</th>
    <th colspan="2" style="text-align: center;">AM</th>
    <th colspan="2" style="text-align: center;">PM</th>
    <th colspan="2" style="text-align: center;">O.T.</th>
    <th rowspan="2" width="10px">Work Hrs.</th>
    <th colspan="2" style="text-align: center;">BT-1</th>
    <th colspan="2" style="text-align: center;">BT-2</th>
    <th colspan="2" style="text-align: center;">BT-3</th>
    <th rowspan="2" width="10px";>Mins. Break</th>
    <th rowspan="2">Remarks</th>
</tr>
<tr>
    <th width="60px";>IN</th>
    <th width="60px";>OUT</th>
    <th width="60px";>IN</th>
    <th width="60px";>OUT</th>
    <th width="60px";>IN</th>
    <th width="60px";>OUT</th>
    <th width="60px";>IN</th>
    <th width="60px";>OUT</th>
    <th width="60px";>IN</th>
    <th width="60px";>OUT</th>
    <th width="60px";>IN</th>
    <th width="60px";>OUT</th>
</tr>
</thead>
<tbody>';

        $total_hrs = 0;
        $total_ot = 0;
        $total_reg_late = 0;
        $bt_late = 0;
        do{
            $emp_id = $_SESSION['emp_id'];
$html .=       '<tr>
                <td style="text-align: center;">'.date('m/d', strtotime($to)).'</td>
                <td style="text-align: center;">'.date('D', strtotime($to)).'</td>';
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
                            $remarks = "Day-Off";
                        }
                        elseif($dayys == 'tue' && $tue == 'OFF' && empty($hol_types))
                        {
                            $remarks = "Day-Off";
                        }
                        elseif($dayys == 'wed' && $wed == 'OFF' && empty($hol_types))
                        {
                            $remarks = "Day-Off";
                        }
                        elseif($dayys == 'thu' && $thu == 'OFF' && empty($hol_types))
                        {
                            $remarks = "Day-Off";
                        }
                        elseif($dayys == 'fri' && $fri == 'OFF' && empty($hol_types))
                        {
                            $remarks = "Day-Off";
                        }
                        elseif($dayys == 'sat' && $sat == 'OFF' && empty($hol_types))
                        {
                            $remarks = "Day-Off";
                        }
                        elseif($dayys == 'sun' && $sun == 'OFF' && empty($hol_types))
                        {
                            $remarks = "Day-Off";
                        }


                if($count < 1)
                {
$html .= '              <td></td>
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
                        <td style="text-align: center;">'.$remarks.'</td>';

                }else{

                    // lates
                    $in_color1 = 'black';
                    $in_color2 = 'black';
                    $late_reg = 0;
                    $lated = 0;
                    $lated2 = 0;

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

                        if(strtotime($row_dtr['in1']) > strtotime($mon_in1) && strtotime($row_dtr['in1']) < strtotime($mon_out1)){
                            $in_color1 = 'red';
                            $lated = abs(strtotime($row_dtr['in1']) - strtotime($mon_in1))/3600;
                            $lated = $lated * 60;
                            $late_reg = $late_reg + $lated;
                        }elseif(strtotime($row_dtr['in1']) > strtotime($mon_in1) && strtotime($row_dtr['in1']) > strtotime($mon_out1) && strtotime($row_dtr['in1']) > strtotime($mon_in2)){
                            $in_color2 = 'red';
                            $lated2 = abs(strtotime($row_dtr['in1']) - strtotime($mon_in2))/3600;
                            $lated2 = $lated2 * 60;
                            $late_reg = $late_reg + $lated2;
                        }

                        if(strtotime($row_dtr['in2']) > strtotime($mon_in2) && strtotime($row_dtr['in2']) < strtotime($mon_out2)){
                            $in_color2 = 'red';
                            $lated2 = abs(strtotime($row_dtr['in2']) - strtotime($mon_in2))/3600;
                            $lated2 = $lated2 * 60;
                            $late_reg = $late_reg + $lated2;
                        }
                    }

                    if($tues == 'ON' && $dayys == 'tue'){
                        $sched_in1 = $tue_in1;
                        $sched_in2 = $tue_in2;
                        $sched_out1 = $tue_out1;
                        $sched_out2 = $tue_out2;

                        $sched_work = 'ON';

                        if(strtotime($row_dtr['in1']) > strtotime($tue_in1) && strtotime($row_dtr['in1']) < strtotime($tue_out1)){
                            $in_color1 = 'red';
                            $lated = abs(strtotime($row_dtr['in1']) - strtotime($tue_in1))/3600;
                            $lated = $lated * 60;
                            $late_reg = $late_reg + $lated;
                        }elseif(strtotime($row_dtr['in1']) > strtotime($tue_in1) && strtotime($row_dtr['in1']) > strtotime($tue_out1) && strtotime($row_dtr['in1']) > strtotime($tue_in2)){
                            $in_color2 = 'red';
                            $lated2 = abs(strtotime($row_dtr['in1']) - strtotime($tue_in2))/3600;
                            $lated2 = $lated2 * 60;
                            $late_reg = $late_reg + $lated2;
                        }

                        if(strtotime($row_dtr['in2']) > strtotime($tue_in2) && strtotime($row_dtr['in2']) < strtotime($tue_out2)){
                            $in_color2 = 'red';
                            $lated2 = abs(strtotime($row_dtr['in2']) - strtotime($tue_in2))/3600;
                            $lated2 = $lated2 * 60;
                            $late_reg = $late_reg + $lated2;
                        }
                    }

                    if($weds == 'ON' && $dayys == 'wed'){
                        $sched_in1 = $wed_in1;
                        $sched_in2 = $wed_in2;
                        $sched_out1 = $wed_out1;
                        $sched_out2 = $wed_out2;

                        $sched_work = 'ON';

                        if(strtotime($row_dtr['in1']) > strtotime($wed_in1) && strtotime($row_dtr['in1']) < strtotime($wed_out1)){
                            $in_color1 = 'red';
                            $lated = abs(strtotime($row_dtr['in1']) - strtotime($wed_in1))/3600;
                            $lated = $lated * 60;
                            $late_reg = $late_reg + $lated;
                        }elseif(strtotime($row_dtr['in1']) > strtotime($wed_in1) && strtotime($row_dtr['in1']) > strtotime($wed_out1) && strtotime($row_dtr['in1']) > strtotime($wed_in2)){
                            $in_color2 = 'red';
                            $lated2 = abs(strtotime($row_dtr['in1']) - strtotime($wed_in2))/3600;
                            $lated2 = $lated2 * 60;
                            $late_reg = $late_reg + $lated2;
                        }

                        if(strtotime($row_dtr['in2']) > strtotime($wed_in2) && strtotime($row_dtr['in2']) < strtotime($wed_out2)){
                            $in_color2 = 'red';
                            $lated2 = abs(strtotime($row_dtr['in2']) - strtotime($wed_in2))/3600;
                            $lated2 = $lated2 * 60;
                            $late_reg = $late_reg + $lated2;
                        }
                    }

                    if($thus == 'ON' && $dayys == 'thu'){
                        $sched_in1 = $thu_in1;
                        $sched_in2 = $thu_in2;
                        $sched_out1 = $thu_out1;
                        $sched_out2 = $thu_out2;

                        $sched_work = 'ON';

                        if(strtotime($row_dtr['in1']) > strtotime($thu_in1) && strtotime($row_dtr['in1']) <= strtotime($thu_out1)){
                            $in_color1 = 'red';
                            $lated = abs(strtotime($row_dtr['in1']) - strtotime($thu_in1))/3600;
                            $lated = $lated * 60;
                            $late_reg = $late_reg + $lated;
                        }elseif(strtotime($row_dtr['in1']) > strtotime($thu_in1) && strtotime($row_dtr['in1']) > strtotime($thu_out1) && strtotime($row_dtr['in1']) > strtotime($thu_in2)){
                            $in_color2 = 'red';
                            $lated2 = abs(strtotime($row_dtr['in1']) - strtotime($thu_in2))/3600;
                            $lated2 = $lated2 * 60;
                            $late_reg = $late_reg + $lated2;
                        }

                        if(strtotime($row_dtr['in2']) > strtotime($thu_in2) && strtotime($row_dtr['in2']) < strtotime($thu_out2)){
                            $in_color2 = 'red';
                            $lated2 = abs(strtotime($row_dtr['in2']) - strtotime($thu_in2))/3600;
                            $lated2 = $lated2 * 60;
                            $late_reg = $late_reg + $lated2;
                        }
                    }

                    if($fris == 'ON' && $dayys == 'fri'){
                        $sched_in1 = $fri_in1;
                        $sched_in2 = $fri_in2;
                        $sched_out1 = $fri_out1;
                        $sched_out2 = $fri_out2;

                        $sched_work = 'ON';

                        if(strtotime($row_dtr['in1']) > strtotime($fri_in1) && strtotime($row_dtr['in1']) <= strtotime($fri_out1)){
                            $in_color1 = 'red';
                            $lated = abs(strtotime($row_dtr['in1']) - strtotime($fri_in1))/3600;
                            $lated = $lated * 60;
                            $late_reg = $late_reg + $lated;
                        }elseif(strtotime($row_dtr['in1']) > strtotime($fri_in1) && strtotime($row_dtr['in1']) > strtotime($fri_out1) && strtotime($row_dtr['in1']) > strtotime($fri_in2)){
                            $in_color2 = 'red';
                            $lated2 = abs(strtotime($row_dtr['in1']) - strtotime($fri_in2))/3600;
                            $lated2 = $lated2 * 60;
                            $late_reg = $late_reg + $lated2;
                        }

                        if(strtotime($row_dtr['in2']) > strtotime($fri_in2) && strtotime($row_dtr['in2']) < strtotime($fri_out2)){
                            $in_color2 = 'red';
                            $lated2 = abs(strtotime($row_dtr['in2']) - strtotime($fri_in2))/3600;
                            $lated2 = $lated2 * 60;
                            $late_reg = $late_reg + $lated2;
                        }
                    }

                    if($sats == 'ON' && $dayys == 'sat'){
                        $sched_in1 = $sat_in1;
                        $sched_in2 = $sat_in2;
                        $sched_out1 = $sat_out1;
                        $sched_out2 = $sat_out2;

                        $sched_work = 'ON';

                        if(strtotime($row_dtr['in1']) > strtotime($sat_in1) && strtotime($row_dtr['in1']) <= strtotime($sat_out1)){
                            $in_color1 = 'red';
                            $lated = abs(strtotime($row_dtr['in1']) - strtotime($sat_in1))/3600;
                            $lated = $lated * 60;
                            $late_reg = $late_reg + $lated;
                        }elseif(strtotime($row_dtr['in1']) > strtotime($sat_in1) && strtotime($row_dtr['in1']) > strtotime($sat_out1) && strtotime($row_dtr['in1']) > strtotime($sat_in2)){
                            $in_color2 = 'red';
                            $lated2 = abs(strtotime($row_dtr['in1']) - strtotime($sat_in2))/3600;
                            $lated2 = $lated2 * 60;
                            $late_reg = $late_reg + $lated2;
                        }

                        if(strtotime($row_dtr['in2']) > strtotime($sat_in2) && strtotime($row_dtr['in2']) < strtotime($sat_out2)){
                            $in_color2 = 'red';
                            $lated2 = abs(strtotime($row_dtr['in2']) - strtotime($sat_in2))/3600;
                            $lated2 = $lated2 * 60;
                            $late_reg = $late_reg + $lated2;
                        }
                    }

                    if($suns == 'ON' && $dayys == 'sun'){
                        $sched_in1 = $sun_in1;
                        $sched_in2 = $sun_in2;
                        $sched_out1 = $sun_out1;
                        $sched_out2 = $sun_out2;

                        $sched_work = 'ON';

                        if(strtotime($row_dtr['in1']) > strtotime($sun_in1) && strtotime($row_dtr['in1']) <= strtotime($sun_out1)){
                            $in_color1 = 'red';
                            $lated = abs(strtotime($row_dtr['in1']) - strtotime($sun_in1))/3600;
                            $lated = $lated * 60;
                            $late_reg = $late_reg + $lated;
                        }elseif(strtotime($row_dtr['in1']) > strtotime($sun_in1) && strtotime($row_dtr['in1']) > strtotime($sun_out1) && strtotime($row_dtr['in1']) > strtotime($sun_in2)){
                            $in_color2 = 'red';
                            $lated2 = abs(strtotime($row_dtr['in1']) - strtotime($sun_in2))/3600;
                            $lated2 = $lated2 * 60;
                            $late_reg = $late_reg + $lated2;
                        }

                        if(strtotime($row_dtr['in2']) > strtotime($sun_in2) && strtotime($row_dtr['in2']) < strtotime($sun_out2)){
                            $in_color2 = 'red';
                            $lated2 = abs(strtotime($row_dtr['in2']) - strtotime($sun_in2))/3600;
                            $lated2 = $lated2 * 60;
                            $late_reg = $late_reg + $lated2;
                        }
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

                        $late_reg = $late_reg - ($lated + $lated2);
                        $lated = 0;
                        $lated2 = 0;

                        if(strtotime($row_dtr['in1']) > strtotime($sched_in1)){
                            $in_color1 = 'red';
                            $lated = abs(strtotime($row_dtr['in1']) - strtotime($sched_in1))/3600;
                            $lated = $lated * 60;
                            $late_reg = $late_reg + $lated;
                        }else{
                            $in_color1 = '';
                        }
                        if(strtotime($row_dtr['in2']) > strtotime($sched_in2)){
                            $in_color2 = 'red';
                            $lated2 = abs(strtotime($row_dtr['in1']) - strtotime($sched_in2))/3600;
                            $lated2 = $lated2 * 60;
                            $late_reg = $late_reg + $lated2;
                        }else{
                            $in_color2 = '';
                        }
                    }
                    
                    $sched_in1 = date('h:i A', strtotime($sched_in1));
                    $sched_in2 = date('h:i A', strtotime($sched_in2));

                    // early in
                    $adv1 = strtotime($sched_in1);
                    $adv1 = date('h:i A', strtotime("- 15 minutes", $adv1));

                    // greater than 15 minutes early in schedule
                    if(strtotime($row_dtr['in1']) <= strtotime($adv1)){
                        // $sched_in1 = strtotime($sched_in1) - (15 * 60);
                        $sched_in1 = date('h:i A', strtotime($adv1));
                    }else{
                        $sched_in1 = date('h:i A', strtotime($row_dtr['in1']));
                    }

                    if(strtotime($row_dtr['in2']) < strtotime($sched_in2)){
                        $sched_in2 = date('h:i A', strtotime($sched_in2));
                    }else{
                        $sched_in2 = date('h:i A', strtotime($row_dtr['in2']));
                    }

                    // number of hours work
                    $am_hr = 0;
                    $pm_hr = 0;
                    $ot_hr = 0;

                    if($row_dtr['in1'] != '00:00' && $row_dtr['out1'] != '00:00' && !empty($row_dtr['out1']) || !empty($row_dtr['in1']) && !empty($row_dtr['out1'])  && $row_dtr['out1'] != '00:00'){
                        $am_hr = abs(strtotime($sched_out1) - strtotime($sched_in1)) / 3600;
                        $am_hr = round($am_hr,2);
                    }

                    if($row_dtr['in2'] != '00:00' && $row_dtr['out2'] != '00:00' && !empty($row_dtr['out2']) || !empty($row_dtr['in2']) && !empty($row_dtr['out2'])  && $row_dtr['out2'] != '00:00'){
                        $pm_hr = abs(strtotime($sched_out2) - strtotime($sched_in2)) / 3600;
                        $pm_hr = round($pm_hr,2);
                    }

                    if($row_dtr['in4'] != '00:00' && $row_dtr['out4'] != '00:00' && !empty($row_dtr['out4']) || !empty($row_dtr['in4']) && !empty($row_dtr['out4'])){
                        if(strtotime($row_dtr['in4']) >= strtotime($sched_out2)){
                            $ot_hr = abs(strtotime($row_dtr['out4']) - strtotime($row_dtr['in4'])) / 3600;
                            $ot_hr = round($ot_hr,2);
                        }elseif(strtotime($row_dtr['in4']) < strtotime($sched_out2) && ((strtotime($row_dtr['out4']) - strtotime($sched_out2))/3600) < 0.5){
                            $ot_hr = abs(strtotime($sched_out2) - strtotime($row_dtr['in4'])) / 3600;
                            $ot_hr = round($ot_hr,2);
                            // $pm_hr = ($ot_hr + $pm_hr) - $ot_hr;
                            $ot_hr = 0;
                        }elseif(strtotime($row_dtr['in4']) < strtotime($sched_out2) && ((strtotime($row_dtr['out4']) - strtotime($sched_out2))/3600) >= 0.5){
                            $ot_hr = abs(strtotime($row_dtr['out4']) - strtotime($sched_out2))/3600;
                            $ot_hr = round($ot_hr,2);
                            // $pm_hr = ($ot_hr + $pm_hr) - $ot_hr;
                            // $ot_hr = 0;
                        }

                    }

                    
                    // no lunch break
                    if(strtotime($row_dtr['out1']) >= strtotime($sched_in2) && strtotime($row_dtr['out1']) < strtotime($sched_out2) && $row_dtr['out2'] != '00:00')
                    {
                        $am_hr = abs(strtotime($row_dtr['out1']) - strtotime($sched_in1)) / 3600;
                        $am_hr = round($am_hr,2) - 1;
                    }elseif(strtotime($row_dtr['out1']) >= strtotime($sched_in2) && strtotime($row_dtr['out1']) >= strtotime($sched_out2) && $row_dtr['out2'] != '00:00')
                    {
                        $am_hr = abs(strtotime($sched_out2) - strtotime($sched_in1)) / 3600;
                        $am_hr = round($am_hr,2) - 1;
                    }


                    // overtime in out2/out1
                    $ot_out1 = (strtotime($row_dtr['out1']) - strtotime($sched_out2)) / 3600;
                    $ot_out1 = round($ot_out1,2);
                    $ot_out2 = (strtotime($row_dtr['out2']) - strtotime($sched_out2)) / 3600;
                    $ot_out2 = round($ot_out2,2);

                    if($ot_out1 >= 0.5 && $sched_work == 'ON'){
                        $ot_hr = $ot_hr + $ot_out1;
                    }
                    if($ot_out2 >= 0.5 && $sched_work == 'ON'){
                        $ot_hr = $ot_hr + $ot_out2;
                    }


                    $hrs1 = $am_hr + $pm_hr;
                    $ot = $ot_hr;

                    // time in o.t. but not ot and in day-off
                    if(($hrs1 + $ot) <= 8.25 && $sched_work == 'OFF'){
                        $hrs1 = $hrs1 + $ot;
                        $ot = 0;
                    }elseif(($hrs1 + $ot) > 8.25 && $sched_work == 'OFF'){
                        $hrs1 = $hrs1 + $ot;
                        $ot = $hrs1 - 8.25;
                        if($ot >= 0.5){
                            $hrs1 = 8.25;
                        }else{
                            $hrs1 = 8.25;
                            $ot = 0;
                        }
                    }

                    $total_reg_late = $total_reg_late + $lated + $lated2;

                    $total_hrs = $total_hrs + $hrs1;
                    $total_ot = $total_ot + $ot;    

                    if($row_dtr['ot_approved'] == 'Approved'){
                        $total_hrs = $total_hrs + $ot;
                        $hrs1 = $hrs1 + $ot;
                    }

$html .=           '<td style="color: '. $in_color1.'; text-align: center">'.($row_dtr['in1'] == '00:00' ? '' : $row_dtr['in1']).'</td>
                    <td style="text-align: center;">'.($row_dtr['out1'] == '00:00' ? '' : $row_dtr['out1']).'</td>
                    <td style="color: '. $in_color2.'; text-align: center;">'.($row_dtr['in2'] == '00:00' ? '' : $row_dtr['in2']).'</td>
                    <td style="text-align: center;">'.($row_dtr['out2'] == '00:00' ? '' : $row_dtr['out2']).'</td>
                    <td style="text-align: center;">'.($row_dtr['in4'] == '00:00' ? '' : $row_dtr['in4']).'</td>
                    <td style="text-align: center;">'.($row_dtr['out4'] == '00:00' ? '' : $row_dtr['out4']).'</td>
                    <td style="text-align: center;'.($hrs1 <= 7 ? 'background-color: #EBC1C1' : '').'"><b>'.$hrs1.'</b></td>';
                    // <td>'. $ot.'</td>';
//                     <td>';
// //                         if ($hrs1 < 8){ 
// // $html .=                 'class="table-danger"';
// //                         } 
//                             if(empty($row_dtr['ot_approved']) && $ot <= 0 || $row_dtr['ot_approved'] == 'Cancelled' && $ot <= 0){
//                                echo "";
//                             }elseif($row_dtr['ot_approved'] == 'Approved'){
// $html .=                       '<span class="badge badge-success">A</span>';
//                             }elseif(empty($row_dtr['ot_approved']) && $ot > 0 || $row_dtr['ot_approved'] == 'Pending'){
// $html .=                        '<span class="badge badge-warning">P</span>';
//                             }elseif($row_dtr['ot_approved'] == 'Cancelled' && $ot > 0){
// $html .=                        '<span class="badge badge-danger">C</span>';
//                             }
// $html .=                         '</td>';
                    // <!-- breaktime -->
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
                            }
$html .=                    '<td style="color: '.$bt_color.'; text-align: center;'.
                            (empty($b_out1) && !empty($b_in1) ? 'background-color: #F3D3D3' : '').'" >'.$b_in1.'</td>
                            <td style="color: '.$bt_color.'; text-align: center;'.
                            (empty($b_out1) && !empty($b_in1) ? 'background-color: #F3D3D3' : '').'" >'.$b_out1.'</td>
                            <td style="color: '.$bt_color.'; text-align: center">'.$b_in2.'</td>
                            <td style="color: '.$bt_color.'; text-align: center">'.$b_out2.'</td>
                            <td style="color: '.$bt_color.'; text-align: center">'.$b_in3.'</td>
                            <td style="color: '.$bt_color.'; text-align: center">'.$b_out3.'</td>
                            <td style="color: '.$bt_color.'; text-align: center">';
                                if($bt_lates_d > 0){
$html .=                            '<b style="color: red">'.$mins_break.'</b></td>';
                                    $mins_late = $mins_break - $breaks;
                                    $bt_late = $bt_late + $bt_lates_d;
                                }else{
$html .=                            $mins_break.'</td>';
                                }
                    }else{
$html .=                   '<td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>';
                    }
$html .=                    '<td style="text-align: center">'.$remarks.'</td>';
                }
$html .=       '</tr>';
            $to = date('m/d/Y', strtotime($to . " +1 day"));
        }while(strtotime($to) <= strtotime($from));
$html .='</tbody>
    </table>
    <p></p>
    <table cellspacing="0" cellspadding="0" width="100%" autosize="2.4">
            <thead>
                <tr>
                    <th width="180px"></th>
                    <th width="200px"></th>
                    <th width="100px"></th>
                    <th width="200px"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Worked Hours:</td>
                    <td><b>'.round($total_hrs,2).'</b></td>
                    <td>Total Minute(s) Late:</td>
                    <td><b>'.$total_reg_late + $bt_late.'</b></td>
                </tr>
                <tr>
                    <td>Total Worked Days:</td>
                    <td><b>'.round(($total_hrs/8),2).'</b></td>
                    <td>Minute(s) Late in Regular Sched:</td>
                    <td><b>'.$total_reg_late.'</b></td>
                </tr>
                <tr>
                    <td>Total No. of Undertime:</td>
                    <td><b>0</b></td>
                    <td>Minute(s) Late in Breaktime:</td>
                    <td><b>'.$bt_late.'</b></td>
                </tr>
            </tbody>
        </table>
        </body>';

   require_once __DIR__ . '/vendor/autoload.php';

   $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8','format' => 'Letter-P' ]);
   $mpdf->AddPageByArray([
    'margin-left' => 3,
    'margin-right' => 3,
    'margin-top' => 10,
    // 'margin-bottom' => 5,
    ]);

   $mpdf->SetDisplayMode('fullpage');
//    $mpdf->SetWatermarkImage('img/logo.jpg');
//    $mpdf->showWatermarkImage = true;
    // $mpdf->SetDefaultBodyCSS('background', "url('img/logo.jpg')");
//    $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
    // $mpdf->SetHTMLHeader('<div><img src="img/COH.jpg"/></div>');
    $mpdf->setFooter('Date & Time Processed: {DATE M j, Y h:i:s A} * Page {PAGENO} of {nbpg}');
    $mpdf->WriteHTML( $html );
//    $mpdf->Output( 'Payroll('.date('mdY', strtotime($_SESSION['dtr_From'])).'-'.date('mdY', strtotime($_SESSION['dtr_To'])).').pdf', 'D' ); // Direct download your project directory
   $mpdf->Output();
   exit;
