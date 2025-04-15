<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");
$y_now = date("Y");
$m_now = date("m");
$d_now = date("j");


if(isset($_GET['Department'])){
    $dept = $_GET['Department'];
    $_SESSION['department'] = $_GET['Department'];
    $_SESSION['departmentsss'] = $dept;
    $dept = str_replace("'", "\'", $dept);
}elseif(isset($_SESSION['department']))
{
    $dept = $_SESSION['department'];
    $dept = $_SESSION['departmentsss'];
    $dept = str_replace("'", "\'", $dept);
}else{
    $dept = "";
}

// employees info
if(empty($dept)){
    $sql_emp = "SELECT * FROM employees order by lname,fname,mname ASC";
}else{
    $sql_emp = "SELECT * FROM employees WHERE payroll_dept = '$dept' order by lname,fname,mname ASC";
}
$dan_emp = $con->query($sql_emp) or die ($con->error);
$row_emp = $dan_emp->fetch_assoc();
$count = mysqli_num_rows($dan_emp); 

// departments
$sql_dept = "SELECT * FROM departments WHERE dept = '$dept'";
$dan_dept = $con->query($sql_dept) or die ($con->error);
$row_dept = $dan_dept->fetch_assoc();

$dept_name = $row_dept['dept'];

isset($_SESSION['Payroll']) ? $cut_off = $_SESSION['Payroll'].'s' : $cut_off = '';

