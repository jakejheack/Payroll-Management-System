<?php
session_start();

// UPDATE `employees` SET `payroll_dept`= `dept` WHERE 1

include_once("connection/cons.php");
$con = conns();

if(isset($_POST['edit']))
{
    $id = $_POST['emp_id'];
}elseif(isset($_GET['ID'])){
    $id = $_GET['ID'];
}else{
    header("Location: employees.php");
}

// employees
$sql = "SELECT * FROM employees WHERE ID = '$id'";
$dan = $con->query($sql) or die ($con->error);
$row = $dan->fetch_assoc();


// registration
    if(isset($_POST['submit'])){

        $id = $_POST['emp_id_number'];
        $lname = trim($_POST['lname']);
        $lname = str_replace("'", "\'", $lname);
        $lname = strtoupper($lname);
        $fname = trim($_POST['fname']);
        $fname = str_replace("'", "\'", $fname);
        $fname = strtoupper($fname);
        $extname = trim($_POST['extname']);
        $extname = str_replace("'", "\'", $extname);
        $extname = strtoupper($extname);
        $mname = trim($_POST['mname']);
        $mname = str_replace("'", "\'", $mname);
        $mname = strtoupper($mname);
        $bdate = $_POST['bdate'];
        $gender = strtoupper($_POST['gender']);
        $civil_status = strtoupper($_POST['civil_status']);
        $address = trim($_POST['address']);
        $address = str_replace("'", "\'", $address);
        $address = strtoupper($address);
        $spouse = strtoupper($_POST['spouse']);
        $spouse = str_replace("'", "\'", $spouse);
        $no_of_child = trim($_POST['no_of_child']);
        $educ_background = strtoupper($_POST['educ_background']);
        $educ_background = str_replace("'", "\'", $educ_background);
        $beneficiary = trim(strtoupper($_POST['beneficiary']));
        $beneficiary = str_replace("'", "\'", $beneficiary);
        $position = strtoupper($_POST['position']);
        $position = str_replace("'", "\'", $position);
        $job_status = strtoupper($_POST['job_status']);
        $job_status = str_replace("'", "\'", $job_status);
        $dept = strtoupper($_POST['dept']);
        $dept = str_replace("'", "\'", $dept);
        $contact_no = $_POST['contact_no'];
        $gsis = $_POST['gsis'];
        $pagibig = $_POST['pagibig'];
        $philhealth = $_POST['philhealth'];
        $sss = $_POST['sss'];
        $tin = $_POST['tin'];
        $date_hired = $_POST['date_hired'];
        $emergency_name = trim(strtoupper($_POST['emergency_name']));
        $emergency_name = str_replace("'", "\'", $emergency_name);
        $emergency_address = trim(strtoupper($_POST['emergency_address']));
        $emergency_address = str_replace("'", "\'", $emergency_address);
        $emergency_contact = trim(strtoupper($_POST['emergency_contact']));
        $emergency_contact = str_replace("'", "\'", $emergency_contact);

        if(isset($_POST['allowances']) && !empty($_POST['allowance'])){
            $allowance = $_POST['allowance'];
            if($allowance <= 0){
                $allowance = '';
            }
        }else{
            $allowance = '';
        }


        // confi member
        if(!empty($_POST['confi']))
        {
            $confi = 'Yes';
            $pay_dept = '';
            $rate = '';
            $ot = '';
        }else{
            $confi = '';
            $pay_dept = strtoupper($_POST['pay_dept']);
            $pay_dept = str_replace("'", "\'", $pay_dept);

            $rate = $_POST['rates'];

            if(empty($rate)){
                $rate = 0;
            }
    
            $ot_add = $rate * 0.25;
            $ot = $rate + $ot_add;    
        }

        $x_gender = $_POST['x_gender'];
        $x_dept = $_POST['x_dept'];
        $x_dept = str_replace("'", "\'", $x_dept);

        $sql = "UPDATE employees SET lname = '$lname', fname = '$fname', extname = '$extname', mname = '$mname',
        bdate = '$bdate', gender = '$gender', civil_status = '$civil_status', address = '$address', spouse = '$spouse',
        no_of_child = '$no_of_child', educ_background = '$educ_background', beneficiary = '$beneficiary', position = '$position', job_status = '$job_status',
        dept = '$dept', payroll_dept = '$pay_dept', contact_no = '$contact_no', gsis = '$gsis', pagibig = '$pagibig', philhealth = '$philhealth', sss = '$sss', tin = '$tin',
        date_hired = '$date_hired', emergency_name = '$emergency_name', emergency_address = '$emergency_address', emergency_contact = '$emergency_contact',
        rates = '$rate', confi = '$confi', allowances = '$allowance' WHERE ID ='$id'";
  
            if($con->query($sql) or die ($con->error))
                {
                    $_SESSION['status'] = "Employee`s Information has been updated!";
                    echo header("Location: view_employee.php?ID=".$id."&".uniqid()."=".uniqid().uniqid().uniqid()."");
                }else{
                    echo "Something went wrong";
            }  
    }
  

