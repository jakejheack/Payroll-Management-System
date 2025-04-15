<?php 
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_GET['id'])){
$id = $_GET['id'];

    // payroll_history
    $sql_pay_history = "SELECT * FROM payroll_history WHERE ID = '$id'";
    $dan_pay_history = $con->query($sql_pay_history) or die ($con->error);
    $row_pay_history = $dan_pay_history->fetch_assoc();
    $count_pay_history = mysqli_num_rows($dan_pay_history);

    if($count_pay_history > 0){
        $payroll_id = $row_pay_history['payroll_id'];
        $from = $row_pay_history['froms'];
        $to = $row_pay_history['tos'];

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
    }

$sql = "DELETE FROM payroll_history WHERE ID = '$id'";

if($dan = $con->query($sql) or die ($con->error))
    {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }else{
        echo "Something went wrong";
    }
}else{
    echo header("Location: logout.php");
}
?>