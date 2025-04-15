<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_POST['archive_member'])){

    $emp_id = trim($_POST['archive_id']);
    $id_no = trim($_POST['archive_employee_id_no']);
    $remarks = trim($_POST['archive_remarks']);
    $username = $_SESSION['Usernames'];

    // archive
    $sql = "INSERT INTO employees_archive (`emp_id`, `lname`, `fname`, `extname`, `mname`, 
    `bdate`, `gender`, `civil_status`, `address`, `spouse`, `no_of_child`, `child`, 
    `educ_background`, `beneficiary`, `position`, `job_status`, `dept`, `payroll_dept`, `dan_dept`, 
    `contact_no`, `gsis`, `pagibig`, `philhealth`, `sss`, `tin`, `date_hired`, `other_status`, 
    `emergency_name`, `emergency_address`, `emergency_contact`, `pictures`, `fingerdata`, `fingers`, 
    `rates`, `ot`, `confi`, `yr_hired`, `allowances`, `on_date`)
    SELECT `emp_id`, `lname`, `fname`, `extname`, `mname`, 
    `bdate`, `gender`, `civil_status`, `address`, `spouse`, `no_of_child`, `child`, 
    `educ_background`, `beneficiary`, `position`, `job_status`, `dept`, `payroll_dept`, `dan_dept`, 
    `contact_no`, `gsis`, `pagibig`, `philhealth`, `sss`, `tin`, `date_hired`, `other_status`, 
    `emergency_name`, `emergency_address`, `emergency_contact`, `pictures`, `fingerdata`, `fingers`, 
    `rates`, `ot`, `confi`, `yr_hired`, `allowances`, `on_date` 
    FROM employees WHERE ID = '$emp_id'";

    if($con->query($sql) or die ($con->error))
    {
        $sql_delete = "DELETE FROM `employees` WHERE ID = '$emp_id'";
        $del = $con->query($sql_delete) or die ($con->error);

        $sql_remarks = "INSERT INTO employees_archive_id (`ID`, `emp_id`, `employees_id`, `remarks`, `deleted_by`)
        VALUES (NULL, '$emp_id', '$id_no', '$remarks', '$username')";
        $in_remarks = $con->query($sql_remarks) or die ($con->error);

        $_SESSION['status'] = 'Employee`s Information successfully Deleted';
        echo header("Location: employees.php");
    }
}

?>