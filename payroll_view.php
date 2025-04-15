<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");
$y_now = date("Y");
$m_now = date("m");
$d_now = date("j");

if(isset($_GET['transaction_id']) && isset($_GET['department_id'])){
    $transaction_id = $_GET['transaction_id'];
    $department_id = $_GET['department_id'];
    // $dept = $_GET['department_name'];
    $from = $_GET['froms'];
    $to = $_GET['tos'];
    $payroll = $_GET['payroll'];
    $status = $_GET['status'];
    $payroll_uniqid = $_GET['payroll_uniqid'];

    $_SESSION['dtr_From'] = trim($_GET['froms']);
    $_SESSION['dtr_To'] = trim($_GET['tos']);
    $_SESSION['Payroll'] = trim($_GET['payroll']);
}else{
    echo header("Location: employees.php");
}

//  payroll history
$sql_pay_h = "SELECT * FROM payrolls WHERE dept = '$department_id' && transaction_id = '$transaction_id' ORDER BY nos asc";
$dan_pay_h = $con->query($sql_pay_h) or die ($con->error);
$row_pay_h = $dan_pay_h->fetch_assoc();
$count = mysqli_num_rows($dan_pay_h);

$sql_pay = "SELECT * FROM payroll_history WHERE dept_id = '$department_id' && transaction_id = '$transaction_id' && froms = '$from' && tos = '$to'";
$dan_pay = $con->query($sql_pay) or die ($con->error);
$row_pay = $dan_pay->fetch_assoc();
$count_pay = mysqli_num_rows($dan_pay);

$payroll_uniqid = $row_pay_h['payroll_id'];

$_SESSION['Payroll_Uniqid'] = $payroll_uniqid;

if($count_pay > 0){
    $payroll_cut = $row_pay['cut_off'];
}else{
    $payroll_cut = '';
}

// departments
$sql_dept = "SELECT * FROM departments WHERE ID = '$department_id'";
$dan_dept = $con->query($sql_dept) or die ($con->error);
$row_dept = $dan_dept->fetch_assoc();

$dept = $row_dept['dept'];
$_SESSION['departmentsss'] = $dept;