// header
include("includes/header.php");
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h4>Manage Deductions & Loans</h4>
      </div>

        <div class="container table-responsive">
            <div class="form-row">
                    <div class="form-group col-md-4">
                        <span>Department: <b><?= $dept_name ?></b></span> 
                    </div>
                    <div class="form-group col-md-8 text-right">
                        <a href="payroll_create.php?Department=<?= $_SESSION['departmentsss'] ?>" class="btn btn-secondary btn-sm">Back</a>
                    </div>
            </div>

                <table id="deduct" class="dataTable table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Name of Employee</th>
                            <th colspan="3">Countributions</th>
                            <th colspan="5">Loans</th>
                            <th colspan="3">Others</th>
                            <th rowspan="2">Total Ded.</th>
                        </tr>
                        <tr>
                            <th>SSS</th>
                            <th>HDMF</th>
                            <th>P.H.</th>
                            <th>SSS Loans</th>
                            <th>SSS Calamity</th>
                            <th>HDMF Loans</th>
                            <th>HDMF Calamity</th>
                            <th>Salary Loans</th>
                            <th>ESF</th>
                            <th>A/R</th>
                            <th>Shortages</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($count < 1){
                                ?>
                                    <tr>
                                    </tr>
                                <?php
                            }else{
                                $no = 0;
                                do{
                                    $no++;

                                    $emp_ID = $row_emp['ID'];

                                    // deductions
                                    $sql_ded = "SELECT * FROM deductions where emp_id = '$emp_ID'";
                                    $dan_ded = $con->query($sql_ded) or die ($con->error);
                                    $row_ded = $dan_ded->fetch_assoc();
                                    $count_ded = mysqli_num_rows($dan_ded);

                                    ?>
                                        <tr>
                                            <td><?= $no?></td>
                                            <th style="text-align: left;">
                                            <strong>
                                            <a href=javascript:void(); data-id="<?= $row_emp['ID'] ?>" class="btn text-active btn-link deductionsbtn" >
                                                <b><?= $row_emp['lname'].', '.$row_emp['fname'].' '.substr($row_emp['mname'],0,1).' '.$row_emp['extname'] ?></b>
                                            </a>
                                            </strong>
                                            </th>
                                            <?php
                                                if($count_ded > 0)
                                                {
                                                    $sss = floatval($row_ded['sss']);
                                                    $pagibig = floatval($row_ded['pagibig']);
                                                    $ph = floatval($row_ded['philhealth']);
                                                    $sss_l = floatval($row_ded['sss_loan']);
                                                    $sss_c = floatval($row_ded['sss_calamity']);
                                                    $hdmf_l = floatval($row_ded['hdmf_loan']);
                                                    $hdmf_c = floatval($row_ded['hdmf_calamity']);
                                                    $salary_l = floatval($row_ded['salary_loan']);
                                                    $esf = floatval($row_ded['esf']);
                                                    $ar = floatval($row_ded['ar']);
                                                    $shortages = floatval($row_ded['shortages']);

                                                    $row_ded['sss_c'] == $cut_off || $row_ded['sss_c'] == 'Both' ? $sss = $sss : $sss = 0;
                                                    $row_ded['pagibig_c'] == $cut_off || $row_ded['pagibig_c'] == 'Both' ? $pagibig = $pagibig : $pagibig = 0;
                                                    $row_ded['philhealth_c'] == $cut_off || $row_ded['philhealth_c'] == 'Both' ? $ph = $ph : $ph = 0;
                                                    $row_ded['sss_loan_c'] == $cut_off || $row_ded['sss_loan_c'] == 'Both' ? $sss_l = $sss_l : $sss_l = 0;
                                                    $row_ded['sss_calamity_c'] == $cut_off || $row_ded['sss_calamity_c'] == 'Both' ? $sss_c = $sss_c : $sss_c = 0;
                                                    $row_ded['hdmf_loan_c'] == $cut_off || $row_ded['hdmf_loan_c'] == 'Both' ? $hdmf_l = $hdmf_l : $hdmf_l = 0;
                                                    $row_ded['hdmf_calamity_c'] == $cut_off || $row_ded['hdmf_calamity_c'] == 'Both' ? $hdmf_c = $hdmf_c : $hdmf_c = 0;
                                                    $row_ded['salary_loan_c'] == $cut_off || $row_ded['salary_loan_c'] == 'Both' ? $salary_l = $salary_l : $salary_l = 0;
                                                    $row_ded['esf_c'] == $cut_off || $row_ded['esf_c'] == 'Both' ? $esf = $esf : $esf = 0;
                                                    $row_ded['ar_c'] == $cut_off || $row_ded['ar_c'] == 'Both' ? $ar = $ar : $ar = 0;
                                                    $row_ded['shortages_c'] == $cut_off || $row_ded['shortages_c'] == 'Both' ? $shortages = $shortages : $shortages = 0;

                                                    $total = $sss + $pagibig + $ph + $sss_l + $sss_c + $hdmf_l + $hdmf_c + $salary_l + $esf + $ar + $shortages;
                                                    ?>
                                                        <td <?= $row_ded['sss_c'] == $cut_off || $row_ded['sss_c'] == 'Both' ? '' : 'class="text-black-50"' ?>><?= $row_ded['sss']?></td>
                                                        <td <?= $row_ded['pagibig_c'] == $cut_off || $row_ded['pagibig_c'] == 'Both' ? '' : 'class="text-black-50"'?>><?= $row_ded['pagibig']?></td>
                                                        <td <?= $row_ded['philhealth_c'] == $cut_off || $row_ded['philhealth_c'] == 'Both' ? '' : 'class="text-black-50"'?>><?= $row_ded['philhealth']?></td>
                                                        <td <?= $row_ded['sss_loan_c'] == $cut_off || $row_ded['sss_loan_c'] == 'Both' ? '' : 'class="text-black-50"'?>><?= $row_ded['sss_loan']?></td>
                                                        <td <?= $row_ded['sss_calamity_c'] == $cut_off || $row_ded['sss_calamity_c'] == 'Both' ? '' : 'class="text-black-50"'?>><?= $row_ded['sss_calamity']?></td>
                                                        <td <?= $row_ded['hdmf_loan_c'] == $cut_off || $row_ded['hdmf_loan_c'] == 'Both' ? '' : 'class="text-black-50"'?>><?= $row_ded['hdmf_loan']?></td>
                                                        <td <?= $row_ded['hdmf_calamity_c'] == $cut_off || $row_ded['hdmf_calamity_c'] == 'Both' ? '' : 'class="text-black-50"'?>><?= $row_ded['hdmf_calamity']?></td>
                                                        <td <?= $row_ded['salary_loan_c'] == $cut_off || $row_ded['salary_loan_c'] == 'Both' ? '' : 'class="text-black-50"'?>><?= $row_ded['salary_loan']?></td>
                                                        <td <?= $row_ded['esf_c'] == $cut_off || $row_ded['esf_c'] == 'Both' ? '' : 'class="text-black-50"'?>><?= $row_ded['esf']?></td>
                                                        <td <?= $row_ded['ar_c'] == $cut_off || $row_ded['ar_c'] == 'Both' ? '' : 'class="text-black-50"'?>><?= $row_ded['ar']?></td>
                                                        <td <?= $row_ded['shortages_c'] == $cut_off || $row_ded['shortages_c'] == 'Both' ? '' : 'class="text-black-50"'?>><?= $row_ded['shortages']?></td>
                                                    <?php
                                                }else{
                                                    ?>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    <?php
                                                    $total = '0.00';
                                                }
                                            ?>
                                            <td><b><?= number_format($total,2)?></b></td>
                                        </tr>
                                    <?php
                                }while($row_emp = $dan_emp->fetch_assoc());
                            }
                        ?>
                    </tbody>
                </table>
            </div>
