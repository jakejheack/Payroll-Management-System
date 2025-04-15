<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");
$y_now = date("Y");
$m_now = date("m");
$d_now = date("j");


// employee registration
    if(isset($_POST['submit'])){

        $y_now = date("Y");
        $m_now = date("m");
        $d_now = date("j");

        $date_hired = $_POST['date_hired'];
        $yr_hired = date("Y");
        $m_hired = date('m', strtotime($date_hired));

            // employees
            $sql_emp = "SELECT * FROM emp_id ORDER BY ID DESC LIMIT 1";
            $dan_emp = $con->query($sql_emp) or die ($con->error);
            $row_emp = $dan_emp->fetch_assoc();
            $count_emp = mysqli_num_rows($dan_emp);

            if($count_emp > 0){
                if($y_now != $row_emp['years']){
                    $e_id = 1;
                    $num_series = 1;
                }elseif($y_now == $row_emp['years']){
                    $e_id = floatval($row_emp['num_series']) + 1;
                    $num_series = $e_id;
                }
            }else{
                $e_id = 1;
                $num_series = 1;
            }

            $add_zero = 7 - strlen($e_id);

            if($add_zero == 6)
            {
                $e_id = '000000'.$e_id;
            }elseif($add_zero == 5)
            {
                $e_id = '00000'.$e_id;
            }elseif($add_zero == 4)
            {
                $e_id = '0000'.$e_id;
            }elseif($add_zero == 3)
            {
                $e_id = '000'.$e_id;
            }elseif($add_zero == 2)
            {
                $e_id = '00'.$e_id;
            }elseif($add_zero == 1)
            {
                $e_id = '0'.$e_id;
            }

            $employee_id = $y_now.$e_id;

        $emp_id = $employee_id;
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
        $dan_dept = "";
        $contact_no = $_POST['contact_no'];
        $gsis = $_POST['gsis'];
        $pagibig = $_POST['pagibig'];
        $philhealth = $_POST['philhealth'];
        $sss = $_POST['sss'];
        $tin = $_POST['tin'];
        $other_status = "";
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

        // picture
        if(!empty($_FILES["image"]["name"])) { 
            // Get file info 
            $fileName = basename($_FILES["image"]["name"]); 
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 
             
            // Allow certain file formats 
            $allowTypes = array('jpg','png','jpeg','gif'); 
            if(in_array($fileType, $allowTypes)){ 
                $image = $_FILES['image']['tmp_name']; 
                $imgContent = addslashes(file_get_contents($image));              
            }
        }else{
            $imgContent = "";
        }

        // check if already registered
        $check_id = "SELECT * FROM employees WHERE lname = '$lname' && fname = '$fname' && mname = '$mname'";
        $result = $con->query($check_id) or die ($con->error);
        $count = mysqli_num_rows($result);
 
        if($count > 0){
            $_SESSION['check'] = "Employee Name - ".$lname.", ".$fname." ".$mname." already Existed!";
            echo header("Location: employees.php");
        }else{

                $sql = "INSERT INTO `employees` (`ID`, `emp_id`, `lname`, `fname`, `extname`, `mname`, `bdate`, `gender`, `civil_status`, 
                        `address`, `spouse`, `no_of_child`, `educ_background`, `beneficiary`, `position`, `job_status`, `dept`, `payroll_dept`, `dan_dept`, 
                        `contact_no`, `gsis`, `pagibig`, `philhealth`, `sss`, `tin`, `date_hired`, `other_status`, 
                        `emergency_name`, `emergency_address`, `emergency_contact`, `pictures`, `rates`, `ot`, `confi`, `yr_hired`, `allowances`)
                        VALUES (NULL, '$emp_id', '$lname', '$fname', '$extname', '$mname', '$bdate', '$gender', '$civil_status', 
                        '$address', '$spouse', '$no_of_child', '$educ_background', '$beneficiary', '$position', '$job_status', '$dept', '$pay_dept', '$dan_dept', 
                        '$contact_no', '$gsis', '$pagibig', '$philhealth', '$sss', '$tin', '$date_hired', '$other_status', 
                        '$emergency_name', '$emergency_address', '$emergency_contact', '$imgContent', '$rate', '$ot', '$confi', '$yr_hired', '$allowance')";


                if($con->query($sql) or die ($con->error))
                {

                    $ret_ID = "SELECT * FROM employees WHERE lname = '$lname' && fname = '$fname' && mname = '$mname'";
                    $res = $con->query($ret_ID) or die ($con->error);
                    $row_ID = $res->fetch_assoc();

                    $id_emp = $row_ID['ID'];
                    
                    //   schedules
                    $sql_sched = "INSERT INTO `schedules` (`ID`, `emp_id`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`, `sun`, 
                    `mon_in1`, `mon_out1`, `mon_in2`, `mon_out2`, `tue_in1`, `tue_out1`, `tue_in2`, `tue_out2`, 
                    `wed_in1`, `wed_out1`, `wed_in2`, `wed_out2`, `thu_in1`, `thu_out1`, `thu_in2`, `thu_out2`, 
                    `fri_in1`, `fri_out1`, `fri_in2`, `fri_out2`, `sat_in1`, `sat_out1`, `sat_in2`, `sat_out2`, 
                    `sun_in1`, `sun_out1`, `sun_in2`, `sun_out2`)
                    VALUES (NULL, '$id_emp', '',  '', '', '', '', '', '', '', '', '', '', 
                    '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')";

                    $ddd = ($con->query($sql_sched) or die ($con->error));

                    // deductions
                    $sql_deductions = "INSERT INTO `deductions` (`ID`, `emp_id`, `ar`, `sss`, `pagibig`, `philhealth`, `esf`, `salary_loan`,
                    `sss_loan`, `sss_calamity`, `hdmf_loan`, `hdmf_calamity`, `shortages`, `ar_c`, `sss_c`, `pagibig_c`,
                    `philhealth_c`, `esf_c`, `salary_loan_c`, `sss_loan_c`, `sss_calamity_c`, `hdmf_loan_c`, `hdmf_calamity_c`,
                    `shortages_c`) VALUES (NULL, '$id_emp', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'Both', 
                    'Starts', 'Starts', 'Starts', 'Both', 'Both', 'Ends', 'Ends', 'Ends', 'Ends', 'Both')";

                    $ded = ($con->query($sql_deductions) or die ($con->error));

                    // employee ID series
                    $sql_id = "INSERT INTO `emp_id` (`ID`, `years`, `num_series`) VALUES (NULL, '$y_now', '$num_series')";
                    $dis = ($con->query($sql_id) or die ($con->error));


                        $_SESSION['status'] = "New Employee's information successfully Saved!";
                        $_SESSION['Employee_ID'] = $id_emp;
                        echo header("Location: work_days.php");
                }else{
                    echo "Something went wrong";
            }
  
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

