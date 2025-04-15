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

$department = str_replace("'", "\'", $department);

// payrolls
$payroll_h = "SELECT * FROM payroll_allowance WHERE payroll_id = '$payroll_id' && dept = '$department'";
$dan_payroll_h = $con->query($payroll_h) or die ($con->error);
$row_payroll_h = $dan_payroll_h->fetch_assoc();
$count_payroll_h = mysqli_num_rows($dan_payroll_h);

// payroll_history
$payroll_history = "SELECT * FROM payroll_history WHERE payroll_id = '$payroll_id'";
$dan_payroll_history = $con->query($payroll_history) or die ($con->error);
$row_payroll_history = $dan_payroll_history->fetch_assoc();
$count_payroll_history = mysqli_num_rows($dan_payroll_history);

$html = '';
$html .= '<body style="font-family: Calibri; font-size:17px">';

if($count_payroll_h > 0){
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
                <table border="1" cellspacing="27px" cellspadding="" width="100%">
                    <thead>
                        <tr>
                            <th style="border: none;"><img src="img/COH.jpg" alt="" style="width: 177px; padding: 0; margin: 0; height: 87px"></th>
                            <th style="border: none; text-align: left" colspan="3"><h2><strong style="font-size:47px; color:#003300">COH Enterprises, Inc.</strong>
                            <br>
                            <b style="color:#2e2e1f">Panday St., Goa, Camarines Sur</b>
                            <br>
                            <b style="font-size:30px; color:#003300;">Cash Voucher</b>
                            <br>
                            <b style="color:#2e2e1f">'.date('F d, Y').'</b>
                            </h2></th>
                        </tr>
                        <tr>
                            <td colspan="" style="border: none; color:padding:7px; font-size:25px"><h2>Pay to order of:</h2></td>
                            <td style="border: none;color:#003366; padding:7px; font-size:30px" colspan="3"><h2>'.$row_payroll_h['emp_name'].'</h2></td>
                        </tr>
                        <tr>
                            <td colspan="" style="border: none; color:padding:7px; font-size:25px"><h3>Position/Department:</h3></td>
                            <td style="border: none; color:#003366; padding:7px; font-size:25px" colspan="3"><h3>'.$position.' / '.$dept.'</h3></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th style="border: none; width: 35%; font-size:27px" colspan="2">Particulars</th>
                            <th style="border: none; width: 20%; font-size:27px" colspan="2">Amount</th>
                        </tr>
                        <tr>
                            <td colspan="2" style="border: none; font-size:30px">
                                Representing payment for Incentives & Performance Bonus for the period of 
                                <br>
                                <b><u>'.date('F d, Y', strtotime($from)).' - '.date('F d, Y', strtotime($to)).'</u><b>    
                            </td>
                            <td colspan="2" style="border: none; font-size:30px; text-align: center;">	&#8369; '.number_format($row_payroll_h['net_pay'],2).'</td>
                        </tr>
                        <tr>
                            <td style="border: none; font-size:27px">Total worked hours</td>
                            <td style="border: none; font-size:27px"><b>'.number_format(floatval(str_replace( ',', '', $row_payroll_h['reg_hrs'])),2) + number_format(floatval(str_replace( ',', '', $row_payroll_h['add_hrs'])),2).'</b></td>
                            <td colspan="2" style="border: none;"></td>
                        </tr>
                        <tr>
                            <th colspan="2" style="border: none; color:#003366; font-size:27px"><h2>Net Pay</h2></th>
                            <th colspan="2" style="border: none; color:#003366; font-size:27px"><h2>	&#8369; '.number_format($row_payroll_h['net_pay'],2).'</h2></th>
                        </tr>
                        <tr>
                            <td colspan="2" style="border: none; text-align: left; padding: 20px 5px; font-size:27px">
                                <br>
                                <br>
                                APPROVED BY:
                                <br>
                                <br>
                                <i>____________________________________</i>
                            </td>
                            <td colspan="2" style="border: none; text-align: left; padding: 20px 5px; font-size:27px">
                                <br>
                                <br>
                                RECEIVED BY:
                                <br>
                                <br>
                                <i>____________________________________</i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td style="width: 6%"></td>
            <td style="width: 47%">
            <table border="1" cellspacing="27px" cellspadding="" width="100%">
            <thead>
                <tr>
                    <th style="border: none;"><img src="img/COH.jpg" alt="" style="width: 177px; padding: 0; margin: 0; height: 87px"></th>
                    <th style="border: none; text-align: left" colspan="3"><h2><strong style="font-size:47px; color:#003300">COH Enterprises, Inc.</strong>
                    <br>
                    <b style="color:#2e2e1f">Panday St., Goa, Camarines Sur</b>
                    <br>
                    <b style="font-size:30px; color:#003300;">Cash Voucher</b>
                    <br>
                    <b style="color:#2e2e1f">'.date('F d, Y').'</b>
                    </h2></th>
                </tr>
                <tr>
                    <td colspan="" style="border: none; color:padding:7px; font-size:25px"><h2>Pay to order of:</h2></td>
                    <td style="border: none;color:#003366; padding:7px; font-size:30px" colspan="3"><h2>'.$row_payroll_h['emp_name'].'</h2></td>
                </tr>
                <tr>
                    <td colspan="" style="border: none; color:padding:7px; font-size:25px"><h3>Position/Department:</h3></td>
                    <td style="border: none; color:#003366; padding:7px; font-size:25px" colspan="3"><h3>'.$position.' / '.$dept.'</h3></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="border: none; width: 35%; font-size:27px" colspan="2">Particulars</th>
                    <th style="border: none; width: 20%; font-size:27px" colspan="2">Amount</th>
                </tr>
                <tr>
                    <td colspan="2" style="border: none; font-size:30px">
                        Representing payment for Incentives & Performance Bonus for the period of 
                        <br>
                        <b><u>'.date('F d, Y', strtotime($from)).' - '.date('F d, Y', strtotime($to)).'</u><b>    
                    </td>
                    <td colspan="2" style="border: none; font-size:30px; text-align: center;">	&#8369; '.number_format($row_payroll_h['net_pay'],2).'</td>
                </tr>
                <tr>
                    <td style="border: none; font-size:27px">Total worked hours</td>
                    <td style="border: none; font-size:27px"><b>'.number_format(floatval(str_replace( ',', '', $row_payroll_h['reg_hrs'])),2) + number_format(floatval(str_replace( ',', '', $row_payroll_h['add_hrs'])),2).'</b></td>
                    <td colspan="2" style="border: none;"></td>
                </tr>
                <tr>
                    <th colspan="2" style="border: none; color:#003366; font-size:27px"><h2>Net Pay</h2></th>
                    <th colspan="2" style="border: none; color:#003366; font-size:27px"><h2>	&#8369; '.number_format($row_payroll_h['net_pay'],2).'</h2></th>
                </tr>
                <tr>
                    <td colspan="2" style="border: none; text-align: left; padding: 20px 5px; font-size:27px">
                        <br>
                        <br>
                        APPROVED BY:
                        <br>
                        <br>
                        <i>____________________________________</i>
                    </td>
                    <td colspan="2" style="border: none; text-align: left; padding: 20px 5px; font-size:27px">
                        <br>
                        <br>
                        RECEIVED BY:
                        <br>
                        <br>
                        <i>____________________________________</i>
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

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8','format' => 'Letter' ]);
// $mpdf->AddPageByArray([
//  'margin-left' => 5,
//  'margin-right' => 5,
//  'margin-top' => 7,
//  'margin-bottom' => 7,
//  ]);
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