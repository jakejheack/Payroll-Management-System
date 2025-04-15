<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_POST['create_month'])){
    $month_from = $_POST['month_from'];
    $year_from = $_POST['year_from'];
    $month_to = $_POST['month_to'];
    $year_to = $_POST['year_to'];

    $_SESSION['Bonus_Month_From'] = $month_from;
    $_SESSION['Bonus_Year_From'] = $year_from;
    $_SESSION['Bonus_Month_To'] = $month_to;
    $_SESSION['Bonus_Year_To'] = $year_to;

    $dept = $_POST['dept_bonus'];
    $dept = str_replace("'", "\'", $dept);

    $from = $month_from.'/1/'.$year_from;
    $to = $month_to.'/1/'.$year_to;
    $to = date('m/t/Y', strtotime($to));

    if(strtotime($from) > strtotime($to)){
        $_SESSION['check'] = 'Invalid date. Please check the date you enter.';
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }

}else{
    header("Location: payroll_summary.php");
}

// employees info
if(empty($dept)){
    $sql_emp = "SELECT * FROM employees order by lname,fname,mname ASC";
}else{
    $sql_emp = "SELECT * FROM employees WHERE dept = '$dept' order by lname,fname,mname ASC";
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


// header
include("includes/header.php");
include("includes/menus.php");


?>
<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style>

<link rel="stylesheet" href="css/datatables/fixedColumns.dataTables.min.css">

<div class="containers"> 
    <h3>13th Month Pay (Period Covered:  
        <?= date('F Y', strtotime($from)).' - '.date('F Y', strtotime($to)); ?>
        )
    </h3>
    <div class="contents">
            <span>Department: </span> 
            <b><?php echo $dept_name ?></b>

            <div class="pay_dtr">
                    <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <ul class="dropdown-menu">
                        <!-- <li><a href="manage_deductions.php?Department=<?= $dept_name?>" class="btn btn-primary btn-sm dropdown-item">Manage Deductions & Loans</a></li> -->
                        <!-- <li><a href="daily_time_records.php?Department=<?= $dept_name?>" class="btn btn-info btn-sm dropdown-item">Daily Time Records</a></li> -->
                    </ul>
                    <button class="btn btn-success dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="print_all_payslip.php" class="dropdown-item btn-sm disabled" target="_blank">Print Payslip</a></li>
                        <li><button onclick="exportTableToExcel('bonus_table', '13th Month Pay')" class="dropdown-item btn-sm" type="button">Excel File</button></li>
                    </ul>
                    <a href="payroll_summary.php" class="btn btn-secondary btn-sm">Back</a>
            </div>

                <p></p>
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
                                                    $start_netpay = '';
                                                }

                                                if($count_payrolls_end > 0){
                                                    $end_netpay = $row_payrolls_end['net_pay'];
                                                }else{
                                                    $end_netpay = '';
                                                }

                                                echo '<td>'.$start_netpay.'</td>';
                                                echo '<td>'.$end_netpay.'</td>';

                                                $start_netpay = str_replace( ',', '', $start_netpay);
                                                $end_netpay = str_replace( ',', '', $end_netpay);

                                                $total = $total + floatval($start_netpay) + floatval($end_netpay);
                                            }
                                            $bonus = $total / 12;
                                        ?>
                                        <th><?= number_format($total,2)?></th>
                                        <th><?= number_format($bonus,2)?></th>
                                    </tr>
                                <?php
                            }while($row_emp = $dan_emp->fetch_assoc())
                        ?>
                    </tbody>
                </table>
            </div>
    </div>
</div>

<script src="js/datatables/jquery.dataTables.min.js"></script>
<script src="js/datatables/dataTables.bootstrap4.min.js"></script>
<script src="js/datatables/dataTables.fixedColumns.min.js"></script>

<!-- modal -->
<script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

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