<!--  -->
</div>
    </div>
</main>

<?php
include("includes/footer.php");
?>


<script>

    let table = new DataTable('#deduct',{
        fixedColumns: {
        left: 2,
        right: 1
        },
        // scrollCollapse: true,
        scrollX: true
        // scrollY: 500
    });

    // Display Deductions Modal
    $('#deduct').on('click', '.deductionsbtn', function(event) {
    var table = $('#deduct').DataTable();
    var trid = $(this).closest('tr').attr('id');
        //console.log(selectedRow);
    var id = $(this).data('id');
    $('#deductionModal').modal('show');

    $.ajax({
        url: "get_single_employees_deductions.php",
        data: {
        id: id
        },
        type: 'post',
        success: function(data) {
        var json = JSON.parse(data);
            $('#fullname').val(json.fullname);
            $('#sss').val(json.sss);
            $('#pagibig').val(json.pagibig);
            $('#philhealth').val(json.philhealth);
            $('#sss_loan').val(json.sss_loan);
            $('#sss_calamity').val(json.sss_calamity);
            $('#hdmf_loan').val(json.hdmf_loan);
            $('#hdmf_calamity').val(json.hdmf_calamity);
            $('#salary_loan').val(json.salary_loan);
            $('#esf').val(json.esf);
            $('#ar').val(json.ar);
            $('#shortages').val(json.shortages);
            $('#id').val(id);
            $('#emp_ids').val(id);
        }
    });
    });
</script>

<!-- deductions -->
<div class="modal fade" id="deductionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title fs-5" id="exampleModalLabel">Manage Deductions</h2>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="ded_submit.php" method="post">
        <div class="form-group row">
            <label for="dated_a" class="col-sm-3 col-form-label">Employee's Name</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="fullname" placeholder="" disabled>
                </div>
        </div>
        <div class="form-row text-center">
            <div class="form-group col-md-">
                <strong>Countributions</strong>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="" >SSS Contribution</label>
                <input type="number" name="sss" id="sss" class="form-control form-control-sm" step=".0001">
            </div>
            <div class="form-group col-md-4">
                <label for="" >HDMF Contribution</label>
                <input type="number" name="pagibig" id="pagibig" class="form-control form-control-sm" step=".0001">
            </div>
            <div class="form-group col-md-4">
                <label for="" >Philhealth Contribution</label>
                <input type="number" name="philhealth" id="philhealth" class="form-control form-control-sm" step=".0001">
            </div>
        </div>

        <div class="form-row text-center">
            <div class="form-group col-md-">
                <strong>Loans</strong>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="" >SSS Loan</label>
                <input type="number" name="sss_loan" id="sss_loan" class="form-control form-control-sm" step=".0001">
            </div>
            <div class="form-group col-md-3">
                <label for="" >SSS Calamity</label>
                <input type="number" name="sss_calamity" id="sss_calamity" class="form-control form-control-sm" step=".0001">
            </div>
            <div class="form-group col-md-3">
                <label for="" >HDMF Loan</label>
                <input type="number" name="hdmf_loan" id="hdmf_loan" class="form-control form-control-sm" step=".0001">
            </div>
            <div class="form-group col-md-3">
                <label for="" >HDMF Calamity</label>
                <input type="number" name="hdmf_calamity" id="hdmf_calamity" class="form-control form-control-sm" step=".0001">
            </div>
        </div>

        <div class="form-row text-center">
            <div class="form-group col-md-">
                <strong>Others</strong>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="" >Salary Loan</label>
                <input type="number" name="salary_loan" id="salary_loan" class="form-control form-control-sm" step=".0001">
            </div>
            <div class="form-group col-md-3">
                <label for="" >ESF</label>
                <input type="number" name="esf" id="esf" class="form-control form-control-sm" step=".0001">
            </div>
            <div class="form-group col-md-3">
                <label for="" >A/R</label>
                <input type="number" name="ar" id="ar" class="form-control form-control-sm" step=".0001">
            </div>
            <div class="form-group col-md-3">
                <label for="" >Shortages</label>
                <input type="number" name="shortages" id="shortages" class="form-control form-control-sm" step=".0001">
            </div>
        </div>

            <div class="mb-3" style="text-align: right;">
                <input type="submit" class="btn btn-primary btn-sm" value="Save Changes" name="changes">
            </div>
                <input type="hidden" name="emp_ids" id="emp_ids" value="">
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
