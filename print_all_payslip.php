<?php
if(!isset($_SESSION)){
    session_start();
}

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");

if(isset($_GET['Payroll']))
{
    $payroll = $_GET['Payroll'];

    $y_now = date("Y");
    $m_now = date("m");
    $d_now = date("j");

    $now = date("m/d/Y");

    $m_last = date('m', strtotime($now . "- 1 month"));

    if($payroll == 'Start')
    {
        if($m_now == date('m', strtotime('01/01/2022')))
        {
            $y_last = date('Y', strtotime($now . "- 1 year"));
        }else{
            $y_last = $y_now;
        }

        $from = $m_last.'/28'.'/'.$y_last;
        $to = $m_now.'/12'.'/'.$y_now;
    }elseif($payroll == 'End')
    {
        $from = $m_now.'/13'.'/'.$y_now;
        $to = $m_now.'/27'.'/'.$y_now;
    }


    $from = date('m/d/Y', strtotime($from));
    $to = date('m/d/Y', strtotime($to));

    $_SESSION['dtr_From'] = $from;
    $_SESSION['dtr_To'] = $to;

}else{
    $payroll = 'Start';
}

$_SESSION['Payroll'] = $payroll;


if(isset($_POST['details']))
{
    $from = $_POST['from'];
    $to = $_POST['to'];

    $from = date('m/d/Y', strtotime($from));
    $to = date('m/d/Y', strtotime($to));

    $_SESSION['dtr_From'] = $from;
    $_SESSION['dtr_To'] = $to;

}


if(isset($_SESSION['departmentsss']))
{
    $dept = $_SESSION['departmentsss'];
    $dept = str_replace("'", "\'", $dept);
}

// employees info
if(empty($dept)){
    $sql_emp = "SELECT * FROM employees order by lname,fname,mname ASC";
}else{
    $sql_emp = "SELECT * FROM employees WHERE dept = '$dept' order by lname,fname,mname ASC";
}
$dan_emp = $con->query($sql_emp) or die ($con->error);
$row_emp = $dan_emp->fetch_assoc();

if(isset($_SESSION['dtr_From']) && isset($_SESSION['dtr_To']))
{
    $from = $_SESSION['dtr_From'];
    $to = $_SESSION['dtr_To'];
}


