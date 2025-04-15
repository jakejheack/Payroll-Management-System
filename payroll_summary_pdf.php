<?php
session_start();

if(isset($_SESSION['Login']) && isset($_SESSION['Usernames'])){
    $username = $_SESSION['Usernames'];
    $user_access = $_SESSION['Access'];
    $store_supervisor = $_SESSION['Store'];
    $_SESSION['department'] = $_SESSION['Store'];
}else{
    echo header("Location: logout.php");
    // exit();
}

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");

if(isset($_GET['payroll_id'])){
    $payroll_id = $_GET['payroll_id'];

    //  payroll summary
    $sql_pay_summary = "SELECT * FROM payroll_summary WHERE payroll_id = '$payroll_id'";
    $dan_pay_summary = $con->query($sql_pay_summary) or die ($con->error);
    $row_pay_summary = $dan_pay_summary->fetch_assoc();
    $count_pay_summary = mysqli_num_rows($dan_pay_summary);

    if($count_pay_summary > 0){
        $pay_date = $row_pay_summary['pay_date'];
        $payroll = $row_pay_summary['cut_off'];
        $from = $row_pay_summary['froms'];
        $to = $row_pay_summary['tos'];
        $id_pay = $row_pay_summary['ID'];

        //  payroll history
        $sql_pay_history = "SELECT * FROM payroll_history WHERE payroll_id = '$payroll_id' ORDER BY department ASC";
        $dan_pay_history = $con->query($sql_pay_history) or die ($con->error);
        $row_pay_history = $dan_pay_history->fetch_assoc();
        $count_pay_history = mysqli_num_rows($dan_pay_history);
    }else{
        header("Location: payroll_summary.php");
    }
}

