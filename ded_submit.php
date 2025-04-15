<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_POST['changes']))
{
    $emp_id = $_POST['emp_ids'];
    $ar = $_POST['ar'];
    $sss = $_POST['sss'];
    $pagibig = $_POST['pagibig'];
    $philhealth = $_POST['philhealth'];
    $esf = $_POST['esf'];
    $salary_loan = $_POST['salary_loan'];
    $sss_loan = $_POST['sss_loan'];
    $sss_calamity = $_POST['sss_calamity'];
    $hdmf_loan = $_POST['hdmf_loan'];
    $hdmf_calamity = $_POST['hdmf_calamity'];
    $shortages = $_POST['shortages'];

    $sss_c = 'Starts';
    $pagibig_c = 'Starts';
    $philhealth_c = 'Starts';
    $sss_loan_c = 'Ends';
    $sss_calamity_c = 'Ends';
    $hdmf_loan_c = 'Ends';
    $hdmf_calamity_c = 'Ends';
    $ar_c = 'Both';
    $esf_c = 'Both';
    $salary_loan_c = 'Both';
    $shortages_c = 'Both';
    
        // check
        $sql_check = "SELECT * FROM deductions WHERE emp_id = '$emp_id'";
        $dan_check = $con->query($sql_check) or die ($con->error);
        $row_check = $dan_check->fetch_assoc();
        $count_check = mysqli_num_rows($dan_check);

        if($count_check > 0)
        {
            $sql_deductions = "UPDATE deductions SET ar = '$ar', sss = '$sss', pagibig = '$pagibig', 
                            philhealth = '$philhealth', esf = '$esf', salary_loan = '$salary_loan', 
                            sss_loan = '$sss_loan', sss_calamity = '$sss_calamity', hdmf_loan = '$hdmf_loan',
                            hdmf_calamity = '$hdmf_calamity', shortages = '$shortages', ar_c = '$ar_c', sss_c = '$sss_c',
                            pagibig_c = '$pagibig_c', philhealth_c = '$philhealth_c', esf_c = '$esf_c', salary_loan_c = '$salary_loan_c',
                            sss_loan_c = '$sss_loan_c', sss_calamity_c = '$sss_calamity_c', hdmf_loan_c = '$hdmf_loan_c',
                            hdmf_calamity_c = '$hdmf_calamity_c', shortages_c = '$shortages_c' WHERE emp_id = '$emp_id'";

            if($con->query($sql_deductions) or die ($con->error))
            {
                header("Location: " . $_SERVER["HTTP_REFERER"]);    
            }else{
                echo "Something went wrong";
            }
        }else{
            // deductions
            $sql_deductions = "INSERT INTO `deductions` (`ID`, `emp_id`, `ar`, `sss`, `pagibig`, `philhealth`, `esf`, `salary_loan`,
                                `sss_loan`, `sss_calamity`, `hdmf_loan`, `hdmf_calamity`, `shortages`, `ar_c`, `sss_c`, `pagibig_c`,
                                `philhealth_c`, `esf_c`, `salary_loan_c`, `sss_loan_c`, `sss_calamity_c`, `hdmf_loan_c`, `hdmf_calamity_c`,
                                `shortages_c`) VALUES (NULL, '$emp_id', '$ar', '$sss', '$pagibig', '$philhealth', '$esf', '$salary_loan',
                                '$sss_loan', '$sss_calamity', '$hdmf_loan', '$hdmf_calamity', '$shortages', '$ar_c', '$sss_c', '$pagibig_c',
                                '$philhealth_c', '$esf_c', '$salary_loan_c', '$sss_loan', '$sss_calamity_c', '$hdmf_loan_c', '$hdmf_calamity_c', 
                                '$shortages_c')";

            if($con->query($sql_deductions) or die ($con->error))
            {
                header("Location: " . $_SERVER["HTTP_REFERER"]);    
            }else{
                echo "Something went wrong.";
            }
        }

    
}
