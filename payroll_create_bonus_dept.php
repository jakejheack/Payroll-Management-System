<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");

$now_update = date("m/d/y-h:i a");

if(isset($_POST['create_month'])){
    $from = $_POST['froms'];
    $to = $_POST['tos'];
    $dept = $_POST['dept_bonus'];
    $dept = str_replace("'", "\'", $dept);
    $pay_uniqid = $_POST['pay_uniqid'];

}elseif(isset($_GET['pay_uniqid'])){
    $from = $_GET['froms'];
    $to = $_GET['tos'];
    $dept = $_GET['department_name'];
    $dept = str_replace("'", "\'", $dept);
    $pay_uniqid = $_GET['pay_uniqid'];

}else{
    header("Location: payroll_summary.php");
}

// employees info
if(empty($dept)){
    $sql_emp = "SELECT * FROM employees order by lname,fname,mname ASC";
}else{
    $sql_emp = "SELECT * FROM employees WHERE payroll_dept = '$dept' order by lname,fname,mname ASC";
}
$dan_emp = $con->query($sql_emp) or die ($con->error);
$row_emp = $dan_emp->fetch_assoc();
$count_emp = mysqli_num_rows($dan_emp);

// departments
$sql_dept = "SELECT * FROM departments WHERE dept = '$dept'";
$dan_dept = $con->query($sql_dept) or die ($con->error);
$row_dept = $dan_dept->fetch_assoc();
$count_dept = mysqli_num_rows($dan_dept);

if($count_dept > 0){
    $dept_id = $row_dept['ID'];
    $dept_name = $row_dept['dept'];
    $_SESSION['departmentsss'] = $dept_name;
}

// payroll_bonus_history
$sql_pay_history = "SELECT * FROM payroll_bonus_history WHERE payroll_id = '$pay_uniqid' && dept_id = '$dept_id'";
$dan_pay_history = $con->query($sql_pay_history) or die ($con->error);
$row_pay_history = $dan_pay_history->fetch_assoc();
$count_pay_history = mysqli_num_rows($dan_pay_history);

// header
include("includes/header.php");

