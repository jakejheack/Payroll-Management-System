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

if($count_sched > 0){
    $breaks = $row_sched['min_break'];
}else{
    $breaks = 30;
}

if(empty($breaks)){
    $breaks = 30;
}

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
$html .= '<table border="1" cellspacing="0" cellspadding="0" width="100%" autosize="2.5" style="font-size: 9pt">
<thead>
<tr>
    <th rowspan="2">Date</th>
    <th rowspan="2">Day</th>
    <th rowspan="2" style="text-align: center;" width="60px";>IN-1</th>
    <th rowspan="2" style="text-align: center;" width="60px";>OUT-1</th>
    <th rowspan="2" style="text-align: center;" width="60px";>IN-2</th>
    <th rowspan="2" style="text-align: center;" width="60px";>OUT-2</th>
    <th rowspan="2" style="text-align: center;" width="60px";>IN-3</th>
    <th rowspan="2" style="text-align: center;" width="60px";>OUT-3</th>
    <th rowspan="2" style="text-align: center;" width="60px";>IN-4</th>
    <th rowspan="2" style="text-align: center;" width="60px";>OUT-4</th>
    <th rowspan="2" width="10px">Work Hrs.</th>
    <th colspan="2" style="text-align: center;">BT-1</th>
    <th colspan="2" style="text-align: center;">BT-2</th>
    <th rowspan="2" width="10px";>Mins. Break</th>
    <th rowspan="2">Remarks</th>
