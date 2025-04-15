<?php
session_start();

include_once("../connection/cons.php");
$con = conns();

$page = 1;

$limit = $page * 10;
$start = $limit - 10;

if(isset($_POST['idt']))
{
    $dept = trim($_POST['idt']);
    $_SESSION['department'] = $dept;
    $dept = str_replace("'", "\'", $dept);
}

if(isset($_SESSION['search']))
{
    $search = $_SESSION['search'];
    $search = str_replace("'", "\'", $search);
}else{
    $search = "";
}

// employees
if(empty($dept))
{
    $sql = "SELECT * FROM employees WHERE emp_id LIKE '%$search%' || lname LIKE '%$search%' || fname LIKE '%$search%' || mname LIKE '%$search%' ORDER BY emp_id";
}else{
    $sql = "SELECT * FROM employees WHERE emp_id LIKE '%$search%' && dept = '$dept' || lname LIKE '%$search%' && dept = '$dept' || fname LIKE '%$search%' && dept = '$dept' || mname LIKE '%$search%' && dept = '$dept' ORDER BY emp_id";
}
$dan = $con->query($sql) or die ($con->error);
$row = $dan->fetch_assoc();
$count = mysqli_num_rows($dan);

?>

<table>
                        <thead>
                            <th>No.</th>
                            <th>Employee ID</th>
                            <th>Name of Employee</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th colspan="2">Actions</th>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                if($count > 0){
                                    do{
                                        if($no > $start && $no <= $limit){
                                        ?>
                                            <tr>
                                                <td><?php echo $no?></td>      
                                                <td><?php echo $row['emp_id'] ?></td>
                                                <td class="tdl"><?php echo $row['lname'].', '.$row['fname'].' '.substr($row['mname'],0,1).' '.$row['extname']; ?></td>
                                                <td><?php echo $row['position'] ?></td>
                                                <td><?php echo $row['dept'] ?></td>
                                                <td><a href="view_employeee.php?
                                                    ID=<?php echo $row['ID']?>" class="view"><img src="img/eyessss.png" alt=""></a></td>
                                                <td><a href="scheds.php?
                                                    ID=<?php echo $row['ID']?>" class="sched"><img src="img/calendar.png" alt=""></a></td>
                                            </tr>
                                        <?php
                                        }
                                        $no++;
                                    }while($row = $dan->fetch_assoc());
                                }else{
                                    ?>
                                        <tr>
                                            <td colspan="7">No results</td>
                                        </tr>
                                    <?php
                                }
                            ?>
                        </tbody>

                        <?php 
                            $pp = ceil($count / 10);

                            $prev = $page - 1;
                            $next = $page + 1;
                        ?>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td colspan="4"><?php echo $count?> records found</td>
                                <td colspan="3">
                                <?php
                                if($page > 1)
                                {
                                ?>
                                    <a href="employees.php?
                                            page=1"><img src="img/backward.png" alt="" width="15px"></a>
                                    <a href="employees.php?
                                            page=<?php echo $prev?>"><img src="img/previous.png" alt="" width="15px"></a>
                                    <?php } ?>
                                    <?php echo 'Page '.$page.' of '.$pp;
                                    if($page < $pp)
                                    {
                                    ?>
                                    <a href="employees.php?
                                    page=<?php echo $next?>"><img src="img/next.png" alt="" width="15px"></a> 
                                    <a href="employees.php?
                                    page=<?php echo $pp?>"><img src="img/forward.png" alt="" width="15px"></a>
                                <?php } ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>