// positions
$sql_pos = "SELECT * FROM positions ORDER BY pos ASC";
$dan_pos = $con->query($sql_pos) or die ($con->error);
$row_pos = $dan_pos->fetch_assoc();

// departments
$sql_dept = "SELECT * FROM departments ORDER BY dept ASC";
$dan_dept = $con->query($sql_dept) or die ($con->error);
$row_dept = $dan_dept->fetch_assoc();


$query = "SELECT DISTINCT `job_status` FROM `employees` ORDER BY `job_status` ASC";


// header
include("includes/header.php");
?>
<script>
    function myFunction() {
  // Get the checkbox
  var checkBox = document.getElementById("allowances");
  // Get the output text
  var text = document.getElementById("text");

    // If the checkbox is checked, display the output text
    if (checkBox.checked == true){
        text.style.display = "block";
    } else {
        text.style.display = "none";
    }
    }

    function confiFunction() {
        // Get the checkbox
        var checkBox = document.getElementById("confis");
        // Get the output text
        var text = document.getElementById("confi_dept");
        var text2 = document.getElementById("pay_dept");

            // If the checkbox is checked, display the output text
            if (checkBox.checked == true){
                text.style.display = "block";
                text2.style.display = "none";
                document.getElementById('rates').disabled = !this.checked;
            } else {
                text2.style.display = "block";
                text.style.display = "none";
                document.getElementById('rates').disabled = this.checked;
            }
    }

</script>

