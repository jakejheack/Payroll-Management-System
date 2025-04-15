<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");

if(isset($_POST['create_month'])){
    $month_from = $_POST['month_from'];
    $year_from = $_POST['year_from'];
    $month_to = $_POST['month_to'];
    $year_to = $_POST['year_to'];

    $_SESSION['Bonus_Month_From'] = $month_from;
    $_SESSION['Bonus_Year_From'] = $year_from;
    $_SESSION['Bonus_Month_To'] = $month_to;
    $_SESSION['Bonus_Year_To'] = $year_to;

    // $dept = $_POST['dept_bonus'];
    // $dept = str_replace("'", "\'", $dept);

    $from = $month_from.'/1'.'/'.$year_from;
    $to = $month_to.'/1'.'/'.$year_to;
    $to = date('m/t/Y', strtotime($to));

    if(strtotime($from) > strtotime($to)){
        $_SESSION['check'] = 'Invalid date. Please check the date you enter.';
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }else{
        $pay_uniqid = uniqid().uniqid();

        if($year_from == $year_to){
            $pay_date = date('M', strtotime($from)).' - '.date('M Y', strtotime($to));
        }else{
            $pay_date = date('M Y', strtotime($from)).' - '.date('M Y', strtotime($to));
        }

        $created_by = $_SESSION['Usernames'];
        $date_time_created = date('m/d/y h:ia');
    
        //  payroll summary
        $sql_pay_summary = "SELECT * FROM payroll_summary WHERE pay_date = '$pay_date'";
        $dan_pay_summary = $con->query($sql_pay_summary) or die ($con->error);
        $row_pay_summary = $dan_pay_summary->fetch_assoc();
        $count_pay_summary = mysqli_num_rows($dan_pay_summary);
    
        if($count_pay_summary > 0){
            $id_pay = $row_pay_summary['ID'];
            $pay_uniqid = $row_pay_summary['payroll_id'];
        }else{
            $sql_add_pay = "INSERT INTO `payroll_summary`(`ID`, `pay_date`, `froms`, `tos`, `cut_off`, `payroll_id`, `created_by`, `date_time_created`, `updated_by`, `date_time_updated`,`status`) 
                            VALUES (NULL,'$pay_date','$from','$to','13th Month','$pay_uniqid','$created_by','$date_time_created','','','ACTIVE')";
            $_add_pay = ($con->query($sql_add_pay) or die ($con->error));
        }
    }

}elseif(isset($_GET['ID'])){
    $pay_uniqid = $_GET['ID'];

   //  payroll summary
   $sql_pay_summary = "SELECT * FROM payroll_summary WHERE payroll_id = '$pay_uniqid'";
   $dan_pay_summary = $con->query($sql_pay_summary) or die ($con->error);
   $row_pay_summary = $dan_pay_summary->fetch_assoc();
   $count_pay_summary = mysqli_num_rows($dan_pay_summary);

   if($count_pay_summary > 0){
       $pay_date = $row_pay_summary['pay_date'];
       $payroll = $row_pay_summary['cut_off'];
       $from = $row_pay_summary['froms'];
       $to = $row_pay_summary['tos'];
       $id_pay = $row_pay_summary['ID'];

       $created_by = $_SESSION['Usernames'];
       $date_time_created = date('m/d/y h:ia');

   }else{
       header("Location: payroll_summary.php");
   }
}else{
    header("Location: payroll_summary.php");
}

// departments
$sql_dept = "SELECT * FROM departments";
$dan_dept = $con->query($sql_dept) or die ($con->error);
$row_dept = $dan_dept->fetch_assoc();
$count_dept = mysqli_num_rows($dan_dept);

if($count_dept > 0){
    $dept_id = $row_dept['ID'];
    $dept_name = $row_dept['dept'];
    $_SESSION['departmentsss'] = $dept_name;
}

