<?php
session_start();

include_once("connection/cons.php");
$con = conns();

if(isset($_GET['ID']))
{
    $emp_ids = $_GET['ID'];
    $_SESSION['emp_id'] = $emp_ids;
}else{
    $emp_ids= $_SESSION['emp_id'];
}

// employees info
$sql_emp = "SELECT * FROM employees WHERE ID = '$emp_ids'";
$dan_emp = $con->query($sql_emp) or die ($con->error);
$row_emp = $dan_emp->fetch_assoc();
$count_emp = mysqli_num_rows($dan_emp);

// deduction
$sql_deds = "SELECT * FROM deductions WHERE emp_id = '$emp_ids'";
$dan_deds = $con->query($sql_deds) or die ($con->error);
$row_deds = $dan_deds->fetch_assoc();
$count_deds = mysqli_num_rows($dan_deds);

if($count_deds > 0)
{
    $ar = $row_deds['ar'];
    $sss = $row_deds['sss'];
    $pagibig = $row_deds['pagibig'];
    $philhealth = $row_deds['philhealth'];
    $esf = $row_deds['esf'];
    $salary_loan = $row_deds['salary_loan'];
    $sss_loan = $row_deds['sss_loan'];
    $sss_calamity = $row_deds['sss_calamity'];
    $hdmf_loan = $row_deds['hdmf_loan'];
    $hdmf_calamity = $row_deds['hdmf_calamity'];
    $shortages = $row_deds['shortages'];
    $ar_c = $row_deds['ar_c'];
    $sss_c = $row_deds['sss_c'];
    $pagibig_c = $row_deds['pagibig_c'];
    $philhealth_c = $row_deds['philhealth_c'];
    $esf_c = $row_deds['esf_c'];
    $salary_loan_c = $row_deds['salary_loan_c'];
    $sss_loan_c = $row_deds['sss_loan_c'];
    $sss_calamity_c = $row_deds['sss_calamity_c'];
    $hdmf_loan_c = $row_deds['hdmf_loan_c'];
    $hdmf_calamity_c = $row_deds['hdmf_calamity_c'];
    $shortages_c = $row_deds['shortages_c'];
}else{
    $ar = '';
    $sss = '';
    $pagibig = '';
    $philhealth = '';
    $esf = '';
    $salary_loan = '';
    $sss_loan = '';
    $sss_calamity = '';
    $hdmf_loan = '';
    $hdmf_calamity = '';
    $shortages = '';
    $ar_c = '';
    $sss_c = '';
    $pagibig_c = '';
    $philhealth_c = '';
    $esf_c = '';
    $salary_loan_c = '';
    $sss_loan_c = '';
    $sss_calamity_c = '';
    $hdmf_loan_c = '';
    $hdmf_calamity_c = '';
    $shortages_c = '';
}

if($count_emp > 0){
    $id = $row_emp['emp_id'];
    $emp_name = $row_emp['lname'].', '.$row_emp['fname'].' '.$row_emp['mname'].' '.$row_emp['extname'];
    $position = $row_emp['position'];
}else{
    $id = '-';
    $emp_name = '-';
    $position = '-';    
}

// header
include("includes/header.php");
include("includes/menus.php");
?>

