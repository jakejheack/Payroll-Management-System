<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_SESSION['Employee_ID']))
{
    $empID = $_SESSION['Employee_ID'];
}else{
    echo header("Location: add_employee.php");
}

// employees info
$sql_emp = "SELECT * FROM employees WHERE ID = '$empID'";
$dan_emp = $con->query($sql_emp) or die ($con->error);
$row_emp = $dan_emp->fetch_assoc();
$count_emp = mysqli_num_rows($dan_emp);

if($count_emp > 0){
    $emp_name = $row_emp['lname'].', '.$row_emp['fname'].' '.$row_emp['mname'].' '.$row_emp['extname'];
    $employee_id = $row_emp['emp_id'];
    $position = $row_emp['position'];
}else{
    echo header("Location: employees.php");
}


// departments
$sql_dept = "SELECT * FROM departments ORDER BY dept ASC";
$dan_dept = $con->query($sql_dept) or die ($con->error);
$row_dept = $dan_dept->fetch_assoc();
$count_dept = mysqli_num_rows($dan_dept);

// save number of employees
do{
    $id_dept = $row_dept['ID'];
    $depts = $row_dept['dept'];
    $depts = str_replace("'", "\'", $depts);

    $sql_m_dept = "SELECT gender,dept FROM employees where gender = 'Male' && dept = '$depts'";
    $dan_m_dept = $con->query($sql_m_dept) or die ($con->error);
    $row_m_dept = $dan_m_dept->fetch_assoc();
    $count_m_dept = mysqli_num_rows($dan_m_dept);

    $sql_f_dept = "SELECT gender,dept FROM employees where gender = 'Female' && dept = '$depts'";
    $dan_f_dept = $con->query($sql_f_dept) or die ($con->error);
    $row_f_dept = $dan_f_dept->fetch_assoc();
    $count_f_dept = mysqli_num_rows($dan_f_dept);

    // departments
    $sql_dept_update = "UPDATE `departments` SET `males` = '$count_m_dept', `females` = '$count_f_dept' WHERE `ID` = '$id_dept'";
    $dan_dept_update = $con->query($sql_dept_update) or die ($con->error);

}while($row_dept = $dan_dept->fetch_assoc());

// header
include("includes/header.php");