// payroll_history
$sql_pay_history = "SELECT * FROM payroll_bonus_history WHERE payroll_id = '$pay_uniqid' order by department asc";
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
            <!-- <span>Department: </span>  -->
            <!-- <b><?php echo $dept_name ?></b> -->

                <div class="btnAdd text-right">
                    <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-success btn-sm">+ Create 13th Month Pay per Department</a>
                    <a href="payroll_bonus_summary.php" class="btn btn-secondary btn-sm">Back</a>
                </div>
        <!-- Create Payroll Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Select Department</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <form action="payroll_create_bonus_dept.php" name="addData" method="post" autocomplete="off">
                <div class="input-group">
                        <span class="input-group-text">From:</span>
                        <select name="month_from" id="" class="form-control" required disabled>
                            <option value="" disabled selected hidden>Month</option>
                            <?php
                                if(isset($_SESSION['Bonus_Month_From'])){
                                    $m_from = $_SESSION['Bonus_Month_From'];
                                }else{
                                    $m_from = '';
                                }
                                for($i = 1; $i <=12; $i++){
                                    $dateObj   = DateTime::createFromFormat('!m', $i);
                                    $monthName = $dateObj->format('F');
                                    ?>
                                        <option value="<?= $i ?>" <?= ($i == $m_from ? 'selected' : '')?>><?= $monthName ?></option>
                                    <?php
                                }
                            ?>
                        </select>
                        <select name="year_from" id="" class="form-control" required disabled>
                            <option value="" disabled selected hidden>Year</option>
                            <?php
                                    for($yr_now = date('Y'); $yr_now >= 2023; $yr_now--){
                                        echo '<option value="'.$yr_now.'" '.($yr_now == date('Y') ? 'selected' : '').'>'.$yr_now.'</option>';
                                    }
                            ?>
                        </select>
                    </div>
                    <p></p>
                    <div class="input-group">
                        <span class="input-group-text">To:</span>
                        <select name="month_to" id="" class="form-control" required disabled>
                            <option value="" disabled selected hidden>Month</option>
                            <?php
                                if(isset($_SESSION['Bonus_Month_To'])){
                                    $m_to = $_SESSION['Bonus_Month_To'];
                                }else{
                                    $m_to = '';
                                }
                                for($i = 1; $i <=12; $i++){
                                    $dateObj   = DateTime::createFromFormat('!m', $i);
                                    $monthName = $dateObj->format('F');
                                    ?>
                                        <option value="<?= $i ?>" <?= ($i == $m_to ? 'selected' : '')?>><?= $monthName ?></option>
                                    <?php
                                }
                            ?>
                        </select>
                        <select name="year_to" id="" class="form-control" required disabled>
                            <option value="" disabled selected hidden>Year</option>
                            <?php
                                for($yr_now = date('Y'); $yr_now >= 2023; $yr_now--){
                                    echo '<option value="'.$yr_now.'" '.($yr_now == date('Y') ? 'selected' : '').'>'.$yr_now.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <p></p>
                    <div class="input-group">
                        <span class="input-group-text">Department:</span>
                        <select name="dept_bonus" class="form-control" required>
                            <option value="" disabled selected>Select Department</option>
                            <?php
                            // departments
                            $sql_dept2 = "SELECT * FROM departments ORDER BY dept ASC";
                            $dan_dept2 = $con->query($sql_dept2) or die ($con->error);
                            $row_dept2 = $dan_dept2->fetch_assoc();

                                do{
                                    ?>
                                        <option value="<?= $row_dept2['dept'] ?>"><?= $row_dept2['dept'] ?></option>
                                    <?php
                                }while($row_dept2 = $dan_dept2->fetch_assoc())
                            ?>
                        </select>
                    </div>
                    <p></p>
                    <div class="text-center">
                        <input type="hidden" name="cut_off" value="<?= $payroll?>">
                        <input type="hidden" name="froms" value="<?= $from?>">
                        <input type="hidden" name="tos" value="<?= $to?>">
                        <input type="hidden" name="pay_uniqid" value="<?= $pay_uniqid?>">
                        <input type="submit" class="btn btn-primary" value="Create" name="create_month">
                    </div>
                </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>


                <p></p>
            <div>
                <table id="bonus_table" class="dataTable table table-striped table-bordered table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Department</th>
                                    <th>Net Pay</th>
                                    <th>Created by</th>
                                    <th>Date & Time Created</th>
                                    <th>Updated By</th>
                                    <th>Date & Time Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 0;
                                    $net_pay = 0;
                                    $gross = 0;
                                    $deductions = 0;
                                    if($count_pay_history > 0){
                                        do{
                                            $no++;

                                            ?>
                                                <tr>
                                                    <td><?= $no ?></td>
                                                    <td>
                                                        <a href="payroll_create_bonus_dept.php?pay_uniqid=<?= $row_pay_history['payroll_id']?>
                                                            &department_id=<?= $row_pay_history['dept_id']?>
                                                            &department_name=<?= $row_pay_history['department'] ?>
                                                            &froms=<?= $row_pay_history['froms'] ?>
                                                            &tos=<?= $row_pay_history['tos'] ?>
                                                            &status=<?= $row_pay_history['status'] ?>" class="btn text-active btn-link btn-sm">
                                                            <?= $row_pay_history['department']?>
                                                        </a>    
                                                    </td>
                                                    <td><?= $row_pay_history['netpay']?></td>
                                                    <td><?= $row_pay_history['created_by']?></td>
                                                    <td><?= $row_pay_history['date_created']?></td>
                                                    <td><?= $row_pay_history['updated_by']?></td>  
                                                    <td><?= $row_pay_history['date_updated']?></td>
                                                    <td><button <?= ($row_pay_history['status'] == 'ACTIVE' ? 'class="btn btn-danger btn-sm" onclick="deleteme('.$row_pay_history['ID'].')"' : 'class="btn btn-secondary btn-sm" disabled')?>>Delete</button></td>
                                                </tr>
                                            <?php
                                            $net_pay = $net_pay + floatval(str_replace( ',', '', $row_pay_history['netpay']));
                                        }while($row_pay_history = $dan_pay_history->fetch_assoc());
                                    }else{
                                        ?>
                                            <!-- <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr> -->
                                        <?php
                                    }

                                    //  payroll summary
                                    $sql_pay_summary = "SELECT * FROM payroll_summary WHERE payroll_id = '$pay_uniqid'";
                                    $dan_pay_summary = $con->query($sql_pay_summary) or die ($con->error);
                                    $row_pay_summary = $dan_pay_summary->fetch_assoc();
                                    $count_pay_summary = mysqli_num_rows($dan_pay_summary);

                                    if($count_pay_summary > 0){
                                        $id_summary = $row_pay_summary['ID'];
                                        if($net_pay != $row_pay_summary['net_pay'] || $gross != $row_pay_summary['gross_pay'] || $deductions != $row_pay_summary['deductions']){
                                            $sql_add_pay = "UPDATE `payroll_summary` SET `updated_by`='$created_by',`date_time_updated`='$date_time_created',
                                                            `gross_pay`='$gross', `deductions`='$deductions', `net_pay`='$net_pay' WHERE `ID` = '$id_summary'";
                                            $_add_pay = ($con->query($sql_add_pay) or die ($con->error));
                                        }
                                    }

                                ?>
                            </tbody>
                            <tfoot>
                                <th></th>
                                <th>Total</th>
                                <th><?= number_format($net_pay,2)?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
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