// header
include("includes/header.php");
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h4>Payroll for the Period of <?= date('M. d, Y', strtotime($from)).' - '.date('M. d, Y', strtotime($to))?></h4>
      </div>

        <div class="container table-responsive">
            <div class="form-row">
                    <div class="form-group col-md-4">
                        <span>Department: <b><?= $dept ?></b></span> 
                    </div>
                    <div class="form-group col-md-8 text-right">
                        <?php
                            if($status == 'ACTIVE'){
                        ?>
                            <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="manage_deductions.php?Department=<?= $dept?>" class="btn btn-primary btn-sm dropdown-item">Manage Deductions & Loans</a></li>
                                <li><a href="daily_time_records.php?Department=<?= $dept?>" class="btn btn-info btn-sm dropdown-item">Daily Time Records</a></li>
                                <!-- <li><a href="employees_allowance.php?ID=<?= $payroll_uniqid?>&from=<?= $from?>&to=<?=$to?>&Department=<?= $dept_name?>" class="btn-sm dropdown-item">Employee's Allowance</a></li> -->
                            </ul>
                        <?php } ?>
                            <button class="btn btn-success dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Export
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="payroll_payslip_pdf.php?payroll_id=<?= $payroll_uniqid?>&department=<?= $department_id?>" class="dropdown-item btn-sm" target="_blank">Print Payslip</a></li>
                                <li><button onclick="exportTableToExcel('payroll_view_print', '<?= $dept.'_'.date('m/d/Y', strtotime($from)).' - '.date('m/d/Y', strtotime($to))?>')" class="dropdown-item btn-sm" type="button">Export To Excel File</button></li>
                                <li><a href="payroll_allowances_pdf.php?payroll_id=<?= $payroll_uniqid?>&department=<?= $dept?>" class="dropdown-item btn-sm" target="_blank">Print Allowance</a></li>
                            </ul>
                            <a href="payroll_summary_create.php?payroll_uniqid=<?= $payroll_uniqid?>" class="btn btn-secondary btn-sm">Back</a>
                </div>  
            </div>

            <div class="" id="payrolls">
                <table id="payroll_table" class="dataTable table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Name of Employee</th>
                            <th colspan="2">Regular Work Days</th>
                            <th colspan="2">O.T., Holidays & Off w/ Work</th>
                            <th rowspan="2">Total Gross</th>
                            <th colspan="<?= $payroll_cut == 'Start' ? '8' : '9' ?>">Deductions</th>
                            <th rowspan="2">Net Pay</th>
                        </tr>
                        <tr>
                            <th class="ths">Hrs.</th>
                            <th class="ths">Pay</th>
                            <th class="ths">Hrs.</th>
                            <th class="ths">Pay</th>
                            <?php
                                if($payroll_cut == 'Start'){
                            ?>
                                <th class="ths">SSS</th>
                                <th class="ths">HDMF</th>
                                <th class="ths">Philhealth</th>
                            <?php
                                }else{
                            ?>
                                <th class="ths">SSS L</th>
                                <th class="ths">SSS C</th>
                                <th class="ths">HDMF L</th>
                                <th class="ths">HDMF C</th>
                            <?php
                                }
                            ?>
                            <th class="ths">Salary Loan</th>
                            <th class="ths">ESF</th>
                            <th class="ths">A/R</th>
                            <th class="ths">Shortages</th>
                            <th class="ths">Total Ded.</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
                        $total_gross = 0;
                        $total_deductions = 0;
                        $total_netpay = 0;

                        if($count < 1){
                            $pp = 0;
                            $prev = 0;
                            $next = 0;
                            ?>
                                <tr>
                                    <td colspan="">No Records Found!</td>
                                </tr>
                            <?php
                        }else{
                            $no = 0;
                            do{
                                $no++;

                                    ?>
                                    <tr>
                                        <td><?= $no?></td>
                                        <td style="text-align: left;"><b><?= $row_pay_h['emp_name'] ?></b></td>
                                        <td><?= $row_pay_h['reg_hrs'] ?></td>
                                        <td><?= $row_pay_h['reg_pay'] ?></td>
                                        <td><?= $row_pay_h['add_hrs'] ?></td>
                                        <td><?= $row_pay_h['add_pay'] ?></td>
                                        <td><?= $row_pay_h['gross'] ?></td>
                                        <?php
                                            if($payroll_cut == 'Start'){
                                        ?>
                                        <td><?= $row_pay_h['sss'] ?></td>
                                        <td><?= $row_pay_h['pagibig'] ?></td>
                                        <td><?= $row_pay_h['philhealth'] ?></td>
                                        <?php
                                            }else{
                                        ?>
                                        <td><?= $row_pay_h['sss_loan'] ?></td>
                                        <td><?= $row_pay_h['sss_calamity'] ?></td>
                                        <td><?= $row_pay_h['hdmf_loan'] ?></td>
                                        <td><?= $row_pay_h['hdmf_calamity'] ?></td>
                                        <?php } ?>
                                        <td><?= $row_pay_h['salary_loan'] ?></td>
                                        <td><?= $row_pay_h['esf'] ?></td>
                                        <td><?= $row_pay_h['ar'] ?></td>
                                        <td><?= $row_pay_h['shortages'] ?></td>
                                        <td><?= $row_pay_h['deductions'] ?></td>
                                        <td><b><?= $row_pay_h['net_pay'] ?></b></td>
                                    </tr>
                                <?php

                                $gg = str_replace( ',', '', $row_pay_h['gross'] );
                                $dd = str_replace( ',', '', $row_pay_h['deductions'] );
                                $nn = str_replace( ',', '', $row_pay_h['net_pay'] );

                                $total_gross = $total_gross + floatval($gg);
                                $total_deductions = $total_deductions + floatval($dd);
                                $total_netpay = $total_netpay + floatval($nn);
                            }while($row_pay_h = $dan_pay_h->fetch_assoc());
                        }
                    ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th colspan="2"></th>
                            <th colspan="">Grandtotal</th>
                            <th><?= number_format($total_gross,2)?></th>
                            <th colspan="<?= $payroll_cut == 'Start' ? '7' : '8'?>"></th>
                            <th><?= number_format($total_deductions,2)?></th>
                            <th><?= number_format($total_netpay,2)?></th>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