<script>
function showLoad() {
  var box = document.getElementById("loading");
  box.style.display = "inline-block";
}
</script>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom form-row">
          <div class="form-group col-md">
                <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#exampleModal" class="button btn btn-link rounded-circle position-relative">
                            <?php
                            $pictures = $row['pictures'];
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
                        <h2>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg bg-light">
                            <span class="glyphicon badge badge-light">&#x270f;</span>
                            <!-- <span class="visually-hidden">unread messages</span> -->
                        </span>
                        </h2>
                </a>
            </div>
            <div class="form-group col-md-11">
                <h4><?= $row['lname'].', '.$row['fname'].' '.$row['mname'].' '.$row['extname']?>
                    <div class="spinner-border spinner-border text-secondary loading" role="status" id="loading" style="display: none;">
                        <span class="sr-only">Loading...</span>
                    </div>
                </h4>
                <span class="text-secondary">ID #: <?= $row['emp_id']?></span>
                <br>
                <strong class="text-secondary">Position: <?= $row['position']?></strong>
                <br>
                <strong class="text-secondary">Department: <?= $row['dept']?></strong>
            </div>
      </div>
      <?php
            if(isset($_SESSION['status'])){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>&#10004; '.$_SESSION['status'].'</strong> 
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
                unset($_SESSION['status']);
            }
        ?>
        <div class="table-responsive">
            <form action="" method="post" enctype='multipart/form-data'>
                    <div class="form-row">
                        <div class="form-group col-md-9 form-check">
                            <input type="checkbox" name="confi" id="confis"  <?php echo($row['confi'] == 'Yes')? 'checked' : '' ?>  onclick="confiFunction()">
                            <label class="form-check-label text-danger" for="confis" style="cursor: pointer;">
                                Confi Member
                            </label>
                        </div>
                        <div class="form-group col-md-3" style="text-align: right;">
                            <a href="employees.php" class="btn btn-secondary btn-sm">Cancel</a>
                            <input type="submit" class="btn btn-primary btn-sm" name="submit" value="Save Changes">
                            <input type="hidden" name="emp_id_number" value="<?= $id?>">
                            <input type="hidden" name="x_gender" value="<?= $row['gender'] ?>">
                            <input type="hidden" name="x_dept" value="<?= $row['dept'] ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="">Name of Employee</label>
                                <input type="text" name="lname" id="" class="form-control form-control-sm" placeholder="Last Name" value="<?php echo $row['lname'] ?>" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for=""></label>
                                <input type="text" name="fname" id="" class="form-control form-control-sm" placeholder="First Name" value="<?php echo $row['fname'] ?>" required> 
                        </div>
                        <div class="form-group col-md-3">
                            <label for=""></label>
                                <input type="text" name="extname" id="" class="form-control form-control-sm" placeholder="Name Ext. (e.g. Jr., Sr., II)"  value="<?php echo $row['extname'] ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label for=""></label>
                                <input type="text" name="mname" id="" class="form-control form-control-sm" placeholder="Middle Name" value="<?php echo $row['mname'] ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md">
                            <label for="inputName4">Position</label>
                                <select name="position" id="" class="custom-select custom-select-sm" required>
                                    <option value="" disabled selected>Position</option>
                                    <?php
                                        do{
                                            ?>
                                                <option value="<?php echo $row_pos['pos'] ?>" <?php echo($row['position'] == $row_pos['pos'])? 'selected' : '' ?>> <?php echo $row_pos['pos'] ?> </option>
                                            <?php
                                        }while($row_pos = $dan_pos->fetch_assoc());
                                    ?>
                                </select>
                        </div>
                        <div class="form-group col-md">
                            <label for="inputName4">Job Status</label>
                                <select name="job_status" id="" class="custom-select custom-select-sm" required>
                                    <option value="" disabled selected>Job Status</option>
                                    <?php
                                        if ($sql_status = $con->query($query) or die ($con->error)) {
                                            while ($row_status = mysqli_fetch_assoc($sql_status)) {
                                                ?>
                                                <option value="<?php echo $row_status['job_status'] ?>" <?php echo($row['job_status'] == $row_status['job_status'])? 'selected' : '' ?>> <?php echo $row_status['job_status'] ?> </option>
                                                <?php
                                                // echo $row_status['job_status'].'<br>';
                                            }
                                        }
                                    ?>
                                </select>
                        </div>
                        <div class="form-group col-md">
                            <label for="inputName4">Department</label>
                                <select name="dept" id="" class="custom-select custom-select-sm" required>
                                    <option value="" disabled selected>Department</option>
                                    <?php
                                        do{
                                            ?>
                                                <option value="<?php echo strtoupper($row_dept['dept']) ?>" <?php echo(strtoupper($row['dept']) == strtoupper($row_dept['dept']))? 'selected' : '' ?>><?php echo strtoupper($row_dept['dept']) ?></option>
                                            <?php
                                        }while($row_dept = $dan_dept->fetch_assoc());
                                        // departments
                                        $sql_dept = "SELECT * FROM departments ORDER BY dept ASC";
                                        $dan_dept = $con->query($sql_dept) or die ($con->error);
                                        $row_dept = $dan_dept->fetch_assoc(); 
                                    ?>
                                </select>
                        </div>
                        <div class="form-group col-md">
                            <label for="inputName4">Payroll Department</label>
                                <?php
                                if($row['confi'] == 'Yes'){
                                ?>
                                <input type="text" name="confi_dept" id="confi_dept" class="form-control form-control-sm" value="CONFI MEMBER" disabled>
                                <select name="pay_dept" id="pay_dept" class="custom-select custom-select-sm" style="display: none;">
                                    <option value="" disabled selected>Payroll Department</option>
                                    <?php
                                        do{
                                            ?>
                                                <option value="<?php echo strtoupper($row_dept['dept']) ?>" <?php echo(strtoupper($row['payroll_dept']) == strtoupper($row_dept['dept']))? 'selected' : '' ?>><?php echo strtoupper($row_dept['dept']) ?></option>
                                            <?php
                                        }while($row_dept = $dan_dept->fetch_assoc())
                                    ?>
                                </select>
                                <?php
                                }else{
                                ?>
                                <input type="text" name="confi_dept" id="confi_dept" class="form-control form-control-sm" value="CONFI MEMBER" style="display: none;" disabled>
                                <select name="pay_dept" id="pay_dept" class="custom-select custom-select-sm">
                                    <option value="" disabled selected>Payroll Department</option>
                                    <?php
                                        do{
                                            ?>
                                                <option value="<?php echo strtoupper($row_dept['dept']) ?>" <?php echo(strtoupper($row['payroll_dept']) == strtoupper($row_dept['dept']))? 'selected' : '' ?>><?php echo strtoupper($row_dept['dept']) ?></option>
                                            <?php
                                        }while($row_dept = $dan_dept->fetch_assoc())
                                    ?>
                                </select>
                                <?php } ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="inputName4">Home Address</label>
                                <input type="text" name="address" id="" class="form-control form-control-sm" placeholder="Home Address" value="<?php echo $row['address'] ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="inputName4">Contact #</label>
                            <input type="text" name="contact_no" id="" class="form-control form-control-sm" placeholder="Contact No." value="<?php echo $row['contact_no'] ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputName4">Birthdate</label>
                            <input type="date" name="bdate" id="" class="form-control form-control-sm" value="<?php echo $row['bdate'] ?>" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputName4">Gender</label>
                            <select name="gender" id="" class="custom-select custom-select-sm" required>
                                    <option value="" hidden selected>SELECT GENDER</option>
                                    <option value="MALE" <?php echo($row['gender'] == 'MALE')? 'selected' : '' ?>>MALE</option>
                                    <option value="FEMALE" <?php echo($row['gender'] == 'FEMALE')? 'selected' : '' ?>>FEMALE</option>
                                </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPosition4">Civil Status</label>
                            <select name="civil_status" id="" class="custom-select custom-select-sm" required>
                                    <option value="" hidden selected>CIVIL STATUS</option>
                                    <option value="SINGLE" <?php echo($row['civil_status'] == 'SINGLE')? 'selected' : '' ?>>SINGLE</option>
                                    <option value="MARRIED" <?php echo($row['civil_status'] == 'MARRIED')? 'selected' : '' ?>>MARRIED</option>
                                    <option value="WIDOWED" <?php echo($row['civil_status'] == 'WIDOWED')? 'selected' : '' ?>>WIDOWED</option>
                                    <option value="DIVORCED/SEPARATED" <?php echo($row['civil_status'] == 'DIVORCED/SEPARATED')? 'selected' : '' ?>>DIVORCED/SEPARATED</option>
                                </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label for="inputName4">Name of Spouse</label>
                            <input type="text" name="spouse" placeholder="Name of Spouse"  class="form-control form-control-sm" value="<?php echo $row['spouse'] ?>">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputPosition4">No. of Child</label>
                            <input type="number" name="no_of_child" placeholder="No. of Child" class="form-control form-control-sm"  value="<?php echo $row['no_of_child'] ?>">
                        </div>
                        <div class="form-group col-md-5">
                            <label for="inputName4">Name of Beneficiary</label>
                            <input type="text" name="beneficiary" id="" placeholder="Name of Beneficiary" class="form-control form-control-sm" value="<?php echo $row['beneficiary'] ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="inputName4">Educational Background</label>
                            <input type="text" name="educ_background" id="" placeholder="Educational Background" class="form-control form-control-sm" value="<?php echo $row['educ_background'] ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="inputPosition2">PAG-IBIG no.</label>
                            <input type="text" name="pagibig" id="" placeholder="Pagibig no." class="form-control form-control-sm" value="<?php echo $row['pagibig'] ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputName2">PHILHEALTH no.</label>
                            <input type="text" name="philhealth" id="" placeholder="Philhealth no." class="form-control form-control-sm" value="<?php echo $row['philhealth'] ?>">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputName2">SSS no.</label>
                            <input type="text" name="sss" id="" placeholder="SSS no." class="form-control form-control-sm" value="<?php echo $row['sss'] ?>">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputName2">GSIS no.</label>
                                <input type="text" name="gsis" id="" placeholder="GSIS no." class="form-control form-control-sm" value="<?php echo $row['gsis'] ?>">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputPosition2">TIN no.</label>
                            <input type="text" name="tin" id="" placeholder="TIN no." class="form-control form-control-sm" value="<?php echo $row['tin'] ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="inputName4">In Case of Emergency</label>
                            <input type="text" name="emergency_name" class="form-control form-control-sm" placeholder="Contact Person" value="<?php echo $row['emergency_name'] ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputName4"></label>
                            <input type="text" name="emergency_contact" id="" class="form-control form-control-sm" placeholder="Mobile no." value="<?php echo $row['emergency_contact'] ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputPosition4"></label>
                            <input type="text" name="emergency_address" class="form-control form-control-sm" placeholder="Address" value="<?php echo $row['emergency_address'] ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="inputName4">Date hired</label>
                            <input type="date" name="date_hired" id="" class="form-control form-control-sm" value="<?php echo $row['date_hired'] ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputName4">Rate per Hour</label>
                            <input type="number" name="rates" step=".0001" id="rates" class="form-control form-control-sm" value=<?= ($row['confi'] == 'Yes' ? '"" disabled' : '"'.$row['rates'].'"' )?>>
                        </div>
                       <div class="form-group col-md-4 form-check">
                            <input type="checkbox" <?= (!empty($row['allowances']) ? 'checked' : '')?> name="allowances" id="allowances" onclick="myFunction()">
                            <label for="allowances" class="form-check-label text-danger" style="cursor: pointer;">
                                With Allowance?
                            </label>
                            <p id="text" style="display: <?= (!empty($row['allowances']) ? 'block' : 'none')?>">
                            <input type="number" step=".0001" name="allowance" id="" placeholder="Per hour allowance" value="<?= $row['allowances']?>" class="form-control form-control-sm">
                            </p>
                        </div>
                    </div>

            </form>
    </div>
