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
        $sql_pays = "SELECT * FROM payrolls WHERE payroll_id = '$payroll_id' && dept = '$dept_id' ORDER BY emp_name ASC";
        $dan_pays = $con->query($sql_pays) or die ($con->error);
        $row_pays = $dan_pays->fetch_assoc();
        $count_pays = mysqli_num_rows($dan_pays);

        if($count_pays > 0){
            if($payroll == 'Start'){
            $html .='<table style=" width:100%; text-align:center; border-collapse:collapse">
                        <thead>
                            <tr>
                                <th colspan="8" style="color:blue; text-align:left; border:none">'.$row_pay_history['department'].'</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr style="border:1px solid black;">
                                <th rowspan="2" style="border:1px solid black;">Employee`s Name</th>
                                <th colspan="3" style="border:1px solid black;">Contributions</th>
                                <th colspan="4" style="border:1px solid black;">Others</th>
                            </tr>
                            <tr style="border:1px solid black;">
                                <th  style="border:1px solid black;">SSS</th>
                                <th  style="border:1px solid black;">PAGIBIG</th>
                                <th  style="border:1px solid black;">PHILHEALTH</th>
                                <th  style="border:1px solid black;">ESF</th>
                                <th  style="border:1px solid black;">A/R</th>
                                <th  style="border:1px solid black;">Shortages</th>
                                <th  style="border:1px solid black;">Salary Loan</th>
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
                                $no = 0;
                                do{
                                    $no++;
                                    $gross = $gross + floatval(str_replace(",", "", $row_pays['gross']));
                                    $sss = $sss + floatval(str_replace(",", "", $row_pays['sss']));
                                    $pagibig = $pagibig + floatval(str_replace(",", "", $row_pays['pagibig']));
                                    $ph = $ph + floatval(str_replace(",", "", $row_pays['philhealth']));
                                    $esf = $esf + floatval(str_replace(",", "", $row_pays['esf']));
                                    $ar = $ar + floatval(str_replace(",", "", $row_pays['ar']));
                                    $shortages = $shortages + floatval(str_replace(",", "", $row_pays['shortages']));
                                    $salary_loan = $salary_loan + floatval(str_replace(",", "", $row_pays['salary_loan']));
                                    $net_pay = $net_pay + floatval(str_replace(",", "", $row_pays['net_pay']));
                            $html.= '<tr>
                                        <td style="border:1px solid black; text-align:left; padding:5px; width:270px">'.$no.'. '.$row_pays['emp_name'].'</td>
                                        <td style="border:1px solid black;">'.($row_pays['sss'] == 0 ? '-' : number_format($row_pays['sss'],2)).'</td>
                                        <td style="border:1px solid black;">'.($row_pays['pagibig'] == 0 ? '-' : number_format($row_pays['pagibig'],2)).'</td>
                                        <td style="border:1px solid black;">'.($row_pays['philhealth'] == 0 ? '-' : number_format($row_pays['philhealth'],2)).'</td>
                                        <td style="border:1px solid black;">'.($row_pays['esf'] == 0 ? '-' : number_format($row_pays['esf'],2)).'</td>
                                        <td style="border:1px solid black;">'.($row_pays['ar'] == 0 ? '-' : number_format($row_pays['ar'],2)).'</td>
                                        <td style="border:1px solid black;">'.($row_pays['shortages'] == 0 ? '-' : number_format($row_pays['shortages'],2)).'</td>
                                        <td style="border:1px solid black;">'.($row_pays['salary_loan'] == 0 ? '-' : number_format($row_pays['salary_loan'],2)).'</td>
                                    </tr>';
                            }while($row_pays = $dan_pays->fetch_assoc());
                        $html .= '</tbody>
                        <tfoot>
                            <tr>
                                <th  style="color:red; border:1px solid black; text-align:right; padding:5px;">Total:</th>
                                <th  style="color:red; border:1px solid black;">'.number_format($sss,2).'</th>
                                <th  style="color:red; border:1px solid black;">'.number_format($pagibig,2).'</th>
                                <th  style="color:red; border:1px solid black;">'.number_format($ph,2).'</th>
                                <th  style="color:red; border:1px solid black;">'.number_format($esf,2).'</th>
                                <th  style="color:red; border:1px solid black;">'.number_format($ar,2).'</th>
                                <th  style="color:red; border:1px solid black;">'.number_format($shortages,2).'</th>
                                <th  style="color:red; border:1px solid black;">'.number_format($salary_loan,2).'</th>
                            </tr>
                        </tfoot>
                    </table><p></p>';
            }elseif($payroll = 'End'){
                $html.='<table style="width:100%; text-align:center; border-collapse:collapse">
                        <thead>
                            <tr>
                                <th colspan="9" style="color:blue; text-align:left; border:none">'.$row_pay_history['department'].'</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr style="border:1px solid black;">
                                <th rowspan="2" style="border:1px solid black;">Employee`s Name</th>
                                <th colspan="4" style="border:1px solid black;">Loans</th>
                                <th colspan="4" style="border:1px solid black;">Others</th>
                            </tr>
                            <tr>
                                <th style="border:1px solid black;">SSS Loan</th>
                                <th style="border:1px solid black;">SSS Calamity</th>
                                <th style="border:1px solid black;">HDMF Loan</th>
                                <th style="border:1px solid black;">HDMF Calamity</th>
                                <th style="border:1px solid black;">ESF</th>
                                <th style="border:1px solid black;">A/R</th>
                                <th style="border:1px solid black;">Shortages</th>
                                <th style="border:1px solid black;">Salary Loan</th>
                            </tr>
                        </thead>
                        <tbody>';
                                $no = 0;
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
                                    $no++;
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
                            $html.='<tr>
                                    <td style="border:1px solid black; text-align:left; padding:5px; width:270px">'.$no.'. '.$row_pays['emp_name'].'</td>
                                    <td style="border:1px solid black;">'.($row_pays['sss_loan'] == 0 ? '-' : number_format($row_pays['sss_loan'],2)).'</td>
                                    <td style="border:1px solid black;">'.($row_pays['sss_calamity'] == 0 ? '-' : number_format($row_pays['sss_calamity'],2)).'</td>
                                    <td style="border:1px solid black;">'.($row_pays['hdmf_loan'] == 0 ? '-' : number_format($row_pays['hdmf_loan'],2)).'</td>
                                    <td style="border:1px solid black;">'.($row_pays['hdmf_calamity'] == 0 ? '-' : number_format($row_pays['hdmf_calamity'],2)).'</td>
                                    <td style="border:1px solid black;">'.($row_pays['esf'] == 0 ? '-' : number_format($row_pays['esf'],2)).'</td>
                                    <td style="border:1px solid black;">'.($row_pays['ar'] == 0 ? '-' : number_format($row_pays['ar'],2)).'</td>
                                    <td style="border:1px solid black;">'.($row_pays['shortages'] == 0 ? '-' : number_format($row_pays['shortages'],2)).'</td>
                                    <td style="border:1px solid black;">'.($row_pays['salary_loan'] == 0 ? '-' : number_format($row_pays['salary_loan'],2)).'</td>
                            </tr>';
                            }while($row_pays = $dan_pays->fetch_assoc());
                        $html .= '</tbody>
                        <tfoot>
                            <tr>
                                <th style="color:red; border:1px solid black; text-align:right; padding:5px;">Total:</th>
                                <th style="color:red; border:1px solid black;">'.number_format($sss_loan,2).'</th>
                                <th style="color:red; border:1px solid black;">'.number_format($sss_calamity,2).'</th>
                                <th style="color:red; border:1px solid black;">'.number_format($hdmf_loan,2).'</th>
                                <th style="color:red; border:1px solid black;">'.number_format($hdmf_calamity,2).'</th>
                                <th style="color:red; border:1px solid black;">'.number_format($esf,2).'</th>
                                <th style="color:red; border:1px solid black;">'.number_format($ar,2).'</th>
                                <th style="color:red; border:1px solid black;">'.number_format($shortages,2).'</th>
                                <th style="color:red; border:1px solid black;">'.number_format($salary_loan,2).'</th>
                            </tr>
                        </tfoot>
                    </table><p></p>';
            }
        }

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
    <span style="font-size:16px"> EMPLOYEES DEDUCTIONS </span>
    <br>
    <span style="font-size:14px">Period Covered: '.date('M d, Y', strtotime($from)).' - '.date('M d, Y', strtotime($to)).' </span>
</div>');

$mpdf->AddPageByArray([
 'margin-left' => 3,
 'margin-right' => 3,
 'margin-top' => 47,
//  'margin-bottom' => 30,
 ]);

$mpdf->SetDisplayMode('fullpage');
//    $mpdf->SetWatermarkImage('img/logo_2.jpg');
//    $mpdf->showWatermarkImage = true;
 // $mpdf->SetDefaultBodyCSS('background', "url('img/logo.jpg')");
//    $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
 // $mpdf->SetHTMLHeader('<div><img src="img/COH.jpg"/></div>');
 $mpdf->setFooter('Printed by: '.$username.' | Page {PAGENO} of {nbpg} | Date & Time Printed: {DATE M j, Y h:i:s A}');
 $mpdf->WriteHTML( $html );
//    $mpdf->Output( 'Payroll('.date('mdY', strtotime($_SESSION['dtr_From'])).'-'.date('mdY', strtotime($_SESSION['dtr_To'])).').pdf', 'D' ); // Direct download your project directory
$mpdf->Output();
exit;