</div>

<?php
//  payroll history
$sql_pay_h = "SELECT * FROM payrolls WHERE dept = '$department_id' && transaction_id = '$transaction_id' ORDER BY nos asc";
$dan_pay_h = $con->query($sql_pay_h) or die ($con->error);
$row_pay_h = $dan_pay_h->fetch_assoc();
$count = mysqli_num_rows($dan_pay_h);

$sql_pay = "SELECT * FROM payroll_history WHERE dept_id = '$department_id' && transaction_id = '$transaction_id' && froms = '$from' && tos = '$to'";
$dan_pay = $con->query($sql_pay) or die ($con->error);
$row_pay = $dan_pay->fetch_assoc();
$count_pay = mysqli_num_rows($dan_pay);


if($count_pay > 0){
    $payroll_cut = $row_pay['cut_off'];
}else{
    $payroll_cut = '';
}

// departments
$sql_dept = "SELECT * FROM departments WHERE ID = '$department_id'";
$dan_dept = $con->query($sql_dept) or die ($con->error);
$row_dept = $dan_dept->fetch_assoc();

$dept = $row_dept['dept'];

?>
<!-- Print -->
<div class="" id="payroll_view_print" style="display: none;">
                <strong style="color: green; font-size:20px">COH ENTERPRISES, INC.</strong>
                <br>
                <strong style="color: blue; font-size:17px">Department: <?= $dept ?></strong>
                <br>
                <strong>Payroll for the Period of  
                        <?php echo date('M. d, Y', strtotime($from)).' - '.date('M. d, Y', strtotime($to))?>
                </strong>
                <table id="" class="" style="width:100%">
                    <thead>
                        <tr>
                            <th style="border: 1px solid black" rowspan="2">No.</th>
                            <th style="border: 1px solid black" rowspan="2">Name of Employee</th>
                            <th style="border: 1px solid black" colspan="2">Regular Work Days</th>
                            <th style="border: 1px solid black" colspan="2">O.T., Holidays & Off w/ Work</th>
                            <th style="border: 1px solid black" rowspan="2">Total Gross</th>
                            <th style="border: 1px solid black" colspan="<?= $payroll_cut == 'Start' ? '8' : '9' ?>">Deductions</th>
                            <th style="border: 1px solid black" rowspan="2">Net Pay</th>
                            <th style="border: 1px solid black; width:200px; text-align:center" rowspan="2">Signature</th>
                        </tr>
                        <tr>
                            <th style="border: 1px solid black" class="ths">Hrs.</th>
                            <th style="border: 1px solid black" class="ths">Pay</th>
                            <th style="border: 1px solid black" class="ths">Hrs.</th>
                            <th style="border: 1px solid black" class="ths">Pay</th>
                            <?php
                                if($payroll_cut == 'Start'){
                            ?>
                                <th style="border: 1px solid black" class="ths">SSS</th>
                                <th style="border: 1px solid black" class="ths">HDMF</th>
                                <th style="border: 1px solid black" class="ths">Philhealth</th>
                            <?php
                                }else{
                            ?>
                                <th style="border: 1px solid black" class="ths">SSS L</th>
                                <th style="border: 1px solid black" class="ths">SSS C</th>
                                <th style="border: 1px solid black" class="ths">HDMF L</th>
                                <th style="border: 1px solid black" class="ths">HDMF C</th>
                            <?php
                                }
                            ?>
                            <th style="border: 1px solid black" class="ths">Salary Loan</th>
                            <th style="border: 1px solid black" class="ths">ESF</th>
                            <th style="border: 1px solid black" class="ths">A/R</th>
                            <th style="border: 1px solid black" class="ths">Shortages</th>
                            <th style="border: 1px solid black" class="ths">Total Ded.</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
                        $total_gross = 0;
                        $total_deductions = 0;
                        $total_netpay = 0;

                        if($count < 1){
                            $pp = 0;
                            $prev = 0;
                            $next = 0;
                            ?>
                                <tr>
                                    <td colspan="">No Records Found!</td>
                                </tr>
                            <?php
                        }else{
                            $no = 0;
                            $sss = 0;
                            $pagibig = 0;
                            $philhealth = 0;
                            $sss_loan = 0;
                            $sss_calamity = 0;
                            $hdmf_loan = 0;
                            $hdmf_calamity = 0;
                            $salary_loan = 0;
                            $esf = 0;
                            $ar = 0;
                            $shortages = 0;
                            do{
                                $no++;

                                    ?>
                                    <tr>
                                        <td style="border: 1px solid black"><?= $no?></td>
                                        <td style="border: 1px solid black" style="text-align: left;"><b><?= $row_pay_h['emp_name'] ?></b></td>
                                        <td style="border: 1px solid black"><?= $row_pay_h['reg_hrs'] ?></td>
                                        <td style="border: 1px solid black"><?= $row_pay_h['reg_pay'] ?></td>
                                        <td style="border: 1px solid black"><?= $row_pay_h['add_hrs'] ?></td>
                                        <td style="border: 1px solid black"><?= $row_pay_h['add_pay'] ?></td>
                                        <td style="border: 1px solid black"><?= $row_pay_h['gross'] ?></td>
                                        <?php
                                            if($payroll_cut == 'Start'){
                                        ?>
                                        <td style="border: 1px solid black"><?= $row_pay_h['sss'] ?></td>
                                        <td style="border: 1px solid black"><?= $row_pay_h['pagibig'] ?></td>
                                        <td style="border: 1px solid black"><?= $row_pay_h['philhealth'] ?></td>
                                        <?php
                                            $sss_a = str_replace(',', '', $row_pay_h['sss']);
                                            $pagibig_a = str_replace(',', '', $row_pay_h['pagibig']);
                                            $philhealth_a = str_replace(',', '', $row_pay_h['philhealth']);

                                            $sss = $sss + floatval($sss_a);
                                            $pagibig = $pagibig + floatval($pagibig_a);
                                            $philhealth = $philhealth + floatval($philhealth_a);
                                            }else{
                                        ?>
                                        <td style="border: 1px solid black"><?= $row_pay_h['sss_loan'] ?></td>
                                        <td style="border: 1px solid black"><?= $row_pay_h['sss_calamity'] ?></td>
                                        <td style="border: 1px solid black"><?= $row_pay_h['hdmf_loan'] ?></td>
                                        <td style="border: 1px solid black"><?= $row_pay_h['hdmf_calamity'] ?></td>
                                        <?php 
                                            $sss_loan_a = str_replace(',', '', $row_pay_h['sss_loan']);
                                            $sss_calamity_a = str_replace(',', '', $row_pay_h['sss_calamity']);
                                            $hdmf_loan_a = str_replace(',', '', $row_pay_h['hdmf_loan']);
                                            $hdmf_calamity_a = str_replace(',', '', $row_pay_h['hdmf_calamity']);

                                            $sss_loan = $sss_loan + floatval($sss_loan_a);
                                            $sss_calamity = $sss_calamity + floatval($sss_calamity_a);
                                            $hdmf_loan = $hdmf_loan + floatval($hdmf_loan_a);
                                            $hdmf_calamity = $hdmf_calamity + floatval($hdmf_calamity_a);
                                            }   
                                        ?>
                                        <td style="border: 1px solid black"><?= $row_pay_h['salary_loan'] ?></td>
                                        <td style="border: 1px solid black"><?= $row_pay_h['esf'] ?></td>
                                        <td style="border: 1px solid black"><?= $row_pay_h['ar'] ?></td>
                                        <td style="border: 1px solid black"><?= $row_pay_h['shortages'] ?></td>
                                        <td style="border: 1px solid black"><?= $row_pay_h['deductions'] ?></td>
                                        <td style="border: 1px solid black; color: red; font-size:15px"><b><?= $row_pay_h['net_pay'] ?></b></td>
                                        <td style="border: 1px solid black"></td>
                                    </tr>
                                <?php
                                $salary_loan_a = str_replace(',', '', $row_pay_h['salary_loan']);
                                $esf_a = str_replace(',', '', $row_pay_h['esf']);
                                $ar_a = str_replace(',', '', $row_pay_h['ar']);
                                $shortages_a = str_replace(',', '', $row_pay_h['shortages']);

                                $salary_loan = $salary_loan + floatval($salary_loan_a);
                                $esf = $esf + floatval($esf_a);
                                $ar = $ar + floatval($ar_a);
                                $shortages = $shortages + floatval($shortages_a);

                                $gg = str_replace( ',', '', $row_pay_h['gross'] );
                                $dd = str_replace( ',', '', $row_pay_h['deductions'] );
                                $nn = str_replace( ',', '', $row_pay_h['net_pay'] );

                                $total_gross = $total_gross + floatval($gg);
                                $total_deductions = $total_deductions + floatval($dd);
                                $total_netpay = $total_netpay + floatval($nn);
                            }while($row_pay_h = $dan_pay_h->fetch_assoc());
                            $pp = ceil($no / 10);

                            $prev = $page - 1;
                            $next = $page + 1;
                        }
                    ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th style="border: 1px solid black"></th>
                            <th style="border: 1px solid black"></th>
                            <th style="border: 1px solid black; color: red; font-size:15px; text-align:center" colspan="4">Grandtotal</th>
                            <th style="border: 1px solid black; color: red; font-size:15px"><?= number_format($total_gross,2)?></th>
                            <?php
                                if($payroll_cut == 'Start'){
                                    ?>
                                        <th style="border: 1px solid black;"><?= number_format($sss,2)?></th>
                                        <th style="border: 1px solid black;"><?= number_format($pagibig,2)?></th>
                                        <th style="border: 1px solid black;"><?= number_format($philhealth,2)?></th>
                                    <?php
                                }else{
                                    ?>
                                        <th style="border: 1px solid black;"><?= number_format($sss_loan,2)?></th>
                                        <th style="border: 1px solid black;"><?= number_format($sss_calamity,2)?></th>
                                        <th style="border: 1px solid black;"><?= number_format($hdmf_loan,2)?></th>
                                        <th style="border: 1px solid black;"><?= number_format($hdmf_calamity,2)?></th>
                                    <?php
                                }
                            ?>
                            <th style="border: 1px solid black;"><?= number_format($salary_loan,2)?></th>
                            <th style="border: 1px solid black;"><?= number_format($esf,2)?></th>
                            <th style="border: 1px solid black;"><?= number_format($ar,2)?></th>
                            <th style="border: 1px solid black;"><?= number_format($shortages,2)?></th>
                            <th style="border: 1px solid black; color: red; font-size:15px"><?= number_format($total_deductions,2)?></th>
                            <th style="border: 1px solid black; color: red; font-size:15px"><?= number_format($total_netpay,2)?></th>
                            <th style="border: 1px solid black"></th>
                        </tr>
                    </tfoot>

                </table>

            </div>        
<!--  -->
</div>
    </div>
</main>

<?php
include("includes/footer.php");
?>

<script>
    let table = new DataTable('#payroll_table',{
        fixedColumns: {
        left: 2,
        right: 2
        },
        // scrollCollapse: true,
        scrollX: true
        // scrollY: 500
    });
</script>