?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom form-row">
          <div class="form-group col-md">
                <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#exampleModal" class="button btn btn-link rounded-circle position-relative">
                            <?php
                            $pictures = $row_emp['pictures'];
                                if(empty($pictures))
                                {
                                    ?>
                                        <img id="" src="img/blank.jpg" alt="Employees Picture"  width="130px" height="130px" class="rounded-circle position-relative"/>
                                    <?php
                                }elseif(!empty($pictures))
                                {
                                    ?>
                                        <img id="" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($pictures); ?>" alt="Employees Picture"  width="130px" height="130px" style="margin:0; padding:0" class="rounded-circle position-relative"/>
                                    <?php
                                }
                            ?>
                </a>
            </div>
            <div class="form-group col-md-11">
                <h4><?= $row_emp['lname'].', '.$row_emp['fname'].' '.$row_emp['mname'].' '.$row_emp['extname']?></h4>
                <span class="text-secondary">ID #: <?= $row_emp['emp_id']?></span>
                <br>
                <strong class="text-secondary">Position: <?= $row_emp['position']?></strong>
                <br>
                <strong class="text-secondary">Department: <?= $row_emp['dept']?></strong>
            </div>
      </div>

        <div class="container table-responsive">

        <form action="schedules.php" method="post" name="work_days">
            <table class="table table-striped" style="width:100%; margin: 0">
                             <thead style="font-size: 15px;">
                                <tr>
                                    <th colspan="" style="text-align: right"><span>Applicable Date:</span></th>
                                    <th colspan="2">
                                        <div class="input-group">
                                            <span class="input-group-text">From:</span>
                                            <input type="date" name="sched_from" id="sched_from" value="<?= date('Y-m-01')?>" class="form-control" required>
                                        </div>
                                    </th>
                                    <th colspan="2">
                                        <div class="input-group">
                                            <span class="input-group-text">To:</span>
                                            <input type="date" name="sched_to" id="sched_to" value="<?= date('Y-m-t')?>" class="form-control" required>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <span class="input-group-text">Breaks:</span>
                                            <input type="number" name="min_break" id="breaks"  class="form-control" placeholder="# of minutes" value="30" style="width: 77px;" required></th>
                                        </div>    
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Day</th>
                                    <th style="text-align: center;">IN-1</th>
                                    <th style="text-align: center;">OUT-1</th>
                                    <th style="text-align: center;">IN-2</th>
                                    <th style="text-align: center;">OUT-2</th>
                                    <th>Work Day Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Monday</th>
                                    <td><input type="time" name="mon_in1" id="mon_in1"  class="form-control" value=""></td>
                                    <td><input type="time" name="mon_out1" id="mon_out1"  class="form-control" value=""></td>
                                    <td><input type="time" name="mon_in2" id="mon_in2"  class="form-control" value=""></td>
                                    <td><input type="time" name="mon_out2" id="mon_out2"  class="form-control" value=""></td>
                                    <td><select name="mon_on" id="mon_on" class="custom-select" required>
                                        <option value="ON" selected>Active</option>
                                        <option value="OFF">Day off</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <th>Tuesday</th>
                                    <td><input type="time" name="tue_in1" id="tue_in1"  class="form-control" value=""></td>
                                    <td><input type="time" name="tue_out1" id="tue_out1"  class="form-control" value=""></td>
                                    <td><input type="time" name="tue_in2" id="tue_in2"  class="form-control" value=""></td>
                                    <td><input type="time" name="tue_out2" id="tue_out2"  class="form-control" value=""></td>
                                    <td><select name="tue_on" id="tue_on" class="custom-select" required>
                                        <option value="ON" selected>Active</option>
                                        <option value="OFF">Day off</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <th>Wednesday</th>
                                    <td><input type="time" name="wed_in1" id="wed_in1"  class="form-control" value=""></td>
                                    <td><input type="time" name="wed_out1" id="wed_out1"  class="form-control" value=""></td>
                                    <td><input type="time" name="wed_in2" id="wed_in2"  class="form-control" value=""></td>
                                    <td><input type="time" name="wed_out2" id="wed_out2"  class="form-control" value=""></td>
                                    <td><select name="wed_on" id="wed_on" class="custom-select" required>
                                        <option value="ON" selected>Active</option>
                                        <option value="OFF">Day off</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <th>Thursday</th>
                                    <td><input type="time" name="thu_in1" id="thu_in1"  class="form-control" value=""></td>
                                    <td><input type="time" name="thu_out1" id="thu_out1"  class="form-control" value=""></td>
                                    <td><input type="time" name="thu_in2" id="thu_in2"  class="form-control" value=""></td>
                                    <td><input type="time" name="thu_out2" id="thu_out2"  class="form-control" value=""></td>
                                    <td><select name="thu_on" id="thu_on" class="custom-select" required>
                                        <option value="ON" selected>Active</option>
                                        <option value="OFF">Day off</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <th>Friday</th>
                                    <td><input type="time" name="fri_in1" id="fri_in1"  class="form-control" value=""></td>
                                    <td><input type="time" name="fri_out1" id="fri_out1"  class="form-control" value=""></td>
                                    <td><input type="time" name="fri_in2" id="fri_in2"  class="form-control" value=""></td>
                                    <td><input type="time" name="fri_out2" id="fri_out2"  class="form-control" value=""></td>
                                    <td><select name="fri_on" id="fri_on" class="custom-select" required>
                                        <option value="ON" selected>Active</option>
                                        <option value="OFF">Day off</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <th>Saturday</th>
                                    <td><input type="time" name="sat_in1" id="sat_in1"  class="form-control" value=""></td>
                                    <td><input type="time" name="sat_out1" id="sat_out1"  class="form-control" value=""></td>
                                    <td><input type="time" name="sat_in2" id="sat_in2"  class="form-control" value=""></td>
                                    <td><input type="time" name="sat_out2" id="sat_out2"  class="form-control" value=""></td>
                                    <td><select name="sat_on" id="sat_on" class="custom-select" required>
                                        <option value="ON" selected>Active</option>
                                        <option value="OFF">Day off</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <th>Sunday</th>
                                    <td><input type="time" name="sun_in1" id="sun_in1"  class="form-control" value=""></td>
                                    <td><input type="time" name="sun_out1" id="sun_out1"  class="form-control" value=""></td>
                                    <td><input type="time" name="sun_in2" id="sun_in2"  class="form-control" value=""></td>
                                    <td><input type="time" name="sun_out2" id="sun_out2"  class="form-control" value=""></td>
                                    <td><select name="sun_on" id="sun_on" class="custom-select" required>                                        <option value="" selected hidden></option>
                                        <option value="ON" selected>Active</option>
                                        <option value="OFF">Day off</option>
                                    </select></td>
                                </tr>
                            </tbody>
                        </table>
                        <p></p>
                        <div class="mb-3" style="text-align: right;">
                            <input type="hidden" name="emp_id" id="emp_id" value="<?= $row_emp['ID']?>">
                            <input type="hidden" name="pages" value="employees">
                            <input type="submit" class="btn btn-primary btn-sm" value="Submit" name="update_time">
                        </div>
        </form>
<!--  -->
</div>
    </div>
</main>
<script>
    $('#mon_in1').change(function() {
        $('#tue_in1').val($('#mon_in1').val())
        $('#wed_in1').val($('#mon_in1').val())
        $('#thu_in1').val($('#mon_in1').val())
        $('#fri_in1').val($('#mon_in1').val())
        $('#sat_in1').val($('#mon_in1').val())
        $('#sun_in1').val($('#mon_in1').val())
    })

    $('#mon_out1').change(function() {
        $('#tue_out1').val($('#mon_out1').val())
        $('#wed_out1').val($('#mon_out1').val())
        $('#thu_out1').val($('#mon_out1').val())
        $('#fri_out1').val($('#mon_out1').val())
        $('#sat_out1').val($('#mon_out1').val())
        $('#sun_out1').val($('#mon_out1').val())
    })

    $('#mon_in2').change(function() {
        $('#tue_in2').val($('#mon_in2').val())
        $('#wed_in2').val($('#mon_in2').val())
        $('#thu_in2').val($('#mon_in2').val())
        $('#fri_in2').val($('#mon_in2').val())
        $('#sat_in2').val($('#mon_in2').val())
        $('#sun_in2').val($('#mon_in2').val())
    })

    $('#mon_out2').change(function() {
        $('#tue_out2').val($('#mon_out2').val())
        $('#wed_out2').val($('#mon_out2').val())
        $('#thu_out2').val($('#mon_out2').val())
        $('#fri_out2').val($('#mon_out2').val())
        $('#sat_out2').val($('#mon_out2').val())
        $('#sun_out2').val($('#mon_out2').val())
    })
</script>

<?php
include("includes/footer.php");
