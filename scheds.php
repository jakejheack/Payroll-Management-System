<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_GET['ID'])){
    $id = $_GET['ID'];
    $_SESSION['Employee_ID'] = $id;
}else{
    echo header("Location: employees.php");
}

// employees
$sql = "SELECT * FROM employees WHERE ID = '$id'";
$dan = $con->query($sql) or die ($con->error);
$row = $dan->fetch_assoc();
$count_emp = mysqli_num_rows($dan);

if($count_emp > 0){
    $emp_name = $row['lname'].', '.$row['fname'].' '.$row['mname'].' '.$row['extname'];
    $position = $row['position'];
}else{
    echo header("Location: employees.php");
}

// schedules
$sql_sched = "SELECT * FROM schedules WHERE emp_id = '$id'";
$dan_sched = $con->query($sql_sched) or die ($con->error);
$row_sched = $dan_sched->fetch_assoc();
$count_sched = mysqli_num_rows($dan_sched);

if($count_sched > 0)
{
    $mon = $row_sched['mon'];
    $tue = $row_sched['tue'];
    $wed = $row_sched['wed'];
    $thu = $row_sched['thu'];
    $fri = $row_sched['fri'];
    $sat = $row_sched['sat'];
    $sun = $row_sched['sun'];
}else{
    echo header("Location: work_days.php");
}

// header
include("includes/header.php");
include("includes/menus.php");

?>

