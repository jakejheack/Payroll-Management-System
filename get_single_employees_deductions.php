<?php 
include_once("connection/cons.php");
$con = conns();

$id = trim($_POST['id']);

$sql = "SELECT * FROM employees WHERE ID='$id' LIMIT 1";
$query = mysqli_query($con,$sql);
$row = mysqli_fetch_assoc($query);
$count = mysqli_num_rows($query);

$fullname = $row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname'];

// deductions
$sql_ded = "SELECT * FROM deductions WHERE emp_id = '$id'";
$dan_ded = $con->query($sql_ded) or die ($con->error);
$row_ded = $dan_ded->fetch_assoc();
$count_ded = mysqli_num_rows($dan_ded);

if($count_ded > 0){
?>
{
    "fullname":"<?= $fullname?>",
    "position":"<?= $row['position']?>",
    "dept":"<?= $row['dept']?>",
    "sss":"<?= $row_ded['sss']?>",
    "pagibig":"<?= $row_ded['pagibig']?>",
    "philhealth":"<?= $row_ded['philhealth']?>",
    "sss_loan":"<?= $row_ded['sss_loan']?>",
    "sss_calamity":"<?= $row_ded['sss_calamity']?>",
    "hdmf_loan":"<?= $row_ded['hdmf_loan']?>",
    "hdmf_calamity":"<?= $row_ded['hdmf_calamity']?>",
    "salary_loan":"<?= $row_ded['salary_loan']?>",
    "ar":"<?= $row_ded['ar']?>",
    "esf":"<?= $row_ded['esf']?>",
    "shortages":"<?= $row_ded['shortages']?>"
}

<?php
}else{
?>
    {
        "fullname":"<?= $fullname?>",
        "position":"<?= $row['position']?>",
        "dept":"<?= $row['dept']?>",
        "sss":"",
        "pagibig":"",
        "philhealth":"",
        "sss_loan":"",
        "sss_calamity":"",
        "hdmf_loan":"",
        "hdmf_calamity":"",
        "salary_loan":"",
        "ar":"",
        "esf":"",
        "shortages":""
    }
<?php
    }
?>