</tr>
<tr>
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
        $total_ut = 0;
        $bt_late = 0;
        do{
            $emp_id = $_SESSION['emp_id'];

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

                    if($hol_types == 'SPECIAL NON-WORKING HOLIDAY'){
                        $hol_types = 'Spe. N-W Hol.';
                    }elseif($hol_types == 'REGULAR HOLIDAY'){
                        $hol_types = 'Reg. Hol.';
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
                    if($remarks != "Day-Off" && empty($hol_types))
                        $remarks = 'Absent';
$html .=       '<tr style="'.($remarks == 'Absent' ? 'background-color:#EBC1C1' : '').'">
                        <td style="text-align: center;">'.date('m/d', strtotime($to)).'</td>
                        <td style="text-align: center;">'.date('D', strtotime($to)).'</td>
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
                        <td style="text-align: center;'.($remarks == 'Absent' ? 'color:red' : '').'">'.$remarks.'</td>
                </tr>';

                }else{

                    // lates
                    $in_color1 = 'black';
                    $in_color2 = 'black';
                    $in_color3 = 'black';
                    $in_color4 = 'black';
                    $ut_color1 = '';
                    $ut_color2 = '';
                    $ut_color3 = '';
                    $ut_color4 = '';
                    $late1 = 0;
                    $late2 = 0;
                    $late3 = 0;
                    $late4 = 0;
                    $late_reg = 0;
                    $ut1 = 0;
                    $ut2 = 0;
                    $ut3 = 0;
                    $ut4 = 0;
                    $ut_day = 0;
                    $lated = 0;
                    $lated2 = 0;
                    $hrs_total = 0;
                    $day_ot = 0;

                    $in1 = $row_dtr['in1'];
                    $out1 = $row_dtr['out1'];
                    $in2 = $row_dtr['in2'];
                    $out2 = $row_dtr['out2'];
                    $in3 = $row_dtr['in3'];
                    $out3 = $row_dtr['out3'];
                    $in4 = $row_dtr['in4'];
                    $out4 = $row_dtr['out4'];

                    // hours in a day
                    if(empty($row_dtr['total_hrs'])){
                        $hrs_total = 0;
                    }else{
                        $hrs_total = $row_dtr['total_hrs'];
                    }

                    if(empty($row_dtr['total_ot'])){
                        $day_ot= 0;
                    }else{
                        $day_ot= $row_dtr['total_ot'];
                    }

                    $in1 == '00:00' ? $in1 = '' : $in1 = $in1;
                    $out1 == '00:00' ? $out1 = '' : $out1 = $out1;
                    $in2 == '00:00' ? $in2 = '' : $in2 = $in2;
                    $out2 == '00:00' ? $out2 = '' : $out2 = $out2;
                    $in3 == '00:00' ? $in3 = '' : $in3 = $in3;
                    $out3 == '00:00' ? $out3 = '' : $out3 = $out3;
                    $in4 == '00:00' ? $in4 = '' : $in4 = $in4;
                    $out4 == '00:00' ? $out4 = '' : $out4 = $out4;

                    if(!empty($row_dtr['late1'])){
                        $late1 = $row_dtr['late1'];
                    }else{
                        $late1 = 0;
                    }

                    if(!empty($row_dtr['late2'])){
                        $late2 = $row_dtr['late2'];
                    }else{
                        $late2 = 0;
                    }

                    if(!empty($row_dtr['late3'])){
                        $late3 = $row_dtr['late3'];
                    }else{
                        $late3 = 0;
                    }

                    if(!empty($row_dtr['late4'])){
                        $late4 = $row_dtr['late4'];
                    }else{
                        $late4 = 0;
                    }

                    if($late1 > 0){
                        $in_color1 = 'red';
                    }

                    if($late2 > 0){
                        $in_color2 = 'red';
                    }

                    if($late3 > 0){
                        $in_color3 = 'red';
                    }

                    if($late4 > 0){
                        $in_color4 = 'red';
                    }


                    // undertime
                    if(!empty($row_dtr['ut1'])){
                        $ut1 = $row_dtr['ut1'];
                    }else{
                        $ut1 = 0;
                    }

                    if(!empty($row_dtr['ut2'])){
                        $ut2 = $row_dtr['ut2'];
                    }else{
                        $ut2 = 0;
                    }

                    if(!empty($row_dtr['ut3'])){
                        $ut3 = $row_dtr['ut3'];
                    }else{
                        $ut3 = 0;
                    }

                    if(!empty($row_dtr['ut4'])){
                        $ut4 = $row_dtr['ut4'];
                    }else{
                        $ut4 = 0;
                    }

                    if($ut1 > 0){
                        $ut_color1 = '#EBC1C1';
                    }

                    if($ut2 > 0){
                        $ut_color2 = '#EBC1C1';
                    }

                    if($ut3 > 0){
                        $ut_color3 = '#EBC1C1';
                    }

                    if($ut4 > 0){
                        $ut_color4 = '#EBC1C1';
                    }

                    $lated = $late1 + $late2 + $late3 + $late4;
                    $total_reg_late = $total_reg_late + $lated;

                    $ut_day = $ut1 + $ut2 + $ut3 + $ut4;
                    $total_ut = $total_ut + $ut_day;

                    $total_hrs = $total_hrs + $hrs_total;
                    $total_ot = $total_ot + $day_ot;    

                    // if($row_dtr['ot_approved'] == 'Approved'){
                    //     $total_hrs = $total_hrs + $day_ot;
                    //     $hrs_total = $hrs_total + $day_ot;
                    // }

$html .=        '<tr>
                    <td style="text-align: center;">'.date('m/d', strtotime($to)).'</td>
                    <td style="text-align: center;">'.date('D', strtotime($to)).'</td>
                    <td style="color: '. $in_color1.'; text-align: center">'.$in1.'</td>
                    <td style="text-align: center; background-color: '.$ut_color1.'">'.$out1.'</td>
                    <td style="color: '. $in_color2.'; text-align: center;">'.$in2.'</td>
                    <td style="text-align: center; background-color: '.$ut_color2.'">'.$out2.'</td>
                    <td style="color: '. $in_color3.'; text-align: center;">'.$in3.'</td>
                    <td style="text-align: center; background-color: '.$ut_color3.'">'.$out3.'</td>
                    <td style="color: '. $in_color4.'; text-align: center;">'.$in4.'</td>
                    <td style="text-align: center; background-color: '.$ut_color4.'">'.$out4.'</td>
                    <td style="text-align: center;'.($hrs_total <= 7 ? 'background-color: #EBC1C1' : '').'"><b>'.$hrs_total.'</b></td>';

                    // <!-- breaktime -->
                    if($count_breaks > 0){
                        $mins_break = 0;
                        $mins_break2 = 0;
                        // $mins_break3 = 0;
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

                            $mins_break = $mins_break + $mins_break2;

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
                            <td></td>';
                    }
$html .=                    '<td style="text-align: center">'.$hol_types.' '.$remarks.'</td>
                        </tr>';
                }
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
                    <td>Total No. of Undertime:</td>';
                    if($total_ut == 0){
                        $ut_remarks = '-';
                    }elseif(intdiv($total_ut, 60) > 0 && ($total_ut % 60) > 0){
                        $ut_remarks = intdiv($total_ut, 60).' hr(s) & '. ($total_ut % 60).' min(s)'; 
                    }elseif(intdiv($total_ut, 60) > 0 && ($total_ut % 60) == 0){
                        $ut_remarks = intdiv($total_ut, 60).' hr(s)'; 
                    }elseif(intdiv($total_ut, 60) == 0 && ($total_ut % 60) > 0){
                        $ut_remarks = ($total_ut % 60).' min(s)';
                    }
$html .=            '<td><b>'.$ut_remarks.'</b></td>
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