<div class="containers">
    <h3>Working Schedules</h3>
        <div class="contents">
            <form action="" method="post" class="sched">
                <h4>Employee's Information</h4>
                        <span>Name: <?php echo $emp_name ?></span>
                        <br>
                        <span>Position: <?php echo $position ?></span>
                        <br>
                        <table class="day1">
                            <thead>
                                <tr>
                                    <th colspan="4">Monday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="2">AM</th>
                                    <th colspan="2">PM</th>
                                </tr>
                                <tr>
                                    <td>In</td>
                                    <td>Out</td>
                                    <td>In</td>
                                    <td>Out</td>
                                </tr>
                                <?php
                                    if($mon == "ON"){
                                        $in_a = date('h:i A',strtotime($row_sched['mon_in1']));
                                        $out_a = date('h:i A',strtotime($row_sched['mon_out1']));
                                        $in_b = date('h:i A',strtotime($row_sched['mon_in2']));
                                        $out_b = date('h:i A',strtotime($row_sched['mon_out2']));

                                        if($in_a == '00:00'){
                                            $in_a = "";
                                        }
                                        if($out_a == '00:00'){
                                            $out_a = "";
                                        }
                                        if($in_b == '00:00'){
                                            $in_b = "";
                                        }
                                        if($out_b == '00:00'){
                                            $out_b = "";
                                        }
                                        
                                        ?>
                                        <tr>
                                            <td><?php echo $in_a ?></td>
                                            <td><?php echo $out_a ?></td>
                                            <td><?php echo $in_b ?></td>
                                            <td><?php echo $out_b ?></td>
                                        </tr>
                                        <?php
                                    }else{
                                        ?>
                                            <tr>
                                                <td colspan="4"><b>Day-OFF </b>
                                                </td>

                                            </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <table class="day2">
                            <thead>
                                <tr>
                                    <th colspan="4">Tuesday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="2">AM</th>
                                    <th colspan="2">PM</th>
                                </tr>
                                <tr>
                                    <td>In</td>
                                    <td>Out</td>
                                    <td>In</td>
                                    <td>Out</td>
                                </tr>
                                <?php
                                    if($tue == "ON"){
                                        $in_a = date('h:i A',strtotime($row_sched['tue_in1']));
                                        $out_a = date('h:i A',strtotime($row_sched['tue_out1']));
                                        $in_b = date('h:i A',strtotime($row_sched['tue_in2']));
                                        $out_b = date('h:i A',strtotime($row_sched['tue_out2']));

                                        if($in_a == '00:00'){
                                            $in_a = "";
                                        }
                                        if($out_a == '00:00'){
                                            $out_a = "";
                                        }
                                        if($in_b == '00:00'){
                                            $in_b = "";
                                        }
                                        if($out_b == '00:00'){
                                            $out_b = "";
                                        }
                                        
                                        ?>
                                        <tr>
                                            <td><?php echo $in_a ?></td>
                                            <td><?php echo $out_a ?></td>
                                            <td><?php echo $in_b ?></td>
                                            <td><?php echo $out_b ?></td>
                                        </tr>
                                        <?php
                                    }else{
                                        ?>
                                            <tr>
                                                <td colspan="4"><b>Day-OFF </b>
                                                </td>

                                            </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <table class="day1">
                            <thead>
                                <tr>
                                    <th colspan="4">Wednesday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="2">AM</th>
                                    <th colspan="2">PM</th>
                                </tr>
                                <tr>
                                    <td>In</td>
                                    <td>Out</td>
                                    <td>In</td>
                                    <td>Out</td>
                                </tr>
                                <?php
                                    if($wed == "ON"){
                                        $in_a = date('h:i A',strtotime($row_sched['wed_in1']));
                                        $out_a = date('h:i A',strtotime($row_sched['wed_out1']));
                                        $in_b = date('h:i A',strtotime($row_sched['wed_in2']));
                                        $out_b = date('h:i A',strtotime($row_sched['wed_out2']));

                                        if($in_a == '00:00'){
                                            $in_a = "";
                                        }
                                        if($out_a == '00:00'){
                                            $out_a = "";
                                        }
                                        if($in_b == '00:00'){
                                            $in_b = "";
                                        }
                                        if($out_b == '00:00'){
                                            $out_b = "";
                                        }
                                        
                                        ?>
                                        <tr>
                                            <td><?php echo $in_a ?></td>
                                            <td><?php echo $out_a ?></td>
                                            <td><?php echo $in_b ?></td>
                                            <td><?php echo $out_b ?></td>
                                        </tr>
                                        <?php
                                    }else{
                                        ?>
                                            <tr>
                                                <td colspan="4"><b>Day-OFF </b>
                                                </td>

                                            </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <table class="day2">
                            <thead>
                                <tr>
                                    <th colspan="4">Thursday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="2">AM</th>
                                    <th colspan="2">PM</th>
                                </tr>
                                <tr>
                                    <td>In</td>
                                    <td>Out</td>
                                    <td>In</td>
                                    <td>Out</td>
                                </tr>
                                <?php
                                    if($thu == "ON"){
                                        $in_a = date('h:i A',strtotime($row_sched['thu_in1']));
                                        $out_a = date('h:i A',strtotime($row_sched['thu_out1']));
                                        $in_b = date('h:i A',strtotime($row_sched['thu_in2']));
                                        $out_b = date('h:i A',strtotime($row_sched['thu_out2']));

                                        if($in_a == '00:00'){
                                            $in_a = "";
                                        }
                                        if($out_a == '00:00'){
                                            $out_a = "";
                                        }
                                        if($in_b == '00:00'){
                                            $in_b = "";
                                        }
                                        if($out_b == '00:00'){
                                            $out_b = "";
                                        }
                                        
                                        ?>
                                        <tr>
                                            <td><?php echo $in_a ?></td>
                                            <td><?php echo $out_a ?></td>
                                            <td><?php echo $in_b ?></td>
                                            <td><?php echo $out_b ?></td>
                                        </tr>
                                        <?php
                                    }else{
                                        ?>
                                            <tr>
                                                <td colspan="4"><b>Day-OFF </b>
                                                </td>

                                            </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        </table>
                        <table class="day1">
                            <thead>
                                <tr>
                                    <th colspan="4">Friday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="2">AM</th>
                                    <th colspan="2">PM</th>
                                </tr>
                                <tr>
                                    <td>In</td>
                                    <td>Out</td>
                                    <td>In</td>
                                    <td>Out</td>
                                </tr>
                                <?php
                                    if($fri == "ON"){
                                        $in_a = date('h:i A',strtotime($row_sched['fri_in1']));
                                        $out_a = date('h:i A',strtotime($row_sched['fri_out1']));
                                        $in_b = date('h:i A',strtotime($row_sched['fri_in2']));
                                        $out_b = date('h:i A',strtotime($row_sched['fri_out2']));

                                        if($in_a == '00:00'){
                                            $in_a = "";
                                        }
                                        if($out_a == '00:00'){
                                            $out_a = "";
                                        }
                                        if($in_b == '00:00'){
                                            $in_b = "";
                                        }
                                        if($out_b == '00:00'){
                                            $out_b = "";
                                        }
                                        
                                        ?>
                                        <tr>
                                            <td><?php echo $in_a ?></td>
                                            <td><?php echo $out_a ?></td>
                                            <td><?php echo $in_b ?></td>
                                            <td><?php echo $out_b ?></td>
                                        </tr>
                                        <?php
                                    }else{
                                        ?>
                                            <tr>
                                                <td colspan="4"><b>Day-OFF </b>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <table class="day2">
                            <thead>
                                <tr>
                                    <th colspan="4">Saturday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="2">AM</th>
                                    <th colspan="2">PM</th>
                                </tr>
                                <tr>
                                    <td>In</td>
                                    <td>Out</td>
                                    <td>In</td>
                                    <td>Out</td>
                                </tr>
                                <?php
                                    if($sat == "ON"){
                                        $in_a = date('h:i A',strtotime($row_sched['sat_in1']));
                                        $out_a = date('h:i A',strtotime($row_sched['sat_out1']));
                                        $in_b = date('h:i A',strtotime($row_sched['sat_in2']));
                                        $out_b = date('h:i A',strtotime($row_sched['sat_out2']));

                                        if($in_a == '00:00'){
                                            $in_a = "";
                                        }
                                        if($out_a == '00:00'){
                                            $out_a = "";
                                        }
                                        if($in_b == '00:00'){
                                            $in_b = "";
                                        }
                                        if($out_b == '00:00'){
                                            $out_b = "";
                                        }
                                        
                                        ?>
                                        <tr>
                                            <td><?php echo $in_a ?></td>
                                            <td><?php echo $out_a ?></td>
                                            <td><?php echo $in_b ?></td>
                                            <td><?php echo $out_b ?></td>
                                        </tr>
                                        <?php
                                    }else{
                                        ?>
                                            <tr>
                                                <td colspan="4"><b>Day-OFF </b>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <table class="day1">
                            <thead>
                                <tr>
                                    <th colspan="4">Sunday</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="2">AM</th>
                                    <th colspan="2">PM</th>
                                </tr>
                                <tr>
                                    <td>In</td>
                                    <td>Out</td>
                                    <td>In</td>
                                    <td>Out</td>
                                </tr>
                                <?php
                                    if($sun == "ON"){
                                        $in_a = date('h:i A',strtotime($row_sched['sun_in1']));
                                        $out_a = date('h:i A',strtotime($row_sched['sun_out1']));
                                        $in_b = date('h:i A',strtotime($row_sched['sun_in2']));
                                        $out_b = date('h:i A',strtotime($row_sched['sun_out2']));

                                        if($in_a == '00:00'){
                                            $in_a = "";
                                        }
                                        if($out_a == '00:00'){
                                            $out_a = "";
                                        }
                                        if($in_b == '00:00'){
                                            $in_b = "";
                                        }
                                        if($out_b == '00:00'){
                                            $out_b = "";
                                        }
                                        
                                        ?>
                                        <tr>
                                            <td><?php echo $in_a ?></td>
                                            <td><?php echo $out_a ?></td>
                                            <td><?php echo $in_b ?></td>
                                            <td><?php echo $out_b ?></td>
                                        </tr>
                                        <?php
                                    }else{
                                        ?>
                                            <tr>
                                                <td colspan="4"><b>Day-OFF </b>
                                                </td>

                                            </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                            <br>
                            <div id="submit_sched">
                                <a href="work_days.php">Update Schedules</a>
                                <br>
                                <a href="employees.php">Back</a>
                            </div>
            </form> 
        </div>
</div>

<?php
include("includes/footer.php");
?>
