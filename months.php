<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_POST['idm'])){
    $m_now = $_POST['idm'];
}

$_SESSION['months'] = $m_now;
$y_now = $_SESSION['years'];

// payrolls
$sql = "SELECT * FROM net_pay WHERE months = '$m_now' && years = '$y_now' ORDER BY ID ASC";
$dan = $con->query($sql) or die ($con->error);
$row = $dan->fetch_assoc();
$count = mysqli_num_rows($dan);

?>

<table>
<thead>
    <th>Transaction Date</th>
    <th>Emp. ID</th>
    <th>Name of Employee</th>
    <th>Position</th>
    <th>Rate</th>
    <th>Work Days</th>
    <th>Basic Pay</th>
    <th>Add.</th>
    <th>Ded.</th>
    <th>Net Pay</th>
    <th>Action</th>
</thead>
<tbody>
    <?php
        if($count > 0){
            do{
                $ot = $row['ot_hrs'] * $row['ot_rate'];
                $additionals = $row['holiday'] + $ot;

                $rate = $row['rate'];

                $l = $rate / 8;
                $late_hours = $row['late'];
                $late_deduction = $late_hours * $l;
            
                $leaves = $row['leaves'] * $row['rate'];
                $deductions = $leaves + $late_deduction + $row['ca'] + $row['sss'] + $row['pag_ibig'] + $row['philhealth'];

                $gross = $row['basic_pay'] + $additionals;
                $net_pay = $gross - $deductions;
                ?>
                    <tr>
                        <td><?php echo $row['months'].' '.$row['dates'].', '.$row['years'] ?></td>
                        <td><?php echo $row['emp_id'] ?></td>
                        <td><?php echo $row['emp_name'] ?></td>
                        <td><?php echo $row['position'] ?></td>
                        <td><?php echo $row['rate'] ?></td>
                        <td><?php echo $row['work_days'] ?></td>                                        
                        <td><?php echo $row['basic_pay'] ?></td>
                        <td><?php echo $additionals ?></td>
                        <td><?php echo $deductions ?></td>
                        <td><h4><?php echo $net_pay ?></h4></td>
                        <td><a href="view_payroll.php?ID=<?php echo $row['ID'] ?>" class="a_pay">View</a></td>
                    </tr>
                <?php
            }while($row = $dan->fetch_assoc());
        }else{
            ?>
                <tr>
                    <td colspan="11">No results</td>
                </tr>
            <?php
        }
    ?>
</tbody>
</table>