$html = '';
$html .= '<body style="font-family: Calibri; font-size: 10pt;">';

    if(isset($_SESSION['dtr_From']) && isset($_SESSION['dtr_To']))
    {
        $to = $_SESSION['dtr_To'];
        $no = 1;

        $total_gross = 0;
        $total_deductions = 0;
        $total_netpay = 0;

        // Load Names of Employees
        do{
            $from = $_SESSION['dtr_From'];
            $rate = 0;

            $emp_name = $row_emp['lname'].', '.$row_emp['fname'].' '.substr($row_emp['mname'],0,1).' '.$row_emp['extname'];
            $emp_name = ucwords($emp_name);
            $position = $row_emp['position'];
            $empID = $row_emp['ID'];
            $rate = $row_emp['rates'];
            $ot_rate = $row_emp['ot'];
            $reg_holiday_rate = $rate * 2;
            $sp = $rate * 0.3;
            $special_holiday_rate = $rate + $sp;
            $day_off_rate = $special_holiday_rate;

            // payroll
                $total_hrs = 0;
                $total_ot = 0;
                $hrs1 = 0;
                $hrs2 = 0;
                $off_hrs = 0;
                $off_hrs2 = 0;
                $basic_pay = 0;
                $ot_pay = 0;
                $off_pay = 0;
                $ver = "";
                $gross = 0;
                $reg_hol_hrs = 0;
                $spe_hol_hrs = 0;
                $reg_hol_hrs2 = 0;
                $spe_hol_hrs2 = 0;
                do{

                    $ddd = date('l, F d, Y', strtotime($from));

                    // dtr
                        $sql_dtr = "SELECT * FROM dtrs WHERE emp_id = '$empID' && log_date = '$ddd'";
                        $dan_dtr = $con->query($sql_dtr) or die ($con->error);
                        $row_dtr = $dan_dtr->fetch_assoc();
                        $count = mysqli_num_rows($dan_dtr);

                        $dayy = date('D', strtotime($from));
                        $dayy = strtolower($dayy);

                        // schedules
                        $sql_sched = "SELECT * FROM schedules WHERE emp_id = '$empID'";
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
                        $hol_date = date('2023-m-d', strtotime($ddd));

                        $sql_hol = "SELECT * FROM holidays WHERE datee = '$hol_date'";
                        $dan_hol = $con->query($sql_hol) or die ($con->error);
                        $row_hol = $dan_hol->fetch_assoc();
                        $count_hol = mysqli_num_rows($dan_hol);

                                // holiday with work
                                if($count_hol > 0){
                                    $hol_types = $row_hol['types'];
                                }else{
                                    $hol_types = "-";
                                }

                                
                        if($count > 0)
                        {
                            $hrs1 = (double)$row_dtr['total_hrs'];
                            $ot = (double)$row_dtr['total_ot'];
                
                            // $d1 = strtotime($row_dtr['in1']);
                            // $d2 = strtotime($row_dtr['out1']);
                            // $d3 = strtotime($row_dtr['in2']);
                            // $d4 = strtotime($row_dtr['out2']);
                            // $d5 = strtotime($row_dtr['in3']);
                            // $d6 = strtotime($row_dtr['out3']);
                            // $d7 = strtotime($row_dtr['in4']);
                            // $d8 = strtotime($row_dtr['out4']);
                
                            // $am = abs($d2 - $d1)/3600;
                            // $pm = abs($d4 - $d3)/3600;
                            // $hrs1 = $am + $pm;
                
                            // $ot1 = abs($d6 - $d5)/3600;
                            // $ot2 = abs($d8 - $d7)/3600;
                            // $hrs2 = $ot1 + $ot2;
                
                                // holiday with work
                                if($hol_types == 'Regular Holiday')
                                {
                                    $reg_hol_hrs = $reg_hol_hrs + $hrs1;
                                    $reg_hol_hrs2 = $reg_hol_hrs2 + $ot;
                                }elseif($hol_types == 'Special Non-Working Holiday')
                                {
                                    $spe_hol_hrs = $spe_hol_hrs + $hrs1;
                                    $spe_hol_hrs2 = $spe_hol_hrs2 + $ot;
                                }


                                // day-off w/ Work
                                if($dayy == 'mon' && $mon == 'OFF' && $hol_types == "-")
                                {
                                    $off_hrs = $off_hrs + $hrs1;
                                    $off_hrs2 = $off_hrs2 + $ot;
                                }
                                elseif($dayy == 'tue' && $tue == 'OFF' && $hol_types == "-")
                                {
                                    $off_hrs = $off_hrs + $hrs1;
                                    $off_hrs2 = $off_hrs2 + $ot;
                                }
                                elseif($dayy == 'wed' && $wed == 'OFF' && $hol_types == "-")
                                {
                                    $off_hrs = $off_hrs + $hrs1;
                                    $off_hrs2 = $off_hrs2 + $ot;
                                }
                                elseif($dayy == 'thu' && $thu == 'OFF' && $hol_types == "-")
                                {
                                    $off_hrs = $off_hrs + $hrs1;
                                    $off_hrs2 = $off_hrs2 + $ot;
                                }
                                elseif($dayy == 'fri' && $fri == 'OFF' && $hol_types == "-")
                                {
                                    $off_hrs = $off_hrs + $hrs1;
                                    $off_hrs2 = $off_hrs2 + $ot;
                                }
                                elseif($dayy == 'sat' && $sat == 'OFF' && $hol_types == "-")
                                {
                                    $off_hrs = $off_hrs + $hrs1;
                                    $off_hrs2 = $off_hrs2 + $ot;
                                }
                                elseif($dayy == 'sun' && $sun == 'OFF' && $hol_types == "-")
                                {
                                    $off_hrs = $off_hrs + $hrs1;
                                    $off_hrs2 = $off_hrs2 + $ot;
                                }
                        
                            $total_hrs = $total_hrs + $hrs1;
                            $total_ot = $total_ot + $ot;
                        }

                            // to verify hrs
                                if($hrs1 > 9 || $hrs1 < 7 || $ot > 0)
                                {
                                    $ver = "To Check";
                                }

                    $from = date('m/d/Y', strtotime($from . " +1 day"));
                }while(strtotime($from) <= strtotime($to));

                    // deduct hrs from day-off, regular and special holidays
                    $minus_hrs = $off_hrs + $reg_hol_hrs + $spe_hol_hrs;
                    $minus_hrs2 = $off_hrs2 + $reg_hol_hrs2 + $spe_hol_hrs2;

                    // regular working hours
                    $total_hrs = $total_hrs - $minus_hrs;
                    $total_ot = $total_ot - $minus_hrs2;
                    $basic_pay = round($total_hrs,2) * $rate;
                    $ot_pay = round($total_ot,2) * $ot_rate;

                    // holidays and day-off with work
                    $reg_hol_pay = round($reg_hol_hrs,2) * $reg_holiday_rate;
                    $spe_hol_pay = round($spe_hol_hrs,2) * $special_holiday_rate;
                    $off_pay = round($off_hrs,2) * $day_off_rate;
                    // off w/ ot
                    $off_ot_rate = $day_off_rate * 0.25;
                    $off_ot_rate = $off_ot_rate + $day_off_rate;
                    $off_ot_pay = round($off_hrs2,2) * $off_ot_rate;
                    // regular holiday w/ ot
                    $reg_hol_ot_rate = $reg_holiday_rate * 0.25;
                    $reg_hol_ot_rate = $reg_holiday_rate + $reg_hol_ot_rate;
                    $reg_hol_ot_pay = round($reg_hol_hrs2,2) * $reg_hol_ot_rate;
                    // special holiday w/ ot
                    $spe_hol_ot_rate = $special_holiday_rate * 0.25;
                    $spe_hol_ot_rate = $special_holiday_rate + $spe_hol_ot_rate;
                    $spe_hol_ot_pay = round($spe_hol_hrs2,2) * $spe_hol_ot_rate;

                    // total gross
                    $holidays_dayoff_pay = $reg_hol_pay + $spe_hol_pay + $off_pay + $reg_hol_ot_pay + $spe_hol_ot_pay + $off_ot_pay;
                    $gross = $basic_pay + $ot_pay + $holidays_dayoff_pay;

                    // deductions
                    $sql_deductions = "SELECT * FROM deductions WHERE emp_id = '$empID'";
                    $dan_deductions = $con->query($sql_deductions) or die ($con->error);
                    $row_deductions = $dan_deductions->fetch_assoc();
                    $count_deductions = mysqli_num_rows($dan_deductions);

                    if($count_deductions > 0 && $payroll == 'Start')
                    {
                        $ar = $row_deductions['ar'];
                        $sss = $row_deductions['sss'];
                        $pagibig = $row_deductions['pagibig'];
                        $philhealth = $row_deductions['philhealth'];
                        $esf = $row_deductions['esf'];
                        $salary_loan = $row_deductions['salary_loan'];
                    }elseif($count_deductions > 0 && $payroll == 'End')
                    {
                        $ar = $row_deductions['ar'];
                        $sss = 0;
                        $pagibig = 0;
                        $philhealth = 0;
                        $esf = 0;
                        $salary_loan = $row_deductions['salary_loan'];
                    }elseif($count_deductions <= 0){
                        $ar = 0;
                        $sss = 0;
                        $pagibig = 0;
                        $philhealth = 0;
                        $esf = 0;
                        $salary_loan = 0;
                    }

                    $deductions = $ar + $sss + $pagibig + $philhealth + $esf + $salary_loan;

                    $net_pay = $gross - $deductions;

                    $regular_holiday = $reg_hol_pay + $reg_hol_ot_pay;
                    $special_holiday = $spe_hol_pay + $spe_hol_ot_pay;
                    $day_off = $off_pay + $off_ot_pay;

                    $regular_holiday_hrs = $reg_hol_hrs + $reg_hol_hrs2;
                    $special_holiday_hrs = $spe_hol_hrs + $spe_hol_hrs2;
                    $day_off_hrs = $off_hrs + $off_hrs2;

                    // ot, holidays & off w/ work pay
                    $additional_hrs = $total_ot + $regular_holiday_hrs + $special_holiday_hrs + $day_off_hrs;
                    $additional_pay = $ot_pay + $regular_holiday + $special_holiday + $day_off;
$html .= '<table width="100%">
<tr>
    <td colspan="5">*<td>
</tr>
<tr>
    <td style="width: 49%" >
        <table border="1" cellspacing="0" cellspadding="0" width="100%">
            <thead>
                <tr>
                    <th style="border: none"><img src="img/COH.png" alt="" style="width: 70px"></th>
                    <th style="border: none" colspan="3">COH Enterprises, Inc.
                    <br>
                    <b>Panday St., Goa, Camarines Sur</b>
                    <br>
                    <b>Period: '.date('M d, Y', strtotime($_SESSION['dtr_From'])).' to '.date('M d, Y', strtotime($_SESSION['dtr_To'])).'</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">Employee Name:</td>
                    <td colspan="3">'.$emp_name.'</td>
                </tr>
                <tr>
                    <td colspan="2">Designation:</td>
                    <td colspan="3">'.$position.'</td>
                </tr>
                <tr>
                    <th colspan="3">Earnings</th>
                    <th colspan="2">Deductions</th>
                </tr>
                <tr>
                    <td style="width: 25%">Particulars</td>
                    <td style="width: 10%">Hrs.</td>
                    <td style="width: 20%">Pay</td>
                    <td style="width: 25%">Particulars</td>
                    <td style="width: 20%">Amount</td>
                </tr>
                <tr>
                    <td>Reg. Days</td>
                    <td>'.round($total_hrs,2).'</td>
                    <td>'.number_format($basic_pay,2).'</td>
                    <td>A/R</td>
                    <td>'.$ar.'</td>
                </tr>
                <tr>
                    <td>Overtime</td>
                    <td>'.round($total_ot,2).'</td>
                    <td>'.number_format($ot_pay,2).'</td>
                    <td>SSS Contr.</td>
                    <td>'.$sss.'</td>
                </tr>
                <tr>
                    <td>Reg. Holiday</td>
                    <td>'.round($reg_hol_hrs,2).'</td>
                    <td>'.number_format($reg_hol_pay,2).'</td>
                    <td>PAG-IBIG</td>
                    <td>'.$pagibig.'</td>
                </tr>
                <tr>
                    <td>Reg.Hol. O.T.</td>
                    <td>'.round($reg_hol_hrs2,2).'</td>
                    <td>'.number_format($reg_hol_ot_pay,2).'</td>
                    <td>Philhealth</td>
                    <td>'.$philhealth.'</td>
                </tr>
                <tr>
                    <td>Spe. Non-Wor. Hol.</td>
                    <td>'.round($spe_hol_hrs,2).'</td>
                    <td>'.number_format($spe_hol_pay,2).'</td>
                    <td>ESF</td>
                    <td>'.$esf.'</td>
                </tr>
                <tr>
                    <td>S.H. Overtime</td>
                    <td>'.round($spe_hol_hrs2,2).'</td>
                    <td>'.number_format($spe_hol_ot_pay,2).'</td>
                    <td>Salary Loan</td>
                    <td>'.$salary_loan.'</td>
                </tr>
                <tr>
                    <td>Day-Off w/ Work</td>
                    <td>'.round($off_hrs,2).'</td>
                    <td>'.number_format($off_pay,2).'</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>D.W. Overtime</td>
                    <td>'.round($off_hrs2,2).'</td>
                    <td>'.number_format($off_ot_pay,2).'</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Total Earnings</th>
                    <th colspan="2">'.number_format($gross,2).'</th>
                    <th>Total Ded.</th>
                    <th>'.number_format($deductions,2).'</th>
                </tr>
                <tr>
                    <th colspan="3">Net Pay</th>
                    <th colspan="2">'.number_format($net_pay,2).'</th>
                </tr>
            </tbody>
        </table>
    </td>
    <td style="width: 2%"></td>
    <td style="width: 49%">
        <table border="1" cellspacing="0" cellspadding="0" width="100%">
            <thead>
                <tr>
                    <th style="border: none"><img src="img/COH.png" alt="" style="width: 70px"></th>
                    <th style="border: none" colspan="3">COH Enterprises, Inc.
                    <br>
                    <b>Panday St., Goa, Camarines Sur</b>
                    <br>
                    <b>Period: '.date('M d, Y', strtotime($_SESSION['dtr_From'])).' to '.date('M d, Y', strtotime($_SESSION['dtr_To'])).'</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">Employee Name:</td>
                    <td colspan="3">'.$emp_name.'</td>
                </tr>
                <tr>
                    <td colspan="2">Designation:</td>
                    <td colspan="3">'.$position.'</td>
                </tr>
                <tr>
                    <th colspan="3">Earnings</th>
                    <th colspan="2">Deductions</th>
                </tr>
                <tr>
                    <td style="width: 25%">Particulars</td>
                    <td style="width: 10%">Hrs.</td>
                    <td style="width: 20%">Pay</td>
                    <td style="width: 25%">Particulars</td>
                    <td style="width: 20%">Amount</td>
                </tr>
                <tr>
                    <td>Reg. Days</td>
                    <td>'.round($total_hrs,2).'</td>
                    <td>'.number_format($basic_pay,2).'</td>
                    <td>A/R</td>
                    <td>'.$ar.'</td>
                </tr>
                <tr>
                    <td>Overtime</td>
                    <td>'.round($total_ot,2).'</td>
                    <td>'.number_format($ot_pay,2).'</td>
                    <td>SSS Contr.</td>
                    <td>'.$sss.'</td>
                </tr>
                <tr>
                    <td>Reg. Holiday</td>
                    <td>'.round($reg_hol_hrs,2).'</td>
                    <td>'.number_format($reg_hol_pay,2).'</td>
                    <td>PAG-IBIG</td> 
                    <td>'.$pagibig.'</td>
                </tr>
                <tr>
                    <td>Reg.Hol. O.T.</td>
                    <td>'.round($reg_hol_hrs2,2).'</td>
                    <td>'.number_format($reg_hol_ot_pay,2).'</td>
                    <td>Philhealth</td>
                    <td>'.$philhealth.'</td>
                </tr>
                <tr>
                    <td>Spe. Non-Wor. Hol.</td>
                    <td>'.round($spe_hol_hrs,2).'</td>
                    <td>'.number_format($spe_hol_pay,2).'</td>
                    <td>ESF</td>
                    <td>'.$esf.'</td>
                </tr>
                <tr>
                    <td>S.H. Overtime</td>
                    <td>'.round($spe_hol_hrs2,2).'</td>
                    <td>'.number_format($spe_hol_ot_pay,2).'</td>
                    <td>Salary Loan</td>
                    <td>'.$salary_loan.'</td>
                </tr>
                <tr>
                    <td>Day-Off w/ Work</td>
                    <td>'.round($off_hrs,2).'</td>
                    <td>'.number_format($off_pay,2).'</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>D.W. Overtime</td>
                    <td>'.round($off_hrs2,2).'</td>
                    <td>'.number_format($off_ot_pay,2).'</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Total Earnings</th>
                    <th colspan="2">'.number_format($gross,2).'</th>
                    <th>Total Ded.</th>
                    <th>'.number_format($deductions,2).'</th>
                </tr>
                <tr>
                    <th colspan="3">Net Pay</th>
                    <th colspan="2">'.number_format($net_pay,2).'</th>
                </tr>
            </tbody>
        </table>            
    </td>
</tr>
    </table>';

            $no++;
            $total_gross = $total_gross + $gross;
            $total_deductions = $total_deductions + $deductions;
            $total_netpay = $total_netpay + $net_pay;
        }while($row_emp = $dan_emp->fetch_assoc());

    }

//    $html .= '</tbody></table>';
   $html .= '</body>';

   require_once __DIR__ . '/vendor/autoload.php';

   $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8','format' => 'Folio-L' ]);
   $mpdf->AddPageByArray([
    'margin-left' => 3,
    'margin-right' => 3,
    'margin-top' => 3,
    'margin-bottom' => 3,
    ]);
   $mpdf->SetDisplayMode('fullpage');
//    $mpdf->SetWatermarkImage('img/logo2.jpg');
//    $mpdf->showWatermarkImage = true;
//     $mpdf->SetDefaultBodyCSS('background', "url('img/logo.jpg')");
//    $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
//    $mpdf->setFooter(date('mdY', strtotime($_SESSION['dtr_From'])).'-'.date('mdY', strtotime($_SESSION['dtr_To'])).' -- [{DATE m-j-Y H:i:s}] * Page {PAGENO} of {nbpg}');
   $mpdf->WriteHTML( $html );
//    $mpdf->Output( 'Payroll('.date('mdY', strtotime($_SESSION['dtr_From'])).'-'.date('mdY', strtotime($_SESSION['dtr_To'])).').pdf', 'D' ); // Direct download your project directory
   $mpdf->Output();
   exit;