<!--  -->
</div>
    </div>
</main>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Change Picture</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="update_pic.php" method="post" enctype='multipart/form-data' style="text-align: center;">
        <div class="modal-body">
                <input id="file" type="file" name="image" accept="image/*" capture="user" onchange="readURL(this);" style="display: none;" accept=".jpg, .jpeg, .png" required>
                <label for="file" style="cursor: pointer;">
                <?php
                $pictures = $row['pictures'];
                    if(empty($pictures))
                    {
                        ?>
                            <img id="blah" src="img/blank.jpg" alt="Employees Picture" width="250px"/>
                        <?php
                    }elseif(!empty($pictures))
                    {
                        ?>
                            <img id="blah" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($pictures); ?>" alt="Employees Picture" width="250px"/>
                        <?php
                    }
                ?>
                </label>
                <p></p>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="ID" value="<?php echo $row['ID'];?>">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <input type="submit" name="change_pic" class="btn btn-primary" value="Save Changes">
        </div>
        </form>
        </div>
    </div>
    </div>


<script>
    function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $('#blah').attr('src', e.target.result).width(230).height(230);
    };
    reader.readAsDataURL(input.files[0]);
  }
}

if (window.File && window.FileReader && window.FormData) {
	var $inputField = $('#file');

	$inputField.on('change', function (e) {
		var file = e.target.files[0];

		if (file) {
			if (/^image\//i.test(file.type)) {
				readFile(file);
			} else {
				alert('Not a valid image!');
			}
		}
	});
} else {
	alert("File upload is not supported!");
}

</script>

<?php
include("includes/footer.php");
?>