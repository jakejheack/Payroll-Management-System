<?php

include_once("connection/cons.php");
$con = conns();

if(isset($_SESSION['search']))
{
    $search = $_SESSION['search'];
    $search = str_replace("'", "\'", $search);
}else{
    $search = "";
}

if(isset($_SESSION['department']))
{
    $dept = $_SESSION['department'];
    $dept = str_replace("'", "\'", $dept);
}else{
    $dept = "";
}

// employees
if(empty($dept))
{
    $sql = "SELECT * FROM employees WHERE emp_id LIKE '%$search%' || lname LIKE '%$search%' || fname LIKE '%$search%' || mname LIKE '%$search%' ORDER BY lname,fname,mname";
}else{
    $sql = "SELECT * FROM employees WHERE emp_id LIKE '%$search%' && dept = '$dept' || lname LIKE '%$search%' && dept = '$dept' || fname LIKE '%$search%' && dept = '$dept' || mname LIKE '%$search%' && dept = '$dept' ORDER BY lname,fname,mname";
}
$dan = $con->query($sql) or die ($con->error);
$row = $dan->fetch_assoc();
$count = mysqli_num_rows($dan); 

?>
<table id="prints" style="border: 1px solid black; display:none;">
    <thead>
        <th style="border: 1px solid black;">No.</th>
        <th style="border: 1px solid black;">Employee ID</th>
        <th style="border: 1px solid black;">Name of Employee</th>
        <th style="border: 1px solid black;">Position</th>
        <th style="border: 1px solid black;">Address</th>
        <th style="border: 1px solid black;">Department</th>
    </thead>
    <tbody>
        <?php
            $no = 1;
            if($count > 0){
                do{
                    ?>
                        <tr>
                            <td style="border: 1px solid black;"><?php echo $no?></td>      
                            <td style="border: 1px solid black;"><?php echo $row['emp_id'] ?></td>
                            <td style="border: 1px solid black;" class="tdl"><?php echo $row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname']; ?></td>
                            <td style="border: 1px solid black;"><?php echo $row['position'] ?></td>
                            <td style="border: 1px solid black;" class="tdl"><?php echo $row['address'] ?></td>
                            <td style="border: 1px solid black;"><?php echo $row['dept'] ?></td>
                        </tr>
                    <?php
                    $no++;
                }while($row = $dan->fetch_assoc());
            }else{
                ?>
                    <tr>
                        <td colspan="6">No results</td>
                    </tr>
                <?php
            }
        ?>
    </tbody>
</table>