?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4>13th Month Pay (Period Covered:  
            <?= date('F Y', strtotime($from)).' - '.date('F Y', strtotime($to)); ?>
            )
        </h4>
      </div>

        <div class="container table-responsive">

        <div class="form-row">
                    <div class="form-group col-md-4">
                        <span>Department: <b><?= $dept_name ?></b></span> 
                    </div>
                    <div class="form-group col-md-8 text-right">
                        <!-- <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button> -->
                        <!-- <ul class="dropdown-menu"> -->
                            <!-- <li><a href="manage_deductions.php?Department=<?= $dept_name?>" class="btn btn-primary btn-sm dropdown-item">Manage Deductions & Loans</a></li> -->
                            <!-- <li><a href="daily_time_records.php?Department=<?= $dept_name?>" class="btn btn-info btn-sm dropdown-item">Daily Time Records</a></li> -->
                        <!-- </ul> -->
                        <button class="btn btn-success dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="print_all_payslip.php" class="dropdown-item btn-sm disabled" target="_blank">Print Payslip</a></li>
                            <li><button onclick="exportTableToExcel('bonus_table', '13th Month Pay')" class="dropdown-item btn-sm" type="button">Excel File</button></li>
                        </ul>
                        <a href="payroll_create_bonus.php?ID=<?= $pay_uniqid?>" class="btn btn-secondary btn-sm">Back</a>
                    </div>
            </div>

            <div>
                <table id="bonus_table" class="dataTable table table-striped table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Name of Employee</th>
                            <?php
                                $dd = $from;
                                do{
                                    ?>
                                        <th colspan="2"><?= date('F', strtotime($dd))?></th>
                                    <?php
                                    $dd = date('m/d/Y', strtotime($dd . " +1 month"));
                                }while(strtotime($dd) <= strtotime($to))
                            ?>
                            <th rowspan="2">Total</th>
                            <th rowspan="2">13th Month Pay</th>
                        </tr>
                        <tr>
                            <?php
                                $dd = $from;
                                do{
                                    ?>
                                        <th>Start</th>
                                        <th>End</th>
                                    <?php
                                    $dd = date('m/d/Y', strtotime($dd . " +1 month"));
                                }while(strtotime($dd) <= strtotime($to))
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 0;
                            $total_netpay = 0;
                            $total_bonus = 0;
                            do{
                                $no++;
                                $total = 0;
                                $bonus = 0;
                                ?>
                                    <tr>
                                        <th><?= $no?></th>
                                        <th style="text-align: left;"><?= ucwords($row_emp['lname'].', '.$row_emp['fname'].' '.substr($row_emp['mname'],0,1).' '.$row_emp['extname'])?></th>
                                        <?php
                                            $emp_id = $row_emp['ID'];
                                            $starts_pay = [];
                                            $ends_pay = [];
                                            for($dd = $from; strtotime($dd) <= strtotime($to); $dd = date('m/d/Y', strtotime($dd . " +1 month"))){
                                                // payrolls that posted
                                                $month = date('F', strtotime($dd));
                                                $sql_payrolls_start = "SELECT * FROM payrolls WHERE pay_month = '$month' && posting = 'POSTED' && cut_off = 'Start' && emp_id = '$emp_id'";
                                                $dan_payrolls_start = $con->query($sql_payrolls_start) or die ($con->error);
                                                $row_payrolls_start = $dan_payrolls_start->fetch_assoc();
                                                $count_payrolls_start = mysqli_num_rows($dan_payrolls_start);

                                                $sql_payrolls_end = "SELECT * FROM payrolls WHERE pay_month = '$month' && posting = 'POSTED' && cut_off = 'End' && emp_id = '$emp_id'";
                                                $dan_payrolls_end = $con->query($sql_payrolls_end) or die ($con->error);
                                                $row_payrolls_end = $dan_payrolls_end->fetch_assoc();
                                                $count_payrolls_end = mysqli_num_rows($dan_payrolls_end);

                                                if($count_payrolls_start > 0){
                                                    $start_netpay = $row_payrolls_start['net_pay'];
                                                }else{
                                                    $start_netpay = 0;
                                                }

                                                if($count_payrolls_end > 0){
                                                    $end_netpay = $row_payrolls_end['net_pay'];
                                                }else{
                                                    $end_netpay = 0;
                                                }

                                                $starts_pay[] = $start_netpay;
                                                $ends_pay[] = $end_netpay;

                                                echo '<td>'.$start_netpay.'</td>';
                                                echo '<td>'.$end_netpay.'</td>';

                                                $start_netpay = str_replace( ',', '', $start_netpay);
                                                $end_netpay = str_replace( ',', '', $end_netpay);

                                                $total = $total + floatval($start_netpay) + floatval($end_netpay);
                                            }
                                            $bonus = $total / 12;

                                            $total_netpay = $total_netpay + $total;
                                            $total_bonus = $total_bonus + $bonus;
                                        ?>
                                        <th><?= number_format($total,2)?></th>
                                        <th><?= number_format($bonus,2)?></th>
                                    </tr>
                                <?php

                                $emp_name = ucwords($row_emp['lname'].', '.$row_emp['fname'].' '.substr($row_emp['mname'],0,1).' '.$row_emp['extname']);

                                $payroll_h = "SELECT * FROM payroll_bonus WHERE emp_id = '$emp_id' && payroll_id = '$pay_uniqid'";
                                $dan_payroll_h = $con->query($payroll_h) or die ($con->error);
                                $row_payroll_h = $dan_payroll_h->fetch_assoc();
                                $count_payroll_h = mysqli_num_rows($dan_payroll_h);

                                $bonus = round($bonus,2);

                                if($count_payroll_h > 0)
                                {
                                    $pay_hID = $row_payroll_h['ID'];

                                    $sql_add_payroll = "UPDATE `payroll_bonus` SET `emp_id`='$emp_id',`emp_name`='$emp_name',
                                            `m_1start` = '$starts_pay[0]', `m_1end` = '$ends_pay[0]',
                                            `m_2start` = '$starts_pay[1]', `m_2end` = '$ends_pay[1]',
                                            `m_3start` = '$starts_pay[2]', `m_3end` = '$ends_pay[2]',
                                            `m_4start` = '$starts_pay[3]', `m_4end` = '$ends_pay[3]',
                                            `m_5start` = '$starts_pay[4]', `m_5end` = '$ends_pay[4]',
                                            `m_6start` = '$starts_pay[5]', `m_6end` = '$ends_pay[5]',
                                            `total_payroll` = '$total',`net_pay`='$bonus', 
                                            `dept`='$dept_id',`nos`='$no', `posting`='ACTIVE',
                                            `payroll_id` = '$pay_uniqid' WHERE `ID` = '$pay_hID'";

                                }else{

                                    $sql_add_payroll = "INSERT INTO `payroll_bonus`(`ID`, `emp_id`, `emp_name`, `m_1start`, `m_1end`, `m_2start`, `m_2end`, 
                                            `m_3start`, `m_3end`, `m_4start`, `m_4end`, `m_5start`, `m_5end`, `m_6start`, `m_6end`, `total_payroll`, 
                                            `net_pay`, `transaction_id`, `dept`, `nos`, `posting`, `payroll_id`) 
                                            VALUES (NULL,'$emp_id','$emp_name',
                                            '$starts_pay[0]','$ends_pay[0]',
                                            '$starts_pay[1]','$ends_pay[1]',
                                            '$starts_pay[2]','$ends_pay[2]',
                                            '$starts_pay[3]','$ends_pay[3]',
                                            '$starts_pay[4]','$ends_pay[4]',
                                            '$starts_pay[5]','$ends_pay[5]',
                                            '$total','$bonus','','$dept_id', 
                                            '$no', 'ACTIVE', '$pay_uniqid')";
                                }

                                $s_pay = ($con->query($sql_add_payroll) or die ($con->error));

                            }while($row_emp = $dan_emp->fetch_assoc())
                        ?>
                    </tbody>
                </table>
    <!--  -->
    </div>
        </div>
    </main>

    <?php
    $total_netpay = round($total_netpay,2);
    $total_bonus = round($total_bonus,2);

                        if($count_pay_history > 0)
                        {
                            $pay_ID = $row_pay_history['ID'];
                            $pay_netpay = $row_pay_history['netpay'];
    
                                $sql_add_history = "UPDATE `payroll_bonus_history` SET `total_netpay`='$total_netpay',
                                `netpay` = '$total_bonus', 
                                `updated_by`='$username',`date_updated`='$now_update' WHERE `ID` = '$pay_ID'";
    
                                $hh_pay = ($con->query($sql_add_history) or die ($con->error));
    
                        }else{
    
                            $from = date('F Y', strtotime($from));
                            $to = date('F Y', strtotime($to));
                            $dept_name = str_replace("'", "\'", $dept_name);
    
                            $sql_add_history = "INSERT INTO `payroll_bonus_history`(`ID`, `transaction_id`, `total_netpay`, `netpay`, 
                            `department`, `froms`, `tos`, `created_by`, `date_created`, `updated_by`, `date_updated`, `status`, 
                            `dept_id`, `payroll_id`) 
                            VALUES (NULL,'','$total_netpay','$total_bonus',
                            '$dept_name','$from','$to','$username','$now_update','','','ACTIVE','$dept_id','$pay_uniqid')";
    
                            $hh_pay = ($con->query($sql_add_history) or die ($con->error));
                        }

    include("includes/footer.php");
    ?>

<script>
    let table = new DataTable('#bonus_table',{
        fixedColumns: {
        left: 2,
        right: 2
        },
        scrollCollapse: true,
        scrollX: true,
        scrollY: 500
    });
</script>