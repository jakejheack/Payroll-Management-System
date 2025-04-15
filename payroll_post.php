<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");

if(isset($_POST['post'])){
    $id = $_POST['payroll_ID'];
    $updated_by = $_SESSION['Usernames'];
    $date_updated = date('m/d/y h:ia');

    // payroll_summary
    $sql_pay_summary = "SELECT * FROM payroll_summary WHERE ID = '$id'";
    $dan_pay_summary = $con->query($sql_pay_summary) or die ($con->error);
    $row_pay_summary = $dan_pay_summary->fetch_assoc();
    $count_pay_summary = mysqli_num_rows($dan_pay_summary);

    if($count_pay_summary > 0){
        $payroll_id = $row_pay_summary['payroll_id'];
        $from = $row_pay_summary['froms'];
        $to = $row_pay_summary['tos'];

        // payroll_history
        $sql_payroll_history = "SELECT * FROM payroll_history WHERE payroll_id = '$payroll_id'";
        $dan_payroll_history = $con->query($sql_payroll_history) or die ($con->error);
        $row_payroll_history = $dan_payroll_history->fetch_assoc();
        $count_payroll_history = mysqli_num_rows($dan_payroll_history);

        if($count_payroll_history > 0){
            do{
                $id_history = $row_payroll_history['ID'];
                // post payroll
                $sql_update_payroll_history = "UPDATE `payroll_history` SET `status` = 'POSTED', `updated_by` = '$updated_by', `date_updated` = '$date_updated' WHERE `ID` = '$id_history'";
                $p_update_history = ($con->query($sql_update_payroll_history) or die ($con->error));
            }while($row_payroll_history = $dan_payroll_history->fetch_assoc());
        }

        // payrolls
        $sql_payrolls = "SELECT * FROM payrolls WHERE payroll_id = '$payroll_id'";
        $dan_payrolls = $con->query($sql_payrolls) or die ($con->error);
        $row_payrolls = $dan_payrolls->fetch_assoc();
        $count_payrolls = mysqli_num_rows($dan_payrolls);

        if($count_payrolls > 0){
            do{
                $emp_id = $row_payrolls['emp_id'];
                $id_payrolls = $row_payrolls['ID'];
                $f_day = date('l, F d, Y', strtotime($from));

                // post payroll
                $sql_update_payrolls = "UPDATE `payrolls` SET `posting` = 'POSTED' WHERE `ID` = '$id_payrolls'";
                $p_update = ($con->query($sql_update_payrolls) or die ($con->error));

                // post dtr (time-in & time-out)
                do{
                    $sql_update_dtr = "UPDATE `dtrs` SET `posting` = 'POSTED' WHERE `emp_id` = '$emp_id' && `log_date` = '$f_day'";
                    $f_update_dtr = ($con->query($sql_update_dtr) or die ($con->error));

                    $f_day = date('l, F d, Y', strtotime($f_day . " +1 day"));
                }while(strtotime($f_day) <= strtotime($to));
            }while($row_payrolls = $dan_payrolls->fetch_assoc());
        }

        // allowances
        $sql_allowance = "SELECT * FROM payroll_allowance WHERE payroll_id = '$payroll_id'";
        $dan_allowance = $con->query($sql_allowance) or die ($con->error);
        $row_allowance = $dan_allowance->fetch_assoc();
        $count_allowance = mysqli_num_rows($dan_allowance);

        if($count_allowance > 0){
            do{
                $id_allowance = $row_allowance['ID'];

                $sql_update_allowance = "UPDATE `payroll_allowance` SET `posting` = 'POSTED' WHERE ID = '$id_allowance'";
                $f_update_allowance = ($con->query($sql_update_allowance) or die ($con->error));
            }while($row_allowance = $dan_allowance->fetch_assoc());
        }
    }

    $sql = "UPDATE `payroll_summary` SET `status` = 'POSTED', `updated_by` = '$updated_by', `date_time_updated` = '$date_updated' WHERE `ID` = '$id'";

    if($con->query($sql) or die ($con->error))
    {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }else{
        echo "Something went wrong";
    }
}elseif(isset($_POST['unpost'])){
    $id = $_POST['payroll_ID2'];
    $updated_by = $_SESSION['Usernames'].' - '.date('m/d/y h:ia');
    $date_updated = date('m/d/y h:ia');

    // payroll_summary
    $sql_pay_summary = "SELECT * FROM payroll_summary WHERE ID = '$id'";
    $dan_pay_summary = $con->query($sql_pay_summary) or die ($con->error);
    $row_pay_summary = $dan_pay_summary->fetch_assoc();
    $count_pay_summary = mysqli_num_rows($dan_pay_summary);

    if($count_pay_summary > 0){
        $payroll_id = $row_pay_summary['payroll_id'];
        $from = $row_pay_summary['froms'];
        $to = $row_pay_summary['tos'];

        // payroll_history
        $sql_payroll_history = "SELECT * FROM payroll_history WHERE payroll_id = '$payroll_id'";
        $dan_payroll_history = $con->query($sql_payroll_history) or die ($con->error);
        $row_payroll_history = $dan_payroll_history->fetch_assoc();
        $count_payroll_history = mysqli_num_rows($dan_payroll_history);

        if($count_payroll_history > 0){
            do{
                $id_history = $row_payroll_history['ID'];
                // post payroll
                $sql_update_payroll_history = "UPDATE `payroll_history` SET `status` = 'ACTIVE', `updated_by` = '$updated_by', `date_updated` = '$date_updated' WHERE `ID` = '$id_history'";
                $p_update_history = ($con->query($sql_update_payroll_history) or die ($con->error));
            }while($row_payroll_history = $dan_payroll_history->fetch_assoc());
        }

        // payrolls
        $sql_payrolls = "SELECT * FROM payrolls WHERE payroll_id = '$payroll_id'";
        $dan_payrolls = $con->query($sql_payrolls) or die ($con->error);
        $row_payrolls = $dan_payrolls->fetch_assoc();
        $count_payrolls = mysqli_num_rows($dan_payrolls);

        if($count_payrolls > 0){
            do{
                $emp_id = $row_payrolls['emp_id'];
                $id_payrolls = $row_payrolls['ID'];
                $f_day = date('l, F d, Y', strtotime($from));

                // post payroll
                $sql_update_payrolls = "UPDATE `payrolls` SET `posting` = '' WHERE `ID` = '$id_payrolls'";
                $p_update = ($con->query($sql_update_payrolls) or die ($con->error));

                // post dtr (time-in & time-out)
                do{
                    $sql_update_dtr = "UPDATE `dtrs` SET `posting` = '' WHERE `emp_id` = '$emp_id' && `log_date` = '$f_day'";
                    $f_update_dtr = ($con->query($sql_update_dtr) or die ($con->error));

                    $f_day = date('l, F d, Y', strtotime($f_day . " +1 day"));
                }while(strtotime($f_day) <= strtotime($to));
            }while($row_payrolls = $dan_payrolls->fetch_assoc());
        }

        // allowances
        $sql_allowance = "SELECT * FROM payroll_allowance WHERE payroll_id = '$payroll_id'";
        $dan_allowance = $con->query($sql_allowance) or die ($con->error);
        $row_allowance = $dan_allowance->fetch_assoc();
        $count_allowance = mysqli_num_rows($dan_allowance);

        if($count_allowance > 0){
            do{
                $id_allowance = $row_allowance['ID'];

                $sql_update_allowance = "UPDATE `payroll_allowance` SET `posting` = 'ACTIVE' WHERE ID = '$id_allowance'";
                $f_update_allowance = ($con->query($sql_update_allowance) or die ($con->error));
            }while($row_allowance = $dan_allowance->fetch_assoc());
        }
    }

    $sql = "UPDATE `payroll_summary` SET `status` = 'ACTIVE', `updated_by` = '$updated_by', `date_time_updated` = '$date_updated' WHERE `ID` = '$id'";
    if($con->query($sql) or die ($con->error))
    {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }else{
        echo "Something went wrong";
    }
}
?>