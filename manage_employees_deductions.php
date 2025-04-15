<?php
session_start();

include_once("connection/cons.php");
$con = conns();

date_default_timezone_set("Asia/Manila");
$y_now = date("Y");
$m_now = date("m");
$d_now = date("j");


// employees info
$sql_emp = "SELECT * FROM employees order by lname,fname,mname ASC";
$dan_emp = $con->query($sql_emp) or die ($con->error);
$row_emp = $dan_emp->fetch_assoc();
$count = mysqli_num_rows($dan_emp); 

// header
include("includes/header.php");
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h4>Manage Deductions & Loans</h4>
      </div>

        <div class="container table-responsive">

                    <div class="row">
                        <div class="" style="width:40%;">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01" style="font-size: 0.7rem;">Department</label>
                                </div>
                                        <select class="custom-select"  id="select_dept" style="font-size: 0.7rem;">
                                        <option value="">Choose Department...</option>
                                        </select>                               
                            </div>
                        </div>
                        <div class="" style="width:40%;  padding: 0 10px ">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01" style="font-size: 0.7rem;">Position</label>
                                </div>
                                <select class="custom-select" id="select_position" style="font-size: 0.7rem;">
                                    <option value="">Choose Position...</option>
                                </select>
                            </div>
                        </div>
                        <div style="width:20%; text-align:right">
                            <button id="filter" class="btn btn-sm btn-info" style="font-size: 0.7rem;">Filter</button>
                            <button id="reset" class="btn btn-sm btn-warning" style="font-size: 0.7rem;">Reset Filter</button>
                        </div>
                    </div>

                <table id="deduct" class="table table-striped table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Name of Employee</th>
                            <th colspan="3">Countributions</th>
                            <th colspan="5">Loans</th>
                            <th colspan="3">Others</th>
                            <th rowspan="2">Start Period <br> Total</th>
                            <th rowspan="2">End Period <br> Total</th>
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
                    </tbody>
                </table>
            </div>
<!--  -->
</div>
    </div>
</main>

<script>

    // Fetch Department

        function fetch_dept() {
            $.ajax({
                url: "fetch_dept.php",
                type: "post",
                dataType: "json",
                success: function(data) {
                    var deptBody = "";
                    for (var key in data) {
                        deptBody += `<option value="${data[key]['dept']}">${data[key]['dept']}</option>`;
                    }
                    $("#select_dept").append(deptBody);
                }
            });
        }
        fetch_dept();

        // Fetch Position
        function fetch_position() {
            $.ajax({
                url: "fetch_position.php",
                type: "post",
                dataType: "json",
                success: function(data) {
                    var positionBody = "";
                    for (var key in data) {
                        positionBody += `<option value="${data[key]['pos']}">${data[key]['pos']}</option>`;
                    }
                    $("#select_position").append(positionBody);
                }
            });
        }
        fetch_position();

        // Fetch deduct

        function fetch_deduction(dept, position) {
        $.ajax({
            url: "recording_deductions.php",
            type: "post",
            data: {
                dept: dept,
                position: position
            },
            dataType: "json",
            success: function(data) {
                var i = 0;
                $('#deduct').DataTable({
                    "data": data,
                    // "responsive": true,
                    "aoColumnDefs": [{
                        // width: 300, targets: 3,
                        // "bSortable": false,
                        targets: [0,1], className: 'dt-left'
                        // "aTargets": [5]
                     }],
                        fixedColumns: {
                            left: 2,
                            right: 2},
                        pageLength : 5,
                        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                        scrollCollapse: true,
                        scrollX: true,
                        scrollY: 350
                    });
                }
            });
        }
        fetch_deduction();

        // Filter

        $(document).on("click", "#filter", function(e) {
        e.preventDefault();

        var dept = $("#select_dept").val();
        var position = $("#select_position").val();


        if (dept !== "" || position !== "") {
            $('#deduct').DataTable().destroy();
            fetch_deduction(dept, position);
        }else{
            $('#deduct').DataTable().destroy();
            fetch_deduction();
        }

        });

        // Reset Filter

        $(document).on("click", "#reset", function(e) {
            e.preventDefault();

            $("#select_dept").html(`<option value="">Choose Department...</option>`);
            $("#select_position").html(`<option value="">Choose Position...</option>`);

            // $('#deduct').DataTable().destroy();
            // fetch();
            fetch_dept();
            fetch_position();

            var dept = "";
            var position = "";

            $('#deduct').DataTable().destroy();
            fetch_deduction(dept, position);
        });

</script>


<script>

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
            $('#position').val(json.position);
            $('#dept').val(json.dept);
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
        <div class="form-row">
    `        <div class="form-group col-md-5">
                <label for="" >Position</label>
                <input type="text" class="form-control form-control-sm" id="position" placeholder="" disabled>
            </div>
            <div class="form-group col-md-6">
                <label for="" >Department</label>
                <input type="text" class="form-control form-control-sm" id="dept" placeholder="" disabled>
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


<?php
include("includes/footer.php");
?>
