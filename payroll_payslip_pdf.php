<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");

if(isset($_GET['payroll_id']) && isset($_GET['department'])){
    $payroll_id = trim($_GET['payroll_id']);
    $department = trim($_GET['department']);
}else{
    header("Location: payroll_summary.php");
}

// payrolls
$payroll_h = "SELECT * FROM payrolls WHERE payroll_id = '$payroll_id' && dept = '$department'";
$dan_payroll_h = $con->query($payroll_h) or die ($con->error);
$row_payroll_h = $dan_payroll_h->fetch_assoc();
$count_payroll_h = mysqli_num_rows($dan_payroll_h);

// payroll_history
$payroll_history = "SELECT * FROM payroll_history WHERE payroll_id = '$payroll_id'";
$dan_payroll_history = $con->query($payroll_history) or die ($con->error);
$row_payroll_history = $dan_payroll_history->fetch_assoc();
$count_payroll_history = mysqli_num_rows($dan_payroll_history);

$html = '';
$html .= '<body style="font-family: Calibri; font-size: 14pt;">';

if($count_payroll_h > 0 && $count_payroll_history > 0){
    $from = $row_payroll_history['froms'];
    $to = $row_payroll_history['tos'];
    $cut_off = $row_payroll_history['cut_off'];
        do{
            $emp_id = $row_payroll_h['emp_id'];
            // employees info
            $sql_emp = "SELECT * FROM employees WHERE ID = '$emp_id'";
            $dan_emp = $con->query($sql_emp) or die ($con->error);
            $row_emp = $dan_emp->fetch_assoc();
            $count_emp = mysqli_num_rows($dan_emp);

            if($count_emp > 0){
                $position = $row_emp['position'];
                $dept = $row_emp['dept'];
            }else{
                $position = '';
                $dept = '';
            }

        $html .='<table width="100%">
        <tr>
            <td style="padding: 30px" colspan="4">*<td>
        </tr>
        <tr>
            <td style="width: 47%">
                <table border="1" cellspacing="0" cellspadding="0" width="100%">
                    <thead>
                        <tr>
                            <th style="border: none;"><img src="img/COH.jpg" alt="" style="width: 177px; padding: 0; margin: 0; height: 87px"></th>
                            <th style="border: none; text-align: left" colspan="3"><h3><strong style="font-size: 40px; color:#003300">COH Enterprises, Inc.</strong>
                            <br>
                            <b style="color:#2e2e1f">Panday St., Goa, Camarines Sur</b>
                            <br>
                            <b style="color:#003300; font-size: 30px">Employee`s Payslip</b>
                            <br>
                            <b style="color:#2e2e1f">Period Covered: '.date('M d, Y', strtotime($from)).' - '.date('M d, Y', strtotime($to)).'</b>
                            </h3></th>
                        </tr>
                        <tr>
                            <td colspan=""><h2>Employee`s Name:</h2></td>
                            <td style="color:#003366; padding:7px" colspan="3"><h2>'.$row_payroll_h['emp_name'].'</h2></td>
                        </tr>
                        <tr>
                            <td colspan=""><b>Position/Department:</b></td>
                            <td style="color:#003366; padding:7px" colspan=""><b>'.$position.'</b></td>
                            <td style="color:#003300; padding:7px; font-size: 25px" colspan="2"><b>'.$dept.'</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="4" style="background-color:#EFFCF2; color:#154925; padding:7px">Earnings</th>
                        </tr>
                        <tr>
                            <th style="width: 25%" colspan="2">Particulars</th>
                            <th style="width: 10%">Hrs.</th>
                            <th style="width: 20%">Pay</th>
                        </tr>
                        <tr>
                            <td colspan="2">Regular Hours</td>
                            <td><b>'.$row_payroll_h['reg_hrs'].'</b></td>
                            <td><b>'.$row_payroll_h['reg_pay'].'</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">Overtime</td>
                            <td><b>'.$row_payroll_h['ot_hrs'].'</b></td>
                            <td><b>'.$row_payroll_h['ot_pay'].'</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">Regular Holiday</td>
                            <td><b>'.$row_payroll_h['reg_hol_hrs'].'</b></td>
                            <td><b>'.$row_payroll_h['reg_hol_pay'].'</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">Special Holiday</td>
                            <td><b>'.$row_payroll_h['spe_hol_hrs'].'</b></td>
                            <td><b>'.$row_payroll_h['spe_hol_pay'].'</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">Work on Dayoff</td>
                            <td><b>'.$row_payroll_h['off_hrs'].'</b></td>
                            <td><b>'.$row_payroll_h['off_pay'].'</b></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Total Worked Hrs/Amount</b></td>
                            <td><b>'.number_format(floatval(str_replace( ',', '', $row_payroll_h['reg_hrs'])) + floatval(str_replace( ',', '', $row_payroll_h['add_hrs'])),2).'</b></td>
                            <td><b>'.number_format(floatval(str_replace( ',', '', $row_payroll_h['reg_pay'])) + floatval(str_replace( ',', '', $row_payroll_h['add_pay'])),2).'</b></td>
                        </tr>
                        <tr>
                            <th colspan="2" style="color:#154925">Total Earnings</th>
                            <th></th>
                            <th colspan="" style="color:#154925">&#8369; '.$row_payroll_h['gross'].'</th>
                        </tr>
                        <tr>
                            <th colspan="4" style="background-color:#EFFCF2; color:#B80909; padding:7px">Deductions</th>
                        </tr>';

                            if($cut_off == 'Start'){
                                $html .= '<tr>
                                            <td>SSS Contr.</td>
                                            <td><b>'.number_format($row_payroll_h['sss'],2).'</b></td>
                                            <td>ESF</td>
                                            <td><b>'.number_format($row_payroll_h['esf'],2).'</b></td>
                                        </tr>';
                            }elseif($cut_off == 'End'){
                                $html .= '<tr><td>SSS Loan</td>
                                        <td><b>'.number_format($row_payroll_h['sss_loan'],2).'</b></td>
                                        <td>ESF</td>
                                        <td><b>'.number_format($row_payroll_h['esf'],2).'</b></td>
                                        </tr>';
                            }

                            if($cut_off == 'Start'){
                                $html .= '<tr>
                                            <td>HDMF</td>
                                            <td><b>'.number_format($row_payroll_h['pagibig'],2).'</b></td>
                                            <td>A/R</td>
                                            <td><b>'.number_format($row_payroll_h['ar'],2).'</b></td>
                                        </tr>';
                            }elseif($cut_off == 'End'){
                                $html .= '<tr><td>SSS Calamity</td>
                                        <td><b>'.number_format($row_payroll_h['sss_calamity'],2).'</b></td>
                                        <td>A/R</td>
                                        <td><b>'.number_format($row_payroll_h['ar'],2).'</b></td>
                                        </tr>';
                            }

                            if($cut_off == 'Start'){
                                $html .= '<tr>
                                            <td>PHILHEALTH</td>
                                            <td><b>'.number_format($row_payroll_h['philhealth'],2).'</b></td>
                                            <td>Shortages</td>
                                            <td><b>'.number_format($row_payroll_h['shortages'],2).'</b></td>
                                        </tr>';
                            }elseif($cut_off == 'End'){
                                $html .= '<tr><td>HDMF Loan</td>
                                        <td><b>'.number_format($row_payroll_h['hdmf_loan'],2).'</b></td>
                                        <td>Shortages</td>
                                        <td><b>'.number_format($row_payroll_h['shortages'],2).'</b></td>
                                        </tr>';
                            }

                            if($cut_off == 'Start'){
                                $html .= '<tr>
                                            <td></td>
                                            <td></td>
                                            <td>Salary Loan</td>
                                            <td><b>'.number_format($row_payroll_h['salary_loan'],2).'</b></td>
                                        </tr>';
                            }elseif($cut_off == 'End'){
                                $html .= '<tr><td>HDMF Calamity</td>
                                        <td><b>'.number_format($row_payroll_h['hdmf_calamity'],2).'</b></td>
                                        <td>Salary Loan</td>
                                        <td><b>'.number_format($row_payroll_h['salary_loan'],2).'</b></td>
                                        </tr>';
                            }
                                   
$html .=        '<tr>
                            <th colspan="2" style="color:#B80909;">Total Deductions</th>
                            <th colspan="2" style="color:#B80909;">&#8369; '.$row_payroll_h['deductions'].'</th>
                        </tr>
                        <tr>
                            <th colspan="2" style="color:#003366;"><h2>Net Pay</h2></th>
                            <th colspan="2" style="color:#003366;"><h2>	&#8369; '.$row_payroll_h['net_pay'].'</h2></th>
                        </tr>
                        <tr style="">
                            <td colspan="4" style="text-align: center; padding: 10px 0; font-size:27px">
                                <p><i>I acknowledge to have recieved the amount of <b><u>&#8369; '.$row_payroll_h['net_pay'].'</u></b> and have no further claims for services rendered.</i></p>
                                <br>
                                <i>____________________________________
                                <br>
                                Employee`s Signature
                                </i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td style="width: 6%"></td>
            <td style="width: 47%">
                <table border="1" cellspacing="0" cellspadding="0" width="100%">
                    <thead>
                        <tr>
                            <th style="border: none;"><img src="img/COH.jpg" alt="" style="width: 177px; padding: 0; margin: 0; height: 87px"></th>
                            <th style="border: none; text-align: left" colspan="3"><h3><strong style="font-size: 40px; color:#003300">COH Enterprises, Inc.</strong>
                            <br>
                            <b style="color:#2e2e1f">Panday St., Goa, Camarines Sur</b>
                            <br>
                            <b style="color:#003300; font-size: 30px">Employee`s Payslip</b>
                            <br>
                            <b style="color:#2e2e1f">Period Covered: '.date('M d, Y', strtotime($from)).' - '.date('M d, Y', strtotime($to)).'</b>
                            </h3></th>
                        </tr>
                        <tr>
                            <td colspan=""><h2>Employee`s Name:</h2></td>
                            <td style="color:#003366; padding:7px" colspan="3"><h2>'.$row_payroll_h['emp_name'].'</h2></td>
                        </tr>
                        <tr>
                        <td colspan=""><b>Position/Department:</b></td>
                        <td style="color:#003366; padding:7px" colspan=""><b>'.$position.'</b></td>
                        <td style="color:#003300; padding:7px; font-size: 25px" colspan="2"><b>'.$dept.'</b></td>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="4" style="background-color:#EFFCF2; color:#154925; padding:7px">Earnings</th>
                        </tr>
                        <tr>
                            <th style="width: 25%" colspan="2">Particulars</th>
                            <th style="width: 10%">Hrs.</th>
                            <th style="width: 20%">Pay</th>
                        </tr>
                        <tr>
                            <td colspan="2">Regular Hours</td>
                            <td><b>'.$row_payroll_h['reg_hrs'].'</b></td>
                            <td><b>'.$row_payroll_h['reg_pay'].'</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">Overtime</td>
                            <td><b>'.$row_payroll_h['ot_hrs'].'</b></td>
                            <td><b>'.$row_payroll_h['ot_pay'].'</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">Regular Holiday</td>
                            <td><b>'.$row_payroll_h['reg_hol_hrs'].'</b></td>
                            <td><b>'.$row_payroll_h['reg_hol_pay'].'</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">Special Holiday</td>
                            <td><b>'.$row_payroll_h['spe_hol_hrs'].'</b></td>
                            <td><b>'.$row_payroll_h['spe_hol_pay'].'</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">Work on Dayoff</td>
                            <td><b>'.$row_payroll_h['off_hrs'].'</b></td>
                            <td><b>'.$row_payroll_h['off_pay'].'</b></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Total Worked Hrs/Amount</b></td>
                            <td><b>'.number_format(floatval(str_replace( ',', '', $row_payroll_h['reg_hrs'])) + floatval(str_replace( ',', '', $row_payroll_h['add_hrs'])),2).'</b></td>
                            <td><b>'.number_format(floatval(str_replace( ',', '', $row_payroll_h['reg_pay'])) + floatval(str_replace( ',', '', $row_payroll_h['add_pay'])),2).'</b></td>
                        </tr>
                        <tr>
                            <th colspan="2" style="color:#154925">Total Earnings</th>
                            <th></th>
                            <th colspan="" style="color:#154925">&#8369; '.$row_payroll_h['gross'].'</th>
                        </tr>
                        <tr>
                            <th colspan="4" style="background-color:#EFFCF2; color:#B80909; padding:7px">Deductions</th>
                        </tr>';

                            if($cut_off == 'Start'){
                                $html .= '<tr>
                                            <td>SSS Contr.</td>
                                            <td><b>'.number_format($row_payroll_h['sss'],2).'</b></td>
                                            <td>ESF</td>
                                            <td><b>'.number_format($row_payroll_h['esf'],2).'</b></td>
                                        </tr>';
                            }elseif($cut_off == 'End'){
                                $html .= '<tr><td>SSS Loan</td>
                                        <td><b>'.number_format($row_payroll_h['sss_loan'],2).'</b></td>
                                        <td>ESF</td>
                                        <td><b>'.number_format($row_payroll_h['esf'],2).'</b></td>
                                        </tr>';
                            }

                            if($cut_off == 'Start'){
                                $html .= '<tr>
                                            <td>HDMF</td>
                                            <td><b>'.number_format($row_payroll_h['pagibig'],2).'</b></td>
                                            <td>A/R</td>
                                            <td><b>'.number_format($row_payroll_h['ar'],2).'</b></td>
                                        </tr>';
                            }elseif($cut_off == 'End'){
                                $html .= '<tr><td>SSS Calamity</td>
                                        <td><b>'.number_format($row_payroll_h['sss_calamity'],2).'</b></td>
                                        <td>A/R</td>
                                        <td><b>'.number_format($row_payroll_h['ar'],2).'</b></td>
                                        </tr>';
                            }

                            if($cut_off == 'Start'){
                                $html .= '<tr>
                                            <td>PHILHEALTH</td>
                                            <td><b>'.number_format($row_payroll_h['philhealth'],2).'</b></td>
                                            <td>Shortages</td>
                                            <td><b>'.number_format($row_payroll_h['shortages'],2).'</b></td>
                                        </tr>';
                            }elseif($cut_off == 'End'){
                                $html .= '<tr><td>HDMF Loan</td>
                                        <td><b>'.number_format($row_payroll_h['hdmf_loan'],2).'</b></td>
                                        <td>Shortages</td>
                                        <td><b>'.number_format($row_payroll_h['shortages'],2).'</b></td>
                                        </tr>';
                            }

                            if($cut_off == 'Start'){
                                $html .= '<tr>
                                            <td></td>
                                            <td></td>
                                            <td>Salary Loan</td>
                                            <td><b>'.number_format($row_payroll_h['salary_loan'],2).'</b></td>
                                        </tr>';
                            }elseif($cut_off == 'End'){
                                $html .= '<tr><td>HDMF Calamity</td>
                                        <td><b>'.number_format($row_payroll_h['hdmf_calamity'],2).'</b></td>
                                        <td>Salary Loan</td>
                                        <td><b>'.number_format($row_payroll_h['salary_loan'],2).'</b></td>
                                        </tr>';
                            }
                                   
$html .=        '<tr>
                            <th colspan="2" style="color:#B80909;">Total Deductions</th>
                            <th colspan="2" style="color:#B80909;">&#8369; '.$row_payroll_h['deductions'].'</th>
                        </tr>
                        <tr>
                            <th colspan="2" style="color:#003366;"><h2>Net Pay</h2></th>
                            <th colspan="2" style="color:#003366;"><h2>	&#8369; '.$row_payroll_h['net_pay'].'</h2></th>
                        </tr>
                        <tr style="">
                            <td colspan="4" style="text-align: center; padding: 10px 0; font-size:27px">
                                <p><i>I acknowledge to have recieved the amount of <b><u>&#8369; '.$row_payroll_h['net_pay'].'</u></b> and have no further claims for services rendered.</i></p>
                                <br>
                                <i>____________________________________
                                <br>
                                Employee`s Signature
                                </i>
                            </td>
                        </tr>
                    </tbody>
                </table>
        </td>
        </tr>
            </table>';
    }while($row_payroll_h = $dan_payroll_h->fetch_assoc());
}

$html .= '</body>';

require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8','format' => 'Folio-L' ]);
$mpdf->AddPageByArray([
 'margin-left' => 5,
 'margin-right' => 5,
 'margin-top' => 7,
 'margin-bottom' => 7,
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