$html = '';
$html .= '<body style="font-family: Calibri;">';
if($count_pay_history > 0){
    do{
        $dept_id = $row_pay_history['dept_id'];

        //  payrolls
        $sql_pays = "SELECT * FROM payrolls WHERE payroll_id = '$payroll_id' && dept = '$dept_id'";
        $dan_pays = $con->query($sql_pays) or die ($con->error);
        $row_pays = $dan_pays->fetch_assoc();
        $count_pays = mysqli_num_rows($dan_pays);

        if($count_pays > 0){
            if($payroll == 'Start'){
            $html .='<table style="width:100%; text-align:center; border-collapse:collapse">
                        <thead>
                            <tr>
                                <th colspan="9" style="padding:5px; color:red; text-align:left; border:none">'.$row_pay_history['department'].'</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr style="border:1px solid black;">
                                <th  style=" padding:5px; border:1px solid black;">Gross Pay</th>
                                <th  style=" padding:5px; border:1px solid black;">SSS Cont.</th>
                                <th  style=" padding:5px; border:1px solid black;">PAGIBIG Cont.</th>
                                <th  style=" padding:5px; border:1px solid black;">PH Cont.</th>
                                <th  style=" padding:5px; border:1px solid black;">ESF</th>
                                <th  style=" padding:5px; border:1px solid black;">A/R</th>
                                <th  style=" padding:5px; border:1px solid black;">Shortages</th>
                                <th  style=" padding:5px; border:1px solid black;">Salary Loan</th>
                                <th  style=" padding:5px; border:1px solid black;">Net Pay</th>
                            </tr>
                        </thead>
                        <tbody>';
                                $gross = 0;
                                $sss = 0;
                                $pagibig = 0;
                                $ph = 0;
                                $esf = 0;
                                $ar = 0;
                                $shortages = 0;
                                $salary_loan = 0;
                                $net_pay = 0;
                                do{
                                    $gross = $gross + floatval(str_replace(",", "", $row_pays['gross']));
                                    $sss = $sss + floatval(str_replace(",", "", $row_pays['sss']));
                                    $pagibig = $pagibig + floatval(str_replace(",", "", $row_pays['pagibig']));
                                    $ph = $ph + floatval(str_replace(",", "", $row_pays['philhealth']));
                                    $esf = $esf + floatval(str_replace(",", "", $row_pays['esf']));
                                    $ar = $ar + floatval(str_replace(",", "", $row_pays['ar']));
                                    $shortages = $shortages + floatval(str_replace(",", "", $row_pays['shortages']));
                                    $salary_loan = $salary_loan + floatval(str_replace(",", "", $row_pays['salary_loan']));
                                    $net_pay = $net_pay + floatval(str_replace(",", "", $row_pays['net_pay']));
                                }while($row_pays = $dan_pays->fetch_assoc());
                            $html.= '<tr>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($gross,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($sss,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($pagibig,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($ph,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($esf,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($ar,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($shortages,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($salary_loan,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($net_pay,2).'</td>
                                </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th  style=" padding:5px; border:1px solid black;">'.number_format($gross,2).'</th>
                                <th  style=" padding:5px; border:1px solid black;">'.number_format($sss,2).'</th>
                                <th  style=" padding:5px; border:1px solid black;">'.number_format($pagibig,2).'</th>
                                <th  style=" padding:5px; border:1px solid black;">'.number_format($ph,2).'</th>
                                <th  style=" padding:5px; border:1px solid black;">'.number_format($esf,2).'</th>
                                <th  style=" padding:5px; border:1px solid black;">'.number_format($ar,2).'</th>
                                <th  style=" padding:5px; border:1px solid black;">'.number_format($shortages,2).'</th>
                                <th  style=" padding:5px; border:1px solid black;">'.number_format($salary_loan,2).'</th>
                                <th  style=" padding:5px; border:1px solid black;">'.number_format($net_pay,2).'</th>
                            </tr>
                        </tfoot>
                    </table>';
            }elseif($payroll = 'End'){
                $html.='<table style="width:100%; text-align:center; border-collapse:collapse">
                        <thead>
                            <tr>
                                <th colspan="10" style="padding:5px; color:red; text-align:left; border:none">'.$row_pay_history['department'].'</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <th style=" padding:5px; border:1px solid black;">Gross Pay</th>
                                <th style=" padding:5px; border:1px solid black;">SSS Loan</th>
                                <th style=" padding:5px; border:1px solid black;">SSS Calamity</th>
                                <th style=" padding:5px; border:1px solid black;">HDMF Loan</th>
                                <th style=" padding:5px; border:1px solid black;">HDMF Calamity</th>
                                <th style=" padding:5px; border:1px solid black;">ESF</th>
                                <th style=" padding:5px; border:1px solid black;">A/R</th>
                                <th style=" padding:5px; border:1px solid black;">Shortages</th>
                                <th style=" padding:5px; border:1px solid black;">Salary Loan</th>
                                <th style=" padding:5px; border:1px solid black;">Net Pay</th>
                            </tr>
                        </thead>
                        <tbody>';
                                $gross = 0;
                                $sss_loan = 0;
                                $sss_calamity = 0;
                                $hdmf_loan = 0;
                                $hdmf_calamity = 0;
                                $esf = 0;
                                $ar = 0;
                                $shortages = 0;
                                $salary_loan = 0;
                                $net_pay = 0;
                                do{
                                    $gross = $gross + floatval(str_replace(",", "", $row_pays['gross']));
                                    $sss_loan = $sss_loan + floatval(str_replace(",", "", $row_pays['sss_loan']));
                                    $sss_calamity = $sss_calamity + floatval(str_replace(",", "", $row_pays['sss_calamity']));
                                    $hdmf_loan = $hdmf_loan + floatval(str_replace(",", "", $row_pays['hdmf_loan']));
                                    $hdmf_calamity = $hdmf_calamity + floatval(str_replace(",", "", $row_pays['hdmf_calamity']));
                                    $esf = $esf + floatval(str_replace(",", "", $row_pays['esf']));
                                    $ar = $ar + floatval(str_replace(",", "", $row_pays['ar']));
                                    $shortages = $shortages + floatval(str_replace(",", "", $row_pays['shortages']));
                                    $salary_loan = $salary_loan + floatval(str_replace(",", "", $row_pays['salary_loan']));
                                    $net_pay = $net_pay + floatval(str_replace(",", "", $row_pays['net_pay']));
                                }while($row_pays = $dan_pays->fetch_assoc());
                            $html.='<tr>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($gross,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($sss_loan,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($sss_calamity,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($hdmf_loan,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($hdmf_calamity,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($esf,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($ar,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($shortages,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($salary_loan,2).'</td>
                                    <td style=" padding:5px; border:1px solid black;">'.number_format($net_pay,2).'</td>
                                </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style=" padding:5px; border:1px solid black;">'.number_format($gross,2).'</th>
                                <th style=" padding:5px; border:1px solid black;">'.number_format($sss_loan,2).'</th>
                                <th style=" padding:5px; border:1px solid black;">'.number_format($sss_calamity,2).'</th>
                                <th style=" padding:5px; border:1px solid black;">'.number_format($hdmf_loan,2).'</th>
                                <th style=" padding:5px; border:1px solid black;">'.number_format($hdmf_calamity,2).'</th>
                                <th style=" padding:5px; border:1px solid black;">'.number_format($esf,2).'</th>
                                <th style=" padding:5px; border:1px solid black;">'.number_format($ar,2).'</th>
                                <th style=" padding:5px; border:1px solid black;">'.number_format($shortages,2).'</th>
                                <th style=" padding:5px; border:1px solid black;">'.number_format($salary_loan,2).'</th>
                                <th style=" padding:5px; border:1px solid black;">'.number_format($net_pay,2).'</th>
                            </tr>
                        </tfoot>
                    </table>';
            }
        }
        $html.= '<span>Approved for Payment</span>
            <br><br>
            <span>_____________________________________</span>
            <p></p>';

    }while($row_pay_history = $dan_pay_history->fetch_assoc());
}


$html .= '</body>';

require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8','format' => 'Letter-P' ]);

$mpdf->SetHTMLHeader('
<div style="text-align: center; font-weight: bold;">
    <img src="img/COH.jpg" alt="" style="width: 120px; padding: 0; margin: 0; height: 70px">
    <br>
    <span style="font-size:12px">Panday St., Goa, Camarines Sur</span>
    <br>
    <span style="font-size:16px"> SUMMARY OF PAYROLL ( '.$pay_date.' ) </span>
</div>');

$mpdf->AddPageByArray([
 'margin-left' => 3,
 'margin-right' => 3,
 'margin-top' => 41,
 'margin-bottom' => 18,
 ]);

$mpdf->SetDisplayMode('fullpage');
//    $mpdf->SetWatermarkImage('img/logo.jpg');
//    $mpdf->showWatermarkImage = true;
 // $mpdf->SetDefaultBodyCSS('background', "url('img/logo.jpg')");
//    $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
 // $mpdf->SetHTMLHeader('<div><img src="img/COH.jpg"/></div>');
 $mpdf->setFooter('Printed by: '.$username.' | Page {PAGENO} of {nbpg} | Date & Time Printed: {DATE M j, Y h:i:s A}');
 $mpdf->WriteHTML( $html );
//    $mpdf->Output( 'Payroll('.date('mdY', strtotime($_SESSION['dtr_From'])).'-'.date('mdY', strtotime($_SESSION['dtr_To'])).').pdf', 'D' ); // Direct download your project directory
$mpdf->Output();
exit;