// DAN
// $sql_dan = "SELECT * FROM dans";
// $dan_dan = $con->query($sql_dan) or die ($con->error);
// $row_dan = $dan_dan->fetch_assoc();

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

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Add New Employee</h1>
      </div>

        <div class="container table-responsive">

            <form action="" method="post" enctype='multipart/form-data'>
                <div class="form-group col-md">
                    <input id="file" type="file" name="image" accept="image/*" capture="user" onchange="readURL(this);" accept=".jpg, .jpeg, .png" style="display: none;">
                        <label for="file" style="cursor: pointer;">
                            <img id="blah" src="img/blank.jpg" alt="Employees Picture" width="130px" height="130px" class="rounded-circle position-relative"/>
                        </label>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-10 form-check">
                        <input type="checkbox" name="confi" id="confis" onclick="confiFunction()">
                        <label for="confis" class="form-check-label text-danger" style="cursor: pointer;">
                            Confi Member
                        </label>
                    </div>
                    <div class="form-group col-md-2" style="text-align: right;">
                        <input type="submit" class="btn btn-primary btn-sm" name="submit" value="Submit">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="">Name of Employee</label>
                            <input type="text" name="lname" id="" class="form-control form-control-sm" placeholder="Last Name" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for=""></label>
                            <input type="text" name="fname" id="" class="form-control form-control-sm" placeholder="First Name" required> 
                    </div>
                    <div class="form-group col-md-3">
                        <label for=""></label>
                            <input type="text" name="extname" id="" class="form-control form-control-sm" placeholder="Name Ext. (e.g. Jr., Sr., II)">
                    </div>
                    <div class="form-group col-md-3">
                        <label for=""></label>
                            <input type="text" name="mname" id="" class="form-control form-control-sm" placeholder="Middle Name">
                    </div>
                </div>
                <div class="form-row">
                        <div class="form-group col-md">
                            <label for="inputName4">Position</label>
                                <select name="position" id="" class="custom-select custom-select-sm" required>
                                    <option value="" disabled selected>Choose Position ...</option>
                                    <?php
                                        do{
                                            ?>
                                                <option value="<?php echo $row_pos['pos'] ?>"><?php echo $row_pos['pos'] ?></option>
                                            <?php
                                        }while($row_pos = $dan_pos->fetch_assoc())
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
                                                <option value="<?php echo $row_status['job_status'] ?>"> <?php echo $row_status['job_status'] ?> </option>
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
                                    <option value="" disabled selected>Choose Department ...</option>
                                    <?php
                                        do{
                                            ?>
                                                <option value="<?php echo strtoupper($row_dept['dept']) ?>"><?php echo strtoupper($row_dept['dept']) ?></option>
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
                                <input type="text" name="confi_dept" id="confi_dept" class="form-control form-control-sm" value="CONFI MEMBER" style="display: none;" disabled>
                                <select name="pay_dept" id="pay_dept" class="custom-select custom-select-sm">
                                    <option value="" disabled selected>Payroll Department</option>
                                    <?php
                                        do{
                                            ?>
                                                <option value="<?php echo strtoupper($row_dept['dept']) ?>" ><?php echo strtoupper($row_dept['dept']) ?></option>
                                            <?php
                                        }while($row_dept = $dan_dept->fetch_assoc())
                                    ?>
                                </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="inputName4">Home Address</label>
                                <input type="text" name="address" id="" class="form-control form-control-sm" placeholder="Home Address" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="inputName4">Contact #</label>
                            <input type="text" name="contact_no" id="" class="form-control form-control-sm" placeholder="Contact No.">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputName4">Birthdate</label>
                            <input type="date" name="bdate" id="" class="form-control form-control-sm" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputName4">Gender</label>
                            <select name="gender" id="" class="custom-select custom-select-sm" required>
                                    <option value="" hidden selected>SELECT GENDER</option>
                                    <option value="MALE">MALE</option>
                                    <option value="FEMALE">FEMALE</option>
                                </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPosition4">Civil Status</label>
                            <select name="civil_status" id="" class="custom-select custom-select-sm" required>
                                    <option value="" hidden selected>CIVIL STATUS</option>
                                    <option value="SINGLE" >SINGLE</option>
                                    <option value="MARRIED" >MARRIED</option>
                                    <option value="WIDOWED" >WIDOWED</option>
                                    <option value="DIVORCED/SEPARATED" >DIVORCED/SEPARATED</option>
                                </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label for="inputName4">Name of Spouse</label>
                            <input type="text" name="spouse" placeholder="Name of Spouse"  class="form-control form-control-sm">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputPosition4">No. of Child</label>
                            <input type="number" name="no_of_child" placeholder="No. of Child" class="form-control form-control-sm" >
                        </div>
                        <div class="form-group col-md-5">
                            <label for="inputName4">Name of Beneficiary</label>
                            <input type="text" name="beneficiary" id="" placeholder="Name of Beneficiary" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="inputName4">Educational Background</label>
                            <input type="text" name="educ_background" id="" placeholder="Educational Background" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="inputPosition2">PAG-IBIG no.</label>
                            <input type="number" name="pagibig" id="" placeholder="Pagibig no." class="form-control form-control-sm" value="">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputName2">PHILHEALTH no.</label>
                            <input type="number" name="philhealth" id="" placeholder="Philhealth no." class="form-control form-control-sm" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputName2">SSS no.</label>
                            <input type="number" name="sss" id="" placeholder="SSS no." class="form-control form-control-sm" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputName2">GSIS no.</label>
                                <input type="number" name="gsis" id="" placeholder="GSIS no." class="form-control form-control-sm" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputPosition2">TIN no.</label>
                            <input type="number" name="tin" id="" placeholder="TIN no." class="form-control form-control-sm" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="inputName4">In Case of Emergency</label>
                            <input type="text" name="emergency_name" class="form-control form-control-sm" placeholder="Contact Person" value="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputName4"></label>
                            <input type="number" name="emergency_contact" id="" class="form-control form-control-sm" placeholder="Mobile no." value="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputPosition4"></label>
                            <input type="text" name="emergency_address" class="form-control form-control-sm" placeholder="Address" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="inputName4">Date hired</label>
                            <input type="date" name="date_hired" id="" class="form-control form-control-sm" value="" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputName4">Rate per Hour</label>
                            <input type="number" name="rates" step=".0001" id="rates" class="form-control form-control-sm" placeholder="Rate per Hour" value="">
                        </div>
                       <div class="form-group col-md-4 form-check">
                            <input type="checkbox" name="allowances" id="allowances" onclick="myFunction()">
                            <label for="allowances" class="form-check-label text-danger" style="cursor: pointer;">
                                With Allowance?
                            </label>
                            <p id="text" style="display: none">
                            <input type="number" step=".0001" name="allowance" id="" placeholder="Per hour allowance" value="" class="form-control form-control-sm">
                            </p>
                        </div>
                    </div>
            </form>
<!--  -->
</div>
    </div>
</main>


<script>
    function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $('#blah').attr('src', e.target.result).width(120).height(100);
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
