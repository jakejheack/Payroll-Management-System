<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_GET['ID']))
{
    $id = $_GET['ID'];
}else{
    header("Location: employees.php");
}

// employees
$sql = "SELECT * FROM employees WHERE ID = '$id'";
$dan = $con->query($sql) or die ($con->error);
$row = $dan->fetch_assoc();


// positions
$sql_pos = "SELECT * FROM positions";
$dan_pos = $con->query($sql_pos) or die ($con->error);
$row_pos = $dan_pos->fetch_assoc();

// departments
$sql_dept = "SELECT * FROM departments ORDER BY dept ASC";
$dan_dept = $con->query($sql_dept) or die ($con->error);
$row_dept = $dan_dept->fetch_assoc();

// header
include("includes/header.php");
?>

<div class="containers">
    <h3>Employee's Personal Information
    <a href="employees.php"><img src="img/back3.png" alt="" width="30px"></a>
    <?php
    if($_SESSION['Access'] != 'Supervisor'){
    ?>
    <a href="view_employee.php?
            ID=<?php echo $id?>"><img src="img/edit.png" alt="" width="25px"></a>
    <?php } ?>
    <!-- <button class="btn btn-danger btn-sm" onclick="deleteme(<?= $row_pay_h['ID'] ?>)">Delete</button> -->
    </h3>
    <div class="contents">
        <div class="add_form">
                    <table>
                        <tr>
                            <td colspan="4">
                                    <button class="button">
                                        <?php
                                        $pictures = $row['pictures'];
                                            if(empty($pictures))
                                            {
                                                ?>
                                                    <img id="" src="img/blank.jpg" alt="Employees Picture"/>
                                                <?php
                                            }elseif(!empty($pictures))
                                            {
                                                ?>
                                                    <img id="" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($pictures); ?>" alt="Employees Picture"/>
                                                <?php
                                            }
                                        ?>
                                    </button>
                            </td>
                            <td>
                                <img class="barcodes" src="barcode/<?php echo $row['emp_id'].'.png'?>" alt="">
                                <!-- <a href="barcode/<?php echo $row['emp_id'].'.png'?>" download="<?php echo $row['emp_id']?>"></a> -->
                            </td>
                        </tr>
                    </table>
                    <form action="" method="post" enctype='multipart/form-data'>
                    <table>
                        <tr>
                            <td><input type="checkbox" name="confi" id="confis" <?php echo($row['confi'] == 'Yes')? 'checked' : '' ?> disabled><label for="confis">Confi Member</label></td>
                            <td colspan="4"></td>
                        </tr>
                        <tr>
                            <td>Employee ID no.</td> 
                            <td colspan="3">Name of Employee <label>*</label></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="emp_id" id="" value="<?php echo $row['emp_id'] ?>" disabled>
                            </td>
                            <td>
                                <input type="text" name="lname" id=""  value="<?php echo $row['lname'] ?>" required disabled>
                            </td>
                            <td>
                                <input type="text" name="fname" id=""  value="<?php echo $row['fname'] ?>" required disabled>
                            </td>
                            <td>
                                <input type="text" name="extname" id=""  value="<?php echo $row['extname'] ?>" disabled>
                            </td>
                            <td>
                                <input type="text" name="mname" id=""  value="<?php echo $row['mname'] ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td>Birthdate <label>*</label></td>
                            <td>Gender <label>*</label></td>
                            <td>Civil Status <label>*</label></td>
                            <td colspan="2">Home Address <label>*</label></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="date" name="bdate" id="" value="<?php echo $row['bdate'] ?>" required disabled>
                            </td>
                            <td>
                                <select name="gender" id="" required disabled>
                                    <option value="" hidden selected>SELECT GENDER</option>
                                    <option value="MALE" disabled <?php echo($row['gender'] == 'MALE')? 'selected' : '' ?>>MALE</option>
                                    <option value="FEMALE" disabled <?php echo($row['gender'] == 'FEMALE')? 'selected' : '' ?>>FEMALE</option>
                                </select>
                            </td>
                            <td>
                                <select name="civil_status" id="" required disabled>
                                    <option value="" hidden selected>CIVIL STATUS</option>
                                    <option value="SINGLE" disabled <?php echo($row['civil_status'] == 'SINGLE')? 'selected' : '' ?>>SINGLE</option>
                                    <option value="MARRIED" disabled <?php echo($row['civil_status'] == 'MARRIED')? 'selected' : '' ?>>MARRIED</option>
                                    <option value="WIDOWED" disabled <?php echo($row['civil_status'] == 'WIDOWED')? 'selected' : '' ?>>WIDOWED</option>
                                    <option value="DIVORCED/SEPARATED" disabled <?php echo($row['civil_status'] == 'DIVORCED/SEPARATED')? 'selected' : '' ?>>DIVORCED/SEPARATED</option>
                                </select>
                            </td>
                            <td colspan="2">
                                <input type="text" name="address" id=""  value="<?php echo $row['address'] ?>" required disabled>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">Name of Spouse</td>
                            <td>No. of Child</td>
                            <td colspan="2">Name of Beneficiary</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="text" name="spouse" value="<?php echo $row['spouse'] ?>" disabled>
                            </td>
                            <td>
                                <input type="number" name="no_of_child" value="<?php echo $row['no_of_child'] ?>" disabled>
                            </td>
                            <td colspan="2">
                                <input type="text" name="beneficiary" id="" value="<?php echo $row['beneficiary'] ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td>Department <label>*</label></td>
                            <td>Position <label>*</label></td>
                            <td>Contact No.</td>
                            <td colspan="2">Educational Background</td>
                        </tr>
                        <tr>
                            <td>
                                <select name="dept" id="" required disabled>
                                    <option value="" hidden selected>DEPARTMENT</option>
                                    <?php
                                        do{
                                            ?>
                                                <option value="<?php echo strtoupper($row_dept['dept']) ?>" <?php echo(strtoupper($row['dept']) == strtoupper($row_dept['dept']))? 'selected' : '' ?> disabled><?php echo strtoupper($row_dept['dept']) ?></option>
                                            <?php
                                        }while($row_dept = $dan_dept->fetch_assoc())
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="position" id="" required disabled>
                                    <option value="" disabled selected>Position</option>
                                    <?php
                                        do{
                                            ?>
                                                <option value="<?php echo $row_pos['pos'] ?>" disabled <?php echo($row['position'] == $row_pos['pos'])? 'selected' : '' ?>> <?php echo $row_pos['pos'] ?> </option>
                                            <?php
                                        }while($row_pos = $dan_pos->fetch_assoc())
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="contact_no" id=""  value="<?php echo $row['contact_no'] ?>" disabled>
                            </td>
                            <td colspan="3">
                                <input type="text" name="educ_background" id=""  value="<?php echo $row['educ_background'] ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td>GSIS no.</td>
                            <td>PAG-IBIG no.</td>
                            <td>Philhealth no.</td>
                            <td>SSS no.</td>
                            <td>TIN no.</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="number" name="gsis" id=""  value="<?php echo $row['gsis'] ?>" disabled>
                            </td>
                            <td>
                                <input type="number" name="pagibig" id=""  value="<?php echo $row['pagibig'] ?>" disabled>
                            </td>
                            <td>
                                <input type="number" name="philhealth" id=""  value="<?php echo $row['philhealth'] ?>" disabled>
                            </td>
                            <td>
                                <input type="number" name="sss" id=""  value="<?php echo $row['sss'] ?>" disabled>
                            </td>
                            <td>
                                <input type="number" name="tin" id=""  value="<?php echo $row['tin'] ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td>Employee Rate / hr <label>*</label></td>
                            <td>Date Hired <label>*</label></td>
                            <td colspan="3">In Case of Emergency;</td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                if($row['confi'] == 'Yes' || $_SESSION['Access'] == 'Supervisor'){
                                    ?>
                                        <label for="">***</label>
                                    <?php
                                }else{
                                ?>
                                <input type="number" name="rates" step=".0001" id="" value="<?php echo $row['rates'] ?>" required disabled>
                                <?php } ?>
                            </td>
                            <td>
                                <input type="date" name="date_hired" id="" value="<?php echo $row['date_hired'] ?>" required disabled>
                            </td>
                            <td>
                                <input type="text" name="emergency_name"  value="<?php echo $row['emergency_name'] ?>" disabled>
                            </td>
                            <td>
                                <input type="text" name="emergency_address" value="<?php echo $row['emergency_address'] ?>" disabled>
                            </td>
                            <td>
                                <input type="number" name="emergency_contact" id=""  value="<?php echo $row['emergency_contact'] ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" <?= (!empty($row['allowances']) ? 'checked' : '')?> name="allowances" id="allowances" onclick="myFunction()" disabled><label for="allowances" style="color:black">With Allowance?</label>
                                <p id="text" style="display: <?= (!empty($row['allowances']) ? 'block' : 'none')?>">Allowance / Hr: <input type="number" step=".0001" name="allowance" id="" placeholder="Per hour allowance" value="<?= $row['allowances']?>" style="width: 50%;" disabled></p>
                            </td>
                            <td colspan="4"></td>
                        </tr>
                    </table>
            </form>
        </div>
    </div>
</div>

<?php
include("includes/footer.php");
include("includes/scripts.php");
?>