<div class="containers">
    <h3>Manage Deductions & Loans</h3>
        <div class="contents">
            <div class="emp_ded">
                <form action="ded_submit.php" method="post">
                    <h4>Employee's Information</h4>
                        <!-- <span>Employee ID: <?php echo $id?></span>
                        <br>     -->
                        <span>Name: <?php echo $emp_name ?></span>
                        <br>
                        <span>Position: <?php echo $position ?></span>

                        <table class="dataTable table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th colspan="2" style="text-align: center;">Contributions</th>
                                    <th colspan="2" style="text-align: center;">Cut-off Period Apply</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>SSS</td>
                                    <td><input type="number" class="form-control form-control-sm" name="sss" step=".0001" id="" value="<?php echo $sss ?>"></td>
                                    <td><input type="checkbox" class="form-check-input" name="sss_c[]" value="Starts" id="1a" 
                                        <?php echo($sss_c == 'Both' || $sss_c == 'Starts' || empty($sss_c))? 'checked' : '' ?> disabled><label class="form-check-label" for="1a">Start</label></td>
                                    <td><input type="checkbox" class="form-check-input" name="sss_c[]" value="Ends" id="1b"
                                        <?php echo($sss_c == 'Both' || $sss_c == 'Ends')? 'checked' : '' ?> disabled><label class="form-check-label" for="1b">End</label></td>
                                </tr>
                                <tr>
                                    <td>HDMF</td>
                                    <td><input type="number" class="form-control form-control-sm" name="pagibig" step=".0001" id="" value="<?php echo $pagibig ?>"></td>
                                    <td><input type="checkbox" class="form-check-input" name="pagibig_c[]" value="Starts" id="2a"
                                        <?php echo($pagibig_c == 'Both' || $pagibig_c == 'Starts' || empty($pagibig_c))? 'checked' : '' ?> disabled><label class="form-check-label" for="2a">Start</label></td>
                                    <td><input type="checkbox" class="form-check-input" name="pagibig_c[]" value="Ends" id="2b"
                                        <?php echo($pagibig_c == 'Both' || $pagibig_c == 'Ends')? 'checked' : '' ?> disabled><label class="form-check-label" for="2b">End</label></td>
                                </tr>
                                <tr>
                                    <td>Philhealth</td>
                                    <td><input type="number" class="form-control form-control-sm" name="philhealth" step=".0001" id="" value="<?php echo $philhealth ?>"></td>
                                    <td><input type="checkbox" class="form-check-input" name="philhealth_c[]" value="Starts" id="3a"
                                        <?php echo($philhealth_c == 'Both' || $philhealth_c == 'Starts' || empty($philhealth_c))? 'checked' : '' ?> disabled><label class="form-check-label" for="3a">Start</label></td>
                                    <td><input type="checkbox" class="form-check-input" name="philhealth_c[]" value="Ends" id="3b"
                                        <?php echo($philhealth_c == 'Both' || $philhealth_c == 'Ends')? 'checked' : '' ?> disabled><label class="form-check-label" for="3b">End</label></td>
                                </tr>
                                <tr>
                                    <th colspan="2">Loans</th>  
                                    <th colspan="2"></th>
                                </tr>
                                <tr>
                                    <td>SSS Loans</td>
                                    <td><input type="number" class="form-control form-control-sm" name="sss_loan" step=".0001" id="" value="<?php echo $sss_loan ?>"></td>
                                    <td><input type="checkbox" class="form-check-input" name="sss_loan_c[]" value="Starts" id="4a"
                                        <?php echo($sss_loan_c == 'Both' || $sss_loan_c == 'Starts')? 'checked' : '' ?> disabled><label class="form-check-label" for="4a">Start</label></td>
                                    <td><input type="checkbox" class="form-check-input" name="sss_loan_c[]" value="Ends" id="4b"
                                        <?php echo($sss_loan_c == 'Both' || $sss_loan_c == 'Ends' || empty($sss_loan_c))? 'checked' : '' ?> disabled><label class="form-check-label" for="4b">End</label></td>
                                </tr>
                                <tr>
                                    <td>SSS Calamity</td>
                                    <td><input type="number" class="form-control form-control-sm" name="sss_calamity" step=".0001" id="" value="<?php echo $sss_calamity ?>"></td>
                                    <td><input type="checkbox" class="form-check-input" name="sss_calamity_c[]" value="Starts" id="5a"
                                        <?php echo($sss_calamity_c == 'Both' || $sss_calamity_c == 'Starts')? 'checked' : '' ?> disabled><label class="form-check-label" for="5a">Start</label></td>
                                    <td><input type="checkbox" class="form-check-input" name="sss_calamity_c[]" value="Ends" id="5b"
                                        <?php echo($sss_calamity_c == 'Both' || $sss_calamity_c == 'Ends' || empty($sss_calamity_c))? 'checked' : '' ?> disabled><label class="form-check-label" for="5b">End</label></td>
                                </tr>
                                <tr>
                                    <td>HDMF Loans</td>
                                    <td><input type="number" class="form-control form-control-sm" name="hdmf_loan" step=".0001" id="" value="<?php echo $hdmf_loan ?>"></td>
                                    <td><input type="checkbox" class="form-check-input" name="hdmf_loan_c[]" value="Starts" id="6a"
                                        <?php echo($hdmf_loan_c == 'Both' || $hdmf_loan_c == 'Starts')? 'checked' : '' ?> disabled><label class="form-check-label" for="6a">Start</label></td>
                                    <td><input type="checkbox" class="form-check-input" name="hdmf_loan_c[]" value="Ends" id="6b"
                                        <?php echo($hdmf_loan_c == 'Both' || $hdmf_loan_c == 'Ends' || empty($hdmf_loan_c))? 'checked' : '' ?> disabled><label class="form-check-label" for="6b">End</label></td>
                                </tr>
                                <tr>
                                    <td>HDMF Calamity</td>
                                    <td><input type="number" class="form-control form-control-sm" name="hdmf_calamity" step=".0001" id="" value="<?php echo $hdmf_calamity ?>"></td>
                                    <td><input type="checkbox" class="form-check-input" name="hdmf_calamity_c[]" value="Starts" id="7a"
                                        <?php echo($hdmf_calamity_c == 'Both' || $hdmf_calamity_c == 'Starts')? 'checked' : '' ?> disabled><label class="form-check-label" for="7a">Start</label></td>
                                    <td><input type="checkbox" class="form-check-input" name="hdmf_calamity_c[]" value="Ends" id="7b"
                                        <?php echo($hdmf_calamity_c == 'Both' || $hdmf_calamity_c == 'Ends' || empty($hdmf_calamity_c))? 'checked' : '' ?> disabled><label class="form-check-label" for="7b">End</label></td>
                                </tr>
                                <tr>
                                    <td>Salary Loans</td>
                                    <td><input type="number" class="form-control form-control-sm" name="salary_loan" step=".0001" id="" value="<?php echo $salary_loan ?>"></td>
                                    <td><input type="checkbox" class="form-check-input" name="salary_loan_c[]" value="Starts" id="8a"
                                        <?php echo($salary_loan_c == 'Both' || $salary_loan_c == 'Starts' || empty($salary_loan_c))? 'checked' : '' ?> disabled><label class="form-check-label" for="8a">Start</label></td>
                                    <td><input type="checkbox" class="form-check-input" name="salary_loan_c[]" value="Ends" id="8b"
                                        <?php echo($salary_loan_c == 'Both' || $salary_loan_c == 'Ends' || empty($salary_loan_c))? 'checked' : '' ?> disabled><label class="form-check-label" for="8b">End</label></td>
                                </tr>
                                <tr>
                                    <th colspan="2">Other Deductions</th>
                                    <th colspan="2"></th>
                                </tr>
                                <tr>
                                    <td>E.S.F.</td>
                                    <td><input type="number" class="form-control form-control-sm" name="esf" step=".0001" id="" value="<?php echo $esf ?>"></td>
                                    <td><input type="checkbox" class="form-check-input" name="esf_c[]" value="Starts" id="9a"
                                        <?php echo($esf_c == 'Both' || $esf_c == 'Starts' || empty($esf_c))? 'checked' : '' ?> disabled><label class="form-check-label" for="9a">Start</label></td>
                                    <td><input type="checkbox" class="form-check-input" name="esf_c[]" value="Ends" id="9b"
                                        <?php echo($esf_c == 'Both' || $esf_c == 'Ends' || empty($esf_c))? 'checked' : '' ?> disabled><label class="form-check-label" for="9b">End</label></td>
                                </tr>
                                <tr>
                                    <td>A/R</td>
                                    <td><input type="number" class="form-control form-control-sm" name="ar" step=".0001" id="" value="<?php echo $ar ?>"></td>
                                    <td><input type="checkbox" class="form-check-input" name="ar_c[]" value="Starts" id="10a"
                                        <?php echo($ar_c == 'Both' || $ar_c == 'Starts' || empty($ar_c))? 'checked' : '' ?> disabled><label class="form-check-label" for="10a">Start</label></td>
                                    <td><input type="checkbox" class="form-check-input" name="ar_c[]" value="Ends" id="10b"
                                        <?php echo($ar_c == 'Both' || $ar_c == 'Ends' || empty($ar_c))? 'checked' : '' ?> disabled><label class="form-check-label" for="10b">End</label></td>
                                </tr>
                                <tr>
                                    <td>Shortages</td>
                                    <td><input type="number" class="form-control form-control-sm" name="shortages" step=".0001" id="" value="<?php echo $shortages ?>"></td>
                                    <td><input type="checkbox" class="form-check-input" name="shortages_c[]" value="Starts" id="11a"
                                        <?php echo($shortages_c == 'Both' || $shortages_c == 'Starts' || empty($shortages))? 'checked' : '' ?> disabled><label class="form-check-label" for="11a">Start</label></td>
                                    <td><input type="checkbox" class="form-check-input" name="shortages_c[]" value="Ends" id="11b"
                                        <?php echo($shortages_c == 'Both' || $shortages_c == 'Ends' || empty($shortages))? 'checked' : '' ?> disabled><label class="form-check-label" for="11b">End</label></td>
                                </tr>
                            </tbody>
                        </table>
                        <div style="text-align: right;">
                            <a href="<?= $_SERVER["HTTP_REFERER"]?>" class="btn btn-secondary btn-sm">Back</a>
                            <input type="submit" value="Save Changes" class="btn btn-primary btn-sm" name="submit_changes">
                        </div>
                </form>
            </div>